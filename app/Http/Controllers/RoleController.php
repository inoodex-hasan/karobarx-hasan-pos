<?php

namespace App\Http\Controllers;

use App\SellingPriceGroup;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $moduleUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! auth()->user()->can('roles.view')) {
            abort(403, 'Unauthorized action.');
        }

        $is_ai_template = request()->is('ai-template/*');

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $roles = Role::where('business_id', $business_id)
                        ->select(['name', 'id', 'is_default', 'business_id']);

            return DataTables::of($roles)
                ->addColumn('action', function ($row) use ($is_ai_template) {
                    if (! $row->is_default || $row->name == 'Cashier#'.$row->business_id) {
                        $use_viho = $is_ai_template;

                        $action = '';
                        $edit_url = $use_viho ? route('ai-template.roles.edit', [$row->id]) : route('roles.edit', [$row->id]);
                        $delete_url = $use_viho ? route('ai-template.roles.destroy', [$row->id]) : route('roles.destroy', [$row->id]);

                        if (auth()->user()->can('roles.update')) {
                            if ($use_viho) {
                                $action .= '<a href="' . $edit_url . '" class="btn btn-primary btn-xs d-inline-flex align-items-center justify-content-center" title="' . __("messages.edit") . '"><i data-feather="edit" style="width: 14px; height: 14px;"></i></a> ';
                            } else {
                                $action .= '<a href="'.$edit_url.'" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary"><i class="glyphicon glyphicon-edit"></i> '.__('messages.edit').'</a>';
                            }
                        }
                        if (auth()->user()->can('roles.delete')) {
                            if ($use_viho) {
                                $action .= '<button data-href="' . $delete_url . '" class="btn btn-danger btn-xs delete_role_button d-inline-flex align-items-center justify-content-center" title="' . __("messages.delete") . '"><i data-feather="trash-2" style="width: 14px; height: 14px;"></i></button>';
                            } else {
                                $action .= '&nbsp
                                    <button data-href="'.$delete_url.'" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error delete_role_button"><i class="glyphicon glyphicon-trash"></i> '.__('messages.delete').'</button>';
                            }
                        }

                        if ($use_viho) {
                            return '<div class="btn-showcase d-flex flex-nowrap" role="group" aria-label="Action Buttons">' . $action . '</div>';
                        }

                        return $action;
                    } else {
                        return '';
                    }
                })
                ->editColumn('name', function ($row) use ($business_id) {
                    $role_name = str_replace('#'.$business_id, '', $row->name);
                    if (in_array($role_name, ['Admin', 'Cashier'])) {
                        $role_name = __('lang_v1.'.$role_name);
                    }

                    return $role_name;
                })
                ->removeColumn('id')
                ->removeColumn('is_default')
                ->removeColumn('business_id')
                ->rawColumns([1])
                ->make(false);
        }

        return view($this->getViewPath('index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('roles.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $selling_price_groups = SellingPriceGroup::where('business_id', $business_id)
                                    ->active()
                                    ->get();

        $module_permissions = $this->moduleUtil->getModuleData('user_permissions');

        $common_settings = ! empty(session('business.common_settings')) ? session('business.common_settings') : [];

        return view($this->getViewPath('create'))
                ->with(compact('selling_price_groups', 'module_permissions', 'common_settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('roles.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            $role_name = $request->input('name');
            $permissions = $request->input('permissions');
            $business_id = $request->session()->get('user.business_id');

            $count = Role::where('name', $role_name.'#'.$business_id)
                        ->where('business_id', $business_id)
                        ->count();
            if ($count == 0) {
                $is_service_staff = 0;
                if ($request->input('is_service_staff') == 1) {
                    $is_service_staff = 1;
                }

                $role = Role::create([
                    'name' => $role_name.'#'.$business_id,
                    'business_id' => $business_id,
                    'is_service_staff' => $is_service_staff,
                ]);

                //Include selling price group permissions
                $spg_permissions = $request->input('spg_permissions');

                if (! empty($spg_permissions)) {
                    foreach ($spg_permissions as $spg_permission) {
                        $permissions[] = $spg_permission;
                    }
                }

                $radio_options = $request->input('radio_option');
                if (! empty($radio_options)) {
                    foreach ($radio_options as $key => $value) {
                        $permissions[] = $value;
                    }
                }

                $this->__createPermissionIfNotExists($permissions);

                if (! empty($permissions)) {
                    $role->syncPermissions($permissions);
                }
                db::commit();
                $output = ['success' => 1,
                    'msg' => __('user.role_added'),
                ];
            } else {
                $output = ['success' => 0,
                    'msg' => __('user.role_already_exists'),
                ];
            }
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            db::rollBack();
            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        $redirect_route = request()->is('ai-template/*') ? 'ai-template.roles.index' : 'roles.index';
        return redirect()->route($redirect_route)->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! auth()->user()->can('roles.update')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $role = Role::where('business_id', $business_id)
                    ->with(['permissions'])
                    ->find($id);
        $role_permissions = [];
        foreach ($role->permissions as $role_perm) {
            $role_permissions[] = $role_perm->name;
        }

        $selling_price_groups = SellingPriceGroup::where('business_id', $business_id)
                                    ->active()
                                    ->get();

        $module_permissions = $this->moduleUtil->getModuleData('user_permissions');

        $common_settings = ! empty(session('business.common_settings')) ? session('business.common_settings') : [];

        return view($this->getViewPath('edit'))
                ->with(compact('role', 'role_permissions', 'selling_price_groups', 'module_permissions', 'common_settings'));
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
        if (! auth()->user()->can('roles.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $role_name = $request->input('name');
            $permissions = $request->input('permissions');
            $business_id = $request->session()->get('user.business_id');

            $count = Role::where('name', $role_name.'#'.$business_id)
                        ->where('id', '!=', $id)
                        ->where('business_id', $business_id)
                        ->count();
            if ($count == 0) {
                $role = Role::findOrFail($id);

                if (! $role->is_default || $role->name == 'Cashier#'.$business_id) {
                    if ($role->name == 'Cashier#'.$business_id) {
                        $role->is_default = 0;
                    }

                    $is_service_staff = 0;
                    if ($request->input('is_service_staff') == 1) {
                        $is_service_staff = 1;
                    }
                    $role->is_service_staff = $is_service_staff;
                    $role->name = $role_name.'#'.$business_id;
                    $role->save();

                    //Include selling price group permissions
                    $spg_permissions = $request->input('spg_permissions');
                    if (! empty($spg_permissions)) {
                        foreach ($spg_permissions as $spg_permission) {
                            $permissions[] = $spg_permission;
                        }
                    }

                    $radio_options = $request->input('radio_option');
                    if (! empty($radio_options)) {
                        foreach ($radio_options as $key => $value) {
                            $permissions[] = $value;
                        }
                    }

                    $this->__createPermissionIfNotExists($permissions);

                    if (! empty($permissions)) {
                        $role->syncPermissions($permissions);
                    }

                    $output = ['success' => 1,
                        'msg' => __('user.role_updated'),
                    ];
                } else {
                    $output = ['success' => 0,
                        'msg' => __('user.role_is_default'),
                    ];
                }
            } else {
                $output = ['success' => 0,
                    'msg' => __('user.role_already_exists'),
                ];
            }
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        $redirect_route = request()->is('ai-template/*') ? 'ai-template.roles.index' : 'roles.index';
        return redirect()->route($redirect_route)->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('roles.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $role = Role::where('business_id', $business_id)->find($id);

                if (! $role->is_default || $role->name == 'Cashier#'.$business_id) {
                    $role->delete();
                    $output = ['success' => true,
                        'msg' => __('user.role_deleted'),
                    ];
                } else {
                    $output = ['success' => 0,
                        'msg' => __('user.role_is_default'),
                    ];
                }
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }
        }

        return $output;
    }

    /**
     * Resolves the view path based on whether request is for ai-template.
     *
     * @param string $view
     * @return string
     */
    private function getViewPath($view)
    {
        if (request()->is('ai-template/*')) {
            return 'templates.viho.role.' . $view;
        }

        return 'role.' . $view;
    }

    /**
     * Creates new permission if doesn't exist
     *
     * @param  array  $permissions
     * @return void
     */
    private function __createPermissionIfNotExists($permissions)
    {
        $exising_permissions = Permission::whereIn('name', $permissions)
                                    ->pluck('name')
                                    ->toArray();

        $non_existing_permissions = array_diff($permissions, $exising_permissions);

        if (! empty($non_existing_permissions)) {
            foreach ($non_existing_permissions as $new_permission) {
                $time_stamp = \Carbon::now()->toDateTimeString();
                Permission::create([
                    'name' => $new_permission,
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
