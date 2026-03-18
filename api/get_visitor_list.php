<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $sql = "SELECT 
                v.Id,
                v.DocNo,
                v.Status,
                v.UserCreated,
                v.SalesDetail,
                (SELECT TOP 1 VisitDate FROM VisitorSchedule WHERE VisitorFormId = v.Id ORDER BY VisitDate ASC) as VisitDate
            FROM VisitorForm v
            ORDER BY v.Id DESC";

    $stmt = sqlsrv_query($konnext_DB64, $sql);
    if (!$stmt) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }

    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Format VisitDate
        if (!empty($row['VisitDate'])) {
            if ($row['VisitDate'] instanceof DateTime) {
                $row['VisitDate'] = $row['VisitDate']->format('d/m/Y');
            } else {
                $row['VisitDate'] = date('d/m/Y', strtotime($row['VisitDate']));
            }
        } else {
            $row['VisitDate'] = '-';
        }
        
        // Extract ProjectName, CompanyName, SalesName from SalesDetail JSON
        $row['ProjectName'] = '';
        $row['CompanyName'] = '';
        $row['SalesName'] = '';
        
        if (!empty($row['SalesDetail'])) {
            $salesDetail = json_decode($row['SalesDetail'], true);
            if (is_array($salesDetail) && count($salesDetail) > 0) {
                $firstJob = $salesDetail[0];
                $row['ProjectName'] = $firstJob['ProjectName'] ?? '';
                $row['CompanyName'] = $firstJob['CompanyName'] ?? '';
                $row['SalesName'] = $firstJob['SalesName'] ?? '';
            }
        }
        
        // Remove SalesDetail from response (too large for list)
        unset($row['SalesDetail']);
        
        $data[] = $row;
    }

    echo json_encode(["status" => true, "data" => $data]);
} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
