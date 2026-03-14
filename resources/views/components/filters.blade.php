{{-- <div class="box @if (!empty($class)) {{$class}} @else box-solid @endif" id="accordion">
  <div class="box-header with-border" style="cursor: pointer;">
    <h3 class="box-title">
      <a data-toggle="collapse" data-parent="#accordion" href="#collapseFilter">
        @if (!empty($icon)) {!! $icon !!} @else <i class="fa fa-filter" aria-hidden="true"></i> @endif {{$title ?? ''}}
      </a>
    </h3>
  </div>
  @php
    if(isMobile()) {
      $closed = true;
    }
  @endphp
  <div id="collapseFilter" class="panel-collapse active collapse @if (empty($closed)) in @endif" aria-expanded="true">
    <div class="box-body">
      {{$slot}}
    </div>
  </div>
</div> --}}


@php
    $collapse_id = $collapse_id ?? ('collapseFilter_' . \Illuminate\Support\Str::random(8));
    $is_ai_template = request()->is('ai-template/*');
@endphp

@once
    @push('styles')
        <style>
            /* Prevent the collapse toggle link/header from overlapping the filter body in some themes. */
            .filters-card {
                position: relative;
            }

            .filters-card .box-header {
                position: relative;
                z-index: 1;
            }

            .filters-card .box-title a {
                display: inline-flex !important;
                width: auto !important;
                position: static !important;
                z-index: 1 !important;
            }

            .filters-card .panel-collapse {
                position: relative;
                z-index: 9999;
            }
        </style>
    @endpush

    <script>
        (function () {
            if (window.__filtersCardGuardInstalled) return;
            window.__filtersCardGuardInstalled = true;

            var markInteracting = function (target) {
                var collapseEl = target && target.closest ? target.closest('.filters-card .panel-collapse') : null;
                if (!collapseEl) return;
                collapseEl.dataset.filtersInteracting = '1';
                window.clearTimeout(collapseEl.__filtersInteractingTimeout);
                collapseEl.__filtersInteractingTimeout = window.setTimeout(function () {
                    try { delete collapseEl.dataset.filtersInteracting; } catch (e) { collapseEl.dataset.filtersInteracting = ''; }
                }, 400);
            };

            document.addEventListener('mousedown', function (e) {
                markInteracting(e.target);
            }, true);

            // If the collapse tries to hide while the user is interacting with inputs inside it, cancel it.
            document.addEventListener('hide.bs.collapse', function (e) {
                var el = e && e.target ? e.target : null;
                if (!el || !el.classList || !el.classList.contains('panel-collapse')) return;
                if (el.dataset && el.dataset.filtersInteracting === '1') {
                    e.preventDefault();
                }
            }, true);
        })();
    </script>
@endonce

<div
    class="filters-card tw-transition-all tw-mb-4 lg:tw-col-span-1 tw-duration-200 tw-bg-white tw-shadow-sm tw-rounded-xl tw-ring-1 hover:tw-shadow-md tw-ring-gray-200">
    <div class="box-header with-border" style="cursor: pointer;">
        <h3 class="box-title tw-pt-2 tw-pb-2 tw-pl-2">
            <a
                @if ($is_ai_template)
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapse_id }}"
                @else
                    data-toggle="collapse"
                    data-target="#{{ $collapse_id }}"
                @endif
                href="#{{ $collapse_id }}"
                aria-controls="{{ $collapse_id }}"
                aria-expanded="{{ empty($closed) ? 'true' : 'false' }}">
                @if (!empty($icon))
                    {!! $icon !!}
                @else
                    <i class="fa fa-filter" aria-hidden="true"></i>
                @endif {{ $title ?? '' }}
            </a>
        </h3>
    </div>
    @php
        // Default behavior: open on desktop, collapsed on mobile.
        $closed = $closed ?? isMobile();
    @endphp
    <div id="{{ $collapse_id }}"
        class="panel-collapse active collapse @if (empty($closed)) in show @endif tw-pt-4 tw-pb-4"
        aria-expanded="{{ empty($closed) ? 'true' : 'false' }}">
        <div class="box-body">
            {{ $slot }}
        </div>
    </div>
</div>
