<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>แบบฟอร์มเยี่ยมชม/ทดสอบผลิตภัณฑ์ - ASEFA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font (Kanit) -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <style>
        /* === ตัดให้เหลือเท่าที่จำเป็น === */
        :root {
            --primary: #007AFF;
            --ok: #34C759;
        }

        body {
            font-family: "Kanit", sans-serif;
            background: #003865;
        }

        .card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, .08);
        }

        .card-header {
            border-bottom: 1px solid #eef2f6;
            background: #fff;
            border-radius: 16px 16px 0 0;
        }

        .card-header h1 {
            font-size: 18px;
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .card-body {
            background: #fff;
            border-radius: 0 0 16px 16px;
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        .form-control input[type="text"],
        .form-select {
            border: 0;
            border-bottom: 1px solid #e1e5e9;
            border-radius: 0;
            padding-left: 0;
        }

        .form-control input[type="text"]:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: var(--primary);
        }

        .required .form-label::after {
            content: " *";
            color: red;
            font-weight: 600;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #0056cc;
            border-color: #0056cc;
        }

        /* ตารางประเมิน */
        .table thead th {
            white-space: nowrap;
        }

        .eval-wrap {
            display: none;
        }

        /* เริ่มต้นซ่อนไว้ */
        .eval-required {
            border: 1px dashed #f5c2c7;
            padding: 8px;
            border-radius: 8px;
        }

        input::placeholder, textarea::placeholder {
            color: #c1c7cd;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-center align-items-center mb-3">
            <img src="https://it.asefa.co.th/corpCommAsefa/projectTemca/assets/media/logos/text_logo.png" height="46" alt="ASEFA">
        </div>

        <div class="card">
            <div class="card-header px-4 py-3">
                <h1>แบบฟอร์มข้อมูลผู้เยี่ยมชม / ทดสอบผลิตภัณฑ์</h1>
                <small class="text-muted">กรอกข้อมูลให้ครบถ้วนตามที่ระบุ</small>
            </div>
            <div class="card-body px-4 py-4">
                <form id="visitForm" novalidate>
                    <!-- บริษัท -->
                    <div class="mb-3 required">
                        <label for="companySelect" class="form-label">บริษัท / Company</label>
                        <select id="companySelect" class="form-select mb-2">
                            <option value="">-- เลือกบริษัท --</option>
                            <option value="__other__">อื่นๆ (พิมพ์เอง)</option>
                        </select>
                        <input type="text" id="company" name="company" class="form-control d-none" placeholder="พิมพ์ชื่อบริษัท">
                    </div>

                    <!-- ชื่อ-นามสกุล -->
                    <div class="mb-3 required">
                        <label for="fullname" class="form-label">ชื่อ-นามสกุล / Name</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" placeholder="ชื่อ นามสกุล">
                    </div>

                    <!-- เบอร์โทร -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">เบอร์โทร / Tel.</label>
                        <input type="tel" id="phone" name="phone" class="form-control" inputmode="numeric">
                        <div class="form-text">ต้องขึ้นต้นด้วย 06, 08, หรือ 09 และยาว 10 หลัก</div>
                    </div>

                    <!-- อีเมล (ไม่บังคับ) -->
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>

                    <!-- โครงการ -->
                    <div class="mb-3 required">
                        <label for="projectSelect" class="form-label">โครงการ/Project</label>
                        <select id="projectSelect" class="form-select mb-2">
                            <option value="">-- เลือกโครงการ --</option>
                            <option value="__other__">อื่นๆ (พิมพ์เอง)</option>
                        </select>
                        <input type="text" id="project" name="project" class="form-control d-none" placeholder="พิมพ์ชื่อโครงการ">
                    </div>

                    <!-- วัตถุประสงค์ -->
                    <div class="mb-3 required">
                        <label class="form-label d-block mb-2">วัตถุประสงค์ / Objective</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="purpose" id="purpose_visit" value="เยี่ยมชมโรงงาน">
                            <label class="form-check-label" for="purpose_visit">เยี่ยมชมโรงงาน</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="purpose" id="purpose_test" value="ทดสอบผลิตภัณฑ์">
                            <label class="form-check-label" for="purpose_test">ทดสอบผลิตภัณฑ์</label>
                        </div>
                    </div>

                    <!-- ตารางประเมิน (แสดงเมื่อเลือกทดสอบผลิตภัณฑ์) -->
                    <div id="evaluationBox" class="eval-wrap mb-4">
                        <div class="mb-2 text-danger small">* ต้องประเมินทุกหัวข้อ</div>
                        <div class="table-responsive eval-required">
                            <table class="table table-bordered align-middle mb-0" style="font-size: 12px;">
                                <thead class="table-light" style="font-size: 10px;">
                                    <tr>
                                        <th class="text-center" style="width: 70%"></th>
                                        <th class="text-center">พึ่งพอใจมาก<br><small>Very satisfied</small></th>
                                        <th class="text-center">พึ่งพอใจ<br><small>Satisfied</small></th>
                                        <th class="text-center">ต้องปรับปรุง<br><small>Unsatisfied</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1. คุณภาพและมาตรฐานผลิตภัณฑ์ / Quality and Standard of Product</td>
                                        <td class="text-center"><input type="radio" name="eval[q1]" value="3"></td>
                                        <td class="text-center"><input type="radio" name="eval[q1]" value="2"></td>
                                        <td class="text-center"><input type="radio" name="eval[q1]" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td>2. สินค้าเป็นไปตามแบบ ข้อกำหนดและข้อตกลง / Product compliance to specification and requirement</td>
                                        <td class="text-center"><input type="radio" name="eval[q2]" value="3"></td>
                                        <td class="text-center"><input type="radio" name="eval[q2]" value="2"></td>
                                        <td class="text-center"><input type="radio" name="eval[q2]" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td>3. การทดสอบและตรวจรับที่โรงงาน / Testing and Inspection at the factory</td>
                                        <td class="text-center"><input type="radio" name="eval[q3]" value="3"></td>
                                        <td class="text-center"><input type="radio" name="eval[q3]" value="2"></td>
                                        <td class="text-center"><input type="radio" name="eval[q3]" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td>4. การประสานงานก่อนและระหว่างการทดสอบผลิตภัณฑ์และเยี่ยมชม / Coordination before and during FAT & Factory Tour</td>
                                        <td class="text-center"><input type="radio" name="eval[q4]" value="3"></td>
                                        <td class="text-center"><input type="radio" name="eval[q4]" value="2"></td>
                                        <td class="text-center"><input type="radio" name="eval[q4]" value="1"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ข้อเสนอแนะ -->
                    <div class="mb-4">
                        <label for="suggestion" class="form-label">ข้อเสนอแนะ / Suggestions for improvement</label>
                        <textarea id="suggestion" name="suggestion" rows="4" class="form-control" placeholder="ข้อเสนอแนะของท่าน"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">ส่งข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const phoneRegex = /^(06|08|09)\d{8}$/;
        const urlParams = new URLSearchParams(window.location.search);
        const visitorId = urlParams.get('id');

        $("#phone").on("input", function() {
            let v = $(this).val().replace(/\D/g, "").slice(0, 10);
            $(this).val(v);
        });

        // Load SalesDetail data for select options
        if (visitorId) {
            fetch(`api/get_sales_detail.php?visitor_id=${visitorId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status && data.data) {
                        // Populate company select
                        const companySelect = $("#companySelect");
                        (data.data.companies || []).forEach(c => {
                            companySelect.find('option[value="__other__"]').before(`<option value="${c}">${c}</option>`);
                        });
                        
                        // Populate project select
                        const projectSelect = $("#projectSelect");
                        (data.data.projects || []).forEach(p => {
                            projectSelect.find('option[value="__other__"]').before(`<option value="${p}">${p}</option>`);
                        });
                    }
                })
                .catch(err => console.log('SalesDetail load error:', err));
        }
        
        // Handle select change - show/hide custom input
        $("#companySelect").on("change", function() {
            const val = $(this).val();
            if (val === "__other__") {
                $("#company").removeClass("d-none").val("").focus();
            } else {
                $("#company").addClass("d-none").val(val);
            }
        });
        
        $("#projectSelect").on("change", function() {
            const val = $(this).val();
            if (val === "__other__") {
                $("#project").removeClass("d-none").val("").focus();
            } else {
                $("#project").addClass("d-none").val(val);
            }
        });

        $("input[name='purpose']").on("change", function() {
            if ($(this).val() === "ทดสอบผลิตภัณฑ์") {
                $("#evaluationBox").slideDown(150);
            } else {
                $("#evaluationBox").slideUp(150);
                $("#evaluationBox input[type=radio]").prop("checked", false);
            }
        });

        function validateForm() {
            const req = {
                company: $("#company").val().trim(),
                fullname: $("#fullname").val().trim(),
                phone: $("#phone").val().trim(),
                project: $("#project").val().trim(),
                purpose: $("input[name='purpose']:checked").val(),
                suggestion: $("#suggestion").val().trim(),
            };

            if (!req.company) return ["กรุณากรอก 'บริษัท'"];
            if (!req.fullname) return ["กรุณากรอก 'ชื่อ-นามสกุล'"];
            // if (!phoneRegex.test(req.phone)) return ["กรุณากรอก 'เบอร์โทร' ให้ถูกต้อง (ขึ้นต้น 06/08/09 และ 10 หลัก)"];
            if (!req.project) return ["กรุณากรอก 'โครงการ'"];
            if (!req.purpose) return ["กรุณาเลือก 'วัตถุประสงค์'"];
            if (req.purpose === "ทดสอบผลิตภัณฑ์") {
                const must = ["q1", "q2", "q3", "q4"];
                for (const k of must) {
                    if (!$(`input[name='eval[${k}]']:checked`).val()) {
                        return ["กรุณาประเมินทุกหัวข้อในตาราง (เมื่อเลือกทดสอบผลิตภัณฑ์)"];
                    }
                }
            }
            // if (!req.suggestion) return ["กรุณากรอก 'ข้อเสนอแนะ'"];
            return null;
        }

        $("#visitForm").on("submit", function(e) {
            e.preventDefault();

            const error = validateForm();
            if (error) {
                Swal.fire({
                    icon: "warning",
                    title: error[0]
                });
                return;
            }

            const payload = {
                company: $("#company").val().trim(),
                fullname: $("#fullname").val().trim(),
                phone: $("#phone").val().trim(),
                email: $("#email").val().trim(),
                project: $("#project").val().trim(),
                purpose: $("input[name='purpose']:checked").val(), // visit | test
                suggestion: $("#suggestion").val().trim(),
                eval: {
                    q1: $(`input[name='eval[q1]']:checked`).val() || null,
                    q2: $(`input[name='eval[q2]']:checked`).val() || null,
                    q3: $(`input[name='eval[q3]']:checked`).val() || null,
                    q4: $(`input[name='eval[q4]']:checked`).val() || null
                }
            };

            $.ajax({
                url: "api/visi_question_api.php",
                type: "POST",
                data: {
                    VisitorFormId: visitorId,
                    data: JSON.stringify(payload)
                },
                dataType: "json",
                success: function(res) {
                    if (res && res.status === true) {
                        Swal.fire({
                            icon: "success",
                            title: "บันทึกข้อมูลเรียบร้อย",
                            confirmButtonText: "ตกลง"
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: res?.message || "บันทึกไม่สำเร็จ"
                        });
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    Swal.fire({
                        icon: "error",
                        title: "เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์"
                    });
                }
            });
        });
    </script>
</body>

</html>