<div class="modal-dialog" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action([\App\Http\Controllers\BarcodeController::class, 'update'], [$barcode->id]), 'method' => 'put', 'id' => 'barcode_edit_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'barcode.edit_barcode_setting' )</h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('name', __( 'barcode.setting_name' ) . ':*') !!}
            {!! Form::text('name', $barcode->name, ['class' => 'form-control', 'placeholder' => __( 'barcode.setting_name' ) ]); !!}
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('description', __( 'barcode.setting_description' ) . ':') !!}
            {!! Form::text('description', $barcode->description, ['class' => 'form-control', 'placeholder' => __( 'barcode.setting_description' ) ]); !!}
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6">
          <div class="form-group">
            {!! Form::label('width', __( 'barcode.width' ) . ':') !!}
            {!! Form::number('width', $barcode->width, ['class' => 'form-control', 'placeholder' => __( 'barcode.width' ) ]); !!}
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            {!! Form::label('height', __( 'barcode.height' ) . ':') !!}
            {!! Form::number('height', $barcode->height, ['class' => 'form-control', 'placeholder' => __( 'barcode.height' ) ]); !!}
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6">
          <div class="form-group">
            {!! Form::label('code', __( 'barcode.barcode_code' ) . ':*') !!}
            {!! Form::select('code', $codes, $barcode->code, ['class' => 'form-control select2', 'style' => 'width:100%;']); !!}
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            {!! Form::label('display_format', __( 'barcode.display_format' ) . ':') !!}
            {!! Form::select('display_format', $display_formats, $barcode->display_format, ['class' => 'form-control select2', 'style' => 'width:100%;']); !!}
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
