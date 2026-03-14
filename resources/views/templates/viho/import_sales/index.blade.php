@extends('templates.viho.layout')
@section('title', __('lang_v1.import_sales'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('lang_v1.import_sales')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @if (session('notification') || !empty($notification))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @if(!empty($notification['msg']))
                        {{$notification['msg']}}
                    @elseif(session('notification.msg'))
                        {{ session('notification.msg') }}
                    @endif
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    {!! Form::open(['url' => action([\App\Http\Controllers\ImportSalesController::class, 'preview']), 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!}
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {!! Form::label('name', __( 'product.file_to_import' ) . ':') !!}
                                {!! Form::file('sales', ['required' => 'required', 'accept' => '.xlsx,.csv']); !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <br>
                            <button type="submit" class="btn btn-primary">@lang('lang_v1.upload_and_review')</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <a href="{{ asset('files/import_sales_template.xlsx') }}" class="btn btn-success" download><i class="fa fa-download"></i> @lang('lang_v1.download_template_file')</a>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <hr>

                    <h4>@lang('lang_v1.instructions')</h4>
                    <table class="table table-condensed">
                        <tr>
                            <td>1.</td>
                            <td>@lang('lang_v1.upload_data_in_excel_format')</td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>@lang('lang_v1.choose_location_and_group_by')</td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>@lang('lang_v1.map_columns_with_respective_sales_fields')</td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>
                                <table class="table table-striped table-slim">
                                    <tr>
                                        <th>@lang('lang_v1.importable_fields')</th>
                                        <th>@lang('lang_v1.instructions')</th>
                                    </tr>
                                    @foreach($import_fields as $key => $value)
                                        <tr>
                                            <td>{{$value['label']}}</td>
                                            <td><small>{{$value['instruction'] ?? ''}}</small></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </table>

                    <hr>

                    <h4>@lang('lang_v1.imports'):</h4>
                    <table class="table table-striped" id="imported_sales_table">
                        <thead>
                            <tr>
                                <th>@lang('lang_v1.import_batch')</th>
                                <th>@lang('lang_v1.import_time')</th>
                                <th>@lang('business.created_by')</th>
                                <th>@lang('lang_v1.invoices')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($imported_sales_array as $batch_no => $batch)
                                <tr>
                                    <td>{{$batch_no}}</td>
                                    <td>{{@format_datetime($batch['import_time'])}}</td>
                                    <td>{{$batch['created_by']}}</td>
                                    <td>
                                        {{implode(', ', $batch['invoices'])}} <br>
                                        <p class="text-muted text-right">
                                            <small>(@lang('sale.total'): {{count($batch['invoices'])}})</small>
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
