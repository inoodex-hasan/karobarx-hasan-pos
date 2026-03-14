@extends('templates.viho.layout')
@section('title', __('barcode.barcodes'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('barcode.barcodes')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('barcode.all_your_barcode')</h5>
                    <div>
                        <a class="btn btn-primary btn-sm" href="{{action([\App\Http\Controllers\BarcodeController::class, 'create'])}}">
                            @lang('barcode.add_new_setting')
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="barcode_table">
                            <thead>
                                <tr>
                                    <th>@lang('barcode.setting_name')</th>
                                    <th>@lang('barcode.setting_description')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
