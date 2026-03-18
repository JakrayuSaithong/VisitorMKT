<?php

/**
 * API: Send Invitation to Customers
 * ใช้ SQL Server (sqlsrv) + PHPMailer
 */

ob_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Register shutdown function to catch fatal errors
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => false,
            'message' => 'Fatal Error: ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line']
        ));
    }
});

require_once '../config/base.php';

// Function เข้ารหัส Visitor ID
function encodeVisitorId($id)
{
    $secretKey = 'ASEFA2024VMS'; // Secret key สำหรับเข้ารหัส
    $data = $id . '|' . time(); // เพิ่ม timestamp

    // XOR obfuscation
    $encoded = '';
    for ($i = 0; $i < strlen($data); $i++) {
        $encoded .= chr(ord($data[$i]) ^ ord($secretKey[$i % strlen($secretKey)]));
    }

    // Base64 encode และทำให้ URL safe
    $base64 = base64_encode($encoded);
    $urlSafe = strtr($base64, '+/', '-_');
    $urlSafe = rtrim($urlSafe, '=');

    return $urlSafe;
}

// ใช้ connection สำหรับ VisitorCompany database
$conn = $konnext_DB64;

try {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $visitorFormId = isset($_POST['visitor_form_id']) ? intval($_POST['visitor_form_id']) : 0;
    $jobIndex = isset($_POST['job_index']) ? intval($_POST['job_index']) : 0;

    if (!$visitorFormId) {
        throw new Exception('Missing visitor_form_id');
    }

    $customerName = isset($_POST['CustomerName']) ? $_POST['CustomerName'] : '[]';
    $position = isset($_POST['Position']) ? $_POST['Position'] : '[]';
    $phoneNumber = isset($_POST['PhoneNumber']) ? $_POST['PhoneNumber'] : '[]';
    $emails = isset($_POST['Emails']) ? $_POST['Emails'] : '[]';

    // ดึงข้อมูล SalesDetail จาก VisitorForm (SQL Server)
    $sql = "SELECT SalesDetail FROM VisitorForm WHERE Id = ?";
    $params = array($visitorFormId);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        throw new Exception('Query failed: ' . print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    if (!$row) {
        throw new Exception('VisitorForm not found');
    }

    $salesDetailJson = isset($row['SalesDetail']) ? $row['SalesDetail'] : '[]';
    $salesDetail = json_decode($salesDetailJson, true);

    if (!is_array($salesDetail) || empty($salesDetail)) {
        throw new Exception('SalesDetail is empty or invalid');
    }

    if (!isset($salesDetail[$jobIndex])) {
        throw new Exception("Job index {$jobIndex} not found");
    }

    // Update เฉพาะ 4 fields
    $salesDetail[$jobIndex]['CustomerName'] = $customerName;
    $salesDetail[$jobIndex]['Position'] = $position;
    $salesDetail[$jobIndex]['PhoneNumber'] = $phoneNumber;
    $salesDetail[$jobIndex]['Emails'] = $emails;

    $updatedJson = json_encode($salesDetail, JSON_UNESCAPED_UNICODE);

    // Update VisitorForm.SalesDetail (SQL Server)
    $updateSql = "UPDATE VisitorForm SET SalesDetail = ? WHERE Id = ?";
    $updateParams = array($updatedJson, $visitorFormId);
    $updateStmt = sqlsrv_query($conn, $updateSql, $updateParams);

    if ($updateStmt === false) {
        throw new Exception('Update failed: ' . print_r(sqlsrv_errors(), true));
    }
    sqlsrv_free_stmt($updateStmt);

    ob_end_clean();

    if ($action === 'update_contacts') {
        echo json_encode(array('success' => true, 'message' => 'Contact info updated'));
        exit;
    }

    // ========================================
    // SEND INVITATION EMAIL
    // ========================================
    if ($action === 'send_invitation') {
        // Load PHPMailer
        $phpMailerPath = __DIR__ . '/../vendor/phpmailer/src/PHPMailer.php';
        if (!file_exists($phpMailerPath)) {
            throw new Exception('PHPMailer not found. Please install PHPMailer.');
        }

        require_once __DIR__ . '/../vendor/phpmailer/src/Exception.php';
        require_once __DIR__ . '/../vendor/phpmailer/src/PHPMailer.php';
        require_once __DIR__ . '/../vendor/phpmailer/src/SMTP.php';

        // Get visitor form data for email content
        $formSql = "SELECT vf.*, vs.VisitDate, vs.TimeStart, vs.TimeEnd
                    FROM VisitorForm vf
                    LEFT JOIN VisitorSchedule vs ON vs.VisitorFormId = vf.Id
                    WHERE vf.Id = ?";
        $formParams = array($visitorFormId);
        $formStmt = sqlsrv_query($conn, $formSql, $formParams);
        $formData = sqlsrv_fetch_array($formStmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($formStmt);

        // ดึง Zone และ SalesName จาก SalesDetail ที่เลือก
        $currentJob = $salesDetail[$jobIndex];
        $zone = isset($currentJob['Zone']) ? $currentJob['Zone'] : 'ยังไม่ได้กำหนด';
        if (is_array($zone)) {
            $zone = implode(', ', $zone);
        } elseif (is_string($zone) && strpos($zone, '[') === 0) {
            $zoneArr = json_decode($zone, true);
            if (is_array($zoneArr)) {
                $zone = implode(', ', $zoneArr);
            }
        }

        // ดึง SalesName จาก mydata function
        $salesId = isset($currentJob['SalesName']) ? $currentJob['SalesName'] : '';
        $salesData = $salesId ? mydata($salesId) : null;
        $salesName = ($salesData && isset($salesData['FullName'])) ? $salesData['FullName'] : 'ทีมงาน ASEFA';
        $projectName = isset($currentJob['ProjectName']) ? $currentJob['ProjectName'] : '';

        // Format date
        $visitDate = 'ยังไม่ได้กำหนด';
        if (isset($formData['VisitDate']) && $formData['VisitDate']) {
            $dt = $formData['VisitDate'];
            if ($dt instanceof DateTime) {
                $thaiMonths = array('', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
                $visitDate = $dt->format('j') . ' ' . $thaiMonths[(int)$dt->format('n')] . ' ' . ($dt->format('Y') + 543);
            } else {
                $visitDate = $dt;
            }
        }

        $timeStart = isset($formData['TimeStart']) ? $formData['TimeStart'] : '';
        $timeEnd = isset($formData['TimeEnd']) ? $formData['TimeEnd'] : '';
        $timeStr = $timeStart ? ($timeStart . ($timeEnd ? " - $timeEnd" : '')) : 'ยังไม่ได้กำหนด';

        $emailList = json_decode($emails, true);
        if (!is_array($emailList)) $emailList = array();

        $nameList = json_decode($customerName, true);
        if (!is_array($nameList)) $nameList = array();

        $sentCount = 0;
        $errors = array();

        foreach ($emailList as $idx => $email) {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $name = isset($nameList[$idx]) ? $nameList[$idx] : 'ท่านลูกค้า';

            // Encode visitor ID for security
            $encodedId = encodeVisitorId($visitorFormId);

            // Generate verification link with encoded ID
            $verifyLink = "https://it.asefa.co.th/visitorMKT/visi_asefa.php?visi_token=" . $encodedId;

            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

                // SMTP Configuration (แก้ไขตามที่ใช้จริง)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'dew76762@gmail.com'; // เปลี่ยนเป็น email จริง
                $mail->Password = 'bhxbvluaqqaktgii'; // เปลี่ยนเป็น app password
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                // Sender
                $mail->setFrom('asefa@gmail.com', 'ASEFA Visitor Management');
                $mail->addAddress($email, $name);

                // Email Content
                $mail->isHTML(true);
                $mail->Subject = '=?UTF-8?B?' . base64_encode('ขอเรียนเชิญเยี่ยมชมโรงงาน ASEFA - ' . $projectName) . '?=';

                $mail->Body = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                </head>
                <body style="margin: 0; padding: 0; font-family: Sarabun, Tahoma, Arial, sans-serif; background-color: #f5f5f5;">
                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 40px 20px;">
                        <tr>
                            <td align="center">
                                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                                    
                                    <!-- Header -->
                                    <tr>
                                        <td style="background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%); padding: 40px 30px; text-align: center;">
                                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">🏭 ASEFA</h1>
                                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 16px;">ขอเรียนเชิญเยี่ยมชมโรงงาน</p>
                                        </td>
                                    </tr>
                                    
                                    <!-- Content -->
                                    <tr>
                                        <td style="padding: 40px 30px;">
                                            <p style="color: #333; font-size: 16px; line-height: 1.8; margin: 0 0 20px;">เรียน คุณ ' . htmlspecialchars($name) . ',</p>
                                            
                                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin: 0 0 30px;">
                                                บริษัท อาซีฟา จำกัด (มหาชน) มีความยินดีเป็นอย่างยิ่งที่จะขอเรียนเชิญท่านเข้าเยี่ยมชมโรงงานของเรา 
                                                ตามรายละเอียดดังต่อไปนี้
                                            </p>
                                            
                                            <!-- Info Box -->
                                            <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 12px; margin-bottom: 30px;">
                                                <tr>
                                                    <td style="padding: 25px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td style="padding: 10px 0; border-bottom: 1px solid #dee2e6;">
                                                                    <span style="color: #6c757d; font-size: 14px;">📋 โครงการ</span><br>
                                                                    <span style="color: #212529; font-size: 16px; font-weight: bold;">' . htmlspecialchars($projectName ? $projectName : '-') . '</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 10px 0; border-bottom: 1px solid #dee2e6;">
                                                                    <span style="color: #6c757d; font-size: 14px;">📅 วันที่</span><br>
                                                                    <span style="color: #212529; font-size: 16px; font-weight: bold;">' . htmlspecialchars($visitDate) . '</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 10px 0; border-bottom: 1px solid #dee2e6;">
                                                                    <span style="color: #6c757d; font-size: 14px;">🕐 เวลา</span><br>
                                                                    <span style="color: #212529; font-size: 16px; font-weight: bold;">' . htmlspecialchars($timeStr) . '</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 10px 0; border-bottom: 1px solid #dee2e6;">
                                                                    <span style="color: #6c757d; font-size: 14px;">🏢 อาคาร/โซน</span><br>
                                                                    <span style="color: #212529; font-size: 16px; font-weight: bold;">' . htmlspecialchars($zone) . '</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 10px 0;">
                                                                    <span style="color: #6c757d; font-size: 14px;">👤 ผู้ประสานงาน</span><br>
                                                                    <span style="color: #212529; font-size: 16px; font-weight: bold;">' . htmlspecialchars($salesName) . '</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin: 0 0 30px; text-align: center;">
                                                กรุณากดปุ่มด้านล่างเพื่อยืนยันการเข้าร่วมและดูรายละเอียดการเดินทาง
                                            </p>
                                            
                                            <!-- CTA Button -->
                                            <table width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td align="center">
                                                        <a href="' . $verifyLink . '" style="display: inline-block; padding: 16px 40px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; text-decoration: none; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 15px rgba(40,167,69,0.4);">
                                                            ✅ ยืนยันการเข้าร่วม
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                            <p style="color: #888; font-size: 13px; line-height: 1.6; margin: 30px 0 0; text-align: center;">
                                                หากมีข้อสงสัยเพิ่มเติม กรุณาติดต่อผู้ประสานงานโดยตรง
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    <!-- Footer -->
                                    <tr>
                                        <td style="background-color: #1e3a5f; padding: 25px 30px; text-align: center;">
                                            <p style="color: rgba(255,255,255,0.7); margin: 0; font-size: 13px;">
                                                © ' . date('Y') . ' ASEFA PUBLIC COMPANY LIMITED<br>
                                                อีเมลนี้ส่งโดยอัตโนมัติจากระบบ Visitor Management
                                            </p>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';

                $mail->send();
                $sentCount++;
            } catch (Exception $e) {
                $errors[] = "Failed to send to {$email}: " . $e->getMessage();
            }
        }

        echo json_encode(array(
            'success' => true,
            'message' => 'ส่งอีเมลเชิญสำเร็จ ' . $sentCount . ' รายการ',
            'sent_count' => $sentCount,
            'errors' => $errors
        ));
        exit;
    }

    throw new Exception('Invalid action');
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
}
