@extends('templates.viho.layout')
@section('title', __( 'sale.drafts'))

@push('styles')
    <style>
        /* Make DataTables controls match Viho users/roles layout */
        #draft_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #draft_table {
            width: 100% !important;
        }

        /* Ensure table rows display properly */
        #draft_table tbody tr {
            display: table-row !important;
        }

        #draft_table tbody td {
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
                    <h3>@lang('sale.drafts')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('sale.drafts')</h5>
                    @can('draft.create')
                        <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" href="{{action([\App\Http\Controllers\SellController::class, 'create'], ['status' => 'draft'])}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5l0 14"/>
                                <path d="M5 12l14 0"/>
                            </svg>
                            <span>@lang('lang_v1.add_draft')</span>
                        </a>
                    @endcan
                </div>
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
                                    {!! Form::label('created_by', __('contact.contacts') . ':') !!}
                                    {!! Form::select('created_by', $sales_representative, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
                                </div>
                            </div>
                        @endcomponent
                    </div>

                    <!-- Draft Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="draft_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('sale.customer_name')</th>
                                    <th>@lang('lang_v1.contact_no')</th>
                                    <th>@lang('sale.location')</th>
                                    <th>@lang('lang_v1.total_items')</th>
                                    <th>@lang('lang_v1.added_by')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade sell_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable('#draft_table')) {
                return;
            }

            var draft_table = $('#draft_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, 'desc']
                ],
                "ajax": {
                    "url": '/sells/draft-dt',
                    "data": function(d) {
                        d.location_id = $('#sell_list_filter_location_id').val();
                        d.customer_id = $('#sell_list_filter_customer_id').val();
                        d.date_range = $('#sell_list_filter_date_range').val();
                        if ($('#created_by').length > 0) {
                            d.created_by = $('#created_by').val();
                        }
                    }
                },
                columnDefs: [{
                    "targets": 7,
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
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
                        data: 'total_items',
                        name: 'total_items',
                        "searchable": false
                    },
                    {
                        data: 'added_by',
                        name: 'added_by'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                "fnDrawCallback": function(oSettings) {
                    __currency_convert_recursively($('#draft_table'));
                }
            });

            $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #created_by', function() {
                draft_table.ajax.reload();
            });

            $(document).on('click', 'a.convert-to-proforma', function(e) {
                e.preventDefault();
                swal({
                    title: LANG.sure,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(confirm => {
                    if (confirm) {
                        var url = $(this).attr('href');
                        $.ajax({
                            method: 'GET',
                            url: url,
                            dataType: 'json',
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    draft_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection
