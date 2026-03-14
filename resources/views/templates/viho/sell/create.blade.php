@extends('templates.viho.layout')
@section('title', __('sale.add_sale'))

@push('styles')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('sale.add_sale')</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @include('sell.partials.sell_form')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    @include('sell.partials.sell_js')
@endsection