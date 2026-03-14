@extends('templates.viho.layout')
@section('title', __( 'lang_v1.shipments'))

@push('styles')
    <style>
        /* Make DataTables controls match Viho users/roles layout */
        #shipments_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #shipments_table {
            width: 100% !important;
        }

        /* Ensure table rows display properly */
        #shipments_table tbody tr {
            display: table-row !important;
        }

        #shipments_table tbody td {
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
                    <h3>@lang('lang_v1.shipments')</h3>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('shipping_status', __('lang_v1.shipping_status') . ':') !!}
                                    {!! Form::select('shipping_status', $shipping_statuses, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
                                </div>
                            </div>
                            @if ($is_service_staff_enabled)
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('service_staff_id', __('lang_v1.service_staff') . ':') !!}
                                    {!! Form::select('service_staff_id', $service_staffs, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('delivery_person', __('lang_v1.delivery_person') . ':') !!}
                                    {!! Form::select('delivery_person', $delevery_person, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
                                </div>
                            </div>
                        @endcomponent
                    </div>

                    <!-- Shipments Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="shipments_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('sale.invoice_no')</th>
                                    <th>@lang('sale.customer_name')</th>
                                    <th>@lang('lang_v1.contact_no')</th>
                                    <th>@lang('sale.location')</th>
                                    <th>@lang('lang_v1.delivery_person')</th>
                                    <th>@lang('lang_v1.shipping_status')</th>
                                    <th>@lang('lang_v1.service_staff')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable('#shipments_table')) {
                return;
            }

            //Date range as a button
            $('#sell_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function (start, end) {
                    $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                    shipments_table.ajax.reload();
                }
            );
            $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#sell_list_filter_date_range').val('');
                shipments_table.ajax.reload();
            });

            shipments_table = $('#shipments_table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader:false,
                aaSorting: [[1, 'desc']],
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                "ajax": {
                    "url": '/ai-template/sells',
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
                        d.only_shipments = true;
                        d.shipping_status = $('#shipping_status').val();
                        d.delivery_person = $('#delivery_person').val();

                        if($('#service_staff_id').length) {
                            d.service_staffs = $('#service_staff_id').val();
                        }
                    }
                },
                columns: [{
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'transaction_date',
                        name: 'transaction_date'
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'conatct_name',
                        name: 'conatct_name'
                    },
                    {
                        data: 'mobile',
                        name: 'contacts.mobile'
                    },
                    {
                        data: 'business_location',
                        name: 'bl.name'
                    },
                    {
                        data: 'delivery_person',
                        name: 'delivery_person'
                    },
                    {
                        data: 'shipping_status',
                        name: 'shipping_status'
                    },
                    {
                        data: 'waiter',
                        name: 'ss.first_name',
                        visible: {{ $is_service_staff_enabled ? 'true' : 'false' }}
                    }
                ],
                "fnDrawCallback": function (oSettings) {
                    __currency_convert_recursively($('#shipments_table'));
                },
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td:eq(4)').attr('class', 'clickable_td');
                }
            });

            $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #shipping_status, #service_staff_id, #delivery_person', function() {
                shipments_table.ajax.reload();
            });
        });
    </script>
@endsection
