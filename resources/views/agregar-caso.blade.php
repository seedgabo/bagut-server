@extends('layouts.app')
@php 
	if(Input::has('contenido'))
		$titulo =	Input::get('titulo');
	else
	$titulo = isset($titulo) ? $titulo : '';

	if(Input::has('contenido'))
		$contenido =	Input::get('contenido');
	else
		$contenido = isset($contenido) ? $contenido : '';

	if(Input::has('contenido_array'))
		$contenido = view('helpers.table-contenido-ticket',['array' => Input::get('contenido_array')]);
@endphp
@section('content')
	{!! Form::open(['method' => 'POST', 'route' => 'tickets.store', 'class' => 'form-horizontal col-md-10 col-md-offset-1' ,'id' => 'nuevoTicket', 'files'=>true]) !!}

	<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
	<input type="hidden" name="estado" value="abierto">

	{{-- Titulo --}}
	<div class="form-group @if($errors->first('titulo')) has-error @endif">
		{!! Form::label('titulo', 'Titulo') !!}
		{!! Form::text('titulo', $titulo , ['class' => 'form-control', 'required' => 'required']) !!}
		<small class="text-danger">{{ $errors->first('titulo') }}</small>
	</div>
	
	{{-- Contenido --}}
	<div class="form-group @if($errors->first('contenido')) has-error @endif">
		{!! Form::label('contenido', 'Contenido') !!}
		{!! Form::textarea('contenido', $contenido, ['class' => 'form-control ckeditor', 'required' => 'required', 'id' =>'textarea']) !!}
		<small class="text-danger">{{ $errors->first('contenido') }}</small>
	</div>
		
	{{-- Categoria --}}
	<div class="form-group @if($errors->first('categoria_id')) has-error @endif">
		{!! Form::label('categoria_id', 'Categoria') !!}
		{!! Form::select('categoria_id',\App\Models\CategoriasTickets::all()->pluck("full_name","id"), Input::get('categoria_id'), ['id' => 'categoria', 'class' => 'form-control chosen', 'required' => 'required']) !!}
		<small class="text-danger">{{ $errors->first('categoria_id') }}</small>
	</div>

	{{-- Guardian --}}
	<div class="form-group @if($errors->first('guardian_id')) has-error @endif">
		{!! Form::label('guardian_id', 'Asignar a: (Responsable)') !!}
		{!! Form::select('guardian_id',\App\User::pluck("nombre","id"), Input::get('guardian_id'), ['id' => 'guardian_id', 'class' => 'form-control chosen depdrop', 'required' => 'required']) !!}
		<small class="text-danger">{{ $errors->first('guardian_id') }}</small>
	</div>

	<script>
		$("#guardian_id").depdrop({
			depends: ['categoria'],
			url: '{{url('ajax/getUsersbyCategoria')}}',
			placeholder: false
		});
	</script>

	{{-- Vencimiento --}}
	<div class="form-group @if($errors->first('vencimiento')) has-error @endif">
		{!! Form::label('vencimiento', 'Fecha de Expiración') !!}
		{!! Form::text('vencimiento', Input::get('vencimiento'), ['class' => 'form-control datetimepicker']) !!}
		<small class="text-danger">{{ $errors->first('vencimiento') }}</small>
		<a href="#!" onclick="$('.datetimepicker').val('')">No vence</a>
	</div>
	@if(config('modulos.clientes'))
		@if(config('modulos.procesos'))
			<form-ticket-abogados 
				:clientes=' {!! \App\Models\Cliente::all()->toJson() !!} ' 
				@if(Input::has('cliente_id'))  :cliente_selected = "{{ Input::get('cliente_id') }}" @endif 
				@if(Input::has('tipo_selected'))  :tipo_selected = "'{{ Input::get('tipo_selected')}}'" @endif 
			></form-ticket-abogados>
		@else
		<div class="form-group @if($errors->first('cliente_id')) has-error @endif">
			{!! Form::label('cliente_id', 'Cliente:') !!}
			{!! Form::select('cliente_id',[null => '---'] + \App\Models\Cliente::all()->pluck('full_name','id')->toArray() , 
			Input::get('cliente_id') ,['id' => 'cliente_id', 'class' => 'form-control chosen']) !!}
			<small class="text-danger">{{ $errors->first('cliente_id') }}</small>
		</div>
		@endif
		
	@endif
	
	<a href="#!" class="toggleOptions btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
	<div id="masOpciones" style="display: none">

		{{-- Transerible --}}
		<div class="form-group @if($errors->first('transferible')) has-error @endif">
			{!! Form::label('transferible', '¿Este caso es transferible?', ['class' => 'col-sm-3 control-label']) !!}
			<div class="col-sm-9">
				{!! Form::select('transferible',[1=>"Si",0=> "No"], 1, ['id' => 'transferible', 'class' => 'form-control']) !!}
				<small class="text-danger">{{ $errors->first('transferible') }}</small>
			</div>
		</div>


				
		{{-- canSetVencimiento --}}
		<div class="form-group{{ $errors->has('canSetVencimiento') ? ' has-error' : '' }}">
		    {!! Form::label('canSetVencimiento', 'El Responsable puede cambiar la  fecha de vencimiento?') !!}
		    {!! Form::select('canSetVencimiento',['0' => 'No', '1' => 'Si'], '1', ['id' => 'canSetVencimiento', 'class' => 'form-control', 'required' => 'required']) !!}
		    <small class="text-danger">{{ $errors->first('canSetVencimiento') }}</small>
		</div>

			{{-- canSetGuardian --}}
		<div class="form-group{{ $errors->has('canSetGuardian') ? ' has-error' : '' }}">
		    {!! Form::label('canSetGuardian', '¿El Responsable puede asignar este caso a otra persona?') !!}
		    {!! Form::select('canSetGuardian',['0' => 'No', '1' => 'Si'], '1', ['id' => 'canSetGuardian', 'class' => 'form-control', 'required' => 'required']) !!}
		    <small class="text-danger">{{ $errors->first('canSetGuardian') }}</small>
		</div>

		{{-- canSetEstado --}}
		<div class="form-group{{ $errors->has('canSetEstado') ? ' has-error' : '' }}">
		    {!! Form::label('canSetEstado', '¿EL Responsable puede cambiar el estado del caso?') !!}
		    {!! Form::select('canSetEstado',['0' => 'No', '1' => 'Si'], '1', ['id' => 'canSetEstado', 'class' => 'form-control', 'required' => 'required']) !!}
		    <small class="text-danger">{{ $errors->first('canSetEstado') }}</small>
		</div>

		{{-- Archivo --}}
		<div class="form-group @if($errors->first('archivo')) has-error @endif">
			{!! Form::label('archivo', 'Archivo') !!}
			{!! Form::file('archivo', ["class" => "file-bootstrap"]) !!}
			<p class="help-block">El archivo debe pesar menos de 10Mb, solo documentos, imagenes y archivos comprimidos estan permitidos</p>
			<small class="text-danger">{{ $errors->first('archivo') }}</small>
		</div>

		{{-- Encriptado --}}
		<div class="form-group">
			<div class="checkbox{{ $errors->has('encriptado') ? ' has-error' : '' }}">
				<label for="encriptado">
					{!! Form::checkbox('encriptado','true', false, ['id' => 'encriptado']) !!} Encriptar Archivo
				</label>
			</div>
			<small class="text-danger">{{ $errors->first('encriptado') }}</small>
		</div>
		
		{{-- Clave de Encriptación --}}
		<div class="form-group{{ $errors->has('clave') ? ' has-error' : '' }}">
			{!! Form::label('clave', 'Clave de Encriptación', ['class' => 'col-sm-3 control-label']) !!}
			<div class="col-sm-9">
				{!! Form::password('clave', null, ['class' => 'form-control']) !!}
				<small class="text-danger">{{ $errors->first('clave') }}</small>
			</div>
		</div>
	</div>
	<button class="btn btn-success btn-lg pull-right" form="nuevoTicket"> Agregar @choice('literales.ticket', 1) <i class="fa fa-send"></i></button>

	<div style="height: 200px;">
		
	</div>
	@if (Input::has('hidden'))
		@foreach (Input::get('hidden') as $key => $value)
			<input type="hidden" name="{{$key}}" value="{{$value}}">
		@endforeach
	@endif
	@if (Input::has('clientes_id'))
		<input type="hidden" name="clientes_id" value="{{Input::get('clientes_id')}}">
	@endif
	{!! Form::close() !!}

	<script>
		$(document).ready(function() {
			$('.toggleOptions').click(function(){
				$('#masOpciones').toggle('fast');
				if($('.toggleOptions').html() == '<i class="fa fa-plus"></i>')
					$('.toggleOptions').html('<i class="fa fa-minus"></i>')
				else
					$('.toggleOptions').html('<i class="fa fa-plus"></i>')
			});

			@if (Input::has('maximize'))
			 	CKEDITOR.on('instanceReady',
			 	      function( evt )
			 	      {
			 	         var editor = evt.editor;
			 	         editor.execCommand('maximize');
			 	      });
			@endif
		});
	</script>
@stop