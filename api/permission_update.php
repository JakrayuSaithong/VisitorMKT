<?php
    ob_start();
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    include_once '../config/base.php';

    $per_id = $_POST['per_id'];
    $per_user = $_POST['per_user'];
    $per_division = $_POST['per_division'];

    $sql = "
        UPDATE [ASF_VisitorCompany].[dbo].[Visi_Permision]
        SET 
            [per_user] = '$per_user', 
            [per_division] = '$per_division',
            [per_useredit] = '". $_SESSION['VisitorMKT_code'] ."',
            [per_dateedit] = GETDATE()
        WHERE [per_id] = '$per_id'
    ";

    $query = sqlsrv_query($konnext_DB64, $sql);

    if($query){
        echo json_encode(array('status' => true), JSON_UNESCAPED_UNICODE);
    }else{
        echo json_encode(array('status' => false), JSON_UNESCAPED_UNICODE);
    }