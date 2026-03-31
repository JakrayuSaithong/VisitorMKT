<?php
include_once 'config/base.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title>Company - Visitor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Themesberg">

    <?php include('css.php'); ?>

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

    <?php include('layout/sidebarMenu.php'); ?>

    <main class="content">

        <?php include('layout/navbar.php'); ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                    <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                        <li class="breadcrumb-item">
                            <a href="#">
                                <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List Visitor</li>
                    </ol>
                </nav>
                <h2 class="h4">All Visitor</h2>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="./visitorform.php?page=insert" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center"><i class="ti ti-plus me-2"></i> New Visitor</a>
            </div>
        </div>
        <div class="card table-wrapper table-responsive p-2">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Doc No</th>
                        <th>Project Name</th>
                        <th>Company Name</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>

            </table>

        </div>

        <?php include('layout/theme-settings.php'); ?>

        <?php //include('layout/footer.php'); 
        ?>

    </main>

    <?php include('js.php'); ?>

    <!-- Customer Evaluation QR Modal -->
    <div class="modal fade" id="evalQRModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-qrcode me-2"></i>QR Code ลูกค้าประเมิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div id="qrCodeDiv" class="d-flex justify-content-center mb-3"></div>
                    <p class="text-muted small mb-3">สแกน QR Code เพื่อเปิดฟอร์มประเมิน</p>
                    <div class="input-group mb-3">
                        <input type="text" id="evalUrlInput" class="form-control form-control-sm" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyEvalLink()">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button class="btn btn-success" type="button" onclick="downloadQR()">
                        <i class="fa-solid fa-download me-1"></i>โหลด QR
                    </button>
                    <button class="btn btn-primary" type="button" onclick="copyEvalLink()">
                        <i class="fa-solid fa-link me-1"></i>คัดลอกลิงก์
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        $(document).ready(function() {
            const $tbody = $('table tbody');
            $tbody.html('<tr><td colspan="8" class="text-center text-muted">กำลังโหลดข้อมูล...</td></tr>');

            $.ajax({
                url: 'api/get_visitor_list.php',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status && res.data.length > 0) {
                        let rows = '';
                        let i = 1;
                        $.each(res.data, function(index, v) {
                            const projectName = v.ProjectName ?
                                (v.ProjectName.length > 60 ? v.ProjectName.substring(0, 60) + '...' : v.ProjectName) :
                                '-';
                            if (v.UserCreated != '<?php echo $_SESSION['VisitorMKT_code']; ?>' && v.Status <= 0) return;

                            let actionBtns = `
                                <a href="./visitor_formupdate.php?page=view&id=${v.Id}" class="btn btn-sm btn-info">
                                    <i class="ti ti-eye"></i> View
                                </a>
                            `;

                            if (v.Status == 6) {
                                actionBtns += `
                                    <a href="./quotation_list.php?page=listvisitor&id=${v.Id}" class="btn btn-sm btn-warning">
                                        <i class="ti ti-archive"></i> รายการประเมิน
                                    </a>
                                    <button class="btn btn-sm btn-primary" onclick="openEvalQRModal(${v.Id})">
                                        <i class="fa-solid fa-qrcode"></i> ลูกค้าประเมิน
                                    </button>
                                `;
                            }

                            if (v.Status == 9) {
                                actionBtns += `
                                    <button class="btn btn-sm btn-secondary" onclick="copyVisitorForm(${v.Id})">
                                        <i class="ti ti-copy"></i> Copy
                                    </button>
                                `;
                            }

                            rows += `
                                <tr>
                                    <td>${i}</td>
                                    <td>${v.DocNo ?? '-'}</td>
                                    <td title="${v.ProjectName ?? '-'}">${projectName}</td>
                                    <td>${v.CompanyName ?? '-'}</td>
                                    <td><span class="badge text-bg-primary" style="font-size: 13px; background-color: ${status_color[v.Status]} !important;">${status_text[v.Status]}</span></td>
                                    <td>${actionBtns}</td>
                                </tr>
                            `;
                            i++;
                        });
                        $tbody.html(rows);

                        if ($.fn.DataTable.isDataTable('.table')) {
                            $('.table').DataTable().destroy();
                        }

                        $(".table").DataTable({
                            responsive: true,
                            scrollX: true
                        });
                    } else {
                        $tbody.html('<tr><td colspan="8" class="text-center text-muted">ไม่พบข้อมูล</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $tbody.html('<tr><td colspan="8" class="text-center text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
                }
            });
        });
        let currentEvalUrl = '';
        let qrCodeInstance = null;

        function encodeEvalId(id) {
            const key = 'ASEFA_EVAL_2024';
            const data = String(id) + '|' + Date.now();
            let out = '';
            for (let i = 0; i < data.length; i++) {
                out += String.fromCharCode(data.charCodeAt(i) ^ key.charCodeAt(i % key.length));
            }
            return btoa(out).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
        }

        function openEvalQRModal(visitorId) {
            const token = encodeEvalId(visitorId);
            currentEvalUrl = `https://it.asefa.co.th/visitorMKT/visi_question.php?eval_token=${token}`;
            $('#evalUrlInput').val(currentEvalUrl);
            $('#qrCodeDiv').empty();
            qrCodeInstance = new QRCode(document.getElementById('qrCodeDiv'), {
                text: currentEvalUrl,
                width: 200,
                height: 200,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
            $('#evalQRModal').modal('show');
        }

        function downloadQR() {
            const img = document.querySelector('#qrCodeDiv img');
            if (img) {
                const link = document.createElement('a');
                link.download = 'QRCode_CustomerEval.png';
                link.href = img.src;
                link.click();
            }
        }

        function copyEvalLink() {
            navigator.clipboard.writeText(currentEvalUrl).then(() => {
                Swal.fire({ icon: 'success', title: 'คัดลอกลิงก์แล้ว!', timer: 1500, showConfirmButton: false });
            }).catch(() => {
                const input = document.getElementById('evalUrlInput');
                input.select();
                document.execCommand('copy');
                Swal.fire({ icon: 'success', title: 'คัดลอกลิงก์แล้ว!', timer: 1500, showConfirmButton: false });
            });
        }

        function copyVisitorForm(id) {
            Swal.fire({
                title: 'คัดลอกเอกสาร?',
                text: 'ระบบจะสร้างเอกสารใหม่โดยคัดลอกข้อมูลจากเอกสารนี้',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'คัดลอก',
                cancelButtonText: 'ยกเลิก',
                cancelButtonColor: '#6B7280',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = './visitorform.php?copy_id=' + id;
                }
            });
        }
    </script>

</body>

</html>