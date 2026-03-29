@extends('templates.viho.layout')
@section('title', __('lang_v1.purchase_return'))

@section('content')
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('purchase_list_filter_location_id', __('purchase.business_location') . ':') !!}
            {!! Form::select('purchase_list_filter_location_id', $business_locations, null, [
    'class' => 'form-control select2',
    'style' => 'width:100%',
    'placeholder' => __('lang_v1.all'),
]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('purchase_list_filter_date_range', __('report.date_range') . ':') !!}
            {!! Form::text('purchase_list_filter_date_range', null, [
    'placeholder' => __('lang_v1.select_a_date_range'),
    'class' => 'form-control',
    'readonly',
]) !!}
        </div>
    </div>
    @endcomponent

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0">@lang('lang_v1.all_purchase_returns')</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-2" id="purchase_return_dt_top">
                        <div class="col-sm-12 col-md-3" id="purchase_return_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-center" id="purchase_return_dt_buttons"></div>
                        <div class="col-sm-12 col-md-3 text-md-end" id="purchase_return_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        @include('purchase_return.partials.purchase_return_list')
                    </div>
                    <div class="row align-items-center mt-2" id="purchase_return_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="purchase_return_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="purchase_return_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
</section>
@stop

@push('styles')
    <style>
        #purchase_return_datatable_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #purchase_return_datatable {
            width: 100% !important;
        }

        .dataTables_length select {
            width: auto !important;
            display: inline-block !important;
            padding-right: 30px !important;
            margin: 0 5px !important;
            height: 30px !important;
            font-size: 13px !important;
            border-radius: 4px !important;
        }

        .dataTables_length label {
            font-weight: 400 !important;
            margin-bottom: 0 !important;
        }

        .dataTables_paginate {
            display: flex !important;
            justify-content: flex-end !important;
            width: 100% !important;
        }

        .paging_simple_numbers {
            margin-left: auto !important;
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
    </style>
@endpush

@section('javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script>
        $(document).ready(function () {
            $('#purchase_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function (start, end) {
                    $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
                        .format(moment_date_format));
                    purchase_return_table.ajax.reload();
                }
            );
            $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function (ev, picker) {
                $('#purchase_list_filter_date_range').val('');
                purchase_return_table.ajax.reload();
            });

            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#purchase_return_datatable')) {
                $('#purchase_return_datatable').DataTable().destroy();
                $('#purchase_return_datatable').find('tbody').remove();
            }

            purchase_return_table = $('#purchase_return_datatable').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader: false,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3 text-md-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-md-end'p>>",
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
                aaSorting: [
                    [0, 'desc']
                ],
                ajax: {
                    url: '/ai-template/purchase-return',
                    data: function (d) {
                        if ($('#purchase_list_filter_location_id').length) {
                            d.location_id = $('#purchase_list_filter_location_id').val();
                        }

                        var start = '';
                        var end = '';
                        if ($('#purchase_list_filter_date_range').val()) {
                            start = $('input#purchase_list_filter_date_range')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            end = $('input#purchase_list_filter_date_range')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                        }
                        d.start_date = start;
                        d.end_date = end;
                    },
                },
                columnDefs: [{
                    "targets": [7, 8],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                    data: 'transaction_date',
                    name: 'transaction_date'
                },
                {
                    data: 'ref_no',
                    name: 'ref_no'
                },
                {
                    data: 'parent_purchase',
                    name: 'T.ref_no'
                },
                {
                    data: 'location_name',
                    name: 'BS.name'
                },
                {
                    data: 'name',
                    name: 'contacts.name'
                },
                {
                    data: 'payment_status',
                    name: 'payment_status'
                },
                {
                    data: 'final_total',
                    name: 'final_total'
                },
                {
                    data: 'payment_due',
                    name: 'payment_due'
                },
                {
                    data: 'action',
                    name: 'action'
                }
                ],
                fnDrawCallback: function (oSettings) {
                    var total_purchase = sum_table_col($('#purchase_return_datatable'), 'final_total');
                    $('#footer_purchase_return_total').text(total_purchase);

                    $('#footer_payment_status_count').html(__sum_status_html($(
                        '#purchase_return_datatable'), 'payment-status-label'));

                    var total_due = sum_table_col($('#purchase_return_datatable'), 'payment_due');
                    $('#footer_total_due').text(total_due);

                    __currency_convert_recursively($('#purchase_return_datatable'));
                },
                createdRow: function (row, data, dataIndex) {
                    $(row).find('td:eq(5)').attr('class', 'clickable_td');
                },
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#purchase_return_datatable_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#purchase_return_dt_length').empty().append($length);
                        if ($buttons.length) $('#purchase_return_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#purchase_return_dt_filter').empty().append($filter);
                        if ($info.length) $('#purchase_return_dt_info').empty().append($info);
                        if ($paginate.length) $('#purchase_return_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                }
            });

            $(document).on(
                'change',
                '#purchase_list_filter_location_id',
                function () {
                    purchase_return_table.ajax.reload();
                }
            );
        });
    </script>
@endsection