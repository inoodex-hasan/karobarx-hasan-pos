<?php

namespace App\Http\Controllers;

use App\User;
use App\Utils\Util;
use DataTables;
use DB;
use Illuminate\Http\Request;

class SalesCommissionAgentController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! auth()->user()->can('user.view') && ! auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        $is_ai_template = request()->is('ai-template/*');

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $use_viho = $is_ai_template;

            $users = User::where('business_id', $business_id)
                        ->where('is_cmmsn_agnt', 1)
                        ->select(['id',
                            DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as full_name"),
                            'email', 'contact_no', 'address', 'cmmsn_percent', ]);

            return Datatables::of($users)
                ->addColumn(
                    'action',
                    function ($row) use ($use_viho) {
                        $edit_url = $use_viho
                            ? route('ai-template.sales-commission-agents.edit', [$row->id])
                            : action([\App\Http\Controllers\SalesCommissionAgentController::class, 'edit'], [$row->id]);

                        $delete_url = $use_viho
                            ? route('ai-template.sales-commission-agents.destroy', [$row->id])
                            : action([\App\Http\Controllers\SalesCommissionAgentController::class, 'destroy'], [$row->id]);

                        if (! $use_viho) {
                            $html = '';
                            if (auth()->user()->can('user.update')) {
                                $html .= '<button type="button" data-href="' . $edit_url . '" data-container=".commission_agent_modal" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline btn-modal tw-dw-btn-primary"><i class="glyphicon glyphicon-edit"></i> ' . __('messages.edit') . '</button>&nbsp;';
                            }
                            if (auth()->user()->can('user.delete')) {
                                $html .= '<button data-href="' . $delete_url . '" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error delete_commsn_agnt_button"><i class="glyphicon glyphicon-trash"></i> ' . __('messages.delete') . '</button>';
                            }
                            return $html;
                        }

                        $html = '<div class="btn-showcase d-flex flex-nowrap" role="group" aria-label="Action Buttons">';
                        if (auth()->user()->can('user.update')) {
                            $html .= '<a href="' . $edit_url . '" class="btn btn-primary btn-xs d-inline-flex align-items-center justify-content-center" title="' . __('messages.edit') . '"><i data-feather="edit" style="width: 14px; height: 14px;"></i></a> ';
                        }
                        if (auth()->user()->can('user.delete')) {
                            $html .= '<button data-href="' . $delete_url . '" class="btn btn-danger btn-xs d-inline-flex align-items-center justify-content-center delete_commsn_agnt_button" title="' . __('messages.delete') . '"><i data-feather="trash-2" style="width: 14px; height: 14px;"></i></button>';
                        }
                        $html .= '</div>';

                        return $html;
                    }
                )
                ->filterColumn('full_name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) like ?", ["%{$keyword}%"]);
                })
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }

        $view = $is_ai_template ? 'templates.viho.sales_commission_agent.index' : 'sales_commission_agent.index';
        return view($view);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        $view = request()->is('ai-template/*') ? 'templates.viho.sales_commission_agent.create' : 'sales_commission_agent.create';
        return view($view);
    }

    public function show($id)
    {
        if (! auth()->user()->can('user.view')) {
            abort(403, 'Unauthorized action.');
        }

        $redirect_route = request()->is('ai-template/*') ? 'ai-template.sales-commission-agents.index' : 'sales-commission-agents.index';
        return redirect()->route($redirect_route);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['surname', 'first_name', 'last_name', 'email', 'address', 'contact_no', 'cmmsn_percent']);
            $input['cmmsn_percent'] = $this->commonUtil->num_uf($input['cmmsn_percent']);
            $business_id = $request->session()->get('user.business_id');
            $input['business_id'] = $business_id;
            $input['allow_login'] = 0;
            $input['is_cmmsn_agnt'] = 1;

            $user = User::create($input);

            $output = ['success' => true,
                'msg' => __('lang_v1.commission_agent_added_success'),
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);

        $view = request()->is('ai-template/*') ? 'templates.viho.sales_commission_agent.edit' : 'sales_commission_agent.edit';

        return view($view)
                    ->with(compact('user'));
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
        if (! auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['surname', 'first_name', 'last_name', 'email', 'address', 'contact_no', 'cmmsn_percent']);
                $input['cmmsn_percent'] = $this->commonUtil->num_uf($input['cmmsn_percent']);
                $business_id = $request->session()->get('user.business_id');

                $user = User::where('id', $id)
                            ->where('business_id', $business_id)
                            ->where('is_cmmsn_agnt', 1)
                            ->first();
                $user->update($input);

                $output = ['success' => true,
                    'msg' => __('lang_v1.commission_agent_updated_success'),
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
        if (! auth()->user()->can('user.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                User::where('id', $id)
                    ->where('business_id', $business_id)
                    ->where('is_cmmsn_agnt', 1)
                    ->delete();

                $output = ['success' => true,
                    'msg' => __('lang_v1.commission_agent_deleted_success'),
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
