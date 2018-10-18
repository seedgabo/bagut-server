@extends('clientes.formularios.layout')
@section('content')

<div class="col-md-6 col-md-offset-3">
	<a class="btn btn-warning pull-left" href="{{url('clientes/notificaciones/read-all')}}"> Leer Todas</i></a>
	<br>
	<h3>Notificaciones</h3>
	<ul class="list-group">
		@forelse ($notificaciones as $not)
            	<a class="list-group-item 
            	   @if(!isset($not->read_at)) list-group-item-success @endif"
            	   href="{{ url('clientes/notificacion/' . $not->id) }}">

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

@stop
