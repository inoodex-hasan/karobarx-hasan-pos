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
