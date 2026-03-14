@extends('templates.viho.layout')
@section('title', __('lang_v1.sales_commission_agents'))

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0">@lang('lang_v1.sales_commission_agents')</h5>
                        </div>
                        @can('user.create')
                            <div class="ms-auto">
                                <a class="btn btn-primary" href="{{ route('ai-template.sales-commission-agents.create') }}">
                                    @lang('messages.add')
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    @can('user.view')
                        <div class="row align-items-center mb-2" id="sales_agent_dt_top">
                            <div class="col-sm-12 col-md-6" id="sales_agent_dt_length"></div>
                            <div class="col-sm-12 col-md-6 text-md-end" id="sales_agent_dt_filter"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="display" id="sales_commission_agent_table">
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
        /* Make DataTables controls match Viho users/roles layout */
        #sales_commission_agent_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #sales_commission_agent_table {
            width: 100% !important;
        }

        #sales_agent_dt_length .dataTables_length {
            white-space: nowrap !important;
        }

        #sales_agent_dt_length .dataTables_length label {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            margin-bottom: 0 !important;
            white-space: nowrap !important;
        }

        #sales_agent_dt_length .dataTables_length select {
            width: auto !important;
            display: inline-block !important;
            padding-right: 30px !important;
            margin: 0 !important;
            height: 30px !important;
            font-size: 13px !important;
            border-radius: 4px !important;
        }

        #sales_agent_dt_filter .dataTables_filter label {
            margin-bottom: 0 !important;
            white-space: nowrap !important;
        }

        #sales_agent_dt_filter .dataTables_filter input {
            width: auto !important;
            display: inline-block !important;
        }

        #sales_agent_dt_paginate .dataTables_paginate {
            display: flex !important;
            justify-content: flex-end !important;
            width: 100% !important;
        }

        #sales_agent_dt_paginate .paging_simple_numbers {
            margin-left: auto !important;
        }
    </style>
@endpush

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            // Replace icons after table draw (Viho buttons use feather icons)
            $('#sales_commission_agent_table').on('draw.dt', function () {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        });
    </script>
@endsection
