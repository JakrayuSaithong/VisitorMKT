<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>แบบฟอร์มประเมิน - ASEFA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <style>
        :root { --primary: #007AFF; }
        body { font-family: "Kanit", sans-serif; background: #003865; }
        .card { border: 0; border-radius: 16px; box-shadow: 0 6px 24px rgba(0,0,0,.08); }
        .card-header { border-bottom: 1px solid #eef2f6; background: #fff; border-radius: 16px 16px 0 0; }
        .card-header h1 { font-size: 18px; margin: 0; font-weight: 600; color: #333; }
        .card-body { background: #fff; border-radius: 0 0 16px 16px; }
        .form-label { font-weight: 500; color: #333; }
        .required .form-label::after { content: " *"; color: red; font-weight: 600; }
        .btn-primary { background: var(--primary); border-color: var(--primary); border-radius: 12px; font-weight: 600; }
        .btn-primary:hover { background: #0056cc; border-color: #0056cc; }
        .table thead th { white-space: nowrap; }
        .eval-wrap { display: none; }
        .eval-required { border: 1px dashed #f5c2c7; padding: 8px; border-radius: 8px; }
        textarea::placeholder { color: #c1c7cd; font-size: 14px; }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-center align-items-center mb-3">
            <img src="https://it.asefa.co.th/corpCommAsefa/projectTemca/assets/media/logos/text_logo.png" height="46" alt="ASEFA">
        </div>

        <div class="card">
            <div class="card-header px-4 py-3">
                <h1>แบบประเมินความพึงพอใจ</h1>
                <small class="text-muted">กรอกข้อมูลให้ครบถ้วนตามที่ระบุ</small>
            </div>
            <div class="card-body px-4 py-4">
                <form id="visitForm" novalidate>
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
        function decodeEvalId(token) {
            const key = 'ASEFA_EVAL_2024';
            try {
                let base64 = token.replace(/-/g, '+').replace(/_/g, '/');
                const pad = base64.length % 4;
                if (pad) base64 += '='.repeat(4 - pad);
                const encoded = atob(base64);
                let decoded = '';
                for (let i = 0; i < encoded.length; i++) {
                    decoded += String.fromCharCode(encoded.charCodeAt(i) ^ key.charCodeAt(i % key.length));
                }
                return parseInt(decoded.split('|')[0]) || 0;
            } catch(e) { return 0; }
        }

        const urlParams = new URLSearchParams(window.location.search);
        const evalToken = urlParams.get('eval_token');
        const visitorId = evalToken ? decodeEvalId(evalToken) : parseInt(urlParams.get('id') || '0');

        $("input[name='purpose']").on("change", function() {
            if ($(this).val() === "ทดสอบผลิตภัณฑ์") {
                $("#evaluationBox").slideDown(150);
            } else {
                $("#evaluationBox").slideUp(150);
                $("#evaluationBox input[type=radio]").prop("checked", false);
            }
        });

        function validateForm() {
            const purpose = $("input[name='purpose']:checked").val();
            if (!purpose) return ["กรุณาเลือก 'วัตถุประสงค์'"];
            if (purpose === "ทดสอบผลิตภัณฑ์") {
                for (const k of ["q1","q2","q3","q4"]) {
                    if (!$(`input[name='eval[${k}]']:checked`).val()) {
                        return ["กรุณาประเมินทุกหัวข้อในตาราง"];
                    }
                }
            }
            return null;
        }

        $("#visitForm").on("submit", function(e) {
            e.preventDefault();
            const error = validateForm();
            if (error) {
                Swal.fire({ icon: "warning", title: error[0] });
                return;
            }

            const payload = {
                purpose: $("input[name='purpose']:checked").val(),
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
                data: { VisitorFormId: visitorId, data: JSON.stringify(payload) },
                dataType: "json",
                success: function(res) {
                    if (res && res.status === true) {
                        Swal.fire({ icon: "success", title: "บันทึกข้อมูลเรียบร้อย", confirmButtonText: "ตกลง" })
                            .then(() => location.reload());
                    } else {
                        Swal.fire({ icon: "error", title: res?.message || "บันทึกไม่สำเร็จ" });
                    }
                },
                error: function() {
                    Swal.fire({ icon: "error", title: "เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์" });
                }
            });
        });
    </script>
</body>

</html>
