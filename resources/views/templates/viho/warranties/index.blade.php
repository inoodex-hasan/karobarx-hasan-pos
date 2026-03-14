@extends('templates.viho.layout')
@section('title', __('lang_v1.warranties'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('lang_v1.warranties')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>@lang('lang_v1.all_warranties')</h5>
                    <a class="btn btn-primary btn-sm btn-modal"
                        data-href="{{ route('ai-template.warranties.create') }}"
                        data-container=".view_modal"><i class="fa fa-plus"></i> @lang('messages.add')</a>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div id="warranty_dt_length"></div>
                        <div id="warranty_dt_filter"></div>
                    </div>
                    <table class="table table-bordered table-striped" id="warranty_table">
                        <thead>
                            <tr>
                                <th>@lang('lang_v1.name')</th>
                                <th>@lang('lang_v1.description')</th>
                                <th>@lang('lang_v1.duration')</th>
                                <th>@lang('messages.action')</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div id="warranty_dt_info"></div>
                        <div id="warranty_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(function() {
            var destroyDataTable = function(selector) {
                if (!window.jQuery || !$.fn || !$.fn.DataTable) {
                    return;
                }

                if ($.fn.DataTable.isDataTable(selector)) {
                    $(selector).DataTable().clear().destroy();
                    $(selector).find('tbody').remove();
                }
            };

            destroyDataTable('#warranty_table');
            var warranty_table = $('#warranty_table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader:false,
                ajax: "{{ route('ai-template.warranties.index') }}",
                columnDefs: [{
                    "targets": 3,
                    "orderable": false,
                    "searchable": false
                }],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'duration', name: 'duration' },
                    { data: 'action', name: 'action' },
                ],
                drawCallback: function () {
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                },
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#warranty_table').closest('.dataTables_wrapper');
                        var $length = $wrapper.find('.dataTables_length');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');
                        if ($length.length) $('#warranty_dt_length').empty().append($length);
                        if ($filter.length) $('#warranty_dt_filter').empty().append($filter);
                        if ($info.length) $('#warranty_dt_info').empty().append($info);
                        if ($paginate.length) $('#warranty_dt_paginate').empty().append($paginate);
                    }
                    relocate();
                    this.api().on('draw.dt', function () {
                        relocate();
                    });
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                }
            });

            $(document).on('submit', 'form#warranty_form', function(e) {
                e.preventDefault();
                $(this).find('button[type="submit"]').attr('disabled', true);
                var data = $(this).serialize();
                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            $('div.view_modal').modal('hide');
                            toastr.success(result.msg);
                            warranty_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });
        });
    </script>
@endsection
