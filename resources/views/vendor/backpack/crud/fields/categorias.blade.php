<div class="form-group{{ $errors->has('categorias_id') ? ' has-error' : '' }}">
    {!! Form::label('categorias_id', 'Categorias', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
    	{!! Form::select('categorias_id[]', \App\Models\Categorias::pluck("nombre","id"), isset($field['value']) ? $field['value']: null, ['id' => 'categorias_id', 'class' => 'form-control chosen', 'required' => 'required', 'multiple']) !!}
    	<small class="text-danger">{{ $errors->first('categorias_id') }}</small>
	</div>
</div>