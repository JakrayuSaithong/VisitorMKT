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
                            <a href="./?page=dashboard">
                                <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">กำหนดสิทธิ์เข้าใช้งาน</li>
                    </ol>
                </nav>
                <h2 class="h4">สิทธิ์เข้าใช้งานทั้งหมด</h2>
            </div>
        </div>
        <div class="card card-body table-wrapper table-responsive">
            <table class="table" id="permission_list">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อสิทธิ์</th>
                        <th>ผู้แก้ไข</th>
                        <th>วันที่แก้ไข</th>
                        <th>Action</th>
                    </tr>

                    <tbody>
                        
                    </tbody>
                </thead>
                
            </table>
        </div>

        <div class="modal fade" id="editPermission" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editPermissionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPermissionLabel" data-id="">แก้ไขสิทธิ์เข้าใช้งาน</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <select class="form-select" id="empList" multiple>
                            <?php 
                                foreach(emplist() as $key => $value) {
                            ?>
                            <option value="<?php echo $value['Code']; ?>"><?php echo $value['FullName']; ?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="divisionList" multiple>
                            <?php 
                                foreach(ListDivision() as $key => $value) {
                            ?>
                            <option value="<?php echo $value['Code']; ?>"><?php echo $value['Code'] ?> - <?php echo $value['Name'] ?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-cancel"></i> ยกเลิก</button>
                    <button type="button" class="btn btn-success" onclick="save_permission()"><i class="ti ti-device-floppy"></i> บันทึก</button>
                </div>
                </div>
            </div>
        </div>

        <?php include('layout/theme-settings.php'); ?>

        <?php //include('layout/footer.php'); ?>

    </main>

    <?php include('js.php'); ?>

    <script>
        let empChoices;
        let divisionChoices;

        $(document).ready(function() {
            empChoices = new Choices('#empList', { 
                searchEnabled: true, 
                removeItemButton: true, 
                placeholderValue: '-- เลือกผู้ใช้ --' 
            });

            divisionChoices = new Choices('#divisionList', { 
                searchEnabled: true, 
                removeItemButton: true, 
                placeholderValue: '-- เลือกแผนกที่ใช้งาน --' 
            });


            get_permission_list();
        });

        async function get_permission_list() {
            const response = await fetch('api/permission_list.php');
            const data = await response.json();
            
            var i = 1;
            data.forEach(function(item, index) {
                $('#permission_list tbody').append(`
                    <tr>
                        <td>${i}</td>
                        <td>${item.per_name}</td>
                        <td>${item.THNameEdit}</td>
                        <td>${item.per_dateedit}</td>
                        <td><button type="button" class="btn btn-warning btn-sm" onclick="edit_permission(${item.per_id}, '${item.per_name}', '${item.per_user}', '${item.per_division}')"><i class="ti ti-pencil-cog"></i> แก้ไข</button></td>
                    </tr>
                `);
                i++;
            });

            $('#permission_list').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            });
        }

        function edit_permission(per_id, per_name, per_user, per_division) {
            var per_user_arr = per_user.split(', ');
            var per_division_arr = per_division.split(', ');

            empChoices.removeActiveItems();
            divisionChoices.removeActiveItems();

            empChoices.setChoiceByValue(per_user_arr);
            divisionChoices.setChoiceByValue(per_division_arr);

            $('#editPermissionLabel').attr('data-id', per_id);

            $('#editPermission').modal('show');
            $('#editPermissionLabel').text('แก้ไขสิทธิ์เข้าใช้งาน : '+ per_name);
        }

        function save_permission() {
            var per_id = $('#editPermissionLabel').attr('data-id');
            var per_user = $('#empList').val().join(', ');
            var per_division = $('#divisionList').val().join(', ');

            Swal.fire({
                title: 'ยืนยันการบันทึก?',
                text: "ยืนยันการบันทึก",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "./api/permission_update.php",
                        type: "POST",
                        data: {
                            per_id: per_id,
                            per_user: per_user,
                            per_division: per_division
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'กำลังบันทึกข้อมูล...',
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
                                });
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
        }

    </script>

</body>

</html>