@extends('templates.viho.layout')
@section('title', __('business.business_locations'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('business.business_locations')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@lang('business.all_your_business_locations')</h5>
                    @can('business_settings.access')
                        <div>
                            <button class="btn btn-primary btn-sm btn-modal"
                                data-href="{{action([\App\Http\Controllers\BusinessLocationController::class, 'create'])}}"
                                data-container=".location_add_modal">
                                @lang('messages.add')
                            </button>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-2">
                        <div class="col-sm-12 col-md-6" id="business_location_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-md-end" id="business_location_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="business_location_table">
                            <thead>
                                <tr>
                                    <th>@lang( 'invoice.name' )</th>
                                    <th>@lang( 'lang_v1.location_id' )</th>
                                    <th>@lang( 'business.landmark' )</th>
                                    <th>@lang( 'business.city' )</th>
                                    <th>@lang( 'business.zip_code' )</th>
                                    <th>@lang( 'business.state' )</th>
                                    <th>@lang( 'business.country' )</th>
                                    <th>@lang( 'lang_v1.price_group' )</th>
                                    <th>@lang( 'invoice.invoice_scheme' )</th>
                                    <th>@lang('lang_v1.invoice_layout_for_pos')</th>
                                    <th>@lang('lang_v1.invoice_layout_for_sale')</th>
                                    <th>@lang( 'messages.action' )</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="row align-items-center mt-2">
                        <div class="col-sm-12 col-md-5" id="business_location_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="business_location_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade location_add_modal" tabindex="-1" role="dialog"
    	aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade location_edit_modal" tabindex="-1" role="dialog"
        aria-labelledby="gridSystemModalLabel">
    </div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        var business_location_table = $('#business_location_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "/business-location",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'location_id', name: 'location_id' },
                { data: 'landmark', name: 'landmark' },
                { data: 'city', name: 'city' },
                { data: 'zip_code', name: 'zip_code' },
                { data: 'state', name: 'state' },
                { data: 'country', name: 'country' },
                { data: 'price_group', name: 'price_group' },
                { data: 'invoice_scheme', name: 'invoice_scheme' },
                { data: 'pos_invoice_layout', name: 'pos_invoice_layout' },
                { data: 'sale_invoice_layout', name: 'sale_invoice_layout' },
                { data: 'action', name: 'action' }
            ],
            initComplete: function() {
                var relocate = function () {
                    var $wrapper = $('#business_location_table_wrapper');
                    if ($wrapper.length < 1) return;

                    var $length = $wrapper.find('.dataTables_length');
                    var $filter = $wrapper.find('.dataTables_filter');
                    var $info = $wrapper.find('.dataTables_info');
                    var $paginate = $wrapper.find('.dataTables_paginate');

                    if ($length.length) $('#business_location_dt_length').empty().append($length);
                    if ($filter.length) $('#business_location_dt_filter').empty().append($filter);
                    if ($info.length) $('#business_location_dt_info').empty().append($info);
                    if ($paginate.length) $('#business_location_dt_paginate').empty().append($paginate);
                };

                relocate();
                this.api().on('draw.dt', function () {
                    relocate();
                });
            }
        });
    });
</script>
@endsection
