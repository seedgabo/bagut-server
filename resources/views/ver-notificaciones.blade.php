@extends('layouts.app')
@section('content')

<div class="col-md-6 col-md-offset-3">
	<a class="btn btn-warning pull-left" href="{{url('ajax/notificaciones/read-all')}}"> Leer Todas</i></a>
	<a class="btn btn-primary pull-right" data-toggle="modal" href='#modal-alert'> <i class="fa fa-bell"></i> Agregar Una Alerta</a>
	<br>
	<h3>Notificaciones</h3>
	<ul class="list-group">
		@forelse ($notificaciones as $not)
            	<a class="list-group-item 
            	   @if(!isset($not->read_at)) list-group-item-success @endif"
            	   href="{{ url('notificacion/' . $not->id) }}">

			        <h4 class="list-group-item-heading">{{ $not->data ? $not->data['titulo']: '' }}</h4>
				    <p class="list-group-item-text">
				    	{{$not->data ? $not->data['texto'] : ''}}
	                	<span class="pull-right text-muted">
	                   		<em>{{\App\Funciones::transdate($not->created_at, 'd-m-y h:m:s')}}</em>
	                	</span>
				    </p>
			  	</a>
		@empty
		@endforelse
	</ul>
</div>

<div class="modal fade" id="modal-alert">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Agregar Una Alerta</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					{!! Form::open(['method' => 'POST', 'url' => 'agregar-alerta', 'class' => 'form-horizontal', 'id' => 'alerta' ,'name' => 'alerta']) !!}
					
					    <div class="form-group{{ $errors->has('titulo') ? ' has-error' : '' }}">
					        {!! Form::label('titulo', 'Titulo', ['class' => 'col-sm-3 control-label']) !!}
					    	<div class="col-sm-8">
					        	{!! Form::text('titulo', null, ['class' => 'form-control', 'required' => 'required']) !!}
					        	<small class="text-danger">{{ $errors->first('titulo') }}</small>
					    	</div>
					    </div>

					    <div class="form-group{{ $errors->has('mensaje') ? ' has-error' : '' }}">
					        {!! Form::label('mensaje', 'Mensaje:', ['class' => 'col-sm-3 control-label']) !!}
					    	<div class="col-sm-8">
					        	{!! Form::text('mensaje', null, ['class' => 'form-control', 'required' => 'required']) !!}
					        	<small class="text-danger">{{ $errors->first('mensaje') }}</small>
					    	</div>
					    </div>

					    <div class="form-group{{ $errors->has('correo') ? ' has-error' : '' }}">
					        {!! Form::label('correo', 'Cuerpo del Correo', ['class' => 'col-sm-3 control-label']) !!}
					        <div class="col-sm-8">
					        	{!! Form::textarea('correo', null, ['class' => 'form-control', 'required' => 'required']) !!}
					        	<small class="text-danger">{{ $errors->first('correo') }}</small>
					    	</div>
					    </div>

					    <div class="form-group{{ $errors->has('programado') ? ' has-error' : '' }}">
					        {!! Form::label('programado', 'Fecha de Entrega:', ['class' => 'col-sm-3 control-label']) !!}
					    	<div class="col-sm-8">
					        	{!! Form::text('programado', null, ['class' => 'form-control datetimepicker', 'required' => 'required']) !!}
					        	<small class="text-danger">{{ $errors->first('programado') }}</small>
					    	</div>
					    </div>
					    <div class="form-group">
					        <div class="col-sm-offset-3 col-sm-8">
					            <div class="checkbox{{ $errors->has('inmediato') ? ' has-error' : '' }}">
					                <label for="inmediato">
					                    {!! Form::checkbox('inmediato', 'true', null, ['id' => 'inmediato']) !!} ¿Entregar Inmidiatamente?
					                </label>
					            </div>
					            <small class="text-danger">{{ $errors->first('inmediato') }}</small>
					        </div>
					    </div>
					
					
					{!! Form::close() !!}
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="submit"  form="alerta" class="btn btn-primary">Programar Alerta</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		if({{Request::exists('add-alerta')}})
		{
			$('#modal-alert').modal('show');
		}
	})
</script>
@stop
