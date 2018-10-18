
<div class="col-md-12">
	
	<div class="form-group{{ $errors->has('paciente_id') ? ' has-error' : '' }}">
	    {!! Form::label('paciente_id', 'Paciente:', ['class' => 'col-sm-3 control-label']) !!}
	    <div class="col-sm-9">
	    	{!! Form::select('paciente_id',\App\Models\Paciente::lists('full_name','id') , null, ['id' => 'paciente_id', 'class' => 'form-control', 'required' => 'required', 'multiple']) !!}
	    	<small class="text-danger">{{ $errors->first('paciente_id') }}</small>
		</div>
	</div>




	<legend>Examen Físico</legend>
	
	<div class="form-group{{ $errors->has('patologicos') ? ' has-error' : '' }} col-md-8">
	    {!! Form::label('patologicos', 'patologicos') !!}
	    {!! Form::text('patologicos',null, ['class' => 'form-control', 'required' => 'required']) !!}
	    <small class="text-danger">{{ $errors->first('patologicos') }}</small>
	</div>


</div>