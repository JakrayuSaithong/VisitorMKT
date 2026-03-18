<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$id = $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(["status" => false, "message" => "Missing file ID"]);
    exit;
}

$sql = "SELECT FilePath FROM VisitorFiles WHERE Id = ?";
$stmt = sqlsrv_query($konnext_DB64, $sql, [$id]);
if (!$stmt || !($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    echo json_encode(["status" => false, "message" => "ไม่พบไฟล์"]);
    exit;
}

$filePath = "../file/" . $row['FilePath'];

if (file_exists($filePath)) {
    unlink($filePath);
}

$sqlDel = "DELETE FROM VisitorFiles WHERE Id = ?";
$del = sqlsrv_query($konnext_DB64, $sqlDel, [$id]);

if ($del) {
    echo json_encode(["status" => true, "message" => "ลบไฟล์เรียบร้อย"]);
} else {
    echo json_encode(["status" => false, "message" => print_r(sqlsrv_errors(), true)]);
}
