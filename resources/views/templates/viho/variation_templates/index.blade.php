@extends('templates.viho.layout')
@section('title', __('product.variations'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('product.variations')
                        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('lang_v1.manage_product_variations')</small>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>@lang('lang_v1.all_variations')</h5>
                    <a class="btn btn-primary btn-sm btn-modal"
                       data-href="{{ action([\App\Http\Controllers\VariationTemplateController::class, 'create']) }}"
                       data-container=".variation_modal">@lang('messages.add')</a>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div id="variation_dt_length"></div>
                        <div id="variation_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="variation_table">
                            <thead>
                            <tr>
                                <th>@lang('product.variations')</th>
                                <th>@lang('lang_v1.values')</th>
                                <th>@lang('messages.action')</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div id="variation_dt_info"></div>
                        <div id="variation_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade variation_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function(){
            $('#variation_table').DataTable({
                retrieve: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('ai-template.variation-templates.index') }}',
                columnDefs: [{
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                }],
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#variation_table').closest('.dataTables_wrapper');
                        var $length = $wrapper.find('.dataTables_length');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');
                        if ($length.length) $('#variation_dt_length').empty().append($length);
                        if ($filter.length) $('#variation_dt_filter').empty().append($filter);
                        if ($info.length) $('#variation_dt_info').empty().append($info);
                        if ($paginate.length) $('#variation_dt_paginate').empty().append($paginate);
                    }
                    relocate();
                    this.api().on('draw.dt', function () {
                        relocate();
                    });
                }
            });
        });
    </script>
@endsection
