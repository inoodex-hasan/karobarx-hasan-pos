@extends('templates.viho.layout')

@section('title', __('user.edit_user'))

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>@lang('user.edit_user')</h5>
                </div>
                <div class="card-body">
                    {!! Form::model($user, ['url' => route('ai-template.users.update', [$user->id]), 'method' => 'PUT', 'id' => 'user_edit_form']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::label('surname', __('business.prefix') . ':') !!}
                                {!! Form::text('surname', null, ['class' => 'form-control', 'placeholder' => __('business.prefix_placeholder')]) !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                {!! Form::label('first_name', __('business.first_name') . ':*') !!}
                                {!! Form::text('first_name', null, ['class' => 'form-control', 'required', 'placeholder' => __('business.first_name')]) !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                {!! Form::label('last_name', __('business.last_name') . ':') !!}
                                {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __('business.last_name')]) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('email', __('business.email') . ':*') !!}
                                {!! Form::text('email', null, ['class' => 'form-control', 'required', 'placeholder' => __('business.email')]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox checkbox-primary m-t-15">
                                    {!! Form::checkbox('is_active', 'active', $is_checked_checkbox, ['id' => 'is_active']) !!}
                                    <label for="is_active">{{ __('lang_v1.status_for_user') }}</label>
                                    @show_tooltip(__('lang_v1.tooltip_enable_user_active'))
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox checkbox-primary m-t-15">
                                    {!! Form::checkbox('is_enable_service_staff_pin', 1, $user->is_enable_service_staff_pin, ['id' => 'is_enable_service_staff_pin']) !!}
                                    <label for="is_enable_service_staff_pin">{{ __('lang_v1.enable_service_staff_pin') }}</label>
                                    @show_tooltip(__('lang_v1.tooltip_is_enable_service_staff_pin'))
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 {{ $user->is_enable_service_staff_pin ? '' : 'hide' }} service_staff_pin_div"
                            id="service_staff_pin_div">
                            <div class="form-group">
                                {!! Form::label('service_staff_pin', __('lang_v1.staff_pin') . ':') !!}
                                {!! Form::password('service_staff_pin', ['class' => 'form-control', 'placeholder' => __('lang_v1.staff_pin')]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="mb-3">@lang('lang_v1.roles_and_permissions')</h6>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox checkbox-primary m-t-15">
                                    {!! Form::checkbox('allow_login', 1, $user->allow_login, ['id' => 'allow_login']) !!}
                                    <label for="allow_login">{{ __('lang_v1.allow_login') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="user_auth_fields row w-100 m-0 {{ $user->allow_login ? '' : 'hide' }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('username', __('business.username') . ':') !!}
                                    @if(!empty($username_ext))
                                        <div class="input-group">
                                            {!! Form::text('username', str_replace($username_ext, '', $user->username), ['class' => 'form-control', 'placeholder' => __('business.username')]) !!}
                                            <span class="input-group-text">{{$username_ext}}</span>
                                        </div>
                                        <p class="help-block" id="show_username"></p>
                                    @else
                                        {!! Form::text('username', $user->username, ['class' => 'form-control', 'placeholder' => __('business.username')]) !!}
                                    @endif
                                    <p class="help-block text-muted small">@lang('lang_v1.username_help')</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('password', __('business.password') . ':') !!}
                                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => __('business.password')]) !!}
                                    <p class="help-block text-muted small">@lang('lang_v1.password_help')</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('confirm_password', __('business.confirm_password') . ':') !!}
                                    {!! Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => __('business.confirm_password')]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                {!! Form::label('role', __('user.role') . ':*') !!}
                                @show_tooltip(__('lang_v1.admin_role_location_permission_help'))
                                {!! Form::select('role', $roles, !empty($user->roles->first()->id) ? $user->roles->first()->id : null, ['class' => 'form-control select2', 'style' => 'width: 100%;']) !!}
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <h6>@lang('role.access_locations') @show_tooltip(__('tooltip.access_locations_permission'))
                            </h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="checkbox checkbox-primary m-t-15">
                                        {!! Form::checkbox('access_all_locations', 'access_all_locations', !is_array($permitted_locations) && $permitted_locations == 'all', ['id' => 'access_all_locations']) !!}
                                        <label for="access_all_locations">{{ __('role.all_locations') }}</label>
                                        @show_tooltip(__('tooltip.all_location_permission'))
                                    </div>
                                </div>
                                @foreach($locations as $location)
                                    <div class="col-md-4">
                                        @php($location_checkbox_id = 'location_permissions_' . $location->id)
                                        <div class="checkbox checkbox-primary m-t-15">
                                            {!! Form::checkbox('location_permissions[]', 'location.' . $location->id, is_array($permitted_locations) && in_array($location->id, $permitted_locations), ['id' => $location_checkbox_id]) !!}
                                            <label for="{{ $location_checkbox_id }}">
                                                {{ $location->name }}
                                                @if(!empty($location->location_id))({{ $location->location_id}}) @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <hr>
                            <h6 class="mb-3">@lang('sale.sells')</h6>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('cmmsn_percent', __('lang_v1.cmmsn_percent') . ':') !!}
                                @show_tooltip(__('lang_v1.commsn_percent_help'))
                                {!! Form::text('cmmsn_percent', @num_format($user->cmmsn_percent), ['class' => 'form-control input_number', 'placeholder' => __('lang_v1.cmmsn_percent')]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('max_sales_discount_percent', __('lang_v1.max_sales_discount_percent') . ':') !!}
                                @show_tooltip(__('lang_v1.max_sales_discount_percent_help'))
                                {!! Form::text('max_sales_discount_percent', @num_format($user->max_sales_discount_percent), ['class' => 'form-control input_number', 'placeholder' => __('lang_v1.max_sales_discount_percent')]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox checkbox-primary m-t-15">
                                    {!! Form::checkbox('selected_contacts', 1, $user->selected_contacts, ['id' => 'selected_contacts']) !!}
                                    <label for="selected_contacts">{{ __('lang_v1.allow_selected_contacts') }}</label>
                                    @show_tooltip(__('lang_v1.allow_selected_contacts_tooltip'))
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 {{ $user->selected_contacts ? '' : 'hide' }} selected_contacts_div mt-3">
                            <div class="form-group">
                                {!! Form::label('user_allowed_contacts', __('lang_v1.selected_contacts') . ':') !!}
                                {!! Form::select('selected_contact_ids[]', $contact_access, array_keys($contact_access), ['class' => 'form-control select2', 'multiple', 'style' => 'width: 100%;', 'id' => 'user_allowed_contacts']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <hr>
                            <h6 class="mb-3">@lang('lang_v1.more_info')</h6>
                        </div>
                        @include('templates.viho.users.form_part')
                    </div>

                    @if(!empty($form_partials))
                        <div class="row mt-4">
                            @foreach($form_partials as $partial)
                                <div class="col-md-12">
                                    {!! $partial !!}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="row mt-5">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-lg"
                                id="submit_user_button">@lang('messages.update')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            var toggleSelectedContacts = function () {
                $('div.selected_contacts_div').toggleClass('hide', !$('#selected_contacts').is(':checked'));
            };
            var toggleServiceStaffPin = function () {
                var checked = $('#is_enable_service_staff_pin').is(':checked');
                $('div.service_staff_pin_div').toggleClass('hide', !checked);
                if (!checked) {
                    $('#service_staff_pin').val('');
                }
            };
            var toggleAllowLogin = function () {
                $('div.user_auth_fields').toggleClass('hide', !$('#allow_login').is(':checked'));
            };

            $('#selected_contacts').on('change', toggleSelectedContacts);
            $('#is_enable_service_staff_pin').on('change', toggleServiceStaffPin);
            $('#allow_login').on('change', toggleAllowLogin);

            toggleSelectedContacts();
            toggleServiceStaffPin();
            toggleAllowLogin();

            $('#user_allowed_contacts').select2({
                ajax: {
                    url: '/contacts/customers',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            all_contact: true
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };
                    },
                },
                templateResult: function (data) {
                    var template = '';
                    if (data.supplier_business_name) {
                        template += data.supplier_business_name + "<br>";
                    }
                    template += data.text + "<br>" + LANG.mobile + ": " + data.mobile;

                    return template;
                },
                minimumInputLength: 1,
                escapeMarkup: function (markup) {
                    return markup;
                },
            });

            $('form#user_edit_form').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    email: {
                        email: true,
                        remote: {
                            url: "/business/register/check-email",
                            type: "post",
                            data: {
                                email: function () {
                                    return $("#email").val();
                                },
                                user_id: {{$user->id}}
                                        }
                        }
                    },
                    password: {
                        minlength: 5
                    },
                    confirm_password: {
                        equalTo: "#password"
                    },
                    username: {
                        minlength: 5,
                        remote: {
                            url: "/business/register/check-username",
                            type: "post",
                            data: {
                                username: function () {
                                    return $("#username").val();
                                },
                                @if(!empty($username_ext))
                                      username_ext: "{{$username_ext}}",
                                @endif
                user_id: {{$user->id}}
                                        }
                                    }
                                }
                            },
            messages: {
            password: {
                minlength: 'Password should be minimum 5 characters',
            },
            confirm_password: {
                equalTo: 'Should be same as password'
            },
            username: {
                remote: 'Invalid username or User already exist'
            },
            email: {
                remote: '{{ __("validation.unique", ["attribute" => __("business.email")]) }}'
            }
        }
                        });

        $('#username').change(function () {
            if ($('#show_username').length > 0) {
                if ($(this).val().trim() != '') {
                    $('#show_username').html("{{__('lang_v1.your_username_will_be')}}: <b>" + $(this).val() + "{{$username_ext}}</b>");
                } else {
                    $('#show_username').html('');
                }
            }
        });
              });
    </script>
@endsection
