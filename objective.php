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
                        <li class="breadcrumb-item active" aria-current="page">กำหนดวัตถุประสงค์</li>
                    </ol>
                </nav>
                <h2 class="h4">รายการวัตถุประสงค์</h2>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button class="btn btn-sm btn-gray-800 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addObjective"><i class="ti ti-plus me-2"></i> เพิ่มวัตถุประสงค์</button>
            </div>
        </div>
        <div class="card card-body table-responsive">
            <table class="table text-nowrap text-left" id="objectiveTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อวัตถุประสงค์</th>
                        <!-- <th>ผู้แก้ไข</th> -->
                        <th>วันที่แก้ไข</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            
        </div>

        <div class="modal fade" id="addObjective" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addObjectiveLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addObjectiveLabel"><i class="ti ti-users-group"></i> เพิ่มวัตถุประสงค์</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">ชื่อวัตถุประสงค์</label>
                        <input type="text" class="form-control" id="addObjectiveName">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-ban"></i> Close</button>
                    <button type="button" class="btn btn-success text-white" id="addObjectiveSave"><i class="ti ti-device-floppy"></i> บันทึก</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editObjective" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editObjectiveLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editObjectiveLabel"><i class="ti ti-users-group"></i> แก้ไขวัตถุประสงค์</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">ชื่อวัตถุประสงค์</label>
                        <input type="text" class="form-control" id="editObjectiveName">
                        <input type="hidden" class="form-control" id="editObjectiveID">
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">status</label>
                        <select class="form-select" id="ObjectiveStatus">
                            <option value="0">เปิดใช้งาน</option>
                            <option value="1">ปิดใช้งาน</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-ban"></i> Close</button>
                    <button type="button" class="btn btn-warning" id="editObjectiveSave"><i class="ti ti-device-floppy"></i> บันทึกแก้ไข</button>
                </div>
                </div>
            </div>
        </div>

        <?php include('layout/theme-settings.php'); ?>

        <?php //include('layout/footer.php'); ?>

    </main>

    <?php include('js.php'); ?>

    <script>
        var VisitorMKT_code = '<?php echo $_SESSION['VisitorMKT_code']; ?>';

        $(document).ready(function() {
            get_objective_list();

            $('#addObjectiveSave').click(function() {
                var ObjectiveName = $('#addObjectiveName').val();

                if(VisitorMKT_code == '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณาเข้าสู่ระบบ',
                        text: 'เนื่องจากคุณหมดเวลาการเข้าสู่ระบบ',
                    });
                }
                
                if(ObjectiveName == '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกชื่อวัตถุประสงค์',
                    });

                    return false;
                }

                Swal.fire({
                    title: 'ต้องการบันทึกข้อมูลใช่หรือไม่',
                    text: "ยืนยันการบันทึก",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "./api/objective_action.php",
                            type: "POST",
                            data: {
                                Action: 'add',
                                ObjectiveName: ObjectiveName
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'กำลังบันทึกข้อมูล...',
                                    text: 'โปรดรอสักครู่',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(data) {
                                if (data.status == true) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'บันทึกข้อมูลเรียบร้อย',
                                    }).then(function() {
                                        location.reload();
                                    })
                                }
                                else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        text: 'ไม่สามารถบันทึกข้อมูลได้',
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถบันทึกข้อมูลได้',
                                });
                            }
                        });
                    }
                });
            });

            $('#editObjectiveSave').click(function() {
                var ObjectiveID = $('#editObjectiveID').val();
                var ObjectiveName = $('#editObjectiveName').val();
                var ObjectiveStatus = $('#ObjectiveStatus').val();

                if(VisitorMKT_code == '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณาเข้าสู่ระบบ',
                        text: 'เนื่องจากคุณหมดเวลาการเข้าสู่ระบบ',
                    });

                    return false;
                }

                if(ObjectiveName == '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกชื่อวัตถุประสงค์',
                    });

                    return false;
                }

                Swal.fire({
                    title: 'ต้องการบันทึกข้อมูลใช่หรือไม่',
                    text: "ยืนยันการบันทึก",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "./api/objective_action.php",
                            type: "POST",
                            data: {
                                Action: 'edit',
                                ObjectiveID: ObjectiveID,
                                ObjectiveName: ObjectiveName,
                                ObjectiveStatus: ObjectiveStatus
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'กำลังบันทึกข้อมูล...',
                                    text: 'โปรดรอสักครู่',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(data) {
                                if (data.status == true) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'บันทึกข้อมูลเรียบร้อย',
                                    }).then(function() {
                                        location.reload();
                                    })
                                }
                                else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        text: 'ไม่สามารถบันทึกข้อมูลได้',
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถบันทึกข้อมูลได้',
                                });
                            }
                        });
                    }
                })
            });
        });

        async function get_objective_list(){
            const response = await fetch('api/objective_action.php');
            const data = await response.json();

            var i = 1;
            data.data.forEach((item, index) => {
                $('#objectiveTable tbody').append(`
                    <tr>
                        <td>${i}</td>
                        <td>${item.visit_objective_name}</td>
                        <td>${item.edit_date}</td>
                        <td>${item.visit_objective_status == 0 ? '<span class="badge text-bg-success">เปิดใช้งาน</span>' : '<span class="badge text-bg-danger">ปิดใช้งาน</span>'}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" onclick="edit_objective(${item.visit_objective_id}, '${item.visit_objective_name}', ${item.visit_objective_status})"><i class="ti ti-pencil"></i> แก้ไข</button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_objective(${item.visit_objective_id})"><i class="ti ti-trash"></i> ลบ</button>
                        </td>
                    </tr>
                `);
                i++;
            });

            $('#objectiveTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            });
        }

        function edit_objective(visit_objective_id, visit_objective_name, visit_objective_status) {
            $('#editObjectiveName').val(visit_objective_name);
            $('#editObjectiveID').val(visit_objective_id);
            $('#ObjectiveStatus').val(visit_objective_status);

            $('#editObjective').modal('show');
        }

        function delete_objective(visit_objective_id){
            Swal.fire({
                title: 'ต้องการลบข้อมูลใช่หรือไม่',
                text: "ยืนยันการลบ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "./api/objective_action.php",
                        type: "POST",
                        data: {
                            Action: 'delete',
                            ObjectiveID: visit_objective_id
                        },
                        success: function(data) {
                            if (data.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบข้อมูลเรียบร้อย',
                                }).then(function() {
                                    location.reload();
                                })
                            }
                            else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถลบข้อมูลได้',
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถลบข้อมูลได้',
                            });
                        }
                    });
                }
            })
        }

    </script>

</body>

</html>