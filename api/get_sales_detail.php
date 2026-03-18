<?php
/**
 * API: get_sales_detail.php
 * ดึงข้อมูล SalesDetail จาก VisitorForm
 */

include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$visitorId = $_GET['visitor_id'] ?? $_GET['id'] ?? null;

if (!$visitorId) {
    echo json_encode(["status" => false, "message" => "Missing visitor_id"]);
    exit;
}

try {
    $sql = "SELECT SalesDetail FROM VisitorForm WHERE Id = ?";
    $stmt = sqlsrv_query($konnext_DB64, $sql, [$visitorId]);
    
    if (!$stmt) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    
    if (!$row) {
        echo json_encode(["status" => false, "message" => "ไม่พบข้อมูล"]);
        exit;
    }
    
    $salesDetail = [];
    if (!empty($row['SalesDetail'])) {
        $salesDetail = json_decode($row['SalesDetail'], true) ?? [];
    }
    
    // Extract unique ProjectNames and CompanyNames
    $projects = [];
    $companies = [];
    
    if (is_array($salesDetail)) {
        foreach ($salesDetail as $item) {
            if (!empty($item['ProjectName']) && !in_array($item['ProjectName'], $projects)) {
                $projects[] = $item['ProjectName'];
            }
            if (!empty($item['CompanyName']) && !in_array($item['CompanyName'], $companies)) {
                $companies[] = $item['CompanyName'];
            }
        }
    }
    
    echo json_encode([
        "status" => true,
        "data" => [
            "sales_detail" => $salesDetail,
            "projects" => $projects,
            "companies" => $companies
        ]
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}
