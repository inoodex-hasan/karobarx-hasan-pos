<div class="modal-dialog" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action([\App\Http\Controllers\UnitController::class, 'update'], [$unit->id]), 'method' => 'PUT', 'id' => 'unit_edit_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang('unit.edit_unit')</h4>
    </div>
    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('actual_name', __( 'unit.name' ) . ':*') !!}
        {!! Form::text('actual_name', $unit->actual_name, ['class' => 'form-control', 'required', 'placeholder' => __( 'unit.name' ) ]); !!}
      </div>
      <div class="form-group">
        {!! Form::label('short_name', __( 'unit.short_name' ) . ':*') !!}
        {!! Form::text('short_name', $unit->short_name, ['class' => 'form-control', 'required', 'placeholder' => __( 'unit.short_name' )]); !!}
      </div>
      <div class="checkbox">
        <label>
          {!!Form::checkbox('allow_decimal', 1, $unit->allow_decimal, ['class' => 'input-icheck']) !!}
          {{ __( 'unit.allow_decimal' )}}
        </label>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang( 'messages.update' )</button>
      <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>
    {!! Form::close() !!}
  </div>
</div>
