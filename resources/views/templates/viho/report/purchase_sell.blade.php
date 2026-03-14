@extends('templates.viho.layout')
@section('title', __('report.purchase_sell'))

@push('styles')
    <style>
        .print_section { display: none; }
        @media print {
            .print_section { display: block !important; }
            .no-print { display: none !important; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('report.purchase_sell')
                        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('report.purchase_sell_msg')</small>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="print_section">
                        <h2>{{session()->get('business.name')}} - @lang('report.purchase_sell')</h2>
                    </div>
                    
                    <div class="row no-print">
                        <div class="col-md-3 col-md-offset-7 col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon bg-light-blue"><i class="fa fa-map-marker"></i></span>
                                 <select class="form-control select2" id="purchase_sell_location_filter">
                                    @foreach($business_locations as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group pull-right">
                                <div class="input-group">
                                  <button type="button" class="btn btn-primary btn-sm" id="purchase_sell_date_filter">
                                    <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                                  </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="no-print">

                    <div class="row">
                        <div class="col-md-6">
                            @component('components.widget', ['title' => __('lang_v1.purchase')])
                                <table class="table table-striped">
                                    <tr>
                                        <th>@lang('report.total_purchase'):<br><small class="text-muted">(@lang('product.exc_of_tax'), @lang('sale.discount'))</small></th>
                                        <td><span class="display_currency total_purchase" data-currency_symbol="true"></span></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('report.purchase_inc_tax'):<br><small class="text-muted">(@lang('product.inc_of_tax'))</small></th>
                                        <td><span class="display_currency purchase_inc_tax" data-currency_symbol="true"></span></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('report.purchase_due'): @show_tooltip(__('tooltip.purchase_due'))</th>
                                        <td><span class="display_currency purchase_due" data-currency_symbol="true"></span></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('lang_v1.total_purchase_return_inc_tax'):<br><small class="text-muted">(@lang('product.inc_of_tax'))</small></th>
                                        <td><span class="display_currency purchase_return_inc_tax" data-currency_symbol="true"></span></td>
                                    </tr>
                                </table>
                            @endcomponent
                        </div>
                        <div class="col-md-6">
                            @component('components.widget', ['title' => __('sale.sells')])
                                <table class="table table-striped">
                                    <tr>
                                        <th>@lang('report.total_sell'):<br><small class="text-muted">(@lang('product.exc_of_tax'), @lang('sale.discount'))</small></th>
                                        <td><span class="display_currency total_sell" data-currency_symbol="true"></span></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('report.sell_inc_tax'):<br><small class="text-muted">(@lang('product.inc_of_tax'))</small></th>
                                        <td><span class="display_currency sell_inc_tax" data-currency_symbol="true"></span></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('lang_v1.total_sell_return_inc_tax'):<br><small class="text-muted">(@lang('product.inc_of_tax'))</small></th>
                                        <td><span class="display_currency total_sell_return" data-currency_symbol="true"></span></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('report.sell_due'): @show_tooltip(__('tooltip.sell_due'))</th>
                                        <td><span class="display_currency sell_due" data-currency_symbol="true"></span></td>
                                    </tr>
                                </table>
                            @endcomponent
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @component('components.widget', ['title' => __('lang_v1.overall') . ' ((' . __('business.sale') . ' - ' . __('lang_v1.sell_return') . ') - (' . __('lang_v1.purchase') . ' - ' . __('lang_v1.purchase_return') . '))'])
                                <table class="table table-striped">
                                    <tr>
                                        <th>@lang('report.sell_minus_purchase'):</th>
                                        <td><span class="display_currency sell_minus_purchase" data-currency_symbol="true"></span></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('report.difference_due'):</th>
                                        <td><span class="display_currency difference_due" data-currency_symbol="true"></span></td>
                                    </tr>
                                </table>
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
