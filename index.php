<?php
include_once 'config/base.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Visitor Company - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Themesberg">

    <?php include('css.php'); ?>

    <style>
        canvas { max-height: 280px; }

        .dashboard-card {
            height: 100%;
            border: none;
            border-radius: var(--md-shape-md);
            background: var(--md-surface);
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
            box-shadow: var(--md-elevation-1);
            border-left: 4px solid transparent;
        }

        .dashboard-card:hover {
            box-shadow: var(--md-elevation-3);
            transform: translateY(-3px);
        }

        .icon-box {
            width: 52px;
            height: 52px;
            border-radius: var(--md-shape-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.2s ease;
        }

        .icon-new { background: linear-gradient(135deg, #d3e3fd, #a8c7fa); color: #041e49; }
        .icon-accept { background: linear-gradient(135deg, #b8f0cb, #7ee4a0); color: #00391a; }
        .icon-closed { background: linear-gradient(135deg, #dce4ef, #b8c6d9); color: #1a2633; }
        .icon-visitor { background: linear-gradient(135deg, #ede9fe, #c4b5fd); color: #5b21b6; }
        .icon-quiz { background: linear-gradient(135deg, #fef3c7, #fcd34d); color: #92400e; }
        .icon-objective { background: linear-gradient(135deg, #d1fae5, #6ee7b7); color: #065f46; }

        .dashboard-card.card-new { border-left-color: var(--md-primary); }
        .dashboard-card.card-accept { border-left-color: var(--md-tertiary); }
        .dashboard-card.card-closed { border-left-color: var(--md-secondary); }
        .dashboard-card.card-visitor { border-left-color: #7c3aed; }
        .dashboard-card.card-quiz { border-left-color: #f9ab00; }
        .dashboard-card.card-objective { border-left-color: #059669; }

        .dashboard-card p {
            font-size: 13px;
            color: var(--md-on-surface-variant);
            margin-bottom: 4px;
            font-weight: 500;
        }

        .dashboard-card h3 {
            color: var(--md-on-surface);
            font-weight: 700;
            font-size: 28px;
        }

        .dashboard-card h5 {
            color: var(--md-on-surface);
            font-weight: 600;
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

        <?php include('layout/theme-settings.php'); ?>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 mt-4">
            <h4 class="fw-bold mb-0" style="color: var(--md-on-surface);">Visitor Dashboard</h4>
            <!-- <div class="d-flex align-items-center gap-2">
                <input type="month" id="monthPicker" class="form-control asefa-month-picker">
                <button id="btnFilter" class="btn btn-primary fw-semibold shadow-sm w-100">
                    <i class="ti ti-filter me-1"></i> ค้นหา
                </button>
            </div> -->
        </div>

        <div class="mt-4 mb-4">
            <div class="row g-3">
                <!-- New -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-new d-flex justify-content-between align-items-center p-3">
                        <div>
                            <p class="mb-1">New</p>
                            <h3 id="status-new" class="mb-0">0</h3>
                            <small>สถานะใหม่ทั้งหมด</small>
                        </div>
                        <div class="icon-box icon-new">
                            <i class="ti ti-sparkles"></i>
                        </div>
                    </div>
                </div>

                <!-- Accepted -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-accept d-flex justify-content-between align-items-center p-3">
                        <div>
                            <p class="mb-1">Accepted</p>
                            <h3 id="status-acept" class="mb-0">0</h3>
                            <small>ที่ได้รับการอนุมัติ</small>
                        </div>
                        <div class="icon-box icon-accept">
                            <i class="ti ti-circle-check"></i>
                        </div>
                    </div>
                </div>

                <!-- Closed -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-closed d-flex justify-content-between align-items-center p-3">
                        <div>
                            <p class="mb-1">Closed</p>
                            <h3 id="status-closed" class="mb-0">0</h3>
                            <small>ที่ปิดงานแล้ว</small>
                        </div>
                        <div class="icon-box icon-closed">
                            <i class="ti ti-archive"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Visitor -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-visitor d-flex justify-content-between align-items-center p-3">
                        <div>
                            <p class="mb-1">Total Visitor</p>
                            <h3 id="total-visitor" class="mb-0">0</h3>
                            <small>จำนวนผู้เยี่ยมชมทั้งหมด</small>
                        </div>
                        <div class="icon-box icon-visitor">
                            <i class="ti ti-users"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Quiz -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-quiz d-flex justify-content-between align-items-center p-3">
                        <div>
                            <p class="mb-1">Total Quiz</p>
                            <h3 id="total-quiz" class="mb-0">0</h3>
                            <small>จำนวนแบบสอบถามทั้งหมด</small>
                        </div>
                        <div class="icon-box icon-quiz">
                            <i class="ti ti-clipboard-list"></i>
                        </div>
                    </div>
                </div>

                <!-- Top Objective -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-objective d-flex justify-content-between align-items-center p-3">
                        <div class="w-75">
                            <p class="mb-1">Top Objective</p>
                            <h5 id="top-objective" class="mb-0">-</h5>
                            <small id="top-objective-detail">ไม่มีข้อมูล</small>
                        </div>
                        <div class="icon-box icon-objective">
                            <i class="ti ti-target"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><i class="ti ti-chart-pie me-2" style="color: var(--md-primary);"></i>Visitor Type Summary</div>
                        <div class="card-body">
                            <canvas id="visitorTypeChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><i class="ti ti-chart-donut me-2" style="color: var(--md-primary);"></i>Purpose Summary</div>
                        <div class="card-body">
                            <canvas id="purposeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php //include('layout/footer.php'); 
        ?>

    </main>

    <?php include('js.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>


    <script>
        $(document).ready(async function() {
            const today = new Date();
            const monthStr = today.toISOString().slice(0, 7);
            $('#monthPicker').val(monthStr);

            Swal.fire({
                title: 'กำลังโหลดข้อมูล...',
                text: 'โปรดรอสักครู่',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            await getVisitorDataDashboard(monthStr);

            $('#btnFilter').on('click', async function() {
                const selectedMonth = $('#monthPicker').val();
                if (!selectedMonth) return;

                Swal.fire({
                    title: 'กำลังโหลดข้อมูล...',
                    text: 'โปรดรอสักครู่',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                await getVisitorDataDashboard(selectedMonth);
            });
        });

        async function getVisitorDataDashboard(month) {
            try {
                const [response, response_ob] = await Promise.all([
                    fetch(`api/dashboard_visi.php?date=${month}-01`),
                    fetch('api/objective_action.php')
                ]);

                const data = await response.json();
                const data_ob = await response_ob.json();

                console.log(data);

                Swal.close();

                const status = data.StatusSummary;
                const visitor = data.VisitorSummary;
                const purpose = data.PurposeSummary || [];

                $('#status-new').text(status.New);
                $('#status-acept').text(status.Acept);
                $('#status-closed').text(status.Closed);

                const quiz = data.QuizSummary;
                $('#total-visitor').text(visitor.TotalCustomers);
                $('#total-quiz').text(quiz.TotalQuiz);

                const objective = data.ObjectiveTop;
                const objectiveMap = {};
                data_ob.data.forEach(o => {
                    objectiveMap[o.visit_objective_id] = o.visit_objective_name;
                });
                const topObjectiveName = objectiveMap[objective.ObjectiveType] || `ID ${objective.ObjectiveType}`;
                $('#top-objective').text(topObjectiveName || 'ไม่มีข้อมูล');
                $('#top-objective-detail').text(`จำนวน: ${objective.Frequency}`);

                // Visitor Pie Chart
                const ctxVisitor = document.getElementById('visitorTypeChart').getContext('2d');
                new Chart(ctxVisitor, {
                    type: 'pie',
                    data: {
                        labels: ['ลูกค้าไทย', 'ลูกค้าต่างประเทศ'],
                        datasets: [{
                            data: [visitor.ThaiCustomers, visitor.ForeignCustomers],
                            backgroundColor: ['#007bff', '#ffc107']
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            datalabels: {
                                color: '#fff',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: (value, context) => {
                                    const total = context.chart._metasets[0].total;
                                    const percentage = ((value / total) * 100).toFixed(1) + '%';
                                    return percentage;
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const dataset = context.dataset;
                                        const total = dataset.data.reduce((a, b) => a + b, 0);
                                        const currentValue = dataset.data[context.dataIndex];
                                        const percentage = ((currentValue / total) * 100).toFixed(1);
                                        return `${context.label}: ${currentValue} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                // Purpose Pie Chart
                const ctxPurpose = document.getElementById('purposeChart').getContext('2d');
                new Chart(ctxPurpose, {
                    type: 'pie',
                    data: {
                        labels: purpose.map(p => p.Purpose),
                        datasets: [{
                            data: purpose.map(p => p.Total),
                            backgroundColor: [
                                '#0056a6',
                                '#009fe3',
                                '#ffc107',
                                '#00b894',
                                '#e74c3c'
                            ],
                            borderWidth: 1,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#333',
                                    font: {
                                        size: 13
                                    }
                                }
                            },
                            datalabels: {
                                color: '#fff',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: (value, context) => {
                                    const total = context.chart._metasets[0].total;
                                    const percentage = ((value / total) * 100).toFixed(1) + '%';
                                    return percentage;
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const dataset = context.dataset;
                                        const total = dataset.data.reduce((a, b) => a + b, 0);
                                        const currentValue = dataset.data[context.dataIndex];
                                        const percentage = ((currentValue / total) * 100).toFixed(1);
                                        return `${context.label}: ${currentValue} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

            } catch (error) {
                console.error(error);
                Swal.close();
            }
        }
    </script>
</body>

</html>