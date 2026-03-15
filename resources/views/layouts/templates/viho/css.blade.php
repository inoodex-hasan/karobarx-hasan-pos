@php
    $template_asset = function ($path) {
        return asset('templates/viho/assets/' . ltrim($path, '/'));
    };
    $layout_templates = config('constants.layout_templates', ['default' => 'Default']);
    $active_layout_template = data_get(session('business.common_settings', []), 'layout_template', 'default');
@endphp

@if(!(isset($pos_layout) && $pos_layout))
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/fontawesome.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/icofont.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/themify.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/feather-icon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/color-1.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/responsive.css') }}">
@endif

<style>
    .viho-template-active .thetop {
        min-height: 100vh;
        background: #f5f6fb;
    }

    .viho-template-active .side-bar {
        display: block !important;
        width: 280px;
        min-width: 280px;
        border-right: 0;
        background: #fff;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.08);
    }

    .viho-template-active .side-bar .sidebar-menu {
        background: transparent;
        border-right: 0;
        height: calc(100vh - 70px);
        overflow: auto;
    }

    .viho-template-active .side-bar #side-bar>a:hover,
    .viho-template-active .side-bar #side-bar .drop_down:hover,
    .viho-template-active .side-bar #side-bar .chiled a:hover {
        background: rgba(36, 105, 92, 0.12) !important;
        color: #24695C !important;
        border-radius: 8px;
    }

    .viho-template-active .side-bar #side-bar .drop_down:hover svg,
    .viho-template-active .side-bar #side-bar .drop_down:hover i,
    .viho-template-active .side-bar #side-bar .chiled a:hover i {
        color: #24695C !important;
    }

    .viho-template-active .viho-main-layout {
        background: #f5f6fb !important;
    }

    .viho-template-active .viho-template-header {
        background: #fff;
        border-bottom: 1px solid #eef0f6;
        padding: 14px 18px;
    }

    .viho-template-active .viho-template-header .viho-title {
        font-weight: 600;
        color: #2f2f3b;
        margin: 0;
        font-size: 18px;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r {
        background-image: none !important;
        background: #ffffff !important;
        border-bottom: 1px solid #eef0f6 !important;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .tw-inline-flex:not(.template-switch-trigger),
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r [class*="tw-inline-flex"]:not(.template-switch-trigger),
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .btn,
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r button:not(.template-switch-trigger) {
        background: #ffffff !important;
        border-color: #e5e7eb !important;
        color: #111827 !important;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .tw-inline-flex:not(.template-switch-trigger):hover,
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r [class*="tw-inline-flex"]:not(.template-switch-trigger):hover,
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .btn:hover,
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r button:not(.template-switch-trigger):hover {
        background: #f9fafb !important;
        color: #111827 !important;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r svg,
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r i {
        color: #111827 !important;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r svg[stroke] {
        stroke: #111827 !important;
    }

    .viho-template-active .template-switch-trigger {
        background: #ffffff !important;
        color: #24695C !important;
        border-color: rgba(255, 255, 255, 0.9) !important;
    }

    .viho-template-active .template-switch-trigger:hover {
        background: #eef7f4 !important;
        color: #1f5a4f !important;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .tw-w-52>div:nth-child(1) .js-layout-template-switch {
        background: #ffffff !important;
        color: #24695C !important;
        border: 1px solid #d1d5db !important;
        margin-bottom: 6px;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .tw-w-52>div:nth-child(1) .js-layout-template-switch:hover {
        background: #eef7f4 !important;
        color: #1f5a4f !important;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .tw-w-52>div:nth-child(1) li:last-child .js-layout-template-switch {
        margin-bottom: 0;
    }

    .viho-template-active #scrollable-container {
        padding: 0 14px 14px;
        height: calc(100vh - 72px);
        background: #24695C !important;
    }

    .viho-template-active #scrollable-container div.tw-px-5:nth-child(1) {
        background: #24695C !important;
    }

    .viho-template-active #scrollable-container .tw-isolate {
        background: #24695C !important;
    }

    /* Home page gradient layers that still paint old color */
    .viho-template-active #scrollable-container>.tw-pb-6.tw-bg-gradient-to-r,
    .viho-template-active #scrollable-container .tw-absolute.tw-inset-0.tw-grid>div.tw-bg-gradient-to-r {
        background-image: none !important;
        background: #24695C !important;
    }

    .viho-template-active .content-wrapper,
    .viho-template-active .main-footer {
        background: transparent !important;
    }

    .viho-template-active .viho-switcher details summary {
        list-style: none;
        cursor: pointer;
    }

    .viho-template-active .viho-switcher details summary::-webkit-details-marker {
        display: none;
    }

    .viho-template-active .viho-switcher .menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 6px;
        background: #fff;
        border: 1px solid #e9ecf2;
        border-radius: 10px;
        min-width: 180px;
        box-shadow: 0 10px 30px rgba(19, 23, 34, 0.12);
        z-index: 1000;
        padding: 8px;
    }

    .viho-template-active .viho-switcher .menu button {
        width: 100%;
        border: 0;
        background: transparent;
        text-align: left;
        padding: 8px 10px;
        border-radius: 8px;
        color: #2f2f3b;
    }

    .viho-template-active .viho-switcher .menu button:hover {
        background: #f5f6fb;
    }

    .viho-template-active .viho-switcher .menu button[disabled] {
        opacity: 0.65;
    }

    .viho-template-active .viho-template-footer {
        padding: 14px 5px 10px;
        color: #8f94a3;
        font-size: 13px;
    }

    .viho-template-active .viho-template-footer .right {
        text-align: right;
    }

    /* Viho structural wrappers */
    .viho-template-active .loader-wrapper {
        display: none;
    }

    .viho-template-active .page-wrapper {
        background: #f5f6fb;
        min-height: 100vh;
    }

    .viho-template-active .page-main-header {
        position: sticky;
        top: 0;
        z-index: 1030;
        background: #ffffff;
        border-bottom: 1px solid #eef0f6;
        height: auto !important;
        min-height: 70px;
    }

    .viho-template-active .page-body-wrapper {
        display: flex;
        min-height: calc(100vh - 72px);
    }

    .viho-template-active .page-body {
        flex: 1;
        min-width: 0;
    }

    /* Dashboard: tone down old Tailwind gradients to match Viho look */
    .viho-template-active .viho-dashboard.tw-bg-gradient-to-r,
    .viho-template-active .viho-dashboard .tw-bg-gradient-to-r {
        background-image: none !important;
        background: transparent !important;
    }

    .viho-template-active .viho-dashboard {
        padding-top: 12px;
        background: transparent !important;
    }

    /* Home page gap fix */
    .viho-template-active .page-body {
        padding-top: 0 !important;
        margin-top: 80px !important;
        /* Clears the header (approx 80px) */
    }

    .viho-template-active .page-body .container-fluid {
        padding-top: 0 !important;
    }

    .viho-template-active .dashboard-default-sec {
        padding-top: 20px !important;
        /* This is the "little gap" requested */
    }

    .viho-template-active .viho-dashboard .page-header {
        background: #ffffff;
        border: 1px solid #eef0f6;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 14px;
        box-shadow: 0 10px 30px rgba(19, 23, 34, 0.06);
    }

    .viho-template-active .viho-dashboard .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }

    .viho-template-active .viho-dashboard .breadcrumb a {
        color: #24695C;
    }

    .viho-template-active .viho-dashboard .tw-text-white {
        color: #2f2f3b !important;
    }

    .viho-template-active .viho-dashboard .tw-text-primary-800 {
        color: #2f2f3b !important;
    }

    .viho-template-active .viho-dashboard .select2-container--default .select2-selection--single,
    .viho-template-active .viho-dashboard .form-control {
        border-radius: 10px;
        border-color: #e9ecf2;
        box-shadow: none;
    }

    /* Sidebar: keep menu data, update spacing/typography */
    .viho-template-active .side-bar .sidebar-menu,
    .viho-template-active .side-bar #side-bar {
        padding: 12px;
    }

    .viho-template-active .side-bar #side-bar>a,
    .viho-template-active .side-bar #side-bar .drop_down,
    .viho-template-active .side-bar #side-bar .chiled a {
        padding: 10px 12px !important;
        margin: 2px 0 !important;
        border-radius: 10px !important;
        font-weight: 500;
        color: #2f2f3b !important;
    }

    .viho-template-active .side-bar #side-bar .chiled {
        margin-left: 0 !important;
        padding-left: 10px !important;
    }

    .viho-template-active .side-bar #side-bar .chiled a {
        font-weight: 400;
        color: #6c757d !important;
    }

    /* Tables/forms: keep structure, refresh style */
    .viho-template-active table.table,
    .viho-template-active .table {
        background: #fff;
        border-radius: 12px;
        /* Removed overflow: hidden to prevent dropdown clipping */
    }

    .viho-template-active .table>thead>tr>th,
    .viho-template-active .table>thead>tr>td {
        background: #f5f6fb;
        border-bottom-color: #eef0f6;
        color: #2f2f3b;
        font-weight: 600;
    }

    .viho-template-active .table>tbody>tr>td {
        border-top-color: #eef0f6;
    }

    .viho-template-active .box,
    .viho-template-active .box-body,
    .viho-template-active .box-header {
        border-radius: 12px;
    }

    .viho-template-active .box {
        border: 1px solid #eef0f6;
        box-shadow: 0 10px 30px rgba(19, 23, 34, 0.06);
        background: #fff;
    }

    /* Global visibility fixes for Viho template */
    .viho-template-active .card,
    .viho-template-active .card-body,
    .viho-template-active .table,
    .viho-template-active .table tbody tr,
    .viho-template-active .table tbody td,
    .viho-template-active .table tbody th,
    .viho-template-active .tab-content,
    .viho-template-active .tab-pane {
        color: #2c323f !important;
        background-color: #ffffff !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    .viho-template-active .table thead tr th {
        color: #2c323f !important;
        /* background-color: #f5f6fb !important; */
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* Force dark text for common Tailwind classes that might be white */
    .viho-template-active .tw-text-white,
    .viho-template-active .tw-text-gray-100,
    .viho-template-active .tw-text-gray-200,
    .viho-template-active .tw-text-gray-300,
    .viho-template-active .tw-text-gray-400,
    .viho-template-active .tw-text-gray-500 {
        color: #2c323f !important;
    }

    /* Ensure specific report tables are visible */
    .viho-template-active #product_table,
    .viho-template-active #stock_report_table,
    .viho-template-active .dataTable,
    .viho-template-active .ajax_view,
    .viho-template-active .dataTables_wrapper {
        background-color: #ffffff !important;
        opacity: 1 !important;
        visibility: visible !important;
        color: #2c323f !important;
    }

    .viho-template-active #product_table td,
    .viho-template-active #stock_report_table td,
    .viho-template-active .ajax_view td,
    .viho-template-active .dataTable td {
        color: #2c323f !important;
        opacity: 1 !important;
        background-color: #ffffff !important;
    }

    /* Profile Greeting visibility fix: Ensures text is visible even if image fails to load */
    .viho-template-active .profile-greeting.card {
        background-color: #24695c !important;
        /* Primary Teal fallback */
        background-image: url("{{ $template_asset('images/dashboard/bg.jpg') }}") !important;
        background-size: cover !important;
        background-position: center !important;
        border: none !important;
        color: #ffffff !important;
    }

    .viho-template-active .profile-greeting.card .card-header,
    .viho-template-active .profile-greeting.card .card-body {
        background-color: transparent !important;
        color: #ffffff !important;
    }

    .viho-template-active .profile-greeting.card h3,
    .viho-template-active .profile-greeting.card p {
        color: #ffffff !important;
        /* Force white text */
    }

    .viho-template-active .profile-greeting.card .btn-light {
        background-color: #ffffff !important;
        color: #24695c !important;
        border: none !important;
        font-weight: 700 !important;
    }

    /* Font Awesome 5 to 4 mapping shims for DataTables buttons */
    .viho-template-active .fa-file-csv:before {
        content: "\f1c3" !important;
        /* Map to fa-file-excel-o */
    }

    .viho-template-active .fa-file-excel:before {
        content: "\f1c3" !important;
        /* fa-file-excel-o */
    }

    .viho-template-active .fa-file-pdf:before {
        content: "\f1c8" !important;
        /* fa-file-pdf-o */
    }

    .viho-template-active .fa-file-import:before {
        content: "\f090" !important;
        /* fa-arrow-circle-o-right */
    }

    .viho-template-active .fa-file-export:before {
        content: "\f08b" !important;
        /* fa-arrow-circle-o-left */
    }

    /* DataTables Buttons Styling */
    .viho-template-active .dt-buttons {
        margin-bottom: 0 !important;
        display: inline-flex !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .viho-template-active .dt-button i {
        font-size: 14px !important;
        color: #ffffff !important;
        display: inline-block !important;
        font-family: "Font Awesome 5 Free", "FontAwesome", "Font Awesome 5 Brands", sans-serif !important;
        font-weight: 900 !important;
        margin-right: 5px !important;
        vertical-align: middle !important;
    }

    .viho-template-active .dt-button {
        background-color: #24695C !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 5px !important;
        padding: 6px 12px !important;
        font-size: 12px !important;
        margin: 2px 3px !important;
        white-space: nowrap !important;
        display: inline-flex !important;
        align-items: center !important;
        line-height: 1 !important;
        box-shadow: 0 4px 12px rgba(36, 105, 92, 0.2) !important;
        transition: all 0.3s ease !important;
    }

    .viho-template-active .dt-button:hover {
        background-color: #1b4f45 !important;
        box-shadow: 0 6px 15px rgba(36, 105, 92, 0.3) !important;
        transform: translateY(-1px) !important;
    }

    .viho-template-active .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.3em !important;
        display: inline-block !important;
        width: auto !important;
        max-width: 120px !important;
        height: 28px !important;
        font-size: 12px !important;
        padding: 2px 8px !important;
    }

    .viho-template-active .dataTables_wrapper .dataTables_length select {
        width: auto !important;
        display: inline-block !important;
        height: 24px !important;
        font-size: 11px !important;
        padding: 0 2px !important;
        margin: 0 2px !important;
    }

    .viho-template-active .dataTables_wrapper .dataTables_length,
    .viho-template-active .dataTables_wrapper .dataTables_filter,
    .viho-template-active .dataTables_wrapper .dataTables_info {
        font-size: 11px !important;
        white-space: nowrap !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .viho-template-active .dataTables_wrapper .dataTables_length label {
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 2px !important;
    }

    .viho-template-active .dataTables_wrapper .dataTables_length {
        display: flex !important;
        align-items: center !important;
    }

    .viho-template-active .dataTables_wrapper .row.align-items-center {
        margin-left: -5px !important;
        margin-right: -5px !important;
    }

    .viho-template-active .dataTables_wrapper .row.align-items-center>[class*="col-"] {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    .viho-template-active .dataTables_wrapper .dt-buttons {
        float: none !important;
        text-align: center !important;
        width: 100% !important;
    }

    /* Dashboard specific table compactness */
    .viho-template-active #sales_order_table,
    .viho-template-active #purchase_order_table,
    .viho-template-active #purchase_requisition_table,
    .viho-template-active #shipments_table,
    .viho-template-active #cash_flow_table,
    .viho-template-active #stock_alert_table,
    .viho-template-active #stock_expiry_alert_table,
    .viho-template-active #sales_payment_dues_table,
    .viho-template-active #purchase_payment_dues_table {
        font-size: 10px !important;
        width: 100% !important;
        table-layout: fixed !important;
    }

    .viho-template-active #sales_order_table td,
    .viho-template-active #sales_order_table th,
    .viho-template-active #purchase_order_table td,
    .viho-template-active #purchase_order_table th,
    .viho-template-active #purchase_requisition_table td,
    .viho-template-active #purchase_requisition_table th,
    .viho-template-active #shipments_table td,
    .viho-template-active #shipments_table th,
    .viho-template-active #cash_flow_table td,
    .viho-template-active #cash_flow_table th,
    .viho-template-active #stock_alert_table td,
    .viho-template-active #stock_alert_table th,
    .viho-template-active #stock_expiry_alert_table td,
    .viho-template-active #stock_expiry_alert_table th,
    .viho-template-active #sales_payment_dues_table td,
    .viho-template-active #sales_payment_dues_table th,
    .viho-template-active #purchase_payment_dues_table td,
    .viho-template-active #purchase_payment_dues_table th {
        padding: 3px 1px !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    /* Adjust specific column widths if needed */
    .viho-template-active #sales_order_table th:nth-child(1) {
        width: 60px !important;
    }

    /* Action */
    .viho-template-active #sales_order_table th:nth-child(2) {
        width: 80px !important;
    }

    /* Date */

    /* Action Column Dropdown Styling */
    .viho-template-active .btn-group .dropdown-toggle.tw-dw-btn-info,
    .viho-template-active .btn-group.open .dropdown-toggle.tw-dw-btn-info,
    .viho-template-active .btn-group.show .dropdown-toggle.tw-dw-btn-info,
    .viho-template-active .btn-group .btn.btn-primary[data-toggle="dropdown"] {
        background-color: #24695C !important;
        background: #24695C !important;
        border-color: #24695C !important;
        color: #ffffff !important;
        font-size: 0 !important;
        /* Hide "Actions" text if present */
        padding: 4px 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 30px !important;
        height: 28px !important;
        border-radius: 4px !important;
        opacity: 1 !important;
        background-image: none !important;
        box-shadow: none !important;
    }

    /* Ensure icons inside these buttons are centered and white */
    .viho-template-active .btn-group .dropdown-toggle.tw-dw-btn-info i,
    .viho-template-active .btn-group .btn.btn-primary[data-toggle="dropdown"] i {
        font-size: 14px !important;
        color: #ffffff !important;
        margin: 0 !important;
    }

    .viho-template-active .btn-group .dropdown-toggle.tw-dw-btn-info .caret,
    .viho-template-active .btn-group.open .dropdown-toggle.tw-dw-btn-info .caret,
    .viho-template-active .btn-group.show .dropdown-toggle.tw-dw-btn-info .caret {
        border-top-color: #ffffff !important;
        border-bottom-color: #ffffff !important;
        margin-left: 0 !important;
        font-size: 16px !important;
        display: inline-block !important;
        vertical-align: middle !important;
    }

    /* Force compact solid white background for the dropdown menu - NUCLEAR FIX */
    .viho-template-active .dropdown-menu,
    .viho-template-active .btn-group.open .dropdown-menu,
    .viho-template-active .btn-group.show .dropdown-menu,
    .viho-template-active .dropdown-menu.show {
        background-color: #ffffff !important;
        background: #ffffff !important;
        border: 1px solid #d1d5db !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
        padding: 2px 0 !important;
        /* Reduced vertical padding for menu */
        z-index: 100000 !important;
        visibility: visible !important;
        opacity: 1 !important;
        min-width: 140px !important;
        transition: none !important;
        animation: none !important;
        filter: none !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        mix-blend-mode: normal !important;
    }

    .viho-template-active .btn-group.open .dropdown-menu,
    .viho-template-active .btn-group.show .dropdown-menu,
    .viho-template-active .dropdown-menu.show {
        display: block !important;
    }

    .viho-template-active .dropdown-menu li,
    .viho-template-active .dropdown-menu li a {
        background-color: #ffffff !important;
        background: #ffffff !important;
        color: #2c323f !important;
        padding: 4px 12px !important;
        /* More compact padding */
        display: block !important;
        font-size: 11.5px !important;
        /* Slightly smaller font for compactness */
        opacity: 1 !important;
        text-decoration: none !important;
        line-height: 1.3 !important;
        filter: none !important;
    }

    .viho-template-active .dropdown-menu li a:hover {
        background-color: #f5f6fb !important;
        color: #24695C !important;
    }

    .viho-template-active .dropdown-menu .divider {
        background-color: #eef0f6 !important;
        margin: 2px 0 !important;
        /* More compact divider */
    }

    /* Ensure table containers don't clip dropdowns */
    .viho-template-active .dataTables_wrapper,
    .viho-template-active .table-responsive,
    .viho-template-active .dataTable,
    .viho-template-active div.dataTables_scrollBody,
    .viho-template-active .tab-content,
    .viho-template-active .tab-pane {
        overflow: visible !important;
    }

    .viho-template-active .dt-buttons {
        margin-bottom: 10px !important;
    }
</style>