<?php $selected = isset($entry) ? $entry->invitados_id : []; ?>
<?php $participantes = isset($entry) ? $entry->participantes()->pluck("nombre","id")->toArray() : []; ?>

<div class="form-group{{ $errors->has('invitados_id[]') ? ' has-error' : '' }} col-md-10">
    {!! Form::label('invitados_id[]', 'Invitados') !!}
    {!! Form::select('invitados_id[]', $participantes, $selected, ['id' => 'invitados_id[]', 'class' => 'form-control chosen', 'multiple']) !!}
    <small class="text-danger">{{ $errors->first('invitados_id[]') }}</small>
</div>