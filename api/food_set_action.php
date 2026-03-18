<?php
ob_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
include_once '../config/base.php';

$Action = $_POST['Action'] ?? '';
$SetID = $_POST['SetID'] ?? '';
$SetName = $_POST['SetName'] ?? '';
$FoodItems = $_POST['FoodItems'] ?? ''; // JSON string
$SetStatus = $_POST['SetStatus'] ?? '';

if ($Action == 'add') {
    $sql = "
        INSERT INTO visit_foodset (food_set_name, food_items, user_edit, edit_date)
        VALUES (?, ?, ?, GETDATE())
    ";
    $params = array($SetName, $FoodItems, $_SESSION['VisitorMKT_code']);
}
else if ($Action == 'edit') {
    $sql = "
        UPDATE visit_foodset
        SET food_set_name = ?, food_items = ?, food_set_status = ?, 
            user_edit = ?, edit_date = GETDATE()
        WHERE food_set_id = ?
    ";
    $params = array($SetName, $FoodItems, $SetStatus, $_SESSION['VisitorMKT_code'], $SetID);
}
else if ($Action == 'delete') {
    $sql = "
        UPDATE visit_foodset 
        SET food_set_status = 2, user_edit = ?, edit_date = GETDATE()
        WHERE food_set_id = ?
    ";
    $params = array($_SESSION['VisitorMKT_code'], $SetID);
}
else {
    $sql = "SELECT * FROM visit_foodset WHERE food_set_status IN (0,1)";
    $query = sqlsrv_query($konnext_DB64, $sql);
    $result = [];
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        // $row['user_edit'] = mydata($row['user_edit'])['FullName'];
        $row['food_items'] = json_decode($row['food_items'], true);
        $result[] = $row;
    }
    echo json_encode(['status' => true, 'data' => $result], JSON_UNESCAPED_UNICODE);
    exit;
}

$query = sqlsrv_query($konnext_DB64, $sql, $params);
echo json_encode(['status' => (bool)$query], JSON_UNESCAPED_UNICODE);
?>
