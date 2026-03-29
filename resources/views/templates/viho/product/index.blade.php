@extends('templates.viho.layout')
@section('title', __('sale.products'))

@push('styles')
    <style>
        .dataTables_length label {
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            font-weight: 400 !important;
            margin-bottom: 0 !important;
            white-space: nowrap !important;
        }

        .dataTables_length select {
            width: auto !important;
            height: 30px !important;
            padding: 0 10px !important;
            margin: 0 !important;
            font-size: 13px !important;
            border-radius: 4px !important;
            display: inline-block !important;
        }

        #product_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #product_table {
            width: 100% !important;
        }

        .dataTables_paginate {
            display: flex !important;
            justify-content: flex-end !important;
            width: 100% !important;
        }

        .paging_simple_numbers {
            margin-left: auto !important;
        }

        /* Force visibility on this specific page */
        .viho-template-active .card,
        .viho-template-active .card-body,
        .viho-template-active .tab-content,
        .viho-template-active .tab-pane,
        .viho-template-active #product_table,
        .viho-template-active #product_table_wrapper {
            background-color: #ffffff !important;
            color: #2c323f !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .viho-template-active .dataTables_wrapper {
            background-color: #ffffff !important;
            color: #2c323f !important;
            opacity: 1 !important;
            visibility: visible !important;
            overflow-x: auto !important;
        }

        .viho-template-active #product_table td,
        .viho-template-active #product_table th,
        .viho-template-active #product_table td *,
        .viho-template-active #product_table th * {
            color: #2c323f !important;
            white-space: nowrap;
        }

        .viho-template-active #product_table td {
            background-color: #ffffff !important;
        }

        /* Ensure the fade class doesn't hide the active tab */
        .viho-template-active .tab-pane.fade.show.active {
            opacity: 1 !important;
            display: block !important;
        }
    </style>
@endpush
@section('content')
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>@lang('sale.products')
                            <small
                                class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('lang_v1.manage_products')</small>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>@lang('report.filters')</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('type', __('product.product_type') . ':') !!}
                                    {!! Form::select(
        'type',
        ['single' => __('lang_v1.single'), 'variable' => __('lang_v1.variable'), 'combo' => __('lang_v1.combo')],
        null,
        [
            'class' => 'form-control select2',
            'style' => 'width:100%',
            'id' => 'product_list_filter_type',
            'placeholder' => __('lang_v1.all'),
        ],
    ) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('category_id', __('product.category') . ':') !!}
                                    {!! Form::select('category_id', $categories, null, [
        'class' => 'form-control select2',
        'style' => 'width:100%',
        'id' => 'product_list_filter_category_id',
        'placeholder' => __('lang_v1.all'),
    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('unit_id', __('product.unit') . ':') !!}
                                    {!! Form::select('unit_id', $units, null, [
        'class' => 'form-control select2',
        'style' => 'width:100%',
        'id' => 'product_list_filter_unit_id',
        'placeholder' => __('lang_v1.all'),
    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('tax_id', __('product.tax') . ':') !!}
                                    {!! Form::select('tax_id', $taxes, null, [
        'class' => 'form-control select2',
        'style' => 'width:100%',
        'id' => 'product_list_filter_tax_id',
        'placeholder' => __('lang_v1.all'),
    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('brand_id', __('product.brand') . ':') !!}
                                    {!! Form::select('brand_id', $brands, null, [
        'class' => 'form-control select2',
        'style' => 'width:100%',
        'id' => 'product_list_filter_brand_id',
        'placeholder' => __('lang_v1.all'),
    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3" id="location_filter">
                                <div class="form-group">
                                    {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
                                    {!! Form::select('location_id', $business_locations, null, [
        'class' => 'form-control select2',
        'style' => 'width:100%',
        'id' => 'location_id',
        'placeholder' => __('lang_v1.all'),
    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <br>
                                <div class="form-group">
                                    {!! Form::select(
        'active_state',
        ['active' => __('business.is_active'), 'inactive' => __('lang_v1.inactive')],
        null,
        [
            'class' => 'form-control select2',
            'style' => 'width:100%',
            'id' => 'active_state',
            'placeholder' => __('lang_v1.all'),
        ],
    ) !!}
                                </div>
                            </div>

                            <!-- include module filter -->
                            @if (!empty($pos_module_data))
                                @foreach ($pos_module_data as $key => $value)
                                    @if (!empty($value['view_path']))
                                        @includeIf($value['view_path'], ['view_data' => $value['view_data']])
                                    @endif
                                @endforeach
                            @endif

                            <div class="col-md-3">
                                <div class="form-group">
                                    <br>
                                    <label>
                                        {!! Form::checkbox('not_for_selling', 1, false, ['class' => 'input-icheck', 'id' => 'not_for_selling']) !!}
                                        <strong>@lang('lang_v1.not_for_selling')</strong>
                                    </label>
                                </div>
                            </div>
                            @if ($is_woocommerce)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <br>
                                        <label>
                                            {!! Form::checkbox('woocommerce_enabled', 1, false, ['class' => 'input-icheck', 'id' => 'woocommerce_enabled']) !!}
                                            {{ __('lang_v1.woocommerce_enabled') }}
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @can('product.view')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <ul class="nav nav-tabs nav-primary" id="product_tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="product_list_tab_link" data-bs-toggle="tab"
                                        href="#product_list_tab" role="tab" aria-controls="product_list_tab" aria-selected="true">
                                        <i class="fa fa-cubes"></i> @lang('lang_v1.all_products')
                                    </a>
                                </li>
                                @can('stock_report.view')
                                    <li class="nav-item">
                                        <a class="nav-link" id="product_stock_report_link" data-bs-toggle="tab"
                                            href="#product_stock_report" role="tab" aria-controls="product_stock_report"
                                            aria-selected="false">
                                            <i class="fa fa-hourglass-half"></i> @lang('report.stock_report')
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="product_tabs_content">
                                <div class="tab-pane fade show active" id="product_list_tab" role="tabpanel"
                                    aria-labelledby="product_list_tab_link">
                                    @can('product.create')
                                        <div class="mb-3 d-flex justify-content-end gap-2">
                                            @if ($is_admin)
                                                <a class="btn btn-info btn-sm"
                                                    href="{{ action([\App\Http\Controllers\ProductController::class, 'downloadExcel']) }}">
                                                    <i class="fa fa-download"></i> @lang('lang_v1.download_excel')
                                                </a>
                                            @endif
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ action([\App\Http\Controllers\ProductController::class, 'create']) }}">
                                                <i class="fa fa-plus"></i> @lang('messages.add')
                                            </a>
                                        </div>
                                    @endcan
                                    <div class="row align-items-center mb-2" id="product_dt_top">
                                        <div class="col-sm-12 col-md-3" id="product_dt_length"></div>
                                                <div class="col-sm-12 col-md-6 text-center" id="product_dt_buttons"></div>
                                                <div class="col-sm-12 col-md-3 text-md-end" id="product_dt_filter"></div>
                                            </div>
                                            @include('templates.viho.product.partials.product_list')
                                            <div class="row align-items-center mt-2" id="product_dt_bottom">
                                                <div class="col-sm-12 col-md-5" id="product_dt_info"></div>
                                                <div class="col-sm-12 col-md-7 text-md-end" id="product_dt_paginate"></div>
                                            </div>
                                        </div>
                                        @can('stock_report.view')
                                            <div class="tab-pane fade" id="product_stock_report" role="tabpanel"
                                                aria-labelledby="product_stock_report_link">
                                                <div class="row align-items-center mb-2" id="stock_report_dt_top">
                                                    <div class="col-sm-12 col-md-6" id="stock_report_dt_length"></div>
                                                    <div class="col-sm-12 col-md-6 text-md-end" id="stock_report_dt_filter"></div>
                                                </div>
                                                @include('templates.viho.report.partials.stock_report_table')
                                                <div class="row align-items-center mt-2" id="stock_report_dt_bottom">
                                                    <div class="col-sm-12 col-md-5" id="stock_report_dt_info"></div>
                                                    <div class="col-sm-12 col-md-7 text-md-end" id="stock_report_dt_paginate"></div>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        @endcan

            <input type="hidden" id="is_rack_enabled" value="{{ $rack_enabled }}">

            <div class="modal fade product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            </div>

            <div class="modal" id="view_product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            </div>

            <div class="modal fade" id="opening_stock_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            </div>

            @if ($is_woocommerce)
                @include('product.partials.toggle_woocommerce_sync_modal')
            @endif
            @include('product.partials.edit_product_location_modal')

@endsection

@section('javascript')
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var destroyDataTable = function (selector) {
                if (!window.jQuery || !$.fn || !$.fn.DataTable) {
                    return;
                }

                if ($.fn.DataTable.isDataTable(selector)) {
                    $(selector).DataTable().clear().destroy();
                }
            };

            // Determine AJAX URL based on template
            var products_ajax_url = (window.location && window.location.pathname && window.location.pathname.indexOf('/ai-template') === 0)
                ? '/ai-template/products'
                : '/products';

            destroyDataTable('#product_table');
            product_table = $('#product_table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader: false,
                aaSorting: [
                    [3, 'asc']
                ],
                autoWidth: false,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3 text-md-end'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-md-end'p>>",
                buttons: [
                    {
                        extend: 'csv',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-file-csv" aria-hidden="true"></i> Export CSV'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-file-excel" aria-hidden="true"></i> Export Excel'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-print" aria-hidden="true"></i> Print'
                    },
                    {
                        extend: 'colvis',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-columns" aria-hidden="true"></i> Column visibility'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-outline-primary btn-xs',
                        text: '<i class="fa fa-file-pdf" aria-hidden="true"></i> Export PDF'
                    }
                ],
                "ajax": {
                    "url": products_ajax_url,
                    "data": function (d) {
                        d.type = $('#product_list_filter_type').val();
                        d.category_id = $('#product_list_filter_category_id').val();
                        d.brand_id = $('#product_list_filter_brand_id').val();
                        d.unit_id = $('#product_list_filter_unit_id').val();
                        d.tax_id = $('#product_list_filter_tax_id').val();
                        d.active_state = $('#active_state').val();
                        d.not_for_selling = $('#not_for_selling').is(':checked');
                        d.location_id = $('#location_id').val();
                        if ($('#repair_model_id').length == 1) {
                            d.repair_model_id = $('#repair_model_id').val();
                        }

                        if ($('#woocommerce_enabled').length == 1 && $('#woocommerce_enabled').is(
                            ':checked')) {
                            d.woocommerce_enabled = 1;
                        }

                        if (typeof __datatable_ajax_callback === 'function') {
                            d = __datatable_ajax_callback(d);
                        }
                    }
                },
                columnDefs: [{
                    "targets": [0, 1, 2],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                    data: 'mass_delete'
                },
                {
                    data: 'image',
                    name: 'products.image'
                },
                {
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'product',
                    name: 'products.name'
                },
                {
                    data: 'product_locations',
                    name: 'product_locations',
                    searchable: false,
                    orderable: false
                },
                    @can('view_purchase_price')
                                                                                    {
                            data: 'purchase_price',
                            name: 'max_purchase_price',
                            searchable: false
                        },
                    @endcan
                @can('access_default_selling_price')
                                                                {
                        data: 'selling_price',
                        name: 'max_price',
                        searchable: false
                    },
                @endcan{
                    data: 'current_stock',
                    searchable: false
                },
                {
                    data: 'type',
                    name: 'products.type'
                },
                {
                    data: 'category',
                    name: 'c1.name'
                },
                {
                    data: 'brand',
                    name: 'brands.name'
                },
                {
                    data: 'tax',
                    name: 'tax_rates.name',
                    searchable: false
                },
                {
                    data: 'sku',
                    name: 'products.sku'
                },
                {
                    data: 'product_custom_field1',
                    name: 'products.product_custom_field1',
                    visible: $('#cf_1').text().length > 0
                },
                {
                    data: 'product_custom_field2',
                    name: 'products.product_custom_field2',
                    visible: $('#cf_2').text().length > 0
                },
                {
                    data: 'product_custom_field3',
                    name: 'products.product_custom_field3',
                    visible: $('#cf_3').text().length > 0
                },
                {
                    data: 'product_custom_field4',
                    name: 'products.product_custom_field4',
                    visible: $('#cf_4').text().length > 0
                },
                {
                    data: 'product_custom_field5',
                    name: 'products.product_custom_field5',
                    visible: $('#cf_5').text().length > 0
                },
                {
                    data: 'product_custom_field6',
                    name: 'products.product_custom_field6',
                    visible: $('#cf_6').text().length > 0
                },
                {
                    data: 'product_custom_field7',
                    name: 'products.product_custom_field7',
                    visible: $('#cf_7').text().length > 0
                },
                ],
                createdRow: function (row, data, dataIndex) {
                    if ($('input#is_rack_enabled').val() == 1) {
                        var target_col = 0;
                        @can('product.delete')
                            target_col = 1;
                        @endcan
                        $(row).find('td:eq(' + target_col + ') div').prepend(
                            '<i style="margin:auto;" class="fa fa-plus-circle text-success cursor-pointer no-print rack-details" title="' +
                            LANG.details + '"></i>&nbsp;&nbsp;');
                    }
                    $(row).find('td:eq(0)').attr('class', 'selectable_td');
                },
                fnDrawCallback: function (oSettings) {
                    __currency_convert_recursively($('#product_table'));
                },
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#product_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#product_dt_length').empty().append($length);
                        if ($buttons.length) $('#product_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#product_dt_filter').empty().append($filter);
                        if ($info.length) $('#product_dt_info').empty().append($info);
                        if ($paginate.length) $('#product_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                }
            });

            window.product_table = product_table;

            // Array to track the ids of the details displayed rows
            var detailRows = [];

            $('#product_table tbody')
                .off('click.vihoProducts', 'tr i.rack-details')
                .on('click.vihoProducts', 'tr i.rack-details', function () {
                    var i = $(this);
                    var tr = $(this).closest('tr');
                    var row = product_table.row(tr);
                    var idx = $.inArray(tr.attr('id'), detailRows);

                    if (row.child.isShown()) {
                        i.addClass('fa-plus-circle text-success');
                        i.removeClass('fa-minus-circle text-danger');

                        row.child.hide();

                        // Remove from the 'open' array
                        detailRows.splice(idx, 1);
                    } else {
                        i.removeClass('fa-plus-circle text-success');
                        i.addClass('fa-minus-circle text-danger');

                        row.child(get_product_details(row.data())).show();

                        // Add to the 'open' array
                        if (idx === -1) {
                            detailRows.push(tr.attr('id'));
                        }
                    }
                });

            $('#opening_stock_modal')
                .off('hidden.bs.modal.vihoProducts')
                .on('hidden.bs.modal.vihoProducts', function (e) {
                    product_table.ajax.reload();
                });

            $('table#product_table tbody')
                .off('click.vihoProducts', 'a.delete-product')
                .on('click.vihoProducts', 'a.delete-product', function (e) {
                    e.preventDefault();
                    swal({
                        title: LANG.sure,
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            var href = $(this).attr('href');
                            $.ajax({
                                method: "DELETE",
                                url: href,
                                dataType: "json",
                                success: function (result) {
                                    if (result.success == true) {
                                        toastr.success(result.msg);
                                        product_table.ajax.reload();
                                    } else {
                                        toastr.error(result.msg);
                                    }
                                }
                            });
                        }
                    });
                });

            $(document)
                .off('click.vihoProducts', '#delete-selected')
                .on('click.vihoProducts', '#delete-selected', function (e) {
                    e.preventDefault();
                    var selected_rows = getSelectedRows();

                    if (selected_rows.length > 0) {
                        $('input#selected_rows').val(selected_rows);
                        swal({
                            title: LANG.sure,
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                $('form#mass_delete_form').submit();
                            }
                        });
                    } else {
                        $('input#selected_rows').val('');
                        swal('@lang('lang_v1.no_row_selected')');
                    }
                });

            $(document)
                .off('click.vihoProducts', '#deactivate-selected')
                .on('click.vihoProducts', '#deactivate-selected', function (e) {
                    e.preventDefault();
                    var selected_rows = getSelectedRows();

                    if (selected_rows.length > 0) {
                        $('input#selected_products').val(selected_rows);
                        swal({
                            title: LANG.sure,
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                var form = $('form#mass_deactivate_form')

                                var data = form.serialize();
                                $.ajax({
                                    method: form.attr('method'),
                                    url: form.attr('action'),
                                    dataType: 'json',
                                    data: data,
                                    success: function (result) {
                                        if (result.success == true) {
                                            toastr.success(result.msg);
                                            product_table.ajax.reload();
                                            form
                                                .find('#selected_products')
                                                .val('');
                                        } else {
                                            toastr.error(result.msg);
                                        }
                                    },
                                });
                            }
                        });
                    } else {
                        $('input#selected_products').val('');
                        swal('@lang('lang_v1.no_row_selected')');
                    }
                })

            $(document)
                .off('click.vihoProducts', '#edit-selected')
                .on('click.vihoProducts', '#edit-selected', function (e) {
                    e.preventDefault();
                    var selected_rows = getSelectedRows();

                    if (selected_rows.length > 0) {
                        $('input#selected_products_for_edit').val(selected_rows);
                        $('form#bulk_edit_form').submit();
                    } else {
                        $('input#selected_products').val('');
                        swal('@lang('lang_v1.no_row_selected')');
                    }
                })

            $('table#product_table tbody')
                .off('click.vihoProducts', 'a.activate-product')
                .on('click.vihoProducts', 'a.activate-product', function (e) {
                    e.preventDefault();
                    var href = $(this).attr('href');
                    $.ajax({
                        method: "get",
                        url: href,
                        dataType: "json",
                        success: function (result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                product_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });

            $(document)
                .off('change.vihoProducts', '#product_list_filter_type, #product_list_filter_category_id, #product_list_filter_brand_id, #product_list_filter_unit_id, #product_list_filter_tax_id, #location_id, #active_state, #repair_model_id')
                .on('change.vihoProducts', '#product_list_filter_type, #product_list_filter_category_id, #product_list_filter_brand_id, #product_list_filter_unit_id, #product_list_filter_tax_id, #location_id, #active_state, #repair_model_id', function () {
                    if ($("#product_list_tab").hasClass('active')) {
                        product_table.ajax.reload();
                    }

                    if ($("#product_stock_report").hasClass('active')) {
                        stock_report_table.ajax.reload();
                    }
                });

            $(document)
                .off('ifChanged.vihoProducts', '#not_for_selling, #woocommerce_enabled')
                .on('ifChanged.vihoProducts', '#not_for_selling, #woocommerce_enabled', function () {
                    if ($("#product_list_tab").hasClass('active')) {
                        product_table.ajax.reload();
                    }

                    if ($("#product_stock_report").hasClass('active')) {
                        stock_report_table.ajax.reload();
                    }
                });

            $('#product_location').select2({
                dropdownParent: $('#product_location').closest('.modal')
            });

            @if ($is_woocommerce)
                $(document)
                    .off('click.vihoProducts', '.toggle_woocomerce_sync')
                    .on('click.vihoProducts', '.toggle_woocomerce_sync', function (e) {
                        e.preventDefault();
                        var selected_rows = getSelectedRows();
                        if (selected_rows.length > 0) {
                            $('#woocommerce_sync_modal').modal('show');
                            $("input#woocommerce_products_sync").val(selected_rows);
                        } else {
                            $('input#selected_products').val('');
                            swal('@lang('lang_v1.no_row_selected')');
                        }
                    });

                $(document)
                    .off('submit.vihoProducts', 'form#toggle_woocommerce_sync_form')
                    .on('submit.vihoProducts', 'form#toggle_woocommerce_sync_form', function (e) {
                        e.preventDefault();
                        var url = $('form#toggle_woocommerce_sync_form').attr('action');
                        var method = $('form#toggle_woocommerce_sync_form').attr('method');
                        var data = $('form#toggle_woocommerce_sync_form').serialize();
                        var ladda = Ladda.create(document.querySelector('.ladda-button'));
                        ladda.start();
                        $.ajax({
                            method: method,
                            dataType: "json",
                            url: url,
                            data: data,
                            success: function (result) {
                                ladda.stop();
                                if (result.success) {
                                    $("input#woocommerce_products_sync").val('');
                                    $('#woocommerce_sync_modal').modal('hide');
                                    toastr.success(result.msg);
                                    product_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    });
            @endif
                            });

        $(document)
            .off('shown.bs.modal.vihoProducts', 'div.view_product_modal, div.view_modal, #view_product_modal')
            .on('shown.bs.modal.vihoProducts', 'div.view_product_modal, div.view_modal, #view_product_modal',
                function () {
                    var div = $(this).find('#view_product_stock_details');
                    if (div.length) {
                        $.ajax({
                            url: "{{ action([\App\Http\Controllers\ReportController::class, 'getStockReport']) }}" +
                                '?for=view_product&product_id=' + div.data('product_id'),
                            dataType: 'html',
                            success: function (result) {
                                div.html(result);
                                if (typeof __currency_convert_recursively === 'function') {
                                    __currency_convert_recursively(div);
                                }
                            },
                        });
                    }
                    if (typeof __currency_convert_recursively === 'function') {
                        __currency_convert_recursively($(this));
                    }
                });

        $(document).on('click', 'a.view-product', function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                dataType: 'html',
                success: function (result) {
                    $('#view_product_modal')
                        .html(result)
                        .modal('show');
                    if (typeof __currency_convert_recursively === 'function') {
                        __currency_convert_recursively($('#view_product_modal'));
                    }
                },
            });
        });

        var data_table_initailized = false;
        $('a[data-bs-toggle="tab"]')
            .off('shown.bs.tab.vihoProducts')
            .on('shown.bs.tab.vihoProducts', function (e) {
                if ($(e.target).attr('href') == '#product_stock_report') {
                    if (!data_table_initailized) {
                        //Stock report table
                        var stock_report_cols = [{
                            data: 'action',
                            name: 'action',
                            searchable: false,
                            orderable: false
                        },
                        {
                            data: 'sku',
                            name: 'variations.sub_sku'
                        },
                        {
                            data: 'product',
                            name: 'p.name'
                        },
                        {
                            data: 'variation',
                            name: 'variation'
                        },
                        {
                            data: 'category_name',
                            name: 'c.name'
                        },
                        {
                            data: 'location_name',
                            name: 'l.name'
                        },
                        {
                            data: 'unit_price',
                            name: 'variations.sell_price_inc_tax'
                        },
                        {
                            data: 'stock',
                            name: 'stock',
                            searchable: false
                        },
                        ];
                        if ($('th.stock_price').length) {
                            stock_report_cols.push({
                                data: 'stock_price',
                                name: 'stock_price',
                                searchable: false
                            });
                            stock_report_cols.push({
                                data: 'stock_value_by_sale_price',
                                name: 'stock_value_by_sale_price',
                                searchable: false,
                                orderable: false
                            });
                            stock_report_cols.push({
                                data: 'potential_profit',
                                name: 'potential_profit',
                                searchable: false,
                                orderable: false
                            });
                        }

                        stock_report_cols.push({
                            data: 'total_sold',
                            name: 'total_sold',
                            searchable: false
                        });
                        stock_report_cols.push({
                            data: 'total_transfered',
                            name: 'total_transfered',
                            searchable: false
                        });
                        stock_report_cols.push({
                            data: 'total_adjusted',
                            name: 'total_adjusted',
                            searchable: false
                        });
                        stock_report_cols.push({
                            data: 'product_custom_field1',
                            name: 'p.product_custom_field1'
                        });
                        stock_report_cols.push({
                            data: 'product_custom_field2',
                            name: 'p.product_custom_field2'
                        });
                        stock_report_cols.push({
                            data: 'product_custom_field3',
                            name: 'p.product_custom_field3'
                        });
                        stock_report_cols.push({
                            data: 'product_custom_field4',
                            name: 'p.product_custom_field4'
                        });

                        if ($('th.current_stock_mfg').length) {
                            stock_report_cols.push({
                                data: 'total_mfg_stock',
                                name: 'total_mfg_stock',
                                searchable: false
                            });
                        }
                        destroyDataTable('#stock_report_table');
                        stock_report_table = $('#stock_report_table').DataTable({
                            order: [
                                [1, 'asc']
                            ],
                            processing: true,
                            serverSide: true,
                            fixedHeader: false,
                            dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-8 text-center'B><'col-sm-12 col-md-2 text-md-end'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-md-end'p>>",
                            ajax: {
                                url: products_ajax_url === '/ai-template/products' ? '/ai-template/reports/stock-report' : '/reports/stock-report',
                                data: function (d) {
                                    d.location_id = $('#location_id').val();
                                    d.category_id = $('#product_list_filter_category_id').val();
                                    d.brand_id = $('#product_list_filter_brand_id').val();
                                    d.unit_id = $('#product_list_filter_unit_id').val();
                                    d.type = $('#product_list_filter_type').val();
                                    d.active_state = $('#active_state').val();
                                    d.not_for_selling = $('#not_for_selling').is(':checked');
                                    if ($('#repair_model_id').length == 1) {
                                        d.repair_model_id = $('#repair_model_id').val();
                                    }
                                }
                            },
                            columns: stock_report_cols,
                            fnDrawCallback: function (oSettings) {
                                __currency_convert_recursively($('#stock_report_table'));
                            },
                            "footerCallback": function (row, data, start, end, display) {
                                var footer_total_stock = 0;
                                var footer_total_sold = 0;
                                var footer_total_transfered = 0;
                                var total_adjusted = 0;
                                var total_stock_price = 0;
                                var footer_stock_value_by_sale_price = 0;
                                var total_potential_profit = 0;
                                var footer_total_mfg_stock = 0;
                                for (var r in data) {
                                    footer_total_stock += $(data[r].stock).data('orig-value') ?
                                        parseFloat($(data[r].stock).data('orig-value')) : 0;

                                    footer_total_sold += $(data[r].total_sold).data('orig-value') ?
                                        parseFloat($(data[r].total_sold).data('orig-value')) : 0;

                                    footer_total_transfered += $(data[r].total_transfered).data(
                                        'orig-value') ?
                                        parseFloat($(data[r].total_transfered).data('orig-value')) : 0;

                                    total_adjusted += $(data[r].total_adjusted).data('orig-value') ?
                                        parseFloat($(data[r].total_adjusted).data('orig-value')) : 0;

                                    total_stock_price += $(data[r].stock_price).data('orig-value') ?
                                        parseFloat($(data[r].stock_price).data('orig-value')) : 0;

                                    footer_stock_value_by_sale_price += $(data[r].stock_value_by_sale_price)
                                        .data('orig-value') ?
                                        parseFloat($(data[r].stock_value_by_sale_price).data(
                                            'orig-value')) : 0;

                                    total_potential_profit += $(data[r].potential_profit).data(
                                        'orig-value') ?
                                        parseFloat($(data[r].potential_profit).data('orig-value')) : 0;

                                    footer_total_mfg_stock += $(data[r].total_mfg_stock).data(
                                        'orig-value') ?
                                        parseFloat($(data[r].total_mfg_stock).data('orig-value')) : 0;

                                }

                                $('.footer_total_stock').html(__currency_trans_from_en(footer_total_stock,
                                    false));
                                $('.footer_total_stock_price').html(__currency_trans_from_en(
                                    total_stock_price));
                                $('.footer_total_sold').html(__currency_trans_from_en(footer_total_sold,
                                    false));
                                $('.footer_total_transfered').html(__currency_trans_from_en(
                                    footer_total_transfered, false));
                                $('.footer_total_adjusted').html(__currency_trans_from_en(total_adjusted,
                                    false));
                                $('.footer_stock_value_by_sale_price').html(__currency_trans_from_en(
                                    footer_stock_value_by_sale_price));
                                $('.footer_potential_profit').html(__currency_trans_from_en(
                                    total_potential_profit));
                                if ($('th.current_stock_mfg').length) {
                                    $('.footer_total_mfg_stock').html(__currency_trans_from_en(
                                        footer_total_mfg_stock, false));
                                }
                            }
                        });
                        data_table_initailized = true;
                    } else {
                        stock_report_table.ajax.reload();
                    }
                }
            });
    </script>
@endsection

@cannot('view_purchase_price')
<style>
    .show_price_with_permission {
        display: none !important;
    }
</style>
@endcannot