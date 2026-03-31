<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$scheduleId = intval($_POST['schedule_id'] ?? 0);
$meetingId  = intval($_POST['meeting_id'] ?? 0);

if (!$scheduleId) {
    echo json_encode(['status' => false, 'message' => 'ไม่พบ schedule_id']);
    exit;
}

try {
    // Delete from VisitorSchedule
    $stmt = sqlsrv_query($konnext_DB64, "DELETE FROM VisitorSchedule WHERE Id = ?", [$scheduleId]);
    if (!$stmt) {
        throw new Exception("ไม่สามารถลบ VisitorSchedule ได้: " . print_r(sqlsrv_errors(), true));
    }

    // Delete from work_progress_091 if meetingId provided
    if ($meetingId) {
        $stmtErp = mysqli_prepare($konnext_lqsym, "DELETE FROM work_progress_091 WHERE site_id_91 = ?");
        if ($stmtErp) {
            mysqli_stmt_bind_param($stmtErp, 'i', $meetingId);
            mysqli_stmt_execute($stmtErp);
            mysqli_stmt_close($stmtErp);
        }
    }

    echo json_encode(['status' => true, 'message' => 'ลบการจองเรียบร้อยแล้ว']);
} catch (Exception $e) {
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}
