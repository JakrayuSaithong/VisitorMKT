<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;
    $userCode = $_SESSION['VisitorMKT_code'] ?? 'SYSTEM';

    if (!$id || $status === null) {
        throw new Exception("Missing required parameters (id, status)");
    }

    // Update เฉพาะ Status และ UserEdit
    $sql = "UPDATE VisitorForm SET Status = ?, UserEdit = ?, EditAt = GETDATE() WHERE Id = ?";
    $stmt = sqlsrv_query($konnext_DB64, $sql, [$status, $userCode, $id]);

    if (!$stmt) {
        throw new Exception("Update error: " . print_r(sqlsrv_errors(), true));
    }

    // ถ้า Status = 3 (Approved) ให้สร้าง record ใน VisitorAcknowledg สำหรับทุกฝ่าย
    if ((int)$status === 3) {
        // รหัสฝ่ายที่ต้องรับทราบ: TC=54, QC=87, PRD=83, สื่อสาร=63, HR=31, วางแผน=61, ขนส่ง=65
        $divisions = [54, 87, 83, 63, 31, 61, 65];

        // ตรวจสอบว่ามี record อยู่แล้วหรือไม่
        $checkSql = "SELECT COUNT(*) as cnt FROM VisitorAcknowledg WHERE VisitorFormId = ?";
        $checkStmt = sqlsrv_query($konnext_DB64, $checkSql, [$id]);
        $existing = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);

        if ((int)$existing['cnt'] === 0) {
            // สร้าง record ใหม่สำหรับทุกฝ่าย
            foreach ($divisions as $divCode) {
                $insertSql = "INSERT INTO VisitorAcknowledg (VisitorFormId, DivisionCode, IsAcknowledged) VALUES (?, ?, 0)";
                sqlsrv_query($konnext_DB64, $insertSql, [$id, $divCode]);
            }
        }

        // --- ส่งแจ้งเตือน ---
        $titelnoti = "แจ้งเตือน Visitor";
        $datetime = date('d/m/Y H:i:s');
        $message = "มีเอกสารเพื่อแจ้งเตือนในส่วนงาน" . "\nที่ท่านรับผิดชอบ" . "\nเมื่อ " . $datetime;

        // 1. แจ้งเตือนกลุ่ม 'แจ้งเตือน'
        $sqlNotiGroup = "SELECT group_user FROM Visi_GroupNoti WHERE group_name = ?";
        $stmtNotiGroup = sqlsrv_query($konnext_DB64, $sqlNotiGroup, ['แจ้งเตือน']);
        if ($stmtNotiGroup) {
            $rowNotiGroup = sqlsrv_fetch_array($stmtNotiGroup, SQLSRV_FETCH_ASSOC);
            if ($rowNotiGroup && !empty($rowNotiGroup['group_user'])) {
                $usersToNotify = explode(',', $rowNotiGroup['group_user']);
                foreach ($usersToNotify as $userCode) {
                    $userCode = trim($userCode);
                    if (!empty($userCode)) {
                        // $userData = mydata($userCode);
                        // $token = $userData['TokenMD5'] ?? '';
                        $DataE = encryptIt(json_encode([
                            "auth_user_name" => $userCode,
                            "date_U" => time(),
                            "FromApp" => "Noti"
                        ], JSON_UNESCAPED_UNICODE));
                        $url = "https://it.asefa.co.th/visitorMKT/visitor_formupdate.php?page=view&id=" . $id . "&DataE=" . $DataE;
                        Notify($titelnoti, $message, $userCode, $url);
                    }
                }
            }
        }

        // 2. เช็ค CorporateDetail ว่า serviceType = 'use_service' หรือไม่
        $sqlCorp = "SELECT CorporateDetail FROM VisitorForm WHERE Id = ?";
        $stmtCorp = sqlsrv_query($konnext_DB64, $sqlCorp, [$id]);
        if ($stmtCorp) {
            $rowCorp = sqlsrv_fetch_array($stmtCorp, SQLSRV_FETCH_ASSOC);
            if ($rowCorp && !empty($rowCorp['CorporateDetail'])) {
                $corpDetail = json_decode($rowCorp['CorporateDetail'], true);
                if (isset($corpDetail['serviceType']) && $corpDetail['serviceType'] === 'use_service') {
                    // แจ้งเตือนกลุ่ม 'แจ้งเตือนสื่อสาร'
                    $sqlNotiComm = "SELECT group_user FROM Visi_GroupNoti WHERE group_name = ?";
                    $stmtNotiComm = sqlsrv_query($konnext_DB64, $sqlNotiComm, ['แจ้งเตือนสื่อสาร']);
                    if ($stmtNotiComm) {
                        $rowNotiComm = sqlsrv_fetch_array($stmtNotiComm, SQLSRV_FETCH_ASSOC);
                        if ($rowNotiComm && !empty($rowNotiComm['group_user'])) {
                            $commUsersToNotify = explode(',', $rowNotiComm['group_user']);
                            foreach ($commUsersToNotify as $commUserCode) {
                                $commUserCode = trim($commUserCode);
                                if (!empty($commUserCode)) {
                                    // $commUserData = mydata($commUserCode);
                                    // $commToken = $commUserData['TokenMD5'] ?? '';
                                    $DataE = encryptIt(json_encode([
                                        "auth_user_name" => $commUserCode,
                                        "date_U" => time(),
                                        "FromApp" => "Noti"
                                    ], JSON_UNESCAPED_UNICODE));
                                    $commUrl = "https://it.asefa.co.th/visitorMKT/visitor_formupdate.php?page=view&id=" . $id . "&DataE=" . $DataE;
                                    Notify($titelnoti, $message, $commUserCode, $commUrl);
                                }
                            }
                        }
                    }
                }
            }
        }

        // 3. เช็ค Travel ว่ามี Provide หรือไม่ → แจ้งเตือนกลุ่ม 'แจ้งเตือนขนส่ง'
        $sqlTravel = "SELECT Travel FROM VisitorForm WHERE Id = ?";
        $stmtTravel = sqlsrv_query($konnext_DB64, $sqlTravel, [$id]);
        if ($stmtTravel) {
            $rowTravel = sqlsrv_fetch_array($stmtTravel, SQLSRV_FETCH_ASSOC);
            if ($rowTravel && !empty($rowTravel['Travel'])) {
                $travelArr = json_decode($rowTravel['Travel'], true);
                if (is_array($travelArr) && in_array('Provide', $travelArr)) {
                    $sqlNotiTransport = "SELECT group_user FROM Visi_GroupNoti WHERE group_name = ?";
                    $stmtNotiTransport = sqlsrv_query($konnext_DB64, $sqlNotiTransport, ['แจ้งเตือนขนส่ง']);
                    if ($stmtNotiTransport) {
                        $rowNotiTransport = sqlsrv_fetch_array($stmtNotiTransport, SQLSRV_FETCH_ASSOC);
                        if ($rowNotiTransport && !empty($rowNotiTransport['group_user'])) {
                            $transportUsers = explode(',', $rowNotiTransport['group_user']);
                            foreach ($transportUsers as $transportUserCode) {
                                $transportUserCode = trim($transportUserCode);
                                if (!empty($transportUserCode)) {
                                    $DataE = encryptIt(json_encode([
                                        "auth_user_name" => $transportUserCode,
                                        "date_U" => time(),
                                        "FromApp" => "Noti"
                                    ], JSON_UNESCAPED_UNICODE));
                                    $transportUrl = "https://it.asefa.co.th/visitorMKT/visitor_formupdate.php?page=view&id=" . $id . "&DataE=" . $DataE;
                                    Notify($titelnoti, "มีเอกสารที่ต้องจัดรถรับลูกค้า" . "\nกรุณาตรวจสอบ" . "\nเมื่อ " . $datetime, $transportUserCode, $transportUrl);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    echo json_encode(["status" => true, "message" => "อัพเดทสถานะสำเร็จ"], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
