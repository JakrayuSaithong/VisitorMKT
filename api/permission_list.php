<?php
    ob_start();
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    include_once '../config/base.php';

    $sql = "
        SELECT 
            [per_id]
            ,[per_name]
            ,[per_user]
            ,[per_division]
            ,[per_useredit]
            ,[per_dateedit]
        FROM [ASF_VisitorCompany].[dbo].[Visi_Permision]
    ";

    $query = sqlsrv_query($konnext_DB64, $sql);

    $data = [];
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $row['THNameEdit'] = mydata($row['per_useredit'])['FullName'];
        $data[] = $row;
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>