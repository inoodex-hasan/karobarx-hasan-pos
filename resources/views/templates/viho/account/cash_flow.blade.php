@extends('templates.viho.layout')
@section('title', __('lang_v1.cash_flow'))

@push('styles')
    <style>
        #cash_flow_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #cash_flow_table {
            width: 100% !important;
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

        .dataTables_paginate {
            display: flex !important;
            justify-content: flex-end !important;
            width: 100% !important;
        }

        .paging_simple_numbers {
            margin-left: auto !important;
        }
    </style>
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('lang_v1.cash_flow')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        <div class="row">
            <div class="col-sm-12">
                @component('components.filters', ['title' => __('report.filters')])
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('account_id', __('account.account') . ':') !!}
                        {!! Form::select('account_id', $accounts, '', ['class' => 'form-control', 'placeholder' => __('messages.all')]) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('cash_flow_location_id', __('purchase.business_location') . ':') !!}
                        {!! Form::select('cash_flow_location_id', $business_locations, null, [
        'class' => 'form-control select2',
        'style' => 'width:100%',
    ]) !!}
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('transaction_date_range', __('report.date_range') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('transaction_date_range', null, [
        'class' => 'form-control',
        'readonly',
        'placeholder' => __('report.date_range'),
    ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('transaction_type', __('account.transaction_type') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-exchange-alt"></i></span>
                            {!! Form::select(
        'transaction_type',
        ['' => __('messages.all'), 'debit' => __('account.debit'), 'credit' => __('account.credit')],
        '',
        ['class' => 'form-control'],
    ) !!}
                        </div>
                    </div>
                </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                @component('components.widget')
                @can('account.access')
                    <div class="row align-items-center mb-2" id="cash_flow_dt_top">
                        <div class="col-sm-12 col-md-3" id="cash_flow_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-center" id="cash_flow_dt_buttons"></div>
                        <div class="col-sm-12 col-md-3 text-md-end" id="cash_flow_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="cash_flow_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('account.account')</th>
                                    <th>@lang('lang_v1.description')</th>
                                    <th>@lang('lang_v1.payment_method')</th>
                                    <th>@lang('lang_v1.payment_details')</th>
                                    <th>@lang('account.debit')</th>
                                    <th>@lang('account.credit')</th>
                                    <th>@lang('lang_v1.account_balance') @show_tooltip(__('lang_v1.account_balance_tooltip'))
                                    </th>
                                    <th>@lang('lang_v1.total_balance') @show_tooltip(__('lang_v1.total_balance_tooltip'))</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 footer-total text-center">
                                    <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                                    <td class="footer_total_debit"></td>
                                    <td class="footer_total_credit"></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endcan
                @endcomponent
            </div>
        </div>


        <div class="modal fade account_model" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script>
        $(document).ready(function () {

            // Destroy existing DataTable if it exists to prevent reinitialization error
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#cash_flow_table')) {
                try {
                    $('#cash_flow_table').DataTable().destroy();
                } catch (e) { }
            }

            // dateRangeSettings.autoUpdateInput = false
            $('#transaction_date_range').daterangepicker(
                dateRangeSettings,
                function (start, end) {
                    $('#transaction_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(
                        moment_date_format));
                    cash_flow_table.ajax.reload();
                }
            );

            // Cash Flow Table
            cash_flow_table = $('#cash_flow_table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader: false,
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
                    "url": "{{ action([\App\Http\Controllers\AccountController::class, 'cashFlow']) }}",
                    "data": function (d) {
                        var start = '';
                        var end = '';
                        if ($('#transaction_date_range').val() != '') {
                            start = $('#transaction_date_range').data('daterangepicker').startDate
                                .format('YYYY-MM-DD');
                            end = $('#transaction_date_range').data('daterangepicker').endDate.format(
                                'YYYY-MM-DD');
                        }

                        d.account_id = $('#account_id').val();
                        d.type = $('#transaction_type').val();
                        d.start_date = start,
                            d.end_date = end
                        d.location_id = $('#cash_flow_location_id').val();

                    }
                },
                "ordering": false,
                columns: [{
                    data: 'operation_date',
                    name: 'operation_date'
                },
                {
                    data: 'account_name',
                    name: 'A.name'
                },
                {
                    data: 'sub_type',
                    name: 'sub_type',
                    searchable: false
                },
                {
                    data: 'method',
                    name: 'TP.method'
                },
                {
                    data: 'payment_details',
                    name: 'TP.payment_ref_no'
                },
                {
                    data: 'debit',
                    name: 'amount',
                    searchable: false
                },
                {
                    data: 'credit',
                    name: 'amount',
                    searchable: false
                },
                {
                    data: 'balance',
                    name: 'balance',
                    searchable: false
                },
                {
                    data: 'total_balance',
                    name: 'total_balance',
                    searchable: false
                },
                ],
                "fnDrawCallback": function (oSettings) {
                    __currency_convert_recursively($('#cash_flow_table'));
                },
                "footerCallback": function (row, data, start, end, display) {
                    var footer_total_debit = 0;
                    var footer_total_credit = 0;

                    for (var r in data) {
                        footer_total_debit += $(data[r].debit).data('orig-value') ? parseFloat($(data[r]
                            .debit).data('orig-value')) : 0;
                        footer_total_credit += $(data[r].credit).data('orig-value') ? parseFloat($(data[
                            r].credit).data('orig-value')) : 0;
                    }

                    $('.footer_total_debit').html(__currency_trans_from_en(footer_total_debit));
                    $('.footer_total_credit').html(__currency_trans_from_en(footer_total_credit));
                },
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#cash_flow_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#cash_flow_dt_length').empty().append($length);
                        if ($buttons.length) $('#cash_flow_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#cash_flow_dt_filter').empty().append($filter);
                        if ($info.length) $('#cash_flow_dt_info').empty().append($info);
                        if ($paginate.length) $('#cash_flow_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                }
            });
            $('#transaction_type, #account_id, #cash_flow_location_id').change(function () {
                cash_flow_table.ajax.reload();
            });
            $('#transaction_date_range').on('cancel.daterangepicker', function (ev, picker) {
                $('#transaction_date_range').val('').change();
                cash_flow_table.ajax.reload();
            });

        });
    </script>
@endsection