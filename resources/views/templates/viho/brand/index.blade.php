@extends('templates.viho.layout')
@section('title', __('brand.brands'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('brand.brands')
                        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('brand.manage_your_brands')</small>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
                <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>@lang('brand.all_your_brands')</h5>
                    <a class="btn btn-primary btn-sm btn-modal float-end"
                       data-href="{{ route('ai-template.brands.create') }}"
                       data-container=".brands_modal"><i class="fa fa-plus"></i> @lang('messages.add')</a>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div id="brands_dt_length"></div>
                        <div id="brands_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="brands_table">
                            <thead>
                                <tr>
                                    <th>@lang('brand.brands')</th>
                                    <th>@lang('brand.note')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div id="brands_dt_info"></div>
                        <div id="brands_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade brands_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function(){
            var relocate = function () {
                var $wrapper = $('#brands_table_wrapper');
                if ($wrapper.length < 1) return;

                var $length = $wrapper.find('.dataTables_length');
                var $filter = $wrapper.find('.dataTables_filter');
                var $info = $wrapper.find('.dataTables_info');
                var $paginate = $wrapper.find('.dataTables_paginate');

                if ($length.length) $('#brands_dt_length').empty().append($length);
                if ($filter.length) $('#brands_dt_filter').empty().append($filter);
                if ($info.length) $('#brands_dt_info').empty().append($info);
                if ($paginate.length) $('#brands_dt_paginate').empty().append($paginate);
            };

            var table;
            if ($.fn.DataTable.isDataTable('#brands_table')) {
                table = $('#brands_table').DataTable();
            } else {
                table = $('#brands_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('ai-template.brands.index') }}',
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'description', name: 'description' },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ]
                });
            }

            relocate();
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            table.off('draw.dt.vihoBrands').on('draw.dt.vihoBrands', function () {
                relocate();
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        });
    </script>
@endsection
