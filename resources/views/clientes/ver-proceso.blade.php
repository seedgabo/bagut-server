@if (Request::is('admin/*')) 
    <?php $layout = 'backpack::layout'; $admin = "admin/"; ?>
@else
    <?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif

@extends($layout)
@section('header')
<section class="content-header">
  <h1>
   Proceso de cliente: {{$cliente->full_name}}
 </h1>
 <ol class="breadcrumb">
  <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
  <li><a href="{{ url('admin/procesos') }}">Listado de Procesos</a></li>
  <li class="active">{{$cliente->full_name}}</li>
</ol>
</section>
@endsection


@section('content')
<div class="row" id="print">
  <div class="col-md-offset-1 col-md-10">
    @include('clientes.partials.ficha-cliente', ['cliente' => $cliente])
  </div>
  <div class="col-md-offset-1 col-md-10">
    @include('clientes.partials.ficha-proceso',['proceso' => $proceso])
  </div>
 <div class="row"></div>
  <div class="col-md-offset-2 col-md-4">
    @include('clientes.partials.tabla-documentos', ['documentos' => $proceso->archivos])
  </div>
<div class="col-md-4">
  <div class="box box-primary">

    {!! Form::open(['method' => 'POST', 'url' => 'admin/cargar-archivo/proceso/'. $proceso->id, 'class' => 'form-inline box-body', 'files' => true]) !!}
    <div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }} col-sm-4">
      {!! Form::label('archivo', 'Agregar un Archivo:') !!}
      {!! Form::file('archivo', ['required' => 'required']) !!}
      <small class="text-danger">{{ $errors->first('archivo') }}</small>
    </div>
    
    <div class="form-group col-sm-3 pull-right">
      {!! Form::submit("Subir", ['class' => 'btn btn-success']) !!}
    </div>    
    {!! Form::close() !!}
  </div>
</div>
</div>
<style type="text/css" media="screen">
  .hover
  {
    -webkit-transition: .1s linear;
    -moz-transition: .1s linear;
    -ms-transition: .1s linear;
    -o-transition: .1s linear;
    transition: .1s linear;
  }
  .hover:hover
  {
    box-shadow: 5px 5px 20px #D4D4D4;
  }
</style>    
@endsection


