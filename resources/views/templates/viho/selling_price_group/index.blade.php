@extends('templates.viho.layout')
@section('title', __('lang_v1.selling_price_group'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.selling_price_group')
        </h1>
        <!-- <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @if (session('notification') || !empty($notification))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        @if (!empty($notification['msg']))
                            {{ $notification['msg'] }}
                        @elseif(session('notification.msg'))
                            {{ session('notification.msg') }}
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @component('components.widget', [
            'class' => 'box-primary',
            'title' => __('lang_v1.all_selling_price_group'),
            'help_text' => __('lang_v1.selling_price_help_text'),
        ])
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-primary btn-sm btn-modal float-end"
                        data-href="{{ route('ai-template.selling-price-group.create') }}"
                        data-container=".view_modal">
                        <i class="fa fa-plus"></i> @lang('messages.add')
                    </a>
                </div>
            @endslot
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="selling_price_group_table">
                    <thead>
                        <tr>
                            <th>@lang('lang_v1.name')</th>
                            <th>@lang('lang_v1.description')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcomponent

        <div class="modal fade brands_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            var destroyDataTable = function(selector) {
                if (!window.jQuery || !$.fn || !$.fn.DataTable) {
                    return;
                }

                if ($.fn.DataTable.isDataTable(selector)) {
                    $(selector).DataTable().clear().destroy();
                    $(selector).find('tbody').remove();
                }
            };

            //selling_price_group_table
            destroyDataTable('#selling_price_group_table');
            var selling_price_group_table = $('#selling_price_group_table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader:false,
                ajax: '{{ route('ai-template.selling-price-group.index') }}',
                columnDefs: [{
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                }]
            });

            window.selling_price_group_table = selling_price_group_table;

            $(document)
                .off('submit.vihoSpg', 'form#selling_price_group_form')
                .on('submit.vihoSpg', 'form#selling_price_group_form', function(e) {
                e.preventDefault();
                var data = $(this).serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            $('div.view_modal').modal('hide');
                            toastr.success(result.msg);
                            selling_price_group_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });

            $(document)
                .off('click.vihoSpg', 'button.delete_spg_button')
                .on('click.vihoSpg', 'button.delete_spg_button', function() {
                swal({
                    title: LANG.sure,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    selling_price_group_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });

            $(document)
                .off('click.vihoSpg', 'button.activate_deactivate_spg')
                .on('click.vihoSpg', 'button.activate_deactivate_spg', function() {
                var href = $(this).data('href');
                $.ajax({
                    url: href,
                    dataType: "json",
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            selling_price_group_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });

        });
    </script>
@endsection
