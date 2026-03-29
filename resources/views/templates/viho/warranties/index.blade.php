@extends('templates.viho.layout')
@section('title', __('lang_v1.warranties'))

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

        #warranty_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #warranty_table {
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
    </style>
@endpush

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
                <a class="btn btn-primary btn-sm btn-modal" data-href="{{ route('ai-template.warranties.create') }}"
                    data-container=".view_modal"><i class="fa fa-plus"></i> @lang('messages.add')</a>
            </div>
            <div class="card-body">
                <div class="row align-items-center mb-2" id="warranty_dt_top">
                    <div class="col-sm-12 col-md-3" id="warranty_dt_length"></div>
                    <div class="col-sm-12 col-md-6 text-center" id="warranty_dt_buttons"></div>
                    <div class="col-sm-12 col-md-3 text-md-end" id="warranty_dt_filter"></div>
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
                <div class="row align-items-center mt-2" id="warranty_dt_bottom">
                    <div class="col-sm-12 col-md-5" id="warranty_dt_info"></div>
                    <div class="col-sm-12 col-md-7 text-md-end" id="warranty_dt_paginate"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(function () {
            var destroyDataTable = function (selector) {
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
                fixedHeader: false,
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
                        var $wrapper = $('#warranty_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#warranty_dt_length').empty().append($length);
                        if ($buttons.length) $('#warranty_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#warranty_dt_filter').empty().append($filter);
                        if ($info.length) $('#warranty_dt_info').empty().append($info);
                        if ($paginate.length) $('#warranty_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                }
            });

            $(document).on('submit', 'form#warranty_form', function (e) {
                e.preventDefault();
                $(this).find('button[type="submit"]').attr('disabled', true);
                var data = $(this).serialize();
                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    success: function (result) {
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