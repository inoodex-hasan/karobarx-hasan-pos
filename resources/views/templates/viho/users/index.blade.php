@extends('templates.viho.layout')
@section('title', __('user.users'))

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('user.users')</h5>
                    @can('user.create')
                        <a class="btn btn-primary" href="{{ route('ai-template.users.create') }}">
                            @lang('messages.add')
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    @can('user.view')
                        <div class="row align-items-center mb-2" id="users_dt_top">
                            <div class="col-sm-12 col-md-3" id="users_dt_length"></div>
                            <div class="col-sm-12 col-md-6 text-center" id="users_dt_buttons"></div>
                            <div class="col-sm-12 col-md-3 text-md-end" id="users_dt_filter"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="users_table">
                                <thead>
                                    <tr>
                                        <th>@lang('business.username')</th>
                                        <th>@lang('user.name')</th>
                                        <th>@lang('user.role')</th>
                                        <th>@lang('business.email')</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row align-items-center mt-2" id="users_dt_bottom">
                            <div class="col-sm-12 col-md-5" id="users_dt_info"></div>
                            <div class="col-sm-12 col-md-7 text-md-end" id="users_dt_paginate"></div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
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

        /* Full width and alignment refinements */
        #users_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #users_table {
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
        $(document).ready(function() {
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#users_table')) {
                $('#users_table').DataTable().destroy();
                // Leave thead intact, just remove any old tbody content.
                $('#users_table').find('tbody').remove();
            }

            var users_table = $('#users_table').DataTable({
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
                ajax: '{{ route('ai-template.users.index') }}',
                columnDefs: [{
                    "targets": [4],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: "username"
                    },
                    {
                        data: "full_name"
                    },
                    {
                        data: "role"
                    },
                    {
                        data: "email"
                    },
                    {
                        data: "action",
                        render: function(data, type, row) {
                            return data;
                        }
                    }
                ],
                drawCallback: function(settings) {
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                },
                initComplete: function() {
                    var relocate = function() {
                        var $wrapper = $('#users_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#users_dt_length').empty().append($length);
                        if ($buttons.length) $('#users_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#users_dt_filter').empty().append($filter);
                        if ($info.length) $('#users_dt_info').empty().append($info);
                        if ($paginate.length) $('#users_dt_paginate').empty().append($paginate);

                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function() {
                        relocate();
                    });
                }
            });

            $(document).on('click', 'button.delete_user_button', function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_user,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (!willDelete) return;

                    var href = $(this).data('href');
                    var data = $(this).serialize();
                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        data: data,
                        success: function(result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                users_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });
            });
        });
    </script>
@endsection
