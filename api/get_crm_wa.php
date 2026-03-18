<?php
/**
 * API: get_crm_wa.php
 * ดึงข้อมูล WA/SN จาก CRM ตามชื่อบริษัท (Thai_Name)
 */

include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$q = $_GET['q'] ?? '';

if ($q === '') {
    echo json_encode(["status" => false, "message" => "Missing q parameter"], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $sql = "select c.Contact_ID, c.Company_Name, c.Thai_Name,
t.Doc_no, t.PO_No, t.Used_For, t.Lot_No, t.Whom,
ts.PartNo, ts.PartName
from contact c
inner join Transection t on c.Contact_ID = t.Customer_ID and t.Doc_Type = 'WA'
inner join Transection_sub ts on ts.Doc_Type = t.Doc_Type and ts.Doc_No = t.Doc_No
where c.Thai_Name like ?";

    $params = ['%' . $q . '%'];
    $stmt = sqlsrv_query($konnext_DB64_ASF7, $sql, $params);

    if (!$stmt) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }

    $rows = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $rows[] = $row;
    }

    echo json_encode(["status" => true, "data" => $rows], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
