@extends('templates.viho.layout')
@section('title', __('unit.units'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('unit.units')
                        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('unit.manage_your_units')</small>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>@lang('unit.all_your_units')</h5>
                    <a class="btn btn-primary btn-sm btn-modal"
                       data-href="{{ action([\App\Http\Controllers\UnitController::class, 'create']) }}"
                       data-container=".unit_modal">@lang('messages.add')</a>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div id="unit_dt_length"></div>
                        <div id="unit_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="unit_table">
                            <thead>
                                <tr>
                                    <th>@lang('unit.name')</th>
                                    <th>@lang('unit.short_name')</th>
                                    <th>@lang('unit.allow_decimal')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div id="unit_dt_info"></div>
                        <div id="unit_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade unit_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function(){
            var destroyDataTable = function(selector) {
                if (!window.jQuery || !$.fn || !$.fn.DataTable) {
                    return;
                }

                if ($.fn.DataTable.isDataTable(selector)) {
                    $(selector).DataTable().clear().destroy();
                    $(selector).find('tbody').remove();
                }
            };

            var relocate = function () {
                var $wrapper = $('#unit_table_wrapper');
                if ($wrapper.length < 1) return;

                var $length = $wrapper.find('.dataTables_length');
                var $filter = $wrapper.find('.dataTables_filter');
                var $info = $wrapper.find('.dataTables_info');
                var $paginate = $wrapper.find('.dataTables_paginate');

                if ($length.length) $('#unit_dt_length').empty().append($length);
                if ($filter.length) $('#unit_dt_filter').empty().append($filter);
                if ($info.length) $('#unit_dt_info').empty().append($info);
                if ($paginate.length) $('#unit_dt_paginate').empty().append($paginate);
            };

            var table;
            if ($.fn.DataTable.isDataTable('#unit_table')) {
                table = $('#unit_table').DataTable();
            } else {
                // If global `app.js` doesn't initialize for any reason, fallback init here.
                destroyDataTable('#unit_table');
                table = $('#unit_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('ai-template.units.index') }}',
                    columnDefs: [{
                        targets: 3,
                        orderable: false,
                        searchable: false
                    }]
                });
            }

            relocate();
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            table.off('draw.dt.vihoUnits').on('draw.dt.vihoUnits', function () {
                relocate();
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        });
    </script>
@endsection
