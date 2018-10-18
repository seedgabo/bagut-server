<div class="table-responsive">
@if (Input::has('contenido_titulo'))
	<h2 class="text-center">{{ Input::get('contenido_titulo') }}</h2>
@endif
@if (Input::has('contenido_subtitulo'))
	<h4 class="text-center">{{ Input::get('contenido_titulo') }}</h4>
@endif

@if (Input::has('contenido_link'))
	<a class="btn btn-link" target="ticket_link" href="{{Input::get('contenido_link')}}"><i class="fa fa-external-link fa-2x"></i> VER</a>
@endif

<table class="table table-bordered table-condensed table-hover table-striped" style="width:80%; margin-left: 10%; border: 1px solid #BDBDBD;">
	<caption>
	<p style="text-align:center">{{ Input::get('contenido_titulo','') }}</p>
	</caption>
	<tbody>
		@foreach ($array as $key => $element)
		@if(!(is_array($element)) && $key !== "id"  && strpos($key,"_id") === false && $element !== false)
			<tr>
				<th>{{$key}}</th>
				<td>{{$element}}</td>
			</tr>
		@endif
		@endforeach
	</tbody>
</table>