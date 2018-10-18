@if (Request::is('admin/*')) 
    <?php $layout = 'backpack::layout'; $admin = "admin/"; ?>
@else
    <?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif

@extends($layout)

@section('header')
    <section class="content-header">
      <h1>
         Cliente : {{$cliente->full_name}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ url('admin/clientes') }}">Listado de clientes</a></li>
        <li class="active">{{$cliente->full_name}}</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="row">
  <div class="col-md-9">
      @include('clientes.partials.ficha-cliente', ['cliente' => $cliente])
  </div>
  <div class="col-md-3">
    @include('clientes.partials.tabla-documentos', ['documentos' => $cliente->archivos])
      

      {!! Form::open(['method' => 'POST', 'url' => 'admin/cargar-archivo/cliente/'. $cliente->id, 'class' => 'form-inline', 'files' => true]) !!}
              {!! Form::file('archivo', ['id' => 'archivo', 'required' => 'required', 'class'=> 'btn']) !!}      
              {!! Form::submit("Subir", ['class' => 'btn btn-success']) !!}
      {!! Form::close() !!}
  </div>


<div class="col-md-12 nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class=""><a href="#tickets" data-toggle="tab">@choice('literales.ticket', 10)</a></li>
      @if(config('modulos.procesos_masivos'))
	<li class="active"><a href="#procesosMasivos" data-toggle="tab">Procesos Masivos</a></li>
      @endif
      @if (config("modulos.procesos"))
          <li><a href="#procesos" data-toggle="tab">@choice('literales.proceso', 10)</a></li>
      @endif
      @if (config("modulos.consultas"))
        <li><a href="#consultas" data-toggle="tab">@choice('literales.consulta', 10)</a></li>
      @endif
      @if(config('modulos.facturas'))
        <li><a href="#facturas" data-toggle="tab">@choice('literales.invoice', 10)</a></li>
      @endif
    </ul>

    <div class="tab-content">
      <div class="tab-pane" id="tickets">
            <div class="col-md-12">
                @include('clientes.partials.tabla-tickets',['tickets' => $cliente->tickets])
            </div>
      </div>
      @if(config("modulos.procesos"))
      <div class="tab-pane" id="procesos">
          <div class="col-md-12">
              @include('clientes.partials.tabla-procesos',['procesos' => $cliente->procesos])
          </div>
      </div>
      @endif
      <!-- /.tab-pane -->
      @if(config("modulos.consultas"))
      <div class="tab-pane" id="consultas">
          <div class="col-md-12">
              @include('clientes.partials.tabla-consultas',['consultas' => $cliente->consultas])
          </div>
      </div>
       @endif
      <!-- /.tab-pane -->

      @if(config("modulos.facturas"))
      <div class="tab-pane" id="facturas">
            <div class="col-md-12">
                @include('clientes.partials.tabla-facturas',['facturas' => $cliente->facturas])
            </div>
      </div>
      @endif
      @if(config("modulos.procesos_masivos"))
      <div class="active tab-pane" id="procesosMasivos">
            <div class="col-md-12">
                @include('procesosMasivos.partials.tabla-procesosMasivos',['procesos' => $cliente->procesosMasivos, 'cliente' => $cliente])
            </div>
      </div>
      @endif
      <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
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


