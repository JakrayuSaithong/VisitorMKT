<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$reserve          = trim($_POST['reserve'] ?? '');
$date             = trim($_POST['dateTrival'] ?? '');
$timeStart        = trim($_POST['meeting_time_start'] ?? '');
$timeEnd          = trim($_POST['meeting_time_end'] ?? '');
$excludeMeetingId = intval($_POST['exclude_meeting_id'] ?? 0);

if (!$reserve || !$date || !$timeStart || !$timeEnd) {
    echo json_encode(['status' => false, 'message' => 'ข้อมูลไม่ครบ', 'data' => []]);
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['status' => false, 'message' => 'รูปแบบวันที่ไม่ถูกต้อง', 'data' => []]);
    exit;
}

$roomType = ($reserve === 'zoom') ? 'Zoom' : 'MeetingRoom';

$sql = "
    SELECT w_40.site_id_40 AS id, w_40.site_f_701 AS name
    FROM work_progress_040 w_40
    LEFT JOIN work_progress_061 w_61 ON w_40.site_f_716 = w_61.site_id_61
    WHERE w_61.site_f_869 = ?
    AND w_40.site_f_3015 = 600
    AND w_40.site_f_938 = '0'
    AND w_40.site_id_40 NOT IN (151, 153, 198)
    AND w_40.site_id_40 NOT IN (
        SELECT site_f_1135
        FROM work_progress_091
        WHERE DATE(site_f_1134) = ?
        AND TIME(site_f_1134) < ?
        AND TIME(site_f_1175) > ?
        AND site_id_91 != ?
    )
    ORDER BY w_40.site_f_701
";

$stmt = mysqli_prepare($konnext_lqsym, $sql);
if (!$stmt) {
    echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาดในการเตรียม query: ' . mysqli_error($konnext_lqsym), 'data' => []]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'ssssi', $roomType, $date, $timeEnd, $timeStart, $excludeMeetingId);

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => false, 'message' => 'Query execution failed: ' . mysqli_stmt_error($stmt), 'data' => []]);
    exit;
}

$result = mysqli_stmt_get_result($stmt);
if ($result === false) {
    echo json_encode(['status' => false, 'message' => 'Failed to get result: ' . mysqli_stmt_error($stmt), 'data' => []]);
    exit;
}

$rooms = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rooms[] = ['id' => (string)$row['id'], 'name' => $row['name']];
}

mysqli_stmt_close($stmt);

echo json_encode(['status' => true, 'data' => $rooms]);
