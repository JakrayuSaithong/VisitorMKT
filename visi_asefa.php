<?php

/**
 * visi_asefa.php
 * หน้าลูกค้ายืนยันด้วยเบอร์โทร และดูรายละเอียดการเยี่ยมชม
 */
$token = isset($_GET['visi_token']) ? htmlspecialchars($_GET['visi_token']) : '';
$rawId = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASEFA — ยืนยันการเยี่ยมชม</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #1e3a5f;
            --primary-light: #2d5a87;
            --surface: #ffffff;
            --surface-variant: #f4f6f9;
            --on-surface: #1a1c1e;
            --on-surface-muted: #6b7280;
            --outline: #e2e8f0;
            --success: #16a34a;
            --radius: 16px;
            --radius-sm: 10px;
        }

        body {
            font-family: 'Sarabun', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(160deg, var(--primary) 0%, var(--primary-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .container { width: 100%; max-width: 420px; }

        /* Logo */
        .brand { text-align: center; margin-bottom: 24px; }
        .brand-logo {
            width: 56px; height: 56px;
            background: rgba(255,255,255,0.15);
            border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 28px;
            margin-bottom: 10px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .brand-name { color: #fff; font-size: 22px; font-weight: 600; letter-spacing: 2px; }
        .brand-sub { color: rgba(255,255,255,0.55); font-size: 12px; margin-top: 2px; }

        /* Card */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        .card-header {
            background: var(--primary);
            padding: 20px 24px;
            text-align: center;
        }
        .card-header h2 { color: #fff; font-size: 17px; font-weight: 600; }
        .card-header p { color: rgba(255,255,255,0.6); font-size: 13px; margin-top: 2px; }
        .card-body { padding: 28px 24px; }

        /* Input */
        .field-label { font-size: 13px; color: var(--on-surface-muted); margin-bottom: 8px; font-weight: 500; }
        .phone-input {
            width: 100%;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 4px;
            text-align: center;
            border: 1.5px solid var(--outline);
            border-radius: var(--radius-sm);
            padding: 14px 16px;
            color: var(--on-surface);
            outline: none;
            transition: border-color .2s;
        }
        .phone-input:focus { border-color: var(--primary); }

        /* Buttons */
        .btn {
            display: block; width: 100%;
            padding: 14px;
            border: none; border-radius: var(--radius-sm);
            font-family: inherit; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: opacity .15s, transform .15s;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { opacity: 0.9; }
        .btn-outline {
            background: transparent; color: var(--primary);
            border: 1.5px solid var(--primary);
            font-size: 14px;
        }

        /* Error */
        .error-msg {
            display: none;
            margin-top: 12px;
            padding: 10px 14px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: var(--radius-sm);
            color: #dc2626;
            font-size: 14px;
            text-align: center;
        }

        /* Info section */
        .info-section { display: none; }

        .success-banner {
            background: var(--success);
            color: #fff;
            border-radius: var(--radius-sm);
            padding: 16px;
            text-align: center;
            margin-bottom: 20px;
        }
        .success-banner .welcome { font-size: 14px; opacity: .85; margin-top: 4px; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
        .info-cell {
            background: var(--surface-variant);
            border-radius: var(--radius-sm);
            padding: 14px 12px;
            text-align: center;
        }
        .info-cell .label { font-size: 11px; color: var(--on-surface-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
        .info-cell .value { font-size: 14px; font-weight: 600; color: var(--on-surface); }

        .map-wrap { border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 12px; }
        .map-wrap iframe { display: block; width: 100%; height: 160px; border: 0; }

        /* Footer */
        .footer { text-align: center; color: rgba(255,255,255,0.4); font-size: 12px; margin-top: 20px; }

        @media (max-width: 380px) {
            .phone-input { font-size: 17px; letter-spacing: 3px; }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="brand">
            <div class="brand-logo">🏭</div>
            <div class="brand-name">ASEFA</div>
            <div class="brand-sub">PUBLIC COMPANY LIMITED</div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>ยืนยันการเยี่ยมชมโรงงาน</h2>
                <p>Visitor Verification</p>
            </div>

            <div class="card-body">
                <!-- Verify Form -->
                <div id="verifySection">
                    <p class="field-label">กรุณาใส่เบอร์โทรศัพท์ของท่าน</p>
                    <input type="tel" id="phoneInput" class="phone-input" placeholder="0XX-XXX-XXXX"
                        maxlength="10" inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <div id="errorMsg" class="error-msg"></div>
                    <button class="btn btn-primary" style="margin-top:16px;" onclick="verifyPhone()">
                        ✅ ยืนยันตัวตน
                    </button>
                </div>

                <!-- Info Section -->
                <div id="infoSection" class="info-section">
                    <div class="success-banner">
                        <div style="font-size:28px;margin-bottom:4px;">🎉</div>
                        <div style="font-weight:600;">ยืนยันสำเร็จ!</div>
                        <div class="welcome" id="welcomeName">ยินดีต้อนรับ</div>
                        <div id="docNoDisplay" style="font-size:12px;opacity:.75;margin-top:4px;"></div>
                    </div>

                    <div class="map-wrap">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15512.277156167438!2d100.3413068!3d13.5925789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e2b95b356bbd05%3A0xb71398162e19fb96!2z4Lia4Lij4Li04Lip4Lix4LiXIOC4reC4suC4i-C4teC4n-C4siDguIjguLPguIHguLHguJQg4Lih4Lir4Liy4LiK4LiZ!5e0!3m2!1sth!2sth!4v1770025920138!5m2!1sth!2sth" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <a href="https://maps.app.goo.gl/Em2f1d3cvSRsKiPN6" target="_blank" class="btn btn-outline" style="margin-bottom:16px;display:block;text-align:center;text-decoration:none;">
                        🗺️ เปิดใน Google Maps
                    </a>

                    <div class="info-grid">
                        <div class="info-cell">
                            <div class="label">📅 วันที่</div>
                            <div class="value" id="visitDate">—</div>
                        </div>
                        <div class="info-cell">
                            <div class="label">🕐 เวลา</div>
                            <div class="value" id="visitTime">—</div>
                        </div>
                        <div class="info-cell">
                            <div class="label">🏢 อาคาร/โซน</div>
                            <div class="value" id="visitZone">—</div>
                        </div>
                        <div class="info-cell">
                            <div class="label">👤 ผู้ประสานงาน</div>
                            <div class="value" id="contactPerson">—</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">© <?= date('Y') ?> ASEFA Public Company Limited</div>
    </div>

    <script>
        const token = '<?= $token ?>';
        const rawId = <?= $rawId ?>;

        async function verifyPhone() {
            const phone = document.getElementById('phoneInput').value.trim();
            const errorEl = document.getElementById('errorMsg');
            const btn = document.querySelector('.btn-primary');

            if (!phone || phone.length < 9) {
                showError('⚠️ กรุณาใส่เบอร์โทรศัพท์ที่ถูกต้อง');
                return;
            }

            btn.textContent = '⏳ กำลังตรวจสอบ...';
            btn.disabled = true;
            errorEl.style.display = 'none';

            try {
                let body = `phone=${encodeURIComponent(phone)}`;
                if (token) body += `&visi_token=${encodeURIComponent(token)}`;
                else body += `&id=${rawId}`;

                const res = await fetch('./api/verify_visitor.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body
                });
                const data = await res.json();

                if (data.success) {
                    document.getElementById('verifySection').style.display = 'none';
                    const info = document.getElementById('infoSection');
                    info.style.display = 'block';

                    document.getElementById('welcomeName').textContent = 'ยินดีต้อนรับ คุณ' + (data.name || '');
                    document.getElementById('docNoDisplay').textContent = data.doc_no ? ('เลขที่เอกสาร: ' + data.doc_no) : '';
                    document.getElementById('visitDate').textContent = data.visit_date || '—';
                    document.getElementById('visitTime').textContent = data.visit_time || '—';
                    document.getElementById('visitZone').textContent = data.zone || '—';
                    document.getElementById('contactPerson').textContent = data.contact_person || '—';
                } else {
                    showError('❌ ' + (data.message || 'เบอร์โทรไม่ตรงกับข้อมูลในระบบ'));
                }
            } catch (e) {
                showError('❌ เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
            }

            btn.textContent = '✅ ยืนยันตัวตน';
            btn.disabled = false;
        }

        function showError(msg) {
            const el = document.getElementById('errorMsg');
            el.textContent = msg;
            el.style.display = 'block';
        }

        document.getElementById('phoneInput').addEventListener('keypress', e => {
            if (e.key === 'Enter') verifyPhone();
        });
    </script>
</body>

</html>
