<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["status" => false, "message" => "Missing Visitor ID"]);
    exit;
}

$sql = "SELECT Id, FileType, FilePath, FileName FROM VisitorFiles WHERE VisitorFormId = ?";
$stmt = sqlsrv_query($konnext_DB64, $sql, [$id]);
if (!$stmt) {
    echo json_encode(["status" => false, "message" => print_r(sqlsrv_errors(), true)]);
    exit;
}

$files = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $files[] = $row;
}

echo json_encode(["status" => true, "data" => $files], JSON_UNESCAPED_UNICODE);
