<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.template = 'viho';</script>
    @php
        $asset_v = $asset_v ?? config('constants.asset_version');
        $viho_asset = asset('templates/viho/assets');
    @endphp

    <title>@yield('title', config('app.name'))</title>

    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/icofont.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/themify.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/feather-icon.css">

    @include('layouts.partials.css')

    <link rel="icon" href="{{ $viho_asset }}/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/animate.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/style.css">
    <link id="color" rel="stylesheet" href="{{ $viho_asset }}/css/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/responsive.css">
    @includeIf('layouts.templates.viho.css')

    <style>
        .loader-wrapper {
            display: none !important;
        }

        /* Embedded default header uses dark gradient; Viho header background is light. */
        .default-header-embedded .tw-border-b.tw-bg-gradient-to-r {
            background-image: none !important;
            background: transparent !important;
            border: 0 !important;
        }

        .default-header-embedded .tw-border-b.tw-bg-gradient-to-r>div {
            padding: 0 !important;
        }

        .default-header-embedded summary,
        .default-header-embedded a.tw-inline-flex,
        .default-header-embedded button {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        .default-header-embedded summary:hover,
        .default-header-embedded a.tw-inline-flex:hover,
        .default-header-embedded button:hover {
            background: #f9fafb !important;
            border-color: #d1d5db !important;
            color: #111827 !important;
        }

        .default-header-embedded svg,
        .default-header-embedded i {
            color: #111827 !important;
        }

        .default-header-embedded svg[stroke] {
            stroke: #111827 !important;
        }

        .default-header-embedded .tw-ring-white\/10 {
            --tw-ring-color: rgba(17, 24, 39, 0.15) !important;
        }

        .default-header-embedded .small-view-button,
        .default-header-embedded .side-bar-collapse {
            display: none !important;
        }

        .default-header-embedded {
            display: flex !important;
            align-items: center !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative;
            z-index: 5;
        }

        .nav-right.right-menu {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .page-main-header .main-header-right {
            align-items: center;
        }

        .page-main-header .left-menu-header,
        .page-main-header .left-menu-header ul {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .page-main-header .left-menu-header ul li {
            list-style: none;
            display: flex;
            align-items: center;
        }

        .page-main-header .left-menu-header {
            display: flex;
            align-items: center;
        }

        .page-main-header .left-menu-header .search-form {
            margin: 0;
        }

        .sidebar-user .rounded-circle {
            display: block;
            margin: 0 auto;
        }

        /* DataTables (Viho pages): keep length control on one line. */
        div[id$="_dt_length"] .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0;
            white-space: nowrap;
        }

        div[id$="_dt_length"] .dataTables_length select {
            margin: 0;
            width: auto !important;
        }

        div[id$="_dt_filter"],
        div[id$="_dt_paginate"] {
            display: flex;
            justify-content: flex-end;
        }

        /* Global fix: ensure DataTable body text is always readable (dark) */
        table.dataTable tbody tr td,
        table.dataTable tbody tr th,
        .dataTables_wrapper table tbody tr td,
        .dataTables_wrapper table tbody tr th {
            color: #2c323f !important;
        }

        table.dataTable tbody tr.odd td,
        table.dataTable tbody tr.even td {
            color: #2c323f !important;
        }

        /* Generic DataTables fixes for all report tables */
        .dataTables_wrapper table.dataTable {
            width: 100% !important;
        }
        .dataTables_wrapper table.dataTable tbody tr {
            display: table-row !important;
        }
        .dataTables_wrapper table.dataTable tbody td {
            display: table-cell !important;
        }
        .dataTables_wrapper {
            width: 100% !important;
            display: block !important;
        }

        /* Sidebar: keep icon + text on one line */
        #mainnav .nav-menu a.menu-title,
        #mainnav .nav-menu a.menu-title.link-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        #mainnav .nav-menu a.menu-title i,
        #mainnav .nav-menu a.menu-title svg {
            flex: 0 0 auto;
        }

        #mainnav .nav-menu a.menu-title span {
            display: inline-block;
            flex: 1 1 auto;
        }

        /* iCheck: hide original checkbox to avoid duplicate "shadow" checkbox */
        input.input-icheck[type="checkbox"],
        input.input-icheck[type="radio"] {
            position: absolute !important;
            opacity: 0 !important;
            width: 1px !important;
            height: 1px !important;
            margin: 0 !important;
            padding: 0 !important;
            pointer-events: none !important;
        }

        /* Ensure header doesn't overflow on small laptop screens */
        .page-main-header {
            overflow: visible !important;
        }
        .page-main-header .main-header-right {
            width: 100%;
        }

        /* Fix Font Awesome icons - support both FA4 and FA5/6 syntax */
        .fa {
            display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Map fas/far/fab to FontAwesome for FA4 compatibility */
        .fas, .far, .fab {
            font-family: FontAwesome !important;
            font-weight: normal;
            font-style: normal;
        }

        /* Laptop Responsiveness Fixes (1024px to 1440px) */
        @media (max-width: 1440px) {
            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper header.main-nav {
                width: 240px !important;
                min-width: 240px !important;
            }
            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper .page-body {
                margin-left: 240px !important;
            }
            .viho-template-active .page-main-header .main-header-right .main-header-left {
                width: 240px !important;
            }
            .viho-template-active .page-main-header .main-header-right .nav-right {
                padding-left: 10px !important;
            }
            .viho-template-active .default-header-embedded a.tw-inline-flex {
                padding: 0.375rem 0.5rem !important;
                font-size: 0.75rem !important;
            }
            .viho-template-active .default-header-embedded svg {
                width: 1.25rem !important;
                height: 1.25rem !important;
            }
            .viho-template-active .page-main-header .main-header-right .left-menu-header {
                max-width: 150px !important;
            }
            .viho-template-active .container-fluid {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
        }

        @media (max-width: 1200px) {
            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper header.main-nav {
                width: 70px !important;
                min-width: 70px !important;
            }
            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper header.main-nav .main-navbar .nav-menu span {
                display: none !important;
            }
            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper .page-body {
                margin-left: 70px !important;
            }
            .viho-template-active .page-main-header .main-header-right .main-header-left {
                width: 70px !important;
            }
            .viho-template-active .logo-wrapper img {
                display: none !important;
            }
            .viho-template-active .main-nav .sidebar-user img {
                width: 40px !important;
                height: 40px !important;
            }
            .viho-template-active .main-nav .sidebar-user h6, 
            .viho-template-active .main-nav .sidebar-user p {
                display: none !important;
            }
        }

        .fa-lg {
            font-size: 1.33333em;
            line-height: 0.75em;
            vertical-align: -0.0667em;
        }

        /* Bootstrap 3 Offset Shims */
        .col-md-offset-4 { margin-left: 33.33333333%; }
        .col-sm-offset-1 { margin-left: 8.33333333%; }
        .col-md-offset-8 { margin-left: 66.66666667%; }

        /* Bootstrap 3 compatibility: many migrated views still use `.input-group-addon` */
        .input-group {
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .input-group .input-group-addon {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            padding: 0.375rem 0.75rem;
            white-space: nowrap;
        }

        .input-group .input-group-addon:first-child {
            border-right: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .input-group .input-group-addon:last-child {
            border-left: 0;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }

        .input-group .form-control {
            flex: 1 1 auto;
            width: 1% !important;
        }

        .input-group .form-control:not(:first-child):not(:last-child) {
            border-radius: 0;
        }

        .input-group .form-control:first-child:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group .form-control:last-child:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Fix button alignment in input groups */
        .input-group-btn {
            display: flex;
        }

        .input-group-btn .btn {
            border-radius: 0;
            margin: 0;
        }

        .input-group-btn:last-child .btn {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }

        /* Better well styling */
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
        }
    </style>

    @stack('styles')
</head>

<body class="viho-template-active">
    <!-- Currency related fields (override symbol for Viho pages) -->
    <input type="hidden" id="__code" value="{{ session('currency')['code'] ?? '' }}">
    <input type="hidden" id="__symbol" value="৳">
    <input type="hidden" id="__thousand" value="{{ session('currency')['thousand_separator'] ?? ',' }}">
    <input type="hidden" id="__decimal" value="{{ session('currency')['decimal_separator'] ?? '.' }}">
    <input type="hidden" id="__symbol_placement"
        value="{{ session('business.currency_symbol_placement') ?? 'before' }}">
    <input type="hidden" id="__precision" value="{{ session('business.currency_precision', 2) }}">
    <input type="hidden" id="__quantity_precision" value="{{ session('business.quantity_precision', 2) }}">
    <!-- End currency related fields -->

    @can('view_export_buttons')
        <input type="hidden" id="view_export_buttons">
    @endcan

    <!-- Loader removed as it was causing visibility issues -->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <div class="page-main-header">
            <div class="main-header-right row m-0">
                <div class="main-header-left">
                    <div class="logo-wrapper">
                        <a href="{{ route('home') }}"><img class="img-fluid"
                                src="{{ $viho_asset }}/images/logo/logo.png" alt=""></a>
                    </div>
                    <div class="dark-logo-wrapper">
                        <a href="{{ route('home') }}"><img class="img-fluid"
                                src="{{ $viho_asset }}/images/logo/dark-logo.png" alt=""></a>
                    </div>
                    <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
                            id="sidebar-toggle"></i></div>
                </div>
                <div class="left-menu-header col-auto">
                    <ul>
                        <li>
                            <form class="form-inline search-form" style="max-width: 200px;">
                                <div class="search-bg"><i class="fa fa-search"></i>
                                    <input class="form-control-plaintext" placeholder="Search here.....">
                                </div>
                            </form><span class="d-sm-none mobile-search search-bg"><i class="fa fa-search"></i></span>
                        </li>
                    </ul>
                </div>
                <div class="nav-right col pull-right right-menu p-0 box-col-8">
                    <div class="default-header-embedded">
                        @include('layouts.partials.header')
                    </div>
                </div>
                <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
            </div>
        </div>
        <!-- Page Header Ends -->

        <div class="page-body-wrapper sidebar-icon">
            <header class="main-nav">
                <div class="sidebar-user text-center">
                    <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a>
                    <img class="img-90 rounded-circle" src="{{ $viho_asset }}/images/dashboard/1.png"
                        alt="">
                    {{-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div> --}}
                    <a href="javascript:void(0)">
                        <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->first_name ?? 'User' }}</h6>
                    </a>
                    <p class="mb-0 font-roboto">{{ Session::get('business.name') }}</p>
                    {{-- <ul>
                        <li><span><span class="counter">0</span></span>
                            <p></p>
                        </li>
                        <li><span></span>
                            <p></p>
                        </li>
                        <li><span><span class="counter">0</span></span>
                            <p></p>
                        </li>
                    </ul> --}}
                </div>
                <nav>
                    <div class="main-navbar">
                        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                        <div id="mainnav">
                            {!! Menu::render('admin-sidebar-menu', 'vihocustom') !!}
                        </div>
                        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                    </div>
                </nav>
            </header>

            <div class="page-body">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @php
        $__is_pusher_enabled = $__is_pusher_enabled ?? false;
        $__system_settings = $__system_settings ?? [];
    @endphp
    @include('layouts.partials.javascripts')

    <!-- feather icon js-->
    <script src="{{ $viho_asset }}/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{ $viho_asset }}/js/icons/feather-icon/feather-icon.js"></script>
    <script src="{{ $viho_asset }}/js/config.js"></script>
    <script src="{{ $viho_asset }}/js/script.js"></script>

    <script>
        (function() {
            if (typeof window.jQuery === 'undefined') return;
            var $ = window.jQuery;
            $(function() {
                // Use Viho-like DataTables layout (length left, buttons center, search right; info left, paginate right).
                if ($.fn && $.fn.dataTable && $.fn.dataTable.defaults) {
                    $.extend($.fn.dataTable.defaults, {
                        dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-8 text-center'B><'col-sm-12 col-md-2 text-md-end'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                    });
                }

                if ($.fn && typeof $.fn.popover === 'function') {
                    $('[data-toggle="popover"]').popover();
                }
                if ($.fn && typeof $.fn.dropdown === 'function') {
                    $('.dropdown-toggle[data-toggle="dropdown"]').dropdown();
                }

                $(document).on('click', '#mainnav .nav-menu > li.dropdown > a.menu-title', function(e) {
                    e.preventDefault();
                    var $parent = $(this).closest('li.dropdown');
                    var $submenu = $parent.children('ul.nav-submenu');

                    $parent.siblings('li.dropdown').removeClass('open').children(
                        'ul.nav-submenu:visible').slideUp(
                        150);
                    $parent.toggleClass('open');
                    $submenu.stop(true, true).slideToggle(150);
                });
            });
        })();
    </script>

    @yield('javascript')
    @stack('scripts')
</body>

</html>
