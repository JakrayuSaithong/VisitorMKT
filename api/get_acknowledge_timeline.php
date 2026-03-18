<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(["status" => false, "message" => "Missing id"]);
    exit;
}

// ------------------------------------------------------
// ดึงข้อมูล VisitorAcknowledge พร้อม Objective จาก VisitorForm
// ------------------------------------------------------
$sql = "
    SELECT 
        VA.[VisitorFormId], VA.[mk], VA.[sell], VA.[Communication], 
        VA.[transport], VA.[hr], VA.[lecturer], VA.[qc], VA.[AckJson],
        VF.[Objective]
    FROM VisitorAcknowledge VA
    LEFT JOIN VisitorForm VF ON VF.id = VA.VisitorFormId
    WHERE VA.VisitorFormId = ?
";

$stmt = sqlsrv_query($konnext_DB64, $sql, [$id]);
if (!$stmt || !($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    echo json_encode(["status" => false, "message" => "ไม่พบข้อมูล VisitorAcknowledge"]);
    exit;
}

$objective = [];
if (!empty($row['Objective'])) {
    $decoded = json_decode($row['Objective'], true);
    if (is_array($decoded)) {
        $objective = $decoded;
    }
}

// ------------------------------------------------------
// ดึงข้อมูล AckJson
// ------------------------------------------------------
$ackList = [];
if (!empty($row['AckJson'])) {
    $ackList = json_decode($row['AckJson'], true);
    if (!is_array($ackList)) $ackList = [];
}

// ------------------------------------------------------
// รายชื่อฝ่ายที่ต้องแสดงใน timeline ตามลำดับ
// ------------------------------------------------------
$rolesOrder = [
    "mk" => "ฝ่ายการตลาด",
    "sell" => "ฝ่ายขาย",
    "Communication" => "ฝ่ายสื่อสาร",
    "transport" => "ฝ่ายขนส่ง",
    "hr" => "ฝ่าย HR",
    "lecturer" => "วิทยากร",
    "qc" => "ฝ่าย QC"
];

if (!(in_array(2, $objective) || in_array(3, $objective))) {
    unset($rolesOrder['lecturer']);
}

// ------------------------------------------------------
// สร้างข้อมูล timeline
// ------------------------------------------------------
$timeline = [];
foreach ($rolesOrder as $key => $label) {
    // หา entry ล่าสุดของ role นี้จาก AckJson
    $lastAck = null;
    foreach (array_reverse($ackList) as $a) {
        if (($a['role'] ?? '') === $key) {
            $lastAck = $a;
            break;
        }
    }

    if ($lastAck) {
        $timeline[] = [
            "role" => $key,
            "label" => $label,
            "status" => "acknowledged",
            "type" => $lastAck['type'] ?? '',
            "user" => $lastAck['user'] ?? '-',
            "time" => date("Y-m-d H:i:s", strtotime($lastAck['date'])),
            "text" => "รับทราบแล้ว"
        ];
    } else {
        $timeline[] = [
            "role" => $key,
            "label" => $label,
            "status" => "pending",
            "type" => null,
            "user" => null,
            "time" => null,
            "text" => "รอรับทราบ"
        ];
    }
}

usort($timeline, function($a, $b) {
    return strtotime($a['time'] ?? '9999-12-31') <=> strtotime($b['time'] ?? '9999-12-31');
});

echo json_encode([
    "status" => true,
    "data" => [
        "VisitorFormId" => $row['VisitorFormId'],
        "Objective" => $objective,
        "timeline" => $timeline
    ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;