@extends('layouts.app')
@section('content')
	@if(isset($subcategorias) && sizeof($subcategorias) != 0)
		<h3 class="text-center text-primary">SubCategorias</h3>
		<div class="list-group">
			@forelse ($subcategorias as $categoria)
			<a class="list-group-item" href="{{url('tickets/categoria/'. $categoria->id)}}">
				<span class="badge">{{$categoria->tickets->count()}}</span>
				{{ $categoria->nombre }}
			</a>
			@empty
			@endforelse
		</div>
		<h3 class="text-center text-primary">Casos</h3>
	@endif
	<div class="well">
		{!! Form::open(['method' => 'GET',  'class' => 'form-inline']) !!}

		    <div class="form-group{{ $errors->has('desde') ? ' has-error' : '' }}">
		        {!! Form::label('desde', 'Desde:', ['class' => 'col-sm-3 control-label']) !!}
		        <div class="col-sm-9">
		        	{!! Form::date('desde',$desde, ['class' => 'form-control', 'required' => 'required']) !!}
		        	<small class="text-danger">{{ $errors->first('desde') }}</small>
		        </div>
		    </div>

		    <div class="form-group{{ $errors->has('hasta') ? ' has-error' : '' }}">
		        {!! Form::label('hasta', 'Hasta:') !!}
		        {!! Form::date('hasta',$hasta, ['class' => 'form-control', 'required' => 'required']) !!}
		        <small class="text-danger">{{ $errors->first('hasta') }}</small>
		    </div>

		    <div class="btn-group pull-right">
		        {!! Form::submit("Buscar", ['class' => 'btn btn-success']) !!}
		    </div>

		{!! Form::close() !!}
	</div>
	<div class="text-right">
		<a class="btn btn-primary" data-toggle="modal" href='{{url('agregar-ticket')}}'><i class="fa fa-plus"></i> Crear un Caso</a>
	</div>
	<div class="card">
		<table class="datatable table table-bordered table-striped display dt-responsive" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th>@choice('literales.ticket', 1)</th>
					<th>Estado</th>
					@if(config("modulos.clientes"))<th>Cliente</th>@endif
					<th>Categoría</th>
					<th>Ultimo  @choice('literales.comentario', 1)</th>
					<th>Usuario</th>
					<th>Asignado a</th>
					<th>Creado el</th>
					<th>Vence el</th>
				</tr>
			</thead>
			<tbody>
			 @forelse ($tickets as $ticket)
				<tr>
					<td>{{$ticket->id}}
						@if ($ticket->user_id == Auth::user()->id)
							<a class="btn btn-xs btn-outline btn-danger not-anim" onclick="return confirm('esta seguro de que desea eliminar este @choice('literales.ticket', 1)?')" href="{{url("ticket/eliminar/".$ticket->id)}}"> <i class="fa fa-trash"></i></a>
						@endif
					</td>
					<td>
						<a class="btn btn-block btn-xs" style="text-transform: uppercase;" href="{{url("ticket/ver/".$ticket->id)}}">
						{{$ticket->titulo}}
						<span class="badge">{{$ticket->comentarios->count()}}</span>
						</a>
					</td>
					
					<td><span class="{{$ticket->estado}} badge"> {{$ticket->estado}} </span></td>
					
					@if(config("modulos.clientes")) 
						<td> @if($ticket->cliente) <a href="{{url('ver-cliente/'. $ticket->cliente->id)}}"> {{$ticket->cliente->full_name}} </a> @endif </td>
					@endif
					
					@if(isset($ticket->categoria))<td>{{$ticket->categoria->nombre}}</td>@else <td class="text-muted">Sin Categoria	</td> @endif 
					
					<td>@if($ticket->comentarios()->count() > 0) {!! $ticket->comentarios()->orderby("id","desc")->take(1)->first()->texto !!} @endif</td>
					
					<td>@if($ticket->user)     {{ $ticket->user->nombre}}     @else  No hay Nadie Asignado @endif  </td>
					
					<td>@if($ticket->guardian) {{ $ticket->guardian->nombre}} @else  No hay Nadie Asignado @endif  </td>


					<td>{{ App\Funciones::transdate($ticket->created_at, 'd-m-y h:m A')}}</td>

					<td>{{ $ticket->vencimiento  ?  \App\Funciones::transdate($ticket->vencimiento, 'd-m-y h:m A') : "No Vence"}}</td>
				</tr>
			 @empty
			 	Ningún caso existente
			 @endforelse
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
	</div>
@stop
