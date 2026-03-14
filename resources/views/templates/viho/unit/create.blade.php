<div class="modal-dialog" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action([\App\Http\Controllers\UnitController::class, 'store']), 'method' => 'post', 'id' => 'unit_add_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang('unit.add_unit')</h4>
    </div>
    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('actual_name', __( 'unit.name' ) . ':*') !!}
        {!! Form::text('actual_name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'unit.name' ) ]); !!}
      </div>
      <div class="form-group">
        {!! Form::label('short_name', __( 'unit.short_name' ) . ':*') !!}
        {!! Form::text('short_name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'unit.short_name' )]); !!}
      </div>
      <div class="checkbox">
        <label>
          {!!Form::checkbox('allow_decimal', 1, false, ['class' => 'input-icheck']) !!}
          {{ __( 'unit.allow_decimal' )}}
        </label>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang( 'messages.save' )</button>
      <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>
    {!! Form::close() !!}
  </div>
</div>
