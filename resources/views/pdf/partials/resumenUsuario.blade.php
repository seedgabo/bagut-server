<div class="well">
	<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
		<tbody>
			<tr>
				<td style="width: 25%;"><img alt="" src="{{$user->imagen()}}" style="height:100px; width:100px" /></td>
				<td style="width: 35%;">
					<h2>{{$user->nombre}}</h2>
					<p>Cargo: {{$user->cargo}}</p>
					<p>Departamento:  {{$user->departamento}}</p>
				</td>
				<td style="width:40%; text-align: right; vertical-align: top;">
					<b>Desde el mes de: {{\App\Funciones::transdate($inicio, 'F')}} </b>
				</td>
			</tr>
			<tr>
		</tbody>
	</table>
	<hr>
	<ul style="list-style: none">
		<li>
		 	<b style="text-transform: capitalize">{{trans_choice('literales.ticket',10)}}  creados : </b> <span> {{$user->creados}}</span>
	 	</li>
	 	<li>
		 	<b style="text-transform: capitalize">{{trans_choice('literales.ticket',10)}}  Asignados : </b> <span> {{$user->responsables}}</span>
	 	</li>
	 	<li>
		 	<b style="text-transform: capitalize">{{trans_choice('literales.comentario',10)}} realizados: </b> <span> {{$user->comentarios}}</span>				 		
	 	</li>
	 	<li>
		 	<b style="text-transform: capitalize">{{trans_choice('literales.ticket',10)}} Abiertos: </b> <span> {{$user->abiertos}}</span>
		 	<span>@if($user->responsables != 0) ({{$user->abiertos / $user->responsables * 100}}  %) @endif</span>			 		
	 	</li>
	 	<li>
		 	<b style="text-transform: capitalize">{{trans_choice('literales.ticket',10)}} Completados: </b> <span> {{$user->completados}}</span>
		 	<span>@if($user->responsables != 0) ({{$user->completados / $user->responsables * 100}}  %) @endif</span>			 		
	 	</li>
	 	<li>
		 	<b style="text-transform: capitalize">{{trans_choice('literales.ticket',10)}} Vencidos: </b> <span> {{$user->vencidos}}</span>
		 	<span>@if($user->responsables != 0) ({{$user->vencidos / $user->responsables * 100}}  %) @endif</span>			 		
	 	</li>
	 	<li>
		 	<b style="text-transform: capitalize"> Entradas al Sistema: </b> <span> {{$user->logins}} Logins</span>	 		
	 	</li>
	</ul>
</div>
