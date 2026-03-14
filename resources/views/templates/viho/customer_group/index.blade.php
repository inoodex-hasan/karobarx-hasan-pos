@extends('templates.viho.layout')
@section('title', __('lang_v1.customer_groups'))

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0">@lang('lang_v1.customer_groups')</h5>
                        </div>
                        @can('customer.create')
                            <div class="ms-auto">
                                <a class="btn btn-primary btn-modal"
                                    data-href="{{ route('ai-template.customer-group.create') }}"
                                    data-container=".customer_groups_modal">
                                    @lang('messages.add')
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    @can('customer.view')
                        <div class="row align-items-center mb-2" id="customer_groups_dt_top">
                            <div class="col-sm-12 col-md-6" id="customer_groups_dt_length"></div>
                            <div class="col-sm-12 col-md-6 text-md-end" id="customer_groups_dt_filter"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="display" id="customer_groups_table">
                                <thead>
                                    <tr>
                                        <th>@lang('lang_v1.customer_group_name')</th>
                                        <th>@lang('lang_v1.calculation_percentage')</th>
                                        <th>@lang('lang_v1.selling_price_group')</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row align-items-center mt-2" id="customer_groups_dt_bottom">
                            <div class="col-sm-12 col-md-5" id="customer_groups_dt_info"></div>
                            <div class="col-sm-12 col-md-7 text-md-end" id="customer_groups_dt_paginate"></div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade customer_groups_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection

@push('styles')
    <style>
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

        #customer_groups_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #customer_groups_table {
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
        $(document).on('change', '#price_calculation_type', function() {
            var price_calculation_type = $(this).val();

            if (price_calculation_type == 'percentage') {
                $('.percentage-field').removeClass('hide');
                $('.selling_price_group-field').addClass('hide');
            } else {
                $('.percentage-field').addClass('hide');
                $('.selling_price_group-field').removeClass('hide');
            }
        })
    </script>
@endsection
