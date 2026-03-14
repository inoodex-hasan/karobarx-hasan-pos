@extends('templates.viho.layout')
@section('title', __('lang_v1.preview_imported_sales'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('lang_v1.preview_imported_sales')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['url' => action([\App\Http\Controllers\ImportSalesController::class, 'import']), 'method' => 'post', 'id' => 'import_sale_form']) !!}
                    {!! Form::hidden('file_name', $file_name); !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('group_by', __('lang_v1.group_sale_line_by') . ':*') !!} @show_tooltip(__('lang_v1.group_by_tooltip'))
                                {!! Form::select('group_by', $parsed_array[0], null, ['class' => 'form-control select2', 'required', 'placeholder' => __('messages.please_select')]); !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('location_id', __('business.business_location') . ':*') !!}
                                {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.please_select')]); !!}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h4>@lang('lang_v1.map_columns'):</h4>
                    <p>@lang('lang_v1.map_columns_help_text')</p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('lang_v1.csv_column')</th>
                                <th>@lang('lang_v1.map_to_field')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parsed_array[0] as $key => $value)
                                <tr>
                                    <td>{{$value}}</td>
                                    <td>
                                        {!! Form::select('column_map['.$key.']', $import_fields, $match_array[$key] ?? null, ['class' => 'form-control', 'placeholder' => __('messages.please_select')]); !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">@lang('messages.import')</button>
                            <a href="{{action([\App\Http\Controllers\ImportSalesController::class, 'index'])}}" class="btn btn-default">@lang('messages.back')</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
