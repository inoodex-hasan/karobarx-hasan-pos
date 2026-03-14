@extends('templates.viho.layout')
@section('title', __('report.tax_report'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('report.tax_report')
                        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('report.tax_report_msg')</small>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('tax_report_location_id', __('purchase.business_location') . ':') !!}
                        {!! Form::select('tax_report_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('tax_report_contact_id', __('report.contact') . ':') !!}
                        {!! Form::select('tax_report_contact_id', $contact_dropdown, null , ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'tax_report_contact_id', 'placeholder' => __('lang_v1.all')]); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('tax_report_date_range', __('report.date_range') . ':') !!}
                        {!! Form::text('tax_report_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'tax_report_date_range', 'readonly']); !!}
                    </div>
                </div>
            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            @component('components.widget')
                @slot('title')
                    {{ __('lang_v1.tax_overall') }} @show_tooltip(__('tooltip.tax_overall'))
                @endslot
                <h3 class="text-muted">
                    {{ __('lang_v1.output_tax_minus_input_tax') }}:
                    <span class="tax_diff">
                        <i class="fas fa-sync fa-spin fa-fw"></i>
                    </span>
                </h3>
            @endcomponent
        </div>
    </div>
    <div class="row no-print">
        <div class="col-sm-12">
            <button class="btn btn-primary pull-right mb-2" aria-label="Print" onclick="window.print();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#input_tax_tab" data-toggle="tab" aria-expanded="true"><i class="fa fas fa-arrow-circle-down" aria-hidden="true"></i> @lang('report.input_tax') ( @lang('lang_v1.purchase') )</a>
                    </li>

                    <li>
                        <a href="#output_tax_tab" data-toggle="tab" aria-expanded="true"><i class="fa fas fa-arrow-circle-up" aria-hidden="true"></i> @lang('report.output_tax')  ( @lang('sale.sells') )</a>
                    </li>

                    <li>
                        <a href="#expense_tax_tab" data-toggle="tab" aria-expanded="true"><i class="fa fas fa-minus-circle" aria-hidden="true"></i> @lang('lang_v1.expense_tax')</a>
                    </li>
                    @if(!empty($tax_report_tabs))
                        @foreach($tax_report_tabs as $key => $tabs)
                            @foreach ($tabs as $index => $value)
                                @if(!empty($value['tab_menu_path']))
                                    @php
                                        $tab_data = !empty($value['tab_data']) ? $value['tab_data'] : [];
                                    @endphp
                                    @include($value['tab_menu_path'], $tab_data)
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="input_tax_tab">
                        <table class="table table-bordered table-striped" id="input_tax_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('purchase.supplier')</th>
                                    <th>@lang('contact.tax_no')</th>
                                    <th>@lang('sale.total_amount')</th>
                                    <th>@lang('lang_v1.payment_method')</th>
                                    <th>@lang('receipt.discount')</th>
                                    @foreach($taxes as $tax)
                                        <th>{{$tax['name']}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 text-center footer-total">
                                    <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                                    <td><span class="display_currency" id="sell_total" data-currency_symbol="true"></span></td>
                                    <td class="input_payment_method_count"></td>
                                    <td>&nbsp;</td>
                                    @foreach($taxes as $tax)
                                        <td><span class="display_currency" id="total_input_{{$tax['id']}}" data-currency_symbol="true"></span></td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane" id="output_tax_tab">
                        <table class="table table-bordered table-striped" id="output_tax_table" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('sale.invoice_no')</th>
                                    <th>@lang('contact.customer')</th>
                                    <th>@lang('contact.tax_no')</th>
                                    <th>@lang('sale.total_amount')</th>
                                    <th>@lang('lang_v1.payment_method')</th>
                                    <th>@lang('receipt.discount')</th>
                                    @foreach($taxes as $tax)
                                        <th>{{$tax['name']}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 text-center footer-total">
                                    <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                                    <td><span class="display_currency" id="purchase_total" data-currency_symbol="true"></span></td>
                                    <td class="output_payment_method_count"></td>
                                    <td>&nbsp;</td>
                                    @foreach($taxes as $tax)
                                        <td><span class="display_currency" id="total_output_{{$tax['id']}}" data-currency_symbol="true"></span></td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane" id="expense_tax_tab">
                        <table class="table table-bordered table-striped" id="expense_tax_table" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('contact.tax_no')</th>
                                    <th>@lang('sale.total_amount')</th>
                                    <th>@lang('lang_v1.payment_method')</th>
                                    @foreach($taxes as $tax)
                                        <th>{{$tax['name']}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 text-center footer-total">
                                    <td colspan="3"><strong>@lang('sale.total'):</strong></td>
                                    <td><span class="display_currency" id="expense_total" data-currency_symbol="true"></span></td>
                                    <td class="expense_payment_method_count"></td>
                                    @foreach($taxes as $tax)
                                        <td><span class="display_currency" id="total_expense_{{$tax['id']}}" data-currency_symbol="true"></span></td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @if(!empty($tax_report_tabs))
                        @foreach($tax_report_tabs as $key => $tabs)
                            @foreach ($tabs as $index => $value)
                                @if(!empty($value['tab_content_path']))
                                    @php
                                        $tab_data = !empty($value['tab_data']) ? $value['tab_data'] : [];
                                    @endphp
                                    @include($value['tab_content_path'], $tab_data)
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
