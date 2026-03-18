<?php
include_once '../config/base.php';

$jobno = $_POST['jobno'] ?? '';
$action = $_POST['action'] ?? '';

if ($jobno === '' || $action === '') {
    echo json_encode(['status' => false, 'message' => 'Missing parameters']);
    exit;
}

$ch = curl_init('https://innovation.asefa.co.th/ChangeRequestForm/searchjobvisit');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query(['jobno' => $jobno, 'action' => $action]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(502);
    echo json_encode(['status' => false, 'message' => 'Proxy error: ' . $curlError]);
    exit;
}

http_response_code($httpCode);
header('Content-Type: application/json');
echo $response;
