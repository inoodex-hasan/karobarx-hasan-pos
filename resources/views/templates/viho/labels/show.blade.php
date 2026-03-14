@extends('templates.viho.layout')
@section('title', __('barcode.print_labels'))

@section('content')
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('barcode.print_labels') @show_tooltip(__('tooltip.print_label'))</h1>
    </section>

    <section class="content no-print">
        {!! Form::open(['url' => '#', 'method' => 'post', 'id' => 'preview_setting_form', 'onsubmit' => 'return false']) !!}
        @component('components.widget', ['class' => 'box-primary', 'title' => __('product.add_product_for_labels')])
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-search"></i>
                            </span>
                            {!! Form::text('search_product', null, ['class' => 'form-control', 'id' => 'search_product_for_label', 'placeholder' => __('lang_v1.enter_product_name_to_print_labels'), 'autofocus']); !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10 col-sm-offset-2">
                    <table class="table table-bordered table-striped table-condensed" id="product_table">
                        <thead>
                            <tr>
                                <th>@lang( 'barcode.products' )</th>
                                <th>@lang( 'barcode.no_of_labels' )</th>
                                @if(request()->session()->get('business.enable_lot_number') == 1)
                                    <th>@lang( 'lang_v1.lot_number' )</th>
                                @endif
                                @if(request()->session()->get('business.enable_product_expiry') == 1)
                                    <th>@lang( 'product.exp_date' )</th>
                                @endif
                                <th>@lang('lang_v1.packing_date')</th>
                                <th>@lang('lang_v1.selling_price_group')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('templates.viho.labels.partials.show_table_rows', ['index' => 0])
                        </tbody>
                    </table>
                </div>
            </div>
        @endcomponent

        @component('components.widget', ['class' => 'box-primary', 'title' => __( 'barcode.info_in_labels' )])
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered label-print-settings-table">
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('print[name]', 1, true) !!} <b>@lang( 'barcode.print_name' )</b>
                                    </label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-addon"><b>@lang( 'lang_v1.size' )</b></div>
                                    <input type="text" class="form-control"
                                        name="print[name_size]"
                                        value="15">
                                </div>
                            </td>

                            <td>
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('print[variations]', 1, true) !!} <b>@lang( 'barcode.print_variations' )</b>
                                    </label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-addon"><b>@lang( 'lang_v1.size' )</b></div>
                                    <input type="text" class="form-control"
                                        name="print[variations_size]"
                                        value="17">
                                </div>
                            </td>

                            <td>
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('print[price]', 1, true, ['id' => 'is_show_price']) !!} <b>@lang( 'barcode.print_price' )</b>
                                    </label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-addon"><b>@lang( 'lang_v1.size' )</b></div>
                                    <input type="text" class="form-control"
                                        name="print[price_size]"
                                        value="17">
                                </div>
                            </td>

                            <td>
                                <div class="" id="price_type_div">
                                    <div class="form-group">
                                        {!! Form::label('print[price_type]', @trans( 'barcode.show_price' ) . ':') !!}
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-info"></i>
                                            </span>
                                            {!! Form::select('print[price_type]', ['inclusive' => __('product.inc_of_tax'), 'exclusive' => __('product.exc_of_tax')], 'inclusive', ['class' => 'form-control']); !!}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" checked name="print[business_name]" value="1"> <b>@lang( 'barcode.print_business_name' )</b>
                                    </label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-addon"><b>@lang( 'lang_v1.size' )</b></div>
                                    <input type="text" class="form-control" 
                                        name="print[business_name_size]" 
                                        value="20">
                                </div>
                            </td>

                            <td>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" checked name="print[packing_date]" value="1"> <b>@lang( 'lang_v1.print_packing_date' )</b>
                                    </label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-addon"><b>@lang( 'lang_v1.size' )</b></div>
                                    <input type="text" class="form-control" 
                                        name="print[packing_date_size]" 
                                        value="12">
                                </div>
                            </td>

                            <td>
                                @if(request()->session()->get('business.enable_lot_number') == 1)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="print[lot_number]" value="1"> <b>@lang( 'lang_v1.print_lot_number' )</b>
                                        </label>
                                    </div>

                                    <div class="input-group">
                                        <div class="input-group-addon"><b>@lang( 'lang_v1.size' )</b></div>
                                        <input type="text" class="form-control" 
                                            name="print[lot_number_size]" 
                                            value="12">
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if(request()->session()->get('business.enable_product_expiry') == 1)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="print[exp_date]" value="1"> <b>@lang( 'lang_v1.print_exp_date' )</b>
                                        </label>
                                    </div>

                                    <div class="input-group">
                                        <div class="input-group-addon"><b>@lang( 'lang_v1.size' )</b></div>
                                        <input type="text" class="form-control" 
                                            name="print[exp_date_size]" 
                                            value="12">
                                    </div>
                                @endif
                            </td>						
                        </tr>
                        <tr>
                            @php
                                $c = 0;
                                $custom_labels = json_decode(session('business.custom_labels'), true);
                                $product_custom_fields = !empty($custom_labels['product']) ? $custom_labels['product'] : [];
                                $product_cf_details = !empty($custom_labels['product_cf_details']) ? $custom_labels['product_cf_details'] : [];
                            @endphp
                            @foreach($product_custom_fields as $index => $cf)
                                @if(!empty($cf))
                                    @php
                                        $field_name = 'product_custom_field' . $loop->iteration;
                                        $cf_type = !empty($product_cf_details[$loop->iteration]['type']) ? $product_cf_details[$loop->iteration]['type'] : 'text';
                                        $dropdown = !empty($product_cf_details[$loop->iteration]['dropdown_options']) ? explode(PHP_EOL, $product_cf_details[$loop->iteration]['dropdown_options']) : [];
                                        $c++;
                                    @endphp
                                    <td>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="print[{{ $field_name }}]" value="1"> <b>{{ $cf }}</b>
                                            </label>
                                        </div>

                                        <div class="input-group">
                                            <div class="input-group-addon"><b>@lang( 'lang_v1.size' )</b></div>
                                            <input type="text" class="form-control" 
                                                name="print[{{ $field_name }}_size]" 
                                                value="12">
                                        </div>
                                    </td>
                                    @if ($c % 4 == 0)
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    </table>
                </div>

                <div class="col-sm-12">
                    <hr/>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('price_type', @trans( 'barcode.barcode_setting' ) . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-cog"></i>
                            </span>
                            {!! Form::select('barcode_setting', $barcode_settings, !empty($default) ? $default->id : null, ['class' => 'form-control']); !!}
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                
                <div class="col-sm-12 text-center">
                    <button type="button" id="labels_preview" class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-lg tw-text-white">@lang( 'barcode.preview' )</button>
                </div>
            </div>
        @endcomponent
        {!! Form::close() !!}

        <div class="col-sm-8 hide display_label_div">
            <h3 class="box-title">@lang( 'barcode.preview' )</h3>
            <button type="button" class="col-sm-offset-2 btn btn-success btn-block" id="print_label">Print</button>
        </div>
        <div class="clearfix"></div>
    </section>

    <div id="preview_box"></div>
@stop

@push('styles')
    <style>
        /* Viho uses Bootstrap 5 styles; this page still contains Bootstrap 3 `.checkbox` + `.input-group-addon` markup.
           Prevent the addon (label "Size") from shrinking/clipping and keep checkbox+label aligned. */
        .label-print-settings-table .input-group {
            display: flex;
            flex-wrap: nowrap;
            align-items: stretch;
            width: 100%;
        }

        .label-print-settings-table .input-group-addon {
            flex: 0 0 auto;
            min-width: 72px;
            justify-content: center;
            overflow: visible;
        }

        .label-print-settings-table .input-group > .form-control,
        .label-print-settings-table .input-group > .form-select {
            flex: 1 1 auto;
            min-width: 0;
        }

        .label-print-settings-table td {
            vertical-align: top;
        }

        .label-print-settings-table .checkbox {
            margin: 0 0 .5rem 0;
        }

        .label-print-settings-table .checkbox label {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            margin: 0;
            padding-left: 0 !important;
        }

        /* Viho theme draws custom checkbox UI via label pseudo-elements; disable to avoid "double" checkboxes. */
        .label-print-settings-table .checkbox label::before,
        .label-print-settings-table .checkbox label::after {
            content: none !important;
            display: none !important;
        }

        .label-print-settings-table .checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin: 0;
            flex: 0 0 auto;
            accent-color: #7366ff;
            position: static !important;
            opacity: 1 !important;
            display: inline-block !important;
        }

        /* Preview section styles */
        .display_label_div {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .display_label_div h3 {
            margin-bottom: 15px;
            font-size: 1.25rem;
            font-weight: 600;
        }

        #preview_box {
            margin-top: 20px;
        }

        /* Hide non-print section when printing */
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush

@section('javascript')
    <script src="{{ asset('js/labels.js?v=' . $asset_v) }}"></script>
@endsection
