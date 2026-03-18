<?php
    include_once 'config/base.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Company - Food Set</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
                        <li class="breadcrumb-item active" aria-current="page">กำหนดชุดอาหาร</li>
                    </ol>
                </nav>
                <h2 class="h4">รายการชุดอาหาร</h2>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button class="btn btn-sm btn-gray-800 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addFoodSet">
                    <i class="ti ti-plus me-2"></i> เพิ่มชุดอาหาร
                </button>
            </div>
        </div>

        <div class="card card-body table-responsive">
            <table class="table text-nowrap text-left" id="foodSetTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อชุดอาหาร</th>
                        <th>รายการอาหาร</th>
                        <!-- <th>ผู้แก้ไข</th> -->
                        <th>วันที่แก้ไข</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Modal: Add -->
        <div class="modal fade" id="addFoodSet" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addFoodSetLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addFoodSetLabel"><i class="ti ti-bowl"></i> เพิ่มชุดอาหาร</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">ชื่อชุดอาหาร</label>
                        <input type="text" class="form-control" id="addSetName">
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">รายการอาหาร</label>
                        <div id="foodItemsContainer"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addFoodInput">
                            <i class="ti ti-plus"></i> เพิ่มเมนู
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-ban"></i> Close</button>
                    <button type="button" class="btn btn-success text-white" id="addFoodSetSave"><i class="ti ti-device-floppy"></i> บันทึก</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Modal: Edit -->
        <div class="modal fade" id="editFoodSet" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editFoodSetLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editFoodSetLabel"><i class="ti ti-edit"></i> แก้ไขชุดอาหาร</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">ชื่อชุดอาหาร</label>
                        <input type="text" class="form-control" id="editSetName">
                        <input type="hidden" id="editSetID">
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">รายการอาหาร</label>
                        <div id="editFoodItemsContainer"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="editAddFoodInput">
                            <i class="ti ti-plus"></i> เพิ่มเมนู
                        </button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">สถานะ</label>
                        <select class="form-select" id="editSetStatus">
                            <option value="0">เปิดใช้งาน</option>
                            <option value="1">ปิดใช้งาน</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-ban"></i> Close</button>
                    <button type="button" class="btn btn-warning" id="editFoodSetSave"><i class="ti ti-device-floppy"></i> บันทึกแก้ไข</button>
                </div>
                </div>
            </div>
        </div>

        <?php include('layout/theme-settings.php'); ?>
    </main>

    <?php include('js.php'); ?>

<script>
    var VisitorMKT_code = '<?php echo $_SESSION['VisitorMKT_code']; ?>';

    $(document).ready(function() {
        get_foodset_list();

        // ---------- เพิ่ม input ใน Modal Add ----------
        $('#addFoodInput').click(function() {
            $('#foodItemsContainer').append(`
                <div class="input-group mb-2 food-item">
                    <input type="text" class="form-control food-name" placeholder="ชื่อเมนู">
                    <button type="button" class="btn btn-outline-danger remove-food"><i class="ti ti-trash"></i></button>
                </div>
            `);
        });

        // ลบ input ที่เลือก
        $(document).on('click', '.remove-food', function() {
            $(this).closest('.food-item').remove();
        });

        // ---------- บันทึกข้อมูล Add ----------
        $('#addFoodSetSave').click(function() {
            var setName = $('#addSetName').val();
            var items = [];
            $('#foodItemsContainer .food-name').each(function() {
                var val = $(this).val().trim();
                if (val !== '') items.push(val);
            });

            if (!setName || items.length == 0) {
                Swal.fire({ icon: 'warning', title: 'กรุณากรอกข้อมูลให้ครบ' });
                return;
            }

            Swal.fire({
                title: 'ยืนยันการบันทึก?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "./api/food_set_action.php",
                        type: "POST",
                        data: {
                            Action: 'add',
                            SetName: setName,
                            FoodItems: JSON.stringify(items)
                        },
                        beforeSend: () => {
                            Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: Swal.showLoading });
                        },
                        success: (data) => {
                            Swal.close();
                            if (data.status) {
                                Swal.fire({ icon: 'success', title: 'บันทึกเรียบร้อย' }).then(() => location.reload());
                            } else {
                                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด' });
                            }
                        }
                    });
                }
            });
        });

        // ---------- Modal แก้ไข ----------
        $('#editAddFoodInput').click(function() {
            $('#editFoodItemsContainer').append(`
                <div class="input-group mb-2 food-item">
                    <input type="text" class="form-control food-name" placeholder="ชื่อเมนู">
                    <button type="button" class="btn btn-outline-danger remove-food"><i class="ti ti-trash"></i></button>
                </div>
            `);
        });

        $('#editFoodSetSave').click(function() {
            var id = $('#editSetID').val();
            var name = $('#editSetName').val();
            var items = [];
            $('#editFoodItemsContainer .food-name').each(function() {
                var val = $(this).val().trim();
                if (val !== '') items.push(val);
            });
            var status = $('#editSetStatus').val();

            if (!name || items.length == 0) {
                Swal.fire({ icon: 'warning', title: 'กรุณากรอกข้อมูลให้ครบ' });
                return;
            }

            Swal.fire({
                title: 'ยืนยันการแก้ไข?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "./api/food_set_action.php",
                        type: "POST",
                        data: {
                            Action: 'edit',
                            SetID: id,
                            SetName: name,
                            FoodItems: JSON.stringify(items),
                            SetStatus: status
                        },
                        beforeSend: () => {
                            Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: Swal.showLoading });
                        },
                        success: (data) => {
                            Swal.close();
                            if (data.status) {
                                Swal.fire({ icon: 'success', title: 'บันทึกเรียบร้อย' }).then(() => location.reload());
                            } else {
                                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด' });
                            }
                        }
                    });
                }
            });
        });
    });

    async function get_foodset_list() {
        const response = await fetch('api/food_set_action.php');
        const data = await response.json();

        var i = 1;
        $('#foodSetTable tbody').empty();

        data.data.forEach(item => {
            const foodList = item.food_items.join(', ');
            const shortText = foodList.length > 60 ? foodList.substring(0, 60) + '...' : foodList;

            $('#foodSetTable tbody').append(`
                <tr>
                    <td>${i}</td>
                    <td>${item.food_set_name}</td>
                    <td title="${foodList.replace(/"/g, '&quot;')}">${shortText}</td>
                    <td>${item.edit_date}</td>
                    <td>
                        ${item.food_set_status == 0 
                            ? '<span class="badge text-bg-success">เปิดใช้งาน</span>' 
                            : '<span class="badge text-bg-danger">ปิดใช้งาน</span>'}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" 
                            onclick="edit_foodset(${item.food_set_id}, '${escapeHtml(item.food_set_name)}', '${escapeHtml(item.food_items.join(', '))}', ${item.food_set_status})">
                            <i class="ti ti-pencil"></i> แก้ไข
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="delete_foodset(${item.food_set_id})">
                            <i class="ti ti-trash"></i> ลบ
                        </button>
                    </td>
                </tr>
            `);
            i++;
        });

        $('#foodSetTable').DataTable({
            pageLength: 10,
            destroy: true, // ป้องกัน initialize ซ้ำ
            columnDefs: [
                { targets: 2, render: $.fn.dataTable.render.text() } // ป้องกัน XSS
            ]
        });
    }

    function escapeHtml(text) {
        if (typeof text !== "string") return text;
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function edit_foodset(id, name, items, status) {
        $('#editSetID').val(id);
        $('#editSetName').val(name);
        $('#editSetStatus').val(status);

        $('#editFoodItemsContainer').empty();
        const list = items.split(',').map(i => i.trim());
        list.forEach(food => {
            $('#editFoodItemsContainer').append(`
                <div class="input-group mb-2 food-item">
                    <input type="text" class="form-control food-name" value="${food}">
                    <button type="button" class="btn btn-outline-danger remove-food"><i class="ti ti-trash"></i></button>
                </div>
            `);
        });

        $('#editFoodSet').modal('show');
    }

    function delete_foodset(id) {
        Swal.fire({
            title: 'ต้องการลบข้อมูลใช่หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "./api/food_set_action.php",
                    type: "POST",
                    data: { Action: 'delete', SetID: id },
                    success: (data) => {
                        if (data.status) {
                            Swal.fire({ icon: 'success', title: 'ลบเรียบร้อย' }).then(() => location.reload());
                        } else {
                            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด' });
                        }
                    }
                });
            }
        });
    }
</script>
</body>
</html>