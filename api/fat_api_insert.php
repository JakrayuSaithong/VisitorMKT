<?php
include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    echo json_encode(['status' => false, 'message' => 'Invalid request'], JSON_UNESCAPED_UNICODE);
    exit;
}

function gen_fat_code()
{
    global    $konnext_fat;
    $fat_projectcode = '';
    $sql_gencode = " SELECT	SUBSTRING(MAX(fat_projectcode), 9, 4) as 'lastdocno'
                     FROM fat_projectmaster
                     where   CONVERT(DateCreate, DATE) between '" . date('Y-m-01') . "' and '" . date('Y-m-31') . "'
                     ORDER BY SUBSTRING(fat_projectcode, 9, 4) DESC
                     LIMIT 1
                   ";
    $query_gencode = mysqli_query($konnext_fat, $sql_gencode) or die(mysqli_error($konnext_fat));
    $has_gencode = mysqli_num_rows($query_gencode);
    if ($has_gencode > 0) {
        $rowgen_code = mysqli_fetch_array($query_gencode, MYSQLI_ASSOC);
        $fat_projectcode = "FAT-" . substr((date('Y') + 543), 2, 2) . (date('m')) .  sprintf("%04d", $rowgen_code['lastdocno'] + 1);
    } else {
        $fat_projectcode = "FAT-" . substr((date('Y') + 543), 2, 2) . (date('m')) . '0001';
    }
    return $fat_projectcode;
}

$fat_projectcode = gen_fat_code();
$projectfatname = $_POST['projectfatname'];
$owner = $_POST['owner'];
$owner_contact = $_POST['owner_contact'];
$jobno = $_POST['jobno'];
$salename = $_POST['salename'];
$jobvalue = $_POST['jobvalue'];
$SN_no = $_POST['SN_no'];
$wa_no = $_POST['WA'];
// $fattest_date = $_POST['fattest_date'];
$fat_remark = $_POST['fat_remark'];
$fat_usersend = $_POST['fat_usersend'];

$sql        =    "
					INSERT INTO fat_projectmaster (
                         fat_projectcode,
                         devitioncode,
                         devitionname,
                         fat_projectname,
                         fat_owner,
                         owner_contact,
                         fat_consult,
                         fat_contractor,
                         designer,
                         job_No,
                         requireDate,
                         salename,
                         jobvalue,
                         SN_no,
                         wa_no,
                         product_type,
                         fattest_date,
                         status_code,
                         fat_remark,
                         fat_usersend,
                         DateCreate,
                         CreateUser,
                         CreateUserTH,
                         LastUpdate,
                         UserUpdate
                     )
					VALUES
					(
						'" . $fat_projectcode . "',
                        '" . $_SESSION['DivisionCode'] . "',
                        '" . $_SESSION['DivisionHead2'] . "',
                        '" . $projectfatname . "',
                        '" . $owner . "',
                        '',
                        '',
                        '',
                        '',
                        '" . $jobno . "',
                        '',
                        '" . $salename . "',
                        '" . $jobvalue . "',
                        '" . $SN_no . "',
                        '" . $wa_no . "',
                        '',
                        '',
                        '1',
                        '" . $fat_remark . "',
                        '" . $fat_usersend . "',
                        now(),
                        '" . $_SESSION['VisitorMKT_code'] . "',
                        '" . $_SESSION['VisitorMKT_name'] . "',
                        now(),
                        '" . $_SESSION['VisitorMKT_code'] . "'
					);
					";
    $query      = mysqli_query($konnext_fat, $sql) or die(mysqli_error($konnext_fat));

    if ($query) {
        echo json_encode(array('status' => true, 'fat_projectcode' => $fat_projectcode), JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array('status' => false, 'message' => mysqli_error($konnext_fat)), JSON_UNESCAPED_UNICODE);
    }
?>