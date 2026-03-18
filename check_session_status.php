<?php
session_start();
header('Content-Type: application/json');

$is_active = isset($_SESSION['VisitorMKT_permision']);

echo json_encode(['status' => $is_active ? 'active' : 'expired']);
