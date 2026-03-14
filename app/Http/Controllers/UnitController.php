<?php

namespace App\Http\Controllers;

use App\Product;
use App\Unit;
use App\Utils\Util;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $commonUtil;

    /**
     * Check if request is for Viho template
     */
    protected function isAiTemplateRequest()
    {
        return request()->segment(1) === 'ai-template';
    }

    /**
     * Get the view path for Viho template
     */
    protected function viewPath($view = 'index')
    {
        return 'templates.viho.unit.' . $view;
    }
    /**
     * Constructor
     *
     * @param  ProductUtils  $product
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
        if (! auth()->user()->can('unit.view') && ! auth()->user()->can('unit.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $unit = Unit::where('business_id', $business_id)
                        ->with(['base_unit'])
                        ->select(['actual_name', 'short_name', 'allow_decimal', 'id',
                            'base_unit_id', 'base_unit_multiplier', ]);

            return Datatables::of($unit)
                ->addColumn(
                    'action',
                    function ($row) {
                        $is_ai_template = $this->isAiTemplateRequest();
                        $edit_href = action([\App\Http\Controllers\UnitController::class, 'edit'], [$row->id]);
                        $delete_href = action([\App\Http\Controllers\UnitController::class, 'destroy'], [$row->id]);

                        if ($is_ai_template) {
                            $html = '<div class="btn-showcase d-flex flex-nowrap" role="group" aria-label="Action Buttons">';

                            if (auth()->user()->can('unit.update')) {
                                $html .=
                                    '<button data-href="' . $edit_href . '" class="btn btn-primary btn-xs d-inline-flex align-items-center justify-content-center edit_unit_button" title="' . __("messages.edit") . '">' .
                                    '<i data-feather="edit" style="width: 14px; height: 14px;"></i>' .
                                    '</button>';
                            }

                            if (auth()->user()->can('unit.delete')) {
                                $html .=
                                    '<button data-href="' . $delete_href . '" class="btn btn-danger btn-xs delete_unit_button d-inline-flex align-items-center justify-content-center" title="' . __("messages.delete") . '">' .
                                    '<i data-feather="trash-2" style="width: 14px; height: 14px;"></i>' .
                                    '</button>';
                            }

                            $html .= '</div>';
                            return $html;
                        }

                        $html = '';
                        if (auth()->user()->can('unit.update')) {
                            $html .= '<button data-href="' . $edit_href . '" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary edit_unit_button"><i class="glyphicon glyphicon-edit"></i> ' . __("messages.edit") . '</button>';
                        }
                        if (auth()->user()->can('unit.delete')) {
                            $html .= '&nbsp;<button data-href="' . $delete_href . '" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error delete_unit_button"><i class="glyphicon glyphicon-trash"></i> ' . __("messages.delete") . '</button>';
                        }

                        return $html;
                    }
                )
                ->editColumn('allow_decimal', function ($row) {
                    if ($row->allow_decimal) {
                        return __('messages.yes');
                    } else {
                        return __('messages.no');
                    }
                })
                ->editColumn('actual_name', function ($row) {
                    if (! empty($row->base_unit_id)) {
                        return  $row->actual_name.' ('.(float) $row->base_unit_multiplier.$row->base_unit->short_name.')';
                    }

                    return  $row->actual_name;
                })
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view($this->isAiTemplateRequest() ? $this->viewPath('index') : 'unit.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('unit.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $quick_add = false;
        if (! empty(request()->input('quick_add'))) {
            $quick_add = true;
        }

        $units = Unit::forDropdown($business_id);

        $view = $this->isAiTemplateRequest() ? $this->viewPath('create') : 'unit.create';
        return view($view)->with(compact('quick_add', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('unit.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['actual_name', 'short_name', 'allow_decimal']);
            $input['business_id'] = $request->session()->get('user.business_id');
            $input['created_by'] = $request->session()->get('user.id');

            if ($request->has('define_base_unit')) {
                if (! empty($request->input('base_unit_id')) && ! empty($request->input('base_unit_multiplier'))) {
                    $base_unit_multiplier = $this->commonUtil->num_uf($request->input('base_unit_multiplier'));
                    if ($base_unit_multiplier != 0) {
                        $input['base_unit_id'] = $request->input('base_unit_id');
                        $input['base_unit_multiplier'] = $base_unit_multiplier;
                    }
                }
            }

            $unit = Unit::create($input);
            $output = ['success' => true,
                'data' => $unit,
                'msg' => __('unit.added_success'),
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
        if (! auth()->user()->can('unit.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $unit = Unit::where('business_id', $business_id)->find($id);

            $units = Unit::forDropdown($business_id);

            $view = $this->isAiTemplateRequest() ? $this->viewPath('edit') : 'unit.edit';
            return view($view)->with(compact('unit', 'units'));
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
        if (! auth()->user()->can('unit.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['actual_name', 'short_name', 'allow_decimal']);
                $business_id = $request->session()->get('user.business_id');

                $unit = Unit::where('business_id', $business_id)->findOrFail($id);
                $unit->actual_name = $input['actual_name'];
                $unit->short_name = $input['short_name'];
                $unit->allow_decimal = $input['allow_decimal'];
                if ($request->has('define_base_unit')) {
                    if (! empty($request->input('base_unit_id')) && ! empty($request->input('base_unit_multiplier'))) {
                        $base_unit_multiplier = $this->commonUtil->num_uf($request->input('base_unit_multiplier'));
                        if ($base_unit_multiplier != 0) {
                            $unit->base_unit_id = $request->input('base_unit_id');
                            $unit->base_unit_multiplier = $base_unit_multiplier;
                        }
                    }
                } else {
                    $unit->base_unit_id = null;
                    $unit->base_unit_multiplier = null;
                }

                $unit->save();

                $output = ['success' => true,
                    'msg' => __('unit.updated_success'),
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
        if (! auth()->user()->can('unit.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $unit = Unit::where('business_id', $business_id)->findOrFail($id);

                //check if any product associated with the unit
                $exists = Product::where('unit_id', $unit->id)
                                ->exists();
                if (! $exists) {
                    $unit->delete();
                    $output = ['success' => true,
                        'msg' => __('unit.deleted_success'),
                    ];
                } else {
                    $output = ['success' => false,
                        'msg' => __('lang_v1.unit_cannot_be_deleted'),
                    ];
                }
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => '__("messages.something_went_wrong")',
                ];
            }

            return $output;
        }
    }
}
