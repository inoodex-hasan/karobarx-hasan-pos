@extends('templates.viho.layout')
@section('title', __('sale.discount'))

@push('styles')
    <style>
        /* Make DataTables controls match Viho users/roles layout */
        #discounts_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #discounts_table {
            width: 100% !important;
        }

        /* Ensure table rows display properly */
        #discounts_table tbody tr {
            display: table-row !important;
        }

        #discounts_table tbody td {
            display: table-cell !important;
        }

        /* Fix for DataTables rendering all in one row */
        .dataTables_wrapper table.dataTable tbody tr {
            display: table-row !important;
        }

        .dataTables_wrapper table.dataTable tbody td {
            display: table-cell !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('sale.discount')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('sale.discount')</h5>
                    @can('discount.access')
                        <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 btn-modal"
                            data-href="{{ action([\App\Http\Controllers\DiscountController::class, 'create']) }}"
                            data-container=".discount_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5l0 14"/>
                                <path d="M5 12l14 0"/>
                            </svg>
                            <span>@lang('messages.add')</span>
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    @can('discount.view')
                        <div class="row align-items-center mb-2" id="discount_dt_top">
                            <div class="col-sm-12 col-md-6" id="discount_dt_length"></div>
                            <div class="col-sm-12 col-md-6 text-md-end" id="discount_dt_filter"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="discounts_table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all-row" data-table-id="discounts_table"></th>
                                        <th>@lang('unit.name')</th>
                                        <th>@lang('lang_v1.starts_at')</th>
                                        <th>@lang('lang_v1.ends_at')</th>
                                        <th>@lang('sale.discount_amount')</th>
                                        <th>@lang('lang_v1.priority')</th>
                                        <th>@lang('product.brand')</th>
                                        <th>@lang('product.category')</th>
                                        <th>@lang('sale.products')</th>
                                        <th>@lang('sale.location')</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td colspan="11">
                                            <div class="d-flex gap-2">
                                                {!! Form::open([
                                                    'url' => action([\App\Http\Controllers\DiscountController::class, 'massDeactivate']),
                                                    'method' => 'post',
                                                    'id' => 'mass_deactivate_form',
                                                ]) !!}
                                                {!! Form::hidden('selected_discounts', null, ['id' => 'selected_discounts']) !!}
                                                {!! Form::submit(__('lang_v1.deactivate_selected'), [
                                                    'class' => 'btn btn-sm btn-warning',
                                                    'id' => 'deactivate-selected',
                                                ]) !!}
                                                {!! Form::close() !!}
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row align-items-center mt-2" id="discount_dt_bottom">
                            <div class="col-sm-12 col-md-5" id="discount_dt_info"></div>
                            <div class="col-sm-12 col-md-7 text-md-end" id="discount_dt_paginate"></div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade discount_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable('#discounts_table')) {
                return;
            }

            discounts_table = $('#discounts_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/ai-template/discount",
                dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-8 text-center'B><'col-sm-12 col-md-2 text-md-end'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-md-end'p>>",
                columns: [
                    { data: 'row_select', name: 'row_select', orderable: false, searchable: false },
                    { data: 'name', name: 'discounts.name' },
                    { data: 'starts_at', name: 'starts_at' },
                    { data: 'ends_at', name: 'ends_at' },
                    { data: 'discount_amount', name: 'discount_amount' },
                    { data: 'priority', name: 'priority' },
                    { data: 'brand', name: 'b.name' },
                    { data: 'category', name: 'c.name' },
                    { data: 'products', name: 'products', orderable: false, searchable: false },
                    { data: 'location', name: 'l.name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                initComplete: function() {
                    var relocate = function() {
                        var $wrapper = $('#discounts_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#discount_dt_length').empty().append($length);
                        if ($filter.length) $('#discount_dt_filter').empty().append($filter);
                        if ($info.length) $('#discount_dt_info').empty().append($info);
                        if ($paginate.length) $('#discount_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function() {
                        relocate();
                    });
                }
            });
        });

        $(document).on('click', '#deactivate-selected', function(e) {
            e.preventDefault();
            var selected_rows = [];
            var i = 0;
            $('.row-select:checked').each(function() {
                selected_rows[i++] = $(this).val();
            });

            if (selected_rows.length > 0) {
                $('input#selected_discounts').val(selected_rows);
                swal({
                    title: LANG.sure,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $('form#mass_deactivate_form').submit();
                    }
                });
            } else {
                $('input#selected_discounts').val('');
                swal('@lang('lang_v1.no_row_selected')');
            }
        });

        $(document).on('click', '.activate-discount', function(e) {
            e.preventDefault();
            var href = $(this).data('href');
            $.ajax({
                method: "get",
                url: href,
                dataType: "json",
                success: function(result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        discounts_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        });

        $(document).on('shown.bs.modal', '.discount_modal', function() {
            $('#variation_ids').select2({
                ajax: {
                    url: '/ai-template/purchases/get_products?check_enable_stock=false&only_variations=true',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        var results = [];
                        for (var item in data) {
                            results.push({
                                id: data[item].variation_id,
                                text: data[item].text,
                            });
                        }
                        return {
                            results: results,
                        };
                    },
                },
                minimumInputLength: 1,
                closeOnSelect: false
            });
        });

        $(document).on('change', '#variation_ids', function() {
            if ($(this).val().length) {
                $('#brand_input').addClass('hide');
                $('#category_input').addClass('hide');
            } else {
                $('#brand_input').removeClass('hide');
                $('#category_input').removeClass('hide');
            }
        });

        $(document).on('hidden.bs.modal', '.discount_modal', function() {
            $("#variation_ids").select2('destroy');
        });
    </script>
@endsection
