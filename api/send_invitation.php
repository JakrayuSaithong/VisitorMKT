<?php

/**
 * API: Send Invitation to Customers
 * ใช้ SQL Server (sqlsrv) + PHPMailer
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailerException;

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

function setupSMTPConnection($mail) {
    $connectionMethods = [
        [
            'secure' => PHPMailer::ENCRYPTION_STARTTLS,
            'port' => 587
        ],
        [
            'secure' => PHPMailer::ENCRYPTION_SMTPS,
            'port' => 465
        ],
        [
            'secure' => false,
            'port' => 25
        ]
    ];

    $lastException = null;
    foreach ($connectionMethods as $method) {
        try {
            $mail->SMTPSecure = $method['secure'];
            $mail->Port = $method['port'];
            $mail->smtpConnect();
            return true;
        } catch (Exception $e) {
            $lastException = $e;
            continue;
        }
    }
    
    throw new Exception("SMTP connection failed with all methods. Last error: " . $lastException->getMessage());
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
    $phpMailerPath = '../vendor/phpmailer/src/PHPMailer.php';
    if (!file_exists($phpMailerPath)) {
      throw new Exception('PHPMailer not found. Please install PHPMailer.');
    }

    require_once '../vendor/phpmailer/src/Exception.php';
    require_once '../vendor/phpmailer/src/PHPMailer.php';
    require_once '../vendor/phpmailer/src/SMTP.php';

    // Get visitor form data for email content
    $formSql = "SELECT vf.UserCreated, vs.VisitDate, vs.TimeStart, vs.TimeEnd
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

    // ดึงชื่อผู้ร้องขอ (UserCreated) จาก mydata
    $requesterCode = isset($formData['UserCreated']) ? $formData['UserCreated'] : '';
    $requesterData = $requesterCode ? mydata($requesterCode) : null;
    $salesName = ($requesterData && isset($requesterData['FullName'])) ? $requesterData['FullName'] : 'ทีมงาน ASEFA';
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
        $mail = new PHPMailer(true);

        // SMTP Configuration (แก้ไขตามที่ใช้จริง)
        $mail->isSMTP();
        $mail->Host = 'smtppro.zoho.com';
        $mail->SMTPAuth = true;
        $mail->SMTPAutoTLS = false;
        $mail->Username = 'csd@asefa.co.th'; // เปลี่ยนเป็น email จริง
        $mail->Password = 'vk:uak2025vk:uak2025'; // เปลี่ยนเป็น app password
        $mail->SMTPSecure = 'ssl';
        $mail->CharSet = 'UTF-8';
        
        setupSMTPConnection($mail);

        // $mail->SMTPOptions = array(
        //   'ssl' => array(
        //     'verify_peer' => false,
        //     'verify_peer_name' => false,
        //     'allow_self_signed' => true
        //   )
        // );

        // Sender
        $mail->setFrom('csd@asefa.co.th', 'ASEFA Visitor Management');
        $mail->addAddress($email, $name);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = '=?UTF-8?B?' . base64_encode('ขอเรียนเชิญเยี่ยมชมโรงงาน ASEFA - ' . $projectName) . '?=';

        $mail->Body = '<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:Sarabun,Tahoma,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;padding:32px 16px;">
  <tr><td align="center">
    <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;">

      <!-- Header -->
      <tr>
        <td style="background:#1e3a5f;padding:32px 32px 24px;text-align:center;">
          <div style="font-size:32px;margin-bottom:8px;">🏭</div>
          <div style="color:#ffffff;font-size:22px;font-weight:700;letter-spacing:2px;">ASEFA</div>
          <div style="color:rgba(255,255,255,0.55);font-size:12px;margin-top:2px;">PUBLIC COMPANY LIMITED</div>
        </td>
      </tr>

      <!-- Divider accent -->
      <tr><td style="height:4px;background:linear-gradient(90deg,#f59e0b,#10b981,#3b82f6);"></td></tr>

      <!-- Body -->
      <tr>
        <td style="padding:32px;">
          <p style="color:#374151;font-size:15px;margin:0 0 8px;">เรียน คุณ ' . htmlspecialchars($name) . ',</p>
          <p style="color:#6b7280;font-size:14px;line-height:1.7;margin:0 0 24px;">
            บริษัท อาซีฟา จำกัด (มหาชน) ขอเรียนเชิญท่านเข้าเยี่ยมชมโรงงานของเรา<br>ตามรายละเอียดด้านล่างนี้
          </p>

          <!-- Info cards -->
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:24px;">
            <tr>
              <td style="padding:20px 24px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #e2e8f0;">
                      <span style="color:#9ca3af;font-size:12px;text-transform:uppercase;letter-spacing:.5px;">โครงการ</span><br>
                      <span style="color:#111827;font-size:15px;font-weight:600;">' . htmlspecialchars($projectName ?: '—') . '</span>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #e2e8f0;">
                      <span style="color:#9ca3af;font-size:12px;text-transform:uppercase;letter-spacing:.5px;">วันที่</span><br>
                      <span style="color:#111827;font-size:15px;font-weight:600;">' . htmlspecialchars($visitDate) . '</span>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #e2e8f0;">
                      <span style="color:#9ca3af;font-size:12px;text-transform:uppercase;letter-spacing:.5px;">เวลา</span><br>
                      <span style="color:#111827;font-size:15px;font-weight:600;">' . htmlspecialchars($timeStr) . '</span>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #e2e8f0;">
                      <span style="color:#9ca3af;font-size:12px;text-transform:uppercase;letter-spacing:.5px;">อาคาร / โซน</span><br>
                      <span style="color:#111827;font-size:15px;font-weight:600;">' . htmlspecialchars($zone) . '</span>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;">
                      <span style="color:#9ca3af;font-size:12px;text-transform:uppercase;letter-spacing:.5px;">ผู้ประสานงาน</span><br>
                      <span style="color:#111827;font-size:15px;font-weight:600;">' . htmlspecialchars($salesName) . '</span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <!-- CTA -->
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center">
                <a href="' . $verifyLink . '" style="display:inline-block;padding:14px 36px;background:#1e3a5f;color:#ffffff;text-decoration:none;border-radius:10px;font-size:15px;font-weight:600;">
                  ยืนยันการเข้าร่วม →
                </a>
              </td>
            </tr>
          </table>

          <p style="color:#9ca3af;font-size:12px;text-align:center;margin:20px 0 0;">
            หากมีข้อสงสัย กรุณาติดต่อผู้ประสานงานโดยตรง
          </p>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:16px 32px;text-align:center;">
          <p style="color:#9ca3af;font-size:12px;margin:0;">
            © ' . date('Y') . ' ASEFA PUBLIC COMPANY LIMITED &nbsp;·&nbsp; ส่งโดยระบบ Visitor Management
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
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
