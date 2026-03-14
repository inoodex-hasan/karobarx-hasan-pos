@extends('templates.viho.layout')
@php
    $heading = !empty($module_category_data['heading']) ? $module_category_data['heading'] : __('category.categories');
    $navbar = !empty($module_category_data['navbar']) ? $module_category_data['navbar'] : null;
@endphp
@section('title', $heading)

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
                    </div>
                </div>
            </div>
            <div class="modal fade category_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
        </div>
    </section>
@stop
@section('javascript')
    @includeIf('taxonomy.taxonomies_js')
@endsection
