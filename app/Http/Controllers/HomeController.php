<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use App\{BusinessLocation, Currency, Media, Transaction, User, VariationLocationDetails};
use App\Charts\CommonChart;
use App\Utils\{BusinessUtil, ModuleUtil, ProductUtil, RestaurantUtil, TransactionUtil, Util};
use DB;
use Datatables;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $businessUtil;

    protected $transactionUtil;

    protected $moduleUtil;

    protected $commonUtil;

    protected $restUtil;
    protected $productUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        BusinessUtil $businessUtil,
        TransactionUtil $transactionUtil,
        ModuleUtil $moduleUtil,
        Util $commonUtil,
        RestaurantUtil $restUtil,
        ProductUtil $productUtil,
    ) {
        $this->businessUtil = $businessUtil;
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->commonUtil = $commonUtil;
        $this->restUtil = $restUtil;
        $this->productUtil = $productUtil;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->user_type == 'user_customer') {
            return redirect()->action([\Modules\Crm\Http\Controllers\DashboardController::class, 'index']);
        }

        $business_id = request()->session()->get('user.business_id');

        $is_admin = $this->businessUtil->is_admin(auth()->user());

        // Access layout_template from business object stored in session
        $business = session('business');
        $common_settings = !empty($business->common_settings) ? $business->common_settings : [];
        $layout_template = !empty($common_settings['layout_template']) ? $common_settings['layout_template'] : 'default';
        if ($layout_template === 'viho') {
            // Keep a clean separation: Viho pages live under /ai-template.
            // The /home endpoint is always the entry-point; when Viho is active we redirect into /ai-template/home.
            if (!request()->is('ai-template/*')) {
                return redirect()->to('/ai-template/home');
            }

            // Read directly from the static index.html for clean, exact HTML matching the original template.
            $viho_index_html = file_get_contents(resource_path('views/templates/viho/index.html'));

            $dashboard_body = '';
            if (preg_match('/<div class="container-fluid dashboard-default-sec">[\\s\\S]*?<!-- Container-fluid Ends-->/', $viho_index_html, $m)) {
                $dashboard_body = $m[0];
                $dashboard_body = str_replace('../assets/', asset('templates/viho/assets') . '/', $dashboard_body);

                // Viho demo dashboard uses "$" in a few places; switch to Bangladeshi Taka sign.
                $dashboard_body = str_replace('$', '৳', $dashboard_body);

                // Add stable ids so we can update specific widgets dynamically via JS.
                $dashboard_body = preg_replace(
                    '/<h5>([^<]*)<\\/h5>\\s*<p>\\s*our\\s+Annual\\s+income\\s*<\\/p>/i',
                    '<h5 id="viho_annual_income">$1</h5><p>Annual income</p>',
                    $dashboard_body,
                    1
                );
                $dashboard_body = preg_replace(
                    '/<p>\\s*annual\\s+income\\s*<\\/p>\\s*<a([^>]*)>\\s*<i([^>]*)><\\/i>\\s*([0-9\\.]+)%\\s*<\\/a>/i',
                    '<p>Annual income</p><a$1><i id="viho_annual_income_pct_icon"$2></i><span id="viho_annual_income_pct">$3%</span></a>',
                    $dashboard_body,
                    1
                );
                $dashboard_body = preg_replace(
                    '/<h5>([^<]*)<\\/h5>\\s*<p>\\s*our\\s+Annual\\s+losses\\s*<\\/p>/i',
                    '<h5 id="viho_annual_losses">$1</h5><p>Annual losses</p>',
                    $dashboard_body,
                    1
                );
                $dashboard_body = preg_replace(
                    '/<p>\\s*annual\\s+losses\\s*<\\/p>\\s*<a([^>]*)>\\s*<i([^>]*)><\\/i>\\s*([0-9\\.]+)%\\s*<\\/a>/i',
                    '<p>Annual losses</p><a$1><i id="viho_annual_losses_pct_icon"$2></i><span id="viho_annual_losses_pct">$3%</span></a>',
                    $dashboard_body,
                    1
                );

                // Growth overview: make the percentage text updatable
                $dashboard_body = preg_replace(
                    '/<h5>\\s*Growth\\s+Overview\\s*<\\/h5>\\s*<div class="center-content">\\s*<p class="d-flex align-items-center">\\s*<i([^>]*)><\\/i>\\s*([0-9\\.]+)%\\s*Growth\\s*<\\/p>/i',
                    '<h5>Growth Overview</h5><div class="center-content"><p class="d-flex align-items-center"><i id="viho_growth_pct_icon"$1></i><span id="viho_growth_pct">$2%</span>&nbsp;Growth</p>',
                    $dashboard_body,
                    1
                );

                // User activations: make the yearly number updatable
                $dashboard_body = preg_replace(
                    '/<h5>\\s*User\\s+Activations\\s*<\\/h5>\\s*<div class="center-content">\\s*<p>\\s*Yearly\\s+User\\s*([^<]*)<\\/p>/i',
                    '<h5>User Activations</h5><div class="center-content"><p>Yearly User <span id="viho_yearly_users">$1</span></p>',
                    $dashboard_body,
                    1
                );

                // Transaction widget: successful transaction count + total balance + percentage
                $dashboard_body = preg_replace(
                    '/<h5>\\s*Transaction\\s*<\\/h5>\\s*<div class="center-content">\\s*<p>\\s*([0-9,]+)\\s*Suceessfull\\s*Transaction\\s*<\\/p>/i',
                    '<h5>Transaction</h5><div class="center-content"><p><span id="viho_success_txn_count">$1</span> Successful Transaction</p>',
                    $dashboard_body,
                    1
                );
                $dashboard_body = preg_replace(
                    '/<div class="transaction-totalbal">\\s*<h2>\\s*([^<]*)\\s*<span class="ms-3">\\s*<a class="btn-arrow arrow-secondary"[^>]*>\\s*<i([^>]*)><\\/i>\\s*([0-9\\.]+)%\\s*<\\/a>\\s*<\\/span>\\s*<\\/h2>/i',
                    '<div class="transaction-totalbal"><h2><span id="viho_total_balance">$1</span> <span class="ms-3"><a class="btn-arrow arrow-secondary" href="javascript:void(0)"><i id="viho_total_balance_pct_icon"$2></i><span id="viho_total_balance_pct">$3%</span></a></span></h2>',
                    $dashboard_body,
                    1
                );

                // Personalize the greeting card
                $dashboard_body = str_replace('Wellcome Back, John!!', 'Welcome Back, ' . (auth()->user()->first_name ?? 'User') . '!', $dashboard_body);

                // Sales Overview: make the total amount and percentage updatable
                $dashboard_body = preg_replace(
                    '/<h5>\s*Sales\s+overview\s*<\/h5>\s*<div class="center-content">\s*<p[^>]*>\s*<span class="font-primary m-r-10 f-w-700">([^<]*)<\/span>\s*<i class="toprightarrow-primary fa fa-arrow-up m-r-10"><\/i>\s*([^<]*)%\s*More\s+than\s+last\s+year\s*<\/p>/i',
                    '<h5>Sales overview</h5><div class="center-content"><p class="d-sm-flex align-items-center"><span class="font-primary m-r-10 f-w-700" id="viho_sales_total">$1</span><i id="viho_sales_pct_icon" class="toprightarrow-primary fa fa-arrow-up m-r-10"></i><span id="viho_sales_pct">$2%</span> More than last year</p></div>',
                    $dashboard_body,
                    1
                );

                // Remove demo "view-html" code preview blocks (they show escaped HTML like &lt;...&gt; without Viho demo JS).
                $dashboard_body = preg_replace('/<div class="code-box-copy">[\\s\\S]*?<\\/div>\\s*<\\/div>/i', '</div>', $dashboard_body);
                $dashboard_body = preg_replace('/<div class="code-box-copy">[\\s\\S]*?<\\/div>/i', '', $dashboard_body);
                $dashboard_body = preg_replace('/<pre><code[^>]*>[\\s\\S]*?<\\/code><\\/pre>/i', '', $dashboard_body);
            }

            return view('templates.viho.home', compact('dashboard_body'));
        }

        if (!auth()->user()->can('dashboard.data')) {
            return view('home.index');
        }

        $fy = $this->businessUtil->getCurrentFinancialYear($business_id);

        $currency = Currency::where('id', request()->session()->get('business.currency_id'))->first();
        //ensure start date starts from at least 30 days before to get sells last 30 days
        $least_30_days = \Carbon::parse($fy['start'])->subDays(30)->format('Y-m-d');

        //get all sells
        $sells_this_fy = $this->transactionUtil->getSellsCurrentFy($business_id, $least_30_days, $fy['end']);

        $all_locations = BusinessLocation::forDropdown($business_id)->toArray();

        //Chart for sells last 30 days
        $labels = [];
        $all_sell_values = [];
        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = \Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;

            $labels[] = date('j M Y', strtotime($date));

            $total_sell_on_date = $sells_this_fy->where('date', $date)->sum('total_sells');

            if (!empty($total_sell_on_date)) {
                $all_sell_values[] = (float) $total_sell_on_date;
            } else {
                $all_sell_values[] = 0;
            }
        }

        //Group sells by location
        $location_sells = [];
        foreach ($all_locations as $loc_id => $loc_name) {
            $values = [];
            foreach ($dates as $date) {
                $total_sell_on_date_location = $sells_this_fy->where('date', $date)->where('location_id', $loc_id)->sum('total_sells');

                if (!empty($total_sell_on_date_location)) {
                    $values[] = (float) $total_sell_on_date_location;
                } else {
                    $values[] = 0;
                }
            }
            $location_sells[$loc_id]['loc_label'] = $loc_name;
            $location_sells[$loc_id]['values'] = $values;
        }

        $sells_chart_1 = new CommonChart;

        $sells_chart_1->labels($labels)
            ->options($this->__chartOptions(__(
                'home.total_sells',
                ['currency' => $currency->code]
            )));

        if (!empty($location_sells)) {
            foreach ($location_sells as $location_sell) {
                $sells_chart_1->dataset($location_sell['loc_label'], 'line', $location_sell['values']);
            }
        }

        if (count($all_locations) > 1) {
            $sells_chart_1->dataset(__('report.all_locations'), 'line', $all_sell_values);
        }

        $labels = [];
        $values = [];
        $date = strtotime($fy['start']);
        $last = date('m-Y', strtotime($fy['end']));
        $fy_months = [];
        do {
            $month_year = date('m-Y', $date);
            $fy_months[] = $month_year;

            $labels[] = \Carbon::createFromFormat('m-Y', $month_year)
                ->format('M-Y');
            $date = strtotime('+1 month', $date);

            $total_sell_in_month_year = $sells_this_fy->where('yearmonth', $month_year)->sum('total_sells');

            if (!empty($total_sell_in_month_year)) {
                $values[] = (float) $total_sell_in_month_year;
            } else {
                $values[] = 0;
            }
        } while ($month_year != $last);

        $fy_sells_by_location_data = [];

        foreach ($all_locations as $loc_id => $loc_name) {
            $values_data = [];
            foreach ($fy_months as $month) {
                $total_sell_in_month_year_location = $sells_this_fy->where('yearmonth', $month)->where('location_id', $loc_id)->sum('total_sells');

                if (!empty($total_sell_in_month_year_location)) {
                    $values_data[] = (float) $total_sell_in_month_year_location;
                } else {
                    $values_data[] = 0;
                }
            }
            $fy_sells_by_location_data[$loc_id]['loc_label'] = $loc_name;
            $fy_sells_by_location_data[$loc_id]['values'] = $values_data;
        }

        $sells_chart_2 = new CommonChart;
        $sells_chart_2->labels($labels)
            ->options($this->__chartOptions(__(
                'home.total_sells',
                ['currency' => $currency->code]
            )));
        if (!empty($fy_sells_by_location_data)) {
            foreach ($fy_sells_by_location_data as $location_sell) {
                $sells_chart_2->dataset($location_sell['loc_label'], 'line', $location_sell['values']);
            }
        }
        if (count($all_locations) > 1) {
            $sells_chart_2->dataset(__('report.all_locations'), 'line', $values);
        }

        //Get Dashboard widgets from module
        $module_widgets = $this->moduleUtil->getModuleData('dashboard_widget');

        $widgets = [];

        foreach ($module_widgets as $widget_array) {
            if (!empty($widget_array['position'])) {
                $widgets[$widget_array['position']][] = $widget_array['widget'];
            }
        }

        $common_settings = !empty(session('business.common_settings')) ? session('business.common_settings') : [];


        return view('home.index', compact('sells_chart_1', 'sells_chart_2', 'widgets', 'all_locations', 'common_settings', 'is_admin'));
    }

    /**
     * Retrieves purchase and sell details for a given time period.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTotals()
    {
        if (request()->ajax()) {
            $start = request()->start;
            $end = request()->end;
            $location_id = request()->location_id;
            $business_id = request()->session()->get('user.business_id');

            // get user id parameter
            $created_by = request()->user_id;

            $purchase_details = $this->transactionUtil->getPurchaseTotals($business_id, $start, $end, $location_id, $created_by);

            $sell_details = $this->transactionUtil->getSellTotals($business_id, $start, $end, $location_id, $created_by);

            $total_ledger_discount = $this->transactionUtil->getTotalLedgerDiscount($business_id, $start, $end);

            $purchase_details['purchase_due'] = $purchase_details['purchase_due'] - $total_ledger_discount['total_purchase_discount'];

            $transaction_types = [
                'purchase_return',
                'sell_return',
                'expense',
            ];

            $transaction_totals = $this->transactionUtil->getTransactionTotals(
                $business_id,
                $transaction_types,
                $start,
                $end,
                $location_id,
                $created_by
            );

            $total_purchase_inc_tax = !empty($purchase_details['total_purchase_inc_tax']) ? $purchase_details['total_purchase_inc_tax'] : 0;
            $total_purchase_return_inc_tax = $transaction_totals['total_purchase_return_inc_tax'];

            $output = $purchase_details;
            $output['total_purchase'] = $total_purchase_inc_tax;
            $output['total_purchase_return'] = $total_purchase_return_inc_tax;
            $output['total_purchase_return_paid'] = $this->transactionUtil->getTotalPurchaseReturnPaid($business_id, $start, $end, $location_id);

            $total_sell_inc_tax = !empty($sell_details['total_sell_inc_tax']) ? $sell_details['total_sell_inc_tax'] : 0;
            $total_sell_return_inc_tax = !empty($transaction_totals['total_sell_return_inc_tax']) ? $transaction_totals['total_sell_return_inc_tax'] : 0;
            $output['total_sell_return_paid'] = $this->transactionUtil->getTotalSellReturnPaid($business_id, $start, $end, $location_id);

            $output['total_sell'] = $total_sell_inc_tax;
            $output['total_sell_return'] = $total_sell_return_inc_tax;

            $output['invoice_due'] = $sell_details['invoice_due'] - $total_ledger_discount['total_sell_discount'];
            $output['total_expense'] = $transaction_totals['total_expense'];

            //NET = TOTAL SALES - INVOICE DUE - EXPENSE
            $output['net'] = $output['total_sell'] - $output['invoice_due'] - $output['total_expense'];

            return $output;
        }
    }

    /**
     * Viho-only: sales overview series for dashboard chart (last 30 days).
     */
    public function getVihoSalesOverview()
    {
        if (!auth()->user()->can('dashboard.data')) {
            abort(403, 'Unauthorized action.');
        }

        if (!request()->ajax()) {
            abort(404);
        }

        $business_id = request()->session()->get('user.business_id');

        // Reuse the same logic as default dashboard: sells for last 30 days within current FY.
        $fy = $this->businessUtil->getCurrentFinancialYear($business_id);
        $least_30_days = Carbon::parse($fy['start'])->subDays(30)->format('Y-m-d');
        $sells_this_fy = $this->transactionUtil->getSellsCurrentFy($business_id, $least_30_days, $fy['end']);

        $labels = [];
        $values = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('j M');
            $values[] = (float) $sells_this_fy->where('date', $date)->sum('total_sells');
        }

        // Calculate total sales for current period and previous period for percentage
        $total_sales = array_sum($values);

        // Previous 30 days
        $prev_values = [];
        for ($i = 59; $i >= 30; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $prev_values[] = (float) $sells_this_fy->where('date', $date)->sum('total_sells');
        }
        $prev_total_sales = array_sum($prev_values);

        // Calculate percentage change
        $sales_pct = null;
        if ($prev_total_sales > 0) {
            $sales_pct = (($total_sales - $prev_total_sales) / $prev_total_sales) * 100;
        }

        return response()->json([
            'labels' => $labels,
            'series' => [
                [
                    'name' => __('home.total_sells'),
                    'data' => $values,
                ],
            ],
            'total_sales' => $total_sales,
            'sales_pct' => $sales_pct,
        ]);
    }

    /**
     * Viho-only: annual totals for dashboard cards (current financial year).
     * Returns the same keys as /home/get-totals (subset), so frontend can reuse formatting helpers.
     */
    public function getVihoAnnualTotals()
    {
        if (!auth()->user()->can('dashboard.data')) {
            abort(403, 'Unauthorized action.');
        }

        if (!request()->ajax()) {
            abort(404);
        }

        $business_id = request()->session()->get('user.business_id');
        $fy = $this->businessUtil->getCurrentFinancialYear($business_id);

        $start = $fy['start'];
        $end = $fy['end'];

        $prev_start = Carbon::parse($start)->subYear()->format('Y-m-d');
        $prev_end = Carbon::parse($end)->subYear()->format('Y-m-d');
        $location_id = request()->get('location_id');
        $created_by = request()->get('user_id');

        $sell_details = $this->transactionUtil->getSellTotals($business_id, $start, $end, $location_id, $created_by);
        $prev_sell_details = $this->transactionUtil->getSellTotals($business_id, $prev_start, $prev_end, $location_id, $created_by);
        $transaction_totals = $this->transactionUtil->getTransactionTotals(
            $business_id,
            ['expense'],
            $start,
            $end,
            $location_id,
            $created_by
        );
        $prev_transaction_totals = $this->transactionUtil->getTransactionTotals(
            $business_id,
            ['expense'],
            $prev_start,
            $prev_end,
            $location_id,
            $created_by
        );

        $income = (float) data_get($sell_details, 'total_sell', 0);
        $prev_income = (float) data_get($prev_sell_details, 'total_sell', 0);
        $losses = (float) data_get($transaction_totals, 'total_expense', 0);
        $prev_losses = (float) data_get($prev_transaction_totals, 'total_expense', 0);

        $pct = function (float $current, float $previous): ?float {
            if ($previous == 0.0) {
                return null;
            }
            return (($current - $previous) / $previous) * 100;
        };

        return response()->json([
            'total_sell' => $income,
            'total_expense' => $losses,
            'income_pct' => $pct($income, $prev_income),
            'losses_pct' => $pct($losses, $prev_losses),
            'start' => $start,
            'end' => $end,
        ]);
    }

    /**
     * Viho-only: growth overview chart + percentage (last 30 days vs previous 30 days).
     */
    public function getVihoGrowthOverview()
    {
        if (!auth()->user()->can('dashboard.data')) {
            abort(403, 'Unauthorized action.');
        }

        if (!request()->ajax()) {
            abort(404);
        }

        $business_id = request()->session()->get('user.business_id');
        $fy = $this->businessUtil->getCurrentFinancialYear($business_id);
        $start = $fy['start'];
        $end = $fy['end'];

        // 1. Calculate Growth Percentage (last 30 days vs previous 30 days sales)
        $least_60_days = Carbon::parse($start)->subDays(60)->format('Y-m-d');
        $sells_this_fy = $this->transactionUtil->getSellsCurrentFy($business_id, $least_60_days, $end);

        $current_30_total = 0.0;
        $previous_30_total = 0.0;

        for ($i = 59; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $total = (float) $sells_this_fy->where('date', $date)->sum('total_sells');
            if ($i <= 29) {
                $current_30_total += $total;
            } else {
                $previous_30_total += $total;
            }
        }

        $growth_pct = null;
        if ($previous_30_total != 0.0) {
            $growth_pct = (($current_30_total - $previous_30_total) / $previous_30_total) * 100;
        }

        // 2. Fetch the 5 requested metrics for the Radial chart (Current FY)
        $location_id = request()->get('location_id');
        $created_by = request()->get('user_id');

        $sell_details = $this->transactionUtil->getSellTotals($business_id, $start, $end, $location_id, $created_by);
        $purchase_details = $this->transactionUtil->getPurchaseTotals($business_id, $start, $end, $location_id, $created_by);
        $total_ledger_discount = $this->transactionUtil->getTotalLedgerDiscount($business_id, $start, $end);

        $transaction_types = ['expense'];
        $transaction_totals = $this->transactionUtil->getTransactionTotals($business_id, $transaction_types, $start, $end, $location_id, $created_by);

        $total_sale = (float) ($sell_details['total_sell_inc_tax'] ?? 0);
        $total_purchase = (float) ($purchase_details['total_purchase_inc_tax'] ?? 0);
        $invoice_due = (float) ($sell_details['invoice_due'] ?? 0) - (float) ($total_ledger_discount['total_sell_discount'] ?? 0);
        $purchase_due = (float) ($purchase_details['purchase_due'] ?? 0) - (float) ($total_ledger_discount['total_purchase_discount'] ?? 0);
        $total_expense = (float) ($transaction_totals['total_expense'] ?? 0);

        // Radial bars usually show relative values. We'll return absolute values and the frontend will handle scaling/labels.
        $radial_values = [
            $total_sale,
            $total_purchase,
            $invoice_due,
            $purchase_due,
            $total_expense
        ];

        $radial_labels = [
            'Total Sale',
            'Total Purchase',
            'Invoice Due',
            'Purchase Due',
            'Total Expense'
        ];

        return response()->json([
            'growth_pct' => $growth_pct,
            'radial_series' => $radial_values,
            'radial_labels' => $radial_labels,
            // Keep legacy keys if any other part of the UI depends on them (unlikely for Viho-only)
            'series' => $radial_values,
            'labels' => $radial_labels
        ]);
    }

    /**
     * Viho-only: user activations (new back-office users per month) for the current FY.
     */
    public function getVihoUserActivations()
    {
        if (!auth()->user()->can('dashboard.data')) {
            abort(403, 'Unauthorized action.');
        }

        if (!request()->ajax()) {
            abort(404);
        }

        $business_id = request()->session()->get('user.business_id');
        $fy = $this->businessUtil->getCurrentFinancialYear($business_id);

        $start = Carbon::parse($fy['start'])->startOfMonth();
        $end = Carbon::parse($fy['end'])->endOfMonth();

        $users = User::where('business_id', $business_id)
            ->where('user_type', '!=', 'user_customer')
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->select([DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'), DB::raw('COUNT(*) as c')])
            ->groupBy('ym')
            ->pluck('c', 'ym')
            ->toArray();

        $labels = [];
        $values = [];
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $ym = $cursor->format('Y-m');
            $labels[] = $cursor->format('M Y');
            $values[] = (int) ($users[$ym] ?? 0);
            $cursor->addMonth();
        }

        return response()->json([
            'labels' => $labels,
            'series' => [
                [
                    'name' => __('user.users'),
                    'data' => $values,
                ],
            ],
            'yearly_total' => array_sum($values),
        ]);
    }

    /**
     * Viho-only: transaction widget (successful transactions count, total balance, % change, and chart).
     */
    public function getVihoTransactions()
    {
        if (!auth()->user()->can('dashboard.data')) {
            abort(403, 'Unauthorized action.');
        }

        if (!request()->ajax()) {
            abort(404);
        }

        $business_id = request()->session()->get('user.business_id');
        $fy = $this->businessUtil->getCurrentFinancialYear($business_id);

        $start = $fy['start'];
        $end = $fy['end'];
        $prev_start = Carbon::parse($start)->subYear()->format('Y-m-d');
        $prev_end = Carbon::parse($end)->subYear()->format('Y-m-d');

        // Successful = final sell transactions (POS invoices)
        $success_count = Transaction::where('business_id', $business_id)
            ->where('type', 'sell')
            ->where('status', 'final')
            ->whereBetween('transaction_date', [$start, $end])
            ->count();

        $prev_success_count = Transaction::where('business_id', $business_id)
            ->where('type', 'sell')
            ->where('status', 'final')
            ->whereBetween('transaction_date', [$prev_start, $prev_end])
            ->count();

        // Balance = total sell - total expense (FY)
        $sell_details = $this->transactionUtil->getSellTotals($business_id, $start, $end);
        $expense_totals = $this->transactionUtil->getTransactionTotals($business_id, ['expense'], $start, $end);
        $balance = (float) data_get($sell_details, 'total_sell_inc_tax', 0) - (float) data_get($expense_totals, 'total_expense', 0);

        $prev_sell_details = $this->transactionUtil->getSellTotals($business_id, $prev_start, $prev_end);
        $prev_expense_totals = $this->transactionUtil->getTransactionTotals($business_id, ['expense'], $prev_start, $prev_end);
        $prev_balance = (float) data_get($prev_sell_details, 'total_sell_inc_tax', 0) - (float) data_get($prev_expense_totals, 'total_expense', 0);

        $pct = function (float $current, float $previous): ?float {
            if ($previous == 0.0) {
                return null;
            }
            return (($current - $previous) / $previous) * 100;
        };

        // Chart: last 30 days sell totals
        $least_30_days = Carbon::parse($start)->subDays(30)->format('Y-m-d');
        $sells_this_fy = $this->transactionUtil->getSellsCurrentFy($business_id, $least_30_days, $end);

        $labels = [];
        $values = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('j M');
            $values[] = (float) $sells_this_fy->where('date', $date)->sum('total_sells');
        }

        return response()->json([
            'success_count' => $success_count,
            'success_count_pct' => $pct((float) $success_count, (float) $prev_success_count),
            'balance' => $balance,
            'balance_pct' => $pct($balance, $prev_balance),
            'labels' => $labels,
            'series' => [
                [
                    'name' => __('home.total_sell'),
                    'data' => $values,
                ],
            ],
        ]);
    }

    /**
     * Retrieves sell products whose available quntity is less than alert quntity.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductStockAlert()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $permitted_locations = auth()->user()->permitted_locations();
            $products = $this->productUtil->getProductAlert($business_id, $permitted_locations);

            return Datatables::of($products)
                ->editColumn('product', function ($row) {
                    if ($row->type == 'single') {
                        return $row->product . ' (' . $row->sku . ')';
                    } else {
                        return $row->product . ' - ' . $row->product_variation . ' - ' . $row->variation . ' (' . $row->sub_sku . ')';
                    }
                })
                ->editColumn('stock', function ($row) {
                    $stock = $row->stock ? $row->stock : 0;

                    return '<span data-is_quantity="true" data-orig-value="' . (float) $stock . '" class="display_currency" data-currency_symbol=false>' . (float) $stock . '</span> ' . $row->unit;
                })
                ->removeColumn('sku')
                ->removeColumn('sub_sku')
                ->removeColumn('unit')
                ->removeColumn('type')
                ->removeColumn('product_variation')
                ->removeColumn('variation')
                ->rawColumns([2])
                ->make(false);
        }
    }

    /**
     * Retrieves payment dues for the purchases.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchasePaymentDues()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $today = \Carbon::now()->format('Y-m-d H:i:s');

            $query = Transaction::join(
                'contacts as c',
                'transactions.contact_id',
                '=',
                'c.id'
            )
                ->leftJoin(
                    'transaction_payments as tp',
                    'transactions.id',
                    '=',
                    'tp.transaction_id'
                )
                ->where('transactions.business_id', $business_id)
                ->where('transactions.type', 'purchase')
                ->where('transactions.payment_status', '!=', 'paid')
                ->whereRaw("DATEDIFF( DATE_ADD( transaction_date, INTERVAL IF(transactions.pay_term_type = 'days', transactions.pay_term_number, 30 * transactions.pay_term_number) DAY), '$today') <= 7");

            //Check for permitted locations of a user
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('transactions.location_id', $permitted_locations);
            }

            if (!empty(request()->input('location_id'))) {
                $query->where('transactions.location_id', request()->input('location_id'));
            }

            $dues = $query->select(
                'transactions.id as id',
                'c.name as supplier',
                'c.supplier_business_name',
                'ref_no',
                'final_total',
                DB::raw('SUM(tp.amount) as total_paid')
            )
                ->groupBy('transactions.id');

            return Datatables::of($dues)
                ->addColumn('due', function ($row) {
                    $total_paid = !empty($row->total_paid) ? $row->total_paid : 0;
                    $due = $row->final_total - $total_paid;

                    return '<span class="display_currency" data-currency_symbol="true">' .
                        $due . '</span>';
                })
                ->addColumn('action', '@can("purchase.create") <a href="{{action([\App\Http\Controllers\TransactionPaymentController::class, \'addPayment\'], [$id])}}" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-accent add_payment_modal"><i class="fas fa-money-bill-alt"></i> @lang("purchase.add_payment")</a> @endcan')
                ->removeColumn('supplier_business_name')
                ->editColumn('supplier', '@if(!empty($supplier_business_name)) {{$supplier_business_name}}, <br> @endif {{$supplier}}')
                ->editColumn('ref_no', function ($row) {
                    if (auth()->user()->can('purchase.view')) {
                        return '<a href="#" data-href="' . action([\App\Http\Controllers\PurchaseController::class, 'show'], [$row->id]) . '"
                                    class="btn-modal" data-container=".view_modal">' . $row->ref_no . '</a>';
                    }

                    return $row->ref_no;
                })
                ->removeColumn('id')
                ->removeColumn('final_total')
                ->removeColumn('total_paid')
                ->rawColumns([0, 1, 2, 3])
                ->make(false);
        }
    }

    /**
     * Retrieves payment dues for the purchases.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSalesPaymentDues()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $today = \Carbon::now()->format('Y-m-d H:i:s');

            $query = Transaction::join(
                'contacts as c',
                'transactions.contact_id',
                '=',
                'c.id'
            )
                ->leftJoin(
                    'transaction_payments as tp',
                    'transactions.id',
                    '=',
                    'tp.transaction_id'
                )
                ->where('transactions.business_id', $business_id)
                ->where('transactions.type', 'sell')
                ->where('transactions.payment_status', '!=', 'paid')
                ->whereNotNull('transactions.pay_term_number')
                ->whereNotNull('transactions.pay_term_type')
                ->whereRaw("DATEDIFF( DATE_ADD( transaction_date, INTERVAL IF(transactions.pay_term_type = 'days', transactions.pay_term_number, 30 * transactions.pay_term_number) DAY), '$today') <= 7");

            //Check for permitted locations of a user
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('transactions.location_id', $permitted_locations);
            }

            if (!empty(request()->input('location_id'))) {
                $query->where('transactions.location_id', request()->input('location_id'));
            }

            $dues = $query->select(
                'transactions.id as id',
                'c.name as customer',
                'c.supplier_business_name',
                'transactions.invoice_no',
                'final_total',
                DB::raw('SUM(tp.amount) as total_paid')
            )
                ->groupBy('transactions.id');

            return Datatables::of($dues)
                ->addColumn('due', function ($row) {
                    $total_paid = !empty($row->total_paid) ? $row->total_paid : 0;
                    $due = $row->final_total - $total_paid;

                    return '<span class="display_currency" data-currency_symbol="true">' .
                        $due . '</span>';
                })
                ->editColumn('invoice_no', function ($row) {
                    if (auth()->user()->can('sell.view')) {
                        return '<a href="#" data-href="' . action([\App\Http\Controllers\SellController::class, 'show'], [$row->id]) . '"
                                    class="btn-modal" data-container=".view_modal">' . $row->invoice_no . '</a>';
                    }

                    return $row->invoice_no;
                })
                ->addColumn('action', '@if(auth()->user()->can("sell.create") || auth()->user()->can("direct_sell.access")) <a href="{{action([\App\Http\Controllers\TransactionPaymentController::class, \'addPayment\'], [$id])}}" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-accent add_payment_modal"><i class="fas fa-money-bill-alt"></i> @lang("purchase.add_payment")</a> @endif')
                ->editColumn('customer', '@if(!empty($supplier_business_name)) {{$supplier_business_name}}, <br> @endif {{$customer}}')
                ->removeColumn('supplier_business_name')
                ->removeColumn('id')
                ->removeColumn('final_total')
                ->removeColumn('total_paid')
                ->rawColumns([0, 1, 2, 3])
                ->make(false);
        }
    }

    public function loadMoreNotifications()
    {
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'DESC')->paginate(10);

        if (request()->input('page') == 1) {
            auth()->user()->unreadNotifications->markAsRead();
        }
        $notifications_data = $this->commonUtil->parseNotifications($notifications);

        return view('layouts.partials.notification_list', compact('notifications_data'));
    }

    /**
     * Function to count total number of unread notifications
     *
     * @return json
     */
    public function getTotalUnreadNotifications()
    {
        $unread_notifications = auth()->user()->unreadNotifications;
        $total_unread = $unread_notifications->count();

        $notification_html = '';
        $modal_notifications = [];
        foreach ($unread_notifications as $unread_notification) {
            if (isset($data['show_popup'])) {
                $modal_notifications[] = $unread_notification;
                $unread_notification->markAsRead();
            }
        }
        if (!empty($modal_notifications)) {
            $notification_html = view('home.notification_modal')->with(['notifications' => $modal_notifications])->render();
        }

        return [
            'total_unread' => $total_unread,
            'notification_html' => $notification_html,
        ];
    }

    private function __chartOptions($title)
    {
        return [
            'yAxis' => [
                'title' => [
                    'text' => $title,
                ],
            ],
            'legend' => [
                'align' => 'right',
                'verticalAlign' => 'top',
                'floating' => true,
                'layout' => 'vertical',
                'padding' => 20,
            ],
        ];
    }

    public function getCalendar()
    {
        $business_id = request()->session()->get('user.business_id');
        $is_admin = $this->restUtil->is_admin(auth()->user(), $business_id);
        $is_superadmin = auth()->user()->can('superadmin');
        if (request()->ajax()) {
            $data = [
                'start_date' => request()->start,
                'end_date' => request()->end,
                'user_id' => ($is_admin || $is_superadmin) && !empty(request()->user_id) ? request()->user_id : auth()->user()->id,
                'location_id' => !empty(request()->location_id) ? request()->location_id : null,
                'business_id' => $business_id,
                'events' => request()->events ?? [],
                'color' => '#007FFF',
            ];
            $events = [];

            if (in_array('bookings', $data['events'])) {
                $events = $this->restUtil->getBookingsForCalendar($data);
            }

            $module_events = $this->moduleUtil->getModuleData('calendarEvents', $data);

            foreach ($module_events as $module_event) {
                $events = array_merge($events, $module_event);
            }

            return $events;
        }

        $all_locations = BusinessLocation::forDropdown($business_id)->toArray();
        $users = [];
        if ($is_admin) {
            $users = User::forDropdown($business_id, false);
        }

        $event_types = [
            'bookings' => [
                'label' => __('restaurant.bookings'),
                'color' => '#007FFF',
            ],
        ];
        $module_event_types = $this->moduleUtil->getModuleData('eventTypes');
        foreach ($module_event_types as $module_event_type) {
            $event_types = array_merge($event_types, $module_event_type);
        }

        return view('home.calendar')->with(compact('all_locations', 'users', 'event_types'));
    }

    public function showNotification($id)
    {
        $notification = DatabaseNotification::find($id);

        $data = $notification->data;

        $notification->markAsRead();

        return view('home.notification_modal')->with([
            'notifications' => [$notification],
        ]);
    }

    public function attachMediasToGivenModel(Request $request)
    {
        if ($request->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $model_id = $request->input('model_id');
                $model = $request->input('model_type');
                $model_media_type = $request->input('model_media_type');

                DB::beginTransaction();

                //find model to which medias are to be attached
                $model_to_be_attached = $model::where('business_id', $business_id)
                    ->findOrFail($model_id);

                Media::uploadMedia($business_id, $model_to_be_attached, $request, 'file', false, $model_media_type);

                DB::commit();

                $output = [
                    'success' => true,
                    'msg' => __('lang_v1.success'),
                ];
            } catch (Exception $e) {
                DB::rollBack();

                \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    public function getUserLocation($latlng)
    {
        $latlng_array = explode(',', $latlng);

        $response = $this->moduleUtil->getLocationFromCoordinates($latlng_array[0], $latlng_array[1]);

        return ['address' => $response];
    }
}
