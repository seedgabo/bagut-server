<h3 class="text-center text-primary">Ticket General en Proceso: {{$proceso->titulo}}</h3>
<h6><a class="btn btn-link btn-lg" href="{{url('ver-procesoMasivo/'. $proceso->id)}}" title="">Ver proceso</a></h6>
<ul class="list-group">
 @foreach ($clientes as $cliente)
	<li class="list-group-item">
		<a  class="btn btn-link btn-sm" href="{{url('ver-cliente/'.$cliente->id)}}">{{$cliente->full_name}}</a>
	</li>
 @endforeach
</ul>