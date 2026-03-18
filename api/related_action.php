<?php
/**
 * API: related_action.php
 * CRUD operations for VisitorRelated table
 * Actions: list, get, get_docno_list, add, update, delete (soft delete)
 */

include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$userCode = $_SESSION['VisitorMKT_code'] ?? '';

if (!$action) {
    echo json_encode(["status" => false, "message" => "Missing action"]);
    exit;
}

// ===== GET_DOCNO_LIST: ดึงรายการ DocNo ที่ user เคยสร้าง =====
if ($action === 'get_docno_list') {
    try {
        $sql = "SELECT Id, DocNo FROM VisitorForm WHERE UserCreated = ? AND Status IN (6) ORDER BY Id DESC";
        $stmt = sqlsrv_query($konnext_DB64, $sql, [$userCode]);
        
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        
        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        
        echo json_encode(["status" => true, "data" => $data], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(["status" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// ===== LIST: ดึงรายการประเมินของ user =====
if ($action === 'list') {
    try {
        $sql = "SELECT Id, DocNo, Divisions, Detail, 
                FORMAT(CreatedAt, 'dd/MM/yyyy HH:mm') as CreatedAt 
                FROM VisitorRelated 
                WHERE UserCreated = ? AND IsDeleted = 0 
                ORDER BY Id DESC";
        $stmt = sqlsrv_query($konnext_DB64, $sql, [$userCode]);
        
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        
        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        
        echo json_encode(["status" => true, "data" => $data], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(["status" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// ===== GET: ดึงรายละเอียดรายการเดียว =====
if ($action === 'get') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        echo json_encode(["status" => false, "message" => "Missing id"]);
        exit;
    }
    
    try {
        $sql = "SELECT * FROM VisitorRelated WHERE Id = ? AND UserCreated = ? AND IsDeleted = 0";
        $stmt = sqlsrv_query($konnext_DB64, $sql, [$id, $userCode]);
        
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        
        if (!$row) {
            echo json_encode(["status" => false, "message" => "ไม่พบข้อมูล"]);
            exit;
        }
        
        echo json_encode(["status" => true, "data" => $row], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(["status" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// ===== ADD: เพิ่มรายการใหม่ =====
if ($action === 'add') {
    $docNo = $_POST['doc_no'] ?? null;
    $divisions = $_POST['divisions'] ?? [];
    $detail = $_POST['detail'] ?? '';
    
    if (!$docNo || empty($divisions)) {
        echo json_encode(["status" => false, "message" => "กรุณากรอกข้อมูลให้ครบ"]);
        exit;
    }
    
    try {
        $divisionsJson = json_encode($divisions, JSON_UNESCAPED_UNICODE);
        
        $sql = "INSERT INTO VisitorRelated (DocNo, Divisions, Detail, UserCreated, CreatedAt, IsDeleted) 
                VALUES (?, ?, ?, ?, GETDATE(), 0)";
        $stmt = sqlsrv_query($konnext_DB64, $sql, [$docNo, $divisionsJson, $detail, $userCode]);
        
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        
        echo json_encode(["status" => true, "message" => "บันทึกสำเร็จ"], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(["status" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// ===== UPDATE: แก้ไขรายการ =====
if ($action === 'update') {
    $id = $_POST['id'] ?? null;
    $docNo = $_POST['doc_no'] ?? null;
    $divisions = $_POST['divisions'] ?? [];
    $detail = $_POST['detail'] ?? '';
    
    if (!$id || !$docNo || empty($divisions)) {
        echo json_encode(["status" => false, "message" => "กรุณากรอกข้อมูลให้ครบ"]);
        exit;
    }
    
    try {
        $divisionsJson = json_encode($divisions, JSON_UNESCAPED_UNICODE);
        
        $sql = "UPDATE VisitorRelated SET DocNo = ?, Divisions = ?, Detail = ?, UpdatedAt = GETDATE() 
                WHERE Id = ? AND UserCreated = ?";
        $stmt = sqlsrv_query($konnext_DB64, $sql, [$docNo, $divisionsJson, $detail, $id, $userCode]);
        
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        
        echo json_encode(["status" => true, "message" => "แก้ไขสำเร็จ"], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(["status" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// ===== DELETE: Soft delete =====
if ($action === 'delete') {
    $id = $_POST['id'] ?? null;
    
    if (!$id) {
        echo json_encode(["status" => false, "message" => "Missing id"]);
        exit;
    }
    
    try {
        $sql = "UPDATE VisitorRelated SET IsDeleted = 1, DeletedAt = GETDATE() WHERE Id = ? AND UserCreated = ?";
        $stmt = sqlsrv_query($konnext_DB64, $sql, [$id, $userCode]);
        
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        
        echo json_encode(["status" => true, "message" => "ลบสำเร็จ"], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(["status" => false, "message" => $e->getMessage()]);
    }
    exit;
}

echo json_encode(["status" => false, "message" => "Unknown action"]);
