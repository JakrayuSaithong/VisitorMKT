<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$id = $_POST['id'] ?? $_GET['id'] ?? null;
if (!$id || !$action) {
    echo json_encode(["status" => false, "message" => "Missing parameters"]);
    exit;
}

$perm = $_SESSION['VisitorMKT_permision'] ?? [];
$user = $_SESSION['VisitorMKT_code'] ?? null;

function isAdmin($perm) {
    return (is_array($perm) && in_array('Admin', $perm)) || $perm === 'Admin';
}

function hasPerm($perm, $target) {
    if (isAdmin($perm)) return true;
    if (is_array($perm)) return in_array($target, $perm) || in_array((int)$target, $perm);
    return (string)$perm === (string)$target || (int)$perm === (int)$target;
}

function roleFromPerm($perm) {
    // Division codes: TC=54, QC=87, PRD=83, สื่อสาร=63, HR=31, วางแผน=61, ขนส่ง=65
    if (hasPerm($perm, 54)) return 'tc';
    if (hasPerm($perm, 87)) return 'qc';
    if (hasPerm($perm, 83)) return 'prd';
    if (hasPerm($perm, 63)) return 'communication'; // สื่อสาร (ใช้ 63 เหมือน MKT)
    if (hasPerm($perm, 31)) return 'hr';
    if (hasPerm($perm, 61)) return 'planning'; // วางแผน
    if (hasPerm($perm, 65)) return 'transport'; // ขนส่ง
    return null;
}

// -------------------- helper --------------------
function getForm($conn, $id) {
    $sql = "SELECT Id, Status, Objective FROM VisitorForm WHERE Id = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    if ($stmt && $r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) return $r;
    return null;
}

function getAck($conn, $id) {
    $sql = "SELECT * FROM VisitorAcknowledge WHERE VisitorFormId = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    if ($stmt && $r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) return $r;
    return null;
}

function ensureAck($conn, $id) {
    $row = getAck($conn, $id);
    if ($row) return $row;
    $sql = "INSERT INTO VisitorAcknowledge (VisitorFormId, AckJson) VALUES (?, N'[]')";
    sqlsrv_query($conn, $sql, [$id]);
    return getAck($conn, $id);
}

function updateStatus($conn, $id, $status) {
    $sql = "UPDATE VisitorForm SET Status = ? WHERE Id = ?";
    sqlsrv_query($conn, $sql, [$status, $id]);
}

function appendAckJson($conn, $id, $entry) {
    $ack = ensureAck($conn, $id);
    $list = [];
    if (!empty($ack['AckJson'])) {
        $tmp = json_decode($ack['AckJson'], true);
        if (is_array($tmp)) $list = $tmp;
    }
    $list[] = $entry;
    $newJson = json_encode($list, JSON_UNESCAPED_UNICODE);
    $sql = "UPDATE VisitorAcknowledge SET AckJson = ? WHERE Id = ?";
    sqlsrv_query($conn, $sql, [$newJson, $ack['Id']]);
}

function setRoleFlag($conn, $id, $role, $value = 1) {
    $ack = ensureAck($conn, $id);
    $sql = "UPDATE VisitorAcknowledge SET $role = ? WHERE Id = ?";
    sqlsrv_query($conn, $sql, [$value, $ack['Id']]);
}

// -------------------- ack check --------------------
function allAckComplete($ack, $objectiveJson) {
    // ฝ่ายที่ต้องรับทราบทั้งหมด: TC, QC, PRD, Communication, HR, Planning, Transport
    $need = ['tc', 'qc', 'prd', 'communication', 'hr', 'planning', 'transport'];

    foreach ($need as $r) {
        if (empty($ack[$r])) return false;
    }
    return true;
}

// =========================================================

if ($action === 'get_state') {
    $ack = getAck($konnext_DB64, $id);
    echo json_encode(["status" => true, "data" => $ack], JSON_UNESCAPED_UNICODE);
    exit;
}

// ===== mk_accept =====================================================
if ($action === 'mk_accept') {
    if (!hasPerm($perm, 63) && !isAdmin($perm)) {
        echo json_encode(["status" => false, "message" => "ไม่มีสิทธิ์ (MK หรือ Admin เท่านั้น)"]);
        exit;
    }

    $form = getForm($konnext_DB64, $id);
    if (!$form) {
        echo json_encode(["status" => false, "message" => "ไม่พบฟอร์ม"]);
        exit;
    }

    if ((int)$form['Status'] !== 1) {
        echo json_encode(["status" => false, "message" => "สถานะต้องเป็น 1 เท่านั้น"]);
        exit;
    }

    updateStatus($konnext_DB64, $id, 2);
    setRoleFlag($konnext_DB64, $id, 'mk', 1);
    appendAckJson($konnext_DB64, $id, [
        "type" => "accept",
        "role" => "mk",
        "user" => $user,
        "date" => date('c')
    ]);

    echo json_encode(["status" => true, "message" => "รับงานสำเร็จ"]);
    exit;
}

// ===== dept_ack =====================================================
if ($action === 'dept_ack') {
    $role = roleFromPerm($perm);
    if (!$role && !isAdmin($perm)) {
        echo json_encode(["status" => false, "message" => "ไม่มีสิทธิ์ฝ่ายรับทราบ"]);
        exit;
    }

    if (isAdmin($perm) && !empty($_POST['role'])) {
        $role = $_POST['role'];
    }

    $form = getForm($konnext_DB64, $id);
    if (!$form) {
        echo json_encode(["status" => false, "message" => "ไม่พบฟอร์ม"]);
        exit;
    }

    // รับทราบได้เมื่อ Status = 3 (Approved) หรือ 5 (Submit)
    if ((int)$form['Status'] !== 3 && (int)$form['Status'] !== 5) {
        echo json_encode(["status" => false, "message" => "สถานะต้องเป็น 3 หรือ 5"]);
        exit;
    }

    setRoleFlag($konnext_DB64, $id, $role, 1);
    appendAckJson($konnext_DB64, $id, [
        "type" => "ack",
        "role" => $role,
        "user" => $user,
        "date" => date('c')
    ]);

    // ถ้า Status = 3 และเพิ่งรับทราบ ให้เปลี่ยนเป็น 5 (Submit)
    if ((int)$form['Status'] === 3) {
        updateStatus($konnext_DB64, $id, 5);
    }

    $ack = getAck($konnext_DB64, $id);
    $allDone = allAckComplete($ack, $form['Objective']);
    echo json_encode([
        "status" => true,
        "message" => $allDone ? "รับทราบครบทุกฝ่ายแล้ว" : "รับทราบเรียบร้อย",
        "data" => ["all_done" => $allDone]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ===== close_job =====================================================
if ($action === 'close_job') {
    if (!hasPerm($perm, 63) && !isAdmin($perm)) {
        echo json_encode(["status" => false, "message" => "ไม่มีสิทธิ์ปิดงาน (MK หรือ Admin เท่านั้น)"]);
        exit;
    }

    $form = getForm($konnext_DB64, $id);
    if (!$form) {
        echo json_encode(["status" => false, "message" => "ไม่พบฟอร์ม"]);
        exit;
    }

    // ปิดงานได้เมื่อ Status = 3 หรือ 5 และรับทราบครบแล้ว
    if ((int)$form['Status'] !== 3 && (int)$form['Status'] !== 5) {
        echo json_encode(["status" => false, "message" => "ปิดงานได้เฉพาะสถานะ 3 หรือ 5"]);
        exit;
    }

    $ack = getAck($konnext_DB64, $id);
    if (!$ack || !allAckComplete($ack, $form['Objective'])) {
        echo json_encode(["status" => false, "message" => "ยังรับทราบไม่ครบทุกฝ่าย"]);
        exit;
    }

    updateStatus($konnext_DB64, $id, 6); // เปลี่ยนเป็น 6 (Closed)
    appendAckJson($konnext_DB64, $id, [
        "type" => "close",
        "role" => "mk",
        "user" => $user,
        "date" => date('c')
    ]);

    echo json_encode(["status" => true, "message" => "ปิดงานสำเร็จ"]);
    exit;
}

// ===== cancel_job =====================================================
if ($action === 'cancel_job') {
    updateStatus($konnext_DB64, $id, 9);
    appendAckJson($konnext_DB64, $id, [
        "type" => "cancel",
        "user" => $user,
        "date" => date('c')
    ]);
    echo json_encode(["status" => true, "message" => "ยกเลิกงานสำเร็จ"]);
    exit;
}

// ===== fat_job =====================================================
if ($action === 'fat_job') {
    setRoleFlag($konnext_DB64, $id, 'qc', 1);
    appendAckJson($konnext_DB64, $id, [
        "type" => "fat",
        "user" => $user,
        "date" => date('c')
    ]);
    echo json_encode(["status" => true, "message" => "รับงาน FAT สำเร็จ"]);
    exit;
}

echo json_encode(["status" => false, "message" => "Unknown action"]);
exit;