@extends('templates.viho.layout')
@section('title', __('user.roles'))

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('user.roles')</h5>
                    @can('roles.create')
                        <a class="btn btn-primary" href="{{ route('ai-template.roles.create') }}">
                            @lang('messages.add')
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    @can('roles.view')
                        <div class="row align-items-center mb-2" id="roles_dt_top">
                            <div class="col-sm-12 col-md-3" id="roles_dt_length"></div>
                            <div class="col-sm-12 col-md-6 text-center" id="roles_dt_buttons"></div>
                            <div class="col-sm-12 col-md-3 text-md-end" id="roles_dt_filter"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="roles_table">
                                <thead>
                                    <tr>
                                        <th>@lang('user.roles')</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row align-items-center mt-2" id="roles_dt_bottom">
                            <div class="col-sm-12 col-md-5" id="roles_dt_info"></div>
                            <div class="col-sm-12 col-md-7 text-md-end" id="roles_dt_paginate"></div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

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

        #roles_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #roles_table {
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

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#roles_table')) {
                $('#roles_table').DataTable().destroy();
                $('#roles_table').find('tbody').remove();
            }

            var roles_table = $('#roles_table').DataTable({
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
                ajax: "{{ route('ai-template.roles.index') }}",
                columnDefs: [{
                    targets: 1,
                    orderable: false,
                    searchable: false
                }],
                // RoleController currently returns row data as a numeric array (because of ->make(false) + rawColumns([1])).
                // Use numeric indexes to avoid "unknown parameter" warnings.
                columns: [
                    { data: 0, name: 'name' },
                    { data: 1, name: 'action', orderable: false, searchable: false }
                ],
                drawCallback: function () {
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                },
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#roles_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#roles_dt_length').empty().append($length);
                        if ($buttons.length) $('#roles_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#roles_dt_filter').empty().append($filter);
                        if ($info.length) $('#roles_dt_info').empty().append($info);
                        if ($paginate.length) $('#roles_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                }
            });

            $(document).off('click.vihoRoles', 'button.delete_role_button').on('click.vihoRoles', 'button.delete_role_button', function () {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_role,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function (result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    roles_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection