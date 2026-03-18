<?php

/**
 * visi_asefa.php
 * หน้าสำหรับลูกค้ายืนยันด้วยเบอร์โทร และดูรายละเอียดการเยี่ยมชม
 */
// รับ token (encoded) หรือ id (raw)
$token = isset($_GET['visi_token']) ? htmlspecialchars($_GET['visi_token']) : '';
$rawId = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASEFA - ยืนยันการเยี่ยมชม</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e3a5f;
            --primary-light: #2d5a87;
            --accent: #f0b429;
        }

        * {
            font-family: 'Sarabun', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        .logo-box {
            width: 70px;
            height: 70px;
            font-size: 32px;
        }

        .card-custom {
            border-radius: 1.25rem;
            border: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 1.25rem 1.25rem 0 0 !important;
        }

        .input-phone {
            font-size: 1.5rem;
            letter-spacing: 3px;
            text-align: center;
        }

        .btn-verify {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 58, 95, 0.4);
        }

        .info-section {
            display: none;
        }

        .info-section.show {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .info-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        .map-frame {
            height: 180px;
        }

        @media (max-width: 576px) {
            .input-phone {
                font-size: 1.25rem;
                letter-spacing: 2px;
            }

            .map-frame {
                height: 150px;
            }
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center py-4 px-3">
    <div class="container" style="max-width: 460px;">

        <!-- Logo -->
        <div class="text-center mb-4">
            <div class="logo-box bg-white rounded-4 d-inline-flex align-items-center justify-content-center shadow mb-3">🏭</div>
            <h1 class="text-white fw-bold fs-2 mb-1" style="letter-spacing: 3px;">ASEFA</h1>
            <p class="text-white-50 small mb-0">PUBLIC COMPANY LIMITED</p>
        </div>

        <!-- Card -->
        <div class="card card-custom">
            <div class="card-header card-header-custom text-center py-4">
                <h5 class="text-white fw-semibold mb-1">ยืนยันการเยี่ยมชมโรงงาน</h5>
                <small class="text-white-50">Visitor Verification</small>
            </div>

            <div class="card-body p-4">
                <!-- Verification Form -->
                <div id="verifySection">
                    <label class="form-label text-muted small">กรุณาใส่เบอร์โทรศัพท์ของท่าน</label>
                    <input type="tel" id="phoneInput" class="form-control form-control-lg input-phone rounded-3 mb-3"
                        placeholder="0XX-XXX-XXXX" maxlength="10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <button type="button" class="btn btn-verify btn-lg w-100 text-white rounded-3 py-3" onclick="verifyPhone()">
                        ✅ ยืนยันตัวตน
                    </button>
                    <div id="errorMsg" class="alert alert-danger text-center mt-3 py-2 d-none"></div>
                </div>

                <!-- Info Section -->
                <div id="infoSection" class="info-section">
                    <!-- Success Banner -->
                    <div class="bg-success bg-gradient text-white text-center rounded-3 p-3 mb-4">
                        <div class="fs-1 mb-1">🎉</div>
                        <div class="fw-semibold">ยืนยันสำเร็จ!</div>
                        <small id="welcomeName">ยินดีต้อนรับ</small>
                    </div>

                    <!-- Map -->
                    <div class="rounded-3 overflow-hidden shadow-sm mb-3">
                        <iframe class="w-100 map-frame border-0"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15512.277156167438!2d100.3413068!3d13.5925789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e2b95b356bbd05%3A0xb71398162e19fb96!2z4Lia4Lij4Li04Lip4Lix4LiXIOC4reC4suC4i-C4teC4n-C4siDguIjguLPguIHguLHguJQg4Lih4Lir4Liy4LiK4LiZ!5e0!3m2!1sth!2sth!4v1770025920138!5m2!1sth!2sth" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <a href="https://maps.app.goo.gl/Em2f1d3cvSRsKiPN6" target="_blank" class="btn btn-outline-primary w-100 mb-4 rounded-3">
                        🗺️ เปิดใน Google Maps
                    </a>

                    <!-- Info Grid -->
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center h-100">
                                <div class="info-icon rounded-3 d-inline-flex align-items-center justify-content-center mb-2">📅</div>
                                <div class="text-muted small text-uppercase">วันที่</div>
                                <div class="fw-semibold" id="visitDate">-</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center h-100">
                                <div class="info-icon rounded-3 d-inline-flex align-items-center justify-content-center mb-2">🕐</div>
                                <div class="text-muted small text-uppercase">เวลา</div>
                                <div class="fw-semibold" id="visitTime">-</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center h-100">
                                <div class="info-icon rounded-3 d-inline-flex align-items-center justify-content-center mb-2">🏢</div>
                                <div class="text-muted small text-uppercase">อาคาร/โซน</div>
                                <div class="fw-semibold" id="visitZone">-</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center h-100">
                                <div class="info-icon rounded-3 d-inline-flex align-items-center justify-content-center mb-2">👤</div>
                                <div class="text-muted small text-uppercase">ผู้ประสานงาน</div>
                                <div class="fw-semibold" id="contactPerson">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-white-50 small mt-4 mb-0">
            © <?= date('Y') ?> ASEFA Public Company Limited
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // รับ token หรือ id จาก PHP
        const token = '<?= $token ?>';
        const rawId = <?= $rawId ?>;

        async function verifyPhone() {
            const phone = document.getElementById('phoneInput').value.trim();
            const errorMsg = document.getElementById('errorMsg');
            const btn = document.querySelector('.btn-verify');

            if (!phone || phone.length < 9) {
                errorMsg.textContent = '⚠️ กรุณาใส่เบอร์โทรศัพท์ที่ถูกต้อง';
                errorMsg.classList.remove('d-none');
                return;
            }

            btn.innerHTML = '⏳ กำลังตรวจสอบ...';
            btn.disabled = true;
            errorMsg.classList.add('d-none');

            try {
                // ส่ง token หรือ id ไปยัง API
                let bodyData = `phone=${encodeURIComponent(phone)}`;
                if (token) {
                    bodyData += `&visi_token=${encodeURIComponent(token)}`;
                } else {
                    bodyData += `&id=${rawId}`;
                }

                const res = await fetch('./api/verify_visitor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: bodyData
                });
                const data = await res.json();

                if (data.success) {
                    document.getElementById('verifySection').style.display = 'none';
                    document.getElementById('infoSection').classList.add('show');

                    document.getElementById('welcomeName').textContent = 'ยินดีต้อนรับ คุณ' + (data.name || '');
                    document.getElementById('visitDate').textContent = data.visit_date || '-';
                    document.getElementById('visitTime').textContent = data.visit_time || '-';
                    document.getElementById('visitZone').textContent = data.zone || '-';
                    document.getElementById('contactPerson').textContent = data.contact_person || '-';
                } else {
                    errorMsg.textContent = '❌ ' + (data.message || 'เบอร์โทรไม่ตรงกับข้อมูลในระบบ');
                    errorMsg.classList.remove('d-none');
                }
            } catch (e) {
                errorMsg.textContent = '❌ เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง';
                errorMsg.classList.remove('d-none');
            }

            btn.innerHTML = '✅ ยืนยันตัวตน';
            btn.disabled = false;
        }

        document.getElementById('phoneInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') verifyPhone();
        });
    </script>
</body>

</html>