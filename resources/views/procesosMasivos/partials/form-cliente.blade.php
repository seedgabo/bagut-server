<label>Formulario de Agregado Rapido Cliente a Proceso</label>
<button type="button" class="form-proceso-masivo-toggle btn btn-sm" onclick="select2sear()"> <i class="fa fa-plus"></i></button>
<div id="form-proceso-masivo" style="display: none;">

	<div class="form-group{{ $errors->has('proceso[proceso_masivo_id]') ? ' has-error' : '' }}">
	    {!! Form::label('proceso[proceso_masivo_id]', 'Proceso a asociar') !!}
	    {!! Form::select('proceso[proceso_masivo_id]',[ null  => '-' ] + \App\Models\ProcesoMasivo::all()->pluck('titulo','id')->toArray(), null, ['id' => 'proceso[proceso_masivo_id]', 'class' => 'form-control select2']) !!}
	    <small class="text-danger">{{ $errors->first('proceso[proceso_masivo_id]') }}</small>
	</div>
	<div class="form-group{{ $errors->has('proceso[entidad_id]') ? ' has-error' : '' }}">
	    {!! Form::label('proceso[entidad_id]', 'Entidad') !!}
	    {!! Form::select('proceso[entidad_id]', App\Models\Entidad::all()->pluck('name','id')->toArray(), null, ['id' => 'proceso[entidad_id]', 'class' => 'form-control select2', 'required' => 'required']) !!}
	    <small class="text-danger">{{ $errors->first('proceso[entidad_id]') }}</small>
	</div>

	<div class="form-group{{ $errors->has('proceso[fecha_agregado]') ? ' has-error' : '' }}">
	    {!! Form::label('proceso[fecha_agregado]', 'Fecha de Agregado') !!}
	    {!! Form::date('proceso[fecha_agregado]', null, ['class' => 'form-control', 'required' => 'required']) !!}
	    <small class="text-danger">{{ $errors->first('proceso[fecha_agregado]') }}</small>
	</div>


	<div class="form-group{{ $errors->has('proceso[estado]') ? ' has-error' : '' }}">
	    {!! Form::label('proceso[estado]', 'Estado') !!}
	    {!! Form::select('proceso[estado]',["abierto" => "Abierto", "en curso" => "En Curso","completado" => "Completado", "rechazado" => "Rechazado"], null, ['id' => 'proceso[estado]', 'class' => 'form-control', 'required' => 'required']) !!}
	    <small class="text-danger">{{ $errors->first('proceso[estado]') }}</small>
	</div>

	@forelse (\App\Models\ProcesosMasivosCliente::fields as $key => $data)
		@if($data['type']!= 'manual')
		<div class="form-group col-md-6">
		<label for="{{$key}}">{{$data['label'] or $key}}</label>
			@if($data['type'] == 'text')
			<input type="text" name="proceso[{{$key}}]" id="{{$key}}" class="form-control">
			@elseif($data['type'] == 'date')
				<input type="date" name="proceso[{{$key}}]" id="{{$key}}" class="form-control">
			@elseif($data['type'] == 'select')
				<select name="proceso[{{$key}}]" id="{{$key}}" class="form-control">
					@forelse($data['options'] as $value => $label)
					<option value="{{$value}}">{{$label}}</option>
					@empty
					@endforelse
				</select>
			@endif
		</div>
		@endif
	@empty
	@endforelse
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
	$(".form-proceso-masivo-toggle").click(function(){
		$("#form-proceso-masivo").fadeToggle('fast');
	});
	function select2sear(){
		window.setTimeout(function(){
			$("select.select2").select2("destroy").select2({});
		},100);
	}

</script>