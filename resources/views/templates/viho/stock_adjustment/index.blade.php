@extends('templates.viho.layout')
@section('title', __('stock_adjustment.stock_adjustments'))

@push('styles')
    <style>
        /* Make DataTables controls match Viho users/roles layout */
        #stock_adjustment_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #stock_adjustment_table {
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

<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>@lang('stock_adjustment.stock_adjustments')</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">@lang('stock_adjustment.all_stock_adjustments')</h5>
                @if (auth()->user()->can('stock_adjustment.create'))
                    <a class="btn btn-primary btn-sm" href="{{ route('ai-template.stock-adjustments.create') }}">
                        @lang('messages.add')
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="row align-items-center mb-2" id="stock_adjustment_dt_top">
                    <div class="col-sm-12 col-md-3" id="stock_adjustment_dt_length"></div>
                    <div class="col-sm-12 col-md-6 text-center" id="stock_adjustment_dt_buttons"></div>
                    <div class="col-sm-12 col-md-3 text-md-end" id="stock_adjustment_dt_filter"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped ajax_view" id="stock_adjustment_table">
                        <thead>
                            <tr>
                                <th>@lang('messages.action')</th>
                                <th>@lang('messages.date')</th>
                                <th>@lang('purchase.ref_no')</th>
                                <th>@lang('business.location')</th>
                                <th>@lang('stock_adjustment.adjustment_type')</th>
                                <th>@lang('stock_adjustment.total_amount')</th>
                                <th>@lang('stock_adjustment.total_amount_recovered')</th>
                                <th>@lang('stock_adjustment.reason_for_stock_adjustment')</th>
                                <th>@lang('lang_v1.added_by')</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row align-items-center mt-2" id="stock_adjustment_dt_bottom">
                    <div class="col-sm-12 col-md-5" id="stock_adjustment_dt_info"></div>
                    <div class="col-sm-12 col-md-7 text-md-end" id="stock_adjustment_dt_paginate"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('javascript')
    <script>
        $(document).ready(function () {
            // Destroy existing DataTable if it exists to prevent reinitialization error
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#stock_adjustment_table')) {
                try {
                    $('#stock_adjustment_table').DataTable().destroy();
                } catch (e) { }
            }

            // Initialize DataTable with correct URL for ai-template
            stock_adjustment_table = $('#stock_adjustment_table').DataTable({
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
                ajax: '/ai-template/stock-adjustments',
                columnDefs: [{
                    targets: 0,
                    orderable: false,
                    searchable: false,
                },],
                aaSorting: [
                    [1, 'desc']
                ],
                columns: [{
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'transaction_date',
                    name: 'transaction_date'
                },
                {
                    data: 'ref_no',
                    name: 'ref_no'
                },
                {
                    data: 'location_name',
                    name: 'BL.name'
                },
                {
                    data: 'adjustment_type',
                    name: 'adjustment_type'
                },
                {
                    data: 'final_total',
                    name: 'final_total'
                },
                {
                    data: 'total_amount_recovered',
                    name: 'total_amount_recovered'
                },
                {
                    data: 'additional_notes',
                    name: 'additional_notes'
                },
                {
                    data: 'added_by',
                    name: 'u.first_name'
                },
                ],
                fnDrawCallback: function (oSettings) {
                    __currency_convert_recursively($('#stock_adjustment_table'));
                },
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#stock_adjustment_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#stock_adjustment_dt_length').empty().append($length);
                        if ($buttons.length) $('#stock_adjustment_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#stock_adjustment_dt_filter').empty().append($filter);
                        if ($info.length) $('#stock_adjustment_dt_info').empty().append($info);
                        if ($paginate.length) $('#stock_adjustment_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                }
            });
        });

        var detailRows = [];

        $(document).on('click', 'button.delete_stock_adjustment', function () {
            swal({
                title: LANG.sure,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    var href = $(this).data('href');
                    $.ajax({
                        method: 'DELETE',
                        url: href,
                        dataType: 'json',
                        success: function (result) {
                            if (result.success) {
                                toastr.success(result.msg);
                                stock_adjustment_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                }
            });
        });
    </script>
@endsection

@cannot('view_purchase_price')
<style>
    .show_price_with_permission {
        display: none !important;
    }
</style>
@endcannot