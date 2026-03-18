<link rel="shortcut icon" href="https://innovation.asefa.co.th/dorm/ChangeRequestForm/assets/media/logos/ASEFA.JPG">

<!-- Sweet Alert -->
<link type="text/css" href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

<!-- Notyf -->
<!-- <link type="text/css" href="./vendor/notyf/notyf.min.css" rel="stylesheet"> -->

<!-- Volt CSS -->
<link type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="./css/volt.css" rel="stylesheet">

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

<!-- Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

<!-- Choices -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

<style>
    thead {
        border: 0 !important;
    }

    .table th {
        background-color: #f8fafc !important;
    }

    div.dt-container div.dt-length select {
        padding: .5rem 2rem .5rem 1rem !important;
    }

    .upload-area {
        border: 2px dashed #667eea;
        border-radius: 15px;
        padding: 20px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(45deg, #f8f9ff 0%, #e8eeff 100%);
        margin-bottom: 10px;
    }

    .upload-area:hover {
        border-color: #5a6fd8;
        background: linear-gradient(45deg, #e8eeff 0%, #d4e0ff 100%);
        transform: translateY(-2px);
    }

    .upload-area.dragover {
        border-color: #4c63d2;
        background: linear-gradient(45deg, #d4e0ff 0%, #c0d0ff 100%);
        transform: scale(1.02);
    }

    .upload-icon {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 10px;
    }

    .upload-text {
        font-size: 18px;
        color: #4a5568;
        margin-bottom: 10px;
    }

    .upload-subtext {
        font-size: 14px;
        color: #718096;
    }

    #fileInput {
        display: none;
    }

    .file-list {
        margin-top: 20px;
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .file-item:hover {
        border-color: #667eea;
        transform: translateX(5px);
    }

    .file-info {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .file-icon {
        font-size: 24px;
        margin-right: 15px;
        color: #667eea;
    }

    .file-details {
        flex: 1;
    }

    .file-name {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 2px;
    }

    .file-size {
        font-size: 12px;
        color: #718096;
    }

    .delete-btn {
        background: linear-gradient(45deg, #ff6b6b, #ee5a52);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 12px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .delete-btn:hover {
        background: linear-gradient(45deg, #ee5a52, #e53e3e);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .clear-all-btn {
        background: linear-gradient(45deg, #ed8936, #dd6b20);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 14px;
        margin-top: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .clear-all-btn:hover {
        background: linear-gradient(45deg, #dd6b20, #c05621);
        transform: translateY(-1px);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .file-item {
        animation: slideIn 0.3s ease;
    }

    /* =====================================================
       MODERN CHECKBOX & RADIO STYLING
       ===================================================== */
    .form-check {
        padding-left: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .form-check-input {
        width: 1.15em;
        height: 1.15em;
        margin-top: 0.2em;
        margin-left: -1.75rem;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        background-color: #fff;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
    }

    .form-check-input:hover {
        border-color: #007aff;
    }

    .form-check-input:focus {
        border-color: #007aff;
        outline: 0;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.15);
    }

    .form-check-input:checked {
        background-color: #007aff;
        border-color: #007aff;
    }

    .form-check-input:checked[type="checkbox"] {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }

    .form-check-input[type="radio"] {
        border-radius: 50%;
    }

    .form-check-input:checked[type="radio"] {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23fff'/%3e%3c/svg%3e");
    }

    .form-check-label {
        font-size: 14px;
        font-weight: 400;
        color: #374151;
        cursor: pointer;
        transition: color 0.15s ease;
    }

    .form-check-input:checked+.form-check-label {
        color: #007aff;
        font-weight: 500;
    }

    .form-check-inline {
        display: inline-flex;
        align-items: center;
        padding-left: 0;
        margin-right: 1.5rem;
    }

    .form-check-inline .form-check-input {
        position: relative;
        margin-top: 0;
        margin-right: 0.5rem;
        margin-left: 0;
    }

    /* =====================================================
       GENERIC FORM ELEMENTS
       ===================================================== */
    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #007aff;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        outline: none;
    }

    .btn-primary {
        background: #007aff;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-1px);
    }

    input.form-control:disabled,
    textarea.form-control:disabled {
        background-color: white !important;
    }

    /* =====================================================
       MODERN FORM GROUP CLEAN
       ===================================================== */
    .form-group-clean {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group-clean label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-group-clean label i {
        color: #007aff;
        font-size: 16px;
    }

    .form-group-clean .form-control,
    .form-group-clean .form-select {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: #fafafa;
    }

    .form-group-clean .form-control:focus,
    .form-group-clean .form-select:focus {
        border-color: #007aff;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
    }

    .form-group-clean .input-group {
        display: flex;
    }

    .form-group-clean .input-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    /* =====================================================
       SELECT2 MODERN STYLING
       ===================================================== */
    .select2-scrollable .select2-results__options {
        max-height: 200px;
        overflow-y: auto;
    }

    .select2-container .select2-selection--multiple {
        height: auto;
        max-height: 80px;
        overflow-y: auto;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-container--bootstrap5 .select2-selection--multiple {
        align-items: flex-start !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
        font-size: 13px !important;
        flex-direction: row-reverse !important;
        border-radius: 8px !important;
        background: #80bdff;
        border: 1px solid #007aff;
        color: white !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove {
        margin-right: 0 !important;
        margin-left: 6px !important;
        color: white !important;
    }

    /* Base Select2 Styling */
    .select2-container--bootstrap-5 .select2-selection {
        font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif !important;
        font-size: 14px !important;
        font-weight: 400 !important;
        letter-spacing: 0.01em !important;
        border: 1.5px solid #e5e7eb !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
        min-height: 44px !important;
        background: linear-gradient(to bottom, #ffffff 0%, #fafafa 100%) !important;
        transition: all 0.2s ease !important;
    }

    .select2-container--bootstrap-5 .select2-selection:hover {
        border-color: #007aff !important;
        box-shadow: 0 2px 8px rgba(0, 122, 255, 0.08) !important;
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #007aff !important;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.15) !important;
        background: #ffffff !important;
    }

    /* Dropdown Styling */
    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1.5px solid #e5e7eb !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
        margin-top: 4px !important;
        overflow: hidden !important;
    }

    .select2-container--bootstrap-5 .select2-results__option {
        font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif !important;
        font-size: 14px !important;
        font-weight: 400 !important;
        padding: 10px 14px !important;
        transition: all 0.15s ease !important;
        color: #374151 !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #f0f7ff !important;
        color: #007aff !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--selected {
        background-color: #007aff !important;
        color: white !important;
        font-weight: 500 !important;
    }

    /* Search Box Styling */
    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif !important;
        font-size: 14px !important;
        border: 1.5px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 10px 12px !important;
        margin: 8px !important;
        width: calc(100% - 16px) !important;
        transition: all 0.2s ease !important;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
        border-color: #007aff !important;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.15) !important;
        outline: none !important;
    }

    /* Multiple Selection Tags */
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background: linear-gradient(135deg, #007aff 0%, #0056b3 100%) !important;
        border: none !important;
        border-radius: 6px !important;
        color: white !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        padding: 4px 10px !important;
        margin: 3px 4px 3px 0 !important;
        transition: all 0.2s ease !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice:hover {
        background: linear-gradient(135deg, #0056b3 0%, #004094 100%) !important;
        transform: scale(1.02) !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff !important;
        margin-left: 8px !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        width: 18px !important;
        height: 18px !important;
        line-height: 16px !important;
        text-align: center !important;
        background: rgba(255, 255, 255, 0.25) !important;
        border-radius: 50% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: white !important;
        background: #ef4444 !important;
        transform: scale(1.15) !important;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4) !important;
    }

    /* Placeholder Text */
    .select2-container--bootstrap-5 .select2-selection__placeholder {
        color: #9ca3af !important;
        font-weight: 400 !important;
    }

    /* Arrow Indicator */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        right: 12px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow b {
        border-color: #007aff transparent transparent transparent !important;
    }

    .select2-container--bootstrap-5.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #007aff transparent !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove {
        text-indent: 0 !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove>span {
        display: block !important;
    }
</style>
<style>
    /* =====================================================
       SIDEBAR CUSTOM THEME
       ===================================================== */
    #sidebarMenu {
        background-color: #1F2937;
        --md-on-surface: #ffffff;
        --md-on-surface-variant: rgba(255, 255, 255, 0.55);
        --md-outline-variant: rgba(255, 255, 255, 0.15);
        --md-error-container: rgba(239, 68, 68, 0.15);
        --md-error: #fca5a5;
    }

    .sidebar .nav-item .nav-link {
        color: #ffffff;
    }

    .sidebar .nav-link .sidebar-icon {
        color: rgba(255, 255, 255, 0.75);
    }

    .sidebar .nav-item .nav-link:hover {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.08);
    }

    .sidebar .nav-item.active > .nav-link {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.13);
    }
</style>
<style>
    /* =====================================================
       UX/UI REDESIGN STYLES (Action Bar, Status, Timeline)
       ===================================================== */
       
    /* Action Bar */
    .action-bar {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: center; /* Center by default (Mobile) */
        flex-wrap: wrap; /* Allow wrapping */
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    @media (min-width: 768px) {
        .action-bar {
            justify-content: flex-end; /* Right align on Desktop */
        }
    }
    
    .action-bar.hidden {
        display: none !important;
    }

    /* Status Dashboard Cards */
    .status-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .status-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .status-card.active {
        border-color: #10b981;
        background: #f0fdf4;
    }
    
    .status-card.pending {
        border-color: #f59e0b;
        background: #fffbeb;
    }
    
    .status-card.waiting-you {
        animation: pulse-border 2s infinite;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    @keyframes pulse-border {
        0% { border-color: #3b82f6; box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
        70% { border-color: #3b82f6; box-shadow: 0 0 0 6px rgba(59, 130, 246, 0); }
        100% { border-color: #3b82f6; box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }

    .status-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .status-card.active .status-icon-box { background: #d1fae5; color: #059669; }
    .status-card.pending .status-icon-box { background: #fef3c7; color: #d97706; }

    .status-card.clickable { cursor: pointer; }
    .status-card.clickable:hover {
        border-color: #10b981;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.2);
        transform: translateY(-3px);
    }

    /* Vertical Timeline */
    /* Modern Vertical Timeline */
    .timeline {
        position: relative;
        padding-left: 50px;
        margin-top: 25px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 17px; /* Center of dot (36px/2 - 1.5px) */
        top: 0;
        bottom: 0;
        width: 3px;
        background: #e5e7eb;
        border-radius: 3px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-dot {
        position: absolute;
        left: -50px; /* Adjust relative to padding-left (50px) -> 0 on screen */
        top: 0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 18px;
        z-index: 2;
        box-shadow: 0 0 0 4px #fff;
    }

    .timeline-dot.reject { background: #ef4444; box-shadow: 0 0 0 4px #fee2e2; }
    .timeline-dot.cancel { background: #6b7280; box-shadow: 0 0 0 4px #f3f4f6; }
    .timeline-dot.info { background: #3b82f6; box-shadow: 0 0 0 4px #dbeafe; }

    .timeline-content {
        background: #fff;
        padding: 16px 20px;
        border-radius: 12px;
        border: 1px solid #f3f4f6;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        position: relative;
        transition: transform 0.2s ease;
    }
    
    .timeline-content:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    }
    
    /* Speech bubble arrow */
    .timeline-content::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 14px;
        width: 16px;
        height: 16px;
        background: #fff;
        border-left: 1px solid #f3f4f6;
        border-bottom: 1px solid #f3f4f6;
        transform: rotate(45deg);
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 13px;
        color: #6b7280;
    }
    
    .timeline-user {
        font-weight: 700;
        color: #111827;
        font-size: 14px;
    }
    
    .timeline-body {
        font-size: 15px;
        color: #374151;
        line-height: 1.6;
    }

</style>