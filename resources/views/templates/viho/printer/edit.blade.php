<div class="modal-dialog" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action([\App\Http\Controllers\PrinterController::class, 'update'], [$printer->id]), 'method' => 'put', 'id' => 'printer_edit_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'printer.edit_printer_setting' )</h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('name', __( 'printer.name' ) . ':*') !!}
            {!! Form::text('name', $printer->name, ['class' => 'form-control', 'required', 'placeholder' => __( 'printer.name' ) ]); !!}
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6">
          <div class="form-group">
            {!! Form::label('connection_type', __( 'printer.connection_type' ) . ':*') !!}
            {!! Form::select('connection_type', $connection_types, $printer->connection_type, ['class' => 'form-control select2', 'required', 'style' => 'width:100%;']); !!}
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            {!! Form::label('capability_profile', __( 'printer.capability_profile' ) . ':*') !!}
            {!! Form::select('capability_profile', $capability_profiles, $printer->capability_profile, ['class' => 'form-control select2', 'required', 'style' => 'width:100%;']); !!}
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('char_per_line', __( 'printer.char_per_line' ) . ':') !!}
            {!! Form::number('char_per_line', $printer->char_per_line, ['class' => 'form-control', 'placeholder' => __( 'printer.char_per_line' ) ]); !!}
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('ip_address', __( 'printer.ip_address' ) . ':') !!}
            {!! Form::text('ip_address', $printer->ip_address, ['class' => 'form-control', 'placeholder' => __( 'printer.ip_address' ) ]); !!}
            <span class="help-block">@lang('printer.ip_address_help')</span>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('port', __( 'printer.port' ) . ':') !!}
            {!! Form::number('port', $printer->port, ['class' => 'form-control', 'placeholder' => __( 'printer.port' ) ]); !!}
            <span class="help-block">@lang('printer.port_help')</span>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('path', __( 'printer.path' ) . ':') !!}
            {!! Form::text('path', $printer->path, ['class' => 'form-control', 'placeholder' => __( 'printer.path' ) ]); !!}
            <span class="help-block">@lang('printer.path_help')</span>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
    </div>
    {!! Form::close() !!}
  </div>
</div>
