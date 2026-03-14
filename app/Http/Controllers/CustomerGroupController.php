<?php

namespace App\Http\Controllers;

use App\CustomerGroup;
use App\SellingPriceGroup;
use App\Utils\Util;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerGroupController extends Controller
{
    /**
     * Constructor
     *
     * @param  Util  $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    private function isAiTemplateRequest(): bool
    {
        return request()->is('ai-template/*');
    }

    private function viewPath(string $view): string
    {
        return $this->isAiTemplateRequest()
            ? 'templates.viho.customer_group.'.$view
            : 'customer_group.'.$view;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! auth()->user()->can('customer.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $route_prefix = $this->isAiTemplateRequest() ? 'ai-template.' : '';
            $business_id = request()->session()->get('user.business_id');

            $customer_group = CustomerGroup::where('customer_groups.business_id', $business_id)
                                    ->leftjoin('selling_price_groups as spg', 'spg.id', '=', 'customer_groups.selling_price_group_id')
                                ->select(['customer_groups.name', 'customer_groups.amount', 'spg.name as selling_price_group', 'customer_groups.id', 'price_calculation_type']);

            return Datatables::of($customer_group)
                    ->addColumn('action', function ($row) use ($route_prefix) {
                        $edit_url = route($route_prefix.'customer-group.edit', [$row->id]);
                        $delete_url = route($route_prefix.'customer-group.destroy', [$row->id]);

                        $html = '';
                        if (auth()->user()->can('customer.update')) {
                            $html .= '<button data-href="'.$edit_url.'" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary tw-m-0.5 edit_customer_group_button"><i class="glyphicon glyphicon-edit"></i> '.__('messages.edit').'</button>';
                        }
                        if (auth()->user()->can('customer.delete')) {
                            $html .= ' <button data-href="'.$delete_url.'" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error tw-m-0.5 delete_customer_group_button"><i class="glyphicon glyphicon-trash"></i> '.__('messages.delete').'</button>';
                        }

                        return $html;
                    })
                    ->editColumn('selling_price_group', '@if($price_calculation_type=="selling_price_group") {{$selling_price_group}} @else -- @endif ')
                    ->editColumn('amount', '@if($price_calculation_type=="percentage") {{$amount}} @else -- @endif ')
                    ->removeColumn('id')
                    ->removeColumn('price_calculation_type')
                    ->rawColumns([3])
                    ->make(false);
        }

        return view($this->viewPath('index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('customer.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $price_groups = SellingPriceGroup::forDropdown($business_id, false);

        return view($this->viewPath('create'))->with(compact('price_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('customer.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['name', 'amount', 'price_calculation_type', 'selling_price_group_id']);
            $input['business_id'] = $request->session()->get('user.business_id');
            $input['created_by'] = $request->session()->get('user.id');
            $input['amount'] = ! empty($input['amount']) ? $this->commonUtil->num_uf($input['amount']) : 0;

            $customer_group = CustomerGroup::create($input);
            $output = ['success' => true,
                'data' => $customer_group,
                'msg' => __('lang_v1.success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! auth()->user()->can('customer.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $customer_group = CustomerGroup::where('business_id', $business_id)->find($id);

            $business_id = request()->session()->get('user.business_id');
            $price_groups = SellingPriceGroup::forDropdown($business_id, false);

            return view($this->viewPath('edit'))
                ->with(compact('customer_group', 'price_groups'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('customer.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'amount', 'price_calculation_type', 'selling_price_group_id']);
                $business_id = $request->session()->get('user.business_id');

                $input['amount'] = ! empty($input['amount']) ? $this->commonUtil->num_uf($input['amount']) : 0;

                $customer_group = CustomerGroup::where('business_id', $business_id)->findOrFail($id);

                $customer_group->update($input);

                $output = ['success' => true,
                    'msg' => __('lang_v1.success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('customer.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $cg = CustomerGroup::where('business_id', $business_id)->findOrFail($id);
                $cg->delete();

                $output = ['success' => true,
                    'msg' => __('lang_v1.success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }
}
