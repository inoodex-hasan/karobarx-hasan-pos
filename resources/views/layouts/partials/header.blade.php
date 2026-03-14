@inject('request', 'Illuminate\Http\Request')
@php
    $layout_templates = config('constants.layout_templates', ['default' => 'Default']);
    $active_layout_template = data_get(session('business.common_settings', []), 'layout_template', 'default');
    if (!array_key_exists($active_layout_template, $layout_templates)) {
        $active_layout_template = 'default';
    }

    $theme_color = session('business.theme_color') ?: 'primary';
@endphp
<!-- Main Header -->
<style>
    /* Tailwind classes built from runtime strings won't exist in compiled CSS.
       Provide minimal fallbacks for allowed theme colors so header contrast stays correct. */
    .tw-bg-primary-700 {
        background-color: #1d4ed8 !important;
    }

    .tw-bg-primary-800 {
        background-color: #1e40af !important;
    }

    .tw-bg-primary-900 {
        background-color: #1e3a8a !important;
    }

    .hover\:tw-bg-primary-700:hover {
        background-color: #1d4ed8 !important;
    }

    .tw-bg-purple-700 {
        background-color: #7c3aed !important;
    }

    .tw-bg-purple-800 {
        background-color: #6d28d9 !important;
    }

    .tw-bg-purple-900 {
        background-color: #5b21b6 !important;
    }

    .hover\:tw-bg-purple-700:hover {
        background-color: #7c3aed !important;
    }

    .tw-bg-green-700 {
        background-color: #16a34a !important;
    }

    .tw-bg-green-800 {
        background-color: #15803d !important;
    }

    .tw-bg-green-900 {
        background-color: #166534 !important;
    }

    .hover\:tw-bg-green-700:hover {
        background-color: #16a34a !important;
    }

    .tw-bg-red-700 {
        background-color: #dc2626 !important;
    }

    .tw-bg-red-800 {
        background-color: #b91c1c !important;
    }

    .tw-bg-red-900 {
        background-color: #991b1b !important;
    }

    .hover\:tw-bg-red-700:hover {
        background-color: #dc2626 !important;
    }

    .tw-bg-yellow-700 {
        background-color: #ca8a04 !important;
    }

    .tw-bg-yellow-800 {
        background-color: #a16207 !important;
    }

    .tw-bg-yellow-900 {
        background-color: #854d0e !important;
    }

    .hover\:tw-bg-yellow-700:hover {
        background-color: #ca8a04 !important;
    }

    .tw-bg-orange-700 {
        background-color: #ea580c !important;
    }

    .tw-bg-orange-800 {
        background-color: #c2410c !important;
    }

    .tw-bg-orange-900 {
        background-color: #9a3412 !important;
    }

    .hover\:tw-bg-orange-700:hover {
        background-color: #ea580c !important;
    }

    .tw-bg-sky-700 {
        background-color: #0284c7 !important;
    }

    .tw-bg-sky-800 {
        background-color: #0369a1 !important;
    }

    .tw-bg-sky-900 {
        background-color: #075985 !important;
    }

    .hover\:tw-bg-sky-700:hover {
        background-color: #0284c7 !important;
    }

    .tw-from-primary-800 {
        --tw-gradient-from: #1e40af;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(30, 64, 175, 0));
    }

    .tw-to-primary-900 {
        --tw-gradient-to: #1e3a8a;
    }

    .tw-from-purple-800 {
        --tw-gradient-from: #6d28d9;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(109, 40, 217, 0));
    }

    .tw-to-purple-900 {
        --tw-gradient-to: #5b21b6;
    }

    .tw-from-green-800 {
        --tw-gradient-from: #15803d;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(21, 128, 61, 0));
    }

    .tw-to-green-900 {
        --tw-gradient-to: #166534;
    }

    .tw-from-red-800 {
        --tw-gradient-from: #b91c1c;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(185, 28, 28, 0));
    }

    .tw-to-red-900 {
        --tw-gradient-to: #991b1b;
    }

    .tw-from-yellow-800 {
        --tw-gradient-from: #a16207;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(161, 98, 7, 0));
    }

    .tw-to-yellow-900 {
        --tw-gradient-to: #854d0e;
    }

    .tw-from-orange-800 {
        --tw-gradient-from: #c2410c;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(194, 65, 12, 0));
    }

    .tw-to-orange-900 {
        --tw-gradient-to: #9a3412;
    }

    .tw-from-sky-800 {
        --tw-gradient-from: #0369a1;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(3, 105, 161, 0));
    }

    .tw-to-sky-900 {
        --tw-gradient-to: #075985;
    }
</style>

<div
    class="tw-transition-all tw-duration-5000 tw-border-b tw-bg-gradient-to-r tw-from-{{ $theme_color }}-800 tw-to-{{ $theme_color }}-900 tw-shrink-0 tw-border-primary-500/30 no-print">
    <div class="tw-px-5 tw-py-3">
        <div class="tw-flex tw-items-start tw-justify-between tw-gap-2 sm:tw-gap-6 lg:tw-items-center">
            <div class="tw-flex tw-items-center tw-gap-3">
                <button type="button"
                    class="small-view-button xl:tw-w-20 lg:tw-hidden tw-inline-flex tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white tw-transition-all tw-duration-200 tw-bg-{{ $theme_color }}-800 hover:tw-bg-{{ $theme_color }}-700 tw-p-1.5 tw-rounded-lg tw-ring-1 hover:tw-text-white tw-ring-white/10">
                    <span class="tw-sr-only">
                        Sidebar Menu
                    </span>
                    <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 6l16 0" />
                        <path d="M4 12l16 0" />
                        <path d="M4 18l16 0" />
                    </svg>
                </button>

                <button type="button"
                    class="side-bar-collapse tw-hidden lg:tw-inline-flex tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white tw-transition-all tw-duration-200 tw-bg-{{ $theme_color }}-800 hover:tw-bg-{{ $theme_color }}-700 tw-p-1.5 tw-rounded-lg tw-ring-1 hover:tw-text-white tw-ring-white/10">
                    <span class="tw-sr-only">
                        Collapse Sidebar
                    </span>
                    <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M15 4v16" />
                        <path d="M10 10l-2 2l2 2" />
                    </svg>
                </button>
            </div>


            {{-- Showing active package for SaaS Superadmin --}}
            @if (Module::has('Superadmin'))
                @includeIf('superadmin::layouts.partials.active_subscription')
            @endif

            {{-- When using superadmin, this button is used to switch users --}}
            @if (!empty(session('previous_user_id')) && !empty(session('previous_username')))
                <a href="{{ route('sign-in-as-user', session('previous_user_id')) }}"
                    class="btn btn-flat btn-danger m-8 btn-sm mt-10"><i class="fas fa-undo"></i> @lang('lang_v1.back_to_username', ['username' => session('previous_username')])</a>
            @endif


            <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-end tw-gap-1 sm:tw-gap-3">
                @if (Module::has('Essentials'))
                    @includeIf('essentials::layouts.partials.header_part')
                @endif

                @can('business_settings.access')
                    <details class="tw-dw-dropdown tw-relative tw-inline-block tw-text-left">
                        <summary
                            class="template-switch-trigger tw-inline-flex tw-transition-all tw-ring-1 tw-ring-white/10 hover:tw-text-white tw-cursor-pointer tw-duration-200 tw-bg-{{ $theme_color }}-800 hover:tw-bg-{{ $theme_color }}-700 tw-py-1.5 tw-px-3 tw-rounded-lg tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white tw-gap-1">
                            <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 6h16" />
                                <path d="M4 12h16" />
                                <path d="M4 18h16" />
                            </svg>
                            <span class="tw-hidden sm:tw-inline">{{ $layout_templates[$active_layout_template] ?? ucfirst($active_layout_template) }}</span>
                        </summary>
                        <ul class="tw-dw-menu tw-dw-dropdown-content tw-dw-z-[1] tw-dw-bg-base-100 tw-dw-rounded-box tw-w-52 tw-absolute tw-left-0 tw-z-10 tw-mt-2 tw-origin-top-right tw-bg-white tw-rounded-lg tw-shadow-lg tw-ring-1 tw-ring-gray-200 focus:tw-outline-none"
                            role="menu" tabindex="-1">
                            <div class="tw-p-2" role="none">
                                @foreach ($layout_templates as $template_key => $template_label)
                                    <li role="none">
                                        <button type="button"
                                            class="js-layout-template-switch tw-w-full tw-flex tw-items-center tw-justify-between tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-700 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                            data-template-key="{{ $template_key }}"
                                            data-update-url="{{ route('business.updateLayoutTemplate') }}"
                                            @if ($template_key === $active_layout_template) disabled @endif>
                                            <span>{{ $template_label }}</span>
                                            @if ($template_key === $active_layout_template)
                                                <span class="tw-text-xs tw-text-green-600">Active</span>
                                            @endif
                                        </button>
                                    </li>
                                @endforeach
                            </div>
                        </ul>
                    </details>
                @endcan

                <details class="tw-dw-dropdown tw-relative tw-inline-block tw-text-left">
                    <summary
                        class="tw-inline-flex tw-transition-all tw-ring-1 tw-ring-white/10 hover:tw-text-white tw-cursor-pointer tw-duration-200 tw-bg-{{ $theme_color }}-800 hover:tw-bg-{{ $theme_color }}-700 tw-py-1.5 tw-px-3 tw-rounded-lg tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white tw-gap-1">
                        <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                            <path d="M9 12h6" />
                            <path d="M12 9v6" />
                        </svg>
                    </summary>
                    <ul class="tw-dw-menu tw-dw-dropdown-content tw-dw-z-[1] tw-dw-bg-base-100 tw-dw-rounded-box tw-w-48 tw-absolute tw-left-0 tw-z-10 tw-mt-2 tw-origin-top-right tw-bg-white tw-rounded-lg tw-shadow-lg tw-ring-1 tw-ring-gray-200 focus:tw-outline-none"
                        role="menu" tabindex="-1">
                        <div class="tw-p-2" role="none">
                            <a href="{{ route('calendar') }}"
                                class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                role="menuitem" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <rect x="4" y="5" width="16" height="16" rx="2" />
                                    <line x1="16" y1="3" x2="16" y2="7" />
                                    <line x1="8" y1="3" x2="8" y2="7" />
                                    <line x1="4" y1="11" x2="20" y2="11" />
                                    <line x1="11" y1="15" x2="12" y2="15" />
                                    <line x1="12" y1="15" x2="12" y2="18" />
                                </svg>
                                @lang('lang_v1.calendar')
                            </a>
                            @if (Module::has('Essentials'))
                                <a href="#"
                                    data-href="{{ action([\Modules\Essentials\Http\Controllers\ToDoController::class, 'create']) }}"
                                    data-container="#task_modal"
                                    class="btn-modal tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                    role="menuitem" tabindex="-1">
                                    <svg aria-hidden="true" class="tw-w-5 tw-h-5" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                        <path d="M9 12l2 2l4 -4" />
                                    </svg>
                                    @lang('essentials::lang.add_to_do')
                                </a>
                            @endif
                            @if (auth()->user()->hasRole('Admin#' . auth()->user()->business_id))
                                <a href="#" id="start_tour"
                                    class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                    role="menuitem" tabindex="-1">
                                    <svg aria-hidden="true" class="tw-w-5 tw-h-5" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                        <path d="M12 17l0 .01" />
                                        <path d="M12 13.5a1.5 1.5 0 0 1 1 -1.5a2.6 2.6 0 1 0 -3 -4" />
                                    </svg>
                                    @lang('lang_v1.application_tour')
                                </a>
                            @endif
                        </div>
                    </ul>

                </details>


                {{-- data-toggle="popover" remove this for on hover show --}}

                <button id="btnCalculator" title="@lang('lang_v1.calculator')" data-content='@include('layouts.partials.calculator')'
                    type="button" data-trigger="click" data-html="true" data-placement="bottom"
                    class="tw-hidden md:tw-inline-flex tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white tw-transition-all tw-duration-200 tw-bg-{{ $theme_color }}-800 hover:tw-bg-{{ $theme_color }}-700 tw-p-1.5 tw-rounded-lg tw-ring-1 hover:tw-text-white tw-ring-white/10">
                    <span class="tw-sr-only" aria-hidden="true">
                        Calculator
                    </span>
                    <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z" />
                        <path d="M8 14l0 .01" />
                        <path d="M12 14l0 .01" />
                        <path d="M16 14l0 .01" />
                        <path d="M8 17l0 .01" />
                        <path d="M12 17l0 .01" />
                        <path d="M16 17l0 .01" />
                    </svg>
                </button>

                @if (in_array('pos_sale', $enabled_modules))
                    @can('sell.create')
                        <a href="{{ route('pos.create') }}"
                            class="tw-inline-flex tw-transition-all tw-duration-200 tw-gap-2 tw-bg-{{ $theme_color }}-800 hover:tw-bg-{{ $theme_color }}-700 tw-py-1.5 tw-px-3 tw-rounded-lg tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-ring-1 tw-ring-white/10 hover:tw-text-white tw-text-white"
                            title="@lang('sale.pos_sale')">
                            <svg aria-hidden="true" class="tw-size-5"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                            </svg>
                            <span class="tw-hidden lg:tw-inline">@lang('sale.pos_sale')</span>
                        </a>
                    @endcan
                @endif
                @if (Module::has('Repair'))
                    @includeIf('repair::layouts.partials.header')
                @endif
                @can('profit_loss_report.view')
                    <button type="button" type="button" id="view_todays_profit"
                        title="{{ __('home.todays_profit') }}" data-toggle="tooltip" data-placement="bottom"
                        class="tw-hidden sm:tw-inline-flex tw-items-center tw-ring-1 tw-ring-white/10 tw-justify-center tw-text-sm tw-font-medium tw-text-white hover:tw-text-white tw-transition-all tw-duration-200 tw-bg-{{ $theme_color }}-800 hover:tw-bg-{{ $theme_color }}-700 tw-p-1.5 tw-rounded-lg">
                        <span class="tw-sr-only">
                            Today's Profit
                        </span>
                        <svg aria-hidden="true" class="tw-size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M3 6m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                            <path d="M18 12l.01 0" />
                            <path d="M6 12l.01 0" />
                        </svg>
                    </button>
                @endcan

                <button type="button"
                    class="tw-hidden md:tw-inline-flex tw-transition-all tw-ring-1 tw-ring-white/10 tw-duration-200 tw-bg-{{ $theme_color }}-800 hover:tw-bg-{{ $theme_color }}-700 tw-py-1.5 tw-px-3 tw-rounded-lg tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white hover:tw-text-white tw-font-mono">
                    {{ @format_date('now') }}
                </button>

                @include('layouts.partials.header-notifications')

                <details class="tw-dw-dropdown tw-relative tw-inline-block tw-text-left">
                    <summary data-toggle="popover"
                        class="tw-dw-m-1 tw-inline-flex tw-transition-all tw-ring-1 tw-ring-white/10 tw-cursor-pointer tw-duration-200 tw-bg-{{ $theme_color }}-800 hover:tw-bg-{{ $theme_color }}-700 tw-py-1.5 tw-px-3 tw-rounded-lg tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-white hover:tw-text-white tw-gap-1">
                        <span class="tw-hidden sm:tw-block">{{ Auth::User()->first_name }}
                            {{ Auth::User()->last_name }}</span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="tw-size-5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                        </svg>
                    </summary>

                    <ul class="tw-p-2 tw-w-48 tw-absolute tw-right-0 tw-z-10 tw-mt-2 tw-origin-top-right tw-bg-white tw-rounded-lg tw-shadow-lg tw-ring-1 tw-ring-gray-200 focus:tw-outline-none"
                        role="menu" tabindex="-1" style="right: 10px !important; max-width: calc(100vw - 40px);">
                        <div class="tw-px-4 tw-pt-3 tw-pb-1" role="none">
                            <p class="tw-text-sm" role="none">
                                @lang('lang_v1.signed_in_as')
                            </p>
                            <p class="tw-text-sm tw-font-medium tw-text-gray-900 tw-truncate" role="none">
                                {{ Auth::User()->first_name }} {{ Auth::User()->last_name }}
                            </p>
                        </div>

                        <li>
                            <a href="{{ action([\App\Http\Controllers\UserController::class, 'getProfile']) }}"
                                class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                role="menuitem" tabindex="-1">
                                <svg aria-hidden="true" class="tw-w-5 tw-h-5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                                </svg>
                                @lang('lang_v1.profile')
                            </a>
                        </li>
                        <li>
                            <a href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'logout']) }}"
                                class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-600 tw-transition-all tw-duration-200 tw-rounded-lg hover:tw-text-gray-900 hover:tw-bg-gray-100"
                                role="menuitem" tabindex="-1">
                                <svg aria-hidden="true" class="tw-w-5 tw-h-5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                    <path d="M9 12h12l-3 -3" />
                                    <path d="M18 15l3 -3" />
                                </svg>
                                @lang('lang_v1.sign_out')
                            </a>
                        </li>
                    </ul>
                </details>
            </div>
        </div>
    </div>
</div>
