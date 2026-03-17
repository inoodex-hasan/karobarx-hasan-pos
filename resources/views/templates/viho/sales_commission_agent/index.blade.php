@extends('templates.viho.layout')
@section('title', __('lang_v1.sales_commission_agents'))

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('lang_v1.sales_commission_agents')</h5>
                    @can('user.create')
                        <a class="btn btn-primary" href="{{ route('ai-template.sales-commission-agents.create') }}">
                            @lang('messages.add')
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    @can('user.view')
                    <div class="row align-items-center mb-2" id="sales_agent_dt_top">
                        <div class="col-sm-12 col-md-3" id="sales_agent_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-center" id="sales_agent_dt_buttons"></div>
                        <div class="col-sm-12 col-md-3 text-md-end" id="sales_agent_dt_filter"></div>
                    </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="sales_commission_agent_table">
                                <thead>
                                    <tr>
                                        <th>@lang('user.name')</th>
                                        <th>@lang('business.email')</th>
                                        <th>@lang('lang_v1.contact_no')</th>
                                        <th>@lang('business.address')</th>
                                        <th>@lang('lang_v1.cmmsn_percent')</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row align-items-center mt-2" id="sales_agent_dt_bottom">
                            <div class="col-sm-12 col-md-5" id="sales_agent_dt_info"></div>
                            <div class="col-sm-12 col-md-7 text-md-end" id="sales_agent_dt_paginate"></div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- Modal disabled for Viho: use full create/edit pages instead --}}

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

        #sales_commission_agent_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #sales_commission_agent_table {
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
        $(document).ready(function () {
            // Destroy existing instance if it exists (e.g. from app.js) to avoid reinitialization error
            if ($.fn.DataTable.isDataTable('#sales_commission_agent_table')) {
                $('#sales_commission_agent_table').DataTable().destroy();
                $('#sales_commission_agent_table').find('tbody').remove();
            }

            var sales_commission_agent_table = $('#sales_commission_agent_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('ai-template.sales-commission-agents.index') }}',
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
                columns: [
                    { data: 'full_name', name: 'full_name' },
                    { data: 'email', name: 'email' },
                    { data: 'contact_no', name: 'contact_no' },
                    { data: 'address', name: 'address' },
                    { data: 'cmmsn_percent', name: 'cmmsn_percent' },
                    { data: 'action', name: 'action' }
                ],
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#sales_commission_agent_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#sales_agent_dt_length').empty().append($length);
                        if ($buttons.length) $('#sales_agent_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#sales_agent_dt_filter').empty().append($filter);
                        if ($info.length) $('#sales_agent_dt_info').empty().append($info);
                        if ($paginate.length) $('#sales_agent_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
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
