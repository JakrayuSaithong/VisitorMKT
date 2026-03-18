<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

try {
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
    $sqlDeleteSchedule = "DELETE FROM VisitorSchedule WHERE VisitorFormId = ?";
    sqlsrv_query($konnext_DB64, $sqlDeleteSchedule, [$id]);

    if (!empty($_POST['schedule'])) {
        $schedules = json_decode($_POST['schedule'], true);
        if (is_array($schedules)) {
            foreach ($schedules as $s) {
                $visitDate = !empty($s['date'])
                    ? DateTime::createFromFormat('d/m/Y', $s['date'])->format('Y-m-d')
                    : null;

                $sql2 = "INSERT INTO VisitorSchedule
                         (VisitorFormId, ReserveType, VisitDate, TimeStart, TimeEnd, MeetingRoom, MeetingName)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
                $params2 = [$id, $s['reserve'] ?? null, $visitDate, $s['time_start'] ?? null, $s['time_end'] ?? null, $s['room'] ?? null, $s['roomname'] ?? null];

                if (!sqlsrv_query($konnext_DB64, $sql2, $params2)) {
                    throw new Exception("ไม่สามารถบันทึกตารางเวลาได้: " . print_r(sqlsrv_errors(), true));
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
} catch (Exception $e) {
    sqlsrv_rollback($konnext_DB64);
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
