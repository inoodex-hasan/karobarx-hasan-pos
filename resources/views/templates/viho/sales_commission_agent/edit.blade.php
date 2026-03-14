@extends('templates.viho.layout')
@section('title', __('lang_v1.edit_sales_commission_agent'))

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0">@lang('lang_v1.edit_sales_commission_agent')</h5>
                        </div>
                        <div class="ms-auto">
                            <a class="btn btn-outline-secondary" href="{{ url()->previous() }}">
                                Back
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => route('ai-template.sales-commission-agents.update', [$user->id]), 'method' => 'PUT', 'id' => 'sale_commission_agent_form' ]) !!}

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::label('surname', __( 'business.prefix' ) . ':') !!}
                                {!! Form::text('surname', $user->surname, ['class' => 'form-control', 'placeholder' => __( 'business.prefix_placeholder' ) ]); !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                {!! Form::label('first_name', __( 'business.first_name' ) . ':*') !!}
                                {!! Form::text('first_name', $user->first_name, ['class' => 'form-control', 'required', 'placeholder' => __( 'business.first_name' ) ]); !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                {!! Form::label('last_name', __( 'business.last_name' ) . ':') !!}
                                {!! Form::text('last_name', $user->last_name, ['class' => 'form-control', 'placeholder' => __( 'business.last_name' ) ]); !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('email', __( 'business.email' ) . ':') !!}
                                {!! Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => __( 'business.email' ) ]); !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('contact_no', __( 'lang_v1.contact_no' ) . ':') !!}
                                {!! Form::text('contact_no', $user->contact_no, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.contact_no' ) ]); !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('address', __( 'business.address' ) . ':') !!}
                                {!! Form::textarea('address', $user->address, ['class' => 'form-control', 'placeholder' => __( 'business.address'), 'rows' => 3 ]); !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('cmmsn_percent', __( 'lang_v1.cmmsn_percent' ) . ':') !!}
                                {!! Form::text('cmmsn_percent', @num_format($user->cmmsn_percent), ['class' => 'form-control input_number', 'placeholder' => __( 'lang_v1.cmmsn_percent' ), 'required' ]); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">@lang('messages.update')</button>
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
            $('form#sale_commission_agent_form').validate({
                submitHandler: function (form, e) {
                    e.preventDefault();
                    var data = $(form).serialize();

                    $.ajax({
                        method: $(form).attr('method'),
                        url: $(form).attr('action'),
                        dataType: 'json',
                        data: data,
                        success: function (result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                window.location.href = "{{ route('ai-template.sales-commission-agents.index') }}";
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
