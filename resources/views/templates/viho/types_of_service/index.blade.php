@extends('templates.viho.layout')
@section('title', __('lang_v1.types_of_service'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang( 'lang_v1.types_of_service' )</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang( 'lang_v1.types_of_service' )</h5>
                    @can('access_types_of_service')
                        <div>
                            <button class="btn btn-primary btn-sm btn-modal"
                                data-href="{{action([\App\Http\Controllers\TypesOfServiceController::class, 'create'])}}"
                                data-container=".type_of_service_modal">
                                @lang('messages.add')
                            </button>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    @can('brand.view')
                        <div class="row align-items-center mb-2" id="types_of_service_dt_top">
                            <div class="col-sm-12 col-md-6" id="types_of_service_dt_length"></div>
                            <div class="col-sm-12 col-md-6 text-md-end" id="types_of_service_dt_filter"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="types_of_service_table">
                                <thead>
                                    <tr>
                                        <th>@lang( 'tax_rate.name' )</th>
                                        <th>@lang( 'lang_v1.description' )</th>
                                        <th>@lang( 'lang_v1.packing_charge' )</th>
                                        <th>@lang( 'messages.action' )</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade type_of_service_modal contains_select2" tabindex="-1" role="dialog"
    	aria-labelledby="gridSystemModalLabel">
    </div>
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#types_of_service_table')) {
                return;
            }

            types_of_service_table = $('#types_of_service_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/ai-template/types-of-service",
                dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-8 text-center'B><'col-sm-12 col-md-2 text-md-end'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-md-end'p>>",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'packing_charge', name: 'packing_charge' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                fnDrawCallback: function(oSettings) {
                    __currency_convert_recursively($('#types_of_service_table'));
                },
                initComplete: function() {
                    var relocate = function() {
                        var $wrapper = $('#types_of_service_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#types_of_service_dt_length').empty().append($length);
                        if ($filter.length) $('#types_of_service_dt_filter').empty().append($filter);
                        if ($info.length) $('#types_of_service_dt_info').empty().append($info);
                        if ($paginate.length) $('#types_of_service_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function() {
                        relocate();
                    });
                }
            });
        });
    </script>
@endsection
