<script type="text/javascript">
    $(document).ready(function () {

        // Destroy existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#sell_table')) {
            $('#sell_table').DataTable().destroy();
        }

        //Date range as a button
        $('#sell_list_filter_date_range').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                sell_table.ajax.reload();
            }
        );
        $('#sell_list_filter_date_range').on('cancel.daterangepicker', function (ev, picker) {
            $('#sell_list_filter_date_range').val('');
            sell_table.ajax.reload();
        });

        $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status, #created_by, #sales_cmsn_agnt, #service_staffs, #shipping_status', function () {
            sell_table.ajax.reload();
        });

        sell_table = $('#sell_table').DataTable({
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
            fixedHeader: false,
            aaSorting: [[1, 'desc']],
            scrollY: "75vh",
            scrollX: true,
            scrollCollapse: true,
            "ajax": {
                "url": "/sells",
                "data": function (d) {
                    if ($('#sell_list_filter_date_range').val()) {
                        var start = $('#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        var end = $('#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                        d.start_date = start;
                        d.end_date = end;
                    }
                    if ($('#is_direct_sale').length) {
                        d.is_direct_sale = $('#is_direct_sale').val();
                    }

                    if ($('#sell_list_filter_location_id').length) {
                        d.location_id = $('#sell_list_filter_location_id').val();
                    }
                    d.customer_id = $('#sell_list_filter_customer_id').val();

                    if ($('#sell_list_filter_payment_status').length) {
                        d.payment_status = $('#sell_list_filter_payment_status').val();
                    }
                    if ($('#created_by').length) {
                        d.created_by = $('#created_by').val();
                    }
                    if ($('#sales_cmsn_agnt').length) {
                        d.sales_cmsn_agnt = $('#sales_cmsn_agnt').val();
                    }
                    if ($('#service_staffs').length) {
                        d.service_staffs = $('#service_staffs').val();
                    }

                    if ($('#shipping_status').length) {
                        d.shipping_status = $('#shipping_status').val();
                    }

                    if ($('#only_subscriptions').length && $('#only_subscriptions').is(':checked')) {
                        d.only_subscriptions = 1;
                    }

                    d = __datatable_ajax_callback(d);
                }
            },
            columns: [
                { data: 'action', name: 'action', orderable: false, "searchable": false },
                { data: 'transaction_date', name: 'transaction_date' },
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'conatct_name', name: 'conatct_name' },
                { data: 'mobile', name: 'contacts.mobile' },
                { data: 'business_location', name: 'bl.name' },
                { data: 'payment_status', name: 'payment_status' },
                { data: 'payment_methods', orderable: false, "searchable": false },
                { data: 'final_total', name: 'final_total' },
                { data: 'total_paid', name: 'total_paid', "searchable": false },
                { data: 'total_remaining', name: 'total_remaining' },
                { data: 'return_due', orderable: false, "searchable": false },
                { data: 'shipping_status', name: 'shipping_status' },
                { data: 'total_items', name: 'total_items', "searchable": false },
                { data: 'types_of_service_name', name: 'tos.name', @if(empty($is_types_service_enabled)) visible: false @endif},
            { data: 'service_custom_field_1', name: 'service_custom_field_1', @if(empty($is_types_service_enabled)) visible: false @endif},
        { data: 'added_by', name: 'u.first_name' },
        { data: 'additional_notes', name: 'additional_notes' },
        { data: 'staff_note', name: 'staff_note' },
        { data: 'shipping_details', name: 'shipping_details' },
        { data: 'table_name', name: 'tables.name', @if(empty($is_tables_enabled)) visible: false @endif },
        { data: 'waiter', name: 'ss.first_name', @if(empty($is_service_staff_enabled)) visible: false @endif }
        ],
        "fnDrawCallback": function (oSettings) {
            __currency_convert_recursively($('#sell_table'));
        },
        "footerCallback": function (row, data, start, end, display) {
            var footer_sale_total = 0;
            var footer_total_paid = 0;
            var footer_total_remaining = 0;
            var footer_total_sell_return_due = 0;
            for (var r in data) {
                footer_sale_total += $(data[r].final_total).data('orig-value') ? parseFloat($(data[r].final_total).data('orig-value')) : 0;
                footer_total_paid += $(data[r].total_paid).data('orig-value') ? parseFloat($(data[r].total_paid).data('orig-value')) : 0;
                footer_total_remaining += $(data[r].total_remaining).data('orig-value') ? parseFloat($(data[r].total_remaining).data('orig-value')) : 0;
                footer_total_sell_return_due += $(data[r].return_due).find('.sell_return_due').data('orig-value') ? parseFloat($(data[r].return_due).find('.sell_return_due').data('orig-value')) : 0;
            }

            $('.footer_total_sell_return_due').html(__currency_trans_from_en(footer_total_sell_return_due));
            $('.footer_total_remaining').html(__currency_trans_from_en(footer_total_remaining));
            $('.footer_total_paid').html(__currency_trans_from_en(footer_total_paid));
            $('.footer_sale_total').html(__currency_trans_from_en(footer_sale_total));

            $('.footer_payment_status_count').html(__count_status(data, 'payment_status'));
            $('.service_type_count').html(__count_status(data, 'types_of_service_name'));
            $('.payment_method_count').html(__count_status(data, 'payment_methods'));
        },
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(6)').attr('class', 'clickable_td');
        },
        initComplete: function () {
            var relocate = function () {
                var $wrapper = $('#sell_table_wrapper');
                if ($wrapper.length < 1) return;

                var $length = $wrapper.find('.dataTables_length');
                var $buttons = $wrapper.find('.dt-buttons');
                var $filter = $wrapper.find('.dataTables_filter');
                var $info = $wrapper.find('.dataTables_info');
                var $paginate = $wrapper.find('.dataTables_paginate');

                if ($length.length) $('#sell_dt_length').empty().append($length);
                if ($buttons.length) $('#sell_dt_buttons').empty().append($buttons);
                if ($filter.length) $('#sell_dt_filter').empty().append($filter);
                if ($info.length) $('#sell_dt_info').empty().append($info);
                if ($paginate.length) $('#sell_dt_paginate').empty().append($paginate);
            };

            relocate();
            var api = this.api();
            api.on('draw.dt', function () {
                relocate();
            });
        }
    });

    $('#only_subscriptions').on('ifChanged', function (event) {
        sell_table.ajax.reload();
    });
});

</script>