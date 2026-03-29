@extends('templates.viho.layout')
@section('title', __('expense.expense_categories'))

@push('styles')
    <style>
        /* Make DataTables controls match Viho layout */
        #expense_category_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #expense_category_table {
            width: 100% !important;
        }

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
                    <h3>@lang('expense.expense_categories')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('expense.all_your_expense_categories')</h5>
                    <button type="button" class="btn btn-primary btn-sm btn-modal"
                        data-href="{{ action([\App\Http\Controllers\ExpenseCategoryController::class, 'create']) }}"
                        data-container=".expense_category_modal">
                        @lang('messages.add')
                    </button>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-2" id="expense_category_dt_top">
                        <div class="col-sm-12 col-md-3" id="expense_category_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-center" id="expense_category_dt_buttons"></div>
                        <div class="col-sm-12 col-md-3 text-md-end" id="expense_category_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="expense_category_table">
                            <thead>
                                <tr>
                                    <th>@lang('expense.category_name')</th>
                                    <th>@lang('expense.category_code')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="row align-items-center mt-2" id="expense_category_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="expense_category_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="expense_category_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade expense_category_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            // Destroy existing DataTable if it exists to prevent reinitialization error
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#expense_category_table')) {
                try {
                    $('#expense_category_table').DataTable().destroy();
                } catch (e) { }
            }

            // Initialize DataTable
            expense_category_table = $('#expense_category_table').DataTable({
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
                ajax: '/ai-template/expense-categories',
                columnDefs: [{
                    targets: 2,
                    orderable: false,
                    searchable: false,
                },],
                columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'action',
                    name: 'action'
                },
                ],
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#expense_category_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#expense_category_dt_length').empty().append($length);
                        if ($buttons.length) $('#expense_category_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#expense_category_dt_filter').empty().append($filter);
                        if ($info.length) $('#expense_category_dt_info').empty().append($info);
                        if ($paginate.length) $('#expense_category_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                }
            });
        });

        $(document).on('click', 'button.delete_expense_category', function () {
            swal({
                title: LANG.sure,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    var href = $(this).data('href');
                    $.ajax({
                        method: 'DELETE',
                        url: href,
                        dataType: 'json',
                        success: function (result) {
                            if (result.success) {
                                toastr.success(result.msg);
                                expense_category_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                }
            });
        });
    </script>
@endsection