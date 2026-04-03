<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

function generateDocNo($conn)
{
    $thaiYear = date('Y') + 543;
    $year = substr($thaiYear, -2);
    $month = date('m');

    $prefix = "VC-{$year}{$month}";

    $sql = "SELECT TOP 1 DocNo FROM VisitorForm 
            WHERE DocNo LIKE ? 
            ORDER BY DocNo DESC";
    $params = ["%{$prefix}%"];
    $stmt = sqlsrv_query($conn, $sql, $params);

    $next = 1;
    if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
        $lastDocNo = $row['DocNo'];
        $lastRun = intval(substr($lastDocNo, -3));
        $next = $lastRun + 1;
    }

    return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
}

try {
    sqlsrv_begin_transaction($konnext_DB64);

    $identityColumn = 'Id';
    $VisitorCode = $_SESSION['VisitorMKT_code'] ?? 'SYSTEM';
    $VisitorID = $_SESSION['VisitorMKT_idref'] ?? 'SYSTEM';
    $DocNo = generateDocNo($konnext_DB64);

    $fields = [
        'DocNo',
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
        'UserCreated',
        'UserEdit'
    ];

    $data = [];
    foreach ($fields as $f) {
        switch ($f) {
            case 'DocNo':
                $data[$f] = $DocNo;
                break;
            case 'UserCreated':
            case 'UserEdit':
                $data[$f] = $VisitorCode;
                break;
            default:
                $val = $_POST[$f] ?? null;
                $data[$f] = is_array($val)
                    ? json_encode($val, JSON_UNESCAPED_UNICODE)
                    : trim($val ?? '');
        }
    }

    $columns = implode(",", array_keys($data));
    $placeholders = implode(",", array_fill(0, count($data), "?"));

    $sql = "INSERT INTO VisitorForm ($columns)
            OUTPUT INSERTED.$identityColumn
            VALUES ($placeholders)";

    $stmt = sqlsrv_query($konnext_DB64, $sql, array_values($data));
    if (!$stmt) throw new Exception("ไม่สามารถบันทึก VisitorForm ได้: " . print_r(sqlsrv_errors(), true));

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $visitorId = $row[$identityColumn] ?? null;

    if (!$visitorId) throw new Exception("ไม่สามารถดึงค่า $identityColumn จาก VisitorForm ได้หลังจาก INSERT");

    // --- ตาราง schedule ---
    $bookingResults = [];
    if (!empty($_POST['schedule'])) {
        $schedules = json_decode($_POST['schedule'], true);
        if (is_array($schedules)) {
            foreach ($schedules as $s) {
                $visitDate = !empty($s['date'])
                    ? DateTime::createFromFormat('d/m/Y', $s['date'])->format('Y-m-d')
                    : null;
                $subject   = $s['subject'] ?? '';
                $timeStart = substr($s['time_start'] ?? '', 0, 5); // normalize HH:MM:SS → HH:MM
                $timeEnd   = substr($s['time_end']   ?? '', 0, 5);
                $roomId    = $s['room'] ?? null;
                $reserveType = $s['reserve'] ?? null;

                $zoomType = $s['zoom_type'] ?? 'Meeting';
                $scheduleOk = false;
                $meetingOk = false;
                $meetingId = null;

                // Insert into work_progress_091 FIRST (MySQL rypsoftcom_erp2)
                if ($visitDate && $timeStart && $timeEnd && $roomId) {
                    $startDatetime = $visitDate . ' ' . $timeStart . ':00';
                    $endDatetime   = $visitDate . ' ' . $timeEnd   . ':00';
                    $startTs  = strtotime($startDatetime);
                    $endTs    = strtotime($endDatetime);
                    $duration = ($startTs !== false && $endTs !== false && $endTs > $startTs) ? ($endTs - $startTs) : 0;
                    $bookingStatus = ($reserveType === 'zoom') ? $zoomType : 'Meeting';

                    // สำหรับ Zoom: ดึง site_f_702 จาก work_progress_040 มาใส่ site_f_1183
                    $zoomLinkValue = '';
                    if ($reserveType === 'zoom') {
                        $sqlGetZoomLink = "SELECT site_f_702 FROM work_progress_040 WHERE site_id_40 = ?";
                        $stmtZoom = mysqli_prepare($konnext_lqsym, $sqlGetZoomLink);
                        if ($stmtZoom) {
                            mysqli_stmt_bind_param($stmtZoom, 's', $roomId);
                            mysqli_stmt_execute($stmtZoom);
                            $resultZoom = mysqli_stmt_get_result($stmtZoom);
                            if ($rowZoom = mysqli_fetch_assoc($resultZoom)) {
                                $zoomLinkValue = $rowZoom['site_f_702'] ?? null;
                            }
                            mysqli_stmt_close($stmtZoom);
                        }
                    }

                    $sqlErp = "INSERT INTO work_progress_091
                        (site_f_1133, site_f_3892, site_f_1171, site_f_1172, site_f_1134,
                         site_f_1173, site_f_1174, site_f_1175, site_f_1135, site_f_1136,
                         site_f_1209, site_f_1210, site_f_3567, site_f_1183, site_f_5108,
                         site_f_5827, site_f_6702, site_f_7412, site_f_7413, site_f_5107,
                         site_f_5106, site_f_3057, site_f_3572, site_f_5823, site_f_3604,
                         site_f_3605, site_f_3616, site_f_3806, site_f_3807, site_f_3808,
                         site_f_3907, site_f_3970, site_f_3980, site_f_3988, site_f_3989,
                         site_f_4000, site_f_4001, site_f_4666, site_f_4676, site_f_4683,
                         site_f_4702, site_f_4703, site_f_4771, site_f_1211, site_f_1227,
                         site_f_1236, site_f_1281)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, '0000-00-00 00:00:00',
                                0, '', 0, 0, 0, 0, 600, '', 0, '', '', '', '', '', '',
                                '', 0, 1, '', '', 0, 0, 0, 0, '', '', '', 0, '', 0, 0, 0)";
                    $stmtErp = mysqli_prepare($konnext_lqsym, $sqlErp);
                    if (!$stmtErp) {
                        throw new Exception("ไม่สามารถเตรียม SQL สำหรับ work_progress_091: " . mysqli_error($konnext_lqsym));
                    }
                    mysqli_stmt_bind_param($stmtErp, 'sssssssssssis',
                        $subject, $bookingStatus, $visitDate, $timeStart, $startDatetime,
                        $visitDate, $timeEnd, $endDatetime,
                        $roomId, $VisitorID, $VisitorID, $duration, $zoomLinkValue);
                    if (mysqli_stmt_execute($stmtErp)) {
                        $meetingOk = true;
                        $meetingId = mysqli_insert_id($konnext_lqsym);
                    } else {
                        throw new Exception("ไม่สามารถบันทึก work_progress_091: " . mysqli_stmt_error($stmtErp));
                    }
                    mysqli_stmt_close($stmtErp);
                }

                // Then INSERT VisitorSchedule with IDMeeting already set
                $sql2 = "INSERT INTO VisitorSchedule
                         (VisitorFormId, ReserveType, VisitDate, TimeStart, TimeEnd, MeetingRoom, MeetingName, Subject, ZoomType, IDMeeting)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $params2 = [$visitorId, $reserveType, $visitDate, $timeStart, $timeEnd, $roomId, $s['roomname'] ?? null, $subject,
                    ($reserveType === 'zoom' ? $zoomType : null), $meetingId];

                if (sqlsrv_query($konnext_DB64, $sql2, $params2)) {
                    $scheduleOk = true;
                } else {
                    throw new Exception("ไม่สามารถบันทึกตารางเวลาได้: " . print_r(sqlsrv_errors(), true));
                }

                // Get inserted VisitorSchedule Id
                $scheduleIdRow = sqlsrv_fetch_array(sqlsrv_query($konnext_DB64, "SELECT @@IDENTITY AS Id"), SQLSRV_FETCH_ASSOC);
                $newScheduleId = $scheduleIdRow['Id'] ?? null;

                // สร้าง booking result สำหรับแสดง badge
                $bookingResult = [
                    'subject' => $subject,
                    'roomname' => $s['roomname'] ?? '',
                    'reserve' => $reserveType,
                    'scheduleOk' => $scheduleOk,
                    'meetingOk' => $meetingOk,
                    'meetingId' => $meetingId
                ];
                if ($reserveType === 'zoom' && $meetingId) {
                    $dataForEncrypt = json_encode([
                        "FromApp"   => 1,
                        "Mode"      => "Meeting",
                        "ModeID"    => $meetingId,
                        "URL"       => $zoomLinkValue,
                        "XDateTime" => date('Y-m-d H:i:s')
                    ]);
                    $DataEMeeting = encryptIt($dataForEncrypt);
                    $bookingResult['zoomUrl'] = 'https://erpapp.asefa.co.th/vx_GotoURL.php?DataE=' . urlencode($DataEMeeting);
                }
                $bookingResults[] = $bookingResult;
            }
        }
    }

    // --- อัปโหลดไฟล์ ---
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
                if (!sqlsrv_query($konnext_DB64, $sqlFile, [$visitorId, $type, $safeName, $name])) {
                    throw new Exception("ไม่สามารถบันทึกข้อมูลไฟล์ $name");
                }
            }
        }
    }

    sqlsrv_commit($konnext_DB64);
    echo json_encode(["status" => true, "message" => "บันทึกข้อมูลสำเร็จ", "DocNo" => $DocNo, "bookingResults" => $bookingResults]);
} catch (Exception $e) {
    sqlsrv_rollback($konnext_DB64);
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
