<div class="list-group-item">
	<div id="comentario-text-{{$comentario->id}}">
		@if ($comentario->has_image)
		<div class="text-center">
			<img src="{{$comentario->file()}}" style="height:100px;">
		</div>
		@endif
		{!!$comentario->texto!!}
		@if ($comentario->created_at !== $comentario->updated_at)
			 <small class="text-muted">(Editado)</small>
		@endif
	</div>
	<form id="form-comentario-{{$comentario->id}}" class="form-horizontal"
		action="{{url('ajax/editComentarioTicket/'. $comentario->id)}}"
		method="post" accept-charset="utf-8" style="display: none;">
		{{ csrf_field() }}
		<textarea name="texto" required="required" class="form-control">{!!$comentario->texto!!}
		</textarea>
		@if (config("modulos.clientes"))
<!-- 		<div class="form-group">
		    <div class="col-sm-offset-2 col-sm-9">
		        <div class="checkbox @if($errors->first('publico')) has-error @endif">
		            <label for="publico">
		                {!! Form::checkbox('publico', 'true', $comentario->publico, ['id' => 'publico']) !!} público para @choice('literales.cliente', 10)
		            </label>
		        </div>
		        <small class="text-danger">{{ $errors->first('publico') }}</small>
		    </div>
		</div> -->
		@endif
		<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>

	</form>
	<p class="text-right">
	{{$comentario->user->nombre}}
	<img src="{{$comentario->user->imagen()}}" alt="" class="img-circle" height="35px">
	@if (Auth::user()->id == $comentario->user_id)
		<btn class="btn btn-info btn-xs" onclick="toggleEditComentario({{$comentario->id}})"><i class="fa fa-edit"></i></btn>

		<a class="btn btn-danger btn-xs not-anim" href="{{url('ajax/deleteComentarioTicket/'.$comentario->id)}}" title="Borrar Comentario" onclick="return confirm('¿Esta seguro de que quiere eliminar este @choice('literales.comentario', 1)?')"><i class="fa fa-trash"></i></a>
	@endif
	<br> {{\App\Funciones::transdate($comentario->created_at)}}
	@if (isset($comentario->archivo) && $comentario->archivo != "")
		<br>
		<a  @if($comentario->encriptado == "true") onclick="verArchivo('{{$comentario->file()}}')" @else href="{{$comentario->file()}}" @endif>
			@if($comentario->encriptado == "true")<i class="fa fa-lock"></i> @endif {{$comentario->archivo}}
		</a>
	@endif
	@if($comentario->publico)<span class="label label-warning">Público para @choice('literales.cliente', 1)</span> @endif
	</p>
</div>