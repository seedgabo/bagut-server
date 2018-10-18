<div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }}">
    {!! Form::label('archivo', 'Archivo:') !!}
    {!! Form::file('archivo', ['bootstrap-file']) !!}
    <p class="help-block"></p>
    <small class="text-danger">{{ $errors->first('archivo') }}</small>
</div>