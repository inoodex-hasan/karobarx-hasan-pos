@extends('templates.viho.layout')
@section('title', __('user.users'))

@section('content')
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h5 class="mb-0">@lang('lang_v1.view_user')</h5>
        </div>
        <div class="col-md-6">
            {!! Form::select('user_id', $users, $user->id , ['class' => 'form-control select2', 'id' => 'user_id']); !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    @php
                        if(isset($user->media->display_url)) {
                            $img_src = $user->media->display_url;
                        } else {
                            $img_src = 'https://ui-avatars.com/api/?name='.$user->first_name;
                        }
                    @endphp

                    <img class="img-90 rounded-circle mb-3" src="{{$img_src}}" alt="User profile picture">

                    <h6 class="mb-0">{{$user->user_full_name}}</h6>
                    <div class="text-muted small mb-3" title="@lang('user.role')">{{$user->role_name}}</div>

                    <ul class="list-group text-start mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>@lang('business.username')</span>
                            <span class="text-muted">{{$user->username}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>@lang('business.email')</span>
                            <span class="text-muted">{{$user->email}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ __('lang_v1.status_for_user') }}</span>
                            @if($user->status == 'active')
                                <span class="badge badge-success">@lang('business.is_active')</span>
                            @else
                                <span class="badge badge-danger">@lang('lang_v1.inactive')</span>
                            @endif
                        </li>
                    </ul>

                    @can('user.update')
                        <a href="{{route('ai-template.users.edit', [$user->id])}}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i> @lang("messages.edit")
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs viho-user-tabs" role="tablist">
                        <li class="active">
                            <a href="#user_info_tab" data-toggle="tab" aria-expanded="true">
                                @lang('lang_v1.user_info')
                            </a>
                        </li>
                        <li>
                            <a href="#documents_and_notes_tab" data-toggle="tab" aria-expanded="false">
                                @lang('lang_v1.documents_and_notes')
                            </a>
                        </li>
                        <li>
                            <a href="#activities_tab" data-toggle="tab" aria-expanded="false">
                                @lang('lang_v1.activities')
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content pt-3">
                        <div class="tab-pane active" id="user_info_tab">
                            @php
                                $custom_labels = json_decode(session('business.custom_labels'), true);
                            @endphp

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <h6 class="mb-0">@lang('lang_v1.more_info')</h6>
                                </div>

                                <div class="col-md-4">
                                    <p><strong>@lang('lang_v1.dob'):</strong> @if (!empty($user->dob)) {{ @format_date($user->dob) }} @endif</p>
                                    <p><strong>@lang('lang_v1.gender'):</strong> @if (!empty($user->gender)) @lang('lang_v1.' . $user->gender) @endif</p>
                                    <p><strong>@lang('lang_v1.marital_status'):</strong> @if (!empty($user->marital_status)) @lang('lang_v1.' . $user->marital_status) @endif</p>
                                    <p><strong>@lang('lang_v1.blood_group'):</strong> {{ $user->blood_group ?? '' }}</p>
                                    <p><strong>@lang('lang_v1.mobile_number'):</strong> {{ $user->contact_number ?? '' }}</p>
                                    <p><strong>@lang('business.alternate_number'):</strong> {{ $user->alt_number ?? '' }}</p>
                                    <p><strong>@lang('lang_v1.family_contact_number'):</strong> {{ $user->family_number ?? '' }}</p>
                                </div>

                                <div class="col-md-4">
                                    <p><strong>@lang('lang_v1.fb_link'):</strong> {{ $user->fb_link ?? '' }}</p>
                                    <p><strong>@lang('lang_v1.twitter_link'):</strong> {{ $user->twitter_link ?? '' }}</p>
                                    <p><strong>@lang('lang_v1.social_media', ['number' => 1]):</strong> {{ $user->social_media_1 ?? '' }}</p>
                                    <p><strong>@lang('lang_v1.social_media', ['number' => 2]):</strong> {{ $user->social_media_2 ?? '' }}</p>
                                </div>

                                <div class="col-md-4">
                                    <p><strong>{{ $custom_labels['user']['custom_field_1'] ?? __('lang_v1.user_custom_field1') }}:</strong> {{ $user->custom_field_1 ?? '' }}</p>
                                    <p><strong>{{ $custom_labels['user']['custom_field_2'] ?? __('lang_v1.user_custom_field2') }}:</strong> {{ $user->custom_field_2 ?? '' }}</p>
                                    <p><strong>{{ $custom_labels['user']['custom_field_3'] ?? __('lang_v1.user_custom_field3') }}:</strong> {{ $user->custom_field_3 ?? '' }}</p>
                                    <p><strong>{{ $custom_labels['user']['custom_field_4'] ?? __('lang_v1.user_custom_field4') }}:</strong> {{ $user->custom_field_4 ?? '' }}</p>
                                </div>

                                <div class="col-12 mt-2">
                                    <hr class="my-2">
                                </div>

                                <div class="col-md-4">
                                    <p><strong>@lang('lang_v1.id_proof_name'):</strong> {{ $user->id_proof_name ?? '' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>@lang('lang_v1.id_proof_number'):</strong> {{ $user->id_proof_number ?? '' }}</p>
                                </div>

                                <div class="col-12 mt-2">
                                    <hr class="my-2">
                                </div>

                                <div class="col-md-6">
                                    <strong>@lang('lang_v1.permanent_address'):</strong>
                                    <p class="mb-0">{{ $user->permanent_address ?? '' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>@lang('lang_v1.current_address'):</strong>
                                    <p class="mb-0">{{ $user->current_address ?? '' }}</p>
                                </div>

                                <div class="col-12 mt-2">
                                    <hr class="my-2">
                                </div>

                                <div class="col-12 mb-2">
                                    <h6 class="mb-0">@lang('lang_v1.bank_details'):</h6>
                                </div>

                                @php
                                    $bank_details = !empty($user->bank_details) ? json_decode($user->bank_details, true) : [];
                                @endphp

                                <div class="col-md-4">
                                    <p><strong>@lang('lang_v1.account_holder_name'):</strong> {{ $bank_details['account_holder_name'] ?? '' }}</p>
                                    <p><strong>@lang('lang_v1.account_number'):</strong> {{ $bank_details['account_number'] ?? '' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>@lang('lang_v1.bank_name'):</strong> {{ $bank_details['bank_name'] ?? '' }}</p>
                                    <p><strong>@lang('lang_v1.bank_code'):</strong> {{ $bank_details['bank_code'] ?? '' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>@lang('lang_v1.branch'):</strong> {{ $bank_details['branch'] ?? '' }}</p>
                                    <p><strong>@lang('lang_v1.tax_payer_id'):</strong> {{ $bank_details['tax_payer_id'] ?? '' }}</p>
                                </div>

                                @if (!empty($view_partials))
                                    <div class="col-12">
                                        @foreach ($view_partials as $partial)
                                            {!! $partial !!}
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane" id="documents_and_notes_tab">
                            <input type="hidden" name="notable_id" id="notable_id" value="{{$user->id}}">
                            <input type="hidden" name="notable_type" id="notable_type" value="App\\User">
                            <div class="document_note_body"></div>
                        </div>
                        <div class="tab-pane" id="activities_tab">
                            @include('activity_log.activities')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .viho-user-tabs {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 12px;
        }

        .viho-user-tabs > li {
            float: none !important;
            display: inline-block !important;
            width: auto !important;
            margin: 0 !important;
        }

        .viho-user-tabs > li > a {
            white-space: nowrap;
        }
    </style>
@endpush

@section('javascript')
    @include('documents_and_notes.document_and_note_js')

    <script type="text/javascript">
        $(document).ready(function () {
            $('#user_id').change(function () {
                if ($(this).val()) {
                    window.location = "{{url('/ai-template/users')}}/" + $(this).val();
                }
            });

            // Ensure Documents & Notes loads even when tab is hidden on initial page load.
            $(document).on('shown.bs.tab', 'a[data-toggle="tab"][href="#documents_and_notes_tab"]', function () {
                if (typeof getDocAndNoteIndexPage === 'function') {
                    getDocAndNoteIndexPage();
                }
                if (typeof initializeDocumentAndNoteDataTable === 'function') {
                    setTimeout(function () {
                        // re-init safe: destroy if already exists
                        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#documents_and_notes_table')) {
                            $('#documents_and_notes_table').DataTable().destroy();
                        }
                        initializeDocumentAndNoteDataTable();
                    }, 200);
                }
            });
        });
    </script>
@endsection
