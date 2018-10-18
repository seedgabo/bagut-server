<?php $selected = isset($entry) ? $entry->usuarios : []; ?>
<?php $todos = \App\User::all()->pluck('nombre','id'); ?>

<div class="form-group{{ $errors->has('usuarios[]') ? ' has-error' : '' }} col-md-10">
    {!! Form::label('usuarios[]', 'Usuarios') !!}
    {!! Form::select('usuarios[]', $todos, $selected, ['id' => 'usuarios', 'class' => 'form-control chosen', 'required' => 'required', 'multiple']) !!}
    <small class="text-danger">{{ $errors->first('usuarios[]') }}</small>
</div>

<a href="#!" class="select btn btn-default">Todos</a>
<a href="#!" class="deselect btn btn-default">Ninguno</a>	
<script type="text/javascript">
$('.select').click(function(){
	$('#usuarios option').prop('selected', true);  
	$('#usuarios').trigger('chosen:updated');
});
$('.deselect').click(function(){
	$('#usuarios option').prop('selected', false);  
	$('#usuarios').trigger('chosen:updated');
});
</script>