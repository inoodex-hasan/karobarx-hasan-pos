@extends('templates.viho.layout')
@section('title', __('lang_v1.selling_price_group'))

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

        #selling_price_group_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #selling_price_group_table {
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

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.selling_price_group')
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @if (session('notification') || !empty($notification))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @if (!empty($notification['msg']))
                        {{ $notification['msg'] }}
                    @elseif(session('notification.msg'))
                        {{ session('notification.msg') }}
                    @endif
                </div>
            </div>
        </div>
    @endif

    @component('components.widget', [
        'class' => 'box-primary',
        'title' => __('lang_v1.all_selling_price_group'),
        'help_text' => __('lang_v1.selling_price_help_text'),
    ])
    @slot('tool')
    <div class="box-tools">
        <a class="btn btn-primary btn-sm btn-modal float-end"
            data-href="{{ route('ai-template.selling-price-group.create') }}" data-container=".view_modal">
            <i class="fa fa-plus"></i> @lang('messages.add')
        </a>
    </div>
    @endslot
    <div class="row align-items-center mb-2" id="selling_price_group_dt_top">
        <div class="col-sm-12 col-md-3" id="selling_price_group_dt_length"></div>
        <div class="col-sm-12 col-md-6 text-center" id="selling_price_group_dt_buttons"></div>
        <div class="col-sm-12 col-md-3 text-md-end" id="selling_price_group_dt_filter"></div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="selling_price_group_table">
            <thead>
                <tr>
                    <th>@lang('lang_v1.name')</th>
                    <th>@lang('lang_v1.description')</th>
                    <th>@lang('messages.action')</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="row align-items-center mt-2" id="selling_price_group_dt_bottom">
        <div class="col-sm-12 col-md-5" id="selling_price_group_dt_info"></div>
        <div class="col-sm-12 col-md-7 text-md-end" id="selling_price_group_dt_paginate"></div>
    </div>
    @endcomponent

    <div class="modal fade brands_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function () {

            var destroyDataTable = function (selector) {
                if (!window.jQuery || !$.fn || !$.fn.DataTable) {
                    return;
                }

                if ($.fn.DataTable.isDataTable(selector)) {
                    $(selector).DataTable().clear().destroy();
                    $(selector).find('tbody').remove();
                }
            };

            //selling_price_group_table
            destroyDataTable('#selling_price_group_table');
            var selling_price_group_table = $('#selling_price_group_table').DataTable({
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
                ajax: '{{ route('ai-template.selling-price-group.index') }}',
                columnDefs: [{
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                }],
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#selling_price_group_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#selling_price_group_dt_length').empty().append($length);
                        if ($buttons.length) $('#selling_price_group_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#selling_price_group_dt_filter').empty().append($filter);
                        if ($info.length) $('#selling_price_group_dt_info').empty().append($info);
                        if ($paginate.length) $('#selling_price_group_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    var api = this.api();
                    api.on('draw.dt', function () {
                        relocate();
                    });
                }
            });

            window.selling_price_group_table = selling_price_group_table;

            $(document)
                .off('submit.vihoSpg', 'form#selling_price_group_form')
                .on('submit.vihoSpg', 'form#selling_price_group_form', function (e) {
                    e.preventDefault();
                    var data = $(this).serialize();

                    $.ajax({
                        method: "POST",
                        url: $(this).attr("action"),
                        dataType: "json",
                        data: data,
                        success: function (result) {
                            if (result.success == true) {
                                $('div.view_modal').modal('hide');
                                toastr.success(result.msg);
                                selling_price_group_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });

            $(document)
                .off('click.vihoSpg', 'button.delete_spg_button')
                .on('click.vihoSpg', 'button.delete_spg_button', function () {
                    swal({
                        title: LANG.sure,
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
                                        selling_price_group_table.ajax.reload();
                                    } else {
                                        toastr.error(result.msg);
                                    }
                                }
                            });
                        }
                    });
                });

            $(document)
                .off('click.vihoSpg', 'button.activate_deactivate_spg')
                .on('click.vihoSpg', 'button.activate_deactivate_spg', function () {
                    var href = $(this).data('href');
                    $.ajax({
                        url: href,
                        dataType: "json",
                        success: function (result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                selling_price_group_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });

        });
    </script>
@endsection