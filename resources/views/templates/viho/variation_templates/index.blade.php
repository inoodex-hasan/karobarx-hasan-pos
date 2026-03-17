@extends('templates.viho.layout')
@section('title', __('product.variations'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('product.variations')
                        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('lang_v1.manage_product_variations')</small>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>@lang('lang_v1.all_variations')</h5>
                    <a class="btn btn-primary btn-sm btn-modal"
                       data-href="{{ action([\App\Http\Controllers\VariationTemplateController::class, 'create']) }}"
                       data-container=".variation_modal">@lang('messages.add')</a>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-2" id="variation_dt_top">
                        <div class="col-sm-12 col-md-3" id="variation_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-center" id="variation_dt_buttons"></div>
                        <div class="col-sm-12 col-md-3 text-md-end" id="variation_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="variation_table">
                            <thead>
                            <tr>
                                <th>@lang('product.variations')</th>
                                <th>@lang('lang_v1.values')</th>
                                <th>@lang('messages.action')</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="row align-items-center mt-2" id="variation_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="variation_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="variation_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade variation_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
    </div>
@endsection

@push('styles')
    <style>
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

        #variation_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #variation_table {
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
@endpush

@section('javascript')
    <script type="text/javascript">
        $(function(){
            $('#variation_table').DataTable({
                retrieve: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('ai-template.variation-templates.index') }}',
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
                columnDefs: [{
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                }],
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#variation_table_wrapper');
                        if ($wrapper.length < 1) return;
                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');
                        if ($length.length) $('#variation_dt_length').empty().append($length);
                        if ($buttons.length) $('#variation_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#variation_dt_filter').empty().append($filter);
                        if ($info.length) $('#variation_dt_info').empty().append($info);
                        if ($paginate.length) $('#variation_dt_paginate').empty().append($paginate);
                    }
                    relocate();
                    this.api().on('draw.dt', function () {
                        relocate();
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    });
                }
            });
        });
    </script>
@endsection
