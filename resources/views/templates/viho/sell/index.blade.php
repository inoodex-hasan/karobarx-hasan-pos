@extends('templates.viho.layout')
@section('title', __('lang_v1.all_sales'))

@section('content')
    <style>
        /*
                         * One horizontal scrollbar only: DataTables .dataTables_scrollBody (under the header / with rows).
                         * Do NOT set overflow-x on #sell_table_wrapper or .table-responsive — that duplicates the bar below "No data..." / footer.
                         */
        #sell_table_wrapper {
            width: 100% !important;
            overflow-x: visible !important;
        }

        .dataTables_wrapper .dataTables_scroll {
            clear: both;
        }

        #sell_table_wrapper .dataTables_scrollBody {
            overflow-x: auto !important;
        }

        .dataTables_length label {
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            font-weight: 400 !important;
            margin-bottom: 0 !important;
            white-space: nowrap !important;
        }

        .dataTables_length select {
            width: auto !important;
            height: 30px !important;
            padding: 0 10px !important;
            margin: 0 !important;
            font-size: 13px !important;
            border-radius: 4px !important;
            display: inline-block !important;
        }

        #sell_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #sell_table {
            width: 100% !important;
        }

        .dataTables_paginate {
            display: flex !important;
            justify-content: flex-end !important;
            width: 100% !important;
        }

        .paging_simple_numbers {
            margin-left: auto !important;
        }
    </style>
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('sale.sells') <span id="sell_list_selected_range"
                            class="tw-text-gray-600 tw-font-normal tw-text-base">{{
                            @format_date(\Carbon\Carbon::now()->subDays(29)) }} ~ {{ @format_date(\Carbon\Carbon::now())
                            }}</span></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>@lang('report.filters')</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @include('sell.partials.sell_list_filters')
                        @if ($payment_types)
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('payment_method', __('lang_v1.payment_method') . ':') !!}
                                                    {!! Form::select('payment_method', $payment_types, null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                                'placeholder' => __('lang_v1.all'),
                            ]) !!}
                                                </div>
                                            </div>
                        @endif

                        @if (!empty($sources))
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('sell_list_filter_source', __('lang_v1.sources') . ':') !!}

                                                    {!! Form::select('sell_list_filter_source', $sources, null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                                'placeholder' => __('lang_v1.all'),
                            ]) !!}
                                                </div>
                                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">@lang('lang_v1.all_sales')</h5>
                    @can('direct_sell.access')
                        <a class="btn btn-primary btn-sm"
                            href="{{ action([\App\Http\Controllers\SellController::class, 'create']) }}">
                            <i class="fa fa-plus"></i> @lang('messages.add')
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    @php
                        $custom_labels = json_decode(session('business.custom_labels'), true);
                    @endphp
                    <div class="row align-items-center mb-3" id="sell_dt_top">
                        <div class="col-sm-12 col-md-3" id="sell_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-center" id="sell_dt_buttons"></div>
                        <div class="col-sm-12 col-md-3 text-md-end" id="sell_dt_filter"></div>
                    </div>
                    <table class="table table-bordered table-striped ajax_view" id="sell_table">
                        <thead>
                            <tr>
                                <th>@lang('messages.action')</th>
                                <th>@lang('messages.date')</th>
                                <th>@lang('sale.invoice_no')</th>
                                <th>@lang('sale.customer_name')</th>
                                <th>@lang('lang_v1.contact_no')</th>
                                <th>@lang('sale.location')</th>
                                <th>@lang('sale.payment_status')</th>
                                <th>@lang('lang_v1.payment_method')</th>
                                <th>@lang('sale.total_amount')</th>
                                <th>@lang('sale.total_paid')</th>
                                <th>@lang('lang_v1.sell_due')</th>
                                <th>@lang('lang_v1.sell_return_due')</th>
                                <th>@lang('lang_v1.shipping_status')</th>
                                <th>@lang('lang_v1.total_items')</th>
                                <th>@lang('lang_v1.types_of_service')</th>
                                <th>{{ $custom_labels['types_of_service']['custom_field_1'] ?? __('lang_v1.service_custom_field_1') }}
                                </th>
                                <th>{{ $custom_labels['sell']['custom_field_1'] ?? '' }}</th>
                                <th>{{ $custom_labels['sell']['custom_field_2'] ?? '' }}</th>
                                <th>{{ $custom_labels['sell']['custom_field_3'] ?? '' }}</th>
                                <th>{{ $custom_labels['sell']['custom_field_4'] ?? '' }}</th>
                                <th>@lang('lang_v1.added_by')</th>
                                <th>@lang('sale.sell_note')</th>
                                <th>@lang('sale.staff_note')</th>
                                <th>@lang('sale.shipping_details')</th>
                                <th>@lang('restaurant.table')</th>
                                <th>@lang('restaurant.service_staff')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr class="bg-gray font-17 footer-total text-center">
                                <td colspan="6"><strong>@lang('sale.total'):</strong></td>
                                <td class="footer_payment_status_count"></td>
                                <td class="payment_method_count"></td>
                                <td class="footer_sale_total"></td>
                                <td class="footer_total_paid"></td>
                                <td class="footer_total_remaining"></td>
                                <td class="footer_total_sell_return_due"></td>
                                <td colspan="2"></td>
                                <td class="service_type_count"></td>
                                <td colspan="7"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row align-items-center mt-3" id="sell_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="sell_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="sell_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <!-- This will be printed -->
    <section class="invoice print_section" id="receipt_section">
    </section>

@endsection

@section('javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // Destroy existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#sell_table')) {
                $('#sell_table').DataTable().destroy();
            }

            //Date range as a button
            var startLast30 = moment().subtract(29, 'days');
            var endLast = moment();

            // Function to update heading with date range
            function updateDateRangeHeading(start, end) {
                if (start && end) {
                    var formattedStart = start.format(moment_date_format);
                    var formattedEnd = end.format(moment_date_format);
                    $('#sell_list_selected_range').text(formattedStart + ' ~ ' + formattedEnd);
                } else {
                    // Reset to default (last 30 days)
                    var defaultStart = moment().subtract(29, 'days').format(moment_date_format);
                    var defaultEnd = moment().format(moment_date_format);
                    $('#sell_list_selected_range').text(defaultStart + ' ~ ' + defaultEnd);
                }
            }

            $('#sell_list_filter_date_range').daterangepicker(
                $.extend(true, {}, dateRangeSettings, { startDate: startLast30, endDate: endLast }),
                function (start, end) {
                    updateDateRangeHeading(start, end);
                    sell_table.ajax.reload();
                }
            );
            $('#sell_list_filter_date_range').on('cancel.daterangepicker', function (ev, picker) {
                $('#sell_list_filter_date_range').val('');
                updateDateRangeHeading(null, null);
                sell_table.ajax.reload();
            });

            sell_table = $('#sell_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                fixedHeader: false,
                aaSorting: [
                    [1, 'desc']
                ],
                scrollX: true,
                scrollCollapse: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3 text-md-end'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-md-end'p>>",
                buttons: [
                    {
                        extend: 'csv',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-file-csv" aria-hidden="true"></i> Export CSV'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-file-excel" aria-hidden="true"></i> Export Excel'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-print" aria-hidden="true"></i> Print'
                    },
                    {
                        extend: 'colvis',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-columns" aria-hidden="true"></i> Column visibility'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-file-pdf" aria-hidden="true"></i> Export PDF'
                    }
                ],
                "ajax": {
                    "url": "{{ route('ai-template.sells.index') }}",
                    "data": function (d) {
                        if ($('#sell_list_filter_date_range').val()) {
                            var start = $('#sell_list_filter_date_range').data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            var end = $('#sell_list_filter_date_range').data('daterangepicker').endDate
                                .format('YYYY-MM-DD');
                            d.start_date = start;
                            d.end_date = end;
                        }
                        d.is_direct_sale = 1;

                        d.location_id = $('#sell_list_filter_location_id').val();
                        d.customer_id = $('#sell_list_filter_customer_id').val();
                        d.payment_status = $('#sell_list_filter_payment_status').val();
                        d.created_by = $('#created_by').val();
                        d.sales_cmsn_agnt = $('#sales_cmsn_agnt').val();
                        d.service_staffs = $('#service_staffs').val();

                        if ($('#shipping_status').length) {
                            d.shipping_status = $('#shipping_status').val();
                        }

                        if ($('#sell_list_filter_source').length) {
                            d.source = $('#sell_list_filter_source').val();
                        }

                        if ($('#only_subscriptions').is(':checked')) {
                            d.only_subscriptions = 1;
                        }

                        if ($('#payment_method').length) {
                            d.payment_method = $('#payment_method').val();
                        }

                        d = __datatable_ajax_callback(d);
                    }
                },
                columns: [{
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    "searchable": false
                },
                {
                    data: 'transaction_date',
                    name: 'transaction_date'
                },
                {
                    data: 'invoice_no',
                    name: 'invoice_no'
                },
                {
                    data: 'conatct_name',
                    name: 'conatct_name'
                },
                {
                    data: 'mobile',
                    name: 'contacts.mobile'
                },
                {
                    data: 'business_location',
                    name: 'bl.name'
                },
                {
                    data: 'payment_status',
                    name: 'payment_status'
                },
                {
                    data: 'payment_methods',
                    orderable: false,
                    "searchable": false
                },
                {
                    data: 'final_total',
                    name: 'final_total'
                },
                {
                    data: 'total_paid',
                    name: 'total_paid',
                    "searchable": false
                },
                {
                    data: 'total_remaining',
                    name: 'total_remaining'
                },
                {
                    data: 'return_due',
                    orderable: false,
                    "searchable": false
                },
                {
                    data: 'shipping_status',
                    name: 'shipping_status'
                },
                {
                    data: 'total_items',
                    name: 'total_items',
                    "searchable": false
                },
                {
                    data: 'types_of_service_name',
                    name: 'tos.name',
                    @if (empty($is_types_service_enabled))
                        visible: false
                    @endif
                                    },
                {
                    data: 'service_custom_field_1',
                    name: 'service_custom_field_1',
                    @if (empty($is_types_service_enabled))
                        visible: false
                    @endif
                                    },
            {
                data: 'custom_field_1',
                name: 'transactions.custom_field_1',
                @if (empty($custom_labels['sell']['custom_field_1']))
                    visible: false
                @endif
                                    },
            {
                data: 'custom_field_2',
                name: 'transactions.custom_field_2',
                @if (empty($custom_labels['sell']['custom_field_2']))
                    visible: false
                @endif
                                    },
            {
                data: 'custom_field_3',
                name: 'transactions.custom_field_3',
                @if (empty($custom_labels['sell']['custom_field_3']))
                    visible: false
                @endif
                                    },
            {
                data: 'custom_field_4',
                name: 'transactions.custom_field_4',
                @if (empty($custom_labels['sell']['custom_field_4']))
                    visible: false
                @endif
                                    },
            {
                data: 'added_by',
                name: 'u.first_name'
            },
            {
                data: 'additional_notes',
                name: 'additional_notes'
            },
            {
                data: 'staff_note',
                name: 'staff_note'
            },
            {
                data: 'shipping_details',
                name: 'shipping_details'
            },
            {
                data: 'table_name',
                name: 'tables.name',
                @if (empty($is_tables_enabled))
                    visible: false
                @endif
                                    },
            {
                data: 'waiter',
                name: 'ss.first_name',
                @if (empty($is_service_staff_enabled))
                    visible: false
                @endif
                                    },
                                ],
            "fnDrawCallback": function (oSettings) {
                __currency_convert_recursively($('#sell_table'));
            },
            "footerCallback": function (row, data, start, end, display) {
                var footer_sale_total = 0;
                var footer_total_paid = 0;
                var footer_total_remaining = 0;
                var footer_total_sell_return_due = 0;
                for (var r in data) {
                    footer_sale_total += $(data[r].final_total).data('orig-value') ? parseFloat($(
                        data[r].final_total).data('orig-value')) : 0;
                    footer_total_paid += $(data[r].total_paid).data('orig-value') ? parseFloat($(
                        data[r].total_paid).data('orig-value')) : 0;
                    footer_total_remaining += $(data[r].total_remaining).data('orig-value') ?
                        parseFloat($(data[r].total_remaining).data('orig-value')) : 0;
                    footer_total_sell_return_due += $(data[r].return_due).find('.sell_return_due')
                        .data('orig-value') ? parseFloat($(data[r].return_due).find(
                            '.sell_return_due').data('orig-value')) : 0;
                }

                $('.footer_total_sell_return_due').html(__currency_trans_from_en(
                    footer_total_sell_return_due));
                $('.footer_total_remaining').html(__currency_trans_from_en(footer_total_remaining));
                $('.footer_total_paid').html(__currency_trans_from_en(footer_total_paid));
                $('.footer_sale_total').html(__currency_trans_from_en(footer_sale_total));

                $('.footer_payment_status_count').html(__count_status(data, 'payment_status'));
                $('.service_type_count').html(__count_status(data, 'types_of_service_name'));
                $('.payment_method_count').html(__count_status(data, 'payment_methods'));
            },
            createdRow: function (row, data, dataIndex) {
                $(row).find('td:eq(6)').attr('class', 'clickable_td');
            },
            initComplete: function () {
                var relocate = function () {
                    var $wrapper = $('#sell_table_wrapper');
                    if ($wrapper.length < 1) return;

                    var $length = $wrapper.find('.dataTables_length');
                    var $buttons = $wrapper.find('.dt-buttons');
                    var $filter = $wrapper.find('.dataTables_filter');
                    var $info = $wrapper.find('.dataTables_info');
                    var $paginate = $wrapper.find('.dataTables_paginate');

                    if ($length.length) $('#sell_dt_length').empty().append($length);
                    if ($buttons.length) $('#sell_dt_buttons').empty().append($buttons);
                    if ($filter.length) $('#sell_dt_filter').empty().append($filter);
                    if ($info.length) $('#sell_dt_info').empty().append($info);
                    if ($paginate.length) $('#sell_dt_paginate').empty().append($paginate);
                };

                relocate();
                var api = this.api();
                api.on('draw.dt', function () {
                    relocate();
                });
            }
                    });

        $(document).on('change',
            '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status, #created_by, #sales_cmsn_agnt, #service_staffs, #shipping_status, #sell_list_filter_source, #payment_method',
            function () {
                sell_table.ajax.reload();
            });

        $('#only_subscriptions').on('ifChanged', function (event) {
            sell_table.ajax.reload();
        });
                        });
    </script>
@endsection