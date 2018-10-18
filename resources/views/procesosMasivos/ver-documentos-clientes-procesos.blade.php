@if (Request::is('admin/*')) 
<?php $layout = 'backpack::layout'; $admin = "admin/"; ?>
@else
<?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif

@extends($layout)

@section('header')
<section class="content-header">
  <h1>
   Documentos del Cliente : {{$cliente->full_name_cedula}}
 </h1>
 <ol class="breadcrumb">
  <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
  <li><a href="{{ url('admin/clientes') }}">Listado de Clientes </a></li>
  <li class="active">{{$cliente->full_name}}</li>
</ol>
</section>
@endsection


@section('content')
<div class="row">
  <div class="box box-primary col-md-12 hover">
    <div class="box-header">
      <h3 class="box-title text-center">{{ $cliente->full_name_cedula }}</h3>
      <div class="box-tools">
        @if (Auth::user()->can('Agregar Archivos')||Auth::user()->hasRole('SuperAdmin'))
        <a class="btn btn-primary" href="{{url('admin/proceso-masivo/archivos-masivos?cliente_id[]='. $cliente->id)}}">
          <i class="fa fa-upload"></i> Subir Documentos
        </a>
        @endif
      </div>
    </div>
    <div class="box-body">
      @forelse($archivosporProceso as $proceso_masivo_id => $grupo)
      @php $procesoMasivo = \App\Models\ProcesoMasivo::find($proceso_masivo_id);  @endphp
      @if(isset($procesoMasivo))
      <h4 class="header"><a href="{{url($admin .'ver-procesoMasivo/'. $procesoMasivo->id)}}">{{ $procesoMasivo->titulo or 'Ninguno' }}</a></h4>
      @else
      <h4>Sin Proceso asociado</h4>
      @endif
      <ul class="list-group">
        @forelse ($grupo as $archivo)
        <li class="list-group-item" @if(isset($procesoMasivo)) id="{{ $procesoMasivo->id }}" @endif>
          {{ $archivo->nombre }}
          <span class="pull-right">
            <span class="label label-default">Pag: {{$archivo->paginas}}</span>
            <a href="{{$archivo->url}}" class="btn btn-xs btn-default"><i class="fa fa-download"></i> Descargar</a>
            @if(Auth::user()->can('Eliminar Archivos') || Auth::user()->hasRole('SuperAdmin'))
            <a 
            href="{{url('ajax/eliminar-archivo/'. $archivo->id)}}" 
            class="btn btn-xs btn-danger" 
            onclick="return confirm('¿Esta seguro de que quire eliminar este @choice('literales.archivo', 1)?')">
            <i class="fa fa-trash"></i> Eliminar
          </a>
          @endif
          <span class="text-primary">Subido el {{$archivo->created_at->format('d/m/Y')}}</span>
        </span>
      </li>
      @empty
      No hay archivos para este proceso
      @endforelse
    </ul>
    @empty
    No hay archivos para este cliente
    @endforelse
  </div>
</div>

</div>

@endsection
@section('after_scripts')
<script>
  function deleteArchivo(id) {
    if (confirm('¿esta seguro de que desea eliminar este @choice('literales.archivo', 1)?'))
      $.ajax({
        url: "{{url('ajax/eliminar-archivo/')}}" + "/" + id,
        type: 'DELETE',
      })
    .done(function(data) {
      console.log("success");
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
  }  
</script>
@stop


