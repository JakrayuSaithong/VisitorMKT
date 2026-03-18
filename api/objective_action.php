<?php
    ob_start();
    header('Content-Type: application/json');
    // header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    include_once '../config/base.php';

    $Action = $_POST['Action'] ?? '';
    $ObjectiveID = $_POST['ObjectiveID'] ?? '';
    $ObjectiveName = $_POST['ObjectiveName'] ?? '';
    $ObjectiveStatus = $_POST['ObjectiveStatus'] ?? '';

    if($Action == 'add'){
        $sql = "
            INSERT INTO visit_objective 
            (visit_objective_name, user_edit, edit_date) 
            VALUES 
            ('$ObjectiveName', '". $_SESSION['VisitorMKT_code'] ."', GETDATE())
        ";

        $query = sqlsrv_query($konnext_DB64, $sql);

        if($query){
            echo json_encode(array('status' => true), JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(array('status' => false), JSON_UNESCAPED_UNICODE);
        }
    }
    else if($Action == 'edit'){
        $sql = "
            UPDATE visit_objective 
            SET 
                visit_objective_name = '$ObjectiveName', 
                visit_objective_status = $ObjectiveStatus,
                user_edit = '". $_SESSION['VisitorMKT_code'] ."',
                edit_date = GETDATE() 
            WHERE visit_objective_id = '$ObjectiveID'
        ";

        $query = sqlsrv_query($konnext_DB64, $sql);

        if($query){
            echo json_encode(array('status' => true), JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(array('status' => false), JSON_UNESCAPED_UNICODE);
        }
    }
    else if($Action == 'delete'){
        $sql = "
            UPDATE visit_objective 
            SET 
                visit_objective_status = 2,
                user_edit = '". $_SESSION['VisitorMKT_code'] ."',
                edit_date = GETDATE() 
            WHERE visit_objective_id = '$ObjectiveID'
        ";

        $query = sqlsrv_query($konnext_DB64, $sql);

        if($query){
            echo json_encode(array('status' => true), JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(array('status' => false), JSON_UNESCAPED_UNICODE);
        }
    }
    else {
        $sql = "
            SELECT * FROM visit_objective where visit_objective_status IN (0,1)
        ";

        $query = sqlsrv_query($konnext_DB64, $sql);

        if($query){
            $result = array();
            while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                // $row['user_edit'] = mydata($row['user_edit'])['FullName'];
                $result[] = $row;
            }
            echo json_encode(array('status' => true, 'data' => $result), JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(array('status' => false), JSON_UNESCAPED_UNICODE);
        }
    }
?>