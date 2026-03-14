@extends('templates.viho.layout')
@section('title', __('tax_rate.tax_rates'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang( 'tax_rate.tax_rates' )</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang( 'tax_rate.all_your_tax_rates' )</h5>
                    @can('tax_rate.create')
                        <div>
                            <button class="btn btn-primary btn-sm btn-modal"
                                data-href="{{action([\App\Http\Controllers\TaxRateController::class, 'create'])}}"
                                data-container=".tax_rate_modal">
                                @lang('messages.add')
                            </button>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    @can('tax_rate.view')
                        <div class="row align-items-center mb-2" id="tax_rates_dt_top">
                            <div class="col-sm-12 col-md-6" id="tax_rates_dt_length"></div>
                            <div class="col-sm-12 col-md-6 text-md-end" id="tax_rates_dt_filter"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tax_rates_table">
                                <thead>
                                    <tr>
                                        <th>@lang( 'tax_rate.name' )</th>
                                        <th>@lang( 'tax_rate.rate' )</th>
                                        <th>@lang( 'messages.action' )</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row align-items-center mt-2" id="tax_rates_dt_bottom">
                            <div class="col-sm-12 col-md-5" id="tax_rates_dt_info"></div>
                            <div class="col-sm-12 col-md-7 text-md-end" id="tax_rates_dt_paginate"></div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade tax_rate_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection
