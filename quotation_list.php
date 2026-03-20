<?php
    include_once 'config/base.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Company - Visitor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Themesberg">

    <?php include('css.php'); ?>

    <style>
        .dashboard-card {
            border-radius: 1.25rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(59, 130, 246, 0.15);
        }

        .dashboard-card:hover::before {
            opacity: 1;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            position: relative;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }

        .icon-circle::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 20px;
            padding: 2px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.5;
        }

        .icon-circle i {
            color: white !important;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .display-count {
            font-size: 3rem;
            letter-spacing: -2px;
            font-weight: 800;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: countPulse 2s ease-in-out infinite;
        }

        @keyframes countPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        .chart-card {
            border-radius: 1.25rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 1px solid rgba(59, 130, 246, 0.1);
            position: relative;
            overflow: hidden;
        }

        .chart-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .chart-header {
            position: relative;
            z-index: 1;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.02) 0%, transparent 100%);
        }

        .chart-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: #0369a1;
            border: 1px solid #bae6fd;
            box-shadow: 0 2px 4px rgba(3, 105, 161, 0.1);
        }

        .chart-container {
            position: relative;
            padding: 1.5rem;
            height: 280px !important;
        }

        .card-title-enhanced {
            font-size: 0.95rem;
            font-weight: 600;
            color: #475569;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-title-enhanced::before {
            content: '';
            width: 4px;
            height: 20px;
            background: linear-gradient(180deg, #3b82f6, #8b5cf6);
            border-radius: 2px;
        }

        .card-subtitle {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 0.25rem;
            font-weight: 400;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }

        @media (max-width: 991.98px) {
            .display-count {
                font-size: 2.5rem;
            }
            .icon-circle {
                width: 70px;
                height: 70px;
            }
        }

        @media (max-width: 767.98px) {
            .dashboard-card {
                border-radius: 1rem;
            }
            .icon-circle {
                width: 65px;
                height: 65px;
            }
            .display-count {
                font-size: 2rem;
            }
            .chart-container {
                height: 220px !important;
                padding: 1rem;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-card {
            animation: slideUp 0.5s ease-out;
        }

        .dashboard-card:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-card:nth-child(2) { animation-delay: 0.2s; }
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
                        <li class="breadcrumb-item">List Visitor</li>
                        <li class="breadcrumb-item active" aria-current="page" id="DocNo"></li>
                    </ol>
                </nav>
                <h2 class="h4">รายการแบบประเมินทั้งหมด</h2>
            </div>
            <!-- <div class="btn-toolbar mb-2 mb-md-0">
                <a href="./visitorform.php?page=insert" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center"><i class="ti ti-plus me-2"></i> New Visitor</a>
            </div> -->
        </div>

        <!-- Dashboard Summary -->
        <div class="row mb-4 gy-4">
            <!-- Card: Total Evaluations -->
            <div class="col-12 col-md-4">
                <div class="card dashboard-card border-1 shadow">
                    <div class="card-body py-4 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stat-label mb-2">แบบประเมินทั้งหมด</p>
                                <h1 id="totalEvaluations" class="display-count mb-0">0</h1>
                                <p class="text-muted small mb-0 mt-2">
                                    <i class="ti ti-trending-up me-1"></i>
                                    Total Evaluations
                                </p>
                            </div>
                            <div class="icon-circle">
                                <i class="ti ti-clipboard-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Average Chart -->
            <div class="col-12 col-md-8" id="box-chart">
                <div class="card chart-card border-0 shadow">
                    <div class="chart-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h6 class="card-title-enhanced">คะแนนเฉลี่ยการประเมิน</h6>
                                <p class="card-subtitle mb-0">ภาพรวมคะแนนแต่ละคำถาม</p>
                            </div>
                            <span class="chart-badge">
                                <i class="ti ti-chart-bar me-1"></i>
                                หน่วย: คะแนน (1-5)
                            </span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="averageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 p-2 shadow table-wrapper">
            <table class="table table-hover" id="table-visitor">
                <thead>
                    <tr>
                        <th class="border-gray-200">#</th>
                        <th class="border-gray-200">Name</th>
                        <th class="border-gray-200">Phone</th>
                        <th class="border-gray-200">Email</th>
                        <th class="border-gray-200">Purpose</th>
                        <th class="border-gray-200">Suggestion</th>
                        <th class="border-gray-200 text-center">Date</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                
            </table>
            
        </div>

        <?php include('layout/theme-settings.php'); ?>

        <?php //include('layout/footer.php'); ?>

    </main>

    <?php include('js.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(async function() {
            const urlParams = new URLSearchParams(window.location.search);
            const visitorId = urlParams.get('id');

            Swal.fire({
                title: 'กำลังส่งข้อมูล...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            getVisitorData(visitorId);
        });

        async function getVisitorData(visitorId) {
            const $tbody = $('#table-visitor tbody');
            $tbody.html('<tr><td colspan="7" class="text-center text-muted">กำลังโหลดข้อมูล...</td></tr>');

            try {
                const res = await fetch(`api/get_question.php?id=${visitorId}`);
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
                $('#DocNo').html(data.doc_no + ' : ' + data.project_name);

                // ====== ส่วน Dashboard ======
                const totalEvaluations = data.dashboard?.total_evaluations ?? 0;
                const avg = data.dashboard?.average_scores ?? null;

                $('#totalEvaluations').text(data.total_records);

                if(totalEvaluations == 0) {
                    $("#box-chart").hide();
                }

                if (avg && totalEvaluations != 0) {
                    renderAverageChart(avg);
                }

                // ====== ส่วนตาราง ======
                if (d && d.length > 0) {
                    let rows = '';
                    let i = 1;
                    $.each(d, function(index, v) {
                        rows += `
                            <tr>
                                <td>${i}</td>
                                <td>${v.fullname ?? '-'}</td>
                                <td>${v.phone ?? '-'}</td>
                                <td>${v.email ?? '-'}</td>
                                <td>${v.purpose ?? '-'}</td>
                                <td>${v.suggestion ?? '-'}</td>
                                <td class="text-center">${v.created_at}</td>
                            </tr>
                        `;
                        i++;
                    });
                    $tbody.html(rows);

                    if ($.fn.DataTable.isDataTable('#table-visitor')) {
                        $('#table-visitor').DataTable().destroy();
                    }

                    $("#table-visitor").DataTable({
                        responsive: true,
                        scrollX: true
                    });
                }
                else {
                    $tbody.html('<tr><td colspan="7" class="text-center text-muted">ไม่พบข้อมูล</td></tr>');
                }

                Swal.close();

            } catch (err) {
                console.log(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message
                });
            }
        }

        function renderAverageChart(avg) {
            const ctx = document.getElementById('averageChart').getContext('2d');

            const labels = ['คุณภาพและมาตรฐานผลิตภัณฑ์', 'สินค้าเป็นไปตามแบบและข้อตกลง', 'การทดสอบและตรวจรับที่โรงงาน', 'การประสานงาน'];
            const scores = [avg.q1, avg.q2, avg.q3, avg.q4];

            const gradients = [
                createGradient(ctx, '#3b82f6', '#2563eb'),
                createGradient(ctx, '#10b981', '#059669'),
                createGradient(ctx, '#f59e0b', '#d97706'),
                createGradient(ctx, '#ef4444', '#dc2626')
            ];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'คะแนนเฉลี่ย',
                        data: scores,
                        backgroundColor: gradients,
                        borderRadius: 12,
                        borderSkipped: false,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 3,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12,
                                    weight: '500'
                                },
                                color: '#64748b'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            title: {
                                display: true,
                                text: 'คะแนนเฉลี่ย',
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#475569',
                                padding: { top: 0, bottom: 10 }
                            }
                        },
                        x: {
                            ticks: {
                                display: false // 👈 ปิด label ด้านล่าง
                            },
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 13,
                                weight: '600'
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 12,
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return 'คะแนน: ' + context.parsed.y.toFixed(2) + ' / 3.00';
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // ฟังก์ชันสร้าง gradient
        function createGradient(ctx, color1, color2) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        }
    </script>

</body>

</html>