<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>
@if (Request::is('admin/*')) 
<?php $layout = 'backpack::layout'; $admin = "admin/"; ?>
@else
<?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif

@php
  $filtrables = [];
  $ordenables = [];
 foreach(\App\Models\ProcesosMasivosCliente::fields as $key => $attr){
   if($attr['type'] != 'manual')
      if(isset($attr['label']))
        $filtrables[$key] =  $attr['label'];
      else
        $filtrables[$key] =  $key;
 }  

 foreach(\App\Models\ProcesosMasivosCliente::fields as $key => $attr){
    $ordenables[""] =  "  ";
    if(isset($attr['label']))
      $ordenables[$key] =  $attr['label'];
    else
      $ordenables[$key] =  $key;
 }  

  $appends =[
        'search' => Input::get('search'),
        'entidad_id' => Input::get('entidad_id'),
        'paginate' => Input::get('paginate',25)
        ];

    if (Input::has('query') && strlen(Input::get('query')) !== 0 ) {
      $appends['filtro'] = Input::get('filtro');
      $appends['query'] = Input::get('query');
      $appends['order'] = Input::get('order');
      $appends['order_type'] = Input::get('order_type');
    }
@endphp

@extends($layout)

@section('header')
<section class="content-header">
  <h1>
   Proceso : {{$proceso->titulo}}
 </h1>
 <ol class="breadcrumb">
  <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
  <li><a href="{{ url('admin/procesos-masivos') }}">Listado de Procesos Masivos</a></li>
  <li class="active">{{$proceso->titulo}}</li>
</ol>
</section>
@endsection


@section('content')
<link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">
<script src="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.js') }}"></script>
<div class="row">
  
  <h2 class="text-center text-primary ">{{$proceso->titulo}}</h2>
  
  {{-- Total --}}
  <span class="pull-right"><b>TOTAL:</b> {{$entradas->count() }}   de  {{   $entradas->total() }} registros</span>
  <div class="text-center">
    {!! $entradas->appends($appends)->links() !!}
  </div>
  {{-- Buscador --}}
  <div class="well">
    {!! Form::open(['method' => 'GET', 'class' => 'form-inline', 'id' => 'filtrador', 'name' => 'filtrador']) !!}
    
        <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
            {!! Form::label('search', 'Buscar por Cliente:') !!}
            {!! Form::text('search', Input::get('search',''), ['class' => 'form-control','style' => 'width:350px']) !!}
        </div>
        <div class="form-group{{ $errors->has('entidad_id[]') ? ' has-error' : '' }}">
            {!! Form::label('entidad_id[]', 'Entidad') !!}
            {!! Form::select('entidad_id[]', $entidades->pluck('name','id')->reject(function ($value, $key) {
                  return $key ==  "";
              })->all(), 
            Input::get('entidad_id'), ['id' => 'entidad_id', 'class' => 'form-control chosen', 'multiple', 'style' => 'width:350px']) !!}
        </div>

        <div class="form-group{{ $errors->has('paginate') ? ' has-error' : '' }}">
            {!! Form::label('paginate', 'Por Pagina') !!}
            {!! Form::number('paginate',Input::get('paginate',25), ['class' => 'form-control', 'required' => 'required', 'max' => 30000, 'style' => 'width:70px']) !!}
        </div>

        <div class="form-group{{ $errors->has('filtro') ? ' has-error' : '' }}">
            {!! Form::label('filtro', 'Filtrar Por:') !!}
            {!! Form::select('filtro',$filtrables, Input::get('filtro'), ['id' => 'filtro', 'class' => 'form-control chosen', 'style' => 'width:200px']) !!}
            {!! Form::text('query',Input::get('query'),['id' => 'query' , 'class' => 'form-control'] ) !!}
        </div>


        <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
            {!! Form::label('order', 'Ordenar Por:') !!}
            {!! Form::select('order',$ordenables, Input::get('order'), ['id' => 'order', 'class' => 'form-control chosen', 'style' => 'width:200px']) !!}
            {!! Form::select('order_type',['asc' => 'ascendente','desc' => 'descendente'], Input::get('order_type'), ['id' => 'order_type', 'class' => 'form-control chosen', 'style' => 'width:200px']) !!}
        </div>
    
        <div class="pull-right">
             <a href="{{url($admin . "ver-procesoMasivo/". $proceso->id)}}" title=""></a>
            {!! Form::submit("Buscar", ['class' => 'btn btn-primary']) !!}
        </div>
    
    {!! Form::close() !!}
    <div class="pull-right">
        <button type="submit" class="btn btn-outline btn-success btn-sm" formaction="{{url('admin/ver-procesoMasivo/'. $proceso->id . "/excel")}}"  form="filtrador">
          Exportar a Excel <i class="fa fa-file-excel-o"></i>
        </button>
    </div>
    <br>
  </div>

  {{-- Tabla --}}
  <div class="box box-primary col-md-12">
    <div class="box-body">
      <a class="btn-link" href="{{url($admin . 'ver-documentos-procesoMasivo/'. $proceso->id)}}" title="ver Documentos"> Ver Documentos</a>
      {{-- <a class="btn btn-link" href="{{url('/admin/procesos-masivos/'. $proceso->id .'/edit')}}"><i class="fa fa-pencil"></i> Agregar @choice('literales.cliente', 10) al @choice('literales.proceso_masivo', 1)</a> --}}
      <table-proceso-masivo 
      :entradas=' {!! json_encode($entradas->toArray()['data']) !!} ' 
      :fields=' {!! collect(\App\Models\ProcesosMasivosCliente::fields)->reject(function($value, $key){
          return $value['type'] == "manual";
        })->toJson() !!} '
      :entidades='{!! json_encode(\App\Models\Entidad::all()) !!}'
      :proceso= '{!! json_encode($proceso) !!}'
      :clientes= '{!! json_encode($clientes) !!}'
      url= '{!!url('')!!}'
      >
       <div class="text-center"> <i class="fa fa-spinner fa-spin fa-3x text-primary"></i> Cargando...</div>
      </table-proceso-masivo>
    </div>
  </div>

</div>
@endsection


