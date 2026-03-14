@extends('templates.viho.layout')
@section('title', __('lang_v1.subscriptions'))

@push('styles')
    <style>
        /* Make DataTables controls match Viho users/roles layout */
        #subscriptions_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #subscriptions_table {
            width: 100% !important;
        }

        /* Ensure table rows display properly */
        #subscriptions_table tbody tr {
            display: table-row !important;
        }

        #subscriptions_table tbody td {
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
                    <h3>@lang('lang_v1.subscriptions') @show_tooltip(__('lang_v1.recurring_invoice_help'))</h3>
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
                                    {!! Form::label('subscriptions_filter_date_range', __('report.date_range') . ':') !!}
                                    {!! Form::text('subscriptions_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
                                </div>
                            </div>
                        @endcomponent
                    </div>

                    <!-- Subscriptions Table -->
                    @can('sell.view')
                        <div class="table-responsive">
                            @include('sale_pos.partials.subscriptions_table')
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            //Date range as a button
            $('#subscriptions_filter_date_range').daterangepicker(
                dateRangeSettings,
                function (start, end) {
                    $('#subscriptions_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                    subscriptions_table.ajax.reload();
                }
            );
            $('#subscriptions_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#subscriptions_filter_date_range').val('');
                subscriptions_table.ajax.reload();
            });

            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable('#subscriptions_table')) {
                return;
            }

            subscriptions_table = $('#subscriptions_table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader: false,
                aaSorting: [[1, 'desc']],
                "ajax": {
                    "url": '/ai-template/sells/subscriptions',
                    "data": function (d) {
                        if ($('#subscriptions_filter_date_range').val()) {
                            var start = $('#subscriptions_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                            var end = $('#subscriptions_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                            d.start_date = start;
                            d.end_date = end;
                        }
                    }
                },
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'transaction_date', name: 'transactions.transaction_date' },
                    { data: 'invoice_no', name: 'transactions.invoice_no' },
                    { data: 'subscription_no', name: 'transactions.subscription_no' },
                    { data: 'name', name: 'contacts.name' },
                    { data: 'business_location', name: 'bl.name' },
                    { data: 'recur_interval', name: 'recur_interval' },
                    { data: 'subscription_invoices', name: 'subscription_invoices', orderable: false, searchable: false },
                    { data: 'upcoming_invoice', name: 'upcoming_invoice', orderable: false, searchable: false }
                ],
                "fnDrawCallback": function (oSettings) {
                    __currency_convert_recursively($('#subscriptions_table'));
                }
            });
        });
    </script>
@endsection
