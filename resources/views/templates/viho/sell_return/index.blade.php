@extends('templates.viho.layout')
@section('title', __('lang_v1.sell_return'))

@push('styles')
    <style>
        /* Make DataTables controls match Viho users/roles layout */
        #sell_return_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #sell_return_table {
            width: 100% !important;
        }

        /* Ensure table rows display properly */
        #sell_return_table tbody tr {
            display: table-row !important;
        }

        #sell_return_table tbody td {
            display: table-cell !important;
        }

        /* Fix for DataTables rendering all in one row */
        .dataTables_wrapper table.dataTable tbody tr {
            display: table-row !important;
        }

        .dataTables_wrapper table.dataTable tbody td {
            display: table-cell !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('lang_v1.sell_return')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        @component('components.filters', ['title' => __('report.filters')])
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('sell_list_filter_location_id',  __('purchase.business_location') . ':') !!}
                                    {!! Form::select('sell_list_filter_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('sell_list_filter_customer_id',  __('contact.customer') . ':') !!}
                                    {!! Form::select('sell_list_filter_customer_id', $customers, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('sell_list_filter_date_range', __('report.date_range') . ':') !!}
                                    {!! Form::text('sell_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
                                </div>
                            </div>
                            @can('access_sell_return')
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('created_by',  __('report.user') . ':') !!}
                                    {!! Form::select('created_by', $sales_representative, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                                </div>
                            </div>
                            @endcan
                        @endcomponent
                    </div>

                    <!-- Sell Return Table -->
                    <div class="row align-items-center mb-2" id="sell_return_dt_top">
                        <div class="col-sm-12 col-md-6" id="sell_return_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-md-end" id="sell_return_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="sell_return_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('sale.invoice_no')</th>
                                    <th>@lang('lang_v1.parent_sale')</th>
                                    <th>@lang('sale.customer_name')</th>
                                    <th>@lang('sale.location')</th>
                                    <th>@lang('purchase.payment_status')</th>
                                    <th>@lang('sale.total_amount')</th>
                                    <th>@lang('lang_v1.payment_due')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 footer-total text-center">
                                    <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                                    <td id="footer_payment_status_count_sr"></td>
                                    <td><span class="display_currency" id="footer_sell_return_total" data-currency_symbol ="true"></span></td>
                                    <td><span class="display_currency" id="footer_total_due_sr" data-currency_symbol ="true"></span></td>
                                    <td colspan="1"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="row align-items-center mt-2" id="sell_return_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="sell_return_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="sell_return_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@stop

@section('javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script>
        $(document).ready(function(){
            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable('#sell_return_table')) {
                return;
            }

            $('#sell_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function (start, end) {
                    $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                    sell_return_table.ajax.reload();
                }
            );
            $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#sell_list_filter_date_range').val('');
                sell_return_table.ajax.reload();
            });

            sell_return_table = $('#sell_return_table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader:false,
                aaSorting: [[0, 'desc']],
                "ajax": {
                    "url": '/ai-template/sell-return',
                    "data": function ( d ) {
                        if($('#sell_list_filter_date_range').val()) {
                            var start = $('#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                            var end = $('#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                            d.start_date = start;
                            d.end_date = end;
                        }

                        if($('#sell_list_filter_location_id').length) {
                            d.location_id = $('#sell_list_filter_location_id').val();
                        }
                        d.customer_id = $('#sell_list_filter_customer_id').val();

                        if($('#created_by').length) {
                            d.created_by = $('#created_by').val();
                        }
                    }
                },
                columnDefs: [{
                    "targets": [7, 8],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [
                    { data: 'transaction_date', name: 'transaction_date'  },
                    { data: 'invoice_no', name: 'invoice_no'},
                    { data: 'parent_sale', name: 'T1.invoice_no'},
                    { data: 'name', name: 'contacts.name'},
                    { data: 'business_location', name: 'bl.name'},
                    { data: 'payment_status', name: 'payment_status'},
                    { data: 'final_total', name: 'final_total'},
                    { data: 'payment_due', name: 'payment_due'},
                    { data: 'action', name: 'action'}
                ],
                "fnDrawCallback": function (oSettings) {
                    var total_sell = sum_table_col($('#sell_return_table'), 'final_total');
                    $('#footer_sell_return_total').text(total_sell);

                    $('#footer_payment_status_count_sr').html(__sum_status_html($('#sell_return_table'), 'payment-status-label'));

                    var total_due = sum_table_col($('#sell_return_table'), 'payment_due');
                    $('#footer_total_due_sr').text(total_due);

                    __currency_convert_recursively($('#sell_return_table'));
                },
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td:eq(2)').attr('class', 'clickable_td');
                },
                initComplete: function() {
                    var relocate = function() {
                        var $wrapper = $('#sell_return_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#sell_return_dt_length').empty().append($length);
                        if ($filter.length) $('#sell_return_dt_filter').empty().append($filter);
                        if ($info.length) $('#sell_return_dt_info').empty().append($info);
                        if ($paginate.length) $('#sell_return_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    this.api().on('draw.dt', function() {
                        relocate();
                    });
                }
            });

            $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #created_by',  function() {
                sell_return_table.ajax.reload();
            });

            $(document).on('click', 'a.delete_sell_return', function(e) {
                e.preventDefault();
                swal({
                    title: LANG.sure,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        var href = $(this).attr('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: 'DELETE',
                            url: href,
                            dataType: 'json',
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    sell_return_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
        })
    </script>
@endsection
