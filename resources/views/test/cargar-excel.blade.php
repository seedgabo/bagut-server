@extends('layouts.app')

@section('content')

{{-- Cargar test2 --}}
{!! Form::open(['method' => 'POST', 'url' => url('test5'), 'class' => 'form-horizontal', 'files' => true]) !!}

		<div class="form-group{{ $errors->has('proceso_masivo_id') ? ' has-error' : '' }}">
		    {!! Form::label('proceso_masivo_id', 'Proceso') !!}
		    {!! Form::select('proceso_masivo_id',\App\Models\ProcesoMasivo::all()->pluck("titulo","id")->toArray(), null, ['id' => 'proceso_masivo_id', 'class' => 'form-control', 'required' => 'required']) !!}
		    <small class="text-danger">{{ $errors->first('proceso_masivo_id') }}</small>
		</div>
		<div class="form-group{{ $errors->has('entidad_id') ? ' has-error' : '' }}">
		    {!! Form::label('entidad_id', 'Entidad') !!}
		    {!! Form::select('entidad_id',\App\Models\Entidad::all()->pluck("name","id")->toArray(), null, ['id' => 'entidad_id', 'class' => 'form-control', 'required' => 'required']) !!}
		    <small class="text-danger">{{ $errors->first('entidad_id') }}</small>
		</div>

		<!-- <div class="form-group{{ $errors->has('clientes_ids') ? ' has-error' : '' }}">
		    {!! Form::label('clientes_ids', 'Clientes') !!}
		    {!! Form::text('clientes_ids' , null, ['required' => 'required', 'class' => 'form-control']) !!}</small>
			<small class="text-info"> Cedulas separadas por comas</small>
		</div> -->
		
		{!! Form::file('archivo', ['required' => 'required']) !!}

        {!! Form::submit("Subir", ['class' => 'btn btn-success']) !!}

{!! Form::close() !!}

<script>
	$(".chosen").select2({});
</script>



@stop
