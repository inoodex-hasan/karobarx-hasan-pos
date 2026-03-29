@extends('templates.viho.layout')
@section('title', __('purchase.purchases'))

@php
    // Determine route prefix based on current URL
    $route_prefix = request()->is('ai-template/*') ? 'ai-template.' : '';
@endphp

@section('content')
<style>
    /* Force horizontal scrollbar visibility on purchases table */
    #purchase_table_wrapper {
        display: block !important;
        width: 100% !important;
        overflow-x: visible !important;
    }

    #purchase_table_wrapper .dataTables_scroll {
        display: block !important;
        width: 100% !important;
        overflow-x: auto !important;
    }

    #purchase_table_wrapper .dataTables_scrollBody {
        overflow-x: auto !important;
    }

    #purchase_table {
        width: 100% !important;
        margin: 0 !important;
        display: table !important;
    }

    /* Ensure the DataTables scroll container allows the horizontal scrollbar to show */
    .dataTables_wrapper .dataTables_scroll {
        clear: both;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('purchase.purchases')</h1>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
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
            {!! Form::label('purchase_list_filter_supplier_id', __('purchase.supplier') . ':') !!}
            {!! Form::select('purchase_list_filter_supplier_id', $suppliers, null, [
    'class' => 'form-control select2',
    'style' => 'width:100%',
    'placeholder' => __('lang_v1.all'),
]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('purchase_list_filter_status', __('purchase.purchase_status') . ':') !!}
            {!! Form::select('purchase_list_filter_status', $orderStatuses, null, [
    'class' => 'form-control select2',
    'style' => 'width:100%',
    'placeholder' => __('lang_v1.all'),
]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('purchase_list_filter_payment_status', __('purchase.payment_status') . ':') !!}
            {!! Form::select(
    'purchase_list_filter_payment_status',
    [
        'paid' => __('lang_v1.paid'),
        'due' => __('lang_v1.due'),
        'partial' => __('lang_v1.partial'),
        'overdue' => __('lang_v1.overdue'),
    ],
    null,
    ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')],
) !!}
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
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="card-title">
                            <i class="fas fa-shopping-cart mr-1"></i>
                            @lang('purchase.all_purchases')
                        </h3>
                        @can('purchase.create')
                            <div class="card-tools">
                                <a class="btn btn-primary btn-sm" href="{{ route($route_prefix . 'purchases.create') }}">
                                    <i class="fa fa-plus"></i> @lang('messages.add')
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-3" id="purchase_dt_top">
                        <div class="col-sm-12 col-md-3" id="purchase_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-center" id="purchase_dt_buttons"></div>
                        <div class="col-sm-12 col-md-3 text-md-end" id="purchase_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        @include('templates.viho.purchase.partials.purchase_table')
                    </div>
                    <div class="row align-items-center mt-3" id="purchase_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="purchase_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="purchase_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    @include('templates.viho.purchase.partials.update_purchase_status_modal')

</section>

<section id="receipt_section" class="print_section"></section>
@stop

@section('javascript')
    @php
        $custom_labels = json_decode(session('business.custom_labels'), true);
    @endphp
    <script>
        // Custom field visibility configuration
        var customFieldVisibility = {
            custom_field_1: @json(!empty($custom_labels['purchase']['custom_field_1'])),
            custom_field_2: @json(!empty($custom_labels['purchase']['custom_field_2'])),
            custom_field_3: @json(!empty($custom_labels['purchase']['custom_field_3'])),
            custom_field_4: @json(!empty($custom_labels['purchase']['custom_field_4']))
        };
    </script>
    <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script>
        $(document).ready(function () {
            //Date range as a button
            $('#purchase_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function (start, end) {
                    $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(
                        moment_date_format));
                    purchase_table.ajax.reload();
                }
            );
            $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function (ev, picker) {
                $('#purchase_list_filter_date_range').val('');
                purchase_table.ajax.reload();
            });

            // Purchase table initialization
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#purchase_table')) {
                $('#purchase_table').DataTable().destroy();
                $('#purchase_table').find('tbody').remove();
            }

            purchase_table = $('#purchase_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                fixedHeader: false,
                scrollX: true,
                scrollCollapse: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
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
                ajax: {
                    url: '{{ route($route_prefix . "purchases.index") }}',
                    data: function (d) {
                        if ($('#purchase_list_filter_location_id').length) {
                            d.location_id = $('#purchase_list_filter_location_id').val();
                        }
                        if ($('#purchase_list_filter_supplier_id').length) {
                            d.supplier_id = $('#purchase_list_filter_supplier_id').val();
                        }
                        if ($('#purchase_list_filter_payment_status').length) {
                            d.payment_status = $('#purchase_list_filter_payment_status').val();
                        }
                        if ($('#purchase_list_filter_status').length) {
                            d.status = $('#purchase_list_filter_status').val();
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

                        d = __datatable_ajax_callback(d);
                    },
                },
                aaSorting: [[1, 'desc']],
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'transaction_date', name: 'transaction_date' },
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'location_name', name: 'BS.name' },
                    { data: 'name', name: 'contacts.name' },
                    { data: 'status', name: 'status' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'final_total', name: 'final_total' },
                    { data: 'payment_due', name: 'payment_due', orderable: false, searchable: false },
                    { data: 'custom_field_1', name: 'transactions.custom_field_1', visible: customFieldVisibility.custom_field_1 },
                    { data: 'custom_field_2', name: 'transactions.custom_field_2', visible: customFieldVisibility.custom_field_2 },
                    { data: 'custom_field_3', name: 'transactions.custom_field_3', visible: customFieldVisibility.custom_field_3 },
                    { data: 'custom_field_4', name: 'transactions.custom_field_4', visible: customFieldVisibility.custom_field_4 },
                    { data: 'added_by', name: 'u.first_name' },
                ],
                fnDrawCallback: function (oSettings) {
                    __currency_convert_recursively($('#purchase_table'));
                },
                footerCallback: function (row, data, start, end, display) {
                    var total_purchase = 0;
                    var total_due = 0;
                    var total_purchase_return_due = 0;
                    for (var r in data) {
                        total_purchase += $(data[r].final_total).data('orig-value') ?
                            parseFloat($(data[r].final_total).data('orig-value')) : 0;
                        var payment_due_obj = $('<div>' + data[r].payment_due + '</div>');
                        total_due += payment_due_obj.find('.payment_due').data('orig-value') ?
                            parseFloat(payment_due_obj.find('.payment_due').data('orig-value')) : 0;

                        total_purchase_return_due += payment_due_obj.find('.purchase_return').data('orig-value') ?
                            parseFloat(payment_due_obj.find('.purchase_return').data('orig-value')) : 0;
                    }

                    $('.footer_purchase_total').html(__currency_trans_from_en(total_purchase));
                    $('.footer_total_due').html(__currency_trans_from_en(total_due));
                    $('.footer_total_purchase_return_due').html(__currency_trans_from_en(total_purchase_return_due));
                    $('.footer_status_count').html(__count_status(data, 'status'));
                    $('.footer_payment_status_count').html(__count_status(data, 'payment_status'));
                },
                createdRow: function (row, data, dataIndex) {
                    $(row).find('td:eq(5)').attr('class', 'clickable_td');
                },
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#purchase_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#purchase_dt_length').empty().append($length);
                        if ($buttons.length) $('#purchase_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#purchase_dt_filter').empty().append($filter);
                        if ($info.length) $('#purchase_dt_info').empty().append($info);
                        if ($paginate.length) $('#purchase_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                }
            });

            $(document).on('change', '#purchase_list_filter_location_id, #purchase_list_filter_supplier_id, #purchase_list_filter_payment_status, #purchase_list_filter_status', function () {
                purchase_table.ajax.reload();
            });
        });
    </script>
@endsection

@push('styles')
    <style>
        #purchase_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #purchase_table {
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