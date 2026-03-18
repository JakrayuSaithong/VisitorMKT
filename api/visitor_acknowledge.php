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

    echo json_encode([
        "status" => true,
        "message" => $allAcknowledged ? "รับทราบครบทุกฝ่ายแล้ว" : "รับทราบเรียบร้อย",
        "all_acknowledged" => $allAcknowledged
    ], JSON_UNESCAPED_UNICODE);
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

    // ตรวจสอบว่ารับทราบครบหรือยัง
    $countSql = "SELECT COUNT(*) as acked FROM VisitorAcknowledg WHERE VisitorFormId = ? AND IsAcknowledged = 1";
    $countStmt = sqlsrv_query($konnext_DB64, $countSql, [$visitorFormId]);
    $countRow = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);

    if ((int)$countRow['acked'] < count($DIVISIONS)) {
        echo json_encode(["status" => false, "message" => "ยังรับทราบไม่ครบทุกฝ่าย"]);
        exit;
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
