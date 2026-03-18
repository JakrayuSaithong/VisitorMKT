<?php
ob_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
include_once '../config/base.php';

$sql = "
        SELECT 
            [group_id]
            ,[group_name]
            ,[group_user]
            ,[group_useredit]
            ,[group_dateedit]
        FROM [ASF_VisitorCompany].[dbo].[Visi_GroupNoti]
    ";

$query = sqlsrv_query($konnext_DB64, $sql);

$data = [];
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    $row['THNameEdit'] = mydata($row['group_useredit'])['FullName'] ?? '';
    $data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
