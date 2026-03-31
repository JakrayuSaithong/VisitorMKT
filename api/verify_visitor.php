<?php

/**
 * API: Verify Visitor by Phone Number
 * ใช้ SQL Server (sqlsrv)
 */

header('Content-Type: application/json');
require_once '../config/base.php';

// Function ถอดรหัส Visitor ID
function decodeVisitorId($token)
{
    $secretKey = 'ASEFA2024VMS'; // ต้องตรงกับ send_invitation.php

    // Restore base64 padding
    $base64 = strtr($token, '-_', '+/');
    $padding = strlen($base64) % 4;
    if ($padding) {
        $base64 .= str_repeat('=', 4 - $padding);
    }

    $encoded = base64_decode($base64);
    if ($encoded === false) {
        return 0;
    }

    // XOR decode
    $decoded = '';
    for ($i = 0; $i < strlen($encoded); $i++) {
        $decoded .= chr(ord($encoded[$i]) ^ ord($secretKey[$i % strlen($secretKey)]));
    }

    // Extract ID (ก่อน |)
    $parts = explode('|', $decoded);
    return isset($parts[0]) ? intval($parts[0]) : 0;
}

// ใช้ connection สำหรับ VisitorCompany database
$conn = $konnext_DB64;

try {
    // รองรับทั้ง token (encoded) และ id (raw)
    $token = isset($_POST['visi_token']) ? trim($_POST['visi_token']) : '';
    $rawId = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // ถ้ามี token ให้ถอดรหัส, ถ้าไม่มีใช้ rawId
    $visitorId = $token ? decodeVisitorId($token) : $rawId;

    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if (!$visitorId || !$phone) {
        throw new Exception('กรุณาระบุข้อมูลให้ครบถ้วน');
    }

    // Normalize phone
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($phone) === 10 && $phone[0] === '0') {
        $phoneWithout0 = substr($phone, 1);
    } else {
        $phoneWithout0 = $phone;
    }

    // Get VisitorForm with SalesDetail and VisitorSchedule (SQL Server)
    $sql = "SELECT vf.SalesDetail, vf.UserCreated, vs.VisitDate, vs.TimeStart, vs.TimeEnd
            FROM VisitorForm vf
            LEFT JOIN VisitorSchedule vs ON vs.VisitorFormId = vf.Id
            WHERE vf.Id = ?";
    $params = array($visitorId);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        throw new Exception('Query failed');
    }

    $data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    if (!$data) {
        throw new Exception('ไม่พบข้อมูลการเยี่ยมชม');
    }

    // Parse SalesDetail JSON
    $salesDetailJson = isset($data['SalesDetail']) ? $data['SalesDetail'] : '[]';
    $salesDetail = json_decode($salesDetailJson, true);

    if (!is_array($salesDetail) || empty($salesDetail)) {
        throw new Exception('ไม่พบข้อมูล SalesDetail');
    }

    $job = $salesDetail[0];

    // Parse phone numbers
    $phones = array();
    $phoneField = isset($job['PhoneNumber']) ? $job['PhoneNumber'] : '';
    if ($phoneField) {
        if (is_array($phoneField)) {
            $phones = $phoneField;
        } elseif (strpos($phoneField, '[') === 0) {
            $decoded = json_decode($phoneField, true);
            if (is_array($decoded)) {
                $phones = $decoded;
            }
        } else {
            $phones = array($phoneField);
        }
    }

    // Normalize all phones
    $normalizedPhones = array();
    foreach ($phones as $p) {
        $p = preg_replace('/[^0-9]/', '', $p);
        $normalizedPhones[] = $p;
        if (strlen($p) === 10 && $p[0] === '0') {
            $normalizedPhones[] = substr($p, 1);
        }
    }

    // Check if phone matches
    $phoneMatched = in_array($phone, $normalizedPhones) || in_array($phoneWithout0, $normalizedPhones);

    if (!$phoneMatched) {
        echo json_encode(array('success' => false, 'message' => 'เบอร์โทรไม่ตรงกับข้อมูลในระบบ'));
        exit;
    }

    // Find customer name
    $customerName = '';
    $names = array();
    $nameField = isset($job['CustomerName']) ? $job['CustomerName'] : '';
    if ($nameField) {
        if (is_array($nameField)) {
            $names = $nameField;
        } elseif (strpos($nameField, '[') === 0) {
            $decoded = json_decode($nameField, true);
            if (is_array($decoded)) {
                $names = $decoded;
            }
        } else {
            $names = array($nameField);
        }
    }

    foreach ($phones as $idx => $p) {
        $pNormalized = preg_replace('/[^0-9]/', '', $p);
        if ($pNormalized === $phone || $pNormalized === $phoneWithout0) {
            $customerName = isset($names[$idx]) ? $names[$idx] : '';
            break;
        }
    }
    if (!$customerName && count($names) > 0) {
        $customerName = $names[0];
    }

    // Format zone
    $zone = isset($job['Zone']) ? $job['Zone'] : '';
    if (is_array($zone)) {
        $zone = implode(', ', $zone);
    } elseif (is_string($zone) && strpos($zone, '[') === 0) {
        $zoneArr = json_decode($zone, true);
        if (is_array($zoneArr)) {
            $zone = implode(', ', $zoneArr);
        }
    }

    // Format time
    $timeStr = '';
    if (isset($data['TimeStart']) && $data['TimeStart']) {
        $timeStr = $data['TimeStart'];
        if (isset($data['TimeEnd']) && $data['TimeEnd']) {
            $timeStr .= ' - ' . $data['TimeEnd'];
        }
    }

    // Format date
    $visitDate = isset($data['VisitDate']) ? $data['VisitDate'] : '';
    if ($visitDate) {
        if ($visitDate instanceof DateTime) {
            $dt = $visitDate;
        } else {
            $dt = new DateTime($visitDate);
        }
        $thaiMonths = array('', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');
        $visitDate = $dt->format('j') . ' ' . $thaiMonths[(int)$dt->format('n')] . ' ' . ($dt->format('Y') + 543);
    }

    // ดึงชื่อผู้ร้องขอ (UserCreated) จาก mydata
    $requesterCode = isset($data['UserCreated']) ? $data['UserCreated'] : '';
    $requesterData = $requesterCode ? mydata($requesterCode) : null;
    $salesName = ($requesterData && isset($requesterData['FullName'])) ? $requesterData['FullName'] : 'ทีมงาน ASEFA';

    echo json_encode(array(
        'success' => true,
        'name' => $customerName,
        'visit_date' => $visitDate ? $visitDate : 'ยังไม่ได้กำหนด',
        'visit_time' => $timeStr ? $timeStr : 'ยังไม่ได้กำหนด',
        'zone' => $zone ? $zone : 'ยังไม่ได้กำหนด',
        'contact_person' => $salesName
    ));
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
}
