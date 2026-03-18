<?php
include_once 'config/base.php';
$perm = $_SESSION['VisitorMKT_permision'] ?? [];
$code = $_SESSION['VisitorMKT_code'] ?? '';
$isAdmin = (is_array($perm) && in_array('Admin', $perm)) || $perm === 'Admin';
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        /* M3 Tabs */
        .nav-tabs {
            border: none;
            background: var(--md-surface-container);
            border-radius: var(--md-shape-full);
            padding: 4px;
            margin-bottom: 12px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: var(--md-shape-full);
            color: var(--md-on-surface-variant);
            padding: 8px 16px;
            margin: 0 2px;
            transition: all 0.2s ease;
            font-size: 13px;
            font-weight: 500;
        }

        .nav-tabs .nav-link:hover {
            background: var(--md-surface-container-high);
            color: var(--md-on-surface);
        }

        .nav-tabs .nav-link.active {
            background: var(--md-primary);
            color: var(--md-on-primary);
            box-shadow: var(--md-elevation-1);
        }

        .nav-tabs .nav-link.active#nav-profile-tab { background: #C8377C; }
        .nav-tabs .nav-link.active#nav-travel-tab { background: #e69500; }
        .nav-tabs .nav-link.active#nav-hr-tab { background: #3AA9B0; }
        .nav-tabs .nav-link.active#nav-lecturer-tab { background: #8b7ff0; }
        .nav-tabs .nav-link.active#nav-prd-tab { background: var(--md-tertiary); }

        /* =====================================================
           JOB CARD COMPONENT
           ===================================================== */
        #job-cards-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .job-card {
            background: var(--md-surface);
            border: 1px solid var(--md-outline-variant);
            border-radius: var(--md-shape-md);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .job-card:hover {
            border-color: var(--md-primary);
            box-shadow: var(--md-elevation-2);
        }

        .job-card-header {
            background: var(--md-primary);
            color: var(--md-on-primary);
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .job-number {
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .job-number::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #4ade80;
            border-radius: 50%;
        }

        .btn-remove-job {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-remove-job:hover {
            background: #ef4444;
            border-color: #ef4444;
        }

        .job-card-body {
            padding: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .form-grid .full-width {
            grid-column: 1 / -1;
        }

        .btn-add-job {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #f0f7ff 0%, #e8f4ff 100%);
            border: 2px dashed #007aff;
            border-radius: 12px;
            color: #007aff;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-add-job:hover {
            background: linear-gradient(135deg, #e8f4ff 0%, #d4ebff 100%);
            border-color: #0056b3;
            transform: translateY(-2px);
        }

        .section-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0;
        }

        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-divider span {
            padding: 0 16px;
            color: #6b7280;
            font-weight: 500;
            font-size: 13px;
        }

        .objective-section {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .objective-section label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            display: block;
        }

        .corporate-service-panel {
            background: #fdf2f8;
            border: 1px solid #f9a8d4;
            border-radius: 10px;
            padding: 20px;
            margin-top: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #007aff;
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
            outline: none;
        }

        .btn-primary {
            background: #007aff;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        #table-schedule th {
            background-color: #007aff !important;
            color: white;
            border: 2px solid #007aff !important;
        }

        #FoodTable th {
            background-color: #3AA9B0 !important;
            color: white;
            border: 2px solid #3AA9B0 !important;
        }

        @media (max-width: 480px) {
            .nav-tabs {
                flex-direction: column;
            }

            .nav-tabs .nav-link {
                margin: 2px 0;
            }
        }

        input.form-control:disabled,
        textarea.form-control:disabled {
            background-color: white !important;
        }

        .select2-scrollable .select2-results__options {
            max-height: 200px;
            overflow-y: auto;
        }

        .select2-container .select2-selection--multiple {
            height: auto;
            max-height: 80px;
            overflow-y: auto;
        }

        .select2-container--bootstrap5 .select2-selection--multiple {
            align-items: flex-start !important;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            font-size: 13px !important;
            flex-direction: row-reverse !important;
            border-radius: 8px !important;
            background: #80bdff;
            border: 1px solid #007aff;
            color: white !important;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove {
            margin-right: 0 !important;
            margin-left: 6px !important;
            color: white !important;
        }

        .timeline-container {
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            overflow-x: auto;
        }

        .timeline-wrapper {
            display: flex;
            align-items: center;
            gap: 20px;
            min-width: min-content;
        }

        .timeline-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            min-width: 180px;
            position: relative;
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .timeline-status-acknowledged {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
            border: 4px solid white;
        }

        .timeline-status-pending {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffc107 0%, #ffcd39 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #212529;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
            transition: all 0.3s ease;
            border: 4px solid white;
        }

        .timeline-content {
            text-align: center;
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            min-width: 150px;
        }

        .timeline-acknowledged {
            font-weight: bold;
            color: #28a745;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .timeline-pending {
            font-weight: bold;
            color: #000000ff;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .timeline-user {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .timeline-time {
            font-size: 12px;
            color: #999;
        }

        .timeline-icon-label {
            font-size: 11px;
            color: #495057;
            margin-top: 8px;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .timeline-container {
                padding: 30px 10px;
            }

            .timeline-item {
                min-width: 150px;
            }

            .timeline-badge {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .timeline-content {
                min-width: 130px;
                padding: 12px;
            }

            .timeline-role {
                font-size: 11px;
            }

            .timeline-user {
                font-size: 11px;
            }
        }
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

    <?php include('layout/sidebarMenu.php'); ?>

    <main class="content">

        <?php include('layout/navbar.php'); ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap flex-column flex-md-row align-items-center py-4">
            <div class="d-block mb-md-0">
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
                        <li class="breadcrumb-item">Visitor Form</li>
                        <li class="breadcrumb-item active" aria-current="page" id="DocNo-Status"></li>
                    </ol>
                </nav>
                <h2 class="h4">Edit Document</h2>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <input type="hidden" id="status">
                <button class="btn btn-danger fs-6 d-none" type="button" id="btn-cancel-job">
                    <i class="ti ti-ban me-1"></i>ยกเลิกงาน
                </button>

                <button class="btn btn-warning fs-6" type="button" id="save-edit" onclick="update_visitor_form(<?php echo $_GET['id']; ?>)">
                    <i class="ti ti-device-floppy me-1"></i>บันทึกแก้ไข
                </button>

                <button class="btn btn-info fs-6 text-white" type="button" id="save-sent" onclick="update_visitor_form(<?php echo $_GET['id']; ?>, 1)">
                    <i class="ti ti-device-floppy me-1"></i>ส่งแบบฟอร์ม
                </button>

                <button class="btn btn-primary fs-6 d-none" type="button" id="btn-mk-accept">
                    <i class="ti ti-hand-stop me-1"></i>รับงาน
                </button>

                <button class="btn btn-success text-white fs-6 d-none" type="button" id="btn-dept-ack">

                </button>

                <button class="btn btn-tertiary text-white fs-6 d-none" type="button" id="btn-lecturer-ack">
                    <i class="ti ti-user-check me-1"></i>วิทยากรรับทราบ
                </button>

                <button class="btn btn-gray-200 fs-6 d-none" type="button" id="btn-close-job">
                    <i class="ti ti-lock me-1"></i>ปิดงาน
                </button>

                <button class="btn btn-tertiary text-white fs-6 d-none" type="button" id="btn-fat">
                    <i class="ti ti-building-factory-2 me-1"></i>รับงาน FAT
                </button>

                <button class="btn btn-info text-white fs-6" type="button" id="btn-qzip">
                    <i class="ti ti-archive me-1"></i>ประเมิน
                </button>
            </div>
        </div>
        <div class="container-fluid my-2">
            <div class="timeline-container">
                <div class="timeline-wrapper" id="ack-timeline"></div>
            </div>
        </div>
        <div>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">ฝ่ายขาย</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">ฝ่ายสื่อสาร</button>
                    <button class="nav-link" id="nav-travel-tab" data-bs-toggle="tab" data-bs-target="#nav-travel" type="button" role="tab" aria-controls="nav-travel" aria-selected="false">ฝ่ายขนส่ง</button>
                    <button class="nav-link" id="nav-hr-tab" data-bs-toggle="tab" data-bs-target="#nav-hr" type="button" role="tab" aria-controls="nav-hr" aria-selected="false">ฝ่าย HR</button>
                    <button class="nav-link d-none" id="nav-lecturer-tab" data-bs-toggle="tab" data-bs-target="#nav-lecturer" type="button" role="tab" aria-controls="nav-lecturer" aria-selected="false">วิทยากร</button>
                    <button class="nav-link" id="nav-prd-tab" data-bs-toggle="tab" data-bs-target="#nav-prd" type="button" role="tab" aria-controls="nav-prd" aria-selected="false">วางแผน</button>
                </div>
            </nav>
            <div class="tab-content mb-4" id="nav-tabContent">
                <!-- Tab Home -->
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                    <div class="card border border-2 table-wrapper table-responsive">
                        <h6 class="card-header py-3" style="background-color: #007aff; color: white;">ข้อมูลทั่วไป</h6>
                        <div class="card-body">
                            <!-- Objective Section (อยู่นอก Job Card) -->
                            <div class="objective-section">
                                <label><i class="ti ti-message-user fs-5 me-2"></i>วัตถุประสงค์การเยี่ยมชม</label>
                                <div class="d-flex flex-wrap gap-2 text-nowrap" id="List_objective"></div>
                            </div>
                            <div class="row mb-3" id="box_objective_other" style="display: none;">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="Objective_other" placeholder="ระบุวัตถุประสงค์อื่นๆ">
                                </div>
                            </div>

                            <!-- Hidden fields for compatibility -->
                            <input type="hidden" id="owner_contact">
                            <input type="hidden" id="salename_text">
                            <input type="hidden" id="jobvalue">

                            <!-- Job Cards Container -->
                            <div id="job-cards-container">
                                <!-- Job Cards จะถูก generate จาก JavaScript ตาม SalesDetail -->
                            </div>

                            <!-- Add Job Button -->
                            <button type="button" class="btn-add-job mt-3" id="addJobBtn" onclick="addJobCard()">
                                <i class="ti ti-plus"></i> Add Job No.
                            </button>

                            <div class="section-divider">
                                <span>ไฟล์แนบ</span>
                            </div>

                            <!-- File Upload Section -->
                            <div class="mb-3">
                                <div class="upload-area" onclick="document.getElementById('fileInput_detail').click()">
                                    <div class="upload-icon">📤</div>
                                    <div class="upload-text">คลิกหรือลากไฟล์มาวางที่นี่</div>
                                    <div class="upload-subtext">รองรับไฟล์ รูปภาพ, PDF, Word, Excel • สามารถเลือกหลายไฟล์พร้อมกัน</div>
                                </div>
                                <input type="file" id="fileInput_detail" class="d-none" multiple accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.pdf,.doc,.docx,.xls,.xlsx">
                                <div class="file-list gap-2" id="fileList_detail"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card border border-2 table-wrapper my-2 p-2">
                        <div class="col-sm-12 d-flex justify-content-between">
                            <label class="col-form-label"><i class="ti ti-calendar-event fs-5 me-2"></i>กรุณาระบุวันที่เยี่ยม</label>
                            <button type="button" class="btn btn-success btn-sm text-white" id="addSchedule"><i class="ti ti-plus fs-5 me-2"></i>เพิ่มวันที่เยี่ยมชม</button>
                        </div>
                    </div>

                    <div class="col-sm-12 table-responsive">
                        <table class="table table-hover border border-2" id="table-schedule">
                            <thead>
                                <tr>
                                    <th>การจอง</th>
                                    <th>วันที่เยี่ยม</th>
                                    <th>เวลาเยี่ยม</th>
                                    <th>ถึงเวลา</th>
                                    <th>ห้องประชุม</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr>
                                    <td>
                                        <select class="form-select reserve">
                                            <option value="meeting">จองห้องประชุม</option>
                                            <option value="zoom">จอง Zoom</option>
                                        </select>
                                    </td>
                                    <td width="250">
                                        <div class="input-group" style="width: 200px !important;">
                                            <span class="input-group-text">
                                                📅
                                            </span>
                                            <input class="form-control dateTrival" name="dateTrival[]" type="text" placeholder="dd/mm/yyyy" required>
                                        </div>
                                    </td>
                                    <td><input type="time" class="form-control" name="meeting_time_start[]" value="08:30"></td>
                                    <td><input type="time" class="form-control" name="meeting_time_end[]" value="23:30"></td>
                                    <td>
                                        <select name="meetingroom[]" class="form-select meetingroom">

                                        </select>
                                    </td>
                                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="ti ti-trash fs-5 me-2"></i>ลบ</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Profile (ฝ่ายสื่อสาร) -->
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                    <div class="card border border-2 table-wrapper table-responsive">
                        <h6 class="card-header py-3" style="background-color: #C8377C; color: white;">ส่วนงานสื่อสารองค์กร</h6>
                        <div class="card-body">
                            <!-- Option 1: ไม่ขอใช้บริการ / ขอใช้บริการ -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input corporate-service-type" type="radio" name="corporateServiceType" id="corporateNoService" value="no_service" checked>
                                        <label class="form-check-label fw-semibold mb-0" for="corporateNoService">
                                            <i class="ti ti-x fs-5 me-1"></i>1. ไม่ขอใช้บริการ
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input corporate-service-type" type="radio" name="corporateServiceType" id="corporateUseService" value="use_service">
                                        <label class="form-check-label fw-semibold mb-0" for="corporateUseService">
                                            <i class="ti ti-device-ipad-horizontal-star fs-5 me-1"></i>2. ขอใช้บริการ Showroom
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Option 2: ขอใช้บริการ Showroom Panel (ซ่อนเริ่มต้น) -->
                            <div id="corporateServicePanel" class="border rounded p-3 mb-3" style="display: none; background: #fafafa;">
                                <!-- วันที่ / ช่วงเวลา -->
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label fw-semibold">
                                        <i class="ti ti-calendar fs-5 me-1"></i>วันที่ / ช่วงเวลา
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control corporate-date" id="corporateDate" placeholder="dd/mm/yyyy">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="time" class="form-control" id="corporateTimeStart" value="08:30">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="time" class="form-control" id="corporateTimeEnd" value="17:00">
                                    </div>
                                </div>

                                <!-- ขอใช้บริการถ่ายรูป -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="usePhotoService">
                                            <label class="form-check-label fw-semibold" for="usePhotoService">
                                                <i class="ti ti-camera fs-5 me-1"></i>ขอใช้บริการถ่ายรูป
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="photoServicePanel" class="ms-4 border-start ps-3 mb-3" style="display: none;">
                                    <div class="row mb-2">
                                        <label class="col-sm-2 col-form-label">ช่วงเวลาถ่ายรูป</label>
                                        <div class="col-sm-3">
                                            <input type="time" class="form-control" id="photoTimeStart">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="time" class="form-control" id="photoTimeEnd">
                                        </div>
                                    </div>
                                    <small class="text-warning"><i class="ti ti-info-circle me-1"></i>หมายเหตุ: กรุณาติดต่อโทร 3CX 6355 เมื่อถึงช่วงเวลาถ่าย</small>
                                </div>

                                <!-- Welcome (ป้ายต้อนรับ) -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="useWelcomeService">
                                            <label class="form-check-label fw-semibold" for="useWelcomeService">
                                                <i class="ti ti-message fs-5 me-1"></i>Welcome (ป้ายต้อนรับ)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="welcomeServicePanel" class="ms-4 border-start ps-3 mb-3" style="display: none;">
                                    <div class="row mb-2">
                                        <label class="col-sm-2 col-form-label">รายละเอียด</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="welcomeDetail" rows="2" placeholder="ระบุชื่อบริษัท / ชื่อ-นามสกุลลูกค้า / ตำแหน่ง"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- LED Wall -->
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label fw-semibold">
                                        <i class="ti ti-device-desktop-cog fs-5 me-1"></i>LED Wall
                                    </label>
                                    <div class="col-sm-10 d-flex flex-wrap gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="LEDWallMP3" name="LEDWall[]" value="LEDWallMP3">
                                            <label class="form-check-label" for="LEDWallMP3">MP3+LEDWall</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="LEDWallMP4" name="LEDWall[]" value="LEDWallMP4">
                                            <label class="form-check-label" for="LEDWallMP4">MP4+LEDWall</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Travel -->
                <div class="tab-pane fade" id="nav-travel" role="tabpanel" aria-labelledby="nav-travel-tab" tabindex="0">
                    <div class="card border border-2 table-wrapper table-responsive">
                        <h6 class="card-header py-3" style="background-color: #FEC107; color: white;">การบริการลูกค้า</h6>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><i class="ti ti-user-circle fs-5 me-2"></i>ลูกค้าคนไทย</label>
                                <div class="col-sm-4">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="CustomerNameThai" min="0" value="0">
                                        <span class="input-group-text">ท่าน</span>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label"><i class="ti ti-user-square fs-5 me-2"></i>ลูกค้าต่างชาติ</label>
                                <div class="col-sm-4">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="CustomerNameForeign" min="0" value="0">
                                        <span class="input-group-text">ท่าน</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><i class="ti ti-users fs-5 me-2"></i>รวมผู้เข้าเยี่ยมชม</label>
                                <div class="col-sm-4">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="CustomerTotal" min="0" value="0" disabled>
                                        <span class="input-group-text">ท่าน</span>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label"><i class="ti ti-car fs-5 me-2"></i>การเดินทาง</label>
                                <div class="col-sm-4 d-flex flex-wrap align-items-center gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="travel" name="travel[]" value="TravelYourself">
                                        <label class="form-check-label" for="travel">เดินทางมาเอง</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="Provide" name="travel[]" value="Provide">
                                        <label class="form-check-label" for="Provide">จัดรถรับลูกค้า</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">ลูกค้าเดินทางมาเองจำนวนรถ</label>
                                <div class="col-sm-4">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="CustomerCar" min="0" value="0">
                                        <span class="input-group-text">คัน</span>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label">ทะเบียนรถ</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="CarNumber">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><i class="ti ti-phone-incoming fs-5 me-2"></i>พนักงานขับรถ/เบอร์ติดต่อ</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="DriverNumber" maxlength="10" pattern="\d{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><i class="ti ti-bookmark-edit fs-5 me-2"></i>หมายเหตุอื่นๆ</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="Remark" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab HR -->
                <div class="tab-pane fade" id="nav-hr" role="tabpanel" aria-labelledby="nav-hr-tab" tabindex="0">
                    <div class="card border border-2 table-wrapper table-responsive">
                        <h6 class="card-header py-3" style="background-color: #3AA9B0; color: white;">การรับรองอาหารและเครื่องดื่ม</h6>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><i class="ti ti-tools-kitchen-2 fs-5 me-2"></i>อาหารและเครื่องดื่ม</label>
                                <div class="col-sm-4 d-flex flex-column gap-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="food_1" name="cusFood[]" value="CoffeeBreak">
                                        <label class="form-check-label" for="food_1">เครื่องดื่มและของว่าง (Coffee Break)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="food_2" name="cusFood[]" value="HaveLunch">
                                        <label class="form-check-label" for="food_2">อาหารกลางวัน (ภายในบริษัท)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="food_3" name="cusFood[]" value="EatOutside">
                                        <label class="form-check-label" for="food_3">รับประทานข้างนอก</label>
                                    </div>
                                </div>
                                <div class="col-sm-2 d-flex flex-column mb-3">
                                    <label class="col-form-label p-0">จำนวนผู้ทานอาหาร</label>
                                    <span class="text-muted" style="font-size: 13px;">(รวมเจ้าหน้าที่อาซีฟา)</span>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="NumberDiners" min="0" value="0">
                                        <span class="input-group-text">ท่าน</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <span class="text-muted" style="font-size: 13px;">รายการอาหารอาจจะมีการปรับเปลี่ยนตามสถานการณ์และรายละเอียดอื่นๆ (ถ้ามีสามารถระบุในหมายเหตุได้)</span>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-form-label"><i class="ti ti-license fs-5 me-2"></i>รายการอาหาร</label>
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-hover" id="FoodTable">
                                        <thead>
                                            <tr id="foodTableHead">
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody id="foodTableBody">
                                            <!-- เมนูจะถูก generate จาก JS -->
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">*** อื่นๆ สามารถปรับเมนูได้โปรดระบุ</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="OtherMenu">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><i class="ti ti-bookmark-edit fs-5 me-2"></i>หมายเหตุ</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="RemarkFood" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <span class="text-muted" style="font-size: 13px;">หมายเหตุ แนวปฏิบัติสำหรับการจองอาหาร : กรณีข้อจำกัดอื่นๆ โปรดระบุ ให้ชัดเจน (เช่น ลูกค้าอิสลาม ทานมังสวิรัติ แพ้อาหารทะเล ไม่ทานเผ็ด เป็นต้น)</span>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Tab Lecturer -->
                <div class="tab-pane fade d-none" id="nav-lecturer" role="tabpanel" aria-labelledby="nav-lecturer-tab" tabindex="0">
                    <div class="card border border-2 table-wrapper table-responsive">
                        <h6 class="card-header py-3" style="background-color: #B8B0FF; color: white;">การขอวิทยากร</h6>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><i class="ti ti-users fs-5 me-2"></i>วิทยากร</label>
                                <div class="col-sm-4">
                                    <select class="form-select" name="Lecturer[]" id="Lecturer" multiple>

                                    </select>
                                </div>
                                <label class="col-sm-2 col-form-label text-nowrap">ประเภทการนำเสนอ</label>
                                <div class="col-sm-4 d-flex flex-wrap align-items-center gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="thai" name="presenttype[]" value="thai">
                                        <label class="form-check-label" for="thai">ไทย</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="eng" name="presenttype[]" value="eng">
                                        <label class="form-check-label" for="eng">อังกฤษ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="upload-area" onclick="document.getElementById('fileInput_lecturer').click()">
                                    <div class="upload-icon">📤</div>
                                    <div class="upload-text">คลิกหรือลากไฟล์มาวางที่นี่</div>
                                    <div class="upload-subtext">รองรับไฟล์ รูปภาพ, PDF, Word, Excel • สามารถเลือกหลายไฟล์พร้อมกัน</div>
                                </div>
                                <input type="file" id="fileInput_lecturer" class="d-none" multiple accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.pdf,.doc,.docx,.xls,.xlsx">
                                <div class="file-list gap-2" id="fileList_lecturer"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab PRD (วางแผน) -->
                <div class="tab-pane fade" id="nav-prd" role="tabpanel" aria-labelledby="nav-prd-tab" tabindex="0">
                    <div class="card border border-2 table-wrapper table-responsive">
                        <h6 class="card-header py-3" style="background-color: #10B981; color: white;">ฝ่ายวางแผน (PRD)</h6>
                        <div class="card-body">
                            <!-- Test Readiness -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="col-form-label fw-semibold"><i class="ti ti-clipboard-check fs-5 me-2"></i>ความพร้อมในการ Test</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input prd-test-ready" type="radio" name="prdTestReady" id="prdReady" value="ready">
                                            <label class="form-check-label fw-semibold text-success" for="prdReady">
                                                <i class="ti ti-check fs-5 me-1"></i>พร้อม Test
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input prd-test-ready" type="radio" name="prdTestReady" id="prdNotReady" value="not_ready" checked>
                                            <label class="form-check-label fw-semibold text-danger" for="prdNotReady">
                                                <i class="ti ti-x fs-5 me-1"></i>ไม่พร้อม Test
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PRD Options Panel (แสดงเมื่อเลือก "พร้อม Test") -->
                            <div id="prdOptionsPanel" class="row mb-3" style="display: none;">
                                <div class="col-md-6">
                                    <label class="col-form-label"><i class="ti ti-calendar fs-5 me-2"></i>วันที่ต้องการ Test</label>
                                    <input type="text" class="form-control prdDateTest" placeholder="dd/mm/yyyy">
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label"><i class="ti ti-clock fs-5 me-2"></i>เวลาที่ต้องการ</label>
                                    <div class="d-flex gap-2">
                                        <input type="time" class="form-control" id="prdTimeStart" value="09:00">
                                        <span class="align-self-center">ถึง</span>
                                        <input type="time" class="form-control" id="prdTimeEnd" value="17:00">
                                    </div>
                                </div>
                            </div>

                            <!-- Remark -->
                            <div class="row mb-3">
                                <label class="col-form-label"><i class="ti ti-notes fs-5 me-2"></i>หมายเหตุ</label>
                                <div class="col-12">
                                    <textarea class="form-control" id="prdRemark" rows="3" placeholder="ระบุหมายเหตุเพิ่มเติม..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('layout/theme-settings.php'); ?>

        <?php //include('layout/footer.php'); 
        ?>

    </main>

    <?php include('js.php'); ?>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        window.__VISITOR_SESSION__ = {
            perm: <?php echo json_encode($perm, JSON_UNESCAPED_UNICODE); ?>,
            code: <?php echo json_encode($code, JSON_UNESCAPED_UNICODE); ?>,
            isAdmin: <?php echo $isAdmin ? 'true' : 'false'; ?>
        };
    </script>

    <script>
        let uploadBoxes = {
            detail: [],
            lecturer: []
        };

        // Cached options for Select2 dropdowns
        let cachedOptions = {
            sales: '',
            tc: '',
            dept: '',
            groupctm: '',
            zone: '<option value="กรุงเทพฯ และปริมณฑล">กรุงเทพฯ และปริมณฑล</option><option value="ภาคกลาง">ภาคกลาง</option><option value="ภาคเหนือ">ภาคเหนือ</option><option value="ภาคใต้">ภาคใต้</option><option value="ภาคตะวันออก">ภาคตะวันออก</option><option value="ภาคตะวันตก">ภาคตะวันตก</option><option value="ภาคตะวันออกเฉียงเหนือ">ภาคตะวันออกเฉียงเหนือ</option><option value="ต่างประเทศ">ต่างประเทศ</option>',
            product: '<option value=""></option><option value="AMS">AMS</option><option value="MV">MV</option><option value="PRISMA">PRISMA</option><option value="BS">BS</option>'
        };
        let jobCounter = 0;

        // Generate Job Card HTML
        function createJobCardHTML(index, jobData = {}) {
            const showRemove = index > 0 ? '' : 'style="display: none;"';
            return `
                <div class="job-card" data-job-index="${index}">
                    <div class="job-card-header">
                        <span class="job-number">Job #${index + 1}</span>
                        <button type="button" class="btn-remove-job" ${showRemove} onclick="removeJobCard(this)">
                            <i class="ti ti-trash"></i> ลบ
                        </button>
                    </div>
                    <div class="job-card-body">
                        <div class="form-grid">
                            <div class="form-group-clean">
                                <label><i class="ti ti-dialpad"></i>JOB No.</label>
                                <div class="input-group">
                                    <input type="text" class="form-control job-field" data-field="JobNo" value="${jobData.JobNo || ''}" placeholder="กรอกเลข Job">
                                    <button class="btn btn-outline-primary btn-search-job" type="button"><i class="ti ti-search fs-5"></i></button>
                                </div>
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-sitemap"></i>ชื่อโครงการ</label>
                                <input type="text" class="form-control job-field" data-field="ProjectName" value="${jobData.ProjectName || ''}" placeholder="ชื่อโครงการ">
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-barcode"></i>Switchboard/Serial</label>
                                <select class="form-select job-field job-select-sn selectable" data-field="SN" multiple></select>
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-hash"></i>WA No.</label>
                                <select class="form-select job-field job-select-wa selectable" data-field="WA" multiple></select>
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-user-cog"></i>TC ผู้ดูแล</label>
                                <select class="form-select job-field job-select-tc" data-field="TCName"><option value=""></option>${cachedOptions.tc}</select>
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-archive"></i>ชื่อผลิตภัณฑ์</label>
                                <select class="form-select job-field job-select-product" data-field="ProductName">${cachedOptions.product}</select>
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-user-dollar"></i>Sales / ผู้รับผิดชอบงาน</label>
                                <select class="form-select job-field job-select-sales" data-field="SalesName"><option value=""></option>${cachedOptions.sales}</select>
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-vector-bezier"></i>ฝ่าย / แผนก</label>
                                <select class="form-select job-field job-select-dept" data-field="Department"><option value=""></option>${cachedOptions.dept}</select>
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-building-skyscraper"></i>ชื่อบริษัท/หน่วยงาน</label>
                                <input type="text" class="form-control job-field" data-field="CompanyName" value="${jobData.CompanyName || ''}" placeholder="ชื่อบริษัท">
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-users-group"></i>กลุ่มลูกค้า</label>
                                <select class="form-select job-field job-select-groupctm" data-field="GroupCtm" multiple>${cachedOptions.groupctm}</select>
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-user-bitcoin"></i>ชื่อลูกค้า (ผู้ประสานงาน)</label>
                                <input type="text" class="form-control job-field" data-field="CustomerName" value="${jobData.CustomerName || ''}" placeholder="ชื่อลูกค้า">
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-reorder"></i>ตําแหน่ง</label>
                                <input type="text" class="form-control job-field" data-field="Position" value="${jobData.Position || ''}" placeholder="ตำแหน่ง">
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-phone"></i>เบอร์ติดต่อ</label>
                                <input type="text" class="form-control job-field" data-field="PhoneNumber" value="${jobData.PhoneNumber || ''}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-map-pin"></i>พื้นที่/โซน</label>
                                <select class="form-select job-field job-select-zone" data-field="Zone" multiple>${cachedOptions.zone}</select>
                            </div>
                            <div class="form-group-clean">
                                <label><i class="ti ti-flag"></i>ประเภทบริษัท</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input job-company-type" type="radio" name="companyType_job${index}" value="thai" id="companyType_thai_job${index}" ${(!jobData.CompanyType || jobData.CompanyType === 'thai') ? 'checked' : ''}>
                                        <label class="form-check-label" for="companyType_thai_job${index}">บริษัทไทย</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input job-company-type" type="radio" name="companyType_job${index}" value="foreign" id="companyType_foreign_job${index}" ${jobData.CompanyType === 'foreign' ? 'checked' : ''}>
                                        <label class="form-check-label" for="companyType_foreign_job${index}">บริษัทต่างชาติ</label>
                                    </div>
                                </div>
                            </div>
                            <div></div>
                            <div class="form-group-clean full-width">
                                <label><i class="ti ti-alert-circle"></i>ระบุรายละเอียด</label>
                                <textarea class="form-control job-field" data-field="Detail" rows="3" placeholder="รายละเอียดเพิ่มเติม...">${jobData.Detail || ''}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Add new Job Card
        function addJobCard() {
            const container = document.getElementById('job-cards-container');
            const newIndex = jobCounter++;
            container.insertAdjacentHTML('beforeend', createJobCardHTML(newIndex));
            const newCard = container.lastElementChild;
            initJobCardSelects(newCard);
            renumberJobCards();
            newCard.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        // Remove Job Card
        function removeJobCard(btn) {
            const card = btn.closest('.job-card');
            const container = document.getElementById('job-cards-container');
            if (container.querySelectorAll('.job-card').length <= 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่สามารถลบได้',
                    text: 'ต้องมีอย่างน้อย 1 Job'
                });
                return;
            }
            card.remove();
            renumberJobCards();
        }

        // Renumber Job Cards
        function renumberJobCards() {
            const cards = document.querySelectorAll('#job-cards-container .job-card');
            cards.forEach((card, index) => {
                card.querySelector('.job-number').textContent = `Job #${index + 1}`;
                const removeBtn = card.querySelector('.btn-remove-job');
                removeBtn.style.display = index === 0 && cards.length === 1 ? 'none' : 'flex';
            });
        }

        // Initialize Select2 for a Job Card
        function initJobCardSelects(card) {
            const $card = $(card);
            const common = {
                allowClear: true,
                theme: 'bootstrap-5'
            };

            $card.find('.job-select-sales').select2({
                ...common,
                placeholder: 'กรุณาเลือกผู้รับผิดชอบ'
            });
            $card.find('.job-select-tc').select2({
                ...common,
                placeholder: 'กรุณาเลือก TC ผู้รับผิดชอบ'
            });
            $card.find('.job-select-dept').select2({
                ...common,
                placeholder: 'กรุณาเลือกแผนก'
            });
            $card.find('.job-select-sn').select2({
                ...common,
                placeholder: 'กรุณาเลือก S/N'
            });
            $card.find('.job-select-wa').select2({
                ...common,
                placeholder: 'กรุณาเลือก WA'
            });
            $card.find('.job-select-groupctm').select2({
                ...common,
                placeholder: 'กรุณาเลือกกลุ่มลูกค้า'
            });
            $card.find('.job-select-zone').select2({
                ...common,
                placeholder: 'กรุณาเลือกพื้นที่/โซน'
            });
            $card.find('.job-select-product').select2({
                ...common,
                placeholder: 'กรุณาเลือกผลิตภัณฑ์'
            });
        }

        // Render Job Cards from SalesDetail JSON
        function renderJobCards(salesDetail) {
            const container = document.getElementById('job-cards-container');
            container.innerHTML = '';
            jobCounter = 0;

            if (!salesDetail || salesDetail.length === 0) {
                // Create one empty job card
                addJobCard();
                return;
            }

            salesDetail.forEach((job, idx) => {
                const newIndex = jobCounter++;
                container.insertAdjacentHTML('beforeend', createJobCardHTML(newIndex, job));
                const card = container.lastElementChild;
                initJobCardSelects(card);

                // Set selected values for selects
                setTimeout(() => {
                    const $card = $(card);
                    if (job.TCName) $card.find('.job-select-tc').val(job.TCName).trigger('change');
                    if (job.ProductName) $card.find('.job-select-product').val(job.ProductName).trigger('change');
                    if (job.SalesName) $card.find('.job-select-sales').val(job.SalesName).trigger('change');
                    if (job.Department) $card.find('.job-select-dept').val(job.Department).trigger('change');
                    if (job.GroupCtm) $card.find('.job-select-groupctm').val(job.GroupCtm).trigger('change');
                    if (job.Zone) $card.find('.job-select-zone').val(job.Zone).trigger('change');
                    if (job.SN) $card.find('.job-select-sn').val(job.SN).trigger('change');
                    if (job.WA) $card.find('.job-select-wa').val(job.WA).trigger('change');
                }, 500);
            });
            renumberJobCards();
        }

        // Collect SalesDetail from Job Cards
        function collectSalesDetail() {
            const cards = document.querySelectorAll('#job-cards-container .job-card');
            const details = [];
            cards.forEach(card => {
                const job = {};
                card.querySelectorAll('.job-field').forEach(field => {
                    const name = field.dataset.field;
                    if (!name) return;
                    if (field.tagName === 'SELECT' && field.multiple) {
                        job[name] = $(field).val() || [];
                    } else if (field.tagName === 'SELECT') {
                        job[name] = $(field).val() || '';
                    } else {
                        job[name] = field.value || '';
                    }
                });
                const companyType = card.querySelector('.job-company-type:checked');
                job['CompanyType'] = companyType ? companyType.value : 'thai';
                details.push(job);
            });
            return details;
        }

        function initUploadBox(inputId, listId, key) {
            const fileInput = document.getElementById(inputId);
            const fileList = document.getElementById(listId);
            const uploadArea = fileInput.previousElementSibling; // div.upload-area

            fileInput.addEventListener('change', (e) => {
                handleFiles(Array.from(e.target.files), key, fileList);
            });

            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                handleFiles(Array.from(e.dataTransfer.files), key, fileList);
            });
        }

        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
        const dangerousExtensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps', 'pht', 'phar',
            'exe', 'sh', 'bat', 'cmd', 'com', 'vbs', 'js', 'wsf', 'cgi', 'pl', 'py', 'rb', 'asp', 'aspx',
            'jsp', 'war', 'svg', 'htaccess', 'htpasswd'];

        function validateFile(file) {
            const name = file.name.toLowerCase();
            const parts = name.split('.');
            if (parts.length < 2) return 'ไฟล์ไม่มีนามสกุล';
            const ext = parts[parts.length - 1];
            for (const part of parts) {
                if (dangerousExtensions.includes(part)) {
                    return `ไฟล์มีนามสกุลที่ไม่อนุญาต (.${part})`;
                }
            }
            if (!allowedExtensions.includes(ext)) {
                return 'รองรับเฉพาะไฟล์ รูปภาพ, PDF, Word, Excel';
            }
            return null;
        }

        function handleFiles(files, key, fileList) {
            const rejected = [];
            files.forEach(file => {
                const error = validateFile(file);
                if (error) {
                    rejected.push(`${file.name}: ${error}`);
                    return;
                }
                const exists = uploadBoxes[key].some(f => f.name === file.name && f.size === file.size);
                if (!exists) {
                    uploadBoxes[key].push(file);
                }
            });
            if (rejected.length > 0) {
                alert('ไฟล์ต่อไปนี้ไม่สามารถแนบได้:\n' + rejected.join('\n'));
            }
            updateFileList(key, fileList);
        }

        function updateFileList(key, fileList) {
            fileList.innerHTML = '';
            uploadBoxes[key].forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <div class="file-info">
                        <div class="file-icon">${getFileIcon(file.name)}</div>
                        <div class="file-details">
                            <div class="file-name">${file.name}</div>
                            <div class="file-size">${formatFileSize(file.size)}</div>
                        </div>
                    </div>
                    <button class="delete-btn" onclick="removeFile('${key}', ${index}, '${fileList.id}')">ลบ</button>
                `;
                fileList.appendChild(fileItem);
            });
        }

        function removeFile(key, index, listId) {
            uploadBoxes[key].splice(index, 1);
            updateFileList(key, document.getElementById(listId));
        }

        function getFileIcon(filename) {
            const extension = filename.split('.').pop().toLowerCase();

            const iconMap = {
                'pdf': '📄',
                'doc': '📝',
                'docx': '📝',
                'xls': '📊',
                'xlsx': '📊',
                'ppt': '📽️',
                'pptx': '📽️',
                'txt': '📃',
                'jpg': '🖼️',
                'jpeg': '🖼️',
                'png': '🖼️',
                'gif': '🖼️',
                'mp4': '🎥',
                'avi': '🎥',
                'mov': '🎥',
                'mp3': '🎵',
                'wav': '🎵',
                'flac': '🎵',
                'zip': '📦',
                'rar': '📦',
                '7z': '📦',
                'html': '🌐',
                'css': '🎨',
                'js': '⚙️',
                'py': '🐍',
                'java': '☕',
                'cpp': '⚡'
            };

            return iconMap[extension] || '📄';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';

            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        async function loadFoodSets() {
            const $table = $('#FoodTable');
            const $thead = $('#foodTableHead');
            const $tbody = $('#foodTableBody');

            try {
                const response = await fetch('api/food_set_action.php', {
                    cache: 'no-store'
                });
                const {
                    status,
                    data
                } = await response.json();

                if (!status || !data?.length) {
                    $tbody.html('<tr><td colspan="10" class="text-center text-muted">ไม่มีข้อมูลชุดอาหาร</td></tr>');
                    return;
                }

                // สร้างหัวตารางทั้งหมดใน memory ก่อน
                let headHTML = '<th class="text-center">#</th>';
                data.forEach(set => headHTML += `<th class="text-center">${set.food_set_name}</th>`);
                $thead.html(headHTML);

                // คำนวณจำนวนเมนูสูงสุด
                const maxItems = Math.max(...data.map(set => set.food_items.length));
                const rows = [];

                for (let i = 0; i < maxItems; i++) {
                    let rowHTML = `<tr><td class="text-center">${i + 1}</td>`;
                    for (const set of data) {
                        const menu = set.food_items[i];
                        if (menu) {
                            const id = `food_${set.food_set_id}_${i}`;
                            rowHTML += `
                                <td class="text-center">
                                    <div class="form-check">
                                        <input class="form-check-input food-choice"
                                            type="checkbox"
                                            id="${id}"
                                            name="food[]"
                                            data-setid="${set.food_set_id}"
                                            data-setname="${set.food_set_name}"
                                            disabled
                                            value="${menu}">
                                        <label class="form-check-label" for="${id}" title="${menu}">
                                            ${menu}
                                        </label>
                                    </div>
                                </td>`;
                        } else {
                            rowHTML += '<td></td>';
                        }
                    }
                    rowHTML += '</tr>';
                    rows.push(rowHTML);
                }

                $tbody.html(rows.join(''));

            } catch (error) {
                console.error('Error loading food sets:', error);
                $tbody.html('<tr><td colspan="10" class="text-center text-danger">โหลดข้อมูลผิดพลาด</td></tr>');
            }
        }

        // Event เลือกเมนูอาหาร — bind ครั้งเดียวเท่านั้น
        $(document).on('change', '.food-choice', function() {
            const currentSet = $(this).data('setid');
            const allChoices = $('.food-choice');
            const checkedInThisSet = $(`.food-choice[data-setid="${currentSet}"]:checked`);
            const diners = parseInt($('#NumberDiners').val()) || 0;

            let maxSelectable = 0;
            if (diners <= 3) maxSelectable = 3;
            else if (diners <= 6) maxSelectable = 4;
            else if (diners <= 10) maxSelectable = 6;

            if (checkedInThisSet.length > maxSelectable && maxSelectable > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: `เลือกได้สูงสุด ${maxSelectable} เมนู`,
                    text: `ตามจำนวนผู้ทาน (${diners} ท่าน)`
                });
                $(this).prop('checked', false);
                return;
            }

            const otherSets = [...new Set(allChoices.map((_, el) => $(el).data('setid')).get())]
                .filter(id => id !== currentSet);

            if (checkedInThisSet.length > 0) {
                otherSets.forEach(id => $(`.food-choice[data-setid="${id}"]`).prop('disabled', true));
            } else {
                allChoices.prop('disabled', false);
            }
        });

        $(document).on('input', '#NumberDiners', function() {
            $('.food-choice').prop('checked', false).prop('disabled', false);
        });

        $(document).ready(async function() {
            try {
                Swal.fire({
                    title: 'กำลังโหลดข้อมูล...',
                    text: 'โปรดรอสักครู่',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $('input[type="number"]').on('input', function() {
                    if ($(this).val() < 0) {
                        $(this).val(0);
                    }
                });

                // PRD Tab - Test Ready toggle
                $(document).on('change', '.prd-test-ready', function() {
                    if ($('#prdReady').is(':checked')) {
                        $('#prdOptionsPanel').slideDown(300);
                    } else {
                        $('#prdOptionsPanel').slideUp(300);
                    }
                });

                // Initialize PRD datepicker
                $('.prdDateTest').datepicker({
                    dateFormat: 'dd/mm/yy',
                    minDate: 0
                });

                const [objectiveList, groupList, foodSets, empSalesRes, empAllRes, divisionRes] = await Promise.all([
                    get_objective_list(),
                    get_groupctm_list(),
                    loadFoodSets(),
                    fetch('./api/emp_list.php?action=emplist&divi_code_1=61').then(r => r.json()),
                    fetch('./api/emp_list.php?action=emplist').then(r => r.json()),
                    fetch('./api/emp_list.php?action=divisionlist').then(r => r.json())
                ]);
                initUploadBox('fileInput_detail', 'fileList_detail', 'detail');
                initUploadBox('fileInput_lecturer', 'fileList_lecturer', 'lecturer');

                // ---------- เพิ่มข้อมูลลง select โดยรวม string ก่อน append ----------
                const $SalesName = $('#SalesName');
                const $TCName = $('#TCName');
                const $Lecturer = $('#Lecturer');
                const $Department = $('#Department');

                let salesHTML = '',
                    tcHTML = '',
                    lecturerHTML = '',
                    deptHTML = '';

                empSalesRes.forEach(e => salesHTML += `<option value="${e.Code}">${e.FullName}</option>`);
                empAllRes.forEach(e => {
                    tcHTML += `<option value="${e.Code}">${e.FullName}</option>`;
                    lecturerHTML += `<option value="${e.Code}">${e.FullName}</option>`;
                });
                Object.values(divisionRes).forEach(sec => {
                    Object.values(sec.Department).forEach(dep => {
                        deptHTML += `<option value="${dep.DepartmentCode}">${sec.SectionCode}-${sec.SectionName}/${dep.DepartmentCode}-${dep.DepartmentName}</option>`;
                    });
                });

                $SalesName.html(salesHTML);
                $TCName.html(tcHTML);
                $Lecturer.html(lecturerHTML);
                $Department.html(deptHTML);

                // Populate cachedOptions สำหรับ Job Cards
                cachedOptions.sales = salesHTML;
                cachedOptions.tc = tcHTML;
                cachedOptions.dept = deptHTML;

                // สร้าง group customer options
                let groupctmHTML = '';
                groupList.forEach(g => {
                    groupctmHTML += `<option value="${g.Name}">${g.Name}</option>`;
                });
                cachedOptions.groupctm = groupctmHTML;

                // ---------- Select2 Init ----------
                const select2Common = {
                    allowClear: true,
                    theme: "bootstrap-5"
                };
                const selects = [{
                        id: '#SalesName',
                        placeholder: 'กรุณาเลือกผู้รับผิดชอบ'
                    },
                    {
                        id: '#TCName',
                        placeholder: 'กรุณาเลือก TC ผู้รับผิดชอบ'
                    },
                    {
                        id: '#WA',
                        placeholder: 'กรุณาเลือก WA'
                    },
                    {
                        id: '#SN',
                        placeholder: 'กรุณาเลือก S/N'
                    },
                    {
                        id: '#Department',
                        placeholder: 'กรุณาเลือกแผนก'
                    },
                    {
                        id: '#Lecturer',
                        placeholder: 'กรุณาเลือกรายชื่อวิทยากร'
                    }
                ];
                selects.forEach(sel => {
                    const $el = $(sel.id);

                    if ($el.find('option[value=""]').length === 0) {
                        $el.prepend('<option value=""></option>');
                    }

                    $el.select2({
                        ...select2Common,
                        placeholder: sel.placeholder
                    });

                    $el.val(null).trigger('change');
                });

                $('.dateTrival').datepicker({
                    dateFormat: 'dd/mm/yy',
                    appendTo: 'body',
                    minDate: 0
                });

                $(document).on("change", "input[name='objective[]']", function() {
                    let hasLecturer = $("input[name='objective[]']:checked").toArray().some(chk => chk.value === "2" || chk.value === "3");

                    if (hasLecturer) {
                        $("#nav-lecturer, #nav-lecturer-tab").removeClass("d-none");
                    } else {
                        $("#nav-lecturer, #nav-lecturer-tab").addClass("d-none");
                    }
                });

                $('#objective_other').on('change', function() {
                    if (this.checked) {
                        $('#box_objective_other').stop(true, true).slideDown(300);
                    } else {
                        $('#box_objective_other').stop(true, true).slideUp(300);
                    }
                });

                // Communication Tab - Corporate Service Panel Toggle
                $(document).on('change', '.corporate-service-type', function() {
                    if ($('#corporateUseService').is(':checked')) {
                        $('#corporateServicePanel').slideDown(300);
                    } else {
                        $('#corporateServicePanel').slideUp(300);
                    }
                });

                // Communication Tab - Photo Service Panel Toggle
                $(document).on('change', '#usePhotoService', function() {
                    if (this.checked) {
                        $('#photoServicePanel').slideDown(300);
                    } else {
                        $('#photoServicePanel').slideUp(300);
                    }
                });

                // Communication Tab - Welcome Service Panel Toggle
                $(document).on('change', '#useWelcomeService', function() {
                    if (this.checked) {
                        $('#welcomeServicePanel').slideDown(300);
                    } else {
                        $('#welcomeServicePanel').slideUp(300);
                    }
                });

                // Initialize corporate date picker
                $('.corporate-date').datepicker({
                    dateFormat: 'dd/mm/yy',
                    minDate: 0
                });

                $('#addSchedule').on('click', function() {
                    let newRow = $(`
                        <tr>
                            <td>
                                <select class="form-select reserve">
                                    <option value="meeting">จองห้องประชุม</option>
                                    <option value="zoom">จอง Zoom</option>
                                </select>
                            </td>
                            <td width="250">
                                <div class="input-group" style="width: 200px !important;">
                                    <span class="input-group-text">📅</span>
                                    <input class="form-control dateTrival" name="dateTrival[]" type="text" placeholder="dd/mm/yyyy" required>
                                </div>
                            </td>
                            <td><input type="time" class="form-control" name="meeting_time_start[]" value="08:30"></td>
                            <td><input type="time" class="form-control" name="meeting_time_end[]" value="23:30"></td>
                            <td>
                                <select name="meetingroom[]" class="form-select meetingroom">
                                    
                                </select>
                            </td>
                            <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="ti ti-trash fs-5 me-2"></i>ลบ</button></td>
                        </tr>
                    `);

                    $('#table-schedule tbody').append(newRow);

                    newRow.find('.dateTrival').datepicker({
                        dateFormat: 'dd/mm/yy',
                        appendTo: 'body',
                        minDate: 0
                    });

                    // new TomSelect(newRow.find('.meetingroom')[0], {
                    //     allowEmptyOption: true,
                    //     dropdownParent: 'body'
                    // });
                });

                $(document).on('click', '.removeRow', function() {
                    $(this).closest('tr').remove();
                });

                $('#showroom_3').on('change', function() {
                    if (this.checked) {
                        $('#showroom_1, #showroom_2').prop('checked', false);
                    }
                });

                $('#showroom_1, #showroom_2').on('change', function() {
                    if (this.checked) {
                        $('#showroom_3').prop('checked', false);
                    }
                });

                $('#food_3').on('change', function() {
                    if (this.checked) {
                        $('#food_1, #food_2').prop('checked', false);
                        $('.food-choice').prop('disabled', true);
                    }
                });

                $('#food_1, #food_2').on('change', function() {
                    if (this.checked) {
                        $('#food_3').prop('checked', false);
                    }
                });

                $('#food_2').on('change', function() {
                    if (this.checked) {
                        $('.food-choice').prop('disabled', false);
                    }
                });

                $('input[name="cusFood[]"]').on('change', function() {
                    const anyChecked = $('input[name="cusFood[]"]:checked').length > 0;

                    if (!anyChecked) {
                        $('#food_3').prop('checked', true);
                        $('.food-choice').prop('disabled', true);
                    }
                });

                function updateTotal() {
                    let thai = parseInt($('#CustomerNameThai').val()) || 0;
                    let foreign = parseInt($('#CustomerNameForeign').val()) || 0;

                    $('#CustomerTotal').val(thai + foreign);
                }

                $('#CustomerNameThai, #CustomerNameForeign').on('input', updateTotal);

                updateTotal();

                $('.selectable').change(function() {
                    var selectedOptions = $(this).val() || [];

                    if (selectedOptions.includes('All')) {
                        $(this).find('option').each(function() {
                            var val = $(this).val();
                            if (val !== 'All' && val !== '-' && val !== '') {
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('selected', false);
                            }
                        });
                    } else {
                        $(this).find('option[value="All"]').prop('selected', false);
                    }
                });

                // ---------- ค้นหา Job ----------
                $('#search_job').on('click', function() {
                    const jobno = $('#JobNo').val();
                    Swal.fire({
                        title: 'กำลังโหลดข้อมูล...',
                        text: 'โปรดรอสักครู่',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    const postData = (action) => $.ajax({
                        url: 'https://innovation.asefa.co.th/ChangeRequestForm/searchjobvisit',
                        type: 'POST',
                        data: {
                            jobno,
                            action
                        }
                    });

                    $.when(postData('searchjob'), postData('search'), postData('searchsn')).done((resJob, resSearch, resSn) => {
                        try {
                            const dataJob = JSON.parse(resJob[0]);
                            if (!dataJob.error) {
                                $('#ProjectName').val(dataJob.JobName);
                                $('#CompanyName').val(dataJob.CustomerName);
                                $('#owner_contact').val(dataJob.Users);
                                $('#salename_text').val(dataJob.SaleName);
                                $('#jobvalue').val(dataJob.Cost);
                                $('#SalesName').val(dataJob.SaleID).trigger('change.select2');
                                $('#Department').val(dataJob.Dep_ID).trigger('change.select2');
                            } else {
                                $('#ProjectName, #CompanyName').val('');
                                $('#SalesName, #Department').val('').trigger('change.select2');
                            }
                        } catch {}

                        try {
                            const dataSearch = JSON.parse(resSearch[0]);
                            if (!dataSearch.error) {
                                const $WA = $('#WA').empty().append(new Option('เลือกเลข WA ทั้งหมด', 'All'));
                                if (Array.isArray(dataSearch.Doc_No)) {
                                    dataSearch.Doc_No.forEach(item => $WA.append(new Option(item, item)));
                                }
                                $WA.append(new Option('-', '-')).val(null).select2({
                                    placeholder: 'กรุณาเลือก WA',
                                    allowClear: true,
                                    theme: "bootstrap-5"
                                });
                            }
                        } catch {}

                        $('#SN').empty().append(resSn[0]).select2({
                            placeholder: 'กรุณาเลือก S/N',
                            allowClear: true,
                            theme: "bootstrap-5"
                        });
                    }).always(() => Swal.close());
                });

                const urlParams = new URLSearchParams(window.location.search);
                const visitorId = urlParams.get('id');

                if (!visitorId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่พบข้อมูล',
                        text: 'กรุณาระบุ Visitor ID'
                    });
                    return;
                }
                await getVisitorData(visitorId);
                await renderTimeline(visitorId);
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'ผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการโหลดข้อมูล'
                });
            }

            // Swal.close();

        });

        $(document).on('change', '.reserve, .dateTrival, input[name="meeting_time_start[]"], input[name="meeting_time_end[]"]', function() {
            let row = $(this).closest('tr');
            loadMeetingRoom(row);
        });

        async function renderTimeline(visitorId) {
            const res = await fetch(`api/get_acknowledge_timeline.php?id=${visitorId}`);
            const data = await res.json();
            // console.log(data);
            if (!data.status) return;
            const timeline = data.data.timeline;
            const list = document.getElementById("ack-timeline");
            list.innerHTML = "";

            timeline.forEach((event, index) => {
                const div = document.createElement("div");
                div.classList.add("timeline-item");

                div.innerHTML = `
                    <div class="timeline-status-${event.status}">
                        ${event.status == 'acknowledged' ? '<i class="ti ti-rosette-discount-check"></i>' : '<i class="ti ti-refresh"></i>'}
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-${event.status}">${event.label}</div>
                        <div class="timeline-time">${event.time ?? event.text}</div>
                    </div>
                `;
                list.appendChild(div);
            });

            //<div class="timeline-user">👤 ${event.user}</div>
        }

        async function getVisitorData(visitorId) {
            try {
                const res = await fetch(`api/visitor_form_get.php?id=${visitorId}`);
                const data = await res.json();

                if (!data.status) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่พบข้อมูล',
                        text: data.message
                    });
                    return;
                }

                const d = data.data;
                console.log('Visitor data:', d);

                // Log new JSON fields for debugging
                console.log('SalesDetail:', d.SalesDetail);
                console.log('CorporateDetail:', d.CorporateDetail);
                console.log('LecturerDetail:', d.LecturerDetail);
                console.log('PRDDetail:', d.PRDDetail);

                // Parse and render SalesDetail JSON to Job Cards
                let salesDetail = [];
                try {
                    salesDetail = typeof d.SalesDetail === 'string' ? JSON.parse(d.SalesDetail || '[]') : (d.SalesDetail || []);
                } catch (e) {
                    console.error('Error parsing SalesDetail:', e);
                }

                // Fallback: ถ้าไม่มี SalesDetail ให้สร้าง Job Card จากข้อมูลเดิม
                if (!salesDetail || salesDetail.length === 0) {
                    const legacyJob = {
                        JobNo: d.JobNo || '',
                        ProjectName: d.ProjectName || '',
                        SN: d.SN ? (Array.isArray(d.SN) ? d.SN : d.SN.split(',')) : [],
                        WA: d.WA ? (Array.isArray(d.WA) ? d.WA : d.WA.split(',')) : [],
                        TCName: d.TCName || '',
                        ProductName: d.ProductName || '',
                        SalesName: d.SalesName || '',
                        Department: d.Department || '',
                        CompanyName: d.CompanyName || '',
                        GroupCtm: d.Groupctm ? (Array.isArray(d.Groupctm) ? d.Groupctm : d.Groupctm.split(',')) : [],
                        CustomerName: d.CustomerName || '',
                        Position: d.Position || '',
                        PhoneNumber: d.PhoneNumber || '',
                        Zone: [],
                        Detail: d.Detail || '',
                        CompanyType: 'thai'
                    };
                    salesDetail = [legacyJob];
                    console.log('Using legacy data for Job Card:', legacyJob);
                }

                renderJobCards(salesDetail);

                // Populate PRDDetail JSON
                let prdDetail = {};
                try {
                    prdDetail = typeof d.PRDDetail === 'string' ? JSON.parse(d.PRDDetail || '{}') : (d.PRDDetail || {});
                } catch (e) {
                    console.error('Error parsing PRDDetail:', e);
                }
                if (prdDetail.testReady === 'ready') {
                    $('#prdReady').prop('checked', true);
                    $('#prdOptionsPanel').show();
                    if (prdDetail.dateTest) $('.prdDateTest').val(prdDetail.dateTest);
                    if (prdDetail.timeStart) $('#prdTimeStart').val(prdDetail.timeStart);
                    if (prdDetail.timeEnd) $('#prdTimeEnd').val(prdDetail.timeEnd);
                } else {
                    $('#prdNotReady').prop('checked', true);
                }
                if (prdDetail.remark) $('#prdRemark').val(prdDetail.remark);

                $('#status').val(d.Status ?? 0);
                $("#DocNo-Status").html(`<span class="fw-bold">${d.DocNo}</span> : <span class="badge text-bg-primary" style="font-size: 13px; background-color: ${status_color[d.Status]} !important;">${status_text[d.Status]}</span>`);
                if (parseInt(d.Status) === 1) {
                    $('#save-edit').show();
                    if (d.UserCreated != '<?php echo $_SESSION['VisitorMKT_code']; ?>') {
                        $('#save-edit').hide();
                    }
                    $('#save-sent').hide();
                } else if (parseInt(d.Status) === 0) {
                    $('#save-edit').show();
                    $('#save-sent').show();
                } else {
                    $('#save-edit').hide();
                    $('#save-sent').hide();
                }

                if ((d.UserCreated == '<?php echo $_SESSION['VisitorMKT_code']; ?>' || <?php echo $isAdmin; ?>) && d.Status !== 3 && d.Status !== 9) {
                    $('#btn-cancel-job').removeClass('d-none');
                }

                // ====== เรียก API Job Search ถ้ามี JobNo ======
                if (d.JobNo && d.JobNo.trim() !== "") {
                    $('#JobNo').val(d.JobNo);
                    $('#search_job').trigger('click');

                    setTimeout(() => {
                        $('#SalesName').val(d.SalesName).trigger('change.select2');
                        if (d.WA) {
                            const wa = JSON.parse(d.WA || '[]');
                            $('#WA').val(wa).trigger('change');
                        }
                        if (d.SN) {
                            const sn = JSON.parse(d.SN || '[]');
                            $('#SN').val(sn).trigger('change');
                        }
                    }, 2000);
                }

                $('#Objective_other').val(d.ObjectiveOther);
                $('#ProjectName').val(d.ProjectName);
                $('#JobNo').val(d.JobNo);
                $('#TCName').val(d.TCName).trigger('change.select2');
                $('#ProductName').val(d.ProductName);
                // $('#SalesName').val(d.SalesName).trigger('change.select2');
                $('#Department').val(d.Department).trigger('change.select2');
                $('#CompanyName').val(d.CompanyName);
                $('#CustomerName').val(d.CustomerName);
                $('#Position').val(d.Position);
                $('#PhoneNumber').val(d.PhoneNumber);
                $('#Detail').val(d.Detail);
                $('#Welcome').val(d.Welcome);
                $('#TimeUseShowroom').val(d.TimeUseShowroom);
                $('#CustomerNameThai').val(d.CustomerNameThai);
                $('#CustomerNameForeign').val(d.CustomerNameForeign);
                $('#CustomerTotal').val(d.CustomerTotal);
                $('#CustomerCar').val(d.CustomerCar);
                $('#CarNumber').val(d.CarNumber);
                $('#DriverNumber').val(d.DriverNumber);
                $('#Remark').val(d.Remark);
                $('#NumberDiners').val(d.NumberDiners);
                $('#OtherMenu').val(d.OtherMenu);
                $('#RemarkFood').val(d.RemarkFood);

                let objectiveArr = [];
                try {
                    objectiveArr = JSON.parse(d.Objective || '[]');
                } catch (e) {}
                objectiveArr.forEach(v => $(`[name='objective[]'][value='${v}']`).prop('checked', true));

                if (objectiveArr.includes("2") || objectiveArr.includes("3")) {
                    $("#nav-lecturer, #nav-lecturer-tab").removeClass("d-none");
                }
                if (objectiveArr.includes("other")) {
                    $("#box_objective_other").slideDown(300);
                    $("#objective_other").prop("checked", true);
                }

                // ====== Checkbox Arrays ======
                const arrays = ['WA', 'SN', 'showroom', 'LEDWall', 'travel', 'cusFood', 'food', 'presenttype', 'groupctm'];
                arrays.forEach(name => {
                    let arr = [];
                    try {
                        arr = JSON.parse(d[name.charAt(0).toUpperCase() + name.slice(1)] || '[]');
                    } catch (e) {}

                    if (name === 'cusFood') {
                        arr.forEach(v => $(`[name='${name}[]'][value='${v}']`).prop('checked', true));

                        const hasHaveLunch = arr.includes('HaveLunch');
                        const hasCoffeeBreak = arr.includes('CoffeeBreak');

                        if (hasHaveLunch || (hasHaveLunch && hasCoffeeBreak)) {
                            $('.food-choice').prop('disabled', false);
                        } else {
                            $('.food-choice').prop('disabled', true);
                        }
                        return;
                    }

                    // --- เงื่อนไขพิเศษของ food ---
                    if (name === 'food') {
                        const uniqueValues = [...new Set(arr)];
                        $('.food-choice').prop('checked', false);

                        let setidCount = {};
                        $('.food-choice').each(function() {
                            const setid = $(this).data('setid');
                            if (!setidCount[setid]) setidCount[setid] = 0;
                        });

                        arr.forEach(v => {
                            $(`.food-choice[value='${v}']`).each(function() {
                                const sid = $(this).data('setid');
                                setidCount[sid] = (setidCount[sid] || 0) + 1;
                            });
                        });

                        let maxSetId = null;
                        let maxCount = -1;
                        Object.entries(setidCount).forEach(([sid, count]) => {
                            const sidNum = parseInt(sid);
                            if (count > maxCount || (count === maxCount && sidNum > maxSetId)) {
                                maxSetId = sidNum;
                                maxCount = count;
                            }
                        });

                        if (maxSetId !== null) {
                            uniqueValues.forEach(v => {
                                $(`.food-choice[value='${v}'][data-setid='${maxSetId}']`).prop('checked', true);
                            });
                        }

                        const allChoices = $('.food-choice');
                        const checkedInThisSet = $(`.food-choice[data-setid="${maxSetId}"]:checked`);

                        if (checkedInThisSet.length > 0) {
                            const otherSets = [...new Set(allChoices.map((_, el) => $(el).data('setid')).get())]
                                .filter(id => id !== maxSetId);
                            otherSets.forEach(id => {
                                $(`.food-choice[data-setid="${id}"]`).prop('disabled', true);
                            });
                        } else {
                            allChoices.prop('disabled', false);
                        }

                        return;
                    }

                    arr.forEach(v => $(`[name='${name}[]'][value='${v}']`).prop('checked', true));
                });

                const lecturer = JSON.parse(d.Lecturer || '[]');
                $('#Lecturer').val(lecturer).trigger('change');

                // ====== Schedule ======
                if (d.Schedule && d.Schedule.length > 0) {
                    const tbody = $('#table-schedule tbody');
                    tbody.empty();

                    d.Schedule.forEach(s => {
                        let newRow = $(`
                            <tr>
                                <td>
                                    <select class="form-select reserve">
                                        <option value="meeting" ${s.ReserveType === 'meeting' ? 'selected' : ''}>จองห้องประชุม</option>
                                        <option value="zoom" ${s.ReserveType === 'zoom' ? 'selected' : ''}>จอง Zoom</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group" style="width:200px!important;">
                                        <span class="input-group-text">📅</span>
                                        <input class="form-control dateTrival" value="${s.VisitDate}" type="text">
                                    </div>
                                </td>
                                <td><input type="time" name="meeting_time_start[]" class="form-control" value="${s.TimeStart}"></td>
                                <td><input type="time" name="meeting_time_end[]" class="form-control" value="${s.TimeEnd}"></td>
                                <td>
                                    <select class="form-select meetingroom">
                                        <option value="">-- กำลังโหลดข้อมูลห้อง --</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeRow">ลบ</button>
                                </td>
                            </tr>
                        `);

                        tbody.append(newRow);

                        newRow.find('.dateTrival').datepicker({
                            dateFormat: 'dd/mm/yy',
                            appendTo: 'body',
                            minDate: 0
                        });

                        loadMeetingRoom(newRow, s.MeetingRoom);
                    });
                }

                await loadVisitorFiles(visitorId);
                await renderAckButtons(d);

                if (parseInt(d.Status) !== 0 && parseInt(d.Status) !== 1) {
                    $('input, select, textarea').prop('disabled', true);

                    $('.delete-btn, .removeRow, #addSchedule').addClass('d-none');

                    $('select.select2').each(function() {
                        $(this).prop('disabled', true);
                        $(this).select2();
                    });
                }

                // ปิด loading dialog หลังโหลดข้อมูลสำเร็จ
                Swal.close();

            } catch (err) {
                console.error('getVisitorData error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message || err
                });
            }
        }

        async function get_objective_list() {
            try {
                const res = await fetch('api/objective_action.php', {
                    cache: 'no-store'
                });
                const data = await res.json();

                const objectives = data?.data ?? [];
                if (!objectives.length) return;

                const list = objectives.map(item => `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                            id="objective_${item.visit_objective_id}"
                            name="objective[]" value="${item.visit_objective_id}">
                        <label class="form-check-label" for="objective_${item.visit_objective_id}">
                            ${item.visit_objective_name}
                        </label>
                    </div>`).join('');

                $('#List_objective').html(list + `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="objective_other" name="objective[]" value="other">
                        <label class="form-check-label" for="objective_other">อื่นๆ</label>
                    </div>
                `);

            } catch (err) {
                console.error('Error loading objectives:', err);
            }
        }

        async function get_groupctm_list() {
            try {
                const res = await fetch('api/group_customer_action.php', {
                    cache: 'no-store'
                });
                const data = await res.json();

                const groups = data?.data ?? [];
                if (!groups.length) return;

                const html = groups.map(item => `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                            id="groupctm_${item.visit_groupctm_id}"
                            name="groupctm[]" value="${item.visit_groupctm_id}">
                        <label class="form-check-label" for="groupctm_${item.visit_groupctm_id}">
                            ${item.visit_groupctm_name}
                        </label>
                    </div>`).join('');

                $('#List_groupctm').html(html);

            } catch (err) {
                console.error('Error loading group customer list:', err);
            }
        }

        async function loadVisitorFiles(visitorId) {
            try {
                const res = await fetch(`api/visitor_files_get.php?id=${visitorId}`);
                const data = await res.json();
                if (!data.status) return;

                const files = data.data;
                const detailList = $('#fileList_detail');
                const lecturerList = $('#fileList_lecturer');
                detailList.empty();
                lecturerList.empty();

                files.forEach(f => {
                    const container = f.FileType === 'detail' ? detailList : lecturerList;
                    const icon = getFileIcon(f.FileName);
                    const fileItem = `
                        <div class="file-item" data-fileid="${f.Id}">
                            <div class="file-info">
                                <div class="file-icon">${icon}</div>
                                <div class="file-details">
                                    <div class="file-name">${f.FileName}</div>
                                    <a href="file/${f.FilePath}" target="_blank" class="text-decoration-underline small text-primary">ดาวน์โหลด</a>
                                </div>
                            </div>
                            <button class="delete-btn" onclick="deleteUploadedFile(${f.Id}, this)">ลบ</button>
                        </div>`;
                    container.append(fileItem);
                });
            } catch (err) {
                console.error('loadVisitorFiles error:', err);
            }
        }

        function deleteUploadedFile(fileId, element) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'ไฟล์นี้จะถูกลบออกจากระบบถาวร',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then(result => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: 'api/visitor_file_delete.php',
                    type: 'POST',
                    data: {
                        id: fileId
                    },
                    success: res => {
                        const response = typeof res === 'string' ? JSON.parse(res) : res;
                        if (response.status) {
                            $(element).closest('.file-item').remove();
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ',
                                text: 'ลบไฟล์แล้ว'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'ผิดพลาด',
                                text: response.message
                            });
                        }
                    },
                    error: err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: 'ไม่สามารถเชื่อมต่อ API ได้'
                        });
                    }
                });
            });
        }

        function update_visitor_form(visitorId, status) {
            if (typeof status === 'undefined' || status === null) {
                status = $('#status').val() || 0;
            }
            let formData = new FormData();

            // --- ข้อมูลพื้นฐาน ---
            formData.append("Id", visitorId);
            formData.append("Status", status);
            formData.append("ObjectiveOther", $('#Objective_other').val());
            formData.append("ProjectName", $('#ProjectName').val());
            formData.append("JobNo", $('#JobNo').val());
            formData.append("TCName", $('#TCName').val());
            formData.append("ProductName", $('#ProductName').val());
            formData.append("SalesName", $('#SalesName').val());
            formData.append("Department", $('#Department').val());
            formData.append("CompanyName", $('#CompanyName').val());
            formData.append("CustomerName", $('#CustomerName').val());
            formData.append("Position", $('#Position').val());
            formData.append("PhoneNumber", $('#PhoneNumber').val());
            formData.append("Detail", $('#Detail').val());
            formData.append("CustomerNameThai", $('#CustomerNameThai').val());
            formData.append("CustomerNameForeign", $('#CustomerNameForeign').val());
            formData.append("CustomerTotal", $('#CustomerTotal').val());
            formData.append("CustomerCar", $('#CustomerCar').val());
            formData.append("CarNumber", $('#CarNumber').val());
            formData.append("DriverNumber", $('#DriverNumber').val());
            formData.append("Remark", $('#Remark').val());
            formData.append("NumberDiners", $('#NumberDiners').val());
            formData.append("OtherMenu", $('#OtherMenu').val());
            formData.append("RemarkFood", $('#RemarkFood').val());
            formData.append("Welcome", $('#Welcome').val());
            formData.append("TimeUseShowroom", $('#TimeUseShowroom').val());

            // --- Array / Checkbox ---
            ['objective', 'WA', 'SN', 'showroom', 'LEDWall', 'travel', 'cusFood', 'food', 'Lecturer', 'presenttype', 'groupctm'].forEach(name => {
                var nameCapitalized = name.charAt(0).toUpperCase() + name.slice(1);
                if (nameCapitalized === 'Groupctm') nameCapitalized = 'GroupCtm';
                if (nameCapitalized === 'Presenttype') nameCapitalized = 'PresentType';
                $(`[name='${name}[]']:checked, [name='${name}[]'] option:selected`).each(function() {
                    const val = $(this).val();
                    formData.append(`${nameCapitalized}[]`, val);
                });
            });

            // --- Schedule ---
            let schedule = [];
            $('#table-schedule tbody tr').each(function() {
                schedule.push({
                    reserve: $(this).find('.reserve').val(),
                    date: $(this).find('.dateTrival').val(),
                    time_start: $(this).find('input[name="meeting_time_start[]"]').val(),
                    time_end: $(this).find('input[name="meeting_time_end[]"]').val(),
                    room: $(this).find('.meetingroom').val(),
                    roomname: $(this).find('.meetingroom option:selected').text()
                });
            });
            formData.append("schedule", JSON.stringify(schedule));

            // --- ไฟล์ใหม่ที่จะอัปโหลด ---
            uploadBoxes.detail.forEach(f => formData.append("files_detail[]", f));
            uploadBoxes.lecturer.forEach(f => formData.append("files_lecturer[]", f));

            Swal.fire({
                title: 'ยืนยันการอัปเดตข้อมูล?',
                text: 'คุณต้องการอัปเดตข้อมูลฟอร์มนี้หรือไม่',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'อัปเดต',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'api/visitor_form_update.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            Swal.fire({
                                title: 'กำลังส่งข้อมูล...',
                                text: 'โปรดรอสักครู่',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: res => {
                            Swal.close();
                            let response = typeof res === "string" ? JSON.parse(res) : res;
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ',
                                    text: response.message
                                });
                                uploadBoxes.detail = [];
                                uploadBoxes.lecturer = [];
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: response.message
                                });
                            }
                        },
                        error: err => {
                            Swal.close();
                            console.log(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'ผิดพลาด',
                                text: 'ไม่สามารถเชื่อมต่อ API ได้'
                            });
                        }
                    });
                }
            });
        }

        function loadMeetingRoom(row, preselectedRoom = null) {
            let reserve = row.find('.reserve').val();
            let dateTrival = row.find('.dateTrival').val();
            let timeStart = row.find('input[name="meeting_time_start[]"]').val();
            let timeEnd = row.find('input[name="meeting_time_end[]"]').val();

            if (reserve && dateTrival && timeStart && timeEnd) {
                let apiUrl = (reserve === 'meeting') ?
                    'https://innovation.asefa.co.th/zoompoo/check_room.php' :
                    './api/check_zoom.php';

                $.ajax({
                    url: apiUrl,
                    type: 'POST',
                    data: {
                        booking_date: formatDateToYMD(dateTrival),
                        time_start: timeStart,
                        time_end: timeEnd
                    },
                    dataType: 'json',
                    success: function(response) {
                        let select = row.find('.meetingroom');
                        select.empty();
                        select.append('<option value="">-- เลือกห้อง --</option>');

                        if (response.data) {
                            for (let item of Object.values(response.data)) {
                                let selected = (item.id_pk == preselectedRoom) ? 'selected' : '';
                                select.append(`<option value="${item.id_pk}" ${selected}>${item.room_name}</option>`);
                            }
                        }

                        if (select[0].tomselect) {
                            select[0].tomselect.destroy();
                        }
                        // new TomSelect(select[0], {
                        //     allowEmptyOption: true,
                        // });
                    },
                    error: function(xhr, status, error) {
                        console.error("API error:", error);
                    }
                });
            }
        }

        function formatDateToYMD(dateStr) {
            let parts = dateStr.split('/');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return dateStr;
        }

        // helper: เช็คสิทธิ์ฝั่งหน้าเว็บ
        function hasPerm(target) {
            const sess = window.__VISITOR_SESSION__ || {};
            if (sess.isAdmin) return true;
            const p = sess.perm || [];
            if (typeof p === 'object' && !Array.isArray(p)) {
                const vals = Object.values(p).map(v => v.toString());
                return vals.includes(String(target));
            }
            if (Array.isArray(p)) {
                return p.includes(target) || p.includes(String(target)) || p.includes(parseInt(target));
            }
            return (p == target) || (String(p) === String(target));
        }

        function show(el, yes) {
            $(el).toggleClass('d-none', !yes);
        }

        function roleFromPerm(ack = null) {
            const sess = window.__VISITOR_SESSION__ || {};
            const isAdmin = sess.isAdmin === true;

            // --- สำหรับ admin: ไล่ role ตามลำดับ
            if (isAdmin) {
                const roles = ['sell', 'Communication', 'transport', 'hr'];
                if (ack) {
                    for (let r of roles) {
                        if (!ack[r] || ack[r] == 0) {
                            return r;
                        }
                    }
                    return null;
                }
                return roles[0];
            }

            // --- สำหรับผู้ใช้ทั่วไป
            if (hasPerm(61)) return 'sell';
            if (hasPerm(62)) return 'Communication';
            if (hasPerm(65)) return 'transport';
            if (hasPerm(31)) return 'hr';
            return null;
        }

        async function renderAckButtons(d) {
            const sess = window.__VISITOR_SESSION__ || {};
            const isAdmin = sess.isAdmin === true;
            const userCode = sess.code;
            const canMK = isAdmin || hasPerm(63);
            let ack = null;

            try {
                const res = await fetch(`api/visitor_ack_action.php?action=get_state&id=${encodeURIComponent(d.Id)}`, {
                    cache: 'no-store'
                });
                const js = await res.json();
                if (js.status) ack = js.data;
            } catch (e) {
                console.error('get_state error', e);
            }

            const role = roleFromPerm(ack);

            const roleNames = {
                sell: 'ฝ่ายขาย',
                Communication: 'ฝ่ายสื่อสาร',
                transport: 'ฝ่ายขนส่ง',
                hr: 'ฝ่าย HR',
                lecturer: 'วิทยากร'
            };

            show('#btn-mk-accept', (parseInt(d.Status) === 1) && canMK);

            let showDeptAck = (parseInt(d.Status) === 2) && (isAdmin || !!role);
            if (showDeptAck && ack && role && ack[role] == 1) {
                showDeptAck = false;
            }

            if (showDeptAck && role) {
                const label = roleNames[role] ? `รับทราบ (${roleNames[role]})` : 'รับทราบ (ฝ่าย)';
                $('#btn-dept-ack').html(`<i class="ti ti-check me-1"></i>${label}`);
            }
            if (!role) showDeptAck = false;
            show('#btn-dept-ack', showDeptAck);

            let showLecturerAck = false;
            let lecturerArr = [];
            let objectiveArr = [];

            try {
                if (typeof d.Lecturer === 'string') lecturerArr = JSON.parse(d.Lecturer);
                else if (Array.isArray(d.Lecturer)) lecturerArr = d.Lecturer;
            } catch (e) {
                console.warn('Lecturer parse error:', e);
            }

            try {
                if (typeof d.Objective === 'string') objectiveArr = JSON.parse(d.Objective);
                else if (Array.isArray(d.Objective)) objectiveArr = d.Objective;
            } catch (e) {
                console.warn('Objective parse error:', e);
            }

            const hasLecturerObjective = objectiveArr.includes("2") || objectiveArr.includes("3");

            if (parseInt(d.Status) === 2 && hasLecturerObjective && Array.isArray(lecturerArr)) {
                if (lecturerArr.includes(userCode)) {
                    showLecturerAck = true;
                    if (ack && ack.lecturer == 1) {
                        showLecturerAck = false;
                    }
                }
            }
            show('#btn-lecturer-ack', showLecturerAck);

            let canClose = (parseInt(d.Status) === 2) && canMK;
            if (canClose && ack) {
                if (hasLecturerObjective) {
                    // ถ้ามี objective 2 หรือ 3
                    // ต้องให้ ack.lecturer == 1 ถึงจะปิดได้
                    const all = (ack.sell | 0) && (ack.Communication | 0) && (ack.transport | 0) && (ack.hr | 0);
                    canClose = !!(all && ack.lecturer == 1);
                } else {
                    // ถ้าไม่มี objective 2 หรือ 3 → ข้ามการเช็ก lecturer
                    const all = (ack.sell | 0) && (ack.Communication | 0) && (ack.transport | 0) && (ack.hr | 0);
                    canClose = !!all;
                }
            }
            show('#btn-close-job', canClose);

            let showFat = false;
            const hasQC = Object.values(sess.perm || {}).includes("QC");
            if ((hasQC && parseInt(d.Status) === 2 && ack && parseInt(ack.qc) === 0) || isAdmin) {
                showFat = true;
            }
            show('#btn-fat', showFat);

            let qz = (parseInt(d.Status) === 3) && (canMK || isAdmin);
            show('#btn-qzip', qz);
        }

        $(document).on('click', '#btn-mk-accept', async function() {
            const id = new URLSearchParams(window.location.search).get('id');
            Swal.fire({
                title: 'ยืนยันรับงาน',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then(async r => {
                if (!r.isConfirmed) return;
                const fd = new FormData();
                fd.append('action', 'mk_accept');
                fd.append('id', id);
                const res = await fetch('api/visitor_ack_action.php', {
                    method: 'POST',
                    body: fd
                });
                const js = await res.json();
                if (js.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: js.message
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด',
                        text: js.message
                    });
                }
            });
        });

        $(document).on('click', '#btn-dept-ack', async function() {
            const id = new URLSearchParams(window.location.search).get('id');
            const sess = window.__VISITOR_SESSION__ || {};
            const isAdmin = sess.isAdmin === true;

            let ack = null;
            try {
                const resAck = await fetch(`api/visitor_ack_action.php?action=get_state&id=${encodeURIComponent(id)}`, {
                    cache: 'no-store'
                });
                const jsAck = await resAck.json();
                if (jsAck.status) ack = jsAck.data;
            } catch (e) {
                console.error('load ack error:', e);
            }

            const role = roleFromPerm(ack);

            const roleNames = {
                sell: 'ฝ่ายขาย',
                Communication: 'ฝ่ายสื่อสาร',
                transport: 'ฝ่ายขนส่ง',
                hr: 'ฝ่าย HR'
            };

            const label = roleNames[role] ? `รับทราบ (${roleNames[role]})` : 'รับทราบ (ฝ่าย)';

            Swal.fire({
                title: `ยืนยัน${label}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then(async r => {
                if (!r.isConfirmed) return;

                const fd = new FormData();
                fd.append('action', 'dept_ack');
                fd.append('id', id);

                if (isAdmin && role) fd.append('role', role);

                try {
                    const res = await fetch('api/visitor_ack_action.php', {
                        method: 'POST',
                        body: fd
                    });
                    const js = await res.json();

                    if (js.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: js.message
                        });

                        renderTimeline(id);

                        $('#btn-dept-ack').fadeOut(300, async () => {
                            let newAck = null;
                            try {
                                const res2 = await fetch(`api/visitor_ack_action.php?action=get_state&id=${encodeURIComponent(id)}`, {
                                    cache: 'no-store'
                                });
                                const js2 = await res2.json();
                                if (js2.status) newAck = js2.data;
                            } catch (e) {
                                console.error('reload ack error:', e);
                            }
                            const nextRole = roleFromPerm(newAck);
                            if (!nextRole) {
                                $('#btn-dept-ack').addClass('d-none');
                            } else {
                                const nextLabel = roleNames[nextRole] ? `รับทราบ (${roleNames[nextRole]})` : 'รับทราบ (ฝ่าย)';
                                $('#btn-dept-ack').html(`<i class="ti ti-check me-1"></i>${nextLabel}`).fadeIn(300);
                            }

                            if (js.data?.all_done) {
                                $('#btn-close-job').removeClass('d-none');
                            }
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: js.message
                        });
                    }

                } catch (e) {
                    console.error(e);
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด',
                        text: 'ไม่สามารถเชื่อมต่อ API ได้'
                    });
                }
            });
        });

        $(document).on('click', '#btn-lecturer-ack', async function() {
            const id = new URLSearchParams(window.location.search).get('id');
            Swal.fire({
                title: 'ยืนยันการรับทราบ (วิทยากร)',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then(async r => {
                if (!r.isConfirmed) return;
                const fd = new FormData();
                fd.append('action', 'dept_ack');
                fd.append('id', id);
                fd.append('role', 'lecturer');

                const res = await fetch('api/visitor_ack_action.php', {
                    method: 'POST',
                    body: fd
                });
                const js = await res.json();
                if (js.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: js.message
                    });
                    renderTimeline(id);
                    $('#btn-lecturer-ack').addClass('d-none');
                    if (js.data?.all_done) {
                        $('#btn-close-job').removeClass('d-none');
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด',
                        text: js.message
                    });
                }
            });
        });

        $(document).on('click', '#btn-close-job', async function() {
            const id = new URLSearchParams(window.location.search).get('id');
            Swal.fire({
                title: 'ยืนยันปิดงาน',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then(async r => {
                if (!r.isConfirmed) return;
                const fd = new FormData();
                fd.append('action', 'close_job');
                fd.append('id', id);
                const res = await fetch('api/visitor_ack_action.php', {
                    method: 'POST',
                    body: fd
                });
                const js = await res.json();
                if (js.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ปิดงานสำเร็จ',
                        text: js.message
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ปิดงานไม่ได้',
                        text: js.message
                    });
                }
            });
        });

        $(document).on('click', '#btn-cancel-job', async function() {
            const id = new URLSearchParams(window.location.search).get('id');
            Swal.fire({
                title: 'ยืนยันยกเลิกงาน',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then(async r => {
                if (!r.isConfirmed) return;
                const fd = new FormData();
                fd.append('action', 'cancel_job');
                fd.append('id', id);
                const res = await fetch('api/visitor_ack_action.php', {
                    method: 'POST',
                    body: fd
                });
                const js = await res.json();
                if (js.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ยกเลิกงานสำเร็จ',
                        text: js.message
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ยกเลิกงานไม่ได้',
                        text: js.message
                    });
                }
            });
        });

        $(document).on('click', '#btn-fat', async function() {
            const id = new URLSearchParams(window.location.search).get('id');
            const fd_fat = new FormData();
            fd_fat.append('projectfatname', $('#ProjectName').val());
            fd_fat.append('owner', $('#CompanyName').val());
            fd_fat.append('owner_contact', $('#owner_contact').val());
            fd_fat.append('jobno', $('#JobNo').val());
            fd_fat.append('salename', $('#salename_text').val());
            fd_fat.append('jobvalue', $('#jobvalue').val());
            let snValues = $('#SN').val();
            let snTexts = $('#SN option:selected').map(function() {
                return $(this).text();
            }).get().join(', ');
            fd_fat.append('SN_no', snTexts);
            fd_fat.append('WA', $('#WA').val().join(', '));
            // fd_fat.append('fattest_date', $('input[name="dateTrival[]"]').eq(0).val());
            fd_fat.append('fat_remark', $('#Detail').val());
            fd_fat.append('fat_usersend', $('#TCName').val() + ', ' + $('#SalesName').val());

            Swal.fire({
                title: 'ยืนยันรับงาน FAT',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then(async r => {
                if (!r.isConfirmed) return;
                const res_fat = await fetch('api/fat_api_insert.php', {
                    method: 'POST',
                    body: fd_fat
                });
                const json_fat = await res_fat.json();
                if (json_fat.status) {
                    const fd = new FormData();
                    fd.append('action', 'fat_job');
                    fd.append('id', id);
                    const res = await fetch('api/visitor_ack_action.php', {
                        method: 'POST',
                        body: fd
                    });
                    const js = await res.json();
                    if (js.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'รับงาน FAT สำเร็จ',
                            text: js.message
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'รับงาน FAT ไม่ได้',
                            text: js.message
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'รับงาน FAT ไม่ได้',
                        text: json_fat.message
                    });
                }
            });
        });

        $('#btn-qzip').on('click', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const visitorId = urlParams.get('id');
            const link = "./visi_question.php?id=" + visitorId;

            const $temp = $("<input>");
            $("body").append($temp);
            $temp.val(link).select();
            document.execCommand("copy");
            $temp.remove();

            Swal.fire({
                icon: 'success',
                title: 'คัดลอกแบบประเมินสำเร็จ',
                showConfirmButton: false,
                timer: 1500
            });
        });
    </script>

</body>

</html>