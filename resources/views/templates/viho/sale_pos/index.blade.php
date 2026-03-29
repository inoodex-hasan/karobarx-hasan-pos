@extends('templates.viho.layout')
@section('title', __('sale.list_pos'))

@push('styles')
    <style>
        /* Force horizontal scrollbar visibility on POS table */
        #sell_table_wrapper {
            display: block !important;
            width: 100% !important;
            overflow-x: visible !important;
        }

        #sell_table_wrapper .dataTables_scroll {
            display: block !important;
            width: 100% !important;
            overflow-x: auto !important;
        }

        #sell_table_wrapper .dataTables_scrollBody {
            overflow-x: auto !important;
        }

        #sell_table {
            width: 100% !important;
            margin: 0 !important;
            display: table !important;
        }

        /* Ensure the DataTables scroll container allows the horizontal scrollbar to show */
        .dataTables_wrapper .dataTables_scroll {
            clear: both;
        }

        /* Standard Viho controls adjustment */
        #sell_table_wrapper {
            width: 100% !important;
            display: block !important;
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
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>@lang('sale.pos_sale')</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">@lang('sale.list_pos')</h5>
                @can('sell.create')
                    <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"
                        href="{{ action([\App\Http\Controllers\SellPosController::class, 'create']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        <span>@lang('messages.add')</span>
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    @component('components.filters', ['title' => __('report.filters')])
                    @include('sell.partials.sell_list_filters')
                    @endcomponent
                </div>

                <!-- Sales Table -->
                @can('sell.view')
                    <input type="hidden" name="is_direct_sale" id="is_direct_sale" value="0">
                    <div class="row align-items-center mb-2" id="sell_dt_top">
                        <div class="col-sm-12 col-md-3" id="sell_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-center" id="sell_dt_buttons"></div>
                        <div class="col-sm-12 col-md-3 text-md-end" id="sell_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        @include('sale_pos.partials.sales_table')
                    </div>
                    <div class="row align-items-center mt-2" id="sell_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="sell_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="sell_dt_paginate"></div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

<!-- This will be printed -->
<section class="invoice print_section" id="receipt_section">
</section>
@stop

@section('javascript')
    @include('templates.viho.sale_pos.partials.sale_table_javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection