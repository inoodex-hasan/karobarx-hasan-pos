@extends('templates.viho.layout')
@section('title', __('lang_v1.' . $type . 's'))

@php
    $api_key = env('GOOGLE_MAP_API_KEY');
@endphp

@if (!empty($api_key))
    @section('css')
        @include('contact.partials.google_map_styles')
    @endsection
@endif

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('lang_v1.' . $type . 's') <small
                            class="text-muted">@lang('contact.manage_your_contact', ['contacts' => __('lang_v1.' . $type . 's')])</small>
                    </h3>
                </div>
                <div class="col-sm-6 text-right">
                    @if (
                            auth()->user()->can('supplier.create') ||
                            auth()->user()->can('customer.create') ||
                            auth()->user()->can('supplier.view_own') ||
                            auth()->user()->can('customer.view_own')
                        )
                        <a class="btn btn-primary btn-sm btn-modal"
                            data-href="{{ route('ai-template.contacts.create', ['type' => $type]) }}"
                            data-container=".contact_modal">
                            <i class="fa fa-plus"></i> @lang('messages.add')
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>@lang('report.filters')</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if ($type == 'customer')
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('has_sell_due', 1, false, ['class' => 'input-icheck', 'id' => 'has_sell_due']) !!}
                                            <strong>@lang('lang_v1.sell_due')</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('has_sell_return', 1, false, ['class' => 'input-icheck', 'id' => 'has_sell_return']) !!}
                                            <strong>@lang('lang_v1.sell_return')</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @elseif($type == 'supplier')
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('has_purchase_due', 1, false, ['class' => 'input-icheck', 'id' => 'has_purchase_due']) !!}
                                            <strong>@lang('report.purchase_due')</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('has_purchase_return', 1, false, ['class' => 'input-icheck', 'id' => 'has_purchase_return']) !!}
                                            <strong>@lang('lang_v1.purchase_return')</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('has_advance_balance', 1, false, ['class' => 'input-icheck', 'id' => 'has_advance_balance']) !!}
                                        <strong>@lang('lang_v1.advance_balance')</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('has_opening_balance', 1, false, ['class' => 'input-icheck', 'id' => 'has_opening_balance']) !!}
                                        <strong>@lang('lang_v1.opening_balance')</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if ($type == 'customer')
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="has_no_sell_from">@lang('lang_v1.has_no_sell_from'):</label>
                                                    {!! Form::select(
                                'has_no_sell_from',
                                [
                                    'one_month' => __('lang_v1.one_month'),
                                    'three_months' => __('lang_v1.three_months'),
                                    'six_months' => __('lang_v1.six_months'),
                                    'one_year' => __('lang_v1.one_year'),
                                ],
                                null,
                                ['class' => 'form-control', 'id' => 'has_no_sell_from', 'placeholder' => __('messages.please_select')],
                            ) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="cg_filter">@lang('lang_v1.customer_group'):</label>
                                                    {!! Form::select('cg_filter', $customer_groups, null, ['class' => 'form-control', 'id' => 'cg_filter']) !!}
                                                </div>
                                            </div>
                        @endif

                        @if (config('constants.enable_contact_assign') === true)
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('assigned_to', __('lang_v1.assigned_to') . ':') !!}
                                    {!! Form::select('assigned_to', $users, null, ['class' => 'form-control select2', 'style' => 'width:100%']) !!}
                                </div>
                            </div>
                        @endif

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status_filter">@lang('sale.status'):</label>
                                {!! Form::select(
        'status_filter',
        ['active' => __('business.is_active'), 'inactive' => __('lang_v1.inactive')],
        null,
        ['class' => 'form-control', 'id' => 'status_filter', 'placeholder' => __('lang_v1.none')],
    ) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="{{ $type }}" id="contact_type">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>@lang('contact.all_your_contact', ['contacts' => __('lang_v1.' . $type . 's')])</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-2" id="contact_dt_top">
                        <div class="col-sm-12 col-md-6" id="contact_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-md-end" id="contact_dt_filter"></div>
                    </div>
                    {{-- Horizontal scroll: DataTables scrollX only — avoid .table-responsive (double/conflicting scroll) --}}
                        <table class="table table-bordered table-striped" id="contact_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('lang_v1.contact_id')</th>
                                    @if ($type == 'supplier')
                                        <th>@lang('business.business_name')</th>
                                        <th>@lang('contact.name')</th>
                                        <th>@lang('business.email')</th>
                                        <th>@lang('contact.tax_no')</th>
                                        <th>@lang('contact.pay_term')</th>
                                        <th>@lang('account.opening_balance')</th>
                                        <th>@lang('lang_v1.advance_balance')</th>
                                        <th>@lang('lang_v1.added_on')</th>
                                        <th>@lang('business.address')</th>
                                        <th>@lang('contact.mobile')</th>
                                        <th>@lang('contact.total_purchase_due')</th>
                                        <th>@lang('lang_v1.total_purchase_return_due')</th>
                                    @elseif($type == 'customer')
                                        <th>@lang('business.business_name')</th>
                                        <th>@lang('user.name')</th>
                                        <th>@lang('business.email')</th>
                                        <th>@lang('contact.tax_no')</th>
                                        <th>@lang('lang_v1.credit_limit')</th>
                                        <th>@lang('contact.pay_term')</th>
                                        <th>@lang('account.opening_balance')</th>
                                        <th>@lang('lang_v1.advance_balance')</th>
                                        <th>@lang('lang_v1.added_on')</th>
                                        @if ($reward_enabled)
                                            <th id="rp_col">{{ session('business.rp_name') }}</th>
                                        @endif
                                        <th>@lang('lang_v1.customer_group')</th>
                                        <th>@lang('business.address')</th>
                                        <th>@lang('contact.mobile')</th>
                                        <th>@lang('contact.total_sale_due')</th>
                                        <th>@lang('lang_v1.total_sell_return_due')</th>
                                    @endif
                                    @php
                                        $custom_labels = json_decode(session('business.custom_labels'), true);
                                    @endphp
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_1'] ?? __('lang_v1.contact_custom_field1') }}
                                    </th>
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_2'] ?? __('lang_v1.contact_custom_field2') }}
                                    </th>
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_3'] ?? __('lang_v1.contact_custom_field3') }}
                                    </th>
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_4'] ?? __('lang_v1.contact_custom_field4') }}
                                    </th>
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_5'] ?? __('lang_v1.custom_field', ['number' => 5]) }}
                                    </th>
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_6'] ?? __('lang_v1.custom_field', ['number' => 6]) }}
                                    </th>
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_7'] ?? __('lang_v1.custom_field', ['number' => 7]) }}
                                    </th>
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_8'] ?? __('lang_v1.custom_field', ['number' => 8]) }}
                                    </th>
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_9'] ?? __('lang_v1.custom_field', ['number' => 9]) }}
                                    </th>
                                    <th>
                                        {{ $custom_labels['contact']['custom_field_10'] ?? __('lang_v1.custom_field', ['number' => 10]) }}
                                    </th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 text-center footer-total">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td @if ($type == 'supplier') colspan="6" @elseif($type == 'customer') @if ($reward_enabled)
                                    colspan="9" @else colspan="8" @endif @endif>
                                        <strong>
                                            @lang('sale.total'):
                                        </strong>
                                    </td>
                                    <td class="footer_contact_due"></td>
                                    <td class="footer_contact_return_due"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    <div class="row align-items-center mt-2" id="contact_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="contact_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="contact_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade pay_contact_due_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

@endsection

@section('javascript')
    @if (!empty($api_key))
        <script>
            function initAutocomplete() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: -33.8688,
                        lng: 151.2195
                    },
                    zoom: 10,
                    mapTypeId: 'roadmap'
                });

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                        map.setCenter(initialLocation);
                    });
                }

                var input = document.getElementById('shipping_address');
                var searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                map.addListener('bounds_changed', function () {
                    searchBox.setBounds(map.getBounds());
                });

                var markers = [];
                searchBox.addListener('places_changed', function () {
                    var places = searchBox.getPlaces();
                    if (places.length == 0) {
                        return;
                    }
                    markers.forEach(function (marker) {
                        marker.setMap(null);
                    });
                    markers = [];
                    var bounds = new google.maps.LatLngBounds();
                    places.forEach(function (place) {
                        if (!place.geometry) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        var icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25)
                        };
                        markers.push(new google.maps.Marker({
                            map: map,
                            icon: icon,
                            title: place.name,
                            position: place.geometry.location
                        }));
                        var lat_long = [place.geometry.location.lat(), place.geometry.location.lng()]
                        $('#position').val(lat_long);
                        if (place.geometry.viewport) {
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&libraries=places" async defer></script>
        <script type="text/javascript">
            $(document).on('shown.bs.modal', '.contact_modal', function (e) {
                initAutocomplete();
            });
        </script>
    @endif

    <script type="text/javascript">
        $(document).ready(function () {
            var destroyContactTable = function () {
                if (!window.jQuery || !$.fn || !$.fn.DataTable) {
                    return;
                }

                if ($.fn.DataTable.isDataTable('#contact_table')) {
                    $('#contact_table').DataTable().clear().destroy();
                    // Remove old tbody to avoid duplicate rows / reinit warnings.
                    $('#contact_table').find('tbody').remove();
                }
            };

            var contact_table_type = $('#contact_type').val();
            var columns = [
                { data: 'action', searchable: false, orderable: false },
                { data: 'contact_id', name: 'contact_id' },
                { data: 'supplier_business_name', name: 'supplier_business_name' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'tax_number', name: 'tax_number' },
            ];

            if (contact_table_type == 'customer') {
                columns.push({ data: 'credit_limit', name: 'credit_limit' });
            }

            columns.push(
                { data: 'pay_term', name: 'pay_term', searchable: false },
                { data: 'opening_balance', name: 'opening_balance', searchable: false },
                { data: 'balance', name: 'balance', searchable: false },
                { data: 'created_at', name: 'contacts.created_at' }
            );

            if (contact_table_type == 'customer' && $('#rp_col').length) {
                columns.push({ data: 'total_rp', name: 'total_rp', searchable: false });
            }

            if (contact_table_type == 'customer') {
                columns.push({ data: 'customer_group', name: 'cg.name' });
            }

            columns.push(
                { data: 'address', name: 'address', orderable: false },
                { data: 'mobile', name: 'mobile' },
                { data: 'due', searchable: false, orderable: false },
                { data: 'return_due', searchable: false, orderable: false },
                { data: 'custom_field1', name: 'custom_field1' },
                { data: 'custom_field2', name: 'custom_field2' },
                { data: 'custom_field3', name: 'custom_field3' },
                { data: 'custom_field4', name: 'custom_field4' },
                { data: 'custom_field5', name: 'custom_field5' },
                { data: 'custom_field6', name: 'custom_field6' },
                { data: 'custom_field7', name: 'custom_field7' },
                { data: 'custom_field8', name: 'custom_field8' },
                { data: 'custom_field9', name: 'custom_field9' },
                { data: 'custom_field10', name: 'custom_field10' }
            );

            // If this script is evaluated more than once (e.g. via partial reload),
            // make sure the table is not initialized twice.
            destroyContactTable();

            contact_table = $('#contact_table').DataTable({
                retrieve: true,
                processing: true,
                serverSide: true,
                fixedHeader: false,
                scrollX: true,
                scrollCollapse: true,
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
                "ajax": {
                    url: "{{ route('ai-template.contacts.index') }}",
                    data: function (d) {
                        d.type = $('#contact_type').val();
                        d.has_sell_due = $('#has_sell_due').is(':checked');
                        d.has_sell_return = $('#has_sell_return').is(':checked');
                        d.has_purchase_due = $('#has_purchase_due').is(':checked');
                        d.has_purchase_return = $('#has_purchase_return').is(':checked');
                        d.has_advance_balance = $('#has_advance_balance').is(':checked');
                        d.has_opening_balance = $('#has_opening_balance').is(':checked');
                        d.has_no_sell_from = $('#has_no_sell_from').val();
                        d.customer_group_id = $('#cg_filter').val();
                        d.assigned_to = $('#assigned_to').val();
                        d.contact_status = $('#status_filter').val();
                        d = __datatable_ajax_callback(d);
                    }
                },
                aaSorting: [[1, 'desc']],
                columns: columns,
                fnDrawCallback: function (oSettings) {
                    __currency_convert_recursively($('#contact_table'));
                },
                "footerCallback": function (row, data, start, end, display) {
                    var footer_contact_due = 0;
                    var footer_contact_return_due = 0;
                    for (var r in data) {
                        footer_contact_due += $(data[r].due).data('orig-value') ? parseFloat($(data[r].due).data('orig-value')) : 0;
                        footer_contact_return_due += $(data[r].return_due).data('orig-value') ? parseFloat($(data[r].return_due).data('orig-value')) : 0;
                    }
                    $('.footer_contact_due').html(__currency_trans_from_en(footer_contact_due));
                    $('.footer_contact_return_due').html(__currency_trans_from_en(footer_contact_return_due));
                },
                initComplete: function () {
                    var relocate = function () {
                        var $wrapper = $('#contact_table_wrapper');
                        if ($wrapper.length < 1) return;

                        var $length = $wrapper.find('.dataTables_length');
                        var $buttons = $wrapper.find('.dt-buttons');
                        var $filter = $wrapper.find('.dataTables_filter');
                        var $info = $wrapper.find('.dataTables_info');
                        var $paginate = $wrapper.find('.dataTables_paginate');

                        if ($length.length) $('#contact_dt_length').empty().append($length);
                        if ($buttons.length) $('#contact_dt_buttons').empty().append($buttons);
                        if ($filter.length) $('#contact_dt_filter').empty().append($filter);
                        if ($info.length) $('#contact_dt_info').empty().append($info);
                        if ($paginate.length) $('#contact_dt_paginate').empty().append($paginate);
                    };

                    relocate();
                    this.api().on('draw.dt', function () {
                        relocate();
                    });
                }
            });

            window.contact_table = contact_table;

            $(document)
                .off(
                    'change.vihoContacts',
                    '#has_sell_due, #has_sell_return, #has_purchase_due, #has_purchase_return, #has_advance_balance, #has_opening_balance, #has_no_sell_from, #cg_filter, #status_filter, #assigned_to'
                )
                .on(
                    'change.vihoContacts',
                    '#has_sell_due, #has_sell_return, #has_purchase_due, #has_purchase_return, #has_advance_balance, #has_opening_balance, #has_no_sell_from, #cg_filter, #status_filter, #assigned_to',
                    function () {
                        contact_table.ajax.reload();
                    }
                );
        });
    </script>
@endsection

@push('styles')
    <style>
        #contact_table_wrapper {
            width: 100% !important;
            display: block !important;
            overflow-x: visible !important;
        }

        #contact_table_wrapper .dataTables_scroll {
            clear: both;
        }

        #contact_table_wrapper .dataTables_scrollBody {
            overflow-x: auto !important;
        }

        #contact_table {
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

        /* Horizontal scroll under table */
        #contact_table_wrapper .dataTables_scrollBody {
            display: block !important;
            width: 100% !important;
            overflow-x: auto !important;
        }

        #contact_table {
            width: 100% !important;
            margin: 0 !important;
            display: table !important;
        }

        /* Ensure the DataTables scroll container allows the horizontal scrollbar to show */
        .dataTables_wrapper .dataTables_scroll {
            clear: both;
        }

        .dataTables_scrollBody::-webkit-scrollbar {
            height: 8px;
        }

        .dataTables_scrollBody::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .dataTables_scrollBody::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
            background: #bbb;
        }

        /* Keep action column compact (prevents wrapping shifting columns) */
        #contact_table td:first-child,
        #contact_table th:first-child {
            white-space: nowrap !important;
            width: 100px !important;
        }

        /* Move checkbox and text to the right side of the label */
        .checkbox-label-right {
            display: block;
            width: 100%;
            cursor: pointer;
        }

        .checkbox-label-right::after {
            content: "";
            display: table;
            clear: both;
        }

        .checkbox-label-right strong {
            float: left;
        }

        .checkbox-label-right input.input-icheck[type="checkbox"] {
            float: right;
            position: static !important;
            opacity: 1 !important;
            width: auto !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            pointer-events: auto !important;
            display: inline-block !important;
        }
    </style>
@endpush