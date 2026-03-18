<?php
ob_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
include_once '../config/base.php';

$group_id = $_POST['group_id'];
$group_user = $_POST['group_user'];

$sql = "
        UPDATE [ASF_VisitorCompany].[dbo].[Visi_GroupNoti]
        SET 
            [group_user] = '$group_user', 
            [group_useredit] = '" . $_SESSION['VisitorMKT_code'] . "',
            [group_dateedit] = GETDATE()
        WHERE [group_id] = '$group_id'
    ";

$query = sqlsrv_query($konnext_DB64, $sql);

if ($query) {
    echo json_encode(array('status' => true), JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array('status' => false), JSON_UNESCAPED_UNICODE);
}
