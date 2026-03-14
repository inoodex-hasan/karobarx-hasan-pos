@extends('templates.viho.layout')
@section('title', __('report.profit_loss'))

@push('styles')
    <style>
        .print_section { display: none; }
        @media print {
            .print_section { display: block !important; }
            .no-print { display: none !important; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('report.profit_loss')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="print_section">
                        <h2>{{ session()->get('business.name') }} - @lang('report.profit_loss')</h2>
                    </div>

                    <div class="row no-print">
                        <div class="col-md-4 col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon bg-light-blue"><i class="fa fa-map-marker"></i></span>
                                <select class="form-control select2" id="profit_loss_location_filter">
                                    @foreach ($business_locations as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon bg-yellow"><i class="fa fa-calendar"></i></span>
                                <button type="button" class="btn btn-primary btn-sm" id="profit_loss_date_filter">
                                    <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <button type="button" class="btn btn-success pull-right" id="print_profit_loss">
                                <i class="fa fa-print"></i> @lang('messages.print')
                            </button>
                        </div>
                    </div>

                    <hr class="no-print">

                    <div id="profit_loss_div"></div>

                    <div class="row no-print">
                        <div class="col-sm-12 mb-2">
                            <button class="btn btn-primary pull-right" aria-label="Print" onclick="window.print();">
                                <i class="fa fa-print"></i> @lang('messages.print')
                            </button>
                        </div>
                    </div>

                    <div class="row no-print">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Custom Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#profit_by_products" data-toggle="tab" aria-expanded="true"><i class="fa fa-cubes" aria-hidden="true"></i> @lang('lang_v1.profit_by_products')</a>
                                        </li>
                                        <li>
                                            <a href="#profit_by_categories" data-toggle="tab" aria-expanded="true"><i class="fa fa-tags" aria-hidden="true"></i> @lang('lang_v1.profit_by_categories')</a>
                                        </li>
                                        <li>
                                            <a href="#profit_by_brands" data-toggle="tab" aria-expanded="true"><i class="fa fa-diamond" aria-hidden="true"></i> @lang('lang_v1.profit_by_brands')</a>
                                        </li>
                                        <li>
                                            <a href="#profit_by_locations" data-toggle="tab" aria-expanded="true"><i class="fa fa-map-marker" aria-hidden="true"></i> @lang('lang_v1.profit_by_locations')</a>
                                        </li>
                                        <li>
                                            <a href="#profit_by_invoice" data-toggle="tab" aria-expanded="true"><i class="fa fa-file-alt" aria-hidden="true"></i> @lang('lang_v1.profit_by_invoice')</a>
                                        </li>
                                        <li>
                                            <a href="#profit_by_date" data-toggle="tab" aria-expanded="true"><i class="fa fa-calendar" aria-hidden="true"></i> @lang('lang_v1.profit_by_date')</a>
                                        </li>
                                        <li>
                                            <a href="#profit_by_customer" data-toggle="tab" aria-expanded="true"><i class="fa fa-user" aria-hidden="true"></i> @lang('lang_v1.profit_by_customer')</a>
                                        </li>
                                        <li>
                                            <a href="#profit_by_day" data-toggle="tab" aria-expanded="true"><i class="fa fa-calendar" aria-hidden="true"></i> @lang('lang_v1.profit_by_day')</a>
                                        </li>
                                        @if(session('business.enable_service_staff') == 1)
                                        <li>
                                            <a href="#profit_by_service_staff" data-toggle="tab" aria-expanded="true"><i class="fa fa-user-secret" aria-hidden="true"></i> @lang('lang_v1.profit_by_service_staff')</a>
                                        </li>
                                        @endif
                                    </ul>

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="profit_by_products">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="profit_by_products_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('sale.product')</th>
                                                            <th>@lang('lang_v1.gross_profit')</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr class="bg-gray font-17 footer-total">
                                                            <td><strong>@lang('sale.total'):</strong></td>
                                                            <td class="footer_total"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="profit_by_categories">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="profit_by_categories_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('product.category')</th>
                                                            <th>@lang('lang_v1.gross_profit')</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr class="bg-gray font-17 footer-total">
                                                            <td><strong>@lang('sale.total'):</strong></td>
                                                            <td class="footer_total"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="profit_by_brands">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="profit_by_brands_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('product.brand')</th>
                                                            <th>@lang('lang_v1.gross_profit')</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr class="bg-gray font-17 footer-total">
                                                            <td><strong>@lang('sale.total'):</strong></td>
                                                            <td class="footer_total"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="profit_by_locations">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="profit_by_locations_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('sale.location')</th>
                                                            <th>@lang('lang_v1.gross_profit')</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr class="bg-gray font-17 footer-total">
                                                            <td><strong>@lang('sale.total'):</strong></td>
                                                            <td class="footer_total"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="profit_by_invoice">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="profit_by_invoice_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('sale.invoice_no')</th>
                                                            <th>@lang('lang_v1.gross_profit')</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr class="bg-gray font-17 footer-total">
                                                            <td><strong>@lang('sale.total'):</strong></td>
                                                            <td class="footer_total"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="profit_by_date">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="profit_by_date_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('sale.date')</th>
                                                            <th>@lang('lang_v1.gross_profit')</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr class="bg-gray font-17 footer-total">
                                                            <td><strong>@lang('sale.total'):</strong></td>
                                                            <td class="footer_total"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="profit_by_customer">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="profit_by_customer_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('sale.customer')</th>
                                                            <th>@lang('lang_v1.gross_profit')</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr class="bg-gray font-17 footer-total">
                                                            <td><strong>@lang('sale.total'):</strong></td>
                                                            <td class="footer_total"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="profit_by_day">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="profit_by_day_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('sale.date')</th>
                                                            <th>@lang('lang_v1.gross_profit')</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr class="bg-gray font-17 footer-total">
                                                            <td><strong>@lang('sale.total'):</strong></td>
                                                            <td class="footer_total"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        @if(session('business.enable_service_staff') == 1)
                                        <div class="tab-pane" id="profit_by_service_staff">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="profit_by_service_staff_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('lang_v1.service_staff')</th>
                                                            <th>@lang('lang_v1.gross_profit')</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr class="bg-gray font-17 footer-total">
                                                            <td><strong>@lang('sale.total'):</strong></td>
                                                            <td class="footer_total"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Date range
            $('#profit_loss_date_filter').daterangepicker({
                ranges: dateRangeSettings.ranges,
                opens: 'left',
                locale: {
                    format: moment_date_format
                }
            }, function(start, end) {
                $('#profit_loss_date_filter').html(
                    '<i class="fa fa-calendar"></i> ' +
                    start.format(moment_date_format) + ' - ' +
                    end.format(moment_date_format)
                );
                get_profit_loss();
            });

            // Location filter
            $('#profit_loss_location_filter').change(function() {
                get_profit_loss();
            });

            // Print
            $('#print_profit_loss').click(function() {
                var divToPrint = document.getElementById('profit_loss_div');
                var newWin = window.open("");
                newWin.document.write('<h3>{{ session()->get('business.name') }} - @lang('report.profit_loss')</h3>');
                newWin.document.write(divToPrint.outerHTML);
                newWin.document.write('</body></html>');
                newWin.document.close();
                newWin.focus();
                setTimeout(function() {
                    newWin.print();
                    newWin.close();
                }, 1000);
            });

            function get_profit_loss() {
                var location_id = $('#profit_loss_location_filter').val();
                var date_range = $('#profit_loss_date_filter').data('daterangepicker');
                
                if (!date_range) {
                    return;
                }
                
                $.ajax({
                    url: '/reports/profit-loss',
                    data: {
                        location_id: location_id,
                        start_date: date_range.startDate.format('YYYY-MM-DD'),
                        end_date: date_range.endDate.format('YYYY-MM-DD')
                    },
                    dataType: 'html',
                    success: function(result) {
                        $('#profit_loss_div').html(result);
                        // Trigger tab load after main data loads
                        setTimeout(function() {
                            $('.nav-tabs a:first').tab('show');
                        }, 500);
                    }
                });
            }
            
            // Initial load - wait for daterangepicker to be ready
            setTimeout(function() {
                get_profit_loss();
            }, 500);
        });
    </script>
@endsection
