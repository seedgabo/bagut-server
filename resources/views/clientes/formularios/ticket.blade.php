@extends('clientes.formularios.layout')

@section('content')

<main class="">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-8">
				<div class="box box-primary">
					<div class="box-header">
						<h2 class="box-title text-center">{{$ticket->titulo}}</h2>
					</div>
					<div class="box-body">
						<hr>
						<div class="text-center">{!! $ticket->contenido !!}</div>
						<hr>
						<div  class="pull-right">
							<img src="{{$ticket->user->imagen}}" alt="" class="img-circle" style="height: 30px">
							<span class="text-primary"> {{$ticket->user->nombre}} </span> <br/>
						</div>
						<p> {{Date::parse($ticket->created_at)->diffForHumans()}} </p>

						<p class="center-align"><a href="{{ $ticket->archivo() }}"> {{ $ticket->archivo }}</a></p>
					</div>
				</div>



				<h4 class="text-center">@choice('literales.comentario', 10)</h4>
				@forelse ($comentarios as $com)
				<div class="box box-solid" id="comentario-{{$com->id}}">
					@if (Auth::user()->id  == $com->user_id)
						<a class='dropdown-button right' href='#' data-activates='dropdown-{{$com->id}}' data-constrainwidth="false"><i class="material-icons">more_vert</i></a>
						<ul id='dropdown-{{$com->id}}' class='dropdown-content'>
						  <li><a href="#!"><i class="material-icons blue-text">edit</i></a></li>
						  <li><a href="#!" onclick="deleteComentario({{$com->id}})"><i class="material-icons red-text">delete</i></a></li>
						</ul>
					@endif
					<div class="box-body">
						<p class="text-center">{!! $com->texto !!} </p>
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<img src="{{$com->user->imagen}}" alt="" class="img-circle" style="height: 30px">
							<span class="valign"> {{$com->user->nombre}} </span> <br/>
						</div>
						<div class="pull-left">
							<p> {{Date::parse($com->created_at)->diffForHumans()}} </p>
						</div>
						
						<p class="text-center"><a href="{{$com->file()}}"> {{$com->archivo}}</a></p>
					</div>
				</div>
				@empty
				@endforelse
			</div>
			<div class="col-md-4 col-sm-12">
				<h4>Agregar @choice('literales.comentario', 1)</h4>
			  		{!! Form::open(['method' => 'POST', 'url' => 'clientes/ticket/' . $ticket->id . '/comentario', 'class'=>'well', 'files' => true]) !!}
				        <textarea id="textarea1" name="texto" class="form-control"></textarea>
						<div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }}">
						    {!! Form::label('archivo', 'Archivo') !!}
						    {!! Form::file('archivo', []) !!}
						    <small class="text-danger">{{ $errors->first('archivo') }}</small>
						</div>
				        <button type="submit" class="btn btn-info pull-right">Enviar <i class="fa fa-send"></i></button>
				        <br>
				  {!! Form::close() !!}
			</div>

		</div>
	</div>

</main>
@stop

@push('scripts')
	<script type="text/javascript">

		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});

		function deleteComentario(id){
			$.ajax({
				url: '{{url('clientes/comentario/')}}' + '/' + id,
				type: 'DELETE',
			})
			.done(function() {
				$("#comentario-" +id).hide('slow');
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		}
	</script>
@endpush