@extends('layouts.app')
@section('content')
<div class="">
<div class="text-center">
	<ol class="breadcrumb">
		<li>
			<a href="{{url('ticket')}}">Casos Abiertos</a>
		</li>

		<li>
			<a href="{{url('mis-tickets')}}">mis Casos</a>
		</li>

		<li> <a href="{{url('tickets/todos')}}">Todos los Casos </a></li>

		<li class="active">{{$ticket->titulo}}</li>
	</ol>
</div>

	{{-- Ticket --}}
	<div class="col-md-12">
		<div class="box box-primary box-solid hover">
		   	<div style="text-transform: uppercase;" class="box-header  text-center">
			   <span class="pull-left">
			   		<a href="{{url('ticket/excel/' . $ticket->id)}}" class="btn btn-default btn-xs"> Exportar a Excel <i class="fa fa-file-excel-o"></i></a>
			   		@if (Auth::user()->can('Editar Casos'))
			   			<a href="{{url('admin/tickets/' . $ticket->id . '/edit')}}" class="btn btn-default btn-xs"> Editar como Administrador<i class="fa fa-file-excel-o"></i></a>
			   		@endif
			   </span>

		   		<p class="">{{$ticket->titulo}}
		    	@if(isset($ticket->categoria))<span class="label  label-warning pull-right">{!! $ticket->categoria->nombre !!}</span>@endif
				 @if(Auth::user()->id == $ticket->guardian_id || Auth::user()->id == $ticket->user_id)
					<a data-toggle="modal" href='#modal-editar' class="btn btn-default btn-xs pull-right">
						<i class="fa fa-edit"></i> Editar
					</a>
				@endif
		   		</p>
	   		</div>

			<div class="box-body text-center">
				@if ($ticket->hasImage)
					<img src="{{asset("archivos/tickets/". $ticket->id ."/". $ticket->archivo)}}" style="width:150px">
				@endif
				<div style="padding-right: 40px; padding-left: 40px;"> {!! $ticket->contenido !!}</div>

				@if ($ticket->archivo!= '' || $ticket->archivo != null)
					<h4 class="text-right">
					<a  @if($ticket->encriptado == "true") onclick="verArchivo('{{$ticket->archivo()}}')" @else href="{{$ticket->archivo()}}" @endif>
						@if($ticket->encriptado == "true")<i class="fa fa-lock"></i> @endif {{$ticket->archivo}}
					</a></h4>
				@endif

			</div>

			<div class="box-footer">
				<div class="container-fluid">
					@if (Auth::user()->id == $ticket->user_id || (Auth::user()->id == $ticket->guardian_id && $ticket->canSetEstado == 1 ))
					<div class="col-md-3 form-inline">
						{!! Form::label('estado', 'Estado:') !!}
		    			{!! Form::select('estado', ['abierto' => 'abierto', 'completado' => 'completado', 'en curso' => 'en curso', ' rechazado' => ' rechazado'], $ticket->estado, ['id'=> 'estado','class' => 'form-control chosen', 'onChange' => "cambiarEstado($ticket->id , this.value)"]) !!}
					</div>
					@endif
					@if ($ticket->transferible == 1  && (Auth::user()->id == $ticket->user_id  || ( $ticket->canSetGuardian == 1   && Auth::user()->id == $ticket->guaridan_id)))
					<div class="col-md-4 form-inline">
						{!! Form::label('guardian', 'Responsable:') !!}
						@if($ticket->categoria)
		    			{!! Form::select('guardian',$ticket->categoria->users()->pluck("nombre","id"), $ticket->guardian_id, ['id'=> 'estado','class' => 'form-control chosen', 'onChange' => "cambiarGuardian($ticket->id , this.value)"]) !!}
		    			@else
		    				<span style="color:red">Asigne este @choice("literales.ticket",1) a una categoría</span>
		    			@endif
					</div>
					@endif
					@if(Auth::user()->id == $ticket->user_id ||($ticket->canSetVencimiento == 1 && Auth::user()->id  == $ticket->guardian_id))
					<div class="col-md-3 form-inline row">
						{!! Form::label('vencimiento', 'Vencimiento:') !!}
						{!! Form::text('vencimiento', $ticket->vencimiento,['id'=> 'vencimiento','class' => 'form-control pre datetimepicker', 'onblur' => "cambiarVencimiento($ticket->id , this.value)"]) !!}
						<button type="button" class="btn">Cambiar</button>
					</div>
					@endif
					<p class="text-right"><span class="text-info">Creado por:</span> {{ $ticket->user->nombre or 'No establecido' }}</p>
					<p class="text-right"><span class="text-info">Asignado a:</span> {{ $ticket->guardian->nombre  or 'No establecido'}}</p>
					<small style="color:red">Vence el: {{isset($ticket->vencimiento) ? \App\Funciones::transdate($ticket->vencimiento) : "No Vence"}}</small> <br>
				</div>

				@if (config('modulos.clientes') )
				<div class="pull-right">
					@if ($ticket->cliente)
						<a class="btn btn-default" href="{{url('ver-cliente/' .$ticket->cliente->id)}}">Ver @choice('literales.cliente', 1) Asociado</a>
					@endif
				</div>
				@endif
			</div>
		</div>
	</div>

	{{-- Seguimiento --}}
	<div class="col-md-12 box box-primary box-solid hover">
		<div class="box-header">
			<h2 class="text-center"> @choice('literales.comentario', 10)</h2>
		</div>
		<div class="box-body">
			<div class="list-group" style="overflow-y: scroll; max-height: 450px;">
				@each('partials.comentario', $comentarios, 'comentario')
			</div>

			{!! Form::open(['method' => 'POST', 'url' => 'ajax/addComentarioTicket', 'class' => 'form-horizontal form-comentario', 'id' => 'form-comentario', "files" => "true"]) !!}
				<input type="hidden" name="comentario[ticket_id]" value="{{$ticket->id}}">
				<input type="hidden" name="comentario[user_id]" value="{{Auth::user()->id}}">
				<textarea rows="3" required="required" minlength="8" class="form-control" name="comentario[texto]" placeholder="agrega aqui el seguimiento"></textarea>

				<button type="button" onclick="masOpciones();" class="btn btn-xs btn-info"><i class="fa fa-toggle-on"></i> Mas Opciones</button>
				<br>
				<div id="input-avanced" style="display:none">


					@if (config("modulos.clientes"))
<!-- 					<div class="form-group">
					    <div class="col-sm-offset-2 col-sm-9">
					        <div class="checkbox @if($errors->first('publico')) has-error @endif">
					            <label for="publico">
					                {!! Form::checkbox('publico', 'true', false, ['id' => 'publico']) !!} Hacer público para los @choice('literales.cliente', 10)
					            </label>
					        </div>
					        <small class="text-danger">{{ $errors->first('publico') }}</small>
					    </div>
					</div>	 -->
					@endif


					<div class="form-group">
					    <div class="col-sm-offset-2 col-sm-9">
					        <div class="checkbox @if($errors->first('notificacion')) has-error @endif">
					            <label for="notificacion">
					                {!! Form::checkbox('notificacion', 'true', true, ['id' => 'notificacion']) !!} Enviar Correo
					            </label>
					        </div>
					        <small class="text-danger">{{ $errors->first('notificacion') }}</small>
					    </div>
					</div>

					<div class="form-group @if($errors->first('emails[]')) has-error @endif">
					    {!! Form::label('emails[]', 'Enviar a', ['class' => 'col-sm-4 control-label']) !!}
					    <div class="col-sm-8">
					    	{!! Form::select('emails[]', $ticket->participantes()->pluck('nombre','id')->toArray() ,[$ticket->user_id,$ticket->guardian_id], ['id' => 'emails[]', 'class' => 'form-control chosen', 'required' => 'required', 'multiple']) !!}
					    	<small class="text-danger">{{ $errors->first('emails[]') }}</small>
						</div>
					</div>

					<div class="form-group @if($errors->first('archivo')) has-error @endif">
					    <div class="col-sm-12">
						    {!! Form::file('archivo',["class"=>"file-bootstrap", "accept" =>".xlsx,.xls,image/*,.doc, .docx.,.ppt, .pptx,.txt,.pdf,.zip,.rar"]) !!}
						    <p class="help-block">Solo imagenes, menores a 10Mb</p>
						    <small class="text-danger">{{ $errors->first('archivo') }}</small>
					    </div>
					</div>

					<div class="form-group ">
					    <div class="checkbox{{ $errors->has('encriptado') ? ' has-error' : '' }} col-sm-offset-2">
					        <label for="encriptado">
					            {!! Form::checkbox('encriptado','true',false, ['id' => 'encriptado']) !!} <b> Encriptar Archivo </b>
					        </label>
					    </div>
					    <small class="text-danger">{{ $errors->first('encriptado') }}</small>
					</div>

					<div class="form-group{{ $errors->has('clave') ? ' has-error' : '' }}">
					    {!! Form::label('clave', 'Clave de Encriptacion', ['class' => 'col-sm-3 control-label']) !!}
						<div class="col-sm-9">
					    	{!! Form::password('clave', null, ['class' => 'form-control']) !!}
					    	<small class="text-danger">{{ $errors->first('clave') }}</small>
						</div>
					</div>
				</div>
				<br>
				<div class="text-right">
				<button type="submit" class="btn btn-block btn-primary">Enviar <i class="fa fa-send"></i></button>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
 	@php
 		if($ticket->categoria)
 			$invitables = \App\User::whereNotIn("id", $ticket->categoria->users()->pluck("id")->toArray())->pluck("nombre","id")->toArray();
 		else
 			$invitables =\App\User::all()->pluck("nombre","id")->toArray();
 	@endphp
	{{-- Invitados --}}
	<h2 class="text-primary text-center">{!! Form::label('invitados_id[]', 'Invitados a este caso') !!} </h2>
	@if (Auth::user()->id == $ticket->user_id)
		{!! Form::open(['method' => 'POST', 'url' => url('ajax/setInvitadosTickets/'. $ticket->id), 'class' => 'form-horizontal well hover']) !!}
		    {!! Form::select('invitados_id[]',
		    	$invitables,
		    	$ticket->invitados_id,
		    	['id' => 'invitados_id[]', 'class' => 'form-control chosen', 'required' => 'required', 'multiple'])
		    !!}
		    <small class="text-danger">{{ $errors->first('invitados_id[]') }}</small>
			<button type="submit" class="btn btn-block btn-primary">Invitar <i class="fa fa-share"></i></button>
			<br>
		{!! Form::close() !!}
	@else
	  	<p>{{ $ticket->invitados()->pluck("nombre")->implode(", ")}}</p>
	@endif


 	<div class="modal fade" id="modal-editar">
		 <div class="modal-dialog modal-lg">
			 <div class="modal-content">
				 <div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					 <h4 class="modal-title">Editar Contenido del Ticket</h4>
				 </div>
				 <div class="modal-body">
				 <div class="row">
					{!! Form::model($ticket, ['url' => url('editar-ticket/' . $ticket->id), 'method' => 'PUT', 'class' => 'form-horizontal col-md-10 col-md-offset-1', 'id' => 'editar-ticket']) !!}
						<div class="form-group{{ $errors->has('titulo') ? ' has-error' : '' }}">
						    {!! Form::label('titulo', 'Titulo') !!}
						    {!! Form::text('titulo', null, ['class' => 'form-control', 'required' => 'required']) !!}
						    <small class="text-danger">{{ $errors->first('titulo') }}</small>
						</div>
					    <div class="form-group{{ $errors->has('contenido') ? ' has-error' : '' }}">
					        {!! Form::label('contenido', 'Contenido') !!}
					        {!! Form::textarea('contenido', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'textarea']) !!}
					        <small class="text-danger">{{ $errors->first('contenido') }}</small>
					    </div>


					{!! Form::close() !!}
				 </div>
				 </div>
				 <div class="modal-footer">
					 <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					 {!! Form::submit("Agregar", ['class' => 'btn btn-success' , 'form' => 'editar-ticket']) !!}
				 </div>
			 </div>
		 </div>
	</div>


	<script>
		function cambiarEstado(id, estado)
		{
			$.post("{{url('ajax/setEstadoTicket/')}}" +"/" + id, {estado: estado},
			function(data)
			{
				$.toast({
            		heading: 'Hecho',
            		text: "Estado Actualizado",
            		showHideTransition: 'slide',
            		icon: 'success',
            		position: 'mid-center',
            	})
				$("#estado").val(data);
				location.reload(true);
			})
		}

		function cambiarVencimiento (id, vencimiento)
		{
			if(vencimiento != "{{$ticket->vencimiento}}")
				$.post("{{url('ajax/setVencimiento/')}}" +"/" + id, {vencimiento: vencimiento},
					function(data)
					{
						$.toast({
							heading: 'Hecho',
							text: "Vencimiento Actualizado",
							showHideTransition: 'slide',
							icon: 'success',
							position: 'mid-center',
						})
						location.reload(true);
					})
		}

		function cambiarGuardian(id, guardian_id)
		{
			$.post("{{url('ajax/setGuardianTicket/')}}" +"/" + id, {guardian_id: guardian_id},
				function(data)
				{
					$.toast({
	            		heading: 'Hecho',
	            		text: "Guardian Transferido",
	            		showHideTransition: 'slide',
	            		icon: 'success',
	            		position: 'mid-center',
    			});
					location.reload(true);
    		})
		}

		function masOpciones()
		{
			$('#input-avanced').fadeToggle('fast');
			$('.chosen').select2('destroy').select2();
		}

		function verArchivo(url)
		{
			var promptDialog = new ax5.ui.dialog();
            promptDialog.prompt({
	                input: {
	                    clave: {label:"clave", type:"password", placeholder: "Clave del archivo encriptado"}
	                }
	            }, function(){
					var clave =  this.clave;
					if (clave)
						window.location = url + "/" + clave;
					else
						alert("debe ingresar una clave valida");
	            });
		}

		function toggleEditComentario(id){
			$("#form-comentario-" + id).toggle();
			$("#comentario-text-"+ id).toggle();
		}

		$(document).ready(function() {
		    $(".file-bootstrap").fileinput({
		        maxFileSize: 10000,
				showUpload: false,
		        browseClass: "btn btn-default",
		        browseLabel: "Cargar Archivo",
		        browseIcon: "<i class=\"glyphicon glyphicon-upload\"></i> ",
				previewFileType: "image",
		        browseClass: "btn btn-default",
		        browseLabel: "Cargar Archivo",
		        browseIcon: "<i class=\"glyphicon glyphicon-files\"></i> ",
		        removeClass: "btn btn-danger",
		        removeLabel: "",
		        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
		        uploadClass: "btn btn-info",
			});
			$('.chosen').select2({
			  placeholder: 'Selecione una opción',
			});
		});
	</script>
</div>
@stop
