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
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <?php include 'css.php'; ?>
    <style>
        /* ── Calendar Wrapper ── */
        .calendar-card {
            background: var(--md-surface, #fff);
            border: 1px solid var(--md-outline-variant, #e5e7eb);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
            padding: 24px;
        }

        /* ── FullCalendar overrides ── */
        .fc { border: none !important; background: transparent; padding: 0; box-shadow: none; }
        .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 600; color: var(--md-on-surface, #1c1b1f); }
        .fc-button {
            border-radius: 8px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            padding: 6px 14px !important;
            transition: all .15s ease !important;
        }
        .fc-button-primary {
            background: var(--md-surface-container, #f3f4f6) !important;
            border: 1px solid var(--md-outline-variant, #e5e7eb) !important;
            color: var(--md-on-surface, #374151) !important;
            box-shadow: none !important;
        }
        .fc-button-primary:hover {
            background: var(--md-surface-container-high, #e9eaec) !important;
            border-color: var(--md-outline, #9ca3af) !important;
        }
        .fc-button-primary.fc-button-active {
            background: var(--md-primary, #1a73e8) !important;
            border-color: var(--md-primary, #1a73e8) !important;
            color: var(--md-on-primary, #fff) !important;
        }
        .fc-col-header-cell { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: var(--md-on-surface-variant, #6b7280); }
        .fc-daygrid-day-number { font-size: 13px; color: var(--md-on-surface, #374151); }
        .fc-day-today { background: rgba(26,115,232,.04) !important; }
        .fc-day-today .fc-daygrid-day-number { color: var(--md-primary, #1a73e8); font-weight: 700; }

        /* ── Events ── */
        .fc-event {
            cursor: pointer;
            border: none !important;
            border-radius: 6px !important;
            padding: 2px 7px !important;
            font-size: 11.5px !important;
            font-weight: 500 !important;
            transition: opacity .15s ease, transform .1s ease;
        }
        .fc-event:hover { opacity: .85; transform: translateY(-1px); }
        .fc-event-title { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .event-welcome { background: #10b981 !important; color: #fff !important; }
        .event-photo   { background: var(--md-primary, #1a73e8) !important; color: #fff !important; }

        /* ── Legend pills ── */
        .legend-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px 4px 8px;
            border-radius: 99px;
            font-size: 12.5px;
            font-weight: 500;
        }
        .legend-pill .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .legend-pill.welcome { background: #d1fae5; color: #065f46; }
        .legend-pill.welcome .dot { background: #10b981; }
        .legend-pill.photo   { background: #dbeafe; color: #1e40af; }
        .legend-pill.photo   .dot { background: var(--md-primary, #1a73e8); }

        /* ── Page header ── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
        .page-title { font-size: 1.15rem; font-weight: 700; color: var(--md-on-surface, #1c1b1f); display: flex; align-items: center; gap: 8px; margin: 0; }
        .legend-group { display: flex; align-items: center; gap: 8px; }

        /* ── Event Modal ── */
        #eventModal .modal-content { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.12); }
        #eventModal .modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--md-outline-variant, #e5e7eb);
            background: var(--md-surface, #fff);
        }
        #eventModal .modal-title { font-size: 1rem; font-weight: 700; color: var(--md-on-surface, #1c1b1f); }
        #eventModal .modal-body { padding: 20px 24px; background: var(--md-surface, #fff); }
        #eventModal .modal-footer { padding: 14px 24px; border-top: 1px solid var(--md-outline-variant, #e5e7eb); background: var(--md-surface-container, #f9fafb); }

        /* type badge in modal */
        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 99px;
            font-size: 12.5px;
            font-weight: 600;
        }
        .type-badge.welcome { background: #d1fae5; color: #065f46; }
        .type-badge.photo   { background: #dbeafe; color: #1e40af; }

        /* info list in modal */
        .info-list { display: flex; flex-direction: column; gap: 0; margin-top: 16px; border: 1px solid var(--md-outline-variant, #e5e7eb); border-radius: 12px; overflow: hidden; }
        .info-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 16px; background: var(--md-surface, #fff); }
        .info-item + .info-item { border-top: 1px solid var(--md-outline-variant, #e5e7eb); }
        .info-icon2 {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .info-icon2.time { background: #ede9fe; color: #7c3aed; }
        .info-icon2.doc  { background: #dbeafe; color: #1d4ed8; }
        .info-icon2.note { background: #fef3c7; color: #d97706; }
        .info-text-label { font-size: 11px; color: var(--md-on-surface-variant, #6b7280); text-transform: uppercase; letter-spacing: .5px; font-weight: 600; margin-bottom: 1px; }
        .info-text-value { font-size: 14px; color: var(--md-on-surface, #1c1b1f); font-weight: 500; line-height: 1.4; }

        /* action button */
        .btn-goto {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--md-primary, #1a73e8);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: background .15s ease, box-shadow .15s ease;
        }
        .btn-goto:hover { background: #1557b0; color: #fff; box-shadow: 0 2px 8px rgba(26,115,232,.3); }
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

                <div class="page-header">
                    <h4 class="page-title">
                        <i class="fa-solid fa-calendar-days" style="color: var(--md-primary, #1a73e8);"></i>
                        ปฏิทินกิจกรรม
                    </h4>
                    <div class="legend-group">
                        <span class="legend-pill welcome">
                            <span class="dot"></span> ป้ายต้อนรับ
                        </span>
                        <span class="legend-pill photo">
                            <span class="dot"></span> ถ่ายรูป
                        </span>
                    </div>
                </div>

                <div class="calendar-card">
                    <div id="calendar"></div>
                </div>

            </div>
        </div>
        <?php include('layout/theme-settings.php'); ?>
    </main>

    <!-- Event Detail Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <div id="eventTypeBadge" class="mb-2"></div>
                        <h5 class="modal-title" id="eventModalTitle"></h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">ปิด</button>
                    <a href="#" id="eventModalLink" class="btn-goto">
                        <i class="fa-solid fa-arrow-right"></i> ดูรายละเอียด
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'js.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                dayMaxEvents: 3,
                events: function (info, successCallback, failureCallback) {
                    fetch('api/calendar_events.php')
                        .then(res => res.json())
                        .then(data => {
                            if (data.status) successCallback(data.events);
                            else failureCallback(data.message);
                        })
                        .catch(err => failureCallback(err));
                },
                eventClick: function (info) {
                    const event = info.event;
                    const props = event.extendedProps;
                    const eventType = props.eventType || 'Welcome';

                    const typeMap = {
                        'Welcome': { label: 'ป้ายต้อนรับ', icon: 'fa-solid fa-sign-hanging', cls: 'welcome' },
                        'Photo':   { label: 'ถ่ายรูป',      icon: 'fa-solid fa-camera',       cls: 'photo'   }
                    };
                    const t = typeMap[eventType] || typeMap['Welcome'];

                    document.getElementById('eventTypeBadge').innerHTML =
                        `<span class="type-badge ${t.cls}"><i class="${t.icon}"></i> ${t.label}</span>`;
                    document.getElementById('eventModalTitle').textContent = props.docNo || event.title;

                    const timeStr = (props.timeStart || '-') + (props.timeEnd ? ' – ' + props.timeEnd : '');
                    let bodyHtml = `<div class="info-list">
                        <div class="info-item">
                            <div class="info-icon2 time"><i class="fa-regular fa-clock"></i></div>
                            <div>
                                <div class="info-text-label">เวลา</div>
                                <div class="info-text-value">${timeStr}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon2 doc"><i class="fa-solid fa-file-lines"></i></div>
                            <div>
                                <div class="info-text-label">เลขที่เอกสาร</div>
                                <div class="info-text-value">${props.docNo || '-'}</div>
                            </div>
                        </div>`;

                    if (props.detail) {
                        bodyHtml += `
                        <div class="info-item">
                            <div class="info-icon2 note"><i class="fa-solid fa-note-sticky"></i></div>
                            <div>
                                <div class="info-text-label">รายละเอียด</div>
                                <div class="info-text-value">${props.detail}</div>
                            </div>
                        </div>`;
                    }
                    bodyHtml += '</div>';

                    document.getElementById('eventModalBody').innerHTML = bodyHtml;
                    document.getElementById('eventModalLink').href =
                        `visitor_formupdate.php?page=view&id=${props.visitorFormId}`;

                    eventModal.show();
                },
                eventDidMount: function (info) {
                    const t = info.event.extendedProps.eventType;
                    if (t === 'Welcome') info.el.classList.add('event-welcome');
                    else if (t === 'Photo') info.el.classList.add('event-photo');
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>
