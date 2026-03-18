<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["status" => false, "message" => "Missing visitor ID"]);
    exit;
}

$sql = "SELECT 
            quiz_form.[id], 
            quiz_form.[VisitorFormId], 
            quiz_form.[json_data], 
            quiz_form.[created_at],
            VisitorForm.[DocNo],
            VisitorForm.[SalesDetail]
        FROM [quiz_form]
        LEFT JOIN [VisitorForm] ON [VisitorForm].[Id] = [quiz_form].[VisitorFormId]
        WHERE [quiz_form].[VisitorFormId] = ?";

$params = array($id);
$stmt = sqlsrv_query($konnext_DB64, $sql, $params);

if ($stmt === false) {
    echo json_encode(["status" => false, "message" => "Query failed", "error" => sqlsrv_errors()]);
    exit;
}

$total = 0;
$dataList = [];
$evalCount = 0;
$evalStats = [
    "q1" => 0,
    "q2" => 0,
    "q3" => 0,
    "q4" => 0,
    "total_eval" => 0
];

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $DocNo = $row['DocNo'];
    $SalesDetail = json_decode($row['SalesDetail'] ?? '[]', true);
    $ProjectName = $SalesDetail[0]['ProjectName'] ?? '';
    $total++;
    $json = json_decode($row['json_data'], true);

    if (!$json) continue;

    $dataList[] = [
        "id" => $row['id'],
        "company" => $json['company'] ?? '',
        "fullname" => $json['fullname'] ?? '',
        "phone" => $json['phone'] ?? '',
        "email" => $json['email'] ?? '',
        "project" => $json['project'] ?? '',
        "purpose" => $json['purpose'] ?? '',
        "suggestion" => $json['suggestion'] ?? '',
        "created_at" => $row['created_at']
    ];

    // เฉพาะข้อมูลที่มีการประเมิน (purpose = test หรือ ทดสอบผลิตภัณฑ์)
    if (isset($json['purpose']) && ($json['purpose'] === 'test' || $json['purpose'] === 'ทดสอบผลิตภัณฑ์')) {
        if (isset($json['eval']) && is_array($json['eval'])) {
            $eval = $json['eval'];
            foreach (['q1', 'q2', 'q3', 'q4'] as $q) {
                $evalStats[$q] += intval($eval[$q] ?? 0);
            }
            $evalStats["total_eval"]++;
        }
    }
}

if ($evalStats["total_eval"] > 0) {
    foreach (['q1', 'q2', 'q3', 'q4'] as $q) {
        $evalStats[$q] = round($evalStats[$q] / $evalStats["total_eval"], 2);
    }
}

echo json_encode([
    "status" => true,
    "doc_no" => $DocNo,
    "project_name" => $ProjectName,
    "total_records" => $total,
    "data" => $dataList,
    "dashboard" => [
        "total_evaluations" => $evalStats["total_eval"],
        "average_scores" => [
            "q1" => $evalStats["q1"],
            "q2" => $evalStats["q2"],
            "q3" => $evalStats["q3"],
            "q4" => $evalStats["q4"]
        ]
    ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

// ปิด connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($konnext_DB64);
