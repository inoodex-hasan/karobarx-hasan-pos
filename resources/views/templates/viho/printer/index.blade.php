@extends('templates.viho.layout')
@section('title', __('printer.printers'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('printer.printers')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('printer.all_your_printer')</h5>
                    <div>
                        <a class="btn btn-primary btn-sm" href="{{action([\App\Http\Controllers\PrinterController::class, 'create'])}}">
                            @lang('printer.add_printer')
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="printer_table">
                            <thead>
                                <tr>
                                    <th>@lang('printer.name')</th>
                                    <th>@lang('printer.connection_type')</th>
                                    <th>@lang('printer.character_per_line')</th>
                                    <th>@lang('printer.profile')</th>
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
