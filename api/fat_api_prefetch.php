<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    echo json_encode(['status' => false, 'message' => 'Invalid request'], JSON_UNESCAPED_UNICODE);
    exit;
}

$job_no = $_POST['job_no'] ?? null;
if (!$job_no) {
    echo json_encode(['status' => false, 'message' => 'Missing job_no'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Query 1: DO document info (owner, sale, value)
$sql1 = "SELECT
    Used_For                                AS JobName,
    cust.Thai_Name                          AS CustomerName,
    ts.Users                                AS Users,
    SAL.[Name] + ' ' + SAL.Surname         AS SaleName,
    FORMAT(ts.Other,        'F2')           AS Other,
    FORMAT(ts.TotalPrice,   'F2')           AS Cost,
    FORMAT(ts.Bill_Amount,  'F2')           AS BillAmount
FROM  [cd-XPSQL-ASF7].dbo.Transection1     ts
LEFT OUTER JOIN [cd-XPSQL-ASF7].dbo.Contact    cust  ON cust.Contact_ID = ts.Customer_ID
LEFT OUTER JOIN [cd-XPSQL-ASF7].dbo.Man_Info   SAL   ON SAL.ManID       = ts.MAN_ID
WHERE ts.Doc_Type = 'DO'
  AND ts.Del      <> 'Y'
  AND ts.Doc_No   = ?
GROUP BY Used_For, cust.Thai_Name, SAL.[Name] + ' ' + SAL.Surname,
         ts.Other, ts.TotalPrice, ts.Bill_Amount, ts.Users";

$stmt1 = sqlsrv_query($konnext_DB64_ASF7, $sql1, [$job_no]);
$query1 = null;
if ($stmt1) {
    $row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
    if ($row) $query1 = $row;
    sqlsrv_free_stmt($stmt1);
}

// Query 2: WA documents linked to this job (→ wa_no)
$sql2 = "SELECT Doc_No, Lot_No
FROM [cd-XPSQL-ASF7].dbo.Transection
WHERE Doc_Type = 'WA'
  AND PO_No   = ?
GROUP BY Doc_No, Lot_No";

$stmt2 = sqlsrv_query($konnext_DB64_ASF7, $sql2, [$job_no]);
$query2 = [];
if ($stmt2) {
    while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
        $query2[] = $row;
    }
    sqlsrv_free_stmt($stmt2);
}

// Query 3: Serial numbers (→ SN_no)
$sql3 = "SELECT PartNo, MAX(item) AS item
FROM [ASF_VIEW].dbo.vw_SerialServiceJob
WHERE job_no = ?
GROUP BY PartNo";

$stmt3 = sqlsrv_query($konnext_DB64_ASF7, $sql3, [$job_no]);
$query3 = [];
if ($stmt3) {
    while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
        $query3[] = $row;
    }
    sqlsrv_free_stmt($stmt3);
}

echo json_encode([
    'status' => true,
    'job_no' => $job_no,
    'query1' => $query1,
    'query2' => $query2,
    'query3' => $query3,
], JSON_UNESCAPED_UNICODE);
