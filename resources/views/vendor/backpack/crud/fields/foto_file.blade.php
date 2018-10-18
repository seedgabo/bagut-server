<div class="form-group{{ $errors->has('foto') ? ' has-error' : '' }} col-md-12">
    {!! Form::label('foto', 'Foto:') !!}
    {!! Form::file('foto', ['bootstrap-file form-control', "accept" => "image/*"]) !!}
    <p class="help-block"></p>
    <small class="text-danger">{{ $errors->first('foto') }}</small>
</div>