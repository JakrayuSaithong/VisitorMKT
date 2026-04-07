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
        body, .content {
            background-color: #f1f8ffff !important; /* Soft Slate-50 background */
        }

        canvas { max-height: 280px; }

        .dashboard-card {
            height: 100%;
            border: 1px solid #e2e8f0; /* Crisp slate-200 border */
            border-radius: 16px;
            background: #ffffff;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            padding: 1.5rem;
            gap: 1.25rem;
        }

        .dashboard-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            transform: translateY(-4px);
            border-bottom-color: transparent;
        }

        .icon-box {
            width: 56px;
            height: 56px;
            flex-shrink: 0;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dashboard-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        /* Colorful SaaS Accents */
        .card-new { border-bottom: 4px solid transparent; }
        .card-new .icon-box { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
        .card-new:hover { border-bottom-color: #3b82f6; }

        .card-accept { border-bottom: 4px solid transparent; }
        .card-accept .icon-box { background: rgba(6, 182, 212, 0.15); color: #06b6d4; }
        .card-accept:hover { border-bottom-color: #06b6d4; }

        .card-approved { border-bottom: 4px solid transparent; }
        .card-approved .icon-box { background: rgba(16, 185, 129, 0.15); color: #10b981; }
        .card-approved:hover { border-bottom-color: #10b981; }

        .card-submit { border-bottom: 4px solid transparent; }
        .card-submit .icon-box { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
        .card-submit:hover { border-bottom-color: #f59e0b; }

        .card-closed { border-bottom: 4px solid transparent; }
        .card-closed .icon-box { background: rgba(100, 116, 139, 0.15); color: #64748b; }
        .card-closed:hover { border-bottom-color: #64748b; }

        .card-visitor { border-bottom: 4px solid transparent; }
        .card-visitor .icon-box { background: rgba(99, 102, 241, 0.15); color: #6366f1; }
        .card-visitor:hover { border-bottom-color: #6366f1; }

        .card-quiz { border-bottom: 4px solid transparent; }
        .card-quiz .icon-box { background: rgba(244, 63, 94, 0.15); color: #f43f5e; }
        .card-quiz:hover { border-bottom-color: #f43f5e; }

        .card-objective { border-bottom: 4px solid transparent; }
        .card-objective .icon-box { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
        .card-objective:hover { border-bottom-color: #8b5cf6; }

        .card-content {
            flex-grow: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .dashboard-card p {
            font-size: 13px;
            color: var(--md-on-surface-variant, #64748b);
            margin-bottom: 2px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dashboard-card h3 {
            color: var(--md-on-surface, #0f172a);
            font-weight: 800;
            font-size: 28px;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .dashboard-card h5 {
            color: var(--md-on-surface, #0f172a);
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dashboard-card small {
            color: var(--md-on-surface-variant, #94a3b8);
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Charts Section */
        .chart-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            height: 100%;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .chart-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .chart-card .card-header {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            color: var(--md-on-surface, #1e293b);
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .chart-card .card-body {
            padding: 1.5rem;
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
            <div class="row g-4">
                <!-- New -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-new" style="cursor: pointer;" onclick="window.location.href='listvisitor.php?page=listvisitor&status=1'">
                        <div class="icon-box">
                            <i class="ti ti-sparkles"></i>
                        </div>
                        <div class="card-content">
                            <p>New</p>
                            <h3 id="status-new">0</h3>
                            <small>สถานะใหม่ทั้งหมด</small>
                        </div>
                    </div>
                </div>

                <!-- Accepted -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-accept" style="cursor: pointer;" onclick="window.location.href='listvisitor.php?page=listvisitor&status=2'">
                        <div class="icon-box">
                            <i class="ti ti-check"></i>
                        </div>
                        <div class="card-content">
                            <p>Accepted</p>
                            <h3 id="status-acept">0</h3>
                            <small>รับทราบการนัดหมาย</small>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-approved" style="cursor: pointer;" onclick="window.location.href='listvisitor.php?page=listvisitor&status=3'">
                        <div class="icon-box">
                            <i class="ti ti-user-check"></i>
                        </div>
                        <div class="card-content">
                            <p>Approved</p>
                            <h3 id="status-approved">0</h3>
                            <small>ที่ได้รับการอนุมัติ</small>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-submit" style="cursor: pointer;" onclick="window.location.href='listvisitor.php?page=listvisitor&status=5'">
                        <div class="icon-box">
                            <i class="ti ti-send"></i>
                        </div>
                        <div class="card-content">
                            <p>Submit</p>
                            <h3 id="status-submit">0</h3>
                            <small>ขออนุมัติเข้าพบ</small>
                        </div>
                    </div>
                </div>

                <!-- Closed -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-closed" style="cursor: pointer;" onclick="window.location.href='listvisitor.php?page=listvisitor&status=6'">
                        <div class="icon-box">
                            <i class="ti ti-archive"></i>
                        </div>
                        <div class="card-content">
                            <p>Closed</p>
                            <h3 id="status-closed">0</h3>
                            <small>ที่ปิดงานแล้ว</small>
                        </div>
                    </div>
                </div>

                <!-- Total Visitor -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-visitor">
                        <div class="icon-box">
                            <i class="ti ti-users"></i>
                        </div>
                        <div class="card-content">
                            <p>Total Visitor</p>
                            <h3 id="total-visitor">0</h3>
                            <small>จำนวนผู้เยี่ยมชมทั้งหมด</small>
                        </div>
                    </div>
                </div>

                <!-- Total Quiz -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-quiz">
                        <div class="icon-box">
                            <i class="ti ti-clipboard-list"></i>
                        </div>
                        <div class="card-content">
                            <p>Total Quiz</p>
                            <h3 id="total-quiz">0</h3>
                            <small>จำนวนแบบสอบถามทั้งหมด</small>
                        </div>
                    </div>
                </div>

                <!-- Top Objective -->
                <div class="col-sm-6 col-md-3">
                    <div class="dashboard-card card-objective">
                        <div class="icon-box">
                            <i class="ti ti-target"></i>
                        </div>
                        <div class="card-content">
                            <p>Top Objective</p>
                            <h5 id="top-objective">-</h5>
                            <small id="top-objective-detail">ไม่มีข้อมูล</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row mt-4 g-4">
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="card-header"><i class="ti ti-chart-pie me-2" style="color: #3b82f6;"></i>Visitor Type Summary</div>
                        <div class="card-body">
                            <canvas id="visitorTypeChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="card-header"><i class="ti ti-chart-donut me-2" style="color: #f43f5e;"></i>Purpose Summary</div>
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
                $('#status-approved').text(status.Approved);
                $('#status-submit').text(status.Submit);
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