<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

try {
    if (!$konnext_DB64) {
        throw new Exception("ไม่สามารถเชื่อมต่อฐานข้อมูลได้");
    }
    sqlsrv_begin_transaction($konnext_DB64);

    $VisitorCode = $_SESSION['VisitorMKT_code'] ?? 'SYSTEM';
    $id = $_POST['id'] ?? null;

    if (!$id) {
        throw new Exception("ไม่พบ ID สำหรับการแก้ไข");
    }

    // --- จัดการ Comment (ถ้ามี) ---
    $newComment = isset($_POST['Comment']) ? trim($_POST['Comment']) : '';
    $commentJson = null;

    if ($newComment !== '') {
        // ดึง Comment เดิม
        $sqlGetComment = "SELECT Comment FROM VisitorForm WHERE Id = ?";
        $stmtGet = sqlsrv_query($konnext_DB64, $sqlGetComment, [$id]);
        $existingComments = [];

        if ($stmtGet) {
            $row = sqlsrv_fetch_array($stmtGet, SQLSRV_FETCH_ASSOC);
            if ($row && !empty($row['Comment'])) {
                $decoded = json_decode($row['Comment'], true);
                if (is_array($decoded)) {
                    $existingComments = $decoded;
                }
            }
        }

        // สร้าง comment object ใหม่
        $newStatus = $_POST['Status'] ?? 0;
        $commentEntry = [
            'date' => date('Y-m-d H:i:s'),
            'user' => $VisitorCode,
            'status' => (int)$newStatus,
            'text' => $newComment
        ];

        // Append comment ใหม่
        $existingComments[] = $commentEntry;
        $commentJson = json_encode($existingComments, JSON_UNESCAPED_UNICODE);
    }

    // ตรวจสอบว่าเป็นการอัพเดทแบบ Comment-only (Cancel/Rework) หรือ Full form update
    $isCommentOnly = !empty($_POST['Comment']) && !isset($_POST['SalesDetail']) && !isset($_POST['schedule']);

    $data = [];
    $updateParts = [];

    if ($isCommentOnly) {
        // อัพเดทเฉพาะ Status, UserEdit, Comment
        $data['Status'] = $_POST['Status'] ?? 0;
        $updateParts[] = "Status = ?";

        $data['UserEdit'] = $VisitorCode;
        $updateParts[] = "UserEdit = ?";

        if ($commentJson !== null) {
            $data['Comment'] = $commentJson;
            $updateParts[] = "Comment = ?";
        }
    } else {
        // Full form update
        $fields = [
            'Objective',
            'ObjectiveOther',
            'SalesDetail',
            'CorporateDetail',
            'LecturerDetail',
            'PRDDetail',
            'CustomerNameThai',
            'CustomerNameForeign',
            'CustomerTotal',
            'Travel',
            'CustomerCar',
            'CarNumber',
            'DriverNumber',
            'Remark',
            'CusFood',
            'NumberDiners',
            'Food',
            'OtherMenu',
            'RemarkFood',
            'Status',
            'UserEdit'
        ];

        foreach ($fields as $f) {
            if ($f === 'UserEdit') {
                $data[$f] = $VisitorCode;
                $updateParts[] = "$f = ?";
            } else {
                $val = $_POST[$f] ?? null;
                $data[$f] = is_array($val)
                    ? json_encode($val, JSON_UNESCAPED_UNICODE)
                    : trim($val ?? '');
                $updateParts[] = "$f = ?";
            }
        }

        // เพิ่ม Comment field ถ้ามี
        if ($commentJson !== null) {
            $data['Comment'] = $commentJson;
            $updateParts[] = "Comment = ?";
        }
    }

    // Add LastUpdate timestamp
    $updateParts[] = "EditAt = GETDATE()";

    $sql = "UPDATE VisitorForm SET " . implode(", ", $updateParts) . " WHERE Id = ?";
    $params = array_values($data);
    $params[] = $id;

    $stmt = sqlsrv_query($konnext_DB64, $sql, $params);
    if (!$stmt) throw new Exception("ไม่สามารถบันทึกการแก้ไข VisitorForm ได้: " . print_r(sqlsrv_errors(), true));

    // --- ตาราง schedule และไฟล์ (เฉพาะ Full form update) ---
    if (!$isCommentOnly) {
    // ดึงข้อมูล schedule เดิมก่อนลบ เพื่อใช้ลบ work_progress_091
    $existingSchedules = [];
    $sqlGetSched = "SELECT ReserveType, VisitDate, TimeStart, MeetingRoom FROM VisitorSchedule WHERE VisitorFormId = ?";
    $stmtGetSched = sqlsrv_query($konnext_DB64, $sqlGetSched, [$id]);
    if ($stmtGetSched) {
        while ($r = sqlsrv_fetch_array($stmtGetSched, SQLSRV_FETCH_ASSOC)) {
            $existingSchedules[] = $r;
        }
    }

    $sqlDeleteSchedule = "DELETE FROM VisitorSchedule WHERE VisitorFormId = ?";
    sqlsrv_query($konnext_DB64, $sqlDeleteSchedule, [$id]);

    // ลบรายการจอง work_progress_091 ที่ตรงกับ schedule เดิม
    foreach ($existingSchedules as $es) {
        $esDate = null;
        if (!empty($es['VisitDate'])) {
            $esDate = ($es['VisitDate'] instanceof DateTime)
                ? $es['VisitDate']->format('Y-m-d')
                : date('Y-m-d', strtotime($es['VisitDate']));
        }
        $esTimeStart = $es['TimeStart'] ?? null;
        $esRoom      = $es['MeetingRoom'] ?? null;
        if ($esDate && $esTimeStart && $esRoom) {
            $sqlDelErp = "DELETE FROM work_progress_091 WHERE site_f_1135 = ? AND DATE(site_f_1134) = ? AND TIME(site_f_1134) = ?";
            $stmtDelErp = mysqli_prepare($konnext_lqsym, $sqlDelErp);
            if ($stmtDelErp) {
                mysqli_stmt_bind_param($stmtDelErp, 'sss', $esRoom, $esDate, $esTimeStart);
                mysqli_stmt_execute($stmtDelErp);
                mysqli_stmt_close($stmtDelErp);
            }
        }
    }

    if (!empty($_POST['schedule'])) {
        $schedules = json_decode($_POST['schedule'], true);
        if (is_array($schedules)) {
            foreach ($schedules as $s) {
                $visitDate = null;
                if (!empty($s['date'])) {
                    $dt = DateTime::createFromFormat('d/m/Y', $s['date']);
                    $visitDate = $dt ? $dt->format('Y-m-d') : null;
                }
                $subject   = $s['subject'] ?? '';
                $timeStart = $s['time_start'] ?? '';
                $timeEnd   = $s['time_end'] ?? '';
                $roomId    = $s['room'] ?? null;

                $sql2 = "INSERT INTO VisitorSchedule
                         (VisitorFormId, ReserveType, VisitDate, TimeStart, TimeEnd, MeetingRoom, MeetingName, Subject)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $params2 = [$id, $s['reserve'] ?? null, $visitDate, $timeStart, $timeEnd, $roomId, $s['roomname'] ?? null, $subject];

                if (!sqlsrv_query($konnext_DB64, $sql2, $params2)) {
                    throw new Exception("ไม่สามารถบันทึกตารางเวลาได้: " . print_r(sqlsrv_errors(), true));
                }

                // Insert into work_progress_091 (MySQL rypsoftcom_erp2)
                if ($visitDate && $timeStart && $timeEnd && $roomId) {
                    $startDatetime = $visitDate . ' ' . $timeStart . ':00';
                    $endDatetime   = $visitDate . ' ' . $timeEnd   . ':00';
                    $startTs  = strtotime($startDatetime);
                    $endTs    = strtotime($endDatetime);
                    $duration = ($startTs !== false && $endTs !== false && $endTs > $startTs) ? ($endTs - $startTs) : 0;
                    $bookingStatus = 'Meeting';

                    $sqlErp = "INSERT INTO work_progress_091
                        (site_f_1133, site_f_3892, site_f_1171, site_f_1172, site_f_1134,
                         site_f_1173, site_f_1174, site_f_1175, site_f_1135, site_f_1136,
                         site_f_1209, site_f_1210, site_f_3567)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
                    $stmtErp = mysqli_prepare($konnext_lqsym, $sqlErp);
                    if (!$stmtErp) {
                        throw new Exception("ไม่สามารถเตรียม SQL สำหรับ work_progress_091: " . mysqli_error($konnext_lqsym));
                    }
                    mysqli_stmt_bind_param($stmtErp, 'sssssssssssi',
                        $subject, $bookingStatus, $visitDate, $timeStart, $startDatetime,
                        $visitDate, $timeEnd, $endDatetime,
                        $roomId, $VisitorCode, $VisitorCode, $duration);
                    if (!mysqli_stmt_execute($stmtErp)) {
                        throw new Exception("ไม่สามารถบันทึก work_progress_091: " . mysqli_stmt_error($stmtErp));
                    }
                    mysqli_stmt_close($stmtErp);
                }
            }
        }
    }

    // --- อัปโหลดไฟล์ (เพิ่มไฟล์ใหม่) ---
    $uploadDir = "../file/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    // นามสกุลไฟล์ที่อนุญาต
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
    $allowedMimeTypes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp',
        'application/pdf',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    $fileTypes = ['files_detail' => 'detail', 'files_lecturer' => 'lecturer'];

    foreach ($fileTypes as $key => $type) {
        if (!empty($_FILES[$key]['name'][0])) {
            foreach ($_FILES[$key]['name'] as $i => $name) {
                $tmp = $_FILES[$key]['tmp_name'][$i];

                // ตรวจสอบนามสกุลไฟล์ (ป้องกัน double extension เช่น .php.jpg)
                $nameLower = strtolower(basename($name));
                $parts = explode('.', $nameLower);
                if (count($parts) < 2) {
                    throw new Exception("ไฟล์ $name ไม่มีนามสกุล");
                }
                $ext = end($parts);
                // ตรวจสอบว่ามีนามสกุลอันตรายซ่อนอยู่ (double extension)
                $dangerousExts = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps', 'pht', 'phar',
                                  'exe', 'sh', 'bat', 'cmd', 'com', 'vbs', 'js', 'wsf', 'cgi', 'pl',
                                  'py', 'rb', 'asp', 'aspx', 'jsp', 'war', 'svg', 'htaccess', 'htpasswd'];
                foreach ($parts as $part) {
                    if (in_array($part, $dangerousExts)) {
                        throw new Exception("ไฟล์ $name มีนามสกุลที่ไม่อนุญาต ($part)");
                    }
                }
                if (!in_array($ext, $allowedExtensions)) {
                    throw new Exception("ไฟล์ $name ไม่อนุญาต (รองรับเฉพาะ รูปภาพ, PDF, Word, Excel)");
                }

                // ตรวจสอบ MIME type จากเนื้อหาไฟล์จริง
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $detectedMime = $finfo->file($tmp);
                if (!in_array($detectedMime, $allowedMimeTypes)) {
                    throw new Exception("ไฟล์ $name มีเนื้อหาไม่ตรงกับประเภทที่อนุญาต (ตรวจพบ: $detectedMime)");
                }

                // ตรวจสอบเนื้อหาไฟล์ว่ามี PHP/script code ซ่อนอยู่หรือไม่
                $fileContent = file_get_contents($tmp, false, null, 0, 8192);
                $dangerousPatterns = [
                    '/<\?php/i', '/<\?=/i', '/<\?[^x]/i',
                    '/<script\b/i', '/<%/i',
                    '/eval\s*\(/i', '/base64_decode\s*\(/i',
                    '/system\s*\(/i', '/exec\s*\(/i', '/passthru\s*\(/i',
                    '/shell_exec\s*\(/i', '/proc_open\s*\(/i', '/popen\s*\(/i'
                ];
                foreach ($dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $fileContent)) {
                        throw new Exception("ไฟล์ $name มีเนื้อหาที่อาจเป็นอันตราย");
                    }
                }

                $safeName = uniqid() . "_" . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($name));
                $destPath = $uploadDir . $safeName;

                if (!move_uploaded_file($tmp, $destPath)) {
                    throw new Exception("ไม่สามารถอัปโหลดไฟล์ $name ได้");
                }

                $sqlFile = "INSERT INTO VisitorFiles (VisitorFormId, FileType, FilePath, FileName)
                            VALUES (?, ?, ?, ?)";
                if (!sqlsrv_query($konnext_DB64, $sqlFile, [$id, $type, $safeName, $name])) {
                    throw new Exception("ไม่สามารถบันทึกข้อมูลไฟล์ $name");
                }
            }
        }
    }
    } // end if (!$isCommentOnly)

    sqlsrv_commit($konnext_DB64);
    echo json_encode(["status" => true, "message" => "บันทึกข้อมูลแก้ไขสำเร็จ"]);
} catch (\Throwable $e) {
    if ($konnext_DB64) {
        sqlsrv_rollback($konnext_DB64);
    }
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
