@extends('templates.viho.layout')
@section('title', __('invoice.invoice_settings'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang( 'invoice.invoice_settings' )</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">@lang('invoice.invoice_schemes')</a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">@lang('invoice.invoice_layouts')</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="d-inline">@lang( 'invoice.all_your_invoice_schemes' )</h5>
                                    <button class="btn btn-primary btn-sm pull-right btn-modal"
                                        data-href="{{action([\App\Http\Controllers\InvoiceSchemeController::class, 'create'])}}"
                                        data-container=".invoice_modal">
                                        @lang('messages.add')
                                    </button>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row align-items-center mb-2">
                                        <div class="col-sm-12 col-md-6" id="invoice_dt_length"></div>
                                        <div class="col-sm-12 col-md-6 text-md-end" id="invoice_dt_filter"></div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="invoice_table">
                                            <thead>
                                                <tr>
                                                    <th>@lang( 'invoice.name' )</th>
                                                    <th>@lang( 'invoice.prefix' )</th>
                                                    <th>@lang( 'invoice.number_type' )</th>
                                                    <th>@lang( 'invoice.start_number' )</th>
                                                    <th>@lang( 'invoice.invoice_count' )</th>
                                                    <th>@lang( 'invoice.total_digits' )</th>
                                                    <th>@lang( 'messages.action' )</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="row align-items-center mt-2">
                                        <div class="col-sm-12 col-md-5" id="invoice_dt_info"></div>
                                        <div class="col-sm-12 col-md-7 text-md-end" id="invoice_dt_paginate"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="d-inline">@lang( 'invoice.all_your_invoice_layouts' )</h5>
                                    <a class="btn btn-primary btn-sm pull-right" href="{{action([\App\Http\Controllers\InvoiceLayoutController::class, 'create'])}}">
                                        @lang( 'messages.add' )
                                    </a>
                                </div>
                                <div class="col-md-12">
                                    @foreach( $invoice_layouts as $layout)
                                    <div class="col-md-3">
                                        <div class="icon-link">
                                            <a href="{{action([\App\Http\Controllers\InvoiceLayoutController::class, 'edit'], [$layout->id])}}">
                                                <i class="fa fa-file-alt fa-4x"></i>
                                            </a>
                                            <div class="icon-link-content">
                                                <a href="{{action([\App\Http\Controllers\InvoiceLayoutController::class, 'edit'], [$layout->id])}}">
                                                    {{ $layout->name }} <br>
                                                    @if($layout->is_default == 1)
                                                        <span class="label label-success">@lang('invoice.default')</span>
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection
