<?php
include_once 'config/base.php';
$pageName = 'related';

// Check login
if (!isset($_SESSION['VisitorMKT_code'])) {
    header("Location: index.php");
    exit;
}

$userCode = $_SESSION['VisitorMKT_code'] ?? '';
$userPerm = $_SESSION['VisitorMKT_permision'] ?? '';
$isAdmin = $userPerm === 'Admin' || (is_array($userPerm) && in_array('Admin', $userPerm));

// ฝ่ายที่สามารถเลือกได้ (Fixed list)
$divisions = [
    'TC',
    'QC',
    'PRD',
    'สื่อสาร',
    'จป.',
    'HR',
    'วางแผน',
    'ขนส่ง',
    'การตลาด'
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายการประเมิน - VisitorMKT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <?php include 'css.php'; ?>
    <style>
        .badge-division { font-size: 12px; margin: 2px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
        <a class="navbar-brand me-lg-5" href="./index.html">
            <img class="navbar-brand-dark" src="https://innovation.asefa.co.th/cdn/logo/icon1.png" alt="Asefa Logo" />
            <img class="navbar-brand-light" src="https://innovation.asefa.co.th/cdn/logo/icon1.png" alt="Asefa Logo" />
        </a>
        <div class="d-flex align-items-center">
            <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <?php include 'layout/sidebarMenu.php'; ?>
    
    <main class="content">

        <?php include('layout/navbar.php'); ?>

        <div class="main-content">
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> รายการประเมิน</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bi bi-plus-lg"></i> เพิ่มประเมิน
                    </button>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="dataTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">#</th>
                                        <th>เลขที่เอกสาร</th>
                                        <th>หน่วยงาน</th>
                                        <th>รายละเอียด</th>
                                        <th>วันที่สร้าง</th>
                                        <th style="width: 120px">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <tr><td colspan="6" class="text-center text-muted py-4">กำลังโหลด...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('layout/theme-settings.php'); ?>
    </main>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">เพิ่มประเมิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="evalForm">
                    <input type="hidden" id="editId" name="id">
                    <div class="modal-body">
                        <!-- DocNo Selection -->
                        <div class="mb-3">
                            <label for="docNo" class="form-label">เลือกเอกสาร <span class="text-danger">*</span></label>
                            <select id="docNo" name="doc_no" class="form-select" required>
                                <option value="">-- เลือกเอกสาร --</option>
                            </select>
                            <div class="form-text">แสดงเฉพาะเอกสารที่คุณเคยกรอกไว้</div>
                        </div>
                        
                        <!-- Division Selection -->
                        <div class="mb-3">
                            <label for="divisions" class="form-label">หน่วยงาน <span class="text-danger">*</span></label>
                            <select id="divisions" name="divisions[]" class="form-select" multiple required>
                                <?php foreach ($divisions as $code => $name): ?>
                                <option value="<?= $name ?>"><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">สามารถเลือกได้มากกว่า 1 หน่วยงาน</div>
                        </div>
                        
                        <!-- Detail -->
                        <div class="mb-3">
                            <label for="detail" class="form-label">รายละเอียด</label>
                            <textarea id="detail" name="detail" class="form-control" rows="4" placeholder="รายละเอียดเพิ่มเติม..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <?php include 'js.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        const userCode = '<?= $userCode ?>';
        const divisions = <?= json_encode($divisions) ?>;
        
        $(document).ready(function() {
            // Initialize Select2
            $('#divisions').select2({
                theme: 'bootstrap-5',
                placeholder: 'เลือกหน่วยงาน',
                dropdownParent: $('#addModal')
            });
            
            $('#docNo').select2({
                theme: 'bootstrap-5',
                placeholder: 'เลือกเอกสาร',
                dropdownParent: $('#addModal')
            });
            
            // Load user's DocNo list
            loadDocNoList();
            
            // Load evaluation list
            loadEvaluations();
            
            // Reset modal on close
            $('#addModal').on('hidden.bs.modal', function() {
                $('#evalForm')[0].reset();
                $('#editId').val('');
                $('#modalTitle').text('เพิ่มประเมิน');
                $('#docNo').val('').trigger('change');
                $('#divisions').val([]).trigger('change');
            });
            
            // Form submit
            $('#evalForm').on('submit', function(e) {
                e.preventDefault();
                
                const id = $('#editId').val();
                const action = id ? 'update' : 'add';
                
                $.ajax({
                    url: 'api/related_action.php',
                    type: 'POST',
                    data: {
                        action: action,
                        id: id,
                        doc_no: $('#docNo').val(),
                        divisions: $('#divisions').val(),
                        detail: $('#detail').val()
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
                            $('#addModal').modal('hide');
                            loadEvaluations();
                        } else {
                            Swal.fire({ icon: 'error', title: res.message });
                        }
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด' });
                    }
                });
            });
        });
        
        function loadDocNoList() {
            $.get('api/related_action.php', { action: 'get_docno_list' }, function(res) {
                if (res.status && res.data) {
                    res.data.forEach(item => {
                        $('#docNo').append(`<option value="${item.DocNo}">${item.DocNo}</option>`);
                    });
                }
            }, 'json');
        }
        
        function loadEvaluations() {
            $.get('api/related_action.php', { action: 'list' }, function(res) {
                const tbody = $('#tableBody');
                tbody.empty();
                
                if (!res.status || !res.data || res.data.length === 0) {
                    tbody.html('<tr><td colspan="6" class="text-center text-muted py-4">ไม่มีข้อมูล</td></tr>');
                    return;
                }
                
                res.data.forEach((item, idx) => {
                    // Parse divisions
                    let divisionBadges = '';
                    if (item.Divisions) {
                        const divs = JSON.parse(item.Divisions);
                        divs.forEach(d => {
                            const name = divisions[d] || d;
                            divisionBadges += `<span class="badge bg-secondary badge-division">${name}</span>`;
                        });
                    }
                    
                    tbody.append(`
                        <tr>
                            <td>${idx + 1}</td>
                            <td><strong>${item.DocNo}</strong></td>
                            <td>${divisionBadges}</td>
                            <td>${item.Detail || '-'}</td>
                            <td>${item.CreatedAt || '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="editItem(${item.Id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(${item.Id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }, 'json');
        }
        
        function editItem(id) {
            $.get('api/related_action.php', { action: 'get', id: id }, function(res) {
                if (res.status && res.data) {
                    const item = res.data;
                    $('#editId').val(item.Id);
                    $('#docNo').val(item.DocNo).trigger('change');
                    $('#divisions').val(JSON.parse(item.Divisions || '[]')).trigger('change');
                    $('#detail').val(item.Detail);
                    $('#modalTitle').text('แก้ไขประเมิน');
                    $('#addModal').modal('show');
                }
            }, 'json');
        }
        
        function deleteItem(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then(result => {
                if (result.isConfirmed) {
                    $.post('api/related_action.php', { action: 'delete', id: id }, function(res) {
                        if (res.status) {
                            Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', timer: 1500, showConfirmButton: false });
                            loadEvaluations();
                        } else {
                            Swal.fire({ icon: 'error', title: res.message });
                        }
                    }, 'json');
                }
            });
        }
    </script>
</body>
</html>
