@extends('templates.viho.layout')
@section('title', __('lang_v1.notification_templates'))

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>@lang('lang_v1.notification_templates')</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['url' => action([\App\Http\Controllers\NotificationTemplateController::class, 'store']), 'method' => 'post' ]) !!}

                    <div class="row">
                        <div class="col-md-12">
                            @component('components.widget', ['title' => __('lang_v1.notifications') . ':'])
                                @include('notification_template.partials.tabs', ['templates' => $general_notifications])
                            @endcomponent
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @component('components.widget', ['title' => __('lang_v1.customer_notifications') . ':'])
                                @include('notification_template.partials.tabs', ['templates' => $customer_notifications])
                            @endcomponent
                        </div>
                    </div>

    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['title' => __('lang_v1.supplier_notifications') . ':'])
                @include('notification_template.partials.tabs', ['templates' => $supplier_notifications])

                <div class="callout callout-warning">
                    <p>@lang('lang_v1.logo_not_work_in_sms'):</p>
                </div>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-danger btn-lg">@lang('messages.save')</button>
        </div>
    </div>
    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
<script type="text/javascript">
    $('textarea.ckeditor').each( function(){
        var editor_id = $(this).attr('id');
        tinymce.init({
            selector: 'textarea#'+editor_id,
        });
    });
</script>
@endsection