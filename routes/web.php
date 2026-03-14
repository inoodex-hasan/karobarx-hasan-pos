<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AccountController, AccountReportsController, AccountTypeController, BackUpController, BarcodeController, BrandController, BusinessController, BusinessLocationController, CashRegisterController, CategoryController, CombinedPurchaseReturnController, ContactController, CustomerGroupController, DashboardConfiguratorController, DiscountController, DocumentAndNoteController, ExpenseCategoryController, ExpenseController, GroupTaxController, HomeController, ImportOpeningStockController, ImportProductsController, ImportSalesController, Install, InvoiceLayoutController, InvoiceSchemeController, LabelsController, LedgerDiscountController, LocationSettingsController, ManageUserController, NotificationController, NotificationTemplateController, OpeningStockController, PrinterController, ProductController, PurchaseController, PurchaseOrderController, PurchaseRequisitionController, PurchaseReturnController, ReportController, Restaurant, RoleController, SalesCommissionAgentController, SalesOrderController, SellController, SellPosController, SellReturnController, SellingPriceGroupController, StockAdjustmentController, StockTransferController, TaxRateController, TaxonomyController, TransactionPaymentController, TypesOfServiceController, UnitController, UserController, VariationTemplateController, WarrantyController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

include_once 'install_r.php';

Route::middleware(['setData'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/business/register', [BusinessController::class, 'getRegister'])->name('business.getRegister');
    Route::post('/business/register', [BusinessController::class, 'postRegister'])->name('business.postRegister');
    Route::post('/business/register/check-username', [BusinessController::class, 'postCheckUsername'])->name('business.postCheckUsername');
    Route::post('/business/register/check-email', [BusinessController::class, 'postCheckEmail'])->name('business.postCheckEmail');

    Route::get('/invoice/{token}', [SellPosController::class, 'showInvoice'])
        ->name('show_invoice');
    Route::get('/quote/{token}', [SellPosController::class, 'showInvoice'])
        ->name('show_quote');

    Route::get('/pay/{token}', [SellPosController::class, 'invoicePayment'])
        ->name('invoice_payment');
    Route::post('/confirm-payment/{id}', [SellPosController::class, 'confirmPayment'])
        ->name('confirm_payment');
});

//Routes for authenticated users only
Route::middleware(['setData', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu', 'CheckUserLogin'])->group(function () {
    Route::get('pos/payment/{id}', [SellPosController::class, 'edit'])->name('edit-pos-payment');
    Route::get('service-staff-availability', [SellPosController::class, 'showServiceStaffAvailibility']);
    Route::get('pause-resume-service-staff-timer/{user_id}', [SellPosController::class, 'pauseResumeServiceStaffTimer']);
    Route::get('mark-as-available/{user_id}', [SellPosController::class, 'markAsAvailable']);

    Route::resource('purchase-requisition', PurchaseRequisitionController::class)->except(['edit', 'update']);
    Route::post('/get-requisition-products', [PurchaseRequisitionController::class, 'getRequisitionProducts'])->name('get-requisition-products');
    Route::get('get-purchase-requisitions/{location_id}', [PurchaseRequisitionController::class, 'getPurchaseRequisitions']);
    Route::get('get-purchase-requisition-lines/{purchase_requisition_id}', [PurchaseRequisitionController::class, 'getPurchaseRequisitionLines']);

    Route::get('/sign-in-as-user/{id}', [ManageUserController::class, 'signInAsUser'])->name('sign-in-as-user');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/get-totals', [HomeController::class, 'getTotals']);
    Route::get('/home/viho/sales-overview', [HomeController::class, 'getVihoSalesOverview'])->name('home.viho.sales-overview');
    Route::get('/home/viho/annual-totals', [HomeController::class, 'getVihoAnnualTotals'])->name('home.viho.annual-totals');
    Route::get('/home/viho/growth-overview', [HomeController::class, 'getVihoGrowthOverview'])->name('home.viho.growth-overview');
    Route::get('/home/viho/user-activations', [HomeController::class, 'getVihoUserActivations'])->name('home.viho.user-activations');
    Route::get('/home/viho/transactions', [HomeController::class, 'getVihoTransactions'])->name('home.viho.transactions');
    Route::get('/home/product-stock-alert', [HomeController::class, 'getProductStockAlert']);
    Route::get('/home/purchase-payment-dues', [HomeController::class, 'getPurchasePaymentDues']);
    Route::get('/home/sales-payment-dues', [HomeController::class, 'getSalesPaymentDues']);
    Route::post('/attach-medias-to-model', [HomeController::class, 'attachMediasToGivenModel'])->name('attach.medias.to.model');
    Route::get('/calendar', [HomeController::class, 'getCalendar'])->name('calendar');

    Route::post('/test-email', [BusinessController::class, 'testEmailConfiguration']);
    Route::post('/test-sms', [BusinessController::class, 'testSmsConfiguration']);
    Route::get('/business/settings', [BusinessController::class, 'getBusinessSettings'])->name('business.getBusinessSettings');
    Route::post('/business/update', [BusinessController::class, 'postBusinessSettings'])->name('business.postBusinessSettings');
    Route::post('/business/layout-template', [BusinessController::class, 'updateLayoutTemplate'])->name('business.updateLayoutTemplate');
    Route::get('/user/profile', [UserController::class, 'getProfile'])->name('user.getProfile');
    Route::post('/user/update', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::post('/user/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');

    Route::resource('brands', BrandController::class);

    // Route::resource('payment-account', 'PaymentAccountController'); // Controller does not exist

    // Account routes
    Route::prefix('account')->name('account.')->group(function () {
        Route::resource('', AccountController::class)->names([
            'index' => 'index',
            'create' => 'create',
            'store' => 'store',
            'show' => 'show',
            'edit' => 'edit',
            'update' => 'update',
            'destroy' => 'destroy'
        ]);
    });

    // Account reports - default template routes
    Route::get('/account/balance-sheet', [AccountReportsController::class, 'balanceSheet'])->name('account.balance-sheet');
    Route::get('/account/trial-balance', [AccountReportsController::class, 'trialBalance'])->name('account.trial-balance');
    Route::get('/account/cash-flow', [AccountController::class, 'cashFlow'])->name('account.cash-flow');
    Route::get('/account/payment-account-report', [AccountReportsController::class, 'paymentAccountReport'])->name('account.payment-report');

    Route::resource('tax-rates', TaxRateController::class);

    Route::resource('units', UnitController::class);

    Route::resource('ledger-discount', LedgerDiscountController::class)->only('edit', 'destroy', 'store', 'update');

    Route::post('check-mobile', [ContactController::class, 'checkMobile']);
    Route::get('/get-contact-due/{contact_id}', [ContactController::class, 'getContactDue']);
    Route::get('/contacts/payments/{contact_id}', [ContactController::class, 'getContactPayments']);
    Route::get('/contacts/map', [ContactController::class, 'contactMap']);
    Route::get('/contacts/update-status/{id}', [ContactController::class, 'updateStatus']);
    Route::get('/contacts/stock-report/{supplier_id}', [ContactController::class, 'getSupplierStockReport']);
    Route::get('/contacts/ledger', [ContactController::class, 'getLedger']);
    Route::post('/contacts/send-ledger', [ContactController::class, 'sendLedger']);
    Route::get('/contacts/import', [ContactController::class, 'getImportContacts'])->name('contacts.import');
    Route::post('/contacts/import', [ContactController::class, 'postImportContacts']);
    Route::post('/contacts/check-contacts-id', [ContactController::class, 'checkContactId']);

    Route::post('/contacts/check-tax-number', [ContactController::class, 'checkTaxNumber']);

    Route::get('/contacts/customers', [ContactController::class, 'getCustomers']);
    Route::resource('contacts', ContactController::class);

    Route::get('taxonomies-ajax-index-page', [TaxonomyController::class, 'getTaxonomyIndexPage']);
    Route::resource('taxonomies', TaxonomyController::class);

    Route::resource('variation-templates', VariationTemplateController::class);

    Route::get('/products/download-excel', [ProductController::class, 'downloadExcel'])->name('products.downloadExcel');

    Route::get('/products/stock-history/{id}', [ProductController::class, 'productStockHistory']);
    Route::get('/delete-media/{media_id}', [ProductController::class, 'deleteMedia']);
    Route::post('/products/mass-deactivate', [ProductController::class, 'massDeactivate']);
    Route::get('/products/activate/{id}', [ProductController::class, 'activate']);
    Route::get('/products/view-product-group-price/{id}', [ProductController::class, 'viewGroupPrice']);
    Route::get('/products/add-selling-prices/{id}', [ProductController::class, 'addSellingPrices']);
    Route::post('/products/save-selling-prices', [ProductController::class, 'saveSellingPrices']);
    Route::post('/products/mass-delete', [ProductController::class, 'massDestroy']);
    Route::get('/products/view/{id}', [ProductController::class, 'view']);
    Route::get('/products/list', [ProductController::class, 'getProducts']);
    Route::get('/products/list-no-variation', [ProductController::class, 'getProductsWithoutVariations']);
    Route::post('/products/bulk-edit', [ProductController::class, 'bulkEdit']);
    Route::post('/products/bulk-update', [ProductController::class, 'bulkUpdate']);
    Route::post('/products/bulk-update-location', [ProductController::class, 'updateProductLocation']);
    Route::get('/products/get-product-to-edit/{product_id}', [ProductController::class, 'getProductToEdit']);

    Route::post('/products/get_sub_categories', [ProductController::class, 'getSubCategories']);
    Route::get('/products/get_sub_units', [ProductController::class, 'getSubUnits']);
    Route::post('/products/product_form_part', [ProductController::class, 'getProductVariationFormPart']);
    Route::post('/products/get_product_variation_row', [ProductController::class, 'getProductVariationRow']);
    Route::post('/products/get_variation_template', [ProductController::class, 'getVariationTemplate']);
    Route::get('/products/get_variation_value_row', [ProductController::class, 'getVariationValueRow']);
    Route::post('/products/check_product_sku', [ProductController::class, 'checkProductSku']);
    Route::post('/products/check_product_name', [ProductController::class, 'checkProductName']);
    Route::post('/products/validate_variation_skus', [ProductController::class, 'validateVaritionSkus']); //validates multiple skus at once
    Route::get('/products/quick_add', [ProductController::class, 'quickAdd']);
    Route::post('/products/save_quick_product', [ProductController::class, 'saveQuickProduct']);
    Route::get('/products/get-combo-product-entry-row', [ProductController::class, 'getComboProductEntryRow']);
    Route::post('/products/toggle-woocommerce-sync', [ProductController::class, 'toggleWooCommerceSync']);

    Route::resource('products', ProductController::class);
    Route::get('/toggle-subscription/{id}', 'SellPosController@toggleRecurringInvoices')->name('toggle-subscription');
    Route::post('/sells/pos/get-types-of-service-details', 'SellPosController@getTypesOfServiceDetails')->name('sells.pos.get-types-of-service-details');
    Route::get('/sells/subscriptions', 'SellPosController@listSubscriptions')->name('sells.subscriptions');
    Route::get('/sells/duplicate/{id}', 'SellController@duplicateSell')->name('sells.duplicate');
    Route::get('/sells/drafts', 'SellController@getDrafts')->name('sells.drafts');
    Route::get('/sells/convert-to-draft/{id}', 'SellPosController@convertToInvoice')->name('sells.convert-to-draft');
    Route::get('/sells/convert-to-proforma/{id}', 'SellPosController@convertToProforma')->name('sells.convert-to-proforma');
    Route::get('/sells/quotations', 'SellController@getQuotations')->name('sells.quotations');
    Route::get('/sells/draft-dt', 'SellController@getDraftDatables')->name('sells.draft-dt');
    Route::resource('sells', 'SellController')->except(['show'])->names('sells');
    Route::get('/sells/copy-quotation/{id}', [SellPosController::class, 'copyQuotation'])->name('sells.copy-quotation');

    Route::post('/import-purchase-products', [PurchaseController::class, 'importPurchaseProducts']);
    Route::post('/purchases/update-status', [PurchaseController::class, 'updateStatus']);
    Route::get('/purchases/get_products', [PurchaseController::class, 'getProducts']);
    Route::get('/purchases/get_suppliers', [PurchaseController::class, 'getSuppliers']);
    Route::post('/purchases/get_purchase_entry_row', [PurchaseController::class, 'getPurchaseEntryRow']);
    Route::post('/purchases/check_ref_number', [PurchaseController::class, 'checkRefNumber']);
    Route::resource('purchases', PurchaseController::class)->except(['show']);

    Route::get('/import-sales', [ImportSalesController::class, 'index'])->name('import-sales.index');
    Route::post('/import-sales/preview', [ImportSalesController::class, 'preview'])->name('import-sales.preview');
    Route::post('/import-sales', [ImportSalesController::class, 'import'])->name('import-sales.import');
    Route::get('/revert-sale-import/{batch}', [ImportSalesController::class, 'revertSaleImport'])->name('import-sales.revert');

    Route::get('/sells/pos/get_product_row/{variation_id}/{location_id}', [SellPosController::class, 'getProductRow']);
    Route::post('/sells/pos/get_payment_row', [SellPosController::class, 'getPaymentRow']);
    Route::post('/sells/pos/get-reward-details', [SellPosController::class, 'getRewardDetails']);
    Route::get('/sells/pos/get-recent-transactions', [SellPosController::class, 'getRecentTransactions']);
    Route::get('/sells/pos/get-product-suggestion', [SellPosController::class, 'getProductSuggestion']);
    Route::get('/sells/pos/get-featured-products/{location_id}', [SellPosController::class, 'getFeaturedProducts']);
    Route::get('/reset-mapping', [SellController::class, 'resetMapping']);
    // pos display screen route
    Route::get('/customer-display', [SellPosController::class, 'posDisplay'])->name('pos_display');

    Route::get('/pos/variations/bulk', [\App\Http\Controllers\ProductController::class, 'getVariationDetailsBulk']);
    // end pos display screen route
    Route::resource('pos', SellPosController::class)->names('pos');

    Route::resource('roles', RoleController::class);
    Route::resource('users', ManageUserController::class);


    // AI Template (Viho) pages
    Route::prefix('ai-template')->name('ai-template.')->middleware('ensureVihoTemplate')->group(function () {
        Route::get('home', [HomeController::class, 'index'])->name('home');

        Route::get('users', [ManageUserController::class, 'index'])->name('users.index');
        Route::get('users/create', [ManageUserController::class, 'create'])->name('users.create');
        Route::get('users/{user}', [ManageUserController::class, 'show'])->name('users.show');
        Route::get('users/{user}/edit', [ManageUserController::class, 'edit'])->name('users.edit');
        Route::post('users', [ManageUserController::class, 'store'])->name('users.store');
        Route::put('users/{user}', [ManageUserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [ManageUserController::class, 'destroy'])->name('users.destroy');

        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        Route::get('sales-comission-agents', [SalesCommissionAgentController::class, 'index'])->name('sales-commission-agents.index');
        Route::get('sales-comission-agents/create', [SalesCommissionAgentController::class, 'create'])->name('sales-commission-agents.create');
        Route::get('sales-comission-agents/{agent}', [SalesCommissionAgentController::class, 'show'])->name('sales-commission-agents.show');
        Route::get('sales-comission-agents/{agent}/edit', [SalesCommissionAgentController::class, 'edit'])->name('sales-commission-agents.edit');
        Route::post('sales-comission-agents', [SalesCommissionAgentController::class, 'store'])->name('sales-commission-agents.store');
        Route::put('sales-comission-agents/{agent}', [SalesCommissionAgentController::class, 'update'])->name('sales-commission-agents.update');
        Route::delete('sales-comission-agents/{agent}', [SalesCommissionAgentController::class, 'destroy'])->name('sales-commission-agents.destroy');

        // Alias routes (correct spelling) to support sidebar auto-switching and direct access.
        // Default routes use `/sales-commission-agents` (2x "m"), but older Viho routes were added with `/sales-comission-agents`.
        Route::get('sales-commission-agents', [SalesCommissionAgentController::class, 'index']);
        Route::get('sales-commission-agents/create', [SalesCommissionAgentController::class, 'create']);
        Route::get('sales-commission-agents/{agent}', [SalesCommissionAgentController::class, 'show']);
        Route::get('sales-commission-agents/{agent}/edit', [SalesCommissionAgentController::class, 'edit']);
        Route::post('sales-commission-agents', [SalesCommissionAgentController::class, 'store']);
        Route::put('sales-commission-agents/{agent}', [SalesCommissionAgentController::class, 'update']);
        Route::delete('sales-commission-agents/{agent}', [SalesCommissionAgentController::class, 'destroy']);

        // Contacts (Viho)
        Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/import', [ContactController::class, 'getImportContacts'])->name('contacts.import');
        Route::post('contacts/import', [ContactController::class, 'postImportContacts'])->name('contacts.import.post');
        Route::get('contacts/map', [ContactController::class, 'contactMap'])->name('contacts.map');
        Route::get('contacts/create', [ContactController::class, 'create'])->name('contacts.create');
        Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
        Route::get('contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
        Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
        Route::put('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
        Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

        // Purchases (Viho)
        Route::post('import-purchase-products', [PurchaseController::class, 'importPurchaseProducts'])->name('import-purchase-products');
        Route::post('purchases/update-status', [PurchaseController::class, 'updateStatus'])->name('purchases.update-status');
        Route::get('purchases/get_products', [PurchaseController::class, 'getProducts'])->name('purchases.get_products');
        Route::get('purchases/get_suppliers', [PurchaseController::class, 'getSuppliers'])->name('purchases.get_suppliers');
        Route::post('purchases/get_purchase_entry_row', [PurchaseController::class, 'getPurchaseEntryRow'])->name('purchases.get_purchase_entry_row');
        Route::post('purchases/check_ref_number', [PurchaseController::class, 'checkRefNumber'])->name('purchases.check_ref_number');
        Route::resource('purchases', PurchaseController::class)->except(['show']);
        Route::get('purchases/print/{id}', [PurchaseController::class, 'printInvoice'])->name('purchases.print');
        Route::get('purchases/{id}', [PurchaseController::class, 'show'])->name('purchases.show');

        // Purchase Returns (Viho)
        Route::get('purchase-return/add/{id}', [PurchaseReturnController::class, 'add'])->name('purchase-return.add');
        Route::resource('purchase-return', PurchaseReturnController::class)->except(['create']);

        // Stock Transfer (Viho)
        Route::get('stock-transfers/print/{id}', [StockTransferController::class, 'printInvoice'])->name('stock-transfers.print');
        Route::post('stock-transfers/update-status/{id}', [StockTransferController::class, 'updateStatus'])->name('stock-transfers.update-status');
        Route::resource('stock-transfers', StockTransferController::class);

        // Stock Adjustment (Viho)
        Route::get('/stock-adjustments/remove-expired-stock/{purchase_line_id}', [StockAdjustmentController::class, 'removeExpiredStock'])->name('stock-adjustments.remove-expired-stock');
        Route::post('/stock-adjustments/get_product_row', [StockAdjustmentController::class, 'getProductRow'])->name('stock-adjustments.get_product_row');
        Route::resource('stock-adjustments', StockAdjustmentController::class);

        // Expenses (Viho)
        Route::get('import-expense', [ExpenseController::class, 'importExpense'])->name('expenses.import');
        Route::post('store-import-expense', [ExpenseController::class, 'storeExpenseImport'])->name('expenses.store-import');
        Route::resource('expenses', ExpenseController::class);

        // Expense Categories (Viho)
        Route::resource('expense-categories', ExpenseCategoryController::class);

        // Products (Viho)
        Route::get('/products/download-excel', [ProductController::class, 'downloadExcel']);
        Route::get('/products/stock-history/{id}', [ProductController::class, 'productStockHistory']);
        Route::post('/products/mass-deactivate', [ProductController::class, 'massDeactivate']);
        Route::get('/products/activate/{id}', [ProductController::class, 'activate']);
        Route::get('/products/view-product-group-price/{id}', [ProductController::class, 'viewGroupPrice']);
        Route::get('/products/add-selling-prices/{id}', [ProductController::class, 'addSellingPrices']);
        Route::post('/products/save-selling-prices', [ProductController::class, 'saveSellingPrices']);
        Route::post('/products/mass-delete', [ProductController::class, 'massDestroy']);
        Route::get('/products/view/{id}', [ProductController::class, 'view']);
        Route::get('/products/list', [ProductController::class, 'getProducts']);
        Route::get('/products/list-no-variation', [ProductController::class, 'getProductsWithoutVariations']);
        Route::post('/products/bulk-edit', [ProductController::class, 'bulkEdit']);
        Route::post('/products/bulk-update', [ProductController::class, 'bulkUpdate']);
        Route::post('/products/bulk-update-location', [ProductController::class, 'updateProductLocation']);
        Route::get('/products/get-product-to-edit/{product_id}', [ProductController::class, 'getProductToEdit']);
        Route::post('/products/get_sub_categories', [ProductController::class, 'getSubCategories']);
        Route::get('/products/get_sub_units', [ProductController::class, 'getSubUnits']);
        Route::post('/products/product_form_part', [ProductController::class, 'getProductVariationFormPart']);
        Route::post('/products/get_product_variation_row', [ProductController::class, 'getProductVariationRow']);
        Route::post('/products/get_variation_template', [ProductController::class, 'getVariationTemplate']);
        Route::get('/products/get_variation_value_row', [ProductController::class, 'getVariationValueRow']);
        Route::post('/products/check_product_sku', [ProductController::class, 'checkProductSku']);
        Route::post('/products/check_product_name', [ProductController::class, 'checkProductName']);
        Route::post('/products/validate_variation_skus', [ProductController::class, 'validateVaritionSkus']);
        Route::get('/products/quick_add', [ProductController::class, 'quickAdd']);
        Route::post('/products/save_quick_product', [ProductController::class, 'saveQuickProduct']);
        Route::get('/products/get-combo-product-entry-row', [ProductController::class, 'getComboProductEntryRow']);
        Route::post('/products/toggle-woocommerce-sync', [ProductController::class, 'toggleWooCommerceSync']);
        Route::resource('products', ProductController::class);

        // Brands (Viho)
        Route::resource('brands', BrandController::class);

        // Units (Viho)
        Route::resource('units', UnitController::class);

        // Categories/Taxonomies (Viho)
        Route::get('/taxonomies', [TaxonomyController::class, 'index']);

        // Variation Templates (Viho)
        Route::resource('variation-templates', VariationTemplateController::class);

        // Selling Price Groups (Viho)
        Route::get('selling-price-group/update-product-price', [SellingPriceGroupController::class, 'updateProductPrice'])->name('update-product-price');
        Route::resource('selling-price-group', SellingPriceGroupController::class);

        // Labels (Viho)
        Route::get('labels/show', [LabelsController::class, 'show'])->name('labels.show');

        // Import Products (Viho)
        Route::resource('import-products', ImportProductsController::class);

        // Import Opening Stock (Viho)
        Route::resource('import-opening-stock', ImportOpeningStockController::class);

        // Account / Payment Account (Viho)
        Route::prefix('account')->name('account.')->group(function () {
            Route::resource('', AccountController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'store' => 'store',
                'show' => 'show',
                'edit' => 'edit',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
            Route::get('/fund-transfer/{id}', [AccountController::class, 'getFundTransfer'])->name('fund-transfer');
            Route::post('/fund-transfer', [AccountController::class, 'postFundTransfer'])->name('fund-transfer.post');
            Route::get('/deposit/{id}', [AccountController::class, 'getDeposit'])->name('deposit');
            Route::post('/deposit', [AccountController::class, 'postDeposit'])->name('deposit.post');
            Route::get('/close/{id}', [AccountController::class, 'close'])->name('close');
            Route::get('/activate/{id}', [AccountController::class, 'activate'])->name('activate');
            Route::get('/delete-account-transaction/{id}', [AccountController::class, 'destroyAccountTransaction'])->name('delete-transaction');
            Route::get('/edit-account-transaction/{id}', [AccountController::class, 'editAccountTransaction'])->name('edit-transaction');
            Route::post('/update-account-transaction/{id}', [AccountController::class, 'updateAccountTransaction'])->name('update-transaction');
            Route::get('/get-account-balance/{id}', [AccountController::class, 'getAccountBalance'])->name('balance');
            Route::get('/balance-sheet', [AccountReportsController::class, 'balanceSheet'])->name('balance-sheet');
            Route::get('/trial-balance', [AccountReportsController::class, 'trialBalance'])->name('trial-balance');
            Route::get('/payment-account-report', [AccountReportsController::class, 'paymentAccountReport'])->name('payment-report');
            Route::get('/link-account/{id}', [AccountReportsController::class, 'getLinkAccount'])->name('link-account');
            Route::post('/link-account', [AccountReportsController::class, 'postLinkAccount'])->name('link-account.post');
            Route::get('/cash-flow', [AccountController::class, 'cashFlow'])->name('cash-flow');
        });

        // Customer Groups (Viho)
        Route::resource('customer-group', CustomerGroupController::class);

        // Warranties (Viho)
        Route::resource('warranties', WarrantyController::class);

        // Sells (Viho)
        Route::get('sells/drafts', [SellController::class, 'getDrafts'])->name('sells.drafts');
        Route::get('sells/quotations', [SellController::class, 'getQuotations'])->name('sells.quotations');
        Route::get('shipments', [SellController::class, 'shipments'])->name('shipments');
        Route::resource('sells', SellController::class)->except(['show']);

        // POS (Viho)
        Route::resource('pos', SellPosController::class);

        // Sell Return (Viho)
        Route::resource('sell-return', SellReturnController::class);
        Route::get('sell-return/get-product-row', [SellReturnController::class, 'getProductRow'])->name('sell-return.get-product-row');
        Route::get('sell-return/print/{id}', [SellReturnController::class, 'printInvoice'])->name('sell-return.print');
        Route::get('sell-return/add/{id}', [SellReturnController::class, 'add'])->name('sell-return.add');

        // Discounts (Viho)
        Route::resource('discount', DiscountController::class);
        Route::get('discount/activate/{id}', [DiscountController::class, 'activate'])->name('discount.activate');
        Route::post('discount/mass-deactivate', [DiscountController::class, 'massDeactivate'])->name('discount.mass-deactivate');

        // Subscriptions (Viho)
        Route::get('sells/subscriptions', [SellPosController::class, 'listSubscriptions'])->name('sells.subscriptions');
        Route::get('toggle-subscription/{id}', [SellPosController::class, 'toggleRecurringInvoices'])->name('toggle-subscription');

        // Import Sales (Viho)
        Route::get('import-sales', [ImportSalesController::class, 'index'])->name('import-sales.index');
        Route::post('import-sales/preview', [ImportSalesController::class, 'preview'])->name('import-sales.preview');
        Route::post('import-sales', [ImportSalesController::class, 'import'])->name('import-sales.import');

        // Reports (Viho) - Batch 1
        Route::get('reports/profit-loss', [ReportController::class, 'getProfitLoss'])->name('reports.profit-loss');
        Route::get('reports/purchase-sell', [ReportController::class, 'getPurchaseSell'])->name('reports.purchase-sell');
        Route::get('reports/tax-report', [ReportController::class, 'getTaxReport'])->name('reports.tax-report');

        // Reports (Viho) - Batch 2 (Remaining reports)
        Route::get('reports/customer-supplier', [ReportController::class, 'getCustomerSuppliers'])->name('reports.customer-supplier');
        Route::get('reports/customer-group', [ReportController::class, 'getCustomerGroup'])->name('reports.customer-group');
        Route::get('reports/stock-report', [ReportController::class, 'getStockReport'])->name('reports.stock-report');
        Route::get('reports/stock-expiry', [ReportController::class, 'getStockExpiryReport'])->name('reports.stock-expiry');
        Route::get('reports/lot-report', [ReportController::class, 'getLotReport'])->name('reports.lot-report');
        Route::get('reports/stock-adjustment-report', [ReportController::class, 'getStockAdjustmentReport'])->name('reports.stock-adjustment-report');
        Route::get('reports/trending-products', [ReportController::class, 'getTrendingProducts'])->name('reports.trending-products');
        Route::get('reports/items-report', [ReportController::class, 'itemsReport'])->name('reports.items-report');
        Route::get('reports/product-purchase-report', [ReportController::class, 'getproductPurchaseReport'])->name('reports.product-purchase-report');
        Route::get('reports/product-sell-report', [ReportController::class, 'getproductSellReport'])->name('reports.product-sell-report');
        Route::get('reports/purchase-payment-report', [ReportController::class, 'purchasePaymentReport'])->name('reports.purchase-payment-report');
        Route::get('reports/sell-payment-report', [ReportController::class, 'sellPaymentReport'])->name('reports.sell-payment-report');
        Route::get('reports/expense-report', [ReportController::class, 'getExpenseReport'])->name('reports.expense-report');
        Route::get('reports/register-report', [ReportController::class, 'getRegisterReport'])->name('reports.register-report');
        Route::get('reports/sales-representative-report', [ReportController::class, 'getSalesRepresentativeReport'])->name('reports.sales-representative-report');
        Route::get('reports/table-report', [ReportController::class, 'getTableReport'])->name('reports.table-report');
        Route::get('reports/service-staff-report', [ReportController::class, 'getServiceStaffReport'])->name('reports.service-staff-report');
        Route::get('reports/activity-log', [ReportController::class, 'activityLog'])->name('reports.activity-log');
        Route::get('reports/purchase-report', [ReportController::class, 'purchaseReport'])->name('reports.purchase-report');
        Route::get('reports/sale-report', [ReportController::class, 'saleReport'])->name('reports.sale-report');
        Route::get('reports/gst-sales-report', [ReportController::class, 'gstSalesReport'])->name('reports.gst-sales-report');
        Route::get('reports/gst-purchase-report', [ReportController::class, 'gstPurchaseReport'])->name('reports.gst-purchase-report');

        // Notification Templates (Viho)
        Route::resource('notification-templates', NotificationTemplateController::class)->only(['index', 'store'])->names('notification-templates');

        // Settings (Viho)
        // Business Settings
        Route::get('business/settings', [BusinessController::class, 'getBusinessSettings'])->name('business.settings');
        Route::post('business/update', [BusinessController::class, 'postBusinessSettings'])->name('business.update');
        Route::post('business/layout-template', [BusinessController::class, 'updateLayoutTemplate'])->name('business.layout-template');

        // Business Locations
        Route::get('business-location', [BusinessLocationController::class, 'index'])->name('business-location.index');
        Route::get('business-location/create', [BusinessLocationController::class, 'create'])->name('business-location.create');
        Route::post('business-location', [BusinessLocationController::class, 'store'])->name('business-location.store');
        Route::get('business-location/{location}', [BusinessLocationController::class, 'show'])->name('business-location.show');
        Route::get('business-location/{location}/edit', [BusinessLocationController::class, 'edit'])->name('business-location.edit');
        Route::put('business-location/{location}', [BusinessLocationController::class, 'update'])->name('business-location.update');
        Route::delete('business-location/{location}', [BusinessLocationController::class, 'destroy'])->name('business-location.destroy');
        Route::get('business-location/activate-deactivate/{location_id}', [BusinessLocationController::class, 'activateDeactivateLocation'])->name('business-location.activate-deactivate');
        Route::post('business-location/check-location-id', [BusinessLocationController::class, 'checkLocationId'])->name('business-location.check-location-id');

        // Location Settings
        Route::prefix('business-location/{location_id}')->name('location.')->group(function () {
            Route::get('settings', [LocationSettingsController::class, 'index'])->name('settings');
            Route::post('settings', [LocationSettingsController::class, 'updateSettings'])->name('settings_update');
        });

        // Invoice Schemes
        Route::get('invoice-schemes', [InvoiceSchemeController::class, 'index'])->name('invoice-schemes.index');
        Route::get('invoice-schemes/create', [InvoiceSchemeController::class, 'create'])->name('invoice-schemes.create');
        Route::post('invoice-schemes', [InvoiceSchemeController::class, 'store'])->name('invoice-schemes.store');
        Route::get('invoice-schemes/{invoice_scheme}', [InvoiceSchemeController::class, 'show'])->name('invoice-schemes.show');
        Route::get('invoice-schemes/{invoice_scheme}/edit', [InvoiceSchemeController::class, 'edit'])->name('invoice-schemes.edit');
        Route::put('invoice-schemes/{invoice_scheme}', [InvoiceSchemeController::class, 'update'])->name('invoice-schemes.update');
        Route::delete('invoice-schemes/{invoice_scheme}', [InvoiceSchemeController::class, 'destroy'])->name('invoice-schemes.destroy');
        Route::get('invoice-schemes/set_default/{id}', [InvoiceSchemeController::class, 'setDefault'])->name('invoice-schemes.set_default');

        // Invoice Layouts
        Route::get('invoice-layouts', [InvoiceLayoutController::class, 'index'])->name('invoice-layouts.index');
        Route::get('invoice-layouts/create', [InvoiceLayoutController::class, 'create'])->name('invoice-layouts.create');
        Route::post('invoice-layouts', [InvoiceLayoutController::class, 'store'])->name('invoice-layouts.store');
        Route::get('invoice-layouts/{invoice_layout}', [InvoiceLayoutController::class, 'show'])->name('invoice-layouts.show');
        Route::get('invoice-layouts/{invoice_layout}/edit', [InvoiceLayoutController::class, 'edit'])->name('invoice-layouts.edit');
        Route::put('invoice-layouts/{invoice_layout}', [InvoiceLayoutController::class, 'update'])->name('invoice-layouts.update');
        Route::delete('invoice-layouts/{invoice_layout}', [InvoiceLayoutController::class, 'destroy'])->name('invoice-layouts.destroy');

        // Barcode Settings
        Route::get('barcodes', [BarcodeController::class, 'index'])->name('barcodes.index');
        Route::get('barcodes/create', [BarcodeController::class, 'create'])->name('barcodes.create');
        Route::post('barcodes', [BarcodeController::class, 'store'])->name('barcodes.store');
        Route::get('barcodes/{barcode}', [BarcodeController::class, 'show'])->name('barcodes.show');
        Route::get('barcodes/{barcode}/edit', [BarcodeController::class, 'edit'])->name('barcodes.edit');
        Route::put('barcodes/{barcode}', [BarcodeController::class, 'update'])->name('barcodes.update');
        Route::delete('barcodes/{barcode}', [BarcodeController::class, 'destroy'])->name('barcodes.destroy');
        Route::get('barcodes/set_default/{id}', [BarcodeController::class, 'setDefault'])->name('barcodes.set_default');

        // Printers
        Route::get('printers', [PrinterController::class, 'index'])->name('printers.index');
        Route::get('printers/create', [PrinterController::class, 'create'])->name('printers.create');
        Route::post('printers', [PrinterController::class, 'store'])->name('printers.store');
        Route::get('printers/{printer}', [PrinterController::class, 'show'])->name('printers.show');
        Route::get('printers/{printer}/edit', [PrinterController::class, 'edit'])->name('printers.edit');
        Route::put('printers/{printer}', [PrinterController::class, 'update'])->name('printers.update');
        Route::delete('printers/{printer}', [PrinterController::class, 'destroy'])->name('printers.destroy');

        // Tax Rates
        Route::get('tax-rates', [TaxRateController::class, 'index'])->name('tax-rates.index');
        Route::get('tax-rates/create', [TaxRateController::class, 'create'])->name('tax-rates.create');
        Route::post('tax-rates', [TaxRateController::class, 'store'])->name('tax-rates.store');
        Route::get('tax-rates/{tax_rate}', [TaxRateController::class, 'show'])->name('tax-rates.show');
        Route::get('tax-rates/{tax_rate}/edit', [TaxRateController::class, 'edit'])->name('tax-rates.edit');
        Route::put('tax-rates/{tax_rate}', [TaxRateController::class, 'update'])->name('tax-rates.update');
        Route::delete('tax-rates/{tax_rate}', [TaxRateController::class, 'destroy'])->name('tax-rates.destroy');

        // Group Taxes
        Route::get('group-taxes', [GroupTaxController::class, 'index'])->name('group-taxes.index');
        Route::get('group-taxes/create', [GroupTaxController::class, 'create'])->name('group-taxes.create');
        Route::post('group-taxes', [GroupTaxController::class, 'store'])->name('group-taxes.store');
        Route::get('group-taxes/{group_tax}', [GroupTaxController::class, 'show'])->name('group-taxes.show');
        Route::get('group-taxes/{group_tax}/edit', [GroupTaxController::class, 'edit'])->name('group-taxes.edit');
        Route::put('group-taxes/{group_tax}', [GroupTaxController::class, 'update'])->name('group-taxes.update');
        Route::delete('group-taxes/{group_tax}', [GroupTaxController::class, 'destroy'])->name('group-taxes.destroy');

        // Tables (Restaurant)
        Route::get('tables', [Restaurant\TableController::class, 'index'])->name('tables.index');
        Route::get('tables/create', [Restaurant\TableController::class, 'create'])->name('tables.create');
        Route::post('tables', [Restaurant\TableController::class, 'store'])->name('tables.store');
        Route::get('tables/{table}', [Restaurant\TableController::class, 'show'])->name('tables.show');
        Route::get('tables/{table}/edit', [Restaurant\TableController::class, 'edit'])->name('tables.edit');
        Route::put('tables/{table}', [Restaurant\TableController::class, 'update'])->name('tables.update');
        Route::delete('tables/{table}', [Restaurant\TableController::class, 'destroy'])->name('tables.destroy');

        // Modifiers (Restaurant)
        Route::get('modifiers', [Restaurant\ModifierSetsController::class, 'index'])->name('modifiers.index');
        Route::get('modifiers/create', [Restaurant\ModifierSetsController::class, 'create'])->name('modifiers.create');
        Route::post('modifiers', [Restaurant\ModifierSetsController::class, 'store'])->name('modifiers.store');
        Route::get('modifiers/{modifier}', [Restaurant\ModifierSetsController::class, 'show'])->name('modifiers.show');
        Route::get('modifiers/{modifier}/edit', [Restaurant\ModifierSetsController::class, 'edit'])->name('modifiers.edit');
        Route::put('modifiers/{modifier}', [Restaurant\ModifierSetsController::class, 'update'])->name('modifiers.update');
        Route::delete('modifiers/{modifier}', [Restaurant\ModifierSetsController::class, 'destroy'])->name('modifiers.destroy');

        // Bookings (Restaurant - Viho)
        Route::get('bookings', [Restaurant\BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/create', [Restaurant\BookingController::class, 'create'])->name('bookings.create');
        Route::post('bookings', [Restaurant\BookingController::class, 'store'])->name('bookings.store');
        Route::get('bookings/{booking}', [Restaurant\BookingController::class, 'show'])->name('bookings.show');
        Route::get('bookings/{booking}/edit', [Restaurant\BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('bookings/{booking}', [Restaurant\BookingController::class, 'update'])->name('bookings.update');
        Route::delete('bookings/{booking}', [Restaurant\BookingController::class, 'destroy'])->name('bookings.destroy');
        Route::get('bookings/get-todays-bookings', [Restaurant\BookingController::class, 'getTodaysBookings'])->name('bookings.get-todays-bookings');

        // Kitchen (Restaurant - Viho)
        Route::get('kitchen', [Restaurant\KitchenController::class, 'index'])->name('kitchen.index');
        Route::get('kitchen/mark-as-cooked/{id}', [Restaurant\KitchenController::class, 'markAsCooked'])->name('kitchen.mark-as-cooked');
        Route::post('kitchen/refresh-orders-list', [Restaurant\KitchenController::class, 'refreshOrdersList'])->name('kitchen.refresh-orders-list');
        Route::post('kitchen/refresh-line-orders-list', [Restaurant\KitchenController::class, 'refreshLineOrdersList'])->name('kitchen.refresh-line-orders-list');

        // Orders (Restaurant - Viho)
        Route::get('orders', [Restaurant\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/mark-as-served/{id}', [Restaurant\OrderController::class, 'markAsServed'])->name('orders.mark-as-served');
        Route::get('orders/mark-line-order-as-served/{id}', [Restaurant\OrderController::class, 'markLineOrderAsServed'])->name('orders.mark-line-order-as-served');
        Route::get('orders/print-line-order', [Restaurant\OrderController::class, 'printLineOrder'])->name('orders.print-line-order');

        // Types of Service
        Route::get('types-of-service', [TypesOfServiceController::class, 'index'])->name('types-of-service.index');
        Route::get('types-of-service/create', [TypesOfServiceController::class, 'create'])->name('types-of-service.create');
        Route::post('types-of-service', [TypesOfServiceController::class, 'store'])->name('types-of-service.store');
        Route::get('types-of-service/{type_of_service}', [TypesOfServiceController::class, 'show'])->name('types-of-service.show');
        Route::get('types-of-service/{type_of_service}/edit', [TypesOfServiceController::class, 'edit'])->name('types-of-service.edit');
        Route::put('types-of-service/{type_of_service}', [TypesOfServiceController::class, 'update'])->name('types-of-service.update');
        Route::delete('types-of-service/{type_of_service}', [TypesOfServiceController::class, 'destroy'])->name('types-of-service.destroy');

        // Backup (Viho)
        Route::get('backup', [BackUpController::class, 'index'])->name('backup.index');
        Route::post('backup/upload', [BackUpController::class, 'upload'])->name('backup.upload');
        Route::get('backup/download/{file_name}', [BackUpController::class, 'download'])->name('backup.download');
        Route::get('backup/delete/{file_name}', [BackUpController::class, 'delete'])->name('backup.delete');

        // Manage Modules (Viho)
        Route::get('manage-modules', [Install\ModulesController::class, 'index'])->name('manage-modules.index');
        Route::post('manage-modules/install', [Install\ModulesController::class, 'install'])->name('manage-modules.install');
        Route::get('manage-modules/uninstall/{module_id}', [Install\ModulesController::class, 'uninstall'])->name('manage-modules.uninstall');
        Route::get('manage-modules/update-module/{module_id}', [Install\ModulesController::class, 'updateModule'])->name('manage-modules.update-module');
        Route::post('manage-modules/install-module', [Install\ModulesController::class, 'installModule'])->name('manage-modules.install-module');
    });

    Route::resource('group-taxes', GroupTaxController::class);

    Route::get('/barcodes/set_default/{id}', [BarcodeController::class, 'setDefault']);
    Route::resource('barcodes', BarcodeController::class);

    //Invoice schemes..
    Route::get('/invoice-schemes/set_default/{id}', [InvoiceSchemeController::class, 'setDefault']);
    Route::resource('invoice-schemes', InvoiceSchemeController::class);

    //Print Labels
    Route::get('/labels/show', [LabelsController::class, 'show'])->name('labels.show');
    Route::get('/labels/add-product-row', [LabelsController::class, 'addProductRow'])->name('labels.add-product-row');
    Route::get('/labels/preview', [LabelsController::class, 'preview'])->name('labels.preview');

    //Reports...
    Route::get('/reports/gst-purchase-report', [ReportController::class, 'gstPurchaseReport']);
    Route::get('/reports/gst-sales-report', [ReportController::class, 'gstSalesReport']);
    Route::get('/reports/get-stock-by-sell-price', [ReportController::class, 'getStockBySellingPrice']);
    Route::get('/reports/purchase-report', [ReportController::class, 'purchaseReport']);
    Route::get('/reports/sale-report', [ReportController::class, 'saleReport']);
    Route::get('/reports/service-staff-report', [ReportController::class, 'getServiceStaffReport']);
    Route::get('/reports/service-staff-line-orders', [ReportController::class, 'serviceStaffLineOrders']);
    Route::get('/reports/table-report', [ReportController::class, 'getTableReport']);
    Route::get('/reports/profit-loss', [ReportController::class, 'getProfitLoss']);
    Route::get('/reports/get-opening-stock', [ReportController::class, 'getOpeningStock']);
    Route::get('/reports/purchase-sell', [ReportController::class, 'getPurchaseSell']);
    Route::get('/reports/customer-supplier', [ReportController::class, 'getCustomerSuppliers']);
    Route::get('/reports/stock-report', [ReportController::class, 'getStockReport']);
    Route::get('/reports/stock-details', [ReportController::class, 'getStockDetails']);
    Route::get('/reports/tax-report', [ReportController::class, 'getTaxReport']);
    Route::get('/reports/tax-details', [ReportController::class, 'getTaxDetails']);
    Route::get('/reports/trending-products', [ReportController::class, 'getTrendingProducts']);
    Route::get('/reports/expense-report', [ReportController::class, 'getExpenseReport']);
    Route::get('/reports/stock-adjustment-report', [ReportController::class, 'getStockAdjustmentReport']);
    Route::get('/reports/register-report', [ReportController::class, 'getRegisterReport']);
    Route::get('/reports/sales-representative-report', [ReportController::class, 'getSalesRepresentativeReport']);
    Route::get('/reports/sales-representative-total-expense', [ReportController::class, 'getSalesRepresentativeTotalExpense']);
    Route::get('/reports/sales-representative-total-sell', [ReportController::class, 'getSalesRepresentativeTotalSell']);
    Route::get('/reports/sales-representative-total-commission', [ReportController::class, 'getSalesRepresentativeTotalCommission']);
    Route::get('/reports/stock-expiry', [ReportController::class, 'getStockExpiryReport']);
    Route::get('/reports/stock-expiry-edit-modal/{purchase_line_id}', [ReportController::class, 'getStockExpiryReportEditModal']);
    Route::post('/reports/stock-expiry-update', [ReportController::class, 'updateStockExpiryReport'])->name('updateStockExpiryReport');
    Route::get('/reports/customer-group', [ReportController::class, 'getCustomerGroup']);
    Route::get('/reports/product-purchase-report', [ReportController::class, 'getproductPurchaseReport']);
    Route::get('/reports/product-sell-grouped-by', [ReportController::class, 'productSellReportBy']);
    Route::get('/reports/product-sell-report', [ReportController::class, 'getproductSellReport']);
    Route::get('/reports/product-sell-report-with-purchase', [ReportController::class, 'getproductSellReportWithPurchase']);
    Route::get('/reports/product-sell-grouped-report', [ReportController::class, 'getproductSellGroupedReport']);
    Route::get('/reports/lot-report', [ReportController::class, 'getLotReport']);
    Route::get('/reports/purchase-payment-report', [ReportController::class, 'purchasePaymentReport']);
    Route::get('/reports/sell-payment-report', [ReportController::class, 'sellPaymentReport']);
    Route::get('/reports/product-stock-details', [ReportController::class, 'productStockDetails']);
    Route::get('/reports/adjust-product-stock', [ReportController::class, 'adjustProductStock']);
    Route::get('/reports/get-profit/{by?}', [ReportController::class, 'getProfit']);
    Route::get('/reports/items-report', [ReportController::class, 'itemsReport']);
    Route::get('/reports/get-stock-value', [ReportController::class, 'getStockValue']);

    Route::get('business-location/activate-deactivate/{location_id}', [BusinessLocationController::class, 'activateDeactivateLocation']);

    //Business Location Settings...
    Route::prefix('business-location/{location_id}')->name('location.')->group(function () {
        Route::get('settings', [LocationSettingsController::class, 'index'])->name('settings');
        Route::post('settings', [LocationSettingsController::class, 'updateSettings'])->name('settings_update');
    });

    //Business Locations...
    Route::post('business-location/check-location-id', [BusinessLocationController::class, 'checkLocationId']);
    Route::resource('business-location', BusinessLocationController::class);

    //Invoice layouts..
    Route::resource('invoice-layouts', InvoiceLayoutController::class);

    Route::post('get-expense-sub-categories', [ExpenseCategoryController::class, 'getSubCategories']);

    //Expense Categories...
    Route::resource('expense-categories', ExpenseCategoryController::class);

    //Expenses...
    Route::resource('expenses', ExpenseController::class);
    Route::get('import-expense', [ExpenseController::class, 'importExpense']);
    Route::post('store-import-expense', [ExpenseController::class, 'storeExpenseImport']);

    //Transaction payments...
    // Route::get('/payments/opening-balance/{contact_id}', 'TransactionPaymentController@getOpeningBalancePayments');
    Route::get('/payments/show-child-payments/{payment_id}', [TransactionPaymentController::class, 'showChildPayments']);
    Route::get('/payments/view-payment/{payment_id}', [TransactionPaymentController::class, 'viewPayment']);
    Route::get('/payments/add_payment/{transaction_id}', [TransactionPaymentController::class, 'addPayment']);
    Route::get('/payments/pay-contact-due/{contact_id}', [TransactionPaymentController::class, 'getPayContactDue']);
    Route::post('/payments/pay-contact-due', [TransactionPaymentController::class, 'postPayContactDue']);
    Route::resource('payments', TransactionPaymentController::class);

    //Printers...
    Route::resource('printers', PrinterController::class);

    Route::get('/stock-adjustments/remove-expired-stock/{purchase_line_id}', [StockAdjustmentController::class, 'removeExpiredStock']);
    Route::post('/stock-adjustments/get_product_row', [StockAdjustmentController::class, 'getProductRow']);
    Route::resource('stock-adjustments', StockAdjustmentController::class);

    Route::get('/cash-register/register-details', [CashRegisterController::class, 'getRegisterDetails']);
    Route::get('/cash-register/close-register/{id?}', [CashRegisterController::class, 'getCloseRegister']);
    Route::post('/cash-register/close-register', [CashRegisterController::class, 'postCloseRegister']);
    Route::resource('cash-register', CashRegisterController::class);

    //Import products
    Route::get('/import-products', [ImportProductsController::class, 'index'])->name('import-products.index');
    Route::post('/import-products/store', [ImportProductsController::class, 'store'])->name('import-products.store');

    //Sales Commission Agent
    Route::resource('sales-commission-agents', SalesCommissionAgentController::class);

    //Stock Transfer
    Route::get('stock-transfers/print/{id}', [StockTransferController::class, 'printInvoice']);
    Route::post('stock-transfers/update-status/{id}', [StockTransferController::class, 'updateStatus']);
    Route::resource('stock-transfers', StockTransferController::class);

    Route::get('/opening-stock/add/{product_id}', [OpeningStockController::class, 'add']);
    Route::post('/opening-stock/save', [OpeningStockController::class, 'save']);

    //Customer Groups
    Route::resource('customer-group', CustomerGroupController::class);

    //Import opening stock
    Route::get('/import-opening-stock', [ImportOpeningStockController::class, 'index'])->name('import-opening-stock.index');
    Route::post('/import-opening-stock/store', [ImportOpeningStockController::class, 'store'])->name('import-opening-stock.store');

    //Sell return
    Route::get('validate-invoice-to-return/{invoice_no}', [SellReturnController::class, 'validateInvoiceToReturn']);
    // service staff replacement
    Route::get('validate-invoice-to-service-staff-replacement/{invoice_no}', [SellPosController::class, 'validateInvoiceToServiceStaffReplacement']);
    Route::put('change-service-staff/{id}', [SellPosController::class, 'change_service_staff'])->name('change_service_staff');

    Route::resource('sell-return', SellReturnController::class)->names('sell-return');
    Route::get('sell-return/get-product-row', [SellReturnController::class, 'getProductRow'])->name('sell-return.get-product-row');
    Route::get('/sell-return/print/{id}', [SellReturnController::class, 'printInvoice'])->name('sell-return.print');
    Route::get('/sell-return/add/{id}', [SellReturnController::class, 'add'])->name('sell-return.add');

    //Backup
    Route::get('backup/download/{file_name}', [BackUpController::class, 'download']);
    Route::get('backup/{id}/delete', [BackUpController::class, 'delete'])->name('delete_backup');
    Route::resource('backup', BackUpController::class)->only('index', 'create', 'store');

    Route::get('selling-price-group/activate-deactivate/{id}', [SellingPriceGroupController::class, 'activateDeactivate']);
    Route::get('update-product-price', [SellingPriceGroupController::class, 'updateProductPrice'])->name('update-product-price');
    Route::get('export-product-price', [SellingPriceGroupController::class, 'export']);
    Route::post('import-product-price', [SellingPriceGroupController::class, 'import']);

    Route::resource('selling-price-group', SellingPriceGroupController::class);

    Route::resource('notification-templates', NotificationTemplateController::class)->only(['index', 'store']);
    Route::get('notification/get-template/{transaction_id}/{template_for}', [NotificationController::class, 'getTemplate']);
    Route::post('notification/send', [NotificationController::class, 'send']);

    Route::post('/purchase-return/update', [CombinedPurchaseReturnController::class, 'update']);
    Route::get('/purchase-return/edit/{id}', [CombinedPurchaseReturnController::class, 'edit']);
    Route::post('/purchase-return/save', [CombinedPurchaseReturnController::class, 'save']);
    Route::post('/purchase-return/get_product_row', [CombinedPurchaseReturnController::class, 'getProductRow']);
    Route::get('/purchase-return/create', [CombinedPurchaseReturnController::class, 'create']);
    Route::get('/purchase-return/add/{id}', [PurchaseReturnController::class, 'add']);
    Route::resource('/purchase-return', PurchaseReturnController::class)->except('create');

    Route::get('/discount/activate/{id}', [DiscountController::class, 'activate'])->name('discount.activate');
    Route::post('/discount/mass-deactivate', [DiscountController::class, 'massDeactivate'])->name('discount.mass-deactivate');
    Route::resource('discount', DiscountController::class)->names('discount');

    Route::resource('account-types', AccountTypeController::class);

    //Restaurant module
    Route::prefix('modules')->group(function () {
        Route::resource('tables', Restaurant\TableController::class);
        Route::resource('modifiers', Restaurant\ModifierSetsController::class);

        //Map modifier to products
        Route::get('/product-modifiers/{id}/edit', [Restaurant\ProductModifierSetController::class, 'edit']);
        Route::post('/product-modifiers/{id}/update', [Restaurant\ProductModifierSetController::class, 'update']);
        Route::get('/product-modifiers/product-row/{product_id}', [Restaurant\ProductModifierSetController::class, 'product_row']);

        Route::get('/add-selected-modifiers', [Restaurant\ProductModifierSetController::class, 'add_selected_modifiers']);

        Route::get('/kitchen', [Restaurant\KitchenController::class, 'index']);
        Route::get('/kitchen/mark-as-cooked/{id}', [Restaurant\KitchenController::class, 'markAsCooked']);
        Route::post('/refresh-orders-list', [Restaurant\KitchenController::class, 'refreshOrdersList']);
        Route::post('/refresh-line-orders-list', [Restaurant\KitchenController::class, 'refreshLineOrdersList']);

        Route::get('/orders', [Restaurant\OrderController::class, 'index']);
        Route::get('/orders/mark-as-served/{id}', [Restaurant\OrderController::class, 'markAsServed']);
        Route::get('/data/get-pos-details', [Restaurant\DataController::class, 'getPosDetails']);
        Route::get('/data/check-staff-pin', [Restaurant\DataController::class, 'checkStaffPin']);
        Route::get('/orders/mark-line-order-as-served/{id}', [Restaurant\OrderController::class, 'markLineOrderAsServed']);
        Route::get('/print-line-order', [Restaurant\OrderController::class, 'printLineOrder']);
    });

    Route::get('bookings/get-todays-bookings', [Restaurant\BookingController::class, 'getTodaysBookings']);
    Route::resource('bookings', Restaurant\BookingController::class);

    Route::resource('types-of-service', TypesOfServiceController::class);
    Route::get('sells/edit-shipping/{id}', [SellController::class, 'editShipping'])->name('sells.edit-shipping');
    Route::put('sells/update-shipping/{id}', [SellController::class, 'updateShipping'])->name('sells.update-shipping');
    Route::get('shipments', [SellController::class, 'shipments'])->name('shipments');

    Route::post('upload-module', [Install\ModulesController::class, 'uploadModule']);
    Route::delete('manage-modules/destroy/{module_name}', [Install\ModulesController::class, 'destroy']);
    Route::resource('manage-modules', Install\ModulesController::class)
        ->only(['index', 'update']);
    Route::get('regenerate', [Install\ModulesController::class, 'regenerate']);

    Route::resource('warranties', WarrantyController::class);

    Route::resource('dashboard-configurator', DashboardConfiguratorController::class)
        ->only(['edit', 'update']);

    Route::get('view-media/{model_id}', [SellController::class, 'viewMedia']);

    //common controller for document & note
    Route::get('get-document-note-page', [DocumentAndNoteController::class, 'getDocAndNoteIndexPage']);
    Route::post('post-document-upload', [DocumentAndNoteController::class, 'postMedia']);
    Route::resource('note-documents', DocumentAndNoteController::class);
    Route::resource('purchase-order', PurchaseOrderController::class);
    Route::get('get-purchase-orders/{contact_id}', [PurchaseOrderController::class, 'getPurchaseOrders']);
    Route::get('get-purchase-order-lines/{purchase_order_id}', [PurchaseController::class, 'getPurchaseOrderLines']);
    Route::get('edit-purchase-orders/{id}/status', [PurchaseOrderController::class, 'getEditPurchaseOrderStatus']);
    Route::put('update-purchase-orders/{id}/status', [PurchaseOrderController::class, 'postEditPurchaseOrderStatus']);
    Route::resource('sales-order', SalesOrderController::class)->only(['index']);
    Route::get('get-sales-orders/{customer_id}', [SalesOrderController::class, 'getSalesOrders']);
    Route::get('get-sales-order-lines', [SellPosController::class, 'getSalesOrderLines']);
    Route::get('edit-sales-orders/{id}/status', [SalesOrderController::class, 'getEditSalesOrderStatus']);
    Route::put('update-sales-orders/{id}/status', [SalesOrderController::class, 'postEditSalesOrderStatus']);
    Route::get('reports/activity-log', [ReportController::class, 'activityLog']);
    Route::get('user-location/{latlng}', [HomeController::class, 'getUserLocation']);
});

// Route::middleware(['EcomApi'])->prefix('api/ecom')->group(function () {
//     Route::get('products/{id?}', [ProductController::class, 'getProductsApi']);
//     Route::get('categories', [CategoryController::class, 'getCategoriesApi']);
//     Route::get('brands', [BrandController::class, 'getBrandsApi']);
//     Route::post('customers', [ContactController::class, 'postCustomersApi']);
//     Route::get('settings', [BusinessController::class, 'getEcomSettings']);
//     Route::get('variations', [ProductController::class, 'getVariationsApi']);
//     Route::post('orders', [SellPosController::class, 'placeOrdersApi']);
// });

//common route
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['setData', 'auth', 'SetSessionData', 'language', 'timezone'])->group(function () {
    Route::get('/load-more-notifications', [HomeController::class, 'loadMoreNotifications']);
    Route::get('/get-total-unread', [HomeController::class, 'getTotalUnreadNotifications']);
    Route::get('/purchases/print/{id}', [PurchaseController::class, 'printInvoice']);
    Route::get('/purchases/{id}', [PurchaseController::class, 'show']);
    Route::get('/download-purchase-order/{id}/pdf', [PurchaseOrderController::class, 'downloadPdf'])->name('purchaseOrder.downloadPdf');
    Route::get('/sells/{id}', [SellController::class, 'show']);
    Route::get('/sells/{transaction_id}/print', [SellPosController::class, 'printInvoice'])->name('sell.printInvoice');
    Route::get('/download-sells/{transaction_id}/pdf', [SellPosController::class, 'downloadPdf'])->name('sell.downloadPdf');
    Route::get('/download-quotation/{id}/pdf', [SellPosController::class, 'downloadQuotationPdf'])
        ->name('quotation.downloadPdf');
    Route::get('/download-packing-list/{id}/pdf', [SellPosController::class, 'downloadPackingListPdf'])
        ->name('packing.downloadPdf');
    Route::get('/sells/invoice-url/{id}', [SellPosController::class, 'showInvoiceUrl']);
    Route::get('/show-notification/{id}', [HomeController::class, 'showNotification']);
    Route::post('/sell/check-invoice-number', [SellController::class, 'checkInvoiceNumber']);
});
