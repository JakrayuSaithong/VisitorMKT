<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$date = isset($_GET['date']) ? $_GET['date'] : null;
if (!$date) {
    echo json_encode(["status" => false, "message" => "Missing parameters"]);
    exit;
}

$sql = "
    DECLARE @date DATE = ?;
    DECLARE @month INT = MONTH(@date);
    DECLARE @year INT = YEAR(@date);

    SELECT
        JSON_QUERY((
            SELECT
                SUM(CASE WHEN Status = 1 THEN 1 ELSE 0 END) AS [New],
                SUM(CASE WHEN Status = 2 THEN 1 ELSE 0 END) AS [Acept],
                SUM(CASE WHEN Status = 3 THEN 1 ELSE 0 END) AS [Approved],
                SUM(CASE WHEN Status = 5 THEN 1 ELSE 0 END) AS [Submit],
                SUM(CASE WHEN Status = 6 THEN 1 ELSE 0 END) AS [Closed]
            FROM VisitorForm
            FOR JSON PATH, WITHOUT_ARRAY_WRAPPER
        )) AS StatusSummary,

        JSON_QUERY((
            SELECT
                COUNT(*) AS TotalVisitor,
                SUM(CASE WHEN ISNULL(CustomerNameThai, 0) > 0 THEN CustomerNameThai ELSE 0 END) AS ThaiCustomers,
                SUM(CASE WHEN ISNULL(CustomerNameForeign, 0) > 0 THEN CustomerNameForeign ELSE 0 END) AS ForeignCustomers,
                SUM(ISNULL(CustomerNameThai, 0) + ISNULL(CustomerNameForeign, 0)) AS TotalCustomers
            FROM VisitorForm
            FOR JSON PATH, WITHOUT_ARRAY_WRAPPER
        )) AS VisitorSummary,

        JSON_QUERY((
            SELECT TOP 1
                value AS ObjectiveType,
                COUNT(*) AS Frequency
            FROM VisitorForm
            CROSS APPLY OPENJSON(Objective) 
            WHERE [Status] = 3
            GROUP BY value
            ORDER BY COUNT(*) DESC
            FOR JSON PATH, WITHOUT_ARRAY_WRAPPER
        )) AS ObjectiveTop,

        JSON_QUERY((
            SELECT 
                COUNT(*) AS TotalQuiz
            FROM quiz_form
            FOR JSON PATH, WITHOUT_ARRAY_WRAPPER
        )) AS QuizSummary,

        JSON_QUERY((
            SELECT 
                JSON_VALUE(json_data, '$.purpose') AS Purpose,
                COUNT(*) AS Total
            FROM quiz_form
            GROUP BY JSON_VALUE(json_data, '$.purpose')
            FOR JSON PATH
        )) AS PurposeSummary
    FOR JSON PATH, WITHOUT_ARRAY_WRAPPER
";

$stmt = sqlsrv_query($konnext_DB64, $sql, [$date]);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo json_encode([
        "status" => false,
        "message" => "SQL Error",
        "error" => $errors,
        "sql" => $sql
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (sqlsrv_fetch($stmt)) {
    $json = sqlsrv_get_field($stmt, 0);
    if (!empty($json)) {
        echo stream_get_contents($json);
    } else {
        echo json_encode(["status" => false, "message" => "Empty result"], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(["status" => false, "message" => "No data found"], JSON_UNESCAPED_UNICODE);
}

?>