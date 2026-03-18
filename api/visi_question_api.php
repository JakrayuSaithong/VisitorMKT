<?php
header('Content-Type: application/json; charset=utf-8');
include_once '../config/connection.php';

$srvsql = new srvsql();
$connect = $srvsql->konnext_DB64();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['data'])) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'Missing data']);
    exit;
}

$data = json_decode($_POST['data'], true);
$VisitorFormId = $_POST['VisitorFormId'] ?? null;
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'Invalid JSON']);
    exit;
}

$company    = trim($data['company'] ?? '');
$fullname   = trim($data['fullname'] ?? '');
$phone      = preg_replace('/\D/', '', $data['phone'] ?? '');
$email      = trim($data['email'] ?? '');
$project    = trim($data['project'] ?? '');
$purpose    = trim($data['purpose'] ?? '');
$suggestion = trim($data['suggestion'] ?? '');
$eval       = $data['eval'] ?? null;

if ($company === '' || $fullname === '' || $project === '' || $purpose === '' || $suggestion === '') {
    echo json_encode(['status' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
    exit;
}

// if (!preg_match('/^(06|08|09)\d{8}$/', $phone)) {
//     echo json_encode(['status' => false, 'message' => 'กรุณากรอกเบอร์โทรให้ถูกต้อง (ขึ้นต้น 06,08,09 และ 10 หลัก)']);
//     exit;
// }

if ($purpose === 'ทดสอบผลิตภัณฑ์') {
    $must = ['q1','q2','q3','q4'];
    foreach ($must as $key) {
        if (!isset($eval[$key]) || !in_array((int)$eval[$key], [1,2,3])) {
            echo json_encode(['status' => false, 'message' => 'กรุณาประเมินทุกหัวข้อ']);
            exit;
        }
    }
}

$store_json = json_encode([
    'company'    => $company,
    'fullname'   => $fullname,
    'phone'      => $phone,
    'email'      => $email,
    'project'    => $project,
    'purpose'    => $purpose,
    'suggestion' => $suggestion,
    'eval'       => $eval
], JSON_UNESCAPED_UNICODE);

$sql = "
    INSERT INTO quiz_form (VisitorFormId, json_data)
    VALUES (?, ?)
";

$params = array($VisitorFormId, $store_json);
$stmt = sqlsrv_query($connect, $sql, $params);

if ($stmt) {
    echo json_encode(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
} else {
    $errors = sqlsrv_errors();
    echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด', 'error' => $errors]);
}
