<html>
    <td colspan="5"><h3>{{$ticket->titulo}}</h3></td>
	<tr>
	    <td colspan="5">{{ strip_tags($ticket->contenido) }}</td>
	</tr>
	<tr>
	    @if($ticket->user) 
	    <td><b>Creador: </b></td>
	    <td> {{ $ticket->user->nombre }}</td>
	    @endif
	    
	    @if($ticket->guardian) 
	    <td><b>Responsable: </b></td>
	    <td> {{ $ticket->guardian->nombre }}</td>
	    @endif

	    <td><b>Vencimiento: </b></td>
	    <td> {{ (new Date($ticket->vencimiento))->format("l j  F Y h:i:s A")}} </td>
	</tr>

	<tr>
		<td><h2>@choice('literales.comentario', 10)</h2></td>
	</tr>
	
	@forelse ($ticket->comentarios as $com)
		<tr>
			<td colspan="2">{{ strip_tags($com->texto) }}</td>
			 @if($com->user)<td>Usuario: {{ $com->user->nombre}}</td>@endif
			 <td>{{ (new Date($com->created_at))->format("l j  F  Y h:i:s A")}}</td>
		</tr>
	@empty
	@endforelse


</html>