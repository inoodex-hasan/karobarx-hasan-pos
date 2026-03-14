<div class="modal-dialog" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action([\App\Http\Controllers\TaxonomyController::class, 'update'], [$category->id]), 'method' => 'PUT', 'id' => 'category_edit_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang('category.edit_category')</h4>
    </div>
    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('category', __( 'category.category' ) . ':*') !!}
        {!! Form::text('name', $category->name, ['class' => 'form-control', 'required']); !!}
      </div>
      <div class="form-group">
        {!! Form::label('description', __( 'lang_v1.description' ) . ':') !!}
        {!! Form::text('description', $category->description, ['class' => 'form-control']); !!}
      </div>
      <div class="checkbox">
        <label>
          {!!Form::checkbox('add_as_sub_cat', 1, !$is_parent, ['class' => 'input-icheck']) !!}
          {{ __( 'category.add_as_sub_category' )}}
        </label>
      </div>
      <div class="form-group">
        {!! Form::label('parent_id', __( 'category.select_parent_category' ) . ':') !!}
        {!! Form::select('parent_id', $parent_categories, $selected_parent, ['class' => 'form-control', 'placeholder' => __( 'messages.please_select' )]); !!}
      </div>
      <div class="form-group">
        {!! Form::label('short_code', __( 'category.code' ) . ':') !!}
        {!! Form::text('short_code', $category->short_code, ['class' => 'form-control']); !!}
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang( 'messages.update' )</button>
      <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>
    {!! Form::close() !!}
  </div>
</div>
