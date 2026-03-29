@extends('templates.viho.layout')
@section('title', __('account.payment_account_report'))

@push('styles')
    <style>
        #payment_account_report_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #payment_account_report {
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
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('account.payment_account_report') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('account_id', __('account.account') . ':') !!}
                        {!! Form::select('account_id', $accounts, null, ['class' => 'form-control select2', 'style' => 'width:100%']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('date_filter', __('report.date_range') . ':') !!}
                        {!! Form::text('date_range', null, [
        'placeholder' => __('lang_v1.select_a_date_range'),
        'class' => 'form-control',
        'id' => 'date_filter',
        'readonly',
    ]) !!}
                    </div>
                </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                <div class="row align-items-center mb-2" id="payment_account_dt_top">
                    <div class="col-sm-12 col-md-3" id="payment_account_dt_length"></div>
                    <div class="col-sm-12 col-md-6 text-center" id="payment_account_dt_buttons"></div>
                    <div class="col-sm-12 col-md-3 text-md-end" id="payment_account_dt_filter"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="payment_account_report">
                        <thead>
                            <tr>
                                <th>@lang('messages.date')</th>
                                <th>@lang('account.payment_ref_no')</th>
                                <th>@lang('account.invoice_ref_no')</th>
                                <th>@lang('sale.amount')</th>
                                <th>@lang('lang_v1.payment_type')</th>
                                <th>@lang('account.account')</th>
                                <th>@lang('lang_v1.description')</th>
                                <th>@lang('messages.action')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                @endcomponent
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@section('javascript')

    <script type="text/javascript">
        $(document).ready(function () {

            // Destroy existing DataTable if it exists to prevent reinitialization error
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#payment_account_report')) {
                try {
                    $('#payment_account_report').DataTable().destroy();
                } catch (e) { }
            }

            if ($('#date_filter').length == 1) {

                $('#date_filter').daterangepicker(
                    dateRangeSettings,
                    function (start, end) {
                        $('#date_filter').val(start.format(moment_date_format) + ' ~ ' + end.format(
                            moment_date_format));
                        payment_account_report.ajax.reload();
                    }
                );

                $('#date_filter').on('cancel.daterangepicker', function (ev, picker) {
                    $(this).val('');
                    payment_account_report.ajax.reload();
                });
            }

            payment_account_report = $('#payment_account_report').DataTable({
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
                    "url": "{{ action([\App\Http\Controllers\AccountReportsController::class, 'paymentAccountReport']) }}",
                    "data": function (d) {
                        d.account_id = $('#account_id').val();
                        var start_date = '';
                        var endDate = '';
                        if ($('#date_filter').val()) {
                            var start_date = $('#date_filter').data('daterangepicker').startDate.format(
                                'YYYY-MM-DD');
                            var endDate = $('#date_filter').data('daterangepicker').endDate.format(
                                'YYYY-MM-DD');
                        }
                        d.start_date = start_date;
                        d.end_date = endDate;
                    }
                },
                columnDefs: [{
                    "targets": 7,
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                    data: 'paid_on',
                    name: 'paid_on'
                },
                {
                    data: 'payment_ref_no',
                    name: 'payment_ref_no'
                },
                {
                    data: 'transaction_number',
                    name: 'transaction_number'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'type',
                    name: 'T.type'
                },
                {
                    data: 'account',
                    name: 'account'
                },
                {
                    data: 'details',
                    name: 'details',
                    "searchable": false
                },
                {
                    data: 'action',
                    name: 'action'
                }
                ],
                "fnDrawCallback": function (oSettings) {
                    __currency_convert_recursively($('#payment_account_report'));
                },
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#payment_account_report_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#payment_account_dt_length').empty().append($length);
                        if ($buttons.length) $('#payment_account_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#payment_account_dt_filter').empty().append($filter);
                        if ($info.length) $('#payment_account_dt_info').empty().append($info);
                        if ($paginate.length) $('#payment_account_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                }
            });

            $('select#account_id, #date_filter').change(function () {
                payment_account_report.ajax.reload();
            });
        })

        $(document).on('submit', 'form#link_account_form', function (e) {
            e.preventDefault();
            var data = $(this).serialize();

            $.ajax({
                method: $(this).attr("method"),
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success: function (result) {
                    if (result.success === true) {
                        $('div.view_modal').modal('hide');
                        toastr.success(result.msg);
                        payment_account_report.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        });
    </script>
@endsection