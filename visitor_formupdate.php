<?php
include_once 'config/base.php';
$perm = $_SESSION['VisitorMKT_permision'] ?? [];
$code = $_SESSION['VisitorMKT_code'] ?? '';
$divisionHead1 = $_SESSION['DivisionHead1'] ?? 0;
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
        .nav-tabs {
            border: none;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 10px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 6px;
            color: #666;
            padding: 8px 16px;
            margin: 0 2px;
            transition: all 0.2s ease;
            font-size: 13px;
            font-weight: 500;
        }

        .nav-tabs .nav-link:hover {
            background: rgba(0, 122, 255, 0.1);
            color: #007aff;
        }

        .nav-tabs .nav-link#nav-profile-tab:hover {
            background: #ffd7ebff;
            color: #C8377C;
        }

        .nav-tabs .nav-link#nav-travel-tab:hover {
            background: #fff7dfff;
            color: #FEC107;
        }

        .nav-tabs .nav-link#nav-hr-tab:hover {
            background: #e4fdffff;
            color: #3AA9B0;
        }

        .nav-tabs .nav-link#nav-lecturer-tab:hover {
            background: #e8e6ffff;
            color: #9387ffff;
        }

        .nav-tabs .nav-link.active {
            background: #007aff;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 122, 255, 0.3);
        }

        .nav-tabs .nav-link.active#nav-profile-tab {
            background: #C8377C;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 122, 255, 0.3);
        }

        .nav-tabs .nav-link.active#nav-travel-tab {
            background: #FEC107;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 122, 255, 0.3);
        }

        .nav-tabs .nav-link.active#nav-hr-tab {
            background: #3AA9B0;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 122, 255, 0.3);
        }

        .nav-tabs .nav-link.active#nav-lecturer-tab {
            background: #B8B0FF;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 122, 255, 0.3);
        }

        .nav-tabs .nav-link#nav-prd-tab:hover {
            background: #d1fae5;
            color: #10B981;
        }

        .nav-tabs .nav-link.active#nav-prd-tab {
            background: #10B981;
            color: white;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
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



        /* =====================================================
           JOB CARD COMPONENT - Multi-Job Support
           ===================================================== */

        /* Container */
        #job-cards-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Job Card */
        .job-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            animation: slideIn 0.3s ease-out;
        }

        .job-card:hover {
            border-color: #007aff;
            box-shadow: 0 4px 12px rgba(0, 122, 255, 0.1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        .job-card.removing {
            animation: slideOut 0.3s ease-out forwards;
        }

        /* Job Card Header */
        .job-card-header {
            background: linear-gradient(135deg, #007aff 0%, #0056b3 100%);
            color: white;
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
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
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

        /* Job Card Body */
        .job-card-body {
            padding: 20px;
        }

        /* Form Grid Layout */
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

        @media (min-width: 992px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-grid .full-width {
                grid-column: 1 / -1;
            }
        }



        .form-group-clean .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Checkbox Group */
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .checkbox-group .form-check {
            background: #f9fafb;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .checkbox-group .form-check:has(input:checked) {
            background: rgba(0, 122, 255, 0.1);
            border-color: #007aff;
        }

        /* Add Job Button */
        .btn-add-job {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px 20px;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            background: transparent;
            color: #6b7280;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-add-job:hover {
            border-color: #007aff;
            background: rgba(0, 122, 255, 0.05);
            color: #007aff;
        }

        .btn-add-job i {
            font-size: 18px;
        }

        /* Section Divider */
        .section-divider {
            position: relative;
            text-align: center;
            margin: 24px 0;
        }

        .section-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }

        .section-divider span {
            position: relative;
            background: white;
            padding: 0 16px;
            color: #9ca3af;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Objective Section */
        .objective-section {
            background: #f9fafb;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .objective-section label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Mobile optimizations */
        @media (max-width: 576px) {
            .job-card-body {
                padding: 16px;
            }

            .form-group-clean label {
                font-size: 12px;
            }

            .form-group-clean .form-control {
                padding: 8px 12px;
                font-size: 13px;
            }

            .btn-add-job {
                padding: 12px 16px;
                font-size: 13px;
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

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-md-0">
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
                        <li class="breadcrumb-item active" aria-current="page">Visitor Form</li>
                    </ol>
                </nav>
                <h2 class="h4">Add Document</h2>
            </div>


        </div>

        <!-- Comment History Alert (สำหรับ Status 4, 9) -->
        <div id="comment-history" class="alert alert-warning border-start border-4 border-warning shadow-sm" style="display: none;">
            <div class="d-flex align-items-center mb-2">
                <i class="ti ti-message-circle fs-4 me-2"></i>
                <h6 class="mb-0 fw-bold">หมายเหตุจากผู้ตรวจสอบ</h6>
            </div>
            <div id="comment-content"></div>
        </div>

        <!-- Acknowledge Status Card (รวม Action Bar ไว้ที่เดียว) -->
        <div id="acknowledge-status" class="card mb-3 shadow-sm border-0" style="display: none;">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #007aff 0%, #0056b3 100%);">
                <div class="d-flex align-items-center text-white">
                    <i class="ti ti-checkbox fs-4 me-2"></i>
                    <h6 class="mb-0 fw-bold">สถานะรับทราบจากฝ่ายที่เกี่ยวข้อง</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-2" id="ack-divisions">
                    <!-- Render by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Action Bar (แยกออกมาจาก acknowledge card) -->
        <div id="action-bar-container" class="action-bar mb-3 d-flex flex-wrap align-items-center gap-2"></div>

        <div>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">ฝ่ายขาย</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">ฝ่ายสื่อสาร</button>
                    <button class="nav-link" id="nav-travel-tab" data-bs-toggle="tab" data-bs-target="#nav-travel" type="button" role="tab" aria-controls="nav-travel" aria-selected="false">ฝ่ายขนส่ง</button>
                    <button class="nav-link" id="nav-hr-tab" data-bs-toggle="tab" data-bs-target="#nav-hr" type="button" role="tab" aria-controls="nav-hr" aria-selected="false">ฝ่าย HR</button>
                    <button class="nav-link" id="nav-lecturer-tab" data-bs-toggle="tab" data-bs-target="#nav-lecturer" type="button" role="tab" aria-controls="nav-lecturer" aria-selected="false">วิทยากร</button>
                    <button class="nav-link" id="nav-prd-tab" data-bs-toggle="tab" data-bs-target="#nav-prd" type="button" role="tab" aria-controls="nav-prd" aria-selected="false" style="display:none;">วางแผน</button>
                </div>
            </nav>
            <div class="tab-content mb-4" id="nav-tabContent">
                <!-- Tab Home -->
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                    <div class="card border border-2 table-wrapper table-responsive">
                        <h6 class="card-header py-3" style="background-color: #007aff; color: white;">ข้อมูลทั่วไป</h6>
                        <div class="card-body">

                            <div class="form-grid">
                                            <!-- Row 0: Objective (Moved inside Job Card) -->
                                            <div class="form-group-clean full-width checkbox-group">
                                                <label><i class="ti ti-message-user"></i>วัตถุประสงค์การเยี่ยมชม</label>
                                                <select class="form-select job-field job-select-objective" data-field="Objective" multiple></select>
                                            </div>
                                            <div class="form-group-clean full-width box-objective-other" style="display: none;">
                                                <label></label>
                                                <input type="text" class="form-control job-field" data-field="ObjectiveOther" placeholder="ระบุวัตถุประสงค์อื่นๆ">
                                            </div>

                                            <!-- Row 1: Job No & Project Name -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-dialpad"></i>JOB No.</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control job-field" data-field="JobNo" placeholder="กรอกเลข Job">
                                                    <button class="btn btn-outline-primary btn-search-job" type="button">
                                                        <i class="ti ti-search fs-5"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-sitemap"></i>ชื่อโครงการ</label>
                                                <input type="text" class="form-control job-field" data-field="ProjectName" placeholder="ชื่อโครงการ">
                                            </div>

                                            <!-- Row 2: SN & WA -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-barcode"></i>Switchboard/Serial</label>
                                                <select class="form-select job-field job-select-sn selectable" data-field="SN" multiple></select>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-hash"></i>WA No.</label>
                                                <select class="form-select job-field job-select-wa selectable" data-field="WA" multiple></select>
                                            </div>

                                            <!-- Row 3: TC & Product -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-user-cog"></i>TC ผู้ดูแล</label>
                                                <select class="form-select job-field job-select-tc" data-field="TCName">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-archive"></i>ชื่อผลิตภัณฑ์</label>
                                                <select class="form-select job-field job-select-product" data-field="ProductName" multiple>
                                                    <option value=""></option>
                                                    <option value="AMS">AMS</option>
                                                    <option value="MV">MV</option>
                                                    <option value="PRISMA">PRISMA</option>
                                                    <option value="BS">BS</option>
                                                </select>
                                            </div>

                                            <!-- Row 4: Requester & Department -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-user-dollar"></i>ผู้ร้องขอ</label>
                                                <select class="form-select job-field job-select-requester" data-field="RequesterCode">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-vector-bezier"></i>แผนก</label>
                                                <input type="text" class="form-control job-field" data-field="RequesterDept" placeholder="แผนก (อัตโนมัติ)" readonly>
                                            </div>

                                            <!-- Row 5: Company & Customer Group -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-building-skyscraper"></i>ชื่อบริษัท/หน่วยงาน</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control job-field" data-field="CompanyName" placeholder="ชื่อบริษัท" id="companyNameInput">
                                                    <button class="btn btn-outline-primary btn-search-company" type="button">
                                                        <i class="ti ti-search fs-5"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-users-group"></i>กลุ่มลูกค้า</label>
                                                <select class="form-select job-field job-select-groupctm" data-field="GroupCtm" multiple></select>
                                            </div>

                                            <!-- Row 6: Customer Name & Position -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-user-bitcoin"></i>ชื่อลูกค้า (ผู้ประสานงาน)</label>
                                                <select class="form-select job-field job-select-contact" data-field="CustomerName" multiple>
                                                </select>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-reorder"></i>ตำแหน่ง</label>
                                                <select class="form-select job-field job-select-position" data-field="Position" multiple>
                                                </select>
                                            </div>

                                            <!-- Row 7: Phone & Email -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-phone"></i>เบอร์ติดต่อ</label>
                                                <select class="form-select job-field job-select-phone" data-field="PhoneNumber" multiple>
                                                </select>
                                            </div>
                                            <div class="form-group-clean d-none">
                                                <label><i class="ti ti-mail"></i>Email</label>
                                                <select class="form-select job-field job-select-email" data-field="Emails" multiple>
                                                </select>
                                            </div>

                                            <!-- Row 7.5: Zone -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-map-pin"></i>พื้นที่/โซน</label>
                                                <select class="form-select job-field job-select-zone" data-field="Zone" multiple>
                                                    <option value="zone-1">โซน 1</option>
                                                    <option value="zone-2">โซน 2</option>
                                                    <option value="zone-3">โซน 3</option>
                                                    <option value="zone-4">โซน 4</option>
                                                    <option value="zone-5">โซน 5</option>
                                                </select>
                                            </div>

                                            <!-- Row 8: Company Type (half width) -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-flag"></i>ประเภทบริษัท</label>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input job-company-type" type="radio" name="companyType_job0" value="thai" id="companyType_thai_job0" checked>
                                                        <label class="form-check-label" for="companyType_thai_job0">บริษัทไทย</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input job-company-type" type="radio" name="companyType_job0" value="foreign" id="companyType_foreign_job0">
                                                        <label class="form-check-label" for="companyType_foreign_job0">บริษัทต่างชาติ</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div></div>

                                            <!-- Row 9: Detail (full width) -->
                                            <div class="form-group-clean full-width">
                                                <label><i class="ti ti-alert-circle"></i>ระบุรายละเอียด</label>
                                                <textarea class="form-control job-field" data-field="Detail" rows="3" placeholder="รายละเอียดเพิ่มเติม..."></textarea>
                                            </div>
                                        </div>

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
                            <!-- Option 1: ไม่ขอใช้บริการ -->
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

                            <!-- Option 2: ขอใช้บริการ Showroom (ซ่อนเริ่มต้น) -->
                            <div id="corporateServicePanel" class="border rounded p-3 mb-3" style="display: none; background: #fafafa;">

                                <!-- 2.1 เลือกวันที่ช่วงเวลา -->
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label fw-semibold">
                                        <i class="ti ti-calendar fs-5 me-1"></i>วันที่
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control corporate-date" id="corporateDate" placeholder="dd/mm/yyyy">
                                    </div>
                                </div>

                                <!-- 2.2 ขอใช้บริการถ่ายรูป -->
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
                                    <div class="row mb-2">
                                        <label class="col-sm-2 col-form-label">สถานที่ถ่ายรูป</label>
                                        <div class="col-sm-10 d-flex flex-wrap gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="photoLocShowroom" value="showroom">
                                                <label class="form-check-label" for="photoLocShowroom">Showroom</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="photoLocMP3" value="MP3">
                                                <label class="form-check-label" for="photoLocMP3">MP3</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="photoLocMP4" value="MP4">
                                                <label class="form-check-label" for="photoLocMP4">MP4</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="photoLocOther" value="other">
                                                <label class="form-check-label" for="photoLocOther">อื่นๆ</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2" id="photoLocOtherInput" style="display: none;">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="photoLocOtherText" placeholder="ระบุสถานที่อื่นๆ">
                                        </div>
                                    </div>
                                    <small class="text-warning"><i class="ti ti-info-circle me-1"></i>หมายเหตุ: กรุณาติดต่อโทร 3CX 6355 เมื่อถึงช่วงเวลาถ่าย</small>
                                </div>

                                <!-- 2.3 Welcome -->
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
                                        <label class="col-sm-2 col-form-label">ช่วงเวลา</label>
                                        <div class="col-sm-3">
                                            <input type="time" class="form-control" id="welcomeTimeStart">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="time" class="form-control" id="welcomeTimeEnd">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label class="col-sm-2 col-form-label">รายละเอียด</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="welcomeDetail" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <small class="text-muted mb-2"><i class="ti ti-info-circle me-1"></i>หมายเหตุ: ระบุชื่อบริษัท / ชื่อ-นามสกุลลูกค้า / ตำแหน่ง / อื่นๆ โปรดระบุ</small>

                                    <div class="mt-3">
                                        <label class="col-form-label fw-semibold">เลือกหน้าจอแสดงผล:</label>

                                        <!-- MP1 -->
                                        <div class="border rounded p-2 mb-2">
                                            <label class="fw-semibold d-block mb-2">MP1</label>
                                            <div class="ms-3 d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP1_TV" value="TV MP1">
                                                    <label class="form-check-label" for="welcomeMP1_TV">TV MP1</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- MP2 -->
                                        <div class="border rounded p-2 mb-2">
                                            <label class="fw-semibold d-block mb-2">MP2</label>
                                            <div class="ms-3 d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP2_Food" value="TV ห้องอาหาร">
                                                    <label class="form-check-label" for="welcomeMP2_Food">TV ห้องอาหาร</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP2_VIP1" value="TV VIP1">
                                                    <label class="form-check-label" for="welcomeMP2_VIP1">TV VIP1</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP2_VIP2" value="TV VIP2">
                                                    <label class="form-check-label" for="welcomeMP2_VIP2">TV VIP2</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- MP3 -->
                                        <div class="border rounded p-2 mb-2">
                                            <label class="fw-semibold d-block mb-2">MP3</label>
                                            <div class="ms-3 d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP3_Sofa" value="TV บริเวณโซฟา">
                                                    <label class="form-check-label" for="welcomeMP3_Sofa">TV บริเวณโซฟา</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP3_Room1" value="TV Room1">
                                                    <label class="form-check-label" for="welcomeMP3_Room1">TV Room1</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP3_Room2" value="TV Room2">
                                                    <label class="form-check-label" for="welcomeMP3_Room2">TV Room2</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP3_Room3" value="TV Room3">
                                                    <label class="form-check-label" for="welcomeMP3_Room3">TV Room3</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP3_LED" value="LED Wall">
                                                    <label class="form-check-label" for="welcomeMP3_LED">LED Wall</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- MP4 -->
                                        <div class="border rounded p-2 mb-2">
                                            <label class="fw-semibold d-block mb-2">MP4</label>
                                            <div class="ms-3 d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="welcomeMP4_LED" value="LED Wall">
                                                    <label class="form-check-label" for="welcomeMP4_LED">LED Wall</label>
                                                </div>
                                            </div>
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
                                <label class="col-sm-2 col-form-label">ทะเบียนรถ(ถ้ามากกว่า 1 คันใส่คั่นด้วย ,)</label>
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
                                        <input class="form-check-input" type="checkbox" id="food_1" name="CusFood[]" value="CoffeeBreak">
                                        <label class="form-check-label" for="food_1">เครื่องดื่มและของว่าง (Coffee Break)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="food_2" name="CusFood[]" value="HaveLunch">
                                        <label class="form-check-label" for="food_2">อาหารกลางวัน (ภายในบริษัท)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="food_3" name="CusFood[]" value="EatOutside" checked>
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

                <!-- Tab Lecturer (วิทยากร) -->
                <div class="tab-pane fade" id="nav-lecturer" role="tabpanel" aria-labelledby="nav-lecturer-tab" tabindex="0">
                    <div class="card border border-2 table-wrapper table-responsive">
                        <h6 class="card-header py-3" style="background-color: #B8B0FF; color: white;">การขอวิทยากร</h6>
                        <div class="card-body">
                            <!-- เลือกประเภทวิทยากร -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="col-form-label fw-semibold mb-2"><i class="ti ti-users fs-5 me-2"></i>ประเภทวิทยากร</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input lecturer-type" type="radio" name="lecturerType" id="lecturerInternal" value="internal">
                                            <label class="form-check-label" for="lecturerInternal">วิทยากรภายใน</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input lecturer-type" type="radio" name="lecturerType" id="lecturerExternal" value="external">
                                            <label class="form-check-label" for="lecturerExternal">วิทยากรภายนอก</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel สำหรับวิทยากรภายใน -->
                            <div id="internalLecturerPanel" class="border rounded p-3 mb-3" style="display: none; background: #fafafa;">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"><i class="ti ti-user-check fs-5 me-2"></i>เลือกวิทยากร</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" name="Lecturer[]" id="Lecturer" multiple>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 col-form-label text-nowrap">ประเภทการนำเสนอ</label>
                                    <div class="col-sm-4 d-flex flex-wrap align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="presentThai" name="presenttype[]" value="thai">
                                            <label class="form-check-label" for="presentThai">ไทย</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="presentEng" name="presenttype[]" value="eng">
                                            <label class="form-check-label" for="presentEng">อังกฤษ</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="presentChinese" name="presenttype[]" value="chinese">
                                            <label class="form-check-label" for="presentChinese">จีน</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel สำหรับวิทยากรภายนอก -->
                            <div id="externalLecturerPanel" class="border rounded p-3 mb-3" style="display: none; background: #fafafa;">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"><i class="ti ti-user-plus fs-5 me-2"></i>รายละเอียดวิทยากรภายนอก</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="externalLecturerDetail" rows="4" placeholder="กรุณาระบุรายละเอียดวิทยากรภายนอก เช่น ชื่อ-นามสกุล, สังกัด, หัวข้อบรรยาย"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload -->
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
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="col-form-label fw-semibold mb-2"><i class="ti ti-clipboard-check fs-5 me-2"></i>สถานะการทดสอบ (Test)</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input prd-test-status prd-field" type="radio" name="prdTestStatus" id="prdTestReady" value="ready">
                                            <label class="form-check-label" for="prdTestReady">
                                                <i class="ti ti-check text-success me-1"></i>พร้อม Test
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input prd-test-status prd-field" type="radio" name="prdTestStatus" id="prdTestNotReady" value="not_ready">
                                            <label class="form-check-label" for="prdTestNotReady">
                                                <i class="ti ti-x text-danger me-1"></i>ไม่พร้อม Test
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">หมายเหตุ PRD</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control prd-field" id="prdRemark" rows="3" placeholder="รายละเอียดเพิ่มเติม..."></textarea>
                                </div>
                            </div>
                            <?php if ($isAdmin || (int)$divisionHead1 === 61): ?>
                            <!-- ปุ่มบันทึก PRD (แสดงเมื่อ tab เปิดและยังไม่ปิดงาน) -->
                            <div id="prdSaveBar" class="text-end" style="display:none;">
                                <button type="button" class="btn btn-success text-white" onclick="savePRDDetail()"><i class="ti ti-device-floppy me-1"></i>บันทึก PRD</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php include('layout/theme-settings.php'); ?>

        <?php //include('layout/footer.php'); 
        ?>

    </main>

    <!-- Comment Modal (สำหรับ Rework/Cancel) -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalTitle"><i class="ti ti-message-circle me-2"></i>ระบุเหตุผล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="commentText" class="form-label">เหตุผล/ข้อความ <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="commentText" rows="4" placeholder="กรุณาระบุเหตุผล..." required></textarea>
                        <div class="invalid-feedback">กรุณากรอกเหตุผล</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>ปิด</button>
                    <button type="button" class="btn btn-primary" id="confirmCommentBtn"><i class="ti ti-check me-1"></i>ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('js.php'); ?>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        let uploadBoxes = {
            detail: [],
            lecturer: []
        };

        // Global variables สำหรับ multi-job support
        let jobCounter = 1;
        let cachedOptions = {
            requester: '',
            tc: '',
            groupctm: '',
            zone: '<option value="zone 1">โซน 1</option><option value="zone 2">โซน 2</option><option value="zone 3">โซน 3</option><option value="zone 4">โซน 4</option><option value="zone 5">โซน 5</option>',
            product: '<option value=""></option><option value="AMS">AMS</option><option value="MV">MV</option><option value="PRISMA">PRISMA</option><option value="BS">BS</option>'
        };
        // Map employee Code -> dept display text for auto-fill
        let requesterDeptMap = {};

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

        function initJobCardSelects(card) {
            const select2Common = {
                allowClear: true,
                theme: "bootstrap-5"
            };

            const select2Tags = {
                allowClear: true,
                theme: "bootstrap-5",
                tags: true
            };

            // Destroy existing Select2 instances if any
            $(card).find('select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });

            // Initialize Objective select (multiple)
            $(card).find('.job-select-objective').select2({
                ...select2Common,
                placeholder: 'เลือกวัตถุประสงค์'
            }).html(cachedOptions.objective);

            // Toggle Objective Other box
            $(card).find('.job-select-objective').on('change', function() {
                const vals = $(this).val() || [];
                const boxOther = $(this).closest('.card-body').find('.box-objective-other');
                if (vals.includes('Other')) { // Assuming 'Other' is the value for "อื่นๆ"
                    boxOther.fadeIn();
                } else {
                    boxOther.hide().find('input').val('');
                }
            });

            // Initialize Requester select
            $(card).find('.job-select-requester').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือกผู้ร้องขอ'
            }).val(null).trigger('change');

            // Auto-fill department when requester is selected
            $(card).find('.job-select-requester').off('change.requesterDept').on('change.requesterDept', function() {
                const code = $(this).val();
                const deptInput = $(this).closest('.card-body').find('[data-field="RequesterDept"]');
                deptInput.val(requesterDeptMap[code] || '');
            });

            // Initialize TC select
            $(card).find('.job-select-tc').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือก TC ผู้รับผิดชอบ'
            }).val(null).trigger('change');

            // Initialize SN & WA selects
            $(card).find('.job-select-sn').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือก S/N'
            });

            $(card).find('.job-select-wa').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือก WA'
            });

            // Initialize GroupCtm select (multiple)
            $(card).find('.job-select-groupctm').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือกกลุ่มลูกค้า'
            });

            // Initialize Zone select (multiple)
            $(card).find('.job-select-zone').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือกพื้นที่/โซน'
            });

            // Initialize ProductName select
            $(card).find('.job-select-product').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือกผลิตภัณฑ์'
            }).val(null).trigger('change');

            // --- New CRM Contact Fields (Tags enabled) ---
            $(card).find('.job-select-contact').select2({
                ...select2Tags,
                placeholder: 'เลือกหรือพิมพ์ชื่อลูกค้า'
            });

            $(card).find('.job-select-position').select2({
                ...select2Tags,
                placeholder: 'เลือกหรือพิมพ์ตำแหน่ง'
            });

            $(card).find('.job-select-phone').select2({
                ...select2Tags,
                placeholder: 'เลือกหรือพิมพ์เบอร์โทร'
            });

            $(card).find('.job-select-email').select2({
                ...select2Tags,
                placeholder: 'เลือกหรือพิมพ์ Email'
            });

        }

        // Search CRM Data Function - Call this when CompanyName is available
        async function fetchCRMData(companyName, card) {
            if (!companyName) return;
            window.crmDataCache = window.crmDataCache || {};

            // Fetch WA/SN data (get_crm_wa.php)
            async function fetchWAData() {
                if (window.crmDataCache[companyName]) {
                    return window.crmDataCache[companyName];
                }
                card.find('.job-select-wa, .job-select-sn').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) $(this).select2('destroy');
                    $(this).html('<option value="">กำลังโหลด...</option>');
                });
                const res = await fetch(`api/get_crm_wa.php?q=${encodeURIComponent(companyName)}`);
                const json = await res.json();
                const data = (json.status && json.data) ? json.data : [];
                window.crmDataCache[companyName] = data;
                return data;
            }

            // Fetch contact data (crm_endpoint.php)
            async function fetchContactData() {
                const loadingOption = new Option('Loading...', '', false, false);
                card.find('.job-select-contact, .job-select-position, .job-select-phone, .job-select-email').append(loadingOption).trigger('change');
                const response = await fetch(`https://it.asefa.co.th/api/crm_endpoint.php?q=${encodeURIComponent(companyName)}`);
                return await response.json();
            }

            // Run both APIs in parallel — each result handled independently
            const [waResult, contactResult] = await Promise.allSettled([fetchWAData(), fetchContactData()]);

            // Handle WA/SN result
            if (waResult.status === 'fulfilled') {
                const data = waResult.value;
                const waSelect = card.find('.job-select-wa');
                waSelect.empty();
                const waAdded = new Set();
                data.forEach(row => {
                    if (row.Doc_no && !waAdded.has(row.Doc_no)) {
                        waSelect.append(new Option(row.Doc_no, row.Doc_no));
                        waAdded.add(row.Doc_no);
                    }
                });

                const snSelect = card.find('.job-select-sn');
                snSelect.empty();
                const snAdded = new Set();
                data.forEach(row => {
                    if (row.PartNo && !snAdded.has(row.PartNo)) {
                        snSelect.append(new Option(row.PartNo + (row.PartName ? ' - ' + row.PartName : ''), row.PartNo));
                        snAdded.add(row.PartNo);
                    }
                });

                if (waSelect.hasClass('select2-hidden-accessible')) waSelect.select2('destroy');
                waSelect.select2({ allowClear: true, theme: 'bootstrap-5', placeholder: 'กรุณาเลือก WA' });

                if (snSelect.hasClass('select2-hidden-accessible')) snSelect.select2('destroy');
                snSelect.select2({ allowClear: true, theme: 'bootstrap-5', placeholder: 'กรุณาเลือก S/N' });
            } else {
                console.error("CRM WA Fetch Error:", waResult.reason);
            }

            // Handle contact result
            if (contactResult.status === 'fulfilled') {
                const resData = contactResult.value;
                card.find('.job-select-contact option[value="Loading..."]').remove();
                card.find('.job-select-position option[value="Loading..."]').remove();
                card.find('.job-select-phone option[value="Loading..."]').remove();
                card.find('.job-select-email option[value="Loading..."]').remove();

                if (resData && resData.data) {
                    const contacts = [];
                    if (Array.isArray(resData.data[0].Branches)) {
                        resData.data[0].Branches.forEach(branch => {
                            if (branch.Contacts && Array.isArray(branch.Contacts)) {
                                contacts.push(...branch.Contacts);
                            }
                        });
                    } else if (resData.data[0].Branches.Contacts) {
                        contacts.push(...resData.data[0].Branches.Contacts);
                    }

                    card.data('contacts', contacts);

                    const updateSelect = ($select, items, key) => {
                        $select.empty();
                        const added = new Set();
                        items.forEach(c => {
                            const val = c[key];
                            if (val && !added.has(val)) {
                                $select.append(new Option(val, val, false, false));
                                added.add(val);
                            }
                        });
                        $select.trigger('change');
                    };

                    updateSelect(card.find('.job-select-contact'), contacts, 'FirstNameTH');
                    updateSelect(card.find('.job-select-position'), contacts, 'PositionName');
                    updateSelect(card.find('.job-select-phone'), contacts, 'Mobile');
                    updateSelect(card.find('.job-select-email'), contacts, 'Emails');

                    setupContactAutoFill(card);
                }
            } else {
                console.error("CRM Contact Fetch Error:", contactResult.reason);
                card.find('.job-select-contact, .job-select-position, .job-select-phone, .job-select-email')
                    .find('option[value="Loading..."]').remove();
            }
        }

        // ========== Auto-fill Position & Phone เมื่อเลือก CustomerName ==========
        function setupContactAutoFill(card) {
            const $contact = card.find('.job-select-contact');

            // ลบ event handler เก่า และเพิ่มใหม่
            $contact.off('change.autofill').on('change.autofill', function() {
                const selectedNames = $(this).val() || [];
                const contacts = card.data('contacts') || [];

                if (selectedNames.length === 0) {
                    // Clear ทั้งหมด
                    card.find('.job-select-position').val(null).trigger('change');
                    card.find('.job-select-phone').val(null).trigger('change');
                    card.find('.job-select-email').val(null).trigger('change');
                    return;
                }

                // หา contacts ที่ถูกเลือกตามลำดับ
                const positions = [];
                const phones = [];
                const emails = [];

                selectedNames.forEach(name => {
                    const contact = contacts.find(c => c.FirstNameTH === name);
                    if (contact) {
                        if (contact.PositionName) positions.push(contact.PositionName);
                        if (contact.Mobile) phones.push(contact.Mobile);
                        if (contact.Emails) emails.push(contact.Emails);
                    }
                });

                // Auto-fill Position, Phone, Email ตามลำดับ
                card.find('.job-select-position').val(positions).trigger('change');
                card.find('.job-select-phone').val(phones).trigger('change');
                card.find('.job-select-email').val(emails).trigger('change');
            });
        }

        function collectSalesDetail() {
            const salesDetails = [];
            const job = {};

            document.querySelectorAll('#nav-home .job-field').forEach(field => {
                const fieldName = field.getAttribute('data-field');
                if (!fieldName) return;

                if (field.tagName === 'SELECT' && field.multiple) {
                    const $select = $(field);
                    let val = $select.val() || [];
                    const jsonFields = ['ProductName', 'CustomerName', 'Position', 'PhoneNumber', 'Emails'];
                    if (jsonFields.includes(fieldName)) {
                        job[fieldName] = JSON.stringify(val);
                    } else {
                        job[fieldName] = val;
                    }
                } else if (field.tagName === 'SELECT') {
                    job[fieldName] = $(field).val() || '';
                } else if (field.tagName === 'TEXTAREA') {
                    job[fieldName] = field.value || '';
                } else {
                    job[fieldName] = field.value || '';
                }
            });

            const companyTypeRadio = document.querySelector('#nav-home .job-company-type:checked');
            job['CompanyType'] = companyTypeRadio ? companyTypeRadio.value : 'thai';

            salesDetails.push(job);
            return salesDetails;
        }

        // =====================================================
        // COLLECT CORPORATE DETAIL (ฝ่ายสื่อสาร)
        // =====================================================
        function collectCorporateDetail() {
            const corporateData = {
                serviceType: $('input[name="corporateServiceType"]:checked').val() || 'no_service'
            };

            console.log(corporateData);

            if (corporateData.serviceType === 'use_service') {
                // Date and time
                corporateData.date = $('#corporateDate').val() || '';

                // Photo service
                corporateData.usePhotoService = $('#usePhotoService').is(':checked');
                if (corporateData.usePhotoService) {
                    corporateData.photoTimeStart = $('#photoTimeStart').val() || '';
                    corporateData.photoTimeEnd = $('#photoTimeEnd').val() || '';
                    corporateData.photoLocations = [];
                    $('#photoServicePanel input[type="checkbox"]:checked').each(function() {
                        const val = $(this).val();
                        if (val === 'other') {
                            corporateData.photoLocations.push($('#photoLocOtherText').val() || 'อื่นๆ');
                        } else {
                            corporateData.photoLocations.push(val);
                        }
                    });
                }

                // Welcome service
                corporateData.useWelcomeService = $('#useWelcomeService').is(':checked');
                if (corporateData.useWelcomeService) {
                    corporateData.welcomeTimeStart = $('#welcomeTimeStart').val() || '';
                    corporateData.welcomeTimeEnd = $('#welcomeTimeEnd').val() || '';
                    corporateData.welcomeDetail = $('#welcomeDetail').val() || '';

                    // Collect screen options (ไม่มี checkbox หลักแล้ว)
                    corporateData.screens = {};

                    // MP1
                    const mp1Screens = [];
                    $('#welcomeMP1_TV:checked').each(function() {
                        mp1Screens.push($(this).val());
                    });
                    if (mp1Screens.length > 0) {
                        corporateData.screens.MP1 = mp1Screens;
                    }

                    // MP2
                    const mp2Screens = [];
                    $('#welcomeMP2_Food:checked, #welcomeMP2_VIP1:checked, #welcomeMP2_VIP2:checked').each(function() {
                        mp2Screens.push($(this).val());
                    });
                    if (mp2Screens.length > 0) {
                        corporateData.screens.MP2 = mp2Screens;
                    }

                    // MP3
                    const mp3Screens = [];
                    $('#welcomeMP3_Sofa:checked, #welcomeMP3_Room1:checked, #welcomeMP3_Room2:checked, #welcomeMP3_Room3:checked, #welcomeMP3_LED:checked').each(function() {
                        mp3Screens.push($(this).val());
                    });
                    if (mp3Screens.length > 0) {
                        corporateData.screens.MP3 = mp3Screens;
                    }

                    // MP4
                    const mp4Screens = [];
                    $('#welcomeMP4_LED:checked').each(function() {
                        mp4Screens.push($(this).val());
                    });
                    if (mp4Screens.length > 0) {
                        corporateData.screens.MP4 = mp4Screens;
                    }
                }
            }

            return corporateData;
        }

        // =====================================================
        // COLLECT LECTURER DETAIL (วิทยากร)
        // =====================================================
        function collectLecturerDetail() {
            const lecturerData = {
                lecturerType: $('input[name="lecturerType"]:checked').val() || ''
            };

            if (lecturerData.lecturerType === 'internal') {
                lecturerData.lecturers = $('#Lecturer').val() || [];
                lecturerData.presentTypes = [];
                $('input[name="presenttype[]"]:checked').each(function() {
                    lecturerData.presentTypes.push($(this).val());
                });
            } else if (lecturerData.lecturerType === 'external') {
                lecturerData.externalDetail = $('#externalLecturerDetail').val() || '';
            }

            return lecturerData;
        }

        // =====================================================
        // COLLECT PRD DETAIL (วางแผน)
        // =====================================================
        function collectPRDDetail() {
            return {
                testStatus: $('input[name="prdTestStatus"]:checked').val() || '',
                remark: $('#prdRemark').val() || ''
            };
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

        // เมื่อมีการเลือกเมนูอาหาร
        $(document).on('change', '.food-choice', function() {
            const currentSet = $(this).data('setid');
            const allChoices = $('.food-choice');
            const checkedInThisSet = $(`.food-choice[data-setid="${currentSet}"]:checked`);
            const diners = parseInt($('#NumberDiners').val()) || 0;

            // กำหนดจำนวนเมนูสูงสุดที่เลือกได้ตามจำนวนผู้ทาน
            let maxSelectable = 0;
            if (diners <= 3) maxSelectable = 3;
            else if (diners <= 6) maxSelectable = 4;
            else if (diners <= 10) maxSelectable = 6;

            // ตรวจสอบไม่ให้เลือกเกินจำนวนที่กำหนด
            if (checkedInThisSet.length > maxSelectable && maxSelectable > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: `เลือกได้สูงสุด ${maxSelectable} เมนู`,
                    text: `ตามจำนวนผู้ทาน (${diners} ท่าน)`
                });
                $(this).prop('checked', false);
                return;
            }

            // หาชุดอาหารอื่นๆ นอกจากชุดปัจจุบัน
            const otherSets = [...new Set(allChoices.map((_, el) => $(el).data('setid')).get())]
                .filter(id => id !== currentSet);

            // ตรวจสอบว่ามีการเลือกเมนูในชุดปัจจุบันหรือไม่
            if (checkedInThisSet.length > 0) {
                // ล็อกชุดอื่น
                otherSets.forEach(id => $(`.food-choice[data-setid="${id}"]`).prop('disabled', true));
            } else {
                // ตรวจสอบว่ามีการเลือกในชุดอื่นหรือไม่
                const anyChecked = $('.food-choice:checked').length > 0;
                if (!anyChecked) {
                    // ถ้าไม่มีการเลือกเลย ปลดล็อกทุกชุด
                    allChoices.prop('disabled', false);
                }
            }
        });

        // เมื่อเปลี่ยนจำนวนผู้ทาน ให้ล้างการเลือกทั้งหมด
        $(document).on('input', '#NumberDiners', function() {
            $('.food-choice').prop('checked', false).prop('disabled', false);
        });

        $(document).ready(async function() {

            // Helper function to robustly reset Select2
            function resetSelect2($select) {
                if ($select.hasClass("select2-hidden-accessible")) {
                    try {
                        $select.select2('destroy');
                    } catch (e) {}
                }
                // Cleanup containers
                $select.siblings('.select2-container').remove();
                $select.next('.select2-container').remove();

                // Cleanup attributes and classes
                $select.removeClass('select2-hidden-accessible')
                    .removeAttr('data-select2-id')
                    .removeAttr('tabindex')
                    .removeAttr('aria-hidden')
                    .css('display', ''); // Ensure visible

                // Clear options
                $select.empty();
            }
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

            const [objectiveList, groupList, foodSets, empAllRes, divisionRes] = await Promise.all([
                get_objective_list(),
                get_groupctm_list(),
                loadFoodSets(),
                fetch('./api/emp_list.php?action=emplist').then(r => r.json()),
                fetch('./api/emp_list.php?action=divisionlist').then(r => r.json())
            ]);
            initUploadBox('fileInput_detail', 'fileList_detail', 'detail');
            initUploadBox('fileInput_lecturer', 'fileList_lecturer', 'lecturer');

            // ---------- เพิ่มข้อมูลลง select โดยรวม string ก่อน append ----------
            let requesterHTML = '',
                tcHTML = '',
                lecturerHTML = '';

            // Build dept name map: DepartmentCode -> display text
            const deptNameMap = {};
            Object.values(divisionRes).forEach(sec => {
                Object.values(sec.Department).forEach(dep => {
                    deptNameMap[dep.DepartmentCode] = `${dep.DepartmentCode}-${dep.DepartmentName}`;
                });
            });

            // Cache Objective options for Select2 in Job Cards (moved from inline)
            let objectiveHTML = '';
            if (Array.isArray(objectiveList)) {
                objectiveList.forEach(obj => objectiveHTML += `<option value="${obj.visit_objective_id}">${obj.visit_objective_name}</option>`);
            }
            objectiveHTML += '<option value="Other">อื่นๆ</option>';
            cachedOptions.objective = objectiveHTML;

            // Populate and Init Sales Card Body Selects
            const salesCardBodyInit = document.querySelector('#nav-home .card-body');
            if (salesCardBodyInit) {
                const objSelect = $(salesCardBodyInit).find('.job-select-objective');
                objSelect.html(cachedOptions.objective);

                // Initialize all selects (this binds events including Other toggle)
                initJobCardSelects(salesCardBodyInit);
            }

            empAllRes.forEach(e => {
                requesterHTML += `<option value="${e.Code}">${e.FullName}</option>`;
                tcHTML += `<option value="${e.Code}">${e.FullName}</option>`;
                lecturerHTML += `<option value="${e.Code}">${e.FullName}</option>`;
                // Build requester -> dept map
                requesterDeptMap[e.Code] = deptNameMap[e.DivisionCode] || e.DivisionCode || '';
            });

            // Cache options สำหรับใช้ใน addJobCard
            cachedOptions.requester = requesterHTML;
            cachedOptions.tc = tcHTML;

            // Populate Lecturer select (ยังใช้ ID เดิม)
            $('#Lecturer').html(lecturerHTML);

            // Populate selects ใน Sales Card Body
            const salesCardBody = document.querySelector('#nav-home .card-body');
            if (salesCardBody) {
                $(salesCardBody).find('.job-select-requester').html('<option value=""></option>' + requesterHTML);
                $(salesCardBody).find('.job-select-tc').html('<option value=""></option>' + tcHTML);

                // Init Select2 for Sales Card
                initJobCardSelects(salesCardBody);
            }

            // ---------- Select2 Init สำหรับ Lecturer เท่านั้น ----------
            const select2Common = {
                allowClear: true,
                theme: "bootstrap-5"
            };

            $('#Lecturer').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือกรายชื่อวิทยากร'
            }).val(null).trigger('change');

            // Bind Company Search Button
            $(document).on('click', '.btn-search-company', function() {
                const card = $(this).closest('.card-body');
                const companyName = $(this).closest('.input-group').find('[data-field="CompanyName"]').val().trim();
                if (companyName) {
                    fetchCRMData(companyName, card);
                }
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

            // CusFood checkboxes - food_1/food_2 เลือกได้พร้อมกัน, food_3 exclusive
            $('input[name="CusFood[]"]').on('change', function() {
                const clickedValue = $(this).val();

                if (this.checked) {
                    if (clickedValue === 'EatOutside') {
                        // food_3 selected: uncheck food_1, food_2 and disable food choices
                        $('#food_1, #food_2').prop('checked', false);
                        $('.food-choice').prop('disabled', true).prop('checked', false);
                    } else {
                        // food_1 or food_2 selected: uncheck food_3, enable food choices
                        $('#food_3').prop('checked', false);
                        $('.food-choice').prop('disabled', false);
                    }
                } else {
                    // If nothing is checked, default to EatOutside
                    const anyChecked = $('input[name="CusFood[]"]:checked').length > 0;
                    if (!anyChecked) {
                        $('#food_3').prop('checked', true);
                        $('.food-choice').prop('disabled', true).prop('checked', false);
                    }
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
            $(document).on('click', '.btn-search-job', function() {
                const $card = $(this).closest('.card-body');
                const jobno = $card.find('[data-field="JobNo"]').val();

                if (!jobno) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกเลข Job',
                        text: 'กรุณากรอกเลข Job ก่อนค้นหา'
                    });
                    return;
                }

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
                            $card.find('[data-field="ProjectName"]').val(dataJob.JobName);
                            $card.find('[data-field="CompanyName"]').val(dataJob.CustomerName);
                        } else {
                            $card.find('[data-field="ProjectName"], [data-field="CompanyName"]').val('');
                        }
                    } catch {}

                    try {
                        const dataSearch = JSON.parse(resSearch[0]);
                        const $WA = $card.find('.job-select-wa');

                        // Reset Select2 completely
                        resetSelect2($WA);

                        $WA.append(new Option('เลือกเลข WA ทั้งหมด', 'All'));
                        if (!dataSearch.error && Array.isArray(dataSearch.Doc_No)) {
                            dataSearch.Doc_No.forEach(item => $WA.append(new Option(item, item)));
                        }
                        $WA.append(new Option('-', '-')).val(null).select2({
                            placeholder: 'กรุณาเลือก WA',
                            allowClear: true,
                            theme: "bootstrap-5"
                        });
                    } catch {}

                    try {
                        const $SN = $card.find('.job-select-sn');

                        // Reset Select2 completely
                        resetSelect2($SN);

                        $SN.append(resSn[0]).select2({
                            placeholder: 'กรุณาเลือก S/N',
                            allowClear: true,
                            theme: "bootstrap-5"
                        });
                    } catch {}
                }).always(() => Swal.close());
            });

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('page') === 'view' && urlParams.get('id')) {
                getVisitorData(urlParams.get('id'));
            } else {
                Swal.close();
            }

        });

        // =====================================================
        // CORPORATE COMMUNICATION TAB - Toggle Panels
        // =====================================================

        // Toggle corporate service panel
        $(document).on('change', '.corporate-service-type', function() {
            if ($(this).val() === 'use_service') {
                $('#corporateServicePanel').slideDown(300);
            } else {
                $('#corporateServicePanel').slideUp(300);
            }
        });

        // Toggle photo service panel  
        $(document).on('change', '#usePhotoService', function() {
            if ($(this).is(':checked')) {
                $('#photoServicePanel').slideDown(300);
            } else {
                $('#photoServicePanel').slideUp(300);
            }
        });

        // Toggle welcome service panel
        $(document).on('change', '#useWelcomeService', function() {
            if ($(this).is(':checked')) {
                $('#welcomeServicePanel').slideDown(300);
            } else {
                $('#welcomeServicePanel').slideUp(300);
            }
        });

        // Toggle photo location "อื่นๆ" input field
        $(document).on('change', '#photoLocOther', function() {
            if ($(this).is(':checked')) {
                $('#photoLocOtherInput').slideDown(200);
            } else {
                $('#photoLocOtherInput').slideUp(200);
                $('#photoLocOtherText').val('');
            }
        });

        // =====================================================
        // LECTURER TAB - Toggle Panels
        // =====================================================

        $(document).on('change', '.lecturer-type', function() {
            if ($(this).val() === 'internal') {
                $('#internalLecturerPanel').slideDown(300);
                $('#externalLecturerPanel').slideUp(300);
            } else {
                $('#internalLecturerPanel').slideUp(300);
                $('#externalLecturerPanel').slideDown(300);
            }
        });

        // Init datepicker สำหรับ corporate date
        $(document).on('focus', '.corporate-date', function() {
            $(this).datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true
            });
        });

        $(document).on('change', '.reserve, .dateTrival, input[name="meeting_time_start[]"], input[name="meeting_time_end[]"]', function() {
            let row = $(this).closest('tr');
            loadMeetingRoom(row);
        });

        async function get_objective_list() {
            try {
                const res = await fetch('api/objective_action.php', {
                    cache: 'no-store'
                });
                const data = await res.json();
                return data?.data ?? [];
            } catch (err) {
                console.error('Error loading objectives:', err);
                return [];
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

                // สร้าง HTML สำหรับ select2 options
                const groupctmHTML = groups.map(item =>
                    `<option value="${item.visit_groupctm_id}">${item.visit_groupctm_name}</option>`
                ).join('');

                // Cache groupctm สำหรับใช้ใน addJobCard
                cachedOptions.groupctm = groupctmHTML;

                // Populate groupctm ใน Sales Card Body
                const salesCardBodyGrp = document.querySelector('#nav-home .card-body');
                if (salesCardBodyGrp) {
                    $(salesCardBodyGrp).find('.job-select-groupctm').html(groupctmHTML);
                }

            } catch (err) {
                console.error('Error loading group customer list:', err);
            }
        }

        // ========== Double Submit Prevention ==========
        let isSaving = false;

        function resetSaveState() {
            isSaving = false;
            const $saveButtons = $('[onclick*="save_visitor_form"]');
            $saveButtons.prop('disabled', false).removeClass('disabled');
        }

        function save_visitor_form(status = 0) {
            // ป้องกันกด Save ซ้ำ
            if (isSaving) {
                console.log('Save in progress, ignoring duplicate request');
                return;
            }
            isSaving = true;

            // Disable save buttons ระหว่างบันทึก
            const $saveButtons = $('[onclick*="save_visitor_form"]');
            $saveButtons.prop('disabled', true).addClass('disabled');

            let formData = new FormData();

            // ข้อมูล Objective และอื่นๆ นอก Job Card
            formData.append("ObjectiveOther", $('#Objective_other').val());

            // ข้อมูล Travel Tab
            formData.append("CustomerNameThai", $('#CustomerNameThai').val());
            formData.append("CustomerNameForeign", $('#CustomerNameForeign').val());
            formData.append("CustomerTotal", $('#CustomerTotal').val());
            formData.append("CustomerCar", $('#CustomerCar').val());
            formData.append("CarNumber", $('#CarNumber').val());
            formData.append("DriverNumber", $('#DriverNumber').val());
            formData.append("Remark", $('#Remark').val());

            // ข้อมูล HR Tab
            formData.append("NumberDiners", $('#NumberDiners').val());
            formData.append("OtherMenu", $('#OtherMenu').val());
            formData.append("RemarkFood", $('#RemarkFood').val());

            formData.append("Status", status);

            // รวบรวมข้อมูล Sales Detail จาก Job Cards เป็น JSON
            const salesDetail = collectSalesDetail();
            formData.append("SalesDetail", JSON.stringify(salesDetail));

            // รวบรวมข้อมูล Corporate Detail (ฝ่ายสื่อสาร) เป็น JSON
            const corporateDetail = collectCorporateDetail();
            formData.append("CorporateDetail", JSON.stringify(corporateDetail));

            // รวบรวมข้อมูล Lecturer Detail (วิทยากร) เป็น JSON
            const lecturerDetail = collectLecturerDetail();
            formData.append("LecturerDetail", JSON.stringify(lecturerDetail));

            // รวบรวมข้อมูล PRD Detail (วางแผน) เป็น JSON
            const prdDetail = collectPRDDetail();
            formData.append("PRDDetail", JSON.stringify(prdDetail));

            // รวบรวม checkbox ทั่วไป
            ['objective', 'travel', 'CusFood', 'food'].forEach(name => {
                var nameCapitalized = name.charAt(0).toUpperCase() + name.slice(1);
                $(`[name='${name}[]']:checked, [name='${name}[]'] option:selected`).each(function() {
                    const val = $(this).val();
                    formData.append(`${nameCapitalized}[]`, val);
                });
            });

            let schedule = [];
            $('#table-schedule tbody tr').each(function() {
                if ($(this).find('.meetingroom').val() == null) return;
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

            uploadBoxes.detail.forEach(f => formData.append("files_detail[]", f));
            uploadBoxes.lecturer.forEach(f => formData.append("files_lecturer[]", f));

            Swal.fire({
                title: 'ยืนยันการบันทึกข้อมูล?',
                text: 'คุณต้องการบันทึกข้อมูลฟอร์มนี้หรือไม่',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // console.log(schedule);
                    $.ajax({
                        url: 'api/visitor_form_save.php',
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
                                if (!schedule || schedule.length === 0) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'สำเร็จ',
                                        text: response.message
                                    }).then(() => {
                                        window.location.href = './listvisitor.php?page=listvisitor';
                                    });
                                    return;
                                }

                                const ajaxCalls = schedule.map(s => {
                                    const parts = s.date.split('/');
                                    const newDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                                    return $.ajax({
                                        url: 'https://it.asefa.co.th/Zoompoo/functionPost.php',
                                        type: 'POST',
                                        data: {
                                            'function': 'insertMeetingLog_Pdew',
                                            'id': s.room,
                                            'user_id': '',
                                            'start_time': s.time_start,
                                            'end_time': s.time_end,
                                            'Subject': 'จองห้องประชุม',
                                            'date_filter': newDate
                                        }
                                    }).then(
                                        res => {
                                            const r = typeof res === 'string' ? JSON.parse(res) : res;
                                            return r.status === '1';
                                        },
                                        () => false
                                    );
                                });

                                Promise.all(ajaxCalls).then(results => {
                                    const allSuccess = results.every(r => r === true);

                                    if (allSuccess) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'สำเร็จ',
                                            text: response.message
                                        }).then(() => {
                                            window.location.href = './listvisitor.php?page=listvisitor';
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'ผิดพลาด',
                                            text: 'ไม่สามารถจองห้องประชุมได้'
                                        });
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: response.message
                                }).then(() => resetSaveState());
                            }
                        },
                        error: err => {
                            resetSaveState(); // Re-enable save buttons
                            Swal.close();
                            console.error(err);
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

        function loadMeetingRoom(row, selectedValue = null) {
            let reserve = row.find('.reserve').val();
            let dateTrival = row.find('.dateTrival').val();
            let timeStart = row.find('input[name="meeting_time_start[]"]').val();
            let timeEnd = row.find('input[name="meeting_time_end[]"]').val();

            if (reserve && dateTrival && timeStart && timeEnd) {
                let apiUrl = (reserve === 'meeting') ?
                    'https://it.asefa.co.th/Zoompoo/check_room.php' :
                    './api/check_zoom.php';

                $.ajax({
                    url: apiUrl,
                    type: 'POST',
                    data: {
                        type: reserve,
                        booking_date: formatDateToYMD(dateTrival),
                        time_start: timeStart,
                        time_end: timeEnd
                    },
                    dataType: 'json',
                    success: async function(response) {
                        // console.log(response);
                        let select = row.find('.meetingroom');

                        select.empty();
                        select.append('<option value="">-- เลือกห้อง --</option>');

                        if (response.data) {
                            for (let item of Object.values(response.data)) {
                                select.append(`<option value="${item.id_pk}">${item.room_name}</option>`);
                            }
                        }

                        if (selectedValue) {
                            select.val(selectedValue);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("API error:", error);
                    }
                });
            }
        }

        // ส่งค่าจาก PHP ไปยัง JavaScript
        const IS_ADMIN = <?php echo json_encode($isAdmin); ?>;
        const CURRENT_USER_CODE = "<?php echo $code; ?>";
        const DIVISION_HEAD1 = <?php echo json_encode($divisionHead1); ?>;
        const APPROVER_CODE = "500611042"; // รหัสผู้มีสิทธิ์ Approve

        // Modal variables
        let pendingCommentAction = {
            id: null,
            newStatus: null
        };

        // =====================================================
        // COMMENT MODAL FUNCTIONS
        // =====================================================
        function openCommentModal(id, newStatus, title) {
            pendingCommentAction = {
                id,
                newStatus
            };
            $('#commentModalTitle').html('<i class="ti ti-message-circle me-2"></i>' + title);
            $('#commentText').val('').removeClass('is-invalid');

            // เปลี่ยนสีปุ่มตามประเภท
            const $btn = $('#confirmCommentBtn');
            if (newStatus === 9) {
                $btn.removeClass('btn-primary btn-warning').addClass('btn-success').html('<i class="ti ti-ban me-1"></i>ยืนยันยกเลิก');
            } else if (newStatus === 4) {
                $btn.removeClass('btn-primary btn-danger').addClass('btn-success text-white').html('<i class="ti ti-refresh me-1"></i>ยืนยัน Rework');
            } else {
                $btn.removeClass('btn-danger btn-warning').addClass('btn-primary').html('<i class="ti ti-check me-1"></i>ยืนยัน');
            }

            const modal = new bootstrap.Modal(document.getElementById('commentModal'));
            modal.show();
        }

        // Modal submit handler
        $(document).on('click', '#confirmCommentBtn', function() {
            const comment = $('#commentText').val().trim();
            if (!comment) {
                $('#commentText').addClass('is-invalid');
                return;
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('commentModal'));
            modal.hide();

            submitWithComment(pendingCommentAction.id, pendingCommentAction.newStatus, comment);
        });

        function submitWithComment(id, newStatus, comment) {
            let formData = new FormData();
            formData.append("id", id);
            formData.append("Status", newStatus);
            formData.append("Comment", comment);

            let actionText = "ดำเนินการ";
            if (newStatus === 9) actionText = "ยกเลิกเอกสาร";
            else if (newStatus === 4) actionText = "Rework เอกสาร";

            Swal.fire({
                title: 'ยืนยันการทำรายการ?',
                text: `คุณต้องการ${actionText}ใช่หรือไม่?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
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
                                title: 'กำลังบันทึก...',
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
                                    text: response.message || 'บันทึกข้อมูลเรียบร้อยแล้ว'
                                }).then(() => {
                                    window.location.href = './listvisitor.php?page=listvisitor';
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
                            Swal.close();
                            console.error(err);
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

        // Approve function (ไม่ต้องกรอก comment) - Update เฉพาะ Status
        function approveDocument(id) {
            Swal.fire({
                title: 'ยืนยันการอนุมัติ?',
                text: 'คุณต้องการอนุมัติเอกสารนี้ใช่หรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'อนุมัติ',
                confirmButtonColor: '#10B981',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'api/visitor_status_update.php',
                        type: 'POST',
                        data: {
                            id: id,
                            status: 3
                        },
                        beforeSend: () => Swal.fire({
                            title: 'กำลังบันทึก...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        }),
                        success: res => {
                            Swal.close();
                            let response = typeof res === "string" ? JSON.parse(res) : res;
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'อนุมัติสำเร็จ'
                                }).then(() => window.location.href = './listvisitor.php?page=listvisitor');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: response.message
                                });
                            }
                        },
                        error: (error) => {
                            console.log(error);
                            Swal.close();
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

        // Delete existing file function
        function deleteExistingFile(fileId, element) {
            Swal.fire({
                title: 'ยืนยันการลบไฟล์?',
                text: 'คุณต้องการลบไฟล์นี้ใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ลบ',
                confirmButtonColor: '#dc3545',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'api/visitor_file_delete.php',
                        type: 'POST',
                        data: {
                            id: fileId
                        },
                        success: res => {
                            let response = typeof res === "string" ? JSON.parse(res) : res;
                            if (response.status) {
                                $(element).closest('.file-item').fadeOut(300, function() {
                                    $(this).remove();
                                });
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบสำเร็จ',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: response.message
                                });
                            }
                        },
                        error: () => Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: 'ไม่สามารถลบไฟล์ได้'
                        })
                    });
                }
            });
        }

        // =====================================================
        // ACKNOWLEDGE FUNCTIONS (ใช้ API visitor_acknowledge.php)
        // =====================================================
        const DIVISION_INFO = {
            54: {
                name: 'TC',
                icon: 'ti-device-desktop'
            },
            87: {
                name: 'QC',
                icon: 'ti-checklist'
            },
            83: {
                name: 'PRD',
                icon: 'ti-package'
            },
            63: {
                name: 'สื่อสาร',
                icon: 'ti-speakerphone'
            },
            31: {
                name: 'HR',
                icon: 'ti-users'
            },
            61: {
                name: 'วางแผน',
                icon: 'ti-calendar-event'
            },
            65: {
                name: 'ขนส่ง',
                icon: 'ti-truck-delivery'
            }
        };

        async function loadAcknowledgeStatus(visitorFormId) {
            try {
                const res = await fetch(`api/visitor_acknowledge.php?action=get_status&visitor_form_id=${visitorFormId}`);
                const data = await res.json();
                if (!data.status) return {
                    data: [],
                    all_acknowledged: false
                };
                return data;
            } catch (e) {
                console.error('Error loading acknowledge status:', e);
                return {
                    data: [],
                    all_acknowledged: false
                };
            }
        }

        function renderAcknowledgeStatus(ackResult, currentStatus, visitorFormId) {
            // ซ่อนถ้าไม่ใช่สถานะที่ต้องแสดง หรือไม่มีข้อมูล
            if (!ackResult || !ackResult.data || (currentStatus !== 3 && currentStatus !== 5 && currentStatus !== 6)) {
                $('#acknowledge-status').hide();
                $('.action-bar').addClass('hidden');
                return false;
            }

            let html = '<div class="status-dashboard">';
            const userDivision = DIVISION_HEAD1;

            // ตรวจสอบว่าวางแผน(61) รับทราบแล้วหรือยัง
            const prdAck = ackResult.data.find(item => item.division_code == 61);
            if (prdAck && prdAck.is_acknowledged) {
                $('#nav-prd-tab').show();
            } else {
                $('#nav-prd-tab').hide();
            }

            ackResult.data.forEach(item => {
                const info = DIVISION_INFO[item.division_code] || {
                    name: item.division_name,
                    icon: 'ti-building'
                };
                const isAcked = item.is_acknowledged;
                const isMyDivision = (item.division_code == userDivision);
                const isWaitingMe = !isAcked && isMyDivision && !IS_ADMIN;

                // ตรวจสอบว่า user สามารถกดรับทราบได้หรือไม่
                const canAcknowledge = !isAcked && (IS_ADMIN || isMyDivision) && currentStatus !== 6;
                const clickAttr = canAcknowledge ? `onclick="acknowledgeAction(${visitorFormId}, ${item.division_code})" style="cursor:pointer;"` : '';

                // Card Classes
                let cardClass = 'status-card';
                if (isAcked) cardClass += ' active';
                else cardClass += ' pending';
                if (isWaitingMe) cardClass += ' waiting-you';
                if (canAcknowledge) cardClass += ' clickable';

                // Status Text & Icon
                const statusText = isAcked ? 'รับทราบแล้ว' : (canAcknowledge ? 'คลิกเพื่อรับทราบ' : 'รอรับทราบ');
                const statusIcon = isAcked ? 'ti-check' : (canAcknowledge ? 'ti-hand-click' : 'ti-clock');
                const userText = item.user ? `<div class="small text-muted mt-1"><i class="ti ti-user me-1"></i>${item.user}</div>` : '';
                const timeText = item.datetime ? `<div class="small text-muted"><i class="ti ti-calendar-time me-1"></i>${item.datetime}</div>` : '';

                html += `
                    <div class="${cardClass}" ${clickAttr}>
                        <div class="status-icon-box">
                            <i class="ti ${info.icon}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark">${info.name}</div>
                            <div class="small ${isAcked ? 'text-success' : 'text-warning'} fw-semibold">
                                <i class="ti ${statusIcon} me-1"></i>${statusText}
                            </div>
                            ${userText}
                            ${timeText}
                        </div>
                    </div>
                `;
            });

            html += '</div>';

            $('#ack-divisions').html(html);
            $('#acknowledge-status').slideDown(300);

            return ackResult.all_acknowledged;
        }

        function acknowledgeAction(visitorFormId, divisionCode) {
            const info = DIVISION_INFO[divisionCode] || {
                name: 'ฝ่าย'
            };
            Swal.fire({
                title: 'ยืนยันการรับทราบ?',
                text: `คุณต้องการรับทราบในนามฝ่าย ${info.name} ใช่หรือไม่?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'รับทราบ',
                confirmButtonColor: '#10B981',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'api/visitor_acknowledge.php',
                        type: 'POST',
                        data: {
                            action: 'acknowledge',
                            visitor_form_id: visitorFormId,
                            division_code: divisionCode
                        },
                        beforeSend: () => Swal.fire({
                            title: 'กำลังบันทึก...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        }),
                        success: res => {
                            Swal.close();
                            let response = typeof res === "string" ? JSON.parse(res) : res;
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'รับทราบสำเร็จ',
                                    text: response.message
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: response.message
                                });
                            }
                        },
                        error: () => {
                            Swal.close();
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

        // ===== PRD Detail Edit Functions =====
        let currentVisitorFormId = null; // เก็บ ID สำหรับ save

        // เปิด PRD fields ให้แก้ไขได้ พร้อมแสดงปุ่มบันทึก
        function enablePRDEdit() {
            $('.prd-field').prop('disabled', false);
            $('#prdSaveBar').show();
        }

        function savePRDDetail() {
            if (!currentVisitorFormId) return;
            const prdDetail = collectPRDDetail();
            Swal.fire({
                title: 'ยืนยันบันทึก?',
                text: 'บันทึกข้อมูลฝ่ายวางแผน (PRD)',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                confirmButtonColor: '#10B981',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'api/visitor_acknowledge.php',
                        type: 'POST',
                        data: {
                            action: 'update_prd',
                            visitor_form_id: currentVisitorFormId,
                            prd_detail: JSON.stringify(prdDetail)
                        },
                        beforeSend: () => Swal.fire({
                            title: 'กำลังบันทึก...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        }),
                        success: res => {
                            Swal.close();
                            let response = typeof res === "string" ? JSON.parse(res) : res;
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'บันทึกสำเร็จ',
                                    text: response.message
                                });
                                // fields ยังคง editable ต่อ
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: response.message
                                });
                            }
                        },
                        error: () => {
                            Swal.close();
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

        function closeJob(visitorFormId) {
            Swal.fire({
                title: 'ยืนยันการปิดงาน?',
                text: 'คุณต้องการปิดงานนี้ใช่หรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ปิดงาน',
                confirmButtonColor: '#3B82F6',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'api/visitor_acknowledge.php',
                        type: 'POST',
                        data: {
                            action: 'close_job',
                            visitor_form_id: visitorFormId
                        },
                        beforeSend: () => Swal.fire({
                            title: 'กำลังบันทึก...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        }),
                        success: res => {
                            Swal.close();
                            let response = typeof res === "string" ? JSON.parse(res) : res;
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ปิดงานสำเร็จ'
                                }).then(() => window.location.href = './listvisitor.php?page=listvisitor');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: response.message
                                });
                            }
                        },
                        error: () => {
                            Swal.close();
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

        function setFormReadOnly(isReadOnly) {
            if (isReadOnly) {
                // Disable all inputs
                $('input, textarea, select').not('#commentText').prop('disabled', true);
                // Hide add buttons
                $('.btn-add-job, .btn-add-schedule, #btnAddRow, .removeRow, .upload-area, .removeJobBtn').hide();
                // Disable Select2
                $('.select2-selection').css('pointer-events', 'none');
            }
        }

        function formatDateToYMD(dateStr) {
            let parts = dateStr.split('/');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return dateStr;
        }

        // ==========================================
        // EDIT MODE LOGIC
        // ==========================================

        function update_visitor_form(id, status) {
            let formData = new FormData();

            // เพิ่ม ID สำหรับอัพเดท
            formData.append("id", id);

            // ข้อมูล Objective และอื่นๆ นอก Job Card
            formData.append("ObjectiveOther", $('#Objective_other').val());

            // ข้อมูล Travel Tab
            formData.append("CustomerNameThai", $('#CustomerNameThai').val());
            formData.append("CustomerNameForeign", $('#CustomerNameForeign').val());
            formData.append("CustomerTotal", $('#CustomerTotal').val());
            formData.append("CustomerCar", $('#CustomerCar').val());
            formData.append("CarNumber", $('#CarNumber').val());
            formData.append("DriverNumber", $('#DriverNumber').val());
            formData.append("Remark", $('#Remark').val());

            // ข้อมูล HR Tab
            formData.append("NumberDiners", $('#NumberDiners').val());
            formData.append("OtherMenu", $('#OtherMenu').val());
            formData.append("RemarkFood", $('#RemarkFood').val());

            formData.append("Status", status);

            // รวบรวมข้อมูล Sales Detail จาก Job Cards เป็น JSON
            const salesDetail = collectSalesDetail();
            formData.append("SalesDetail", JSON.stringify(salesDetail));

            // รวบรวมข้อมูล Corporate Detail (ฝ่ายสื่อสาร) เป็น JSON
            const corporateDetail = collectCorporateDetail();
            formData.append("CorporateDetail", JSON.stringify(corporateDetail));

            // รวบรวมข้อมูล Lecturer Detail (วิทยากร) เป็น JSON
            const lecturerDetail = collectLecturerDetail();
            formData.append("LecturerDetail", JSON.stringify(lecturerDetail));

            // รวบรวมข้อมูล PRD Detail (วางแผน) เป็น JSON
            const prdDetail = collectPRDDetail();
            formData.append("PRDDetail", JSON.stringify(prdDetail));

            // ข้อมูล checkboxes นอก Job Card
            // Note: For Food, handle logic inside collect or ensure valid state
            ['objective', 'travel', 'CusFood', 'food'].forEach(name => {
                var nameCapitalized = name.charAt(0).toUpperCase() + name.slice(1);
                $(`[name='${name}[]']:checked, [name='${name}[]'] option:selected`).each(function() {
                    const val = $(this).val();
                    formData.append(`${nameCapitalized}[]`, val);
                });
            });

            let schedule = [];
            $('#table-schedule tbody tr').each(function() {
                if ($(this).find('.meetingroom').val() == null) return;
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

            uploadBoxes.detail.forEach(f => formData.append("files_detail[]", f));
            uploadBoxes.lecturer.forEach(f => formData.append("files_lecturer[]", f));

            let actionText = "บันทึกข้อมูล";
            if (status === 9) actionText = "ยกเลิกเอกสาร";
            else if (status === 2) actionText = "ส่งแบบฟอร์ม";

            Swal.fire({
                title: 'ยืนยันการทำรายการ?',
                text: `คุณต้องการ ${actionText} ใช่หรือไม่?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
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
                                title: 'กำลังบันทึก...',
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
                                    text: response.message || 'บันทึกข้อมูลเรียบร้อยแล้ว'
                                }).then(() => {
                                    window.location.reload();
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
                            Swal.close();
                            console.error(err);
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


        // renderJobCards is defined earlier in the file

        // ==========================================
        // EDIT MODE LOGIC
        // ==========================================

        async function getVisitorData(visitorId) {
            try {
                Swal.fire({
                    title: 'กำลังโหลดข้อมูล...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const res = await fetch(`api/visitor_form_get.php?id=${visitorId}`);
                const data = await res.json();

                if (!data.status) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่พบข้อมูล',
                        text: data.message
                    });
                    return;
                }

                const d = data.data;

                // ตรวจสอบสิทธิ์การเข้าถึง (Admin หรือผู้สร้างเท่านั้น)
                if (!IS_ADMIN && d.UserCreated !== CURRENT_USER_CODE) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่มีสิทธิ์เข้าถึง',
                        text: 'คุณไม่มีสิทธิ์แก้ไขเอกสารนี้'
                    }).then(() => {
                        window.location.href = './listvisitor.php?page=listvisitor';
                    });
                    return;
                }
                // console.log(d);

                var currentStatus = d.Status;

                // --- 1. Populate Simple Fields ---
                $('#Objective_other').val(d.ObjectiveOther);
                $('#ProjectName').val(d.ProjectName);
                $('#JobNo').val(d.JobNo);
                $('#ProductName').val(d.ProductName);
                $('#CompanyName').val(d.CompanyName);
                $('#CustomerName').val(d.CustomerName);
                $('#Position').val(d.Position);
                $('#PhoneNumber').val(d.PhoneNumber);
                $('#Detail').val(d.Detail);
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

                // กรอกข้อมูลวัตถุประสงค์
                try {
                    const obj = JSON.parse(d.Objective || '[]');
                    $('#List_objective').val(obj).trigger('change');
                    if (obj.includes("other") || d.ObjectiveOther) {
                        $("#box_objective_other").slideDown(300);
                    }
                } catch (e) {
                    console.error("Error loading Objective:", e);
                }

                // แสดงแท็บวิทยากร (ถ้าจำเป็น)
                try {
                    const obj = JSON.parse(d.Objective || '[]');
                    if (obj.includes('2') || obj.includes('3')) {
                        $("#nav-lecturer, #nav-lecturer-tab").removeClass("d-none");
                    }
                } catch (e) {}

                // Checkboxes Arrays
                ['travel', 'CusFood'].forEach(name => {
                    try {
                        $(`[name='${name}[]']`).prop('checked', false);
                        let arr = JSON.parse(d[name.charAt(0).toUpperCase() + name.slice(1)] || '[]');
                        arr.forEach(v => $(`[name='${name}[]'][value='${v}']`).prop('checked', true));
                    } catch (e) {}
                });

                // กรอกข้อมูลอาหาร (บังคับเลือกชุดเดียว)
                try {
                    let foodArr = JSON.parse(d.Food || '[]');

                    if (foodArr.length > 0) {
                        // 1. หาว่าเมนูที่ save มา อยู่ใน Set ไหนบ้าง
                        // โดยการวนลูปเช็คกับ checkbox ทั้งหมดที่มีในตาราง
                        let setCounts = {};
                        let itemToSetMap = {};

                        $('.food-choice').each(function() {
                            const val = $(this).val();
                            const setId = $(this).data('setid');
                            const setName = $(this).data('setname'); // ใช้เช็คเพิ่มเติมถ้าจำเป็น

                            itemToSetMap[val] = setId;
                        });

                        // นับจำนวนว่า foodArr ตรงกับ Set ไหนมากที่สุด
                        foodArr.forEach(val => {
                            const setId = itemToSetMap[val];
                            if (setId) {
                                setCounts[setId] = (setCounts[setId] || 0) + 1;
                            }
                        });

                        // หา Set ที่มีคะแนนสูงสุด (ถือว่าเป็น Active Set)
                        let activeSetId = null;
                        let maxCount = 0;
                        for (const [sid, count] of Object.entries(setCounts)) {
                            if (count > maxCount) {
                                maxCount = count;
                                activeSetId = sid;
                            }
                        }

                        // 2. ทำการ Checked และ Disabled
                        if (activeSetId) {
                            // Checked เฉพาะตัวที่อยู่ใน foodArr AND อยู่ใน activeSet
                            foodArr.forEach(v => {
                                // เช็คว่า value นี้ เป็นของ activeSet จริงไหม (ป้องกันเคส value ซ้ำข้าม set ถ้ามี)
                                if (itemToSetMap[v] == activeSetId) {
                                    $(`.food-choice[value='${v}'][data-setid='${activeSetId}']`).prop('checked', true);
                                }
                            });

                            // Disabled ตัวที่ไม่ได้อยู่ใน Active Set
                            $(`.food-choice[data-setid!='${activeSetId}']`).prop('disabled', true);

                            // Enable ตัวที่อยู่ใน Active Set (เผื่อเลือกเพิ่ม)
                            $(`.food-choice[data-setid='${activeSetId}']`).prop('disabled', false);
                        } else {
                            // Fallback: ถ้าหา Set ไม่เจอ (อาจจะเป็นเมนูเก่าที่ไม่มีแล้ว)
                            // ก็ Checked ตามปกติไปก่อน แต่ไม่ Disable อะไร หรือ Disable หมด?
                            // เอาเป็น Checked ตาม value โดยไม่สน Set (พฤติกรรมเดิม)
                            foodArr.forEach(v => {
                                $(`[name='food[]'][value='${v}']`).prop('checked', true);
                            });
                        }

                    } else {
                        // ถ้าไม่มีข้อมูล (foodArr ว่าง)
                        // ให้ Enable ทั้งหมด รอให้ user เลือกใหม่
                        $('.food-choice').prop('disabled', false).prop('checked', false);
                    }

                    // จัดการ Checkbox ประเภทการทาน (CoffeeBreak, Lunch, EatOutside)
                    if ($('#food_3').is(':checked')) { // รับประทานข้างนอก
                        $('#food_1, #food_2').prop('checked', false);
                        $('.food-choice').prop('disabled', true).prop('checked', false);
                    } else if ($('#food_1').is(':checked') || $('#food_2').is(':checked')) {
                        // ถ้าเลือกทานในบริษัท และยังไม่มี Active Set (คือ foodArr ว่าง) -> เปิดทั้งหมด
                        // ถ้ามี Active Set แล้ว -> โค้ดข้างบนจัดการ Disabled set อื่นไปแล้ว
                        if (foodArr.length === 0) {
                            $('.food-choice').prop('disabled', false);
                        }
                    } else {
                        // ยังไม่เลือกอะไรเลย -> ปิดเมนูทั้งหมดไว้ก่อน
                        $('.food-choice').prop('disabled', true).prop('checked', false);
                    }

                } catch (e) {
                    console.error("Error loading Food:", e);
                }

                // --- 3. Job Cards (SalesDetail) ---
                let salesDetail = [];
                try {
                    salesDetail = JSON.parse(d.SalesDetail || '[]');
                } catch (e) {}
                if (!salesDetail || salesDetail.length === 0) {
                    salesDetail = [{
                        JobNo: d.JobNo,
                        ProjectName: d.ProjectName,
                        SN: d.SN ? JSON.parse(d.SN) : [],
                        WA: d.WA ? JSON.parse(d.WA) : [],
                        TCName: d.TCName,
                        ProductName: d.ProductName,
                        RequesterCode: d.RequesterCode || '',
                        RequesterDept: d.RequesterDept || '',
                        CompanyName: d.CompanyName,
                        GroupCtm: d.Groupctm ? JSON.parse(d.Groupctm) : [],
                        CustomerName: d.CustomerName,
                        Position: d.Position,
                        PhoneNumber: d.PhoneNumber,
                        Zone: [],
                        Detail: d.Detail,
                        CompanyType: 'thai'
                    }];
                }
                await renderJobCards(salesDetail);

                // กรอกข้อมูลฝ่ายสื่อสาร
                let corp = {};
                try {
                    corp = JSON.parse(d.CorporateDetail || '{}');
                } catch (e) {}

                if (corp.serviceType === 'use_service') {
                    $('#corporateUseService').prop('checked', true).trigger('change');
                    $('#corporateDate').val(corp.date);

                    if (corp.usePhotoService) {
                        $('#usePhotoService').prop('checked', true).trigger('change');
                        $('#photoTimeStart').val(corp.photoTimeStart);
                        $('#photoTimeEnd').val(corp.photoTimeEnd);
                        (corp.photoLocations || []).forEach(loc => {
                            if (['showroom', 'MP3', 'MP4'].includes(loc)) $(`#photoLoc${loc}`).prop('checked', true);
                            else {
                                $('#photoLocOther').prop('checked', true).trigger('change');
                                $('#photoLocOtherText').val(loc);
                            }
                        });
                    }

                    if (corp.useWelcomeService) {
                        $('#useWelcomeService').prop('checked', true).trigger('change');
                        $('#welcomeTimeStart').val(corp.welcomeTimeStart);
                        $('#welcomeTimeEnd').val(corp.welcomeTimeEnd);
                        $('#welcomeDetail').val(corp.welcomeDetail);

                        // เลือกหน้าจอแสดงผล
                        if (corp.screens) {
                            // วนลูปผ่านทุก MP (MP1, MP2, MP3, MP4)
                            for (const mpKey of ['MP1', 'MP2', 'MP3', 'MP4']) {
                                if (corp.screens[mpKey] && Array.isArray(corp.screens[mpKey])) {
                                    for (const screenValue of corp.screens[mpKey]) {
                                        // เช็คจากทั้ง value และ id prefix (label for)
                                        // เช่น id="welcomeMP3_LED" มี value="LED Wall"
                                        $(`#welcomeServicePanel input[id^="welcome${mpKey}_"][value="${screenValue}"]`).prop('checked', true);
                                    }
                                }
                            }
                        }
                    }
                }

                // --- 6. Lecturer Detail ---
                let lect = {};
                try {
                    lect = JSON.parse(d.LecturerDetail || '{}');
                } catch (e) {}
                if (lect.lecturerType === 'internal') {
                    $('#lecturerInternal').prop('checked', true).trigger('change');
                    if (lect.lecturers) $('#Lecturer').val(lect.lecturers).trigger('change');
                    (lect.presentTypes || []).forEach(t => {
                        if (t === 'thai') $('#presentThai').prop('checked', true);
                        if (t === 'eng') $('#presentEng').prop('checked', true);
                        if (t === 'chinese') $('#presentChinese').prop('checked', true);
                    });
                } else if (lect.lecturerType === 'external') {
                    $('#lecturerExternal').prop('checked', true).trigger('change');
                    if (lect.externalDetail) {
                        $('#externalLecturerDetail').val(lect.externalDetail);
                    }
                }

                // --- 7. PRD Detail ---
                let prd = {};
                try {
                    prd = JSON.parse(d.PRDDetail || '{}');
                } catch (e) {}
                if (prd.testStatus === 'ready') $('#prdTestReady').prop('checked', true);
                else if (prd.testStatus === 'not_ready') $('#prdTestNotReady').prop('checked', true);
                $('#prdRemark').val(prd.remark);

                // --- 8. Schedule ---
                if (d.Schedule && d.Schedule.length > 0) {
                    $('#table-schedule tbody').empty();
                    d.Schedule.forEach(s => {
                        let newRow = $(`
                        <tr>
                            <td>
                                <select class="form-select reserve">
                                    <option value="meeting" ${s.ReserveType === 'meeting' ? 'selected' : ''}>จองห้องประชุม</option>
                                    <option value="zoom" ${s.ReserveType === 'zoom' ? 'selected' : ''}>จอง Zoom</option>
                                </select>
                            </td>
                            <td width="250">
                                <div class="input-group" style="width: 200px !important;">
                                    <span class="input-group-text">📅</span>
                                    <input class="form-control dateTrival" name="dateTrival[]" type="text" placeholder="dd/mm/yyyy" value="${s.VisitDate}" required>
                                </div>
                            </td>
                            <td><input type="time" class="form-control" name="meeting_time_start[]" value="${s.TimeStart}"></td>
                            <td><input type="time" class="form-control" name="meeting_time_end[]" value="${s.TimeEnd}"></td>
                            <td><select name="meetingroom[]" class="form-select meetingroom"></select></td>
                            <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="ti ti-trash fs-5 me-2"></i>ลบ</button></td>
                        </tr>`);
                        $('#table-schedule tbody').append(newRow);
                        newRow.find('.dateTrival').datepicker({
                            dateFormat: 'dd/mm/yy',
                            appendTo: 'body',
                            minDate: 0
                        });
                        loadMeetingRoom(newRow, s.MeetingRoom);
                    });
                }

                // --- 9. Files ---
                // กำหนดว่าสามารถลบไฟล์ได้หรือไม่ (Status 0, 1, 4 และมีสิทธิ์)
                const canDeleteFiles = (currentStatus === 0 || currentStatus === 1 || currentStatus === 4) &&
                    (IS_ADMIN || DIVISION_HEAD1 == 63);
                await loadVisitorFiles(visitorId, canDeleteFiles);

                // --- 10. Comment History (Timeline Style) ---
                if ((currentStatus === 4 || currentStatus === 9) && d.Comment) {
                    try {
                        const comments = JSON.parse(d.Comment);
                        if (Array.isArray(comments) && comments.length > 0) {
                            let timelineHtml = '<div class="timeline">';
                            comments.forEach(c => {
                                const isReject = c.status === 4;
                                const isCancel = c.status === 9;
                                // const dotClass = isReject ? 'reject' : (isCancel ? 'cancel' : 'info'); // ใช้ร่วมกับ itemClass
                                const itemClass = isReject ? 'reject' : (isCancel ? 'cancel' : 'info');
                                const statusText = isReject ? 'Rework' : (isCancel ? 'ยกเลิก' : 'Info');
                                const iconClass = isReject ? 'ti-alert-triangle' : (isCancel ? 'ti-ban' : 'ti-info-circle');

                                timelineHtml += `
                                    <div class="timeline-item ${itemClass}">
                                        <div class="timeline-dot ${itemClass}">
                                            <i class="ti ${iconClass}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <span class="timeline-user"><i class="ti ti-user me-1"></i>${c.user || 'Unknown'}</span>
                                                <span class="text-muted"><i class="ti ti-calendar me-1"></i>${c.date || ''}</span>
                                            </div>
                                            <div class="timeline-body">
                                                <div class="mb-1"><span class="badge ${isReject ? 'bg-danger' : (isCancel ? 'bg-secondary' : 'bg-primary')}">${statusText}</span></div>
                                                <div>${c.text || '-'}</div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            timelineHtml += '</div>';

                            $('#comment-content').html(timelineHtml);
                            $('#comment-history').slideDown(300);
                        }
                    } catch (e) {
                        console.error('Error parsing Comment:', e);
                    }
                }

                // --- 11. Buttons & Action Bar Logic ---

                const $actionBar = $('#action-bar-container');

                // Reset Action Bar และแสดง card
                $actionBar.empty();
                $('#ack-divisions').empty();
                $('#acknowledge-status').show();

                // Helper for button generation
                let buttons = '';
                const isMKTHead = IS_ADMIN || DIVISION_HEAD1 == 63;
                const isApprover = IS_ADMIN || CURRENT_USER_CODE === APPROVER_CODE;

                if (currentStatus === 3 || currentStatus === 5 || currentStatus === 6 || currentStatus === 9) {
                    setFormReadOnly(true);
                    const ackResult = await loadAcknowledgeStatus(d.Id);
                    const allDone = renderAcknowledgeStatus(ackResult, currentStatus, d.Id);

                    // เก็บ ID สำหรับ PRD Edit
                    currentVisitorFormId = d.Id;

                    // เปิด PRD fields ให้แก้ไขได้เมื่อวางแผน(61) รับทราบแล้ว และยังไม่ปิดงาน
                    const prdAcked = ackResult && ackResult.data && ackResult.data.find(item => item.division_code == 61 && item.is_acknowledged);
                    if (prdAcked && currentStatus !== 6 && currentStatus !== 9) {
                        enablePRDEdit();
                    }

                    if (currentStatus !== 6 && currentStatus !== 9) {
                        // Cancel Button
                        if (isMKTHead) {
                            buttons += `<button class="btn btn-danger shadow-sm me-2" type="button" onclick="openCommentModal(${d.Id}, 9, 'ระบุเหตุผลการยกเลิก')"><i class="ti ti-ban me-1"></i>ยกเลิก</button>`;
                        }

                        // Close Job Button
                        if (allDone && isMKTHead) {
                            buttons += `<button class="btn btn-dark shadow-sm text-white me-2" type="button" onclick="closeJob(${d.Id})"><i class="ti ti-check-all me-1"></i>ปิดงาน</button>`;
                        }

                        // ปุ่มเชิญลูกค้า (สำหรับ MKT Head)
                        if (isMKTHead) {
                            buttons += `<button class="btn btn-info shadow-sm text-white me-2" type="button" onclick="openInviteModal(${d.Id})"><i class="ti ti-mail-forward me-1"></i>เชิญลูกค้า</button>`;
                        }
                    } else if (currentStatus === 6) {
                        buttons = `<span class="badge bg-dark fs-6 py-2 px-4 shadow-sm me-2"><i class="ti ti-check-all me-2"></i>ปิดงานแล้ว</span>`;
                        buttons += `<button class="btn btn-primary shadow-sm text-white" type="button" onclick="openEvalQRModal(${d.Id})"><i class="fa-solid fa-qrcode me-1"></i>ลูกค้าประเมิน</button>`;
                    } else if (currentStatus === 9) {
                        buttons = `<span class="badge bg-danger fs-6 py-2 px-4 shadow-sm me-2"><i class="ti ti-ban me-2"></i>ยกเลิกแล้ว</span>`;
                        buttons += `<button class="btn btn-outline-primary shadow-sm" type="button" onclick="copyVisitorForm(${d.Id})"><i class="ti ti-copy me-1"></i>คัดลอกข้อมูล</button>`;
                    } else if (!buttons) {
                        buttons = `<span class="text-muted fw-bold"><i class="ti ti-clock me-1"></i>รอฝ่ายที่เกี่ยวข้องดำเนินการ...</span>`;
                    }

                } else if (currentStatus === 0) {
                    buttons = `
                        <button class="btn btn-secondary shadow-sm me-2" type="button" onclick="update_visitor_form(${d.Id}, 0)"><i class="ti ti-device-floppy me-1"></i>บันทึกแบบร่าง</button>
                        <button class="btn btn-success shadow-sm text-white" type="button" onclick="update_visitor_form(${d.Id}, 1)"><i class="ti ti-send me-1"></i>ส่งแบบฟอร์ม</button>
                    `;
                } else {
                    // Status 1, 2, 4
                    if (currentStatus === 1 || currentStatus === 4) {
                        if (isMKTHead) {
                            buttons += `<button class="btn btn-danger shadow-sm me-auto" type="button" onclick="openCommentModal(${d.Id}, 9, 'ระบุเหตุผลการยกเลิก')"><i class="ti ti-ban me-1"></i>ยกเลิก</button>`;
                            buttons += `<div class="ms-auto d-flex gap-2">`;
                            buttons += `<button class="btn btn-primary shadow-sm text-white" type="button" onclick="update_visitor_form(${d.Id}, ${currentStatus})"><i class="ti ti-device-floppy me-1"></i>บันทึกแก้ไข</button>`;
                            buttons += `<button class="btn btn-success shadow-sm text-white" type="button" onclick="update_visitor_form(${d.Id}, 2)"><i class="ti ti-send me-1"></i>ส่งตรวจสอบ</button>`;
                            buttons += `</div>`;
                        } else {
                            buttons = `<span class="badge bg-secondary fs-6 py-2 px-3">ไม่มีสิทธิ์ดำเนินการ</span>`;
                        }
                    } else if (currentStatus === 2) {
                        if (isApprover) {
                            buttons += `<button class="btn btn-danger shadow-sm me-auto" type="button" onclick="openCommentModal(${d.Id}, 9, 'ระบุเหตุผลการยกเลิก')"><i class="ti ti-ban me-1"></i>ยกเลิก</button>`;
                            buttons += `<div class="ms-auto d-flex gap-2">`;
                            buttons += `<button class="btn btn-warning shadow-sm" type="button" onclick="openCommentModal(${d.Id}, 4, 'ระบุเหตุผลการ Rework')"><i class="ti ti-arrow-back-up me-1"></i>Rework</button>`;
                            buttons += `<button class="btn btn-success shadow-sm text-white" type="button" onclick="approveDocument(${d.Id})"><i class="ti ti-check me-1"></i>อนุมัติ</button>`;
                            buttons += `</div>`;
                        } else {
                            buttons = `<span class="badge bg-info fs-6 py-2 px-3">รอการอนุมัติ</span>`;
                        }
                    }
                }

                $actionBar.html(buttons);

                // Header
                // Header (Use Global status_color/status_text from js.php)
                const sColor = (typeof status_color !== 'undefined' && status_color[currentStatus]) ? status_color[currentStatus] : '#6c757d';
                const sText = (typeof status_text !== 'undefined' && status_text[currentStatus]) ? status_text[currentStatus] : 'Unknown';
                const badgeHtml = `<span class="badge text-white ms-2" style="background-color: ${sColor}">${sText}</span>`;

                document.querySelector('.h4').innerHTML = `แก้ไขแบบฟอร์ม <span class="badge bg-secondary ms-2">${d.DocNo}</span> ${badgeHtml}`;

                Swal.close();

            } catch (e) {
                console.error(e);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load visitor data'
                });
            }
        }

        async function renderJobCards(salesDetail) {
            // Save salesDetail to window for invite modal access
            window.currentSalesDetail = salesDetail;

            if (!salesDetail || salesDetail.length === 0) return;

            // Use only the first job since multi-job support has been removed
            const job = salesDetail[0];
            const $card = $('#nav-home .card-body');

            // Populate Text fields (Standard)
            $card.find('[data-field="JobNo"]').val(job.JobNo);
            $card.find('[data-field="ProjectName"]').val(job.ProjectName);
            $card.find('[data-field="CompanyName"]').val(job.CompanyName);
            $card.find('[data-field="ObjectiveOther"]').val(job.ObjectiveOther || '');
            $card.find('[data-field="Detail"]').val(job.Detail || '');

            // Populate Selects (Standard)
            $card.find('.job-select-requester').val(job.RequesterCode).trigger('change');
            $card.find('[data-field="RequesterDept"]').val(requesterDeptMap[job.RequesterCode] || job.RequesterDept || '');
            $card.find('.job-select-tc').val(job.TCName).trigger('change');

            // Populate Objective (Multiple)
            if (job.Objective) {
                const $obj = $card.find('.job-select-objective');
                let objVal = job.Objective;
                if (typeof objVal === 'string') {
                    try {
                        objVal = JSON.parse(objVal);
                    } catch (e) {
                        objVal = [objVal];
                    }
                }
                if (!Array.isArray(objVal)) objVal = [objVal];
                $obj.val(objVal).trigger('change');
            }

            // Populate Contact Fields (Select2 Tags) - Support JSON Array for multiple
            const populateTagSelect = ($sel, val) => {
                if (!val) return;

                let values = val;
                if (typeof val === 'string') {
                    try {
                        if (val.startsWith('[')) {
                            values = JSON.parse(val);
                        } else {
                            values = [val];
                        }
                    } catch (e) {
                        values = [val];
                    }
                }
                if (!Array.isArray(values)) values = [values];

                values.forEach(v => {
                    if (v && $sel.find(`option[value="${v}"]`).length === 0) {
                        $sel.append(new Option(v, v, true, true));
                    }
                });
                $sel.val(values).trigger('change');
            };

            populateTagSelect($card.find('.job-select-contact'), job.CustomerName);
            populateTagSelect($card.find('.job-select-position'), job.Position);
            populateTagSelect($card.find('.job-select-phone'), job.PhoneNumber);
            populateTagSelect($card.find('.job-select-email'), job.Emails);

            // ProductName: Parse if JSON string
            let productVal = job.ProductName;
            try {
                if (typeof productVal === 'string' && (productVal.startsWith('[') || productVal.startsWith('{'))) {
                    productVal = JSON.parse(productVal);
                }
            } catch (e) { /* use default value */ }
            $card.find('.job-select-product').val(productVal).trigger('change');

            // Arrays (SN, WA)
            if (job.SN && Array.isArray(job.SN)) {
                const $sn = $card.find('.job-select-sn');
                job.SN.forEach(v => {
                    if ($sn.find(`option[value='${v}']`).length === 0) $sn.append(new Option(v, v, true, true));
                });
                $sn.val(job.SN).trigger('change');
            }
            if (job.WA && Array.isArray(job.WA)) {
                const $wa = $card.find('.job-select-wa');
                job.WA.forEach(v => {
                    if ($wa.find(`option[value='${v}']`).length === 0) $wa.append(new Option(v, v, true, true));
                });
                $wa.val(job.WA).trigger('change');
            }
            if (job.GroupCtm && Array.isArray(job.GroupCtm)) {
                $card.find('.job-select-groupctm').val(job.GroupCtm).trigger('change');
            }
            if (job.Zone && Array.isArray(job.Zone)) {
                $card.find('.job-select-zone').val(job.Zone).trigger('change');
            }

            // Radios
            if (job.CompanyType === 'foreign') $card.find(`input[value="foreign"][type="radio"]`).prop('checked', true);
            else $card.find(`input[value="thai"][type="radio"]`).prop('checked', true);

            // Fetch CRM WA/SN data if CompanyName is present
            if (job.CompanyName) {
                fetchCRMData(job.CompanyName, $card);
            }
        }

        async function loadVisitorFiles(id, canDelete = false) {
            try {
                const res = await fetch(`api/visitor_files_get.php?id=${id}`);
                const data = await res.json();
                if (!data.status) return;

                const detailList = document.getElementById('fileList_detail');
                const lecturerList = document.getElementById('fileList_lecturer');

                data.data.forEach(f => {
                    const list = f.FileType === 'detail' ? detailList : lecturerList;
                    const div = document.createElement('div');
                    div.className = 'file-item p-2 mb-2 border rounded d-flex justify-content-between align-items-center bg-light';

                    let deleteBtn = '';
                    if (canDelete) {
                        deleteBtn = `<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteExistingFile(${f.Id}, this)">
                                        <i class="ti ti-trash"></i> ลบ
                                     </button>`;
                    } else {
                        deleteBtn = `<span class="badge bg-secondary">Existing</span>`;
                    }

                    div.innerHTML = `
                         <div class="d-flex align-items-center gap-2">
                            <span>${getFileIcon(f.FileName)}</span>
                            <a href="file/${f.FilePath}" target="_blank" class="text-decoration-none text-dark text-truncate" style="max-width:200px;">${f.FileName}</a>
                         </div>
                         ${deleteBtn}
                    `;
                    list.appendChild(div);
                });
            } catch (e) {}
        }
    </script>

    <!-- Customer Evaluation QR Modal -->
    <div class="modal fade" id="evalQRModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-qrcode me-2"></i>QR Code ลูกค้าประเมิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div id="qrCodeContainer" class="mb-3">
                        <div id="qrCodeDiv" class="d-flex justify-content-center">

                        </div>
                    </div>
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

    <!-- Invite Customer Modal -->
    <div class="modal fade" id="inviteCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="ti ti-mail-forward me-2"></i>เชิญลูกค้า</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="text-muted mb-3">จัดการข้อมูลผู้ติดต่อที่ต้องการเชิญ</p>
                    <div class="table-responsive">
                        <table class="table table-hover" id="inviteContactsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%;">ชื่อลูกค้า</th>
                                    <th style="width: 20%;">ตำแหน่ง</th>
                                    <th style="width: 20%;">เบอร์โทร</th>
                                    <th style="width: 25%;">อีเมล</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="inviteContactsBody">
                                <!-- Dynamic rows -->
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addInviteRow()">
                        <i class="ti ti-plus me-1"></i>เพิ่มผู้ติดต่อ
                    </button>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>ยกเลิก
                    </button>
                    <button class="btn btn-warning" type="button" onclick="saveInviteData()">
                        <i class="ti ti-device-floppy me-1"></i>บันทึกแก้ไข
                    </button>
                    <button class="btn btn-primary" type="button" onclick="sendInvitation()">
                        <i class="ti ti-send me-1"></i>เชิญ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // === Invite Customer Modal Logic ===
        let currentInviteId = null;
        let currentSalesDetailIndex = 0; // ใช้ job card แรก (index 0)

        async function openInviteModal(visitorId) {
            currentInviteId = visitorId;
            const $tbody = $('#inviteContactsBody').empty();

            try {
                // ดึงข้อมูล SalesDetail จาก form ที่โหลดแล้ว
                const salesDetail = window.currentSalesDetail || [];
                const job = salesDetail[currentSalesDetailIndex] || {};

                // Parse JSON fields
                const names = parseJsonField(job.CustomerName);
                const positions = parseJsonField(job.Position);
                const phones = parseJsonField(job.PhoneNumber);
                const emails = parseJsonField(job.Emails);

                // สร้าง rows
                const maxLen = Math.max(names.length, positions.length, phones.length, emails.length, 1);
                for (let i = 0; i < maxLen; i++) {
                    addInviteRowData(
                        names[i] || '',
                        positions[i] || '',
                        phones[i] || '',
                        emails[i] || ''
                    );
                }

                $('#inviteCustomerModal').modal('show');
            } catch (e) {
                console.error('Error loading invite data:', e);
                Swal.fire('Error', 'ไม่สามารถโหลดข้อมูลลูกค้าได้', 'error');
            }
        }

        function parseJsonField(val) {
            if (!val) return [];
            if (Array.isArray(val)) return val;
            if (typeof val === 'string') {
                try {
                    if (val.startsWith('[')) return JSON.parse(val);
                    return [val];
                } catch (e) {
                    return [val];
                }
            }
            return [];
        }

        function addInviteRow() {
            addInviteRowData('', '', '', '');
        }

        function addInviteRowData(name, position, phone, email) {
            const row = `
                <tr>
                    <td><input type="text" class="form-control form-control-sm invite-name" value="${escapeHtml(name)}" placeholder="ชื่อลูกค้า"></td>
                    <td><input type="text" class="form-control form-control-sm invite-position" value="${escapeHtml(position)}" placeholder="ตำแหน่ง"></td>
                    <td><input type="text" class="form-control form-control-sm invite-phone" value="${escapeHtml(phone)}" placeholder="เบอร์โทร"></td>
                    <td><input type="email" class="form-control form-control-sm invite-email" value="${escapeHtml(email)}" placeholder="อีเมล"></td>
                    <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="$(this).closest('tr').remove()"><i class="ti ti-trash"></i></button></td>
                </tr>
            `;
            $('#inviteContactsBody').append(row);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.toString().replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[m]);
        }

        function collectInviteData() {
            const names = [],
                positions = [],
                phones = [],
                emails = [];
            $('#inviteContactsBody tr').each(function() {
                const name = $(this).find('.invite-name').val().trim();
                const position = $(this).find('.invite-position').val().trim();
                const phone = $(this).find('.invite-phone').val().trim();
                const email = $(this).find('.invite-email').val().trim();
                if (name || email) { // ต้องมีชื่อหรืออีเมลอย่างน้อย
                    names.push(name);
                    positions.push(position);
                    phones.push(phone);
                    emails.push(email);
                }
            });
            return {
                names,
                positions,
                phones,
                emails
            };
        }

        async function saveInviteData() {
            const data = collectInviteData();
            if (data.names.length === 0) {
                Swal.fire('Warning', 'กรุณาเพิ่มข้อมูลผู้ติดต่ออย่างน้อย 1 คน', 'warning');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'update_contacts');
                formData.append('visitor_form_id', currentInviteId);
                formData.append('CustomerName', JSON.stringify(data.names));
                formData.append('Position', JSON.stringify(data.positions));
                formData.append('PhoneNumber', JSON.stringify(data.phones));
                formData.append('Emails', JSON.stringify(data.emails));

                const res = await fetch('./api/send_invitation.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();

                if (result.success) {
                    Swal.fire('Success', 'บันทึกข้อมูลสำเร็จ', 'success');
                    // อัปเดต currentSalesDetail
                    if (window.currentSalesDetail && window.currentSalesDetail[currentSalesDetailIndex]) {
                        window.currentSalesDetail[currentSalesDetailIndex].CustomerName = JSON.stringify(data.names);
                        window.currentSalesDetail[currentSalesDetailIndex].Position = JSON.stringify(data.positions);
                        window.currentSalesDetail[currentSalesDetailIndex].PhoneNumber = JSON.stringify(data.phones);
                        window.currentSalesDetail[currentSalesDetailIndex].Emails = JSON.stringify(data.emails);
                    }
                } else {
                    throw new Error(result.message || 'Unknown error');
                }
            } catch (e) {
                console.error('Save invite data error:', e);
                Swal.fire('Error', 'ไม่สามารถบันทึกข้อมูลได้: ' + e.message, 'error');
            }
        }

        async function sendInvitation() {
            const data = collectInviteData();
            const validEmails = data.emails.filter(e => e && e.includes('@'));

            if (validEmails.length === 0) {
                Swal.fire('Warning', 'กรุณาระบุอีเมลที่ถูกต้องอย่างน้อย 1 รายการ', 'warning');
                return;
            }

            const confirm = await Swal.fire({
                title: 'ยืนยันการส่งเชิญ',
                html: `จะส่งอีเมลเชิญไปที่:<br><strong>${validEmails.join(', ')}</strong>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ส่งเชิญ',
                cancelButtonText: 'ยกเลิก'
            });

            if (!confirm.isConfirmed) return;

            try {
                Swal.fire({
                    title: 'กำลังส่งอีเมล...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const formData = new FormData();
                formData.append('action', 'send_invitation');
                formData.append('visitor_form_id', currentInviteId);
                formData.append('CustomerName', JSON.stringify(data.names));
                formData.append('Position', JSON.stringify(data.positions));
                formData.append('PhoneNumber', JSON.stringify(data.phones));
                formData.append('Emails', JSON.stringify(data.emails));

                const res = await fetch('./api/send_invitation.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();

                if (result.success) {
                    Swal.fire('Success', `ส่งอีเมลเชิญสำเร็จ ${result.sent_count || validEmails.length} รายการ`, 'success');
                    $('#inviteCustomerModal').modal('hide');
                } else {
                    throw new Error(result.message || 'Unknown error');
                }
            } catch (e) {
                console.error('Send invitation error:', e);
                Swal.fire('Error', 'ไม่สามารถส่งอีเมลได้: ' + e.message, 'error');
            }
        }
    </script>


    <script>
        let currentEvalUrl = '';
        function copyVisitorForm(id) {
            Swal.fire({
                title: 'คัดลอกเอกสาร?',
                text: 'ระบบจะสร้างเอกสารใหม่โดยคัดลอกข้อมูลจากเอกสารนี้',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'คัดลอก',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = './visitorform.php?copy_id=' + id;
                }
            });
        }

        let qrCodeInstance = null;

        function openEvalQRModal(visitorId) {
            currentEvalUrl = `https://it.asefa.co.th/visitorMKT/visi_question.php?id=${visitorId}`;
            $('#evalUrlInput').val(currentEvalUrl);

            // Clear previous QR
            $('#qrCodeDiv').empty();

            // Generate QR Code using qrcodejs
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
                Swal.fire({
                    icon: 'success',
                    title: 'คัดลอกลิงก์แล้ว!',
                    timer: 1500,
                    showConfirmButton: false
                });
            }).catch(err => {
                // Fallback for older browsers
                const input = document.getElementById('evalUrlInput');
                input.select();
                document.execCommand('copy');
                Swal.fire({
                    icon: 'success',
                    title: 'คัดลอกลิงก์แล้ว!',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }
    </script>

</body>

</html>