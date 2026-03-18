# VisitorMKT — ระบบบริหารจัดการผู้มาเยือน

ระบบบริหารจัดการผู้มาเยือน (Visitor Management) สำหรับองค์กร ใช้ติดตาม การนัดหมาย วัตถุประสงค์การเยี่ยมชม และสิทธิ์การเข้าถึงของผู้ใช้งาน

---

## ภาพรวมระบบ

VisitorMKT เป็นเว็บแอปพลิเคชันแบบ Vanilla PHP (ไม่ใช้ Framework) ที่รองรับกระบวนการทำงานตั้งแต่การบันทึกข้อมูลผู้เยี่ยมชม ไปจนถึงการอนุมัติและปิดงาน โดยมีสถานะหลักดังนี้:

```
Draft → New → Accept → Approved → Closed
                     ↘ Rework
                     ↘ Cancel
```

---

## Tech Stack

| Layer      | Technology                                          |
|------------|-----------------------------------------------------|
| Backend    | PHP 7/8 (procedural)                                |
| Frontend   | Bootstrap 5.2.3, jQuery 3.7.1, DataTables 2.2.2    |
| UI Library | SweetAlert2, Select2 / TomSelect                    |
| Database   | SQL Server (`sqlsrv`), MySQL (`mysqli`)              |
| Design     | Material Design 3                                   |

---

## โครงสร้างโปรเจค

```
visitorMKT/
├── api/                    # ~30 JSON API endpoints (AJAX)
│   ├── get_visitor_list.php
│   ├── visitor_form_save.php
│   ├── visitor_form_update.php
│   ├── visitor_status_update.php
│   ├── send_invitation.php
│   └── ...
├── layout/
│   ├── navbar.php
│   ├── sidebarMenu.php
│   ├── footer.php
│   └── theme-settings.php
├── css/                    # Custom stylesheets
├── css.php                 # Central CSS loader
├── js.php                  # Central JS loader
├── index.php               # Dashboard / หน้าหลัก
├── visitorform.php         # ฟอร์มบันทึกผู้เยี่ยมชม
├── visitor_form_edit.php   # แก้ไขข้อมูลผู้เยี่ยมชม
├── listvisitor.php         # รายการผู้เยี่ยมชมทั้งหมด
├── calendar.php            # ปฏิทินการนัดหมาย
├── objective.php           # จัดการวัตถุประสงค์
├── permission.php          # จัดการสิทธิ์ผู้ใช้งาน
├── groupcustomer.php       # จัดกลุ่มลูกค้า
├── notigroup.php           # กลุ่มการแจ้งเตือน
├── setfood.php             # ตั้งค่าอาหาร/สวัสดิการ
├── visi_question.php       # คำถามสำหรับผู้เยี่ยมชม
├── visi_asefa.php          # หน้าสำหรับ Asefa
└── quotation_list.php      # รายการใบเสนอราคา
```

---

## API Pattern

ทุก endpoint ใน `api/` คืนค่าเป็น JSON รูปแบบนี้:

```json
{
  "status": true,
  "data": [...],
  "message": "..."
}
```

- `get_*.php` — อ่านข้อมูล
- `*_action.php` — เขียน/แก้ไขข้อมูล
- `visitor_form_*.php` — CRUD สำหรับฟอร์มผู้เยี่ยมชม

---

## หมายเหตุ

- ไดเรกทอรี `file/` ใช้สำหรับเก็บไฟล์แนบที่ผู้ใช้อัปโหลด
- ไม่มี test infrastructure — ทดสอบโดยการใช้งานจริงบน Web Server
