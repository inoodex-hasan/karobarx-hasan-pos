@extends('templates.viho.layout')
@php
    $heading = !empty($module_category_data['heading']) ? $module_category_data['heading'] : __('category.categories');
    $navbar = !empty($module_category_data['navbar']) ? $module_category_data['navbar'] : null;
@endphp
@section('title', $heading)

@push('styles')
    <style>
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

        #category_table_wrapper {
            width: 100% !important;
            display: block !important;
        }

        #category_table {
            width: 100% !important;
        }

        .dataTables_paginate {
            display: flex !important;
            justify-content: flex-end !important;
            width: 100% !important;
        }

        .paging_simple_numbers {
            margin-left: auto !important;
        }
    </style>
@endpush

@section('content')
@if (!empty($navbar))
    @include($navbar)
@endif
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>{{ $heading }}
                    <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">
                        {{ $module_category_data['sub_heading'] ?? __('category.manage_your_categories') }}
                    </small>
                    @if (isset($module_category_data['heading_tooltip']))
                        @show_tooltip($module_category_data['heading_tooltip'])
                    @endif
                </h3>
            </div>
        </div>
    </div>
</div>

<section class="content">
    @php
        $cat_code_enabled =
            isset($module_category_data['enable_taxonomy_code']) && !$module_category_data['enable_taxonomy_code']
            ? false
            : true;
    @endphp
    <input type="hidden" id="category_type" value="{{ request()->get('type') }}">
    @php
        $can_add = true;
        if (request()->get('type') == 'product' && !auth()->user()->can('category.create')) {
            $can_add = false;
        }
    @endphp
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>{{ $module_category_data['heading'] ?? __('category.categories') }}</h5>
                    @if ($can_add)
                        <a class="btn btn-primary btn-sm btn-modal float-end"
                            data-href="{{action([\App\Http\Controllers\TaxonomyController::class, 'create'])}}?type={{request()->get('type')}}"
                            data-container=".category_modal"><i class="fa fa-plus"></i> @lang('messages.add')</a>
                    @endif
                </div>
                <div class="card-body taxonomy_body">
                    <div class="row align-items-center mb-2" id="category_dt_top">
                        <div class="col-sm-12 col-md-3" id="category_dt_length"></div>
                        <div class="col-sm-12 col-md-6 text-center" id="category_dt_buttons"></div>
                        <div class="col-sm-12 col-md-3 text-md-end" id="category_dt_filter"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="category_table">
                            <thead>
                                <tr>
                                    <th>
                                        @if (!empty($module_category_data['taxonomy_label']))
                                            {{ $module_category_data['taxonomy_label'] }}
                                        @else
                                            @lang('category.category')
                                        @endif
                                    </th>
                                    @if ($cat_code_enabled)
                                        <th>{{ $module_category_data['taxonomy_code_label'] ?? __('category.code') }}</th>
                                    @endif
                                    <th>@lang('lang_v1.description')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="row align-items-center mt-2" id="category_dt_bottom">
                        <div class="col-sm-12 col-md-5" id="category_dt_info"></div>
                        <div class="col-sm-12 col-md-7 text-md-end" id="category_dt_paginate"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade category_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
    </div>
</section>
@stop
@section('javascript')
    @includeIf('templates.viho.taxonomy.taxonomies_js')
@endsection