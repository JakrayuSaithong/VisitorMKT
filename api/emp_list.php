<?php
include_once '../config/base.php';
// ob_clean();
// header_remove();
header("Content-type: application/json; charset=utf-8");

$action = $_GET['action'] ?? '';
$divi_code_1 = $_GET['divi_code_1'] ?? '';

if($action == 'emplist'){
    $sql = "
        SELECT 
            emp_code AS Code,
            emp_Name AS FullName,
            DepartmentCode AS DivisionCode
        FROM
            [ASEFA_CRM].[dbo].[Users]
        WHERE
            emp_Company = '600'
            AND (
                DateExpire IS NULL OR DateExpire > CAST(GETDATE() AS DATE)
            )
    ";

    if($divi_code_1 != null){
        $sql .= " AND (SectionCode = '$divi_code_1' OR DepartmentCode = '$divi_code_1')";
    }

    $sql .= " ORDER BY emp_Name ASC";

    $query = sqlsrv_query($konnext_DB64_CRM, $sql);
    $data = array();
    if($query == true){
        while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){
            $data[] = $row;
        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
elseif($action == 'divisionlist'){
    $sql = "
        SELECT 
            [SectionCode]
            ,[SectionName]
            ,[DepartmentCode]
            ,[DepartmentName]
        FROM
            [ASEFA_CRM].[dbo].[Users]
        WHERE
            emp_Company = '600'
            AND (
                DateExpire IS NULL OR DateExpire > CAST(GETDATE() AS DATE)
            )
    ";

    $query = sqlsrv_query($konnext_DB64_CRM, $sql);
    $result = array();
    if($query == true){
        while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){
            $sectionCode = $row['SectionCode'];

            if (!isset($result[$sectionCode])) {
                $result[$sectionCode] = [
                    "SectionName" => $row['SectionName'],
                    "SectionCode" => $sectionCode,
                    "Department" => []
                ];
            }

            $result[$sectionCode]["Department"][$row['DepartmentCode']] = [
                "DepartmentCode" => $row['DepartmentCode'],
                "DepartmentName" => $row['DepartmentName']
            ];
        }
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


?>