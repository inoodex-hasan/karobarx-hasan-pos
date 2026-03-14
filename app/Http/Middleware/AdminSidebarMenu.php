<?php

namespace App\Http\Middleware;

use App\Utils\ModuleUtil;
use Closure;
use Menu;
use Modules\CustomDashboard\Entities\CustomDashboard;

class AdminSidebarMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);
        }

        Menu::create('admin-sidebar-menu', function ($menu) {
            $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

            // Access common_settings from business object (stored in session as Eloquent model)
            $business = session('business');
            // Access common_settings - handle both array and object cases
            if (is_object($business) && isset($business->common_settings)) {
                $common_settings = $business->common_settings;
            } elseif (is_array($business) && isset($business['common_settings'])) {
                $common_settings = $business['common_settings'];
            } else {
                $common_settings = [];
            }
            // Ensure common_settings is an array (it might be a JSON string in some cases)
            if (is_string($common_settings)) {
                $common_settings = json_decode($common_settings, true) ?? [];
            }
            $pos_settings = !empty(session('business.pos_settings')) ? json_decode(session('business.pos_settings'), true) : [];

            $is_admin = auth()->user()->hasRole('Admin#' . session('business.id')) ? true : false;
            // Check the layout_template setting to determine if viho template is active
            $layout_template = !empty($common_settings['layout_template']) ? $common_settings['layout_template'] : 'default';
            $is_ai_template = $layout_template === 'viho';
            //Home
            //     $menu->url(action([\App\Http\Controllers\HomeController::class, 'index']), __('home.home'), ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            //     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            //     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            //     <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
            //     <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
            //     <path d="M10 12h4v4h-4z"></path>
            //   </svg>', 'active' => request()->segment(1) == 'home'])->order(5);

            
                  
            $home_url = $is_ai_template ? route('ai-template.home') : route('home');
            $menu->url($home_url, __('home.home'), ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="tw-size-5 tw-shrink-0" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
            <path d="M10 12h4v4h-4z" />
          </svg>', 'active' => request()->segment(1) == 'home'])->order(5);

            //User management dropdown
            if (auth()->user()->can('user.view') || auth()->user()->can('user.create') || auth()->user()->can('roles.view')) {
                $menu->dropdown(
                    __('user.user_management'),
                    function ($sub) use ($is_ai_template) {
                        if (auth()->user()->can('user.view')) {
                            $users_url = $is_ai_template ? route('ai-template.users.index') : route('users.index');
                            $sub->url(
                                $users_url,
                                __('user.users'),
                                ['icon' => '', 'active' => request()->segment(1) == 'users' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'users')]
                            );
                        }
                        if (auth()->user()->can('roles.view')) {
                            $roles_url = $is_ai_template ? route('ai-template.roles.index') : route('roles.index');
                            $sub->url(
                                $roles_url,
                                __('user.roles'),
                                ['icon' => '', 'active' => request()->segment(1) == 'roles' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'roles')]
                            );
                        }
                        if (auth()->user()->can('user.create')) {
                            $agents_url = $is_ai_template ? route('ai-template.sales-commission-agents.index') : route('sales-commission-agents.index');
                            $sub->url(
                                $agents_url,
                                __('lang_v1.sales_commission_agents'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sales-commission-agents' || (request()->segment(1) == 'ai-template' && in_array(request()->segment(2), ['sales-comission-agents', 'sales-commission-agents']))]
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                  </svg>', ]
                )->order(10);
            }

            //Contacts dropdown
            if (auth()->user()->can('supplier.view') || auth()->user()->can('customer.view') || auth()->user()->can('supplier.view_own') || auth()->user()->can('customer.view_own')) {
                $menu->dropdown(
                    __('contact.contacts'),
                    function ($sub) use ($is_ai_template) {
                        if (auth()->user()->can('supplier.view') || auth()->user()->can('supplier.view_own')) {
                            $suppliers_url = $is_ai_template
                                ? route('ai-template.contacts.index', ['type' => 'supplier'])
                                : route('contacts.index', ['type' => 'supplier']);
                            $sub->url(
                                $suppliers_url,
                                __('report.supplier'),
                                ['icon' => '', 'active' => request()->input('type') == 'supplier']
                            );
                        }
                        if (auth()->user()->can('customer.view') || auth()->user()->can('customer.view_own')) {
                            $customers_url = $is_ai_template
                                ? route('ai-template.contacts.index', ['type' => 'customer'])
                                : route('contacts.index', ['type' => 'customer']);
                            $sub->url(
                                $customers_url,
                                __('report.customer'),
                                ['icon' => '', 'active' => request()->input('type') == 'customer']
                            );

                            $customer_groups_url = $is_ai_template
                                ? route('ai-template.customer-group.index')
                                : route('customer-group.index');
                            $sub->url(
                                $customer_groups_url,
                                __('lang_v1.customer_groups'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'customer-group' : request()->segment(1) == 'customer-group']
                            );
                        }
                        if (auth()->user()->can('supplier.create') || auth()->user()->can('customer.create')) {
                            $import_contacts_url = $is_ai_template
                                ? route('ai-template.contacts.import')
                                : route('contacts.import');
                            $sub->url(
                                $import_contacts_url,
                                __('lang_v1.import_contacts'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'contacts' && request()->segment(3) == 'import' : request()->segment(1) == 'contacts' && request()->segment(2) == 'import']
                            );
                        }

                        if (!empty(env('GOOGLE_MAP_API_KEY'))) {
                            $contacts_map_url = $is_ai_template
                                ? route('ai-template.contacts.map')
                                : url('/contacts/map');
                            $sub->url(
                                $contacts_map_url,
                                __('lang_v1.map'),
                                ['icon' => 'fa fas fa-map-marker-alt', 'active' => $is_ai_template ? request()->segment(2) == 'contacts' && request()->segment(3) == 'map' : request()->segment(1) == 'contacts' && request()->segment(2) == 'map']
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M20 6v12a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2z"></path>
                    <path d="M10 16h6"></path>
                    <path d="M13 11m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                    <path d="M4 8h3"></path>
                    <path d="M4 12h3"></path>
                    <path d="M4 16h3"></path>
                  </svg>', 'id' => 'tour_step4']
                )->order(15);
            }

            //Products dropdown
            if (auth()->user()->can('product.view') || auth()->user()->can('product.create') ||
                auth()->user()->can('brand.view') || auth()->user()->can('unit.view') ||
                auth()->user()->can('category.view') || auth()->user()->can('brand.create') ||
                auth()->user()->can('unit.create') || auth()->user()->can('category.create')) {
                $menu->dropdown(
                    __('sale.products'),
                    function ($sub) use ($is_ai_template) {
                        if (auth()->user()->can('product.view')) {
                            $products_url = $is_ai_template ? route('ai-template.products.index') : route('products.index');
                            $sub->url(
                                $products_url,
                                __('lang_v1.list_products'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'products' && request()->segment(3) == '' : request()->segment(1) == 'products' && request()->segment(2) == '']
                            );
                        }

                        if (auth()->user()->can('product.create')) {
                            $product_create_url = $is_ai_template ? route('ai-template.products.create') : route('products.create');
                            $sub->url(
                                $product_create_url,
                                __('product.add_product'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'products' && request()->segment(3) == 'create' : request()->segment(1) == 'products' && request()->segment(2) == 'create']
                            );
                        }
                        if (auth()->user()->can('product.create')) {
                            $update_price_url = $is_ai_template ? route('ai-template.update-product-price') : route('update-product-price');
                            $sub->url(
                                $update_price_url,
                                __('lang_v1.update_product_price'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'update-product-price' : request()->segment(1) == 'update-product-price']
                            );
                        }
                        if (auth()->user()->can('product.view')) {
                            $labels_url = $is_ai_template ? route('ai-template.labels.show') : route('labels.show');
                            $sub->url(
                                $labels_url,
                                __('barcode.print_labels'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'labels' && request()->segment(3) == 'show' : request()->segment(1) == 'labels' && request()->segment(2) == 'show']
                            );
                        }
                        if (auth()->user()->can('product.create')) {
                            $variations_url = $is_ai_template ? route('ai-template.variation-templates.index') : route('variation-templates.index');
                            $sub->url(
                                $variations_url,
                                __('product.variations'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'variation-templates' : request()->segment(1) == 'variation-templates']
                            );
                            $import_products_url = $is_ai_template ? route('ai-template.import-products.index') : route('import-products.index');
                            $sub->url(
                                $import_products_url,
                                __('product.import_products'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'import-products' : request()->segment(1) == 'import-products']
                            );
                        }
                        if (auth()->user()->can('product.opening_stock')) {
                            $import_opening_url = $is_ai_template ? route('ai-template.import-opening-stock.index') : route('import-opening-stock.index');
                            $sub->url(
                                $import_opening_url,
                                __('lang_v1.import_opening_stock'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'import-opening-stock' : request()->segment(1) == 'import-opening-stock']
                            );
                        }
                        if (auth()->user()->can('product.create')) {
                            $selling_price_url = $is_ai_template ? route('ai-template.selling-price-group.index') : route('selling-price-group.index');
                            $sub->url(
                                $selling_price_url,
                                __('lang_v1.selling_price_group'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'selling-price-group' : request()->segment(1) == 'selling-price-group']
                            );
                        }
                        if (auth()->user()->can('unit.view') || auth()->user()->can('unit.create')) {
                            $units_url = $is_ai_template ? route('ai-template.units.index') : route('units.index');
                            $sub->url(
                                $units_url,
                                __('unit.units'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'units' : request()->segment(1) == 'units']
                            );
                        }
                        if (auth()->user()->can('category.view') || auth()->user()->can('category.create')) {
                            $categories_url = $is_ai_template ? '/ai-template/taxonomies?type=product' : route('taxonomies.index') . '?type=product';
                            $sub->url(
                                $categories_url,
                                __('category.categories'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'taxonomies' && request()->get('type') == 'product' : request()->segment(1) == 'taxonomies' && request()->get('type') == 'product']
                            );
                        }
                        if (auth()->user()->can('brand.view') || auth()->user()->can('brand.create')) {
                            $brands_url = $is_ai_template ? route('ai-template.brands.index') : route('brands.index');
                            $sub->url(
                                $brands_url,
                                __('brand.brands'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'brands' : request()->segment(1) == 'brands']
                            );
                        }

                        $warranties_url = $is_ai_template ? route('ai-template.warranties.index') : route('warranties.index');
                        $sub->url(
                            $warranties_url,
                            __('lang_v1.warranties'),
                            ['icon' => '', 'active' => $is_ai_template ? (request()->segment(1) == 'ai-template' && request()->segment(2) == 'warranties') : (request()->segment(1) == 'warranties')]
                        );
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5"></path>
                    <path d="M12 12l8 -4.5"></path>
                    <path d="M8.2 9.8l7.6 -4.6"></path>
                    <path d="M12 12v9"></path>
                    <path d="M12 12l-8 -4.5"></path>
                  </svg>', 'id' => 'tour_step5']
                )->order(20);
            }

            //Purchase dropdown
            if (in_array('purchases', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') || auth()->user()->can('purchase.update'))) {
                $menu->dropdown(
                    __('purchase.purchases'),
                    function ($sub) use ($common_settings, $is_ai_template) {
                        if (!empty($common_settings['enable_purchase_requisition']) && (auth()->user()->can('purchase_requisition.view_all') || auth()->user()->can('purchase_requisition.view_own'))) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseRequisitionController::class, 'index']),
                                __('lang_v1.purchase_requisition'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchase-requisition']
                            );
                        }

                        if (!empty($common_settings['enable_purchase_order']) && (auth()->user()->can('purchase_order.view_all') || auth()->user()->can('purchase_order.view_own'))) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseOrderController::class, 'index']),
                                __('lang_v1.purchase_order'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchase-order']
                            );
                        }
                        if (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase')) {
                            $purchase_list_url = $is_ai_template ? route('ai-template.purchases.index') : route('purchases.index');
                            $sub->url(
                                $purchase_list_url,
                                __('purchase.list_purchase'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'purchases' && request()->segment(3) == null : request()->segment(1) == 'purchases' && request()->segment(2) == null]
                            );
                        }
                        if (auth()->user()->can('purchase.create')) {
                            $purchase_create_url = $is_ai_template ? route('ai-template.purchases.create') : route('purchases.create');
                            $sub->url(
                                $purchase_create_url,
                                __('purchase.add_purchase'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'purchases' && request()->segment(3) == 'create' : request()->segment(1) == 'purchases' && request()->segment(2) == 'create']
                            );
                        }
                        if (auth()->user()->can('purchase.update')) {
                            $purchase_return_list_url = $is_ai_template ? route('ai-template.purchase-return.index') : route('purchase-return.index');
                            $sub->url(
                                $purchase_return_list_url,
                                __('lang_v1.list_purchase_return'),
                                ['icon' => '', 'active' => $is_ai_template ? request()->segment(2) == 'purchase-return' : request()->segment(1) == 'purchase-return']
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 3v12"></path>
                    <path d="M16 11l-4 4l-4 -4"></path>
                    <path d="M3 12a9 9 0 0 0 18 0"></path>
                  </svg>', 'id' => 'tour_step6']
                )->order(25);
            }
            //Sell dropdown
            if ($is_admin || auth()->user()->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'view_own_sell_only', 'view_commission_agent_sell', 'access_shipping', 'access_own_shipping', 'access_commission_agent_shipping', 'access_sell_return', 'direct_sell.view', 'direct_sell.update', 'access_own_sell_return'])) {
                $menu->dropdown(
                    __('sale.sale'),
                    function ($sub) use ($enabled_modules, $is_admin, $pos_settings, $is_ai_template) {
                        if (!empty($pos_settings['enable_sales_order']) && ($is_admin || auth()->user()->hasAnyPermission(['so.view_own', 'so.view_all', 'so.create']))) {
                            $sales_order_url = $is_ai_template ? route('ai-template.sales-order.index') : route('sales-order.index');
                            $sub->url(
                                $sales_order_url,
                                __('lang_v1.sales_order'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'sales-order') || request()->segment(1) == 'sales-order']
                            );
                        }

                        if ($is_admin || auth()->user()->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'direct_sell.view', 'view_own_sell_only', 'view_commission_agent_sell', 'access_shipping', 'access_own_shipping', 'access_commission_agent_shipping'])) {
                            $sell_list_url = $is_ai_template ? route('ai-template.sells.index') : route('sells.index');
                            $sub->url(
                                $sell_list_url,
                                __('lang_v1.all_sales'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'sells' && request()->segment(3) == null) || (request()->segment(1) == 'sells' && request()->segment(2) == null)]
                            );
                        }
                        if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                            $sell_create_url = $is_ai_template ? route('ai-template.sells.create') : route('sells.create');
                            $sub->url(
                                $sell_create_url,
                                __('sale.add_sale'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'sells' && request()->segment(3) == 'create' && empty(request()->get('status'))) || (request()->segment(1) == 'sells' && request()->segment(2) == 'create' && empty(request()->get('status')))]
                            );
                        }
                        if (auth()->user()->can('sell.create')) {
                            if (in_array('pos_sale', $enabled_modules)) {
                                if (auth()->user()->can('sell.view')) {
                                    $pos_list_url = $is_ai_template ? route('ai-template.pos.index') : route('pos.index');
                                    $sub->url(
                                        $pos_list_url,
                                        __('sale.list_pos'),
                                        ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'pos' && request()->segment(3) == null) || (request()->segment(1) == 'pos' && request()->segment(2) == null)]
                                    );
                                }

                                $pos_create_url = $is_ai_template ? route('ai-template.pos.create') : route('pos.create');
                                $sub->url(
                                    $pos_create_url,
                                    __('sale.pos_sale'),
                                    ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'pos' && request()->segment(3) == 'create') || (request()->segment(1) == 'pos' && request()->segment(2) == 'create')]
                                );
                            }
                        }

                        if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                            $add_draft_url = $is_ai_template ? route('ai-template.sells.create', ['status' => 'draft']) : route('sells.create', ['status' => 'draft']);
                            $sub->url(
                                $add_draft_url,
                                __('lang_v1.add_draft'),
                                ['icon' => '', 'active' => request()->get('status') == 'draft']
                            );
                        }
                        if (in_array('add_sale', $enabled_modules) && ($is_admin || auth()->user()->hasAnyPermission(['draft.view_all', 'draft.view_own']))) {
                            $drafts_url = $is_ai_template ? route('ai-template.sells.drafts') : route('sells.drafts');
                            $sub->url(
                                $drafts_url,
                                __('lang_v1.list_drafts'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'sells' && request()->segment(3) == 'drafts') || (request()->segment(1) == 'sells' && request()->segment(2) == 'drafts')]
                            );
                        }
                        if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                            $add_quotation_url = $is_ai_template ? route('ai-template.sells.create', ['status' => 'quotation']) : route('sells.create', ['status' => 'quotation']);
                            $sub->url(
                                $add_quotation_url,
                                __('lang_v1.add_quotation'),
                                ['icon' => '', 'active' => request()->get('status') == 'quotation']
                            );
                        }
                        if (in_array('add_sale', $enabled_modules) && ($is_admin || auth()->user()->hasAnyPermission(['quotation.view_all', 'quotation.view_own']))) {
                            $quotes_url = $is_ai_template ? route('ai-template.sells.quotations') : route('sells.quotations');
                            $sub->url(
                                $quotes_url,
                                __('lang_v1.list_quotations'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'sells' && request()->segment(3) == 'quotations') || (request()->segment(1) == 'sells' && request()->segment(2) == 'quotations')]
                            );
                        }

                        if (auth()->user()->can('access_sell_return') || auth()->user()->can('access_own_sell_return')) {
                            $sell_return_url = $is_ai_template ? route('ai-template.sell-return.index') : route('sell-return.index');
                            $sub->url(
                                $sell_return_url,
                                __('lang_v1.list_sell_return'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'sell-return') || (request()->segment(1) == 'sell-return' && request()->segment(2) == null)]
                            );
                        }

                        if ($is_admin || auth()->user()->hasAnyPermission(['access_shipping', 'access_own_shipping', 'access_commission_agent_shipping'])) {
                            $shipments_url = $is_ai_template ? route('ai-template.shipments') : route('shipments');
                            $sub->url(
                                $shipments_url,
                                __('lang_v1.shipments'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'shipments') || (request()->segment(1) == 'shipments')]
                            );
                        }

                        if (auth()->user()->can('discount.access')) {
                            $discount_url = $is_ai_template ? route('ai-template.discount.index') : route('discount.index');
                            $sub->url(
                                $discount_url,
                                __('lang_v1.discounts'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'discount') || (request()->segment(1) == 'discount')]
                            );
                        }
                        if (in_array('subscription', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                            $subscriptions_url = $is_ai_template ? route('ai-template.sells.subscriptions') : route('sells.subscriptions');
                            $sub->url(
                                $subscriptions_url,
                                __('lang_v1.subscriptions'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'sells' && request()->segment(3) == 'subscriptions') || (request()->segment(1) == 'subscriptions')]
                            );
                        }

                        if (auth()->user()->can('sell.create')) {
                            $import_sales_url = $is_ai_template ? route('ai-template.import-sales.index') : route('import-sales.index');
                            $sub->url(
                                $import_sales_url,
                                __('lang_v1.import_sales'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'import-sales') || (request()->segment(1) == 'import-sales')]
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 15v-12"></path>
                    <path d="M16 7l-4 -4l-4 4"></path>
                    <path d="M3 12a9 9 0 0 0 18 0"></path>
                  </svg>', 'id' => 'tour_step7']
                )->order(30);
            }

            //Stock transfer dropdown
            if (in_array('stock_transfers', $enabled_modules) && (auth()->user()->can('stock_transfer.view') || auth()->user()->can('stock_transfer.create') || auth()->user()->can('stock_transfer.view_own'))) {
                $menu->dropdown(
                    __('lang_v1.stock_transfers'),
                    function ($sub) use ($is_ai_template) {
                        if (auth()->user()->can('stock_transfer.view') || auth()->user()->can('stock_transfer.view_own')) {
                            $stock_transfer_list_url = $is_ai_template ? route('ai-template.stock-transfers.index') : route('stock-transfers.index');
                            $sub->url(
                                $stock_transfer_list_url,
                                __('lang_v1.list_stock_transfers'),
                                ['icon' => '', 'active' => request()->segment(1) == 'stock-transfers' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'stock-transfers' && request()->segment(3) == null)]
                            );
                        }
                        if (auth()->user()->can('stock_transfer.create')) {
                            $stock_transfer_create_url = $is_ai_template ? route('ai-template.stock-transfers.create') : route('stock-transfers.create');
                            $sub->url(
                                $stock_transfer_create_url,
                                __('lang_v1.add_stock_transfer'),
                                ['icon' => '', 'active' => (request()->segment(1) == 'stock-transfers' && request()->segment(2) == 'create') || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'stock-transfers' && request()->segment(3) == 'create')]
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                    <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                    <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5"></path>
                    <path d="M3 9l4 0"></path>
                  </svg>']
                )->order(35);
            }

            //stock adjustment dropdown
            if (in_array('stock_adjustment', $enabled_modules) && (auth()->user()->can('stock_adjustment.view') || auth()->user()->can('stock_adjustment.create') || auth()->user()->can('view_own_stock_adjustment'))) {
                $menu->dropdown(
                    __('stock_adjustment.stock_adjustment'),
                    function ($sub) use ($is_ai_template) {
                        if (auth()->user()->can('stock_adjustment.view')  || auth()->user()->can('view_own_stock_adjustment')) {
                            $stock_adjustment_list_url = $is_ai_template ? route('ai-template.stock-adjustments.index') : action([\App\Http\Controllers\StockAdjustmentController::class, 'index']);
                            $sub->url(
                                $stock_adjustment_list_url,
                                __('stock_adjustment.list'),
                                ['icon' => '', 'active' => request()->segment(1) == 'stock-adjustments' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'stock-adjustments' && request()->segment(3) == null)]
                            );
                        }
                        if (auth()->user()->can('stock_adjustment.create')) {
                            $stock_adjustment_create_url = $is_ai_template ? route('ai-template.stock-adjustments.create') : action([\App\Http\Controllers\StockAdjustmentController::class, 'create']);
                            $sub->url(
                                $stock_adjustment_create_url,
                                __('stock_adjustment.add'),
                                ['icon' => '', 'active' => (request()->segment(1) == 'stock-adjustments' && request()->segment(2) == 'create') || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'stock-adjustments' && request()->segment(3) == 'create')]
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 6m-8 0a8 3 0 1 0 16 0a8 3 0 1 0 -16 0"></path>
                    <path d="M4 6v6a8 3 0 0 0 16 0v-6"></path>
                    <path d="M4 12v6a8 3 0 0 0 16 0v-6"></path>
                  </svg>']
                )->order(40);
            }

            //Expense dropdown
            if (in_array('expenses', $enabled_modules) && (auth()->user()->can('all_expense.access') || auth()->user()->can('view_own_expense'))) {
                $menu->dropdown(
                    __('expense.expenses'),
                    function ($sub) use ($is_ai_template) {
                        $expense_list_url = $is_ai_template ? '/ai-template/expenses' : action([\App\Http\Controllers\ExpenseController::class, 'index']);
                        $sub->url(
                            $expense_list_url,
                            __('lang_v1.list_expenses'),
                            ['icon' => '', 'active' => request()->segment(1) == 'expenses' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'expenses' && request()->segment(3) == null)]
                        );

                        if (auth()->user()->can('expense.add')) {
                            $expense_create_url = $is_ai_template ? '/ai-template/expenses/create' : action([\App\Http\Controllers\ExpenseController::class, 'create']);
                            $sub->url(
                                $expense_create_url,
                                __('expense.add_expense'),
                                ['icon' => '', 'active' => (request()->segment(1) == 'expenses' && request()->segment(2) == 'create') || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'expenses' && request()->segment(3) == 'create')]
                            );
                        }

                        if (auth()->user()->can('expense.add') || auth()->user()->can('expense.edit')) {
                            $expense_category_url = $is_ai_template ? '/ai-template/expense-categories' : action([\App\Http\Controllers\ExpenseCategoryController::class, 'index']);
                            $sub->url(
                                $expense_category_url,
                                __('expense.expense_categories'),
                                ['icon' => '', 'active' => request()->segment(1) == 'expense-categories' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'expense-categories')]
                            );
                        }
                    },
                    ['icon' => ' <svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2"></path>
                    <path d="M14.8 8a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1"></path>
                    <path d="M12 6v10"></path>
                  </svg>']
                )->order(45);
            }
            //Accounts dropdown
            if (auth()->user()->can('account.access') && in_array('account', $enabled_modules)) {
                $menu->dropdown(
                    __('lang_v1.payment_accounts'),
                    function ($sub) use ($is_ai_template) {
                        $account_list_url = $is_ai_template ? route('ai-template.account.index') : route('account.index');
                        $sub->url(
                            $account_list_url,
                            __('account.list_accounts'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'account')]
                        );
                        $balance_sheet_url = $is_ai_template ? route('ai-template.account.balance-sheet') : route('account.balance-sheet');
                        $sub->url(
                            $balance_sheet_url,
                            __('account.balance_sheet'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'balance-sheet' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'account' && request()->segment(3) == 'balance-sheet')]
                        );
                        $trial_balance_url = $is_ai_template ? route('ai-template.account.trial-balance') : route('account.trial-balance');
                        $sub->url(
                            $trial_balance_url,
                            __('account.trial_balance'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'trial-balance' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'account' && request()->segment(3) == 'trial-balance')]
                        );
                        $cash_flow_url = $is_ai_template ? route('ai-template.account.cash-flow') : route('account.cash-flow');
                        $sub->url(
                            $cash_flow_url,
                            __('lang_v1.cash_flow'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'cash-flow' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'account' && request()->segment(3) == 'cash-flow')]
                        );
                        $payment_account_report_url = $is_ai_template ? route('ai-template.account.payment-report') : route('account.payment-report');
                        $sub->url(
                            $payment_account_report_url,
                            __('account.payment_account_report'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'payment-account-report' || (request()->segment(1) == 'ai-template' && request()->segment(2) == 'account' && request()->segment(3) == 'payment-account-report')]
                        );
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z"></path>
                    <path d="M3 10l18 0"></path>
                    <path d="M7 15l.01 0"></path>
                    <path d="M11 15l2 0"></path>
                  </svg>']
                )->order(50);
            }

            //Reports dropdown
            if (auth()->user()->can('purchase_n_sell_report.view') || auth()->user()->can('contacts_report.view')
                || auth()->user()->can('stock_report.view') || auth()->user()->can('tax_report.view')
                || auth()->user()->can('trending_product_report.view') || auth()->user()->can('sales_representative.view') || auth()->user()->can('register_report.view')
                || auth()->user()->can('expense_report.view')) {
                $menu->dropdown(
                    __('report.reports'),
                    function ($sub) use ($enabled_modules, $is_admin, $is_ai_template) {
                        if (auth()->user()->can('profit_loss_report.view')) {
                            $profit_loss_url = $is_ai_template ? route('ai-template.reports.profit-loss') : action([\App\Http\Controllers\ReportController::class, 'getProfitLoss']);
                            $sub->url(
                                $profit_loss_url,
                                __('report.profit_loss'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'profit-loss') || (request()->segment(2) == 'profit-loss')]
                            );
                        }
                        if (config('constants.show_report_606') == true) {
                            $report_606_url = $is_ai_template ? route('ai-template.reports.purchase-report') : action([\App\Http\Controllers\ReportController::class, 'purchaseReport']);
                            $sub->url(
                                $report_606_url,
                                'Report 606 (' . __('lang_v1.purchase') . ')',
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'purchase-report') || (request()->segment(2) == 'purchase-report')]
                            );
                        }
                        if (config('constants.show_report_607') == true) {
                            $report_607_url = $is_ai_template ? route('ai-template.reports.sale-report') : action([\App\Http\Controllers\ReportController::class, 'saleReport']);
                            $sub->url(
                                $report_607_url,
                                'Report 607 (' . __('business.sale') . ')',
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'sale-report') || (request()->segment(2) == 'sale-report')]
                            );
                        }
                        if ((in_array('purchases', $enabled_modules) || in_array('add_sale', $enabled_modules) || in_array('pos_sale', $enabled_modules)) && auth()->user()->can('purchase_n_sell_report.view')) {
                            $purchase_sell_url = $is_ai_template ? route('ai-template.reports.purchase-sell') : action([\App\Http\Controllers\ReportController::class, 'getPurchaseSell']);
                            $sub->url(
                                $purchase_sell_url,
                                __('report.purchase_sell_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'purchase-sell') || (request()->segment(2) == 'purchase-sell')]
                            );
                        }

                        if (auth()->user()->can('tax_report.view')) {
                            $tax_report_url = $is_ai_template ? route('ai-template.reports.tax-report') : action([\App\Http\Controllers\ReportController::class, 'getTaxReport']);
                            $sub->url(
                                $tax_report_url,
                                __('report.tax_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'tax-report') || (request()->segment(2) == 'tax-report')]
                            );
                        }
                        if (auth()->user()->can('contacts_report.view')) {
                            $contacts_url = $is_ai_template ? route('ai-template.reports.customer-supplier') : action([\App\Http\Controllers\ReportController::class, 'getCustomerSuppliers']);
                            $sub->url(
                                $contacts_url,
                                __('report.contacts'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'customer-supplier') || (request()->segment(2) == 'customer-supplier')]
                            );
                            $customer_groups_url = $is_ai_template ? route('ai-template.reports.customer-group') : action([\App\Http\Controllers\ReportController::class, 'getCustomerGroup']);
                            $sub->url(
                                $customer_groups_url,
                                __('lang_v1.customer_groups_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'customer-group') || (request()->segment(2) == 'customer-group')]
                            );
                        }
                        if (auth()->user()->can('stock_report.view')) {
                            $stock_report_url = $is_ai_template ? route('ai-template.reports.stock-report') : action([\App\Http\Controllers\ReportController::class, 'getStockReport']);
                            $sub->url(
                                $stock_report_url,
                                __('report.stock_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'stock-report') || (request()->segment(2) == 'stock-report')]
                            );
                            if (session('business.enable_product_expiry') == 1) {
                                $stock_expiry_url = $is_ai_template ? route('ai-template.reports.stock-expiry') : action([\App\Http\Controllers\ReportController::class, 'getStockExpiryReport']);
                                $sub->url(
                                    $stock_expiry_url,
                                    __('report.stock_expiry_report'),
                                    ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'stock-expiry') || (request()->segment(2) == 'stock-expiry')]
                                );
                            }
                            if (session('business.enable_lot_number') == 1) {
                                $lot_report_url = $is_ai_template ? route('ai-template.reports.lot-report') : action([\App\Http\Controllers\ReportController::class, 'getLotReport']);
                                $sub->url(
                                    $lot_report_url,
                                    __('lang_v1.lot_report'),
                                    ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'lot-report') || (request()->segment(2) == 'lot-report')]
                                );
                            }

                            if (in_array('stock_adjustment', $enabled_modules)) {
                                $stock_adjustment_url = $is_ai_template ? route('ai-template.reports.stock-adjustment-report') : action([\App\Http\Controllers\ReportController::class, 'getStockAdjustmentReport']);
                                $sub->url(
                                    $stock_adjustment_url,
                                    __('report.stock_adjustment_report'),
                                    ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'stock-adjustment-report') || (request()->segment(2) == 'stock-adjustment-report')]
                                );
                            }
                        }

                        if (auth()->user()->can('trending_product_report.view')) {
                            $trending_url = $is_ai_template ? route('ai-template.reports.trending-products') : action([\App\Http\Controllers\ReportController::class, 'getTrendingProducts']);
                            $sub->url(
                                $trending_url,
                                __('report.trending_products'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'trending-products') || (request()->segment(2) == 'trending-products')]
                            );
                        }

                        if (auth()->user()->can('purchase_n_sell_report.view')) {
                            $items_url = $is_ai_template ? route('ai-template.reports.items-report') : action([\App\Http\Controllers\ReportController::class, 'itemsReport']);
                            $sub->url(
                                $items_url,
                                __('lang_v1.items_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'items-report') || (request()->segment(2) == 'items-report')]
                            );

                            $product_purchase_url = $is_ai_template ? route('ai-template.reports.product-purchase-report') : action([\App\Http\Controllers\ReportController::class, 'getproductPurchaseReport']);
                            $sub->url(
                                $product_purchase_url,
                                __('lang_v1.product_purchase_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'product-purchase-report') || (request()->segment(2) == 'product-purchase-report')]
                            );

                            $product_sell_url = $is_ai_template ? route('ai-template.reports.product-sell-report') : action([\App\Http\Controllers\ReportController::class, 'getproductSellReport']);
                            $sub->url(
                                $product_sell_url,
                                __('lang_v1.product_sell_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'product-sell-report') || (request()->segment(2) == 'product-sell-report')]
                            );

                            $purchase_payment_url = $is_ai_template ? route('ai-template.reports.purchase-payment-report') : action([\App\Http\Controllers\ReportController::class, 'purchasePaymentReport']);
                            $sub->url(
                                $purchase_payment_url,
                                __('lang_v1.purchase_payment_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'purchase-payment-report') || (request()->segment(2) == 'purchase-payment-report')]
                            );

                            $sell_payment_url = $is_ai_template ? route('ai-template.reports.sell-payment-report') : action([\App\Http\Controllers\ReportController::class, 'sellPaymentReport']);
                            $sub->url(
                                $sell_payment_url,
                                __('lang_v1.sell_payment_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'sell-payment-report') || (request()->segment(2) == 'sell-payment-report')]
                            );
                        }
                        if (in_array('expenses', $enabled_modules) && auth()->user()->can('expense_report.view')) {
                            $expense_url = $is_ai_template ? route('ai-template.reports.expense-report') : action([\App\Http\Controllers\ReportController::class, 'getExpenseReport']);
                            $sub->url(
                                $expense_url,
                                __('report.expense_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'expense-report') || (request()->segment(2) == 'expense-report')]
                            );
                        }
                        if (auth()->user()->can('register_report.view')) {
                            $register_url = $is_ai_template ? route('ai-template.reports.register-report') : action([\App\Http\Controllers\ReportController::class, 'getRegisterReport']);
                            $sub->url(
                                $register_url,
                                __('report.register_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'register-report') || (request()->segment(2) == 'register-report')]
                            );
                        }
                        if (auth()->user()->can('sales_representative.view')) {
                            $sales_rep_url = $is_ai_template ? route('ai-template.reports.sales-representative-report') : action([\App\Http\Controllers\ReportController::class, 'getSalesRepresentativeReport']);
                            $sub->url(
                                $sales_rep_url,
                                __('report.sales_representative'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'sales-representative-report') || (request()->segment(2) == 'sales-representative-report')]
                            );
                        }
                        if (auth()->user()->can('purchase_n_sell_report.view') && in_array('tables', $enabled_modules)) {
                            $table_url = $is_ai_template ? route('ai-template.reports.table-report') : action([\App\Http\Controllers\ReportController::class, 'getTableReport']);
                            $sub->url(
                                $table_url,
                                __('restaurant.table_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'table-report') || (request()->segment(2) == 'table-report')]
                            );
                        }

                        if (auth()->user()->can('tax_report.view') && !empty(config('constants.enable_gst_report_india'))) {
                            $gst_sales_url = $is_ai_template ? route('ai-template.reports.gst-sales-report') : action([\App\Http\Controllers\ReportController::class, 'gstSalesReport']);
                            $sub->url(
                                $gst_sales_url,
                                __('lang_v1.gst_sales_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'gst-sales-report') || (request()->segment(2) == 'gst-sales-report')]
                            );

                            $gst_purchase_url = $is_ai_template ? route('ai-template.reports.gst-purchase-report') : action([\App\Http\Controllers\ReportController::class, 'gstPurchaseReport']);
                            $sub->url(
                                $gst_purchase_url,
                                __('lang_v1.gst_purchase_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'gst-purchase-report') || (request()->segment(2) == 'gst-purchase-report')]
                            );
                        }

                        if (auth()->user()->can('sales_representative.view') && in_array('service_staff', $enabled_modules)) {
                            $service_staff_url = $is_ai_template ? route('ai-template.reports.service-staff-report') : action([\App\Http\Controllers\ReportController::class, 'getServiceStaffReport']);
                            $sub->url(
                                $service_staff_url,
                                __('restaurant.service_staff_report'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'service-staff-report') || (request()->segment(2) == 'service-staff-report')]
                            );
                        }

                        if ($is_admin) {
                            $activity_log_url = $is_ai_template ? route('ai-template.reports.activity-log') : action([\App\Http\Controllers\ReportController::class, 'activityLog']);
                            $sub->url(
                                $activity_log_url,
                                __('lang_v1.activity_log'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(3) == 'activity-log') || (request()->segment(2) == 'activity-log')]
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697"></path>
                    <path d="M18 14v4h4"></path>
                    <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2"></path>
                    <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                    <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                    <path d="M8 11h4"></path>
                    <path d="M8 15h3"></path>
                  </svg>', 'id' => 'tour_step8']
                )->order(55);
            }

            //Backup menu
            if (auth()->user()->can('backup')) {
                $backup_url = $is_ai_template ? route('ai-template.backup.index') : action([\App\Http\Controllers\BackUpController::class, 'index']);
                $menu->url($backup_url, __('lang_v1.backup'), ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 18.004h-5.343c-2.572 -.004 -4.657 -2.011 -4.657 -4.487c0 -2.475 2.085 -4.482 4.657 -4.482c.393 -1.762 1.794 -3.2 3.675 -3.773c1.88 -.572 3.956 -.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99c1.38 0 2.57 .811 3.128 1.986"></path>
                <path d="M19 22v-6"></path>
                <path d="M22 19l-3 -3l-3 3"></path>
              </svg>', 'active' => ($is_ai_template && request()->segment(2) == 'backup') || (request()->segment(1) == 'backup')])->order(60);
            }

            //Modules menu
            if (auth()->user()->can('manage_modules')) {
                $modules_url = $is_ai_template ? route('ai-template.manage-modules.index') : action([\App\Http\Controllers\Install\ModulesController::class, 'index']);
                $menu->url($modules_url, __('lang_v1.modules'), ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path d="M12 4l-8 4l8 4l8 -4l-8 -4"></path>
              <path d="M4 12l8 4l8 -4"></path>
              <path d="M4 16l8 4l8 -4"></path>
            </svg>', 'active' => ($is_ai_template && request()->segment(2) == 'manage-modules') || (request()->segment(1) == 'manage-modules')])->order(60);
            }

            //Booking menu
            if (in_array('booking', $enabled_modules) && (auth()->user()->can('crud_all_bookings') || auth()->user()->can('crud_own_bookings'))) {
                $bookings_url = $is_ai_template ? route('ai-template.bookings.index') : action([\App\Http\Controllers\Restaurant\BookingController::class, 'index']);
                $menu->url($bookings_url, __('restaurant.bookings'), ['icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M15 19l2 2l4 -4" /></svg>', 'active' => ($is_ai_template && request()->segment(2) == 'bookings') || (request()->segment(1) == 'bookings')])->order(65);
            }

            //Kitchen menu
            if (in_array('kitchen', $enabled_modules)) {
                $kitchen_url = $is_ai_template ? route('ai-template.kitchen.index') : action([\App\Http\Controllers\Restaurant\KitchenController::class, 'index']);
                $menu->url($kitchen_url, __('restaurant.kitchen'), ['icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-flame"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12c2 -2.96 0 -7 -1 -8c0 3.038 -1.773 4.741 -3 6c-1.226 1.26 -2 3.24 -2 5a6 6 0 1 0 12 0c0 -1.532 -1.056 -3.94 -2 -5c-1.786 3 -2.791 3 -4 2z" /></svg>', 'active' => ($is_ai_template && request()->segment(2) == 'kitchen') || (request()->segment(1) == 'modules' && request()->segment(2) == 'kitchen')])->order(70);
            }

            //Service Staff menu
            if (in_array('service_staff', $enabled_modules)) {
                $orders_url = $is_ai_template ? route('ai-template.orders.index') : action([\App\Http\Controllers\Restaurant\OrderController::class, 'index']);
                $menu->url($orders_url, __('restaurant.orders'), ['icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-baseline-density-medium"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h16" /><path d="M4 12h16" /><path d="M4 4h16" /></svg>', 'active' => ($is_ai_template && request()->segment(2) == 'orders') || (request()->segment(1) == 'modules' && request()->segment(2) == 'orders')])->order(75);
            }

            //Notification template menu
            if (auth()->user()->can('send_notifications')) {
                $notification_template_url = $is_ai_template ? route('ai-template.notification-templates.index') : action([\App\Http\Controllers\NotificationTemplateController::class, 'index']);
                $menu->url($notification_template_url, __('lang_v1.notification_templates'), ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
                    <path d="M3 7l9 6l9 -6"></path>
                  </svg>', 'active' => ($is_ai_template && request()->segment(2) == 'notification-templates') || (request()->segment(1) == 'notification-templates')])->order(80);
            }

            //Settings Dropdown
            if (auth()->user()->can('business_settings.access') ||
                auth()->user()->can('barcode_settings.access') ||
                auth()->user()->can('invoice_settings.access') ||
                auth()->user()->can('tax_rate.view') ||
                auth()->user()->can('tax_rate.create') ||
                auth()->user()->can('access_package_subscriptions')) {
                $menu->dropdown(
                    __('business.settings'),
                    function ($sub) use ($enabled_modules, $is_ai_template) {
                        if (auth()->user()->can('business_settings.access')) {
                            $business_settings_url = $is_ai_template ? route('ai-template.business.settings') : route('business.getBusinessSettings');
                            $sub->url(
                                $business_settings_url,
                                __('business.business_settings'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'business' && request()->segment(3) == 'settings') || (request()->segment(1) == 'business'), 'id' => 'tour_step2']
                            );
                            $business_locations_url = $is_ai_template ? route('ai-template.business-location.index') : route('business-location.index');
                            $sub->url(
                                $business_locations_url,
                                __('business.business_locations'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'business-location') || (request()->segment(1) == 'business-location')]
                            );
                        }
                        if (auth()->user()->can('invoice_settings.access')) {
                            $invoice_schemes_url = $is_ai_template ? route('ai-template.invoice-schemes.index') : route('invoice-schemes.index');
                            $sub->url(
                                $invoice_schemes_url,
                                __('invoice.invoice_settings'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'invoice-schemes') || in_array(request()->segment(1), ['invoice-schemes', 'invoice-layouts'])]
                            );
                        }
                        if (auth()->user()->can('barcode_settings.access')) {
                            $barcodes_url = $is_ai_template ? route('ai-template.barcodes.index') : route('barcodes.index');
                            $sub->url(
                                $barcodes_url,
                                __('barcode.barcode_settings'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'barcodes') || (request()->segment(1) == 'barcodes')]
                            );
                        }
                        if (auth()->user()->can('access_printers')) {
                            $printers_url = $is_ai_template ? route('ai-template.printers.index') : route('printers.index');
                            $sub->url(
                                $printers_url,
                                __('printer.receipt_printers'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'printers') || (request()->segment(1) == 'printers')]
                            );
                        }

                        if (auth()->user()->can('tax_rate.view') || auth()->user()->can('tax_rate.create')) {
                            $tax_rates_url = $is_ai_template ? route('ai-template.tax-rates.index') : route('tax-rates.index');
                            $sub->url(
                                $tax_rates_url,
                                __('tax_rate.tax_rates'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'tax-rates') || (request()->segment(1) == 'tax-rates')]
                            );
                        }

                        if (in_array('tables', $enabled_modules) && auth()->user()->can('access_tables')) {
                            $tables_url = $is_ai_template ? route('ai-template.tables.index') : route('tables.index');
                            $sub->url(
                                $tables_url,
                                __('restaurant.tables'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'tables') || (request()->segment(1) == 'modules' && request()->segment(2) == 'tables')]
                            );
                        }

                        if (in_array('modifiers', $enabled_modules) && (auth()->user()->can('product.view') || auth()->user()->can('product.create'))) {
                            $modifiers_url = $is_ai_template ? route('ai-template.modifiers.index') : route('modifiers.index');
                            $sub->url(
                                $modifiers_url,
                                __('restaurant.modifiers'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'modifiers') || (request()->segment(1) == 'modules' && request()->segment(2) == 'modifiers')]
                            );
                        }

                        if (in_array('types_of_service', $enabled_modules) && auth()->user()->can('access_types_of_service')) {
                            $types_of_service_url = $is_ai_template ? route('ai-template.types-of-service.index') : route('types-of-service.index');
                            $sub->url(
                                $types_of_service_url,
                                __('lang_v1.types_of_service'),
                                ['icon' => '', 'active' => ($is_ai_template && request()->segment(2) == 'types-of-service') || (request()->segment(1) == 'types-of-service')]
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                  </svg>', 'id' => 'tour_step3']
                )->order(85);
            }
        });

        //Add menus from modules
        $moduleUtil = new ModuleUtil;
        $moduleUtil->getModuleData('modifyAdminMenu');

        return $next($request);
    }
}
