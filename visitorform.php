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





        /* === Schedule Booking Card — Modern SaaS === */
        #schedule-container { display: flex; flex-direction: column; gap: 8px; }
        .schedule-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-left: 3px solid #6366f1;
            border-radius: 10px;
            padding: 12px 14px;
            transition: box-shadow .2s, border-left-color .25s;
        }
        .schedule-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.07); }
        .schedule-card.is-zoom { border-left-color: #0ea5e9; }
        .schedule-card .schedule-label {
            font-size: .68rem; font-weight: 600; color: #9ca3af;
            text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px;
        }
        .scard-header {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #f3f4f6;
        }
        .reserve-type-wrap {
            display: inline-flex; align-items: center; gap: 6px;
            background: #f9fafb; border: 1px solid #e5e7eb;
            border-radius: 8px; padding: 4px 10px 4px 6px; flex-shrink: 0;
        }
        .reserve-type-wrap .reserve-icon {
            width: 26px; height: 26px; border-radius: 6px;
            display: inline-flex; align-items: center; justify-content: center;
            color: white; transition: background .2s; flex-shrink: 0;
        }
        .reserve-type-wrap .reserve {
            border: none !important; background: transparent !important;
            padding: 0 4px !important; font-size: .83rem !important;
            font-weight: 500 !important; color: #374151 !important;
            box-shadow: none !important; min-width: 90px;
        }
        .zoom-type-row {
            overflow: hidden; max-width: 0; opacity: 0;
            transition: max-width .25s ease, opacity .2s ease;
            pointer-events: none; display: flex !important;
            align-items: center; gap: 4px; flex-shrink: 0; white-space: nowrap;
        }
        .zoom-type-row.visible { max-width: 300px; opacity: 1; pointer-events: auto; }
        .zoom-type-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 12px; border-radius: 20px; border: 1.5px solid #e5e7eb;
            cursor: pointer; font-size: .78rem; font-weight: 500; color: #6b7280;
            transition: all .15s; user-select: none; margin: 0;
        }
        .zoom-type-pill.active { background: #e0f2fe; border-color: #0ea5e9; color: #0284c7; font-weight: 600; }
        .zoom-type-pill input { display: none; }
        .scard-delete-btn {
            color: #d1d5db; background: transparent; border: none;
            padding: 4px 6px; border-radius: 6px;
            transition: color .15s, background .15s; line-height: 1;
        }
        .scard-delete-btn:hover { color: #ef4444; background: #fef2f2; }
        .schedule-add-btn {
            margin-top: 1rem;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; padding: 9px; border: 1.5px dashed #d1d5db;
            border-radius: 10px; background: transparent; color: #6b7280;
            font-size: .85rem; font-weight: 500; cursor: pointer;
            transition: border-color .2s, background .2s, color .2s;
        }
        .schedule-add-btn:hover { border-color: #6366f1; background: #f5f3ff; color: #6366f1; }

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

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-secondary fs-6" type="button" onclick="save_visitor_form(0)"><i class="ti ti-device-floppy me-1"></i>บันทึกแบบร่าง</button>
                <button class="btn btn-success fs-6 text-white" type="button" onclick="save_visitor_form(1)"><i class="ti ti-send me-1"></i>ส่งแบบฟอร์ม</button>
            </div>
        </div>
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
                                            <!-- Row 0: Objective -->
                                            <div class="form-group-clean full-width checkbox-group">
                                                <label><i class="ti ti-message-user"></i>วัตถุประสงค์การเยี่ยมชม</label>
                                                <select class="form-select job-field job-select-objective" data-field="Objective" multiple></select>
                                            </div>
                                            <div class="form-group-clean full-width box-objective-other" style="display: none;">
                                                <label></label>
                                                <input type="text" class="form-control job-field" data-field="ObjectiveOther" placeholder="ระบุวัตถุประสงค์อื่นๆ">
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

                                            <div class="form-group-clean">
                                                <label><i class="ti ti-user-dollar"></i>ผู้ร้องขอ</label>
                                                <select class="form-select job-field job-select-requester" data-field="RequesterCode">
                                                </select>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-vector-bezier"></i>แผนก</label>
                                                <input type="text" class="form-control job-field" data-field="RequesterDept" placeholder="แผนก" readonly>
                                            </div>

                                            <!-- Row 1: Job No & Project Name (multi-row) -->
                                            <div class="form-group-clean" style="grid-column: 1 / -1;">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <label class="mb-0"><i class="ti ti-dialpad"></i>JOB No. & ชื่อโครงการ</label>
                                                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-job-no">
                                                        <i class="ti ti-plus me-1"></i>เพิ่ม Job No.
                                                    </button>
                                                </div>
                                                <div class="job-items-container d-flex flex-column gap-2">
                                                    <div class="job-item-row d-flex gap-2 align-items-center">
                                                        <select class="form-select job-select-jobno" style="width: 200px; flex-shrink: 0;"></select>
                                                        <input type="text" class="form-control job-projectname" placeholder="ชื่อโครงการ">
                                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-job-row" style="display:none; flex-shrink:0;">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Row 2: SN & WA -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-hash"></i>WA No.</label>
                                                <select class="form-select job-field job-select-wa selectable" data-field="WA" multiple></select>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-barcode"></i>Switchboard/Serial</label>
                                                <select class="form-select job-field job-select-sn selectable" data-field="SN" multiple></select>
                                            </div>

                                            <!-- Row 3: TC & Product -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-user-cog"></i>TC ผู้ดูแล</label>
                                                <select class="form-select job-field job-select-tc" data-field="TCName">
                                                </select>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-archive"></i>ชื่อผลิตภัณฑ์</label>
                                                <select class="form-select job-field job-select-product" data-field="ProductName" multiple>
                                                    <option value="AMS">AMS</option>
                                                    <option value="MV">MV</option>
                                                    <option value="PRISMA">PRISMA</option>
                                                    <option value="BS">BS</option>
                                                </select>
                                            </div>

                                            <!-- Row 4: Requester & Department -->

                                            <!-- Row 6: Customer Name & Position -->
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-user-bitcoin"></i>ชื่อลูกค้า (ผู้ประสานงาน)</label>
                                                <select class="form-select job-field job-select-contact" data-field="CustomerName" multiple>
                                                </select>
                                            </div>
                                            <div class="form-group-clean">
                                                <label><i class="ti ti-reorder"></i>ตําแหน่ง</label>
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

                    <div class="d-flex align-items-center gap-2 mt-3 mb-3">
                        <i class="ti ti-calendar-event fs-5" style="color:#6366f1"></i>
                        <span class="fw-semibold text-body">กรุณาระบุวันที่เยี่ยม</span>
                    </div>

                    <div id="schedule-container">
                        <div class="schedule-card">
                            <div class="scard-header">
                                <div class="reserve-type-wrap">
                                    <span class="reserve-icon" style="background:#6366f1">
                                        <i class="ti ti-building fs-6"></i>
                                    </span>
                                    <select class="form-select reserve">
                                        <option value="meeting">ห้องประชุม</option>
                                        <option value="zoom">Zoom</option>
                                    </select>
                                </div>
                                <div class="zoom-type-row">
                                    <label class="zoom-type-pill active">
                                        <input class="zoom-type-radio" type="radio" name="zt_0" value="Meeting" checked> Meeting
                                    </label>
                                    <label class="zoom-type-pill">
                                        <input class="zoom-type-radio" type="radio" name="zt_0" value="Seminar"> Seminar
                                    </label>
                                </div>
                                <button type="button" class="btn scard-delete-btn removeRow ms-auto">
                                    <i class="ti ti-trash fs-5"></i>
                                </button>
                            </div>
                            <div class="row g-2 align-items-end">
                                <div class="col-12 col-md-4">
                                    <div class="schedule-label">หัวข้อการประชุม</div>
                                    <input type="text" class="form-control" name="meeting_subject[]" placeholder="ระบุหัวข้อการประชุม...">
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="schedule-label">วันที่เยี่ยม</div>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ti ti-calendar fs-5"></i></span>
                                        <input class="form-control dateTrival" name="dateTrival[]" type="text" placeholder="dd/mm/yyyy" required>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="schedule-label">เวลาเริ่ม</div>
                                    <input type="time" class="form-control" name="meeting_time_start[]" value="08:30" min="08:30" max="17:30">
                                </div>
                                <div class="col-6 col-md-1">
                                    <div class="schedule-label">ถึงเวลา</div>
                                    <input type="time" class="form-control" name="meeting_time_end[]" value="17:30" min="08:30" max="17:30">
                                </div>
                                <div class="col-12 col-md-2">
                                    <div class="schedule-label">ห้อง / Zoom</div>
                                    <select name="meetingroom[]" class="form-select meetingroom">
                                        <option value="">-- เลือกเวลาก่อน --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="zoom-url-row d-none mt-2 pt-2" style="border-top:1px dashed #bae6fd">
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-nowrap" style="font-size:.68rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em">Zoom Link</small>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control zoom-url-input" readonly style="font-size:11.5px">
                                        <button class="btn btn-outline-primary btn-sm zoom-url-copy" type="button"><i class="ti ti-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="schedule-add-btn" id="addSchedule">
                        <i class="ti ti-plus fs-5"></i> เพิ่มวันที่เยี่ยมชม
                    </button>
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
                            <div id="corporateServicePanel" class="mb-3" style="display: none;">
                                <div id="corporateBoxContainer">
                                    <div class="corporate-box border rounded p-3 mb-2" style="background: #fafafa; position:relative;">
                                        <button type="button" class="btn btn-sm btn-outline-danger corporate-box-remove" style="position:absolute;top:8px;right:8px;display:none;"><i class="ti ti-trash"></i></button>
                                        <!-- 2.1 เลือกวันที่ช่วงเวลา -->
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label fw-semibold">
                                                <i class="ti ti-calendar fs-5 me-1"></i>วันที่
                                            </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control corporate-date" placeholder="dd/mm/yyyy">
                                            </div>
                                        </div>

                                        <!-- 2.2 ขอใช้บริการถ่ายรูป -->
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input corp-usePhotoService" type="checkbox">
                                                    <label class="form-check-label fw-semibold">
                                                        <i class="ti ti-camera fs-5 me-1"></i>ขอใช้บริการถ่ายรูป
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="corp-photoServicePanel ms-4 border-start ps-3 mb-3" style="display: none;">
                                            <div class="row mb-2">
                                                <label class="col-sm-2 col-form-label">ช่วงเวลาถ่ายรูป</label>
                                                <div class="col-sm-3">
                                                    <input type="time" class="form-control corp-photoTimeStart">
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="time" class="form-control corp-photoTimeEnd">
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label class="col-sm-2 col-form-label">สถานที่ถ่ายรูป</label>
                                                <div class="col-sm-10 d-flex flex-wrap gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input corp-photoLoc" type="checkbox" value="showroom">
                                                        <label class="form-check-label">Showroom</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input corp-photoLoc" type="checkbox" value="MP3">
                                                        <label class="form-check-label">MP3</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input corp-photoLoc" type="checkbox" value="MP4">
                                                        <label class="form-check-label">MP4</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input corp-photoLoc corp-photoLocOther" type="checkbox" value="other">
                                                        <label class="form-check-label">อื่นๆ</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2 corp-photoLocOtherInput" style="display: none;">
                                                <label class="col-sm-2 col-form-label"></label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control corp-photoLocOtherText" placeholder="ระบุสถานที่อื่นๆ">
                                                </div>
                                            </div>
                                            <small class="text-warning"><i class="ti ti-info-circle me-1"></i>หมายเหตุ: กรุณาติดต่อโทร 3CX 6355 เมื่อถึงช่วงเวลาถ่าย</small>
                                        </div>

                                        <!-- 2.3 Welcome -->
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input corp-useWelcomeService" type="checkbox">
                                                    <label class="form-check-label fw-semibold">
                                                        <i class="ti ti-message fs-5 me-1"></i>Welcome (ป้ายต้อนรับ)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="corp-welcomeServicePanel ms-4 border-start ps-3 mb-3" style="display: none;">
                                            <div class="row mb-2">
                                                <label class="col-sm-2 col-form-label">ช่วงเวลา</label>
                                                <div class="col-sm-3">
                                                    <input type="time" class="form-control corp-welcomeTimeStart">
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="time" class="form-control corp-welcomeTimeEnd">
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label class="col-sm-2 col-form-label">รายละเอียด</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control corp-welcomeDetail" rows="2"></textarea>
                                                </div>
                                            </div>
                                            <small class="text-muted mb-2"><i class="ti ti-info-circle me-1"></i>หมายเหตุ: ระบุชื่อบริษัท / ชื่อ-นามสกุลลูกค้า / ตำแหน่ง / อื่นๆ โปรดระบุ</small>

                                            <div class="mt-3">
                                                <label class="col-form-label fw-semibold">เลือกหน้าจอแสดงผล:</label>
                                                <div class="border rounded p-2 mb-2">
                                                    <label class="fw-semibold d-block mb-2">MP1</label>
                                                    <div class="ms-3 d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="TV MP1" data-mp="MP1">
                                                            <label class="form-check-label">TV MP1</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border rounded p-2 mb-2">
                                                    <label class="fw-semibold d-block mb-2">MP2</label>
                                                    <div class="ms-3 d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="TV ห้องอาหาร" data-mp="MP2">
                                                            <label class="form-check-label">TV ห้องอาหาร</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="TV VIP1" data-mp="MP2">
                                                            <label class="form-check-label">TV VIP1</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="TV VIP2" data-mp="MP2">
                                                            <label class="form-check-label">TV VIP2</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border rounded p-2 mb-2">
                                                    <label class="fw-semibold d-block mb-2">MP3</label>
                                                    <div class="ms-3 d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="TV บริเวณโซฟา" data-mp="MP3">
                                                            <label class="form-check-label">TV บริเวณโซฟา</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="TV Room1" data-mp="MP3">
                                                            <label class="form-check-label">TV Room1</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="TV Room2" data-mp="MP3">
                                                            <label class="form-check-label">TV Room2</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="TV Room3" data-mp="MP3">
                                                            <label class="form-check-label">TV Room3</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="LED Wall" data-mp="MP3">
                                                            <label class="form-check-label">LED Wall</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border rounded p-2 mb-2">
                                                    <label class="fw-semibold d-block mb-2">MP4</label>
                                                    <div class="ms-3 d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input corp-screen" type="checkbox" value="LED Wall" data-mp="MP4">
                                                            <label class="form-check-label">LED Wall</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddCorpDay"><i class="ti ti-calendar-plus me-1"></i>เพิ่มวัน</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAddCorpClone"><i class="ti ti-copy me-1"></i>เพิ่มข้อมูลเดิม</button>
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
                                    <label class="col-sm-2 col-form-label"><i class="ti ti-user-plus fs-5 me-2"></i>รายละเอียด</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="externalLecturerDetail" rows="4" placeholder="กรุณาระบุรายละเอียด เช่น ชื่อ-นามสกุล, สังกัด, หัวข้อบรรยาย"></textarea>
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
                        <h6 class="card-header py-3" style="background-color: #10B981; color: white;">ฝ่ายวางแผน</h6>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="col-form-label fw-semibold mb-2"><i class="ti ti-clipboard-check fs-5 me-2"></i>สถานะการทดสอบ (Test)</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input prd-test-status" type="radio" name="prdTestStatus" id="prdTestReady" value="ready">
                                            <label class="form-check-label" for="prdTestReady">
                                                <i class="ti ti-check text-success me-1"></i>พร้อม Test
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input prd-test-status" type="radio" name="prdTestStatus" id="prdTestNotReady" value="not_ready">
                                            <label class="form-check-label" for="prdTestNotReady">
                                                <i class="ti ti-x text-danger me-1"></i>ไม่พร้อม Test
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">หมายเหตุ</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="prdRemark" rows="3" placeholder="รายละเอียดเพิ่มเติม..."></textarea>
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
        // Map employee Code -> DivisionCode for auto-fill department
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
            // ตรวจสอบ double extension (เช่น .php.jpg)
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

            // Initialize Objective select (multiple)
            $(card).find('.job-select-objective').select2({
                ...select2Common,
                placeholder: 'เลือกวัตถุประสงค์'
            }).html(cachedOptions.objective);

            // Toggle Objective Other box
            $(card).find('.job-select-objective').on('change', function() {
                const vals = $(this).val() || [];
                const boxOther = $(this).closest('.card-body').find('.box-objective-other');
                if (vals.includes('Other')) {
                    boxOther.fadeIn();
                } else {
                    boxOther.hide().find('input').val('');
                }
            });

            // Initialize SN & WA selects
            $(card).find('.job-select-sn').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือก S/N'
            });

            $(card).find('.job-select-wa').select2({
                ...select2Common,
                placeholder: 'กรุณาเลือก WA'
            });

            // Initialize JobNo select2
            $(card).find('.job-select-jobno').select2({
                ...select2Common,
                placeholder: 'เลือก Job No.',
                allowClear: true
            });

            // Auto-fill ProjectName when JobNo is selected
            $(card).on('change.jobno', '.job-select-jobno', function() {
                const selectedPO = $(this).val();
                const companyName = $(card).find('[data-field="CompanyName"]').val();
                const cacheData = (window.crmDataCache && companyName) ? (window.crmDataCache[companyName] || []) : [];
                const matched = cacheData.find(row => row.PO_No === selectedPO);
                $(this).closest('.job-item-row').find('.job-projectname').val(matched ? (matched.Used_For || '') : '');
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
                        snSelect.append(new Option(row.PartNo, row.PartNo));
                        snAdded.add(row.PartNo);
                    }
                });

                if (waSelect.hasClass('select2-hidden-accessible')) waSelect.select2('destroy');
                waSelect.select2({ allowClear: true, theme: 'bootstrap-5', placeholder: 'กรุณาเลือก WA' });

                if (snSelect.hasClass('select2-hidden-accessible')) snSelect.select2('destroy');
                snSelect.select2({ allowClear: true, theme: 'bootstrap-5', placeholder: 'กรุณาเลือก S/N' });

                // Populate JobNo selects with PO_No
                const jobnoPOMap = {};
                data.forEach(row => { if (row.PO_No) jobnoPOMap[row.PO_No] = row.Used_For || ''; });
                const jobnoPOOptions = Object.keys(jobnoPOMap);

                card.find('.job-select-jobno').each(function() {
                    const $sel = $(this);
                    const currentVal = $sel.val();
                    if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');
                    $sel.empty();
                    $sel.append(new Option('', ''));
                    jobnoPOOptions.forEach(po => $sel.append(new Option(po, po)));
                    if (currentVal && $sel.find(`option[value="${currentVal}"]`).length > 0) $sel.val(currentVal);
                    $sel.select2({ allowClear: true, theme: 'bootstrap-5', placeholder: 'เลือก Job No.' });
                });

                // Restore pending job items (set by loadCopyData or renderJobCards)
                const pendingItems = card.data('pendingJobItems');
                if (pendingItems && pendingItems.length > 0) {
                    card.removeData('pendingJobItems');
                    const $container = card.find('.job-items-container');
                    pendingItems.forEach((item, idx) => {
                        let $row;
                        if (idx === 0) {
                            $row = $container.find('.job-item-row').first();
                        } else {
                            const $sel = $('<select class="form-select job-select-jobno" style="width: 200px; flex-shrink: 0;"></select>');
                            const $inp = $('<input type="text" class="form-control job-projectname" placeholder="ชื่อโครงการ">');
                            const $btn = $('<button type="button" class="btn btn-outline-danger btn-sm btn-remove-job-row" style="flex-shrink:0;"><i class="ti ti-trash"></i></button>');
                            jobnoPOOptions.forEach(po => $sel.append(new Option(po, po)));
                            $row = $('<div class="job-item-row d-flex gap-2 align-items-center"></div>').append($sel, $inp, $btn);
                            $container.append($row);
                            $sel.select2({ allowClear: true, theme: 'bootstrap-5', placeholder: 'เลือก Job No.' });
                        }
                        if (item.JobNo) {
                            const $sel = $row.find('.job-select-jobno');
                            if ($sel.find(`option[value="${item.JobNo}"]`).length === 0) $sel.append(new Option(item.JobNo, item.JobNo));
                            $sel.val(item.JobNo).trigger('change.jobno');
                        }
                        if (item.ProjectName) $row.find('.job-projectname').val(item.ProjectName);
                    });
                    if (pendingItems.length > 1) $container.find('.btn-remove-job-row').show();
                }
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

            // Collect Job No. & ProjectName rows
            const jobItems = [];
            document.querySelectorAll('#nav-home .job-item-row').forEach(row => {
                const jobno = $(row).find('.job-select-jobno').val();
                const projname = $(row).find('.job-projectname').val();
                if (jobno || projname) jobItems.push({ JobNo: jobno || '', ProjectName: projname || '' });
            });
            job.JobItems = jobItems;
            if (jobItems.length > 0) {
                job.JobNo = jobItems[0].JobNo;
                job.ProjectName = jobItems[0].ProjectName;
            }

            salesDetails.push(job);
            return salesDetails;
        }

        // =====================================================
        // COLLECT CORPORATE DETAIL (ฝ่ายสื่อสาร)
        // =====================================================
        function collectCorporateBoxData(box) {
            const entry = {};
            entry.date = box.find('.corporate-date').val() || '';

            entry.usePhotoService = box.find('.corp-usePhotoService').is(':checked');
            if (entry.usePhotoService) {
                entry.photoTimeStart = box.find('.corp-photoTimeStart').val() || '';
                entry.photoTimeEnd = box.find('.corp-photoTimeEnd').val() || '';
                entry.photoLocations = [];
                box.find('.corp-photoLoc:checked').each(function() {
                    const val = $(this).val();
                    if (val === 'other') {
                        entry.photoLocations.push(box.find('.corp-photoLocOtherText').val() || 'อื่นๆ');
                    } else {
                        entry.photoLocations.push(val);
                    }
                });
            }

            entry.useWelcomeService = box.find('.corp-useWelcomeService').is(':checked');
            if (entry.useWelcomeService) {
                entry.welcomeTimeStart = box.find('.corp-welcomeTimeStart').val() || '';
                entry.welcomeTimeEnd = box.find('.corp-welcomeTimeEnd').val() || '';
                entry.welcomeDetail = box.find('.corp-welcomeDetail').val() || '';
                entry.screens = {};
                box.find('.corp-screen:checked').each(function() {
                    const mp = $(this).data('mp');
                    if (!entry.screens[mp]) entry.screens[mp] = [];
                    entry.screens[mp].push($(this).val());
                });
            }

            return entry;
        }

        function collectCorporateDetail() {
            const corporateData = {
                serviceType: $('input[name="corporateServiceType"]:checked').val() || 'no_service'
            };

            if (corporateData.serviceType === 'use_service') {
                const entries = [];
                const seen = new Set();
                $('#corporateBoxContainer .corporate-box').each(function() {
                    const entry = collectCorporateBoxData($(this));
                    const key = JSON.stringify(entry);
                    if (!seen.has(key)) {
                        seen.add(key);
                        entries.push(entry);
                    }
                });
                corporateData.entries = entries;
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
                lecturerHTML = '',
                groupHTML = '';

            // Build dept name map: DepartmentCode -> display text
            const deptNameMap = {};
            Object.values(divisionRes).forEach(sec => {
                Object.values(sec.Department).forEach(dep => {
                    deptNameMap[dep.DepartmentCode] = `${dep.DepartmentCode}-${dep.DepartmentName}`;
                });
            });

            // Populate and Init Sales Card Body Selects
            const salesCardBodyInit = document.querySelector('#nav-home .card-body');
            if (salesCardBodyInit) {
                // Initialize all selects
                initJobCardSelects(salesCardBodyInit);
            }

            empAllRes.forEach(e => {
                requesterHTML += `<option value="${e.Code}">${e.FullName}</option>`;
                tcHTML += `<option value="${e.Code}">${e.FullName}</option>`;
                lecturerHTML += `<option value="${e.Code}">${e.FullName}</option>`;
                // Build requester -> dept map
                requesterDeptMap[e.Code] = deptNameMap[e.DivisionCode] || e.DivisionCode || '';
            });

            // Cache Data
            cachedOptions.requester = requesterHTML;
            cachedOptions.tc = tcHTML;
            cachedOptions.groupctm = groupHTML;

            $('#Lecturer').html(lecturerHTML);

            // Cache Objective options for Select2 in Job Cards
            let objectiveHTML = '';
            if (Array.isArray(objectiveList)) {
                objectiveList.forEach(obj => objectiveHTML += `<option value="${obj.visit_objective_id}">${obj.visit_objective_name}</option>`);
            }
            objectiveHTML += '<option value="Other">อื่นๆ</option>';
            cachedOptions.objective = objectiveHTML;

            // Populate selects ใน Sales Card Body
            const salesCardBody = document.querySelector('#nav-home .card-body');
            if (salesCardBody) {
                $(salesCardBody).find('.job-select-requester').html('<option value=""></option>' + requesterHTML);
                $(salesCardBody).find('.job-select-tc').html('<option value=""></option>' + tcHTML);
                $(salesCardBody).find('.job-select-objective').html(cachedOptions.objective);

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


            let _ztCounter = 0;

            $('#addSchedule').on('click', function() {
                const ztName = 'zt_' + (++_ztCounter);
                let newCard = $(`
                    <div class="schedule-card">
                        <div class="scard-header">
                            <div class="reserve-type-wrap">
                                <span class="reserve-icon" style="background:#6366f1">
                                    <i class="ti ti-building fs-6"></i>
                                </span>
                                <select class="form-select reserve">
                                    <option value="meeting">ห้องประชุม</option>
                                    <option value="zoom">Zoom</option>
                                </select>
                            </div>
                            <div class="zoom-type-row">
                                <label class="zoom-type-pill active">
                                    <input class="zoom-type-radio" type="radio" name="${ztName}" value="Meeting" checked> Meeting
                                </label>
                                <label class="zoom-type-pill">
                                    <input class="zoom-type-radio" type="radio" name="${ztName}" value="Seminar"> Seminar
                                </label>
                            </div>
                            <button type="button" class="btn scard-delete-btn removeRow ms-auto">
                                <i class="ti ti-trash fs-5"></i>
                            </button>
                        </div>
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-4">
                                <div class="schedule-label">หัวข้อการประชุม</div>
                                <input type="text" class="form-control" name="meeting_subject[]" placeholder="ระบุหัวข้อการประชุม...">
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="schedule-label">วันที่เยี่ยม</div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-calendar fs-5"></i></span>
                                    <input class="form-control dateTrival" name="dateTrival[]" type="text" placeholder="dd/mm/yyyy" required>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="schedule-label">เวลาเริ่ม</div>
                                <input type="time" class="form-control" name="meeting_time_start[]" value="08:30" min="08:30" max="17:30">
                            </div>
                            <div class="col-6 col-md-1">
                                <div class="schedule-label">ถึงเวลา</div>
                                <input type="time" class="form-control" name="meeting_time_end[]" value="17:30" min="08:30" max="17:30">
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="schedule-label">ห้อง / Zoom</div>
                                <select name="meetingroom[]" class="form-select meetingroom">
                                    <option value="">-- เลือกเวลาก่อน --</option>
                                </select>
                            </div>
                        </div>
                        <div class="zoom-url-row d-none mt-2 pt-2" style="border-top:1px dashed #bae6fd">
                            <div class="d-flex align-items-center gap-2">
                                <small class="text-nowrap" style="font-size:.68rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em">Zoom Link</small>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control zoom-url-input" readonly style="font-size:11.5px">
                                    <button class="btn btn-outline-primary btn-sm zoom-url-copy" type="button"><i class="ti ti-copy"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                $('#schedule-container').append(newCard);

                newCard.find('.dateTrival').datepicker({
                    dateFormat: 'dd/mm/yy',
                    appendTo: 'body',
                    minDate: 0
                });
            });

            $(document).on('click', '.removeRow', function() {
                const card = $(this).closest('.schedule-card');
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: 'ต้องการลบการจองนี้ใช่หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'ลบ',
                    cancelButtonText: 'ยกเลิก',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) card.remove();
                });
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

            // ---------- เพิ่ม Job No. row ----------
            $(document).on('click', '.btn-add-job-no', function() {
                const $card = $(this).closest('.card-body');
                const $container = $card.find('.job-items-container');
                const companyName = $card.find('[data-field="CompanyName"]').val();
                const cacheData = (window.crmDataCache && companyName) ? (window.crmDataCache[companyName] || []) : [];

                const jobnoPOMap = {};
                cacheData.forEach(row => { if (row.PO_No) jobnoPOMap[row.PO_No] = row.Used_For || ''; });

                const $newSelect = $('<select class="form-select job-select-jobno" style="width: 200px; flex-shrink: 0;"></select>');
                const $newInput = $('<input type="text" class="form-control job-projectname" placeholder="ชื่อโครงการ">');
                const $removeBtn = $('<button type="button" class="btn btn-outline-danger btn-sm btn-remove-job-row" style="flex-shrink:0;"><i class="ti ti-trash"></i></button>');

                $newSelect.append(new Option('', ''));
                Object.keys(jobnoPOMap).forEach(po => $newSelect.append(new Option(po, po)));

                const $newRow = $('<div class="job-item-row d-flex gap-2 align-items-center"></div>').append($newSelect, $newInput, $removeBtn);
                $container.append($newRow);

                $newSelect.select2({ allowClear: true, theme: 'bootstrap-5', placeholder: 'เลือก Job No.' });
                $container.find('.btn-remove-job-row').show();
            });

            // ---------- ลบ Job No. row ----------
            $(document).on('click', '.btn-remove-job-row', function() {
                const $container = $(this).closest('.job-items-container');
                $(this).closest('.job-item-row').remove();
                if ($container.find('.job-item-row').length <= 1) {
                    $container.find('.btn-remove-job-row').hide();
                }
            });

            // Check if copying from existing form
            const copyParams = new URLSearchParams(window.location.search);
            const copyId = copyParams.get('copy_id');
            if (copyId) {
                loadCopyData(copyId);
            } else {
                Swal.close();
            }

        });

        async function loadCopyData(copyId) {
            try {
                const res = await fetch(`api/visitor_form_get.php?id=${copyId}`);
                const data = await res.json();
                if (!data.status) { Swal.close(); return; }
                const d = data.data;

                // Fill basic fields
                $('#CustomerNameThai').val(d.CustomerNameThai || 0);
                $('#CustomerNameForeign').val(d.CustomerNameForeign || 0);
                $('#CustomerTotal').val(d.CustomerTotal || 0);
                $('#CustomerCar').val(d.CustomerCar || 0);
                $('#CarNumber').val(d.CarNumber || '');
                $('#DriverNumber').val(d.DriverNumber || '');
                $('#Remark').val(d.Remark || '');
                $('#NumberDiners').val(d.NumberDiners || '');
                $('#OtherMenu').val(d.OtherMenu || '');
                $('#RemarkFood').val(d.RemarkFood || '');

                // Checkboxes: travel, CusFood
                ['travel', 'CusFood'].forEach(name => {
                    try {
                        let arr = JSON.parse(d[name.charAt(0).toUpperCase() + name.slice(1)] || '[]');
                        arr.forEach(v => $(`[name='${name}[]'][value='${v}']`).prop('checked', true));
                    } catch (e) {}
                });

                // Food checkboxes
                try {
                    let foodArr = JSON.parse(d.Food || '[]');
                    foodArr.forEach(v => $(`.food-choice[value='${v}']`).prop('checked', true));
                } catch (e) {}

                // Sales Detail (Job Cards)
                try {
                    let salesDetail = JSON.parse(d.SalesDetail || '[]');
                    if (salesDetail.length > 0) {
                        const firstJob = salesDetail[0];
                        const $firstCard = $('#nav-home .card-body');
                        if (firstJob.requester) {
                            $firstCard.find('.job-select-requester').val(firstJob.requester).trigger('change');
                        }
                        if (firstJob.tc) {
                            $firstCard.find('.job-select-tc').val(firstJob.tc).trigger('change');
                        }
                        // Restore Job items - store as pending (will be applied after CRM data loads)
                        const itemsToRestore = firstJob.JobItems && firstJob.JobItems.length > 0
                            ? firstJob.JobItems
                            : (firstJob.JobNo ? [{ JobNo: firstJob.JobNo, ProjectName: firstJob.ProjectName || '' }] : []);
                        if (itemsToRestore.length > 0) $firstCard.data('pendingJobItems', itemsToRestore);
                    }
                } catch (e) {}

                // Corporate Detail
                try {
                    let corp = JSON.parse(d.CorporateDetail || '{}');
                    if (corp.serviceType) {
                        $(`.corporate-service-type[value='${corp.serviceType}']`).prop('checked', true).trigger('change');
                    }
                    if (corp.serviceType === 'use_service') {
                        // backward compat: old format has no entries array
                        const corpEntries = corp.entries || [corp];

                        corpEntries.forEach((entry, idx) => {
                            let box;
                            if (idx === 0) {
                                box = $('#corporateBoxContainer .corporate-box:first');
                            } else {
                                box = $('#corporateBoxContainer .corporate-box:first').clone(false);
                                box.find('input[type="text"], input[type="time"], textarea').val('');
                                box.find('input[type="checkbox"]').prop('checked', false);
                                box.find('.corp-photoServicePanel, .corp-welcomeServicePanel, .corp-photoLocOtherInput').hide();
                                $('#corporateBoxContainer').append(box);
                            }

                            box.find('.corporate-date').val(entry.date || '');

                            if (entry.usePhotoService) {
                                box.find('.corp-usePhotoService').prop('checked', true);
                                box.find('.corp-photoServicePanel').show();
                                box.find('.corp-photoTimeStart').val(entry.photoTimeStart || '');
                                box.find('.corp-photoTimeEnd').val(entry.photoTimeEnd || '');
                                (entry.photoLocations || []).forEach(loc => {
                                    if (loc === 'showroom' || loc === 'MP3' || loc === 'MP4') {
                                        box.find(`.corp-photoLoc[value="${loc}"]`).prop('checked', true);
                                    } else {
                                        box.find('.corp-photoLocOther').prop('checked', true);
                                        box.find('.corp-photoLocOtherInput').show();
                                        box.find('.corp-photoLocOtherText').val(loc);
                                    }
                                });
                            }

                            if (entry.useWelcomeService) {
                                box.find('.corp-useWelcomeService').prop('checked', true);
                                box.find('.corp-welcomeServicePanel').show();
                                box.find('.corp-welcomeTimeStart').val(entry.welcomeTimeStart || '');
                                box.find('.corp-welcomeTimeEnd').val(entry.welcomeTimeEnd || '');
                                box.find('.corp-welcomeDetail').val(entry.welcomeDetail || '');

                                if (entry.screens) {
                                    for (const mpKey of ['MP1', 'MP2', 'MP3', 'MP4']) {
                                        if (entry.screens[mpKey] && Array.isArray(entry.screens[mpKey])) {
                                            for (const screenValue of entry.screens[mpKey]) {
                                                box.find(`.corp-screen[data-mp="${mpKey}"][value="${screenValue}"]`).prop('checked', true);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        updateCorpRemoveButtons();
                    }
                } catch (e) {}

                // Lecturer Detail
                try {
                    let lect = JSON.parse(d.LecturerDetail || '{}');
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
                } catch (e) {}

                Swal.close();
                Swal.fire({ icon: 'success', title: 'คัดลอกข้อมูลแล้ว', text: 'กรุณาตรวจสอบและแก้ไขข้อมูลก่อนบันทึก', timer: 2000, showConfirmButton: false });
            } catch (e) {
                console.error('Copy error:', e);
                Swal.close();
            }
        }

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

        // Toggle photo service panel (class-based for multi-box)
        $(document).on('change', '.corp-usePhotoService', function() {
            const panel = $(this).closest('.corporate-box').find('.corp-photoServicePanel');
            if ($(this).is(':checked')) {
                panel.slideDown(300);
            } else {
                panel.slideUp(300);
            }
        });

        // Toggle welcome service panel (class-based for multi-box)
        $(document).on('change', '.corp-useWelcomeService', function() {
            const panel = $(this).closest('.corporate-box').find('.corp-welcomeServicePanel');
            if ($(this).is(':checked')) {
                panel.slideDown(300);
            } else {
                panel.slideUp(300);
            }
        });

        // Toggle photo location "อื่นๆ" input field (class-based for multi-box)
        $(document).on('change', '.corp-photoLocOther', function() {
            const row = $(this).closest('.corporate-box').find('.corp-photoLocOtherInput');
            if ($(this).is(':checked')) {
                row.slideDown(200);
            } else {
                row.slideUp(200);
                row.find('.corp-photoLocOtherText').val('');
            }
        });

        // Remove corporate box
        $(document).on('click', '.corporate-box-remove', function() {
            const container = $('#corporateBoxContainer');
            if (container.find('.corporate-box').length > 1) {
                $(this).closest('.corporate-box').slideUp(300, function() { $(this).remove(); updateCorpRemoveButtons(); });
            }
        });

        function updateCorpRemoveButtons() {
            const boxes = $('#corporateBoxContainer .corporate-box');
            boxes.find('.corporate-box-remove').toggle(boxes.length > 1);
        }

        // เพิ่มวัน — clone blank box
        $(document).on('click', '#btnAddCorpDay', function() {
            const template = $('#corporateBoxContainer .corporate-box:first').clone(false);
            template.find('input[type="text"], input[type="time"], textarea').val('');
            template.find('input[type="checkbox"]').prop('checked', false);
            template.find('.corp-photoServicePanel, .corp-welcomeServicePanel, .corp-photoLocOtherInput').hide();
            template.hide();
            $('#corporateBoxContainer').append(template);
            template.slideDown(300);
            updateCorpRemoveButtons();
        });

        // เพิ่มข้อมูลเดิม — clone with data from last box
        $(document).on('click', '#btnAddCorpClone', function() {
            const lastBox = $('#corporateBoxContainer .corporate-box:last');
            const cloned = lastBox.clone(true);
            cloned.find('.corporate-date').val('');
            cloned.hide();
            $('#corporateBoxContainer').append(cloned);
            cloned.slideDown(300);
            updateCorpRemoveButtons();
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

        $(document).on('change', '.reserve', function() {
            const card = $(this).closest('.schedule-card');
            const span = card.find('.reserve-icon');
            const isZoom = $(this).val() === 'zoom';
            if (isZoom) {
                span.find('i').removeClass('ti-building').addClass('ti-video');
                span.css('background', '#0ea5e9');
            } else {
                span.find('i').removeClass('ti-video').addClass('ti-building');
                span.css('background', '#6366f1');
            }
            card.toggleClass('is-zoom', isZoom);
            card.find('.zoom-type-row').toggleClass('visible', isZoom);
            const hasUrl = !!card.find('.zoom-url-input').val();
            card.find('.zoom-url-row').toggleClass('d-none', !isZoom || !hasUrl);
            loadMeetingRoom(card);
        });

        $(document).on('change', '.zoom-type-radio', function() {
            $(this).closest('.zoom-type-row').find('.zoom-type-pill').removeClass('active');
            $(this).closest('.zoom-type-pill').addClass('active');
        });

        $(document).on('click', '.zoom-url-copy', function() {
            const input = $(this).closest('.input-group').find('.zoom-url-input')[0];
            input.select();
            document.execCommand('copy');
            const btn = this;
            $(btn).html('<i class="ti ti-copy"></i> Copied').removeClass('btn-outline-primary').addClass('btn-success');
            setTimeout(() => {
                $(btn).html('<i class="ti ti-copy"></i>').removeClass('btn-success').addClass('btn-outline-primary');
            }, 2000);
        });

        $(document).on('change', '.dateTrival', function() {
            loadMeetingRoom($(this).closest('.schedule-card'));
        });

        $(document).on('change', 'input[name="meeting_time_start[]"]', function() {
            const card = $(this).closest('.schedule-card');
            const endInput = card.find('input[name="meeting_time_end[]"]');
            if ($(this).val() && endInput.val() && endInput.val() <= $(this).val()) {
                endInput.val('');
            }
            loadMeetingRoom(card);
        });

        $(document).on('change', 'input[name="meeting_time_end[]"]', function() {
            const card = $(this).closest('.schedule-card');
            const startVal = card.find('input[name="meeting_time_start[]"]').val();
            const endVal = $(this).val();
            if (startVal && endVal && endVal <= startVal) {
                Swal.fire({ icon: 'warning', title: 'เวลาไม่ถูกต้อง', text: 'เวลาสิ้นสุดต้องมากกว่าเวลาเริ่มต้น', timer: 2000, showConfirmButton: false });
                $(this).val('');
                return;
            }
            loadMeetingRoom(card);
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
            ['objective', 'travel', 'CusFood', 'food'].forEach(name => {
                var nameCapitalized = name.charAt(0).toUpperCase() + name.slice(1);
                $(`[name='${name}[]']:checked, [name='${name}[]'] option:selected`).each(function() {
                    const val = $(this).val();
                    formData.append(`${nameCapitalized}[]`, val);
                });
            });

            let schedule = [];
            $('#schedule-container .schedule-card').each(function() {
                if (!$(this).find('.meetingroom').val()) return;
                schedule.push({
                    reserve: $(this).find('.reserve').val(),
                    zoom_type: $(this).find('.zoom-type-radio:checked').val() || 'Meeting',
                    subject: $(this).find('input[name="meeting_subject[]"]').val(),
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
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: res => {
                            Swal.close();
                            let response = typeof res === "string" ? JSON.parse(res) : res;

                            if (response.status) {
                                // Populate zoom URLs into schedule cards
                                if (response.bookingResults && response.bookingResults.length > 0) {
                                    const scheduleCards = [];
                                    $('#schedule-container .schedule-card').each(function() {
                                        if ($(this).find('.meetingroom').val()) scheduleCards.push($(this));
                                    });
                                    response.bookingResults.forEach((b, i) => {
                                        if (b.reserve === 'zoom' && b.zoomUrl && scheduleCards[i]) {
                                            scheduleCards[i].find('.zoom-url-input').val(b.zoomUrl);
                                            scheduleCards[i].find('.zoom-url-row').removeClass('d-none');
                                        }
                                    });
                                }
                                if (response.bookingResults && response.bookingResults.length > 0) {
                                    let html = '<div class="text-start">';
                                    response.bookingResults.forEach((b, i) => {
                                        const allOk = b.scheduleOk && b.meetingOk;
                                        const badgeCls = allOk ? 'bg-success' : 'bg-danger';
                                        const badgeTxt = allOk ? '✓ จองสำเร็จ' : '✗ จองไม่สำเร็จ';
                                        const icon = b.reserve === 'zoom' ? 'video' : 'building';
                                        html += `<div class="border rounded p-2 mb-2">`;
                                        html += `<div class="d-flex justify-content-between align-items-center">`;
                                        html += `<span class="fw-semibold"><i class="ti ti-${icon} me-1"></i>${b.roomname || b.subject || 'รายการ '+(i+1)}</span>`;
                                        html += `<span class="badge ${badgeCls}">${badgeTxt}</span>`;
                                        html += `</div>`;
                                        if (b.reserve === 'zoom' && b.zoomUrl && allOk) {
                                            html += `<div class="mt-2"><small class="text-muted d-block mb-1">Zoom Meeting Link:</small>`;
                                            html += `<div class="input-group input-group-sm">`;
                                            html += `<input type="text" class="form-control form-control-sm" value="${b.zoomUrl}" readonly id="zoomUrl_${i}" style="font-size:11px">`;
                                            html += `<button class="btn btn-outline-primary btn-sm" type="button" onclick="var inp=document.getElementById('zoomUrl_${i}');inp.select();document.execCommand('copy');var bt=this;bt.innerHTML='✓ Copied';bt.className='btn btn-success btn-sm text-white';setTimeout(function(){bt.innerHTML='📋 Copy';bt.className='btn btn-outline-primary btn-sm'},2000)">📋 Copy</button>`;
                                            html += `</div></div>`;
                                        }
                                        html += `</div>`;
                                    });
                                    html += '</div>';
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'บันทึกสำเร็จ',
                                        html: html,
                                        width: 520,
                                        confirmButtonText: 'ตกลง',
                                        allowOutsideClick: false
                                    }).then(() => {
                                        window.location.href = './listvisitor.php?page=listvisitor';
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'สำเร็จ',
                                        text: response.message
                                    }).then(() => {
                                        window.location.href = './listvisitor.php?page=listvisitor';
                                    });
                                }
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

        function loadMeetingRoom(card, selectedValue = null) {
            let reserve = card.find('.reserve').val();
            let dateTrival = card.find('.dateTrival').val();
            let timeStart = card.find('input[name="meeting_time_start[]"]').val();
            let timeEnd = card.find('input[name="meeting_time_end[]"]').val();
            const select = card.find('.meetingroom');

            if (reserve && dateTrival && timeStart && timeEnd) {
                select.html('<option value="">กำลังโหลด...</option>').prop('disabled', true);

                $.ajax({
                    url: './api/check_available_room.php',
                    type: 'POST',
                    data: {
                        reserve: reserve,
                        dateTrival: formatDateToYMD(dateTrival),
                        meeting_time_start: timeStart,
                        meeting_time_end: timeEnd
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Only exclude rooms selected by other cards on the same date with overlapping time
                        const selectedRooms = [];
                        const currentDate = card.find('.dateTrival').val();
                        const currentStart = card.find('input[name="meeting_time_start[]"]').val();
                        const currentEnd = card.find('input[name="meeting_time_end[]"]').val();
                        $('#schedule-container .schedule-card').each(function() {
                            if ($(this).is(card)) return;
                            const otherDate = $(this).find('.dateTrival').val();
                            const otherStart = $(this).find('input[name="meeting_time_start[]"]').val();
                            const otherEnd = $(this).find('input[name="meeting_time_end[]"]').val();
                            const val = $(this).find('.meetingroom').val();
                            if (val && otherDate === currentDate && otherStart < currentEnd && otherEnd > currentStart) {
                                selectedRooms.push(val);
                            }
                        });

                        select.empty().prop('disabled', false);
                        select.append('<option value="">-- เลือกห้อง --</option>');
                        if (response.status && response.data && response.data.length > 0) {
                            let hasOptions = false;
                            response.data.forEach(item => {
                                if (!selectedRooms.includes(item.id)) {
                                    select.append(`<option value="${item.id}">${item.name}</option>`);
                                    hasOptions = true;
                                }
                            });
                            if (!hasOptions) {
                                select.append('<option value="" disabled>ไม่มีห้องว่างในช่วงเวลานี้</option>');
                            }
                        } else {
                            select.append('<option value="" disabled>ไม่มีห้องว่างในช่วงเวลานี้</option>');
                        }
                        if (selectedValue) select.val(selectedValue);
                    },
                    error: function(xhr, status, error) {
                        select.html('<option value="">เกิดข้อผิดพลาด</option>').prop('disabled', false);
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
    </script>

</body>

</html>