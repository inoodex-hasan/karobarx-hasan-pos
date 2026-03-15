@extends('templates.viho.layout')

@section('title', __('home.home'))

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('templates/viho/assets/css/chartist.css') }}">
    <style>
        /* Recent Orders Table - Full Width Columns */
        .recent-orders-card .table-responsive {
            overflow-x: auto;
        }
        .recent-orders-card table.table-bordernone {
            width: 100% !important;
            table-layout: auto;
        }
        .recent-orders-card table.table-bordernone thead th {
            white-space: nowrap;
            padding: 12px 8px;
        }
        .recent-orders-card table.table-bordernone tbody td {
            white-space: nowrap;
            padding: 12px 8px;
            vertical-align: middle;
        }
        .recent-orders-card table.table-bordernode tbody td .media {
            min-width: 200px;
        }
        .recent-orders-card .card-body {
            overflow-x: auto;
        }

        /* Standardize Latest Activity card to prevent content overlap at smaller column widths */
        .latest-update-sec .card-body {
            overflow-x: auto;
            padding: 15px !important;
        }
        .latest-update-sec .table-responsive {
            overflow-x: auto;
            border: none;
        }
        .latest-update-sec table {
            min-width: 300px; /* Ensure content doesn't shrink too much */
        }
        .latest-update-sec .media .media-body span {
            display: block;
            white-space: normal; /* Allow wrapping */
            max-width: 250px; /* Increased for 50% width col */
        }

        /* Equal height cards for the second row */
        .row-equal-height {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
        }
        .row-equal-height > div {
            display: flex;
            margin-bottom: 30px;
        }
        .row-equal-height > div > .card {
            width: 100%;
            height: 100% !important;
            display: flex;
            flex-direction: column;
            margin-bottom: 0 !important;
        }
        .row-equal-height > div > .card .card-body {
            flex: 1 1 auto;
        }
        /* Override template-specific margin that might break the height balance */
        .trasaction-sec.card .card-body {
            margin-bottom: 0 !important;
        }

        /* Ensure Recent Orders table fits within 50% card width - Zero Scroll Strategy */
        .recent-orders-card .card-body {
            padding: 10px !important;
        }
        .recent-orders-card .table-responsive {
            overflow: hidden !important; 
        }
        .recent-orders-card table {
            width: 100% !important;
            table-layout: fixed !important;
            border-collapse: collapse !important;
        }
        .recent-orders-card table thead th {
            background-color: #ffffff !important; 
            border-bottom: 2px solid #f2f4f6 !important;
            color: #2b2b2b !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .recent-orders-card table th, 
        .recent-orders-card table td {
            padding: 12px 8px !important; /* Breathable spacing */
            font-size: 12px !important;
            vertical-align: middle !important;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap !important;
            border-bottom: 1px solid #f8f9fa !important; /* Subtle row separator */
            -webkit-font-smoothing: antialiased;
        }
        .recent-orders-card table tbody tr {
            transition: background-color 0.2s ease;
        }
        .recent-orders-card table tbody tr:hover {
            background-color: #f9fafb !important; /* Subtle hover effect */
        }
        /* Strict Column Widths - Optimized for visibility and wrapping */
        .recent-orders-card table th:nth-child(1), .recent-orders-card table td:nth-child(1) { 
            width: 38%; 
            white-space: normal !important; 
            word-break: break-word;
            overflow: visible;
        } 
        .recent-orders-card table th:nth-child(2), .recent-orders-card table td:nth-child(2) { width: 14%; text-align: center; } /* Date */
        .recent-orders-card table th:nth-child(3), .recent-orders-card table td:nth-child(3) { width: 10%; text-align: center; } /* Qty */
        .recent-orders-card table th:nth-child(4), .recent-orders-card table td:nth-child(4) { width: 12%; text-align: center; } /* Value */
        .recent-orders-card table th:nth-child(5), .recent-orders-card table td:nth-child(5) { width: 13%; text-align: center; } /* Rate */
        .recent-orders-card table th:nth-child(6), .recent-orders-card table td:nth-child(6) { width: 13%; text-align: center; } /* Status */

        .recent-orders-card .media-body span {
            max-width: 100%;
            display: block;
            line-height: 1.2;
        }
        .recent-orders-card table img.img-fluid {
            max-width: 100%; 
            height: 16px;
        }
        .recent-orders-card .media img {
            width: 25px !important;
            height: 25px !important;
            margin-right: 8px;
        }

        /* Zero Scroll Strategy for Latest Activity */
        .latest-update-sec .card-body {
            padding: 10px !important;
        }
        .latest-update-sec .table-responsive {
            overflow: hidden !important;
        }
        .latest-update-sec table {
            width: 100% !important;
            table-layout: fixed !important;
            border-collapse: collapse !important;
        }
        .latest-update-sec table tr {
            background-color: #ffffff !important;
        }
        .latest-update-sec table td {
            padding: 10px 5px !important;
            font-size: 12px !important;
            vertical-align: middle !important;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap !important;
            border-bottom: 1px solid #f2f4f6 !important;
        }
        /* Column Widths (70% for Activity, 15% each for Edit/Delete) */
        .latest-update-sec table td:nth-child(1) { 
            width: 70%; 
            white-space: normal !important; 
            word-break: break-word;
            overflow: visible;
        }
        .latest-update-sec table td:nth-child(2) { width: 15%; text-align: center; }
        .latest-update-sec table td:nth-child(3) { width: 15%; text-align: center; }

        .latest-update-sec .media svg {
            width: 20px !important;
            height: 20px !important;
            margin-right: 10px;
            flex-shrink: 0;
        }
        .latest-update-sec .media-body span {
            display: block;
            line-height: 1.2;
            font-weight: 500;
        }
        .latest-update-sec .media-body p {
            font-size: 10px;
            margin-top: 2px;
            color: #898989;
        }

        /* Reduce the gap between header and dashboard cards */
        .viho-dashboard {
            margin-top: -40px !important;
        }
        .page-body {
            padding-top: 0 !important;
        }
    </style>
@endpush

@section('content')
    <div class="viho-dashboard">
        {!! $dashboard_body ?? '' !!}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('templates/viho/assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('templates/viho/assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('templates/viho/assets/js/chart/knob/knob.min.js') }}"></script>
    <script src="{{ asset('templates/viho/assets/js/chart/knob/knob-chart.js') }}"></script>
    <script src="{{ asset('templates/viho/assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('templates/viho/assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    {{-- Counter scripts for animated number counts --}}
    <script src="{{ asset('templates/viho/assets/js/counter/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('templates/viho/assets/js/counter/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('templates/viho/assets/js/counter/counter-custom.js') }}"></script>
    {{-- Custom card controls (minimize, reload, close) --}}
    <script src="{{ asset('templates/viho/assets/js/custom-card/custom-card.js') }}"></script>
    {{-- config.js must come before default.js — defines window.vihoAdminConfig (primary/secondary colors) --}}
    <script src="{{ asset('templates/viho/assets/js/config.js') }}"></script>
    {{-- Viho default dashboard JS — renders all charts exactly as index.html --}}
    <script src="{{ asset('templates/viho/assets/js/dashboard/default.js') }}"></script>

    <script type="text/javascript">
        (function () {
            $(document).ready(function () {

                // ─── Annual Income / Losses (text only) ──────────────────────────────────────
                $.ajax({
                    url: "{{ route('home.viho.annual-totals') }}",
                    method: "GET",
                    dataType: "json",
                    success: function (data) {
                        var fmtPct = function (v) {
                            if (v === null || typeof v === 'undefined' || isNaN(v)) return '--%';
                            return Math.abs(v).toFixed(2) + '%';
                        };
                        var applyPct = function (pct, $icon, $text) {
                            var isUp = (pct === null || typeof pct === 'undefined') ? true : pct >= 0;
                            if ($icon && $icon.length) {
                                $icon.removeClass('fa-arrow-up fa-arrow-down');
                                $icon.addClass(isUp ? 'fa-arrow-up' : 'fa-arrow-down');
                            }
                            if ($text && $text.length) { $text.text(fmtPct(pct)); }
                        };

                        if (typeof __currency_trans_from_en === 'function') {
                            if ($('#viho_annual_income').length)
                                $('#viho_annual_income').text(__currency_trans_from_en(data.total_sell, true));
                            if ($('#viho_annual_losses').length)
                                $('#viho_annual_losses').text(__currency_trans_from_en(data.total_expense, true));
                        } else {
                            if ($('#viho_annual_income').length) $('#viho_annual_income').text('৳' + (data.total_sell || 0));
                            if ($('#viho_annual_losses').length) $('#viho_annual_losses').text('৳' + (data.total_expense || 0));
                        }
                        applyPct(data.income_pct, $('#viho_annual_income_pct_icon'), $('#viho_annual_income_pct'));
                        applyPct(data.losses_pct, $('#viho_annual_losses_pct_icon'), $('#viho_annual_losses_pct'));
                    }
                });

                // ─── Sales Overview (text only — chart stays as default.js rendered it) ──────
                $.ajax({
                    url: "{{ route('home.viho.sales-overview') }}",
                    method: "GET",
                    dataType: "json",
                    success: function (data) {
                        if ($('#viho_sales_total').length) {
                            if (typeof __currency_trans_from_en === 'function') {
                                $('#viho_sales_total').text(__currency_trans_from_en(data.total_sales, true));
                            } else {
                                $('#viho_sales_total').text('৳' + (data.total_sales || 0));
                            }
                        }
                        if ($('#viho_sales_pct').length) {
                            if (data.sales_pct === null || typeof data.sales_pct === 'undefined' || isNaN(data.sales_pct)) {
                                $('#viho_sales_pct').text('--%');
                            } else {
                                $('#viho_sales_pct').text(Math.abs(data.sales_pct).toFixed(2) + '%');
                            }
                        }
                        if ($('#viho_sales_pct_icon').length) {
                            var isUp = (data.sales_pct === null || typeof data.sales_pct === 'undefined') ? true : data.sales_pct >= 0;
                            $('#viho_sales_pct_icon').removeClass('fa-arrow-up fa-arrow-down');
                            $('#viho_sales_pct_icon').addClass(isUp ? 'fa-arrow-up' : 'fa-arrow-down');
                        }

                        // Fix graph visibility since card background is now white instead of primary color
                        if (typeof charttimeline !== 'undefined') {
                            charttimeline.updateOptions({
                                colors: [vihoAdminConfig.primary],
                                fill: {
                                    type: 'gradient',
                                    gradient: {
                                        shadeIntensity: 1,
                                        opacityFrom: 0.4,
                                        opacityTo: 0.0,
                                        stops: [0, 100]
                                    }
                                }
                            });
                        }
                    }
                });

                // ─── Growth Overview (#chart-dashbord) ────────────────────────────────────────
                // default.js creates global `chart17` (radialBar).
                // We update it to show 5 dynamic metrics: Sale, Purchase, Invoice Due, Purchase Due, Expense.
                $.ajax({
                    url: "{{ route('home.viho.growth-overview') }}",
                    method: "GET",
                    dataType: "json",
                    success: function (payload) {
                        // 1. Update text percentage and icon
                        if ($('#viho_growth_pct').length) {
                            if (payload.growth_pct === null || typeof payload.growth_pct === 'undefined' || isNaN(payload.growth_pct)) {
                                $('#viho_growth_pct').text('--%');
                            } else {
                                $('#viho_growth_pct').text(Math.abs(payload.growth_pct).toFixed(2) + '%');
                            }
                        }
                        if ($('#viho_growth_pct_icon').length) {
                            var up = (payload.growth_pct === null || typeof payload.growth_pct === 'undefined') ? true : payload.growth_pct >= 0;
                            $('#viho_growth_pct_icon').removeClass('fa-arrow-up fa-arrow-down').addClass(up ? 'fa-arrow-up' : 'fa-arrow-down');
                        }

                        // 2. Update the radialBar chart (chart17)
                        if (typeof chart17 !== 'undefined' && payload.radial_series) {
                            var rawValues = payload.radial_series; // [Sale, Purchase, Inv Due, Purch Due, Expense]
                            var labels = payload.radial_labels;

                            // Normalizing values (0-100) for visual arc length
                            var maxVal = Math.max.apply(null, rawValues);
                            if (maxVal <= 0) maxVal = 1;
                            var normalizedSeries = rawValues.map(function (v) {
                                return Math.min(100, Math.max(5, (v / maxVal) * 100)); // Min 5% so bar is visible
                            });

                            chart17.updateOptions({
                                series: normalizedSeries,
                                labels: labels,
                                colors: [
                                    vihoAdminConfig.primary,    // Sale
                                    vihoAdminConfig.secondary,  // Purchase
                                    '#e2c636',                  // Invoice Due
                                    '#d22d3d',                  // Purchase Due
                                    '#222222'                   // Expense
                                ],
                                legend: {
                                    formatter: function (seriesName, opts) {
                                        var val = rawValues[opts.seriesIndex];
                                        return seriesName + ":  ৳" + Math.round(val).toLocaleString();
                                    }
                                },
                                tooltip: {
                                    enabled: true,
                                    y: {
                                        formatter: function (val, opts) {
                                            return "৳" + Math.round(rawValues[opts.seriesIndex]).toLocaleString();
                                        }
                                    }
                                }
                            }, false, true);
                        }
                    }
                });

                // ─── User Activations (dynamic bar chart) ─────────────────────────────────────
                $.ajax({
                    url: "{{ route('home.viho.user-activations') }}",
                    method: "GET",
                    dataType: "json",
                    success: function (payload) {
                        if ($('#viho_yearly_users').length) {
                            $('#viho_yearly_users').text(payload.yearly_total || 0);
                        }

                        if (typeof chart55 !== 'undefined' && payload.labels && payload.series) {
                            var rawData = payload.series[0].data;
                            var labels = payload.labels;
                            var mappedData = [];

                            for (var i = 0; i < labels.length; i++) {
                                var point = {
                                    x: labels[i],
                                    y: rawData[i] || 0
                                };
                                // Apply alternating color to mimic original design (every other bar)
                                if (i % 2 !== 0) {
                                    point.fillColor = vihoAdminConfig.secondary;
                                }
                                mappedData.push(point);
                            }

                            chart55.updateSeries([{
                                name: "{{ __('user.users') }}",
                                data: mappedData
                            }]);
                        }
                    }
                });

                // ─── Transactions (dynamic area chart) ─────────────────────────────────────────────────
                $.ajax({
                    url: "{{ route('home.viho.transactions') }}",
                    method: "GET",
                    dataType: "json",
                    success: function (payload) {
                        if ($('#viho_success_txn_text').length) {
                            $('#viho_success_txn_text').text((payload.success_count || 0).toLocaleString() + ' Successful Transaction');
                        }
                        if ($('#viho_total_balance').length) {
                            if (typeof __currency_trans_from_en === 'function') {
                                $('#viho_total_balance').text(__currency_trans_from_en(payload.balance || 0, true));
                            } else {
                                $('#viho_total_balance').text('৳' + (payload.balance || 0));
                            }
                        }
                        if ($('#viho_total_balance_pct').length) {
                            if (payload.balance_pct === null || typeof payload.balance_pct === 'undefined' || isNaN(payload.balance_pct)) {
                                $('#viho_total_balance_pct').text('--%');
                            } else {
                                $('#viho_total_balance_pct').text(Math.abs(payload.balance_pct).toFixed(2) + '%');
                            }
                        }
                        if ($('#viho_total_balance_pct_icon').length) {
                            var up = (payload.balance_pct === null || typeof payload.balance_pct === 'undefined') ? true : payload.balance_pct >= 0;
                            $('#viho_total_balance_pct_icon').removeClass('fa-arrow-up fa-arrow-down').addClass(up ? 'fa-arrow-up' : 'fa-arrow-down');
                        }

                        if (typeof chart21 !== 'undefined' && payload.labels && payload.series) {
                            // chart21 expects categories (datetime strings) in xaxis
                            chart21.updateOptions({
                                chart: {
                                    height: 280
                                },
                                xaxis: {
                                    categories: payload.labels
                                }
                            });
                            // Update the series data
                            chart21.updateSeries([{
                                name: "{{ __('home.total_sell') }}",
                                data: payload.series[0].data
                            }]);
                        }
                    }
                });

            });
        })();
    </script>
@endpush
