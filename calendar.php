<?php
include_once 'config/base.php';
$pageName = 'calendar';

// Check login
if (!isset($_SESSION['VisitorMKT_code'])) {
    header("Location: index.php");
    exit;
}

$userCode = $_SESSION['VisitorMKT_code'] ?? '';
$userPerm = $_SESSION['VisitorMKT_permision'] ?? '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ปฏิทินกิจกรรม - VisitorMKT</title>
    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <?php include 'css.php'; ?>
    <style>
        .fc { background: var(--md-surface); border-radius: var(--md-shape-md); padding: 16px; box-shadow: var(--md-elevation-1); border: 1px solid var(--md-outline-variant); }
        .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 600; color: var(--md-on-surface); }
        .fc-event { cursor: pointer; border-radius: var(--md-shape-sm); padding: 2px 6px; transition: transform 0.15s ease; }
        .fc-event:hover { transform: scale(1.02); }
        .fc-daygrid-event { font-size: 0.8rem; }
        .event-welcome { background: var(--md-tertiary) !important; border-color: var(--md-tertiary) !important; }
        .event-photo { background: var(--md-primary) !important; border-color: var(--md-primary) !important; }
        .event-visitor { background: #f9ab00 !important; border-color: #f9ab00 !important; }
        .legend-box { display: inline-block; width: 14px; height: 14px; border-radius: var(--md-shape-xs); margin-right: 6px; vertical-align: middle; }

        .event-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: var(--md-shape-full); font-size: 12px; font-weight: 500; }
        .event-badge.visitor { background: #fef3c7; color: #92400e; }
        .event-badge.welcome { background: var(--md-tertiary-container); color: #065f46; }
        .event-badge.photo { background: var(--md-primary-container); color: var(--md-on-primary-container); }
        .event-badge i { font-size: 0.85rem; }

        .info-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--md-outline-variant); }
        .info-row:last-child { border-bottom: 0; }
        .info-icon { width: 36px; height: 36px; border-radius: var(--md-shape-sm); display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .info-icon.time { background: #ede9fe; color: #7c3aed; }
        .info-icon.doc { background: var(--md-primary-container); color: var(--md-on-primary-container); }
        .info-icon.note { background: #fef3c7; color: #d97706; }
        .info-content { flex: 1; }
        .info-label { font-size: 11px; color: var(--md-on-surface-variant); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; font-weight: 500; }
        .info-value { font-size: 14px; color: var(--md-on-surface); font-weight: 500; }

        .btn-action { background: var(--md-primary); border: 0; color: var(--md-on-primary); border-radius: var(--md-shape-full); padding: 10px 20px; font-weight: 500; transition: all 0.2s; }
        .btn-action:hover { background: #1557b0; box-shadow: var(--md-elevation-1); color: var(--md-on-primary); }

        .fc-button { border-radius: var(--md-shape-full) !important; font-size: 13px !important; font-weight: 500 !important; }
        .fc-button-primary { background: var(--md-surface-container) !important; border: 1px solid var(--md-outline) !important; color: var(--md-on-surface) !important; }
        .fc-button-primary:hover { background: var(--md-surface-container-high) !important; }
        .fc-button-primary.fc-button-active { background: var(--md-primary) !important; border-color: var(--md-primary) !important; color: var(--md-on-primary) !important; }
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
                    <h4 class="mb-0"><i class="fa-solid fa-calendar-days me-2"></i> ปฏิทินกิจกรรม</h4>
                    <div>
                        <span class="legend-box event-visitor"></span> Showroom
                        <span class="legend-box event-welcome ms-3"></span> ป้ายต้อนรับ
                        <span class="legend-box event-photo ms-3"></span> ถ่ายรูป
                    </div>
                </div>
                
                <div id="calendar"></div>
            </div>
        </div>
        <?php include('layout/theme-settings.php'); ?>
    </main>

    <!-- Event Detail Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalTitle">รายละเอียดกิจกรรม</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventModalBody"></div>
                <div class="modal-footer">
                    <a href="#" id="eventModalLink" class="btn btn-primary btn-action">
                        <i class="fa-solid fa-arrow-right me-1"></i> ดูรายละเอียดเพิ่มเติม
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script> -->
    <?php include 'js.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'th',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: 'วันนี้',
                    month: 'เดือน',
                    week: 'สัปดาห์',
                    list: 'รายการ'
                },
                events: function(info, successCallback, failureCallback) {
                    fetch(`api/calendar_events.php`)
                        .then(res => res.json())
                        .then(data => {
                            console.log(data);
                            if (data.status) {
                                successCallback(data.events);
                            } else {
                                failureCallback(data.message);
                            }
                        })
                        .catch(err => failureCallback(err));
                },
                eventClick: function(info) {
                    const event = info.event;
                    const props = event.extendedProps;
                    const eventType = props.eventType || 'Showroom';
                    
                    // Event type label and badge class
                    const typeLabels = {
                        'Showroom': { label: 'Showroom', icon: 'fa-solid fa-users', badge: 'visitor' },
                        'Welcome': { label: 'ป้ายต้อนรับ', icon: 'fa-solid fa-sign-hanging', badge: 'welcome' },
                        'Photo': { label: 'ถ่ายรูป', icon: 'fa-solid fa-camera', badge: 'photo' }
                    };
                    const typeInfo = typeLabels[eventType] || typeLabels['visitor'];
                    
                    document.getElementById('eventModalTitle').textContent = props.docNo || event.title;
                    document.getElementById('eventModalBody').innerHTML = `
                        <div class="text-center mb-3">
                            <span class="event-badge ${typeInfo.badge}">
                                <i class="${typeInfo.icon}"></i> ${typeInfo.label}
                            </span>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-icon time"><i class="fa-regular fa-clock"></i></div>
                            <div class="info-content">
                                <div class="info-label">เวลา</div>
                                <div class="info-value">${props.timeStart || '-'} - ${props.timeEnd || '-'}</div>
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-icon doc"><i class="fa-solid fa-file-lines"></i></div>
                            <div class="info-content">
                                <div class="info-label">เลขที่เอกสาร</div>
                                <div class="info-value">${props.docNo || '-'}</div>
                            </div>
                        </div>
                        
                        ${props.detail ? `
                        <div class="info-row">
                            <div class="info-icon note"><i class="fa-solid fa-sticky-note"></i></div>
                            <div class="info-content">
                                <div class="info-label">รายละเอียด</div>
                                <div class="info-value">${props.detail}</div>
                            </div>
                        </div>` : ''}
                    `;
                    document.getElementById('eventModalLink').href = `visitor_formupdate.php?page=view&id=${props.visitorFormId}`;
                    
                    eventModal.show();
                },
                eventDidMount: function(info) {
                    const eventType = info.event.extendedProps.eventType;
                    if (eventType === 'Welcome') {
                        info.el.classList.add('event-welcome');
                    } else if (eventType === 'Photo') {
                        info.el.classList.add('event-photo');
                    } else {
                        info.el.classList.add('event-visitor');
                    }
                }
            });
            
            calendar.render();
        });
    </script>
</body>
</html>
