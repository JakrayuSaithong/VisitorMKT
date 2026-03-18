<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["status" => false, "message" => "Missing visitor ID"]);
    exit;
}

// Select all columns
$sql = "SELECT * FROM VisitorForm WHERE Id = ?";
$stmt = sqlsrv_query($konnext_DB64, $sql, [$id]);

if (!$stmt) {
    echo json_encode(["status" => false, "message" => print_r(sqlsrv_errors(), true)]);
    exit;
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$row) {
    echo json_encode(["status" => false, "message" => "ไม่พบข้อมูล"]);
    exit;
}

// Handle DateTime objects for main row if any (though usually fields are strings or handled by driver)
foreach ($row as $key => $value) {
    if ($value instanceof DateTime) {
        $row[$key] = $value->format('Y-m-d H:i:s');
    }
}

// Fetch Schedule
$schedules = [];
$sql2 = "SELECT * FROM VisitorSchedule WHERE VisitorFormId = ?";
$stmt2 = sqlsrv_query($konnext_DB64, $sql2, [$id]);
if ($stmt2) {
    while ($r = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
        if (!empty($r['VisitDate'])) {
            if ($r['VisitDate'] instanceof DateTime) {
                $r['VisitDate'] = $r['VisitDate']->format('d/m/Y');
            } else {
                $r['VisitDate'] = date('d/m/Y', strtotime($r['VisitDate']));
            }
        }
        $schedules[] = $r;
    }
}

$row['Schedule'] = $schedules;

echo json_encode(["status" => true, "data" => $row], JSON_UNESCAPED_UNICODE);
