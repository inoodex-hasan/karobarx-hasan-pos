@extends('templates.viho.layout')
@section('title', __('lang_v1.stock_transfers'))

@push('styles')
    <style>
        /* Make DataTables controls match Viho users/roles layout */
        #stock_transfer_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #stock_transfer_table {
            width: 100% !important;
        }

        /* Ensure table rows display properly */
        #stock_transfer_table tbody tr {
            display: table-row !important;
        }

        #stock_transfer_table tbody td {
            display: table-cell !important;
        }

        /* Fix for DataTables rendering all in one row */
        .dataTables_wrapper table.dataTable tbody tr {
            display: table-row !important;
        }
        
        .dataTables_wrapper table.dataTable tbody td {
            display: table-cell !important;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('lang_v1.stock_transfers')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('lang_v1.all_stock_transfers')</h5>
                    @if (auth()->user()->can('stock_transfer.create'))
                        <a class="btn btn-primary btn-sm" href="{{ route('ai-template.stock-transfers.create') }}">
                            @lang('messages.add')
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-2" id="stock_transfer_dt_top">
                        <div class="col-sm-12 col-md-6" id="stock_transfer_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-md-end" id="stock_transfer_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="stock_transfer_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('lang_v1.location_from')</th>
                                    <th>@lang('lang_v1.location_to')</th>
                                    <th>@lang('sale.status')</th>
                                    <th>@lang('lang_v1.shipping_charges')</th>
                                    <th>@lang('stock_adjustment.total_amount')</th>
                                    <th>@lang('purchase.additional_notes')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="row align-items-center mt-2" id="stock_transfer_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="stock_transfer_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="stock_transfer_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('stock_transfer.partials.update_status_modal')

    <section id="receipt_section" class="print_section"></section>

    <!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/stock_transfer.js?v=' . $asset_v) }}"></script>
@endsection

@cannot('view_purchase_price')
    <style>
        .show_price_with_permission {
            display: none !important;
        }
    </style>
@endcannot
