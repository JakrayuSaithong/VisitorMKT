<?php
/**
 * API: visitor_acknowledge.php
 * รองรับ actions: get_status, acknowledge, close_job
 * 
 * Division Codes:
 * - TC = 54
 * - QC = 87
 * - PRD = 83
 * - สื่อสาร = 63
 * - HR = 31
 * - วางแผน = 61
 * - ขนส่ง = 65
 */

include_once '../config/base.php';
include_once '../config/function.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$visitorFormId = $_POST['visitor_form_id'] ?? $_GET['visitor_form_id'] ?? null;
$userCode = $_SESSION['VisitorMKT_code'] ?? 'SYSTEM';
$userPerm = $_SESSION['VisitorMKT_permision'] ?? '';
$userDivision = $_SESSION['DivisionHead1'] ?? 0;

// ฝ่ายที่ต้องรับทราบ
$DIVISIONS = [
    54 => 'TC',
    87 => 'QC',
    83 => 'PRD',
    63 => 'สื่อสาร',
    31 => 'HR',
    61 => 'วางแผน',
    65 => 'ขนส่ง'
];

function isAdmin($perm) {
    return $perm === 'Admin' || (is_array($perm) && in_array('Admin', $perm));
}

// ===== FAT helpers =====
function genFatCode() {
    global $konnext_fat;
    $sql = "SELECT SUBSTRING(MAX(fat_projectcode), 9, 4) AS lastdocno
            FROM fat_projectmaster
            WHERE CONVERT(DateCreate, DATE) BETWEEN '" . date('Y-m-01') . "' AND '" . date('Y-m-t') . "'
            LIMIT 1";
    $q = mysqli_query($konnext_fat, $sql);
    $prefix = "FAT-" . substr((string)(date('Y') + 543), 2, 2) . date('m');
    if ($q && mysqli_num_rows($q) > 0) {
        $r = mysqli_fetch_assoc($q);
        $next = (int)($r['lastdocno'] ?? 0) + 1;
        return $prefix . sprintf('%04d', $next);
    }
    return $prefix . '0001';
}

function insertFatFromVisitorForm($visitorFormId) {
    global $konnext_DB64, $konnext_DB64_ASF7, $konnext_fat;

    // Fetch SalesDetail from VisitorForm
    $sql = "SELECT SalesDetail FROM VisitorForm WHERE Id = ?";
    $stmt = sqlsrv_query($konnext_DB64, $sql, [$visitorFormId]);
    if (!$stmt) return [];
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    if (!$row || empty($row['SalesDetail'])) return [];

    $salesDetail = json_decode($row['SalesDetail'], true);
    if (!is_array($salesDetail)) return [];

    $createdCodes = [];

    foreach ($salesDetail as $job) {
        $jobItems = $job['JobItems'] ?? [];
        // fallback: single job stored without JobItems array
        if (empty($jobItems) && !empty($job['JobNo'])) {
            $jobItems = [['JobNo' => $job['JobNo'], 'ProjectName' => $job['ProjectName'] ?? '']];
        }
        if (empty($jobItems)) continue;

        $tcName       = $job['TCName'] ?? '';
        $requesterCode = $job['RequesterCode'] ?? '';
        $fat_usersend = implode(',', array_filter([$requesterCode, $tcName]));

        // ProductName stored as JSON string e.g. '["AMD","IN"]'
        $productNameRaw = $job['ProductName'] ?? '';
        if (is_string($productNameRaw) && (strpos($productNameRaw, '[') === 0 || strpos($productNameRaw, '{') === 0)) {
            $productArr = json_decode($productNameRaw, true);
            $product_type = is_array($productArr) ? implode(',', $productArr) : $productNameRaw;
        } elseif (is_array($productNameRaw)) {
            $product_type = implode(',', $productNameRaw);
        } else {
            $product_type = $productNameRaw;
        }

        $detail = $job['Detail'] ?? '';

        foreach ($jobItems as $item) {
            $jobNo       = $item['JobNo'] ?? '';
            $projectName = $item['ProjectName'] ?? '';
            if (empty($jobNo)) continue;

            // Query 1: DO document → owner, sale name, job value
            $sql1 = "SELECT
                         cust.Thai_Name                   AS CustomerName,
                         SAL.[Name] + ' ' + SAL.Surname   AS SaleName,
                         FORMAT(ts.Bill_Amount, 'F2')      AS BillAmount
                     FROM  [cd-XPSQL-ASF7].dbo.Transection1   ts
                     LEFT OUTER JOIN [cd-XPSQL-ASF7].dbo.Contact   cust ON cust.Contact_ID = ts.Customer_ID
                     LEFT OUTER JOIN [cd-XPSQL-ASF7].dbo.Man_Info  SAL  ON SAL.ManID       = ts.MAN_ID
                     WHERE ts.Doc_Type = 'DO'
                       AND ts.Del      <> 'Y'
                       AND ts.Doc_No   = ?
                     GROUP BY cust.Thai_Name, SAL.[Name] + ' ' + SAL.Surname,
                              ts.Other, ts.TotalPrice, ts.Bill_Amount, ts.Users";
            $stmt1 = sqlsrv_query($konnext_DB64_ASF7, $sql1, [$jobNo]);
            $q1 = ($stmt1) ? sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC) : null;
            if ($stmt1) sqlsrv_free_stmt($stmt1);

            $fat_owner = $q1['CustomerName'] ?? '';
            $salename  = $q1['SaleName']     ?? '';
            $jobvalue  = $q1['BillAmount']   ?? '';

            // Query 2: WA documents → wa_no
            $sql2 = "SELECT Doc_No
                     FROM [cd-XPSQL-ASF7].dbo.Transection
                     WHERE Doc_Type = 'WA'
                       AND PO_No   = ?
                     GROUP BY Doc_No, Lot_No";
            $stmt2 = sqlsrv_query($konnext_DB64_ASF7, $sql2, [$jobNo]);
            $wa_parts = [];
            if ($stmt2) {
                while ($r = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                    $wa_parts[] = $r['Doc_No'];
                }
                sqlsrv_free_stmt($stmt2);
            }
            $wa_no = implode(',', $wa_parts);

            // Query 3: Serial numbers → SN_no
            $sql3 = "SELECT PartNo
                     FROM [ASF_VIEW].dbo.vw_SerialServiceJob
                     WHERE job_no = ?
                     GROUP BY PartNo";
            $stmt3 = sqlsrv_query($konnext_DB64_ASF7, $sql3, [$jobNo]);
            $sn_parts = [];
            if ($stmt3) {
                while ($r = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
                    $sn_parts[] = $r['PartNo'];
                }
                sqlsrv_free_stmt($stmt3);
            }
            $sn_no = implode(',', $sn_parts);

            // Generate FAT code and insert into MySQL fat_projectmaster
            $fat_projectcode = genFatCode();

            $e = function($v) use ($konnext_fat) { return mysqli_real_escape_string($konnext_fat, (string)$v); };

            $insertSql = "INSERT INTO fat_projectmaster (
                fat_projectcode, devitioncode, devitionname, fat_projectname,
                fat_owner, owner_contact, fat_consult, fat_contractor, designer,
                job_No, requireDate, salename, jobvalue, SN_no, wa_no,
                product_type, fattest_date, status_code, fat_remark, fat_usersend,
                DateCreate, CreateUser, CreateUserTH, LastUpdate, UserUpdate, Date_updatetxt
            ) VALUES (
                '" . $e($fat_projectcode) . "',
                '" . $e($_SESSION['DivisionCode'] ?? '') . "',
                '" . $e($_SESSION['DivisionHead2'] ?? '') . "',
                '" . $e($projectName) . "',
                '" . $e($fat_owner) . "',
                '', '', '', '',
                '" . $e($jobNo) . "',
                NULL,
                '" . $e($salename) . "',
                '" . $e($jobvalue) . "',
                '" . $e($sn_no) . "',
                '" . $e($wa_no) . "',
                '" . $e($product_type) . "',
                now(), '1',
                '" . $e($detail) . "',
                '" . $e($fat_usersend) . "',
                now(),
                '" . $e($_SESSION['VisitorMKT_code'] ?? '') . "',
                '" . $e($_SESSION['VisitorMKT_name'] ?? '') . "',
                now(),
                '" . $e($_SESSION['VisitorMKT_code'] ?? '') . "',
                now()
            )";

            if (mysqli_query($konnext_fat, $insertSql)) {
                $createdCodes[] = $fat_projectcode;
            }
            // $createdCodes[] = $insertSql;
        }
    }

    return $createdCodes;
}

// ===== GET_STATUS: ดึงสถานะรับทราบทั้งหมด =====
if ($action === 'get_status') {
    if (!$visitorFormId) {
        echo json_encode(["status" => false, "message" => "Missing visitor_form_id"]);
        exit;
    }

    $sql = "SELECT DivisionCode, IsAcknowledged, AcknowledgeData FROM VisitorAcknowledg WHERE VisitorFormId = ?";
    $stmt = sqlsrv_query($konnext_DB64, $sql, [$visitorFormId]);

    if (!$stmt) {
        echo json_encode(["status" => false, "message" => print_r(sqlsrv_errors(), true)]);
        exit;
    }

    $result = [];
    $acknowledged = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $ackData = null;
        if (!empty($row['AcknowledgeData'])) {
            $ackData = json_decode($row['AcknowledgeData'], true);
        }
        // Lookup FullName from user code
        $userName = null;
        if (!empty($ackData['user'])) {
            $userData = mydata($ackData['user']);
            $userName = $userData['FullName'] ?? $ackData['user'];
        }
        $acknowledged[$row['DivisionCode']] = [
            'is_acknowledged' => (bool)$row['IsAcknowledged'],
            'user' => $userName,
            'datetime' => $ackData['datetime'] ?? null
        ];
    }

    $allAcknowledged = true;
    foreach ($DIVISIONS as $code => $name) {
        $ack = $acknowledged[$code] ?? ['is_acknowledged' => false, 'user' => null, 'datetime' => null];
        $result[] = [
            'division_code' => $code,
            'division_name' => $name,
            'is_acknowledged' => $ack['is_acknowledged'],
            'user' => $ack['user'],
            'datetime' => $ack['datetime']
        ];
        if (!$ack['is_acknowledged']) {
            $allAcknowledged = false;
        }
    }

    echo json_encode([
        "status" => true,
        "data" => $result,
        "all_acknowledged" => $allAcknowledged
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ===== ACKNOWLEDGE: บันทึกการรับทราบ =====
if ($action === 'acknowledge') {
    $divisionCode = $_POST['division_code'] ?? null;

    if (!$visitorFormId || !$divisionCode) {
        echo json_encode(["status" => false, "message" => "Missing required parameters"]);
        exit;
    }

    // ตรวจสอบสิทธิ์
    if (!isAdmin($userPerm) && (int)$userDivision !== (int)$divisionCode) {
        echo json_encode(["status" => false, "message" => "ไม่มีสิทธิ์รับทราบในนามฝ่ายนี้"]);
        exit;
    }

    // ตรวจสอบว่ามี record อยู่แล้วหรือไม่
    $checkSql = "SELECT Id, IsAcknowledged FROM VisitorAcknowledg WHERE VisitorFormId = ? AND DivisionCode = ?";
    $checkStmt = sqlsrv_query($konnext_DB64, $checkSql, [$visitorFormId, $divisionCode]);
    $existing = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);

    if ($existing && $existing['IsAcknowledged']) {
        echo json_encode(["status" => false, "message" => "ฝ่ายนี้รับทราบแล้ว"]);
        exit;
    }

    // Lookup FullName for storage
    $userData = mydata($userCode);
    $userFullName = $userData['FullName'] ?? $userCode;
    
    $ackData = json_encode([
        'user' => $userCode,
        'user_name' => $userFullName,
        'datetime' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);

    if ($existing) {
        // Update
        $updateSql = "UPDATE VisitorAcknowledg SET IsAcknowledged = 1, AcknowledgeData = ? WHERE Id = ?";
        $updateStmt = sqlsrv_query($konnext_DB64, $updateSql, [$ackData, $existing['Id']]);
    } else {
        // Insert
        $insertSql = "INSERT INTO VisitorAcknowledg (VisitorFormId, DivisionCode, IsAcknowledged, AcknowledgeData) VALUES (?, ?, 1, ?)";
        $insertStmt = sqlsrv_query($konnext_DB64, $insertSql, [$visitorFormId, $divisionCode, $ackData]);
    }

    // QC (87) รับทราบ → สร้าง FAT records จาก SalesDetail
    $fatCodes = [];
    $fatWarning = null;
    if ((int)$divisionCode === 87 || isAdmin($userPerm)) {
        ob_start();
        $fatCodes = insertFatFromVisitorForm($visitorFormId);
        $captured = ob_get_clean();
        if (!empty(trim($captured))) {
            $fatWarning = 'เกิดข้อผิดพลาดขณะสร้าง FAT: ' . trim(strip_tags($captured));
        }
    }

    // ตรวจสอบว่ารับทราบครบหรือยัง
    $countSql = "SELECT COUNT(*) as acked FROM VisitorAcknowledg WHERE VisitorFormId = ? AND IsAcknowledged = 1";
    $countStmt = sqlsrv_query($konnext_DB64, $countSql, [$visitorFormId]);
    $countRow = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);
    $ackedCount = (int)$countRow['acked'];
    $allAcknowledged = ($ackedCount >= count($DIVISIONS));

    // ถ้ายังไม่ครบ และ Status ยังเป็น 3 ให้เปลี่ยนเป็น 5
    $formSql = "SELECT Status FROM VisitorForm WHERE Id = ?";
    $formStmt = sqlsrv_query($konnext_DB64, $formSql, [$visitorFormId]);
    $formRow = sqlsrv_fetch_array($formStmt, SQLSRV_FETCH_ASSOC);

    if ($formRow && (int)$formRow['Status'] === 3) {
        $updateStatusSql = "UPDATE VisitorForm SET Status = 5, EditAt = GETDATE() WHERE Id = ?";
        sqlsrv_query($konnext_DB64, $updateStatusSql, [$visitorFormId]);
    }

    $resp = [
        "status"          => true,
        "message"         => $allAcknowledged ? "รับทราบครบทุกฝ่ายแล้ว" : "รับทราบเรียบร้อย",
        "all_acknowledged" => $allAcknowledged,
    ];
    if (!empty($fatCodes)) {
        $resp["fat_codes"] = $fatCodes;
    }
    if ($fatWarning) {
        $resp["fat_warning"] = $fatWarning;
    }
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    exit;
}

// ===== CLOSE_JOB: ปิดงาน =====
if ($action === 'close_job') {
    if (!$visitorFormId) {
        echo json_encode(["status" => false, "message" => "Missing visitor_form_id"]);
        exit;
    }

    // ตรวจสอบสิทธิ์ (เฉพาะ MKT หรือ Admin)
    if (!isAdmin($userPerm) && (int)$userDivision !== 63) {
        echo json_encode(["status" => false, "message" => "ไม่มีสิทธิ์ปิดงาน (เฉพาะ MKT หรือ Admin)"]);
        exit;
    }

    // Determine which divisions are required based on form data
    $formSql2 = "SELECT CorporateDetail, Travel FROM VisitorForm WHERE Id = ?";
    $formStmt2 = sqlsrv_query($konnext_DB64, $formSql2, [$visitorFormId]);
    $formData2 = sqlsrv_fetch_array($formStmt2, SQLSRV_FETCH_ASSOC);

    $corp = json_decode($formData2['CorporateDetail'] ?? '{}', true) ?: [];
    $travel = json_decode($formData2['Travel'] ?? '[]', true) ?: [];

    $showSueasarn = ($corp['serviceType'] ?? '') === 'use_service';
    $showTransport = is_array($travel) && in_array('Provide', $travel);

    // Build list of required division codes (skip PRD=83, conditionally skip 63 and 65)
    $requiredDivisions = [];
    foreach ($DIVISIONS as $code => $name) {
        if ($code == 83) continue; // PRD is separate
        if ($code == 63 && !$showSueasarn) continue;
        if ($code == 65 && !$showTransport) continue;
        $requiredDivisions[] = $code;
    }

    // ตรวจสอบว่ารับทราบครบฝ่ายที่เกี่ยวข้องหรือยัง
    $requiredCount = count($requiredDivisions);
    if ($requiredCount > 0) {
        $placeholders = implode(',', array_fill(0, $requiredCount, '?'));
        $countSql = "SELECT COUNT(*) as acked FROM VisitorAcknowledg WHERE VisitorFormId = ? AND IsAcknowledged = 1 AND DivisionCode IN ($placeholders)";
        $countParams = array_merge([$visitorFormId], $requiredDivisions);
        $countStmt = sqlsrv_query($konnext_DB64, $countSql, $countParams);
        $countRow = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);

        if ((int)$countRow['acked'] < $requiredCount) {
            echo json_encode(["status" => false, "message" => "ยังรับทราบไม่ครบทุกฝ่าย"]);
            exit;
        }
    }

    // Update Status เป็น 6 (Closed)
    $updateSql = "UPDATE VisitorForm SET Status = 6, EditAt = GETDATE() WHERE Id = ?";
    $updateStmt = sqlsrv_query($konnext_DB64, $updateSql, [$visitorFormId]);

    if (!$updateStmt) {
        echo json_encode(["status" => false, "message" => print_r(sqlsrv_errors(), true)]);
        exit;
    }

    echo json_encode(["status" => true, "message" => "ปิดงานสำเร็จ"], JSON_UNESCAPED_UNICODE);
    exit;
}

// ===== INIT_RECORDS: สร้าง records เมื่อ Approve =====
if ($action === 'init_records') {
    if (!$visitorFormId) {
        echo json_encode(["status" => false, "message" => "Missing visitor_form_id"]);
        exit;
    }

    // ตรวจสอบว่ามี records อยู่แล้วหรือไม่
    $checkSql = "SELECT COUNT(*) as cnt FROM VisitorAcknowledg WHERE VisitorFormId = ?";
    $checkStmt = sqlsrv_query($konnext_DB64, $checkSql, [$visitorFormId]);
    $checkRow = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);

    if ((int)$checkRow['cnt'] > 0) {
        echo json_encode(["status" => true, "message" => "Records already exist"]);
        exit;
    }

    // สร้าง records สำหรับทุกฝ่าย
    foreach ($DIVISIONS as $code => $name) {
        $insertSql = "INSERT INTO VisitorAcknowledg (VisitorFormId, DivisionCode, IsAcknowledged) VALUES (?, ?, 0)";
        sqlsrv_query($konnext_DB64, $insertSql, [$visitorFormId, $code]);
    }

    echo json_encode(["status" => true, "message" => "Created acknowledge records"], JSON_UNESCAPED_UNICODE);
    exit;
}

// ===== UPDATE_PRD: อัปเดต PRDDetail (เฉพาะแผนกวางแผน 61) =====
if ($action === 'update_prd') {
    $prdDetail = $_POST['prd_detail'] ?? null;

    if (!$visitorFormId || !$prdDetail) {
        echo json_encode(["status" => false, "message" => "Missing required parameters"]);
        exit;
    }

    // ตรวจสอบสิทธิ์ (เฉพาะ Admin หรือ แผนกวางแผน 61)
    if (!isAdmin($userPerm) && (int)$userDivision !== 61) {
        echo json_encode(["status" => false, "message" => "ไม่มีสิทธิ์แก้ไข (เฉพาะแผนกวางแผน)"]);
        exit;
    }

    $updateSql = "UPDATE VisitorForm SET PRDDetail = ?, EditAt = GETDATE() WHERE Id = ?";
    $updateStmt = sqlsrv_query($konnext_DB64, $updateSql, [$prdDetail, $visitorFormId]);

    if (!$updateStmt) {
        echo json_encode(["status" => false, "message" => print_r(sqlsrv_errors(), true)]);
        exit;
    }

    echo json_encode(["status" => true, "message" => "บันทึกข้อมูล PRD สำเร็จ"], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(["status" => false, "message" => "Unknown action"]);
