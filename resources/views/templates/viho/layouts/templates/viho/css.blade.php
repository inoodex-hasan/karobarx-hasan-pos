@php
    $template_asset = function ($path) {
        return asset('templates/viho/assets/' . ltrim($path, '/'));
    };
    $layout_templates = config('constants.layout_templates', ['default' => 'Default']);
    $active_layout_template = data_get(session('business.common_settings', []), 'layout_template', 'default');
@endphp

<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/fontawesome.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/icofont.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/themify.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/feather-icon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/color-1.css') }}">
<link rel="stylesheet" type="text/css" href="{{ $template_asset('css/responsive.css') }}">

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
        background: #24695C !important;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .tw-inline-flex:not(.template-switch-trigger),
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r [class*="tw-inline-flex"]:not(.template-switch-trigger),
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .btn,
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r button:not(.template-switch-trigger) {
        background: #24695C !important;
        border-color: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
    }

    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .tw-inline-flex:not(.template-switch-trigger):hover,
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r [class*="tw-inline-flex"]:not(.template-switch-trigger):hover,
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r .btn:hover,
    .viho-template-active .tw-border-b.tw-bg-gradient-to-r button:not(.template-switch-trigger):hover {
        background: #1f5a4f !important;
        color: #ffffff !important;
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
        overflow: hidden;
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
</style>
