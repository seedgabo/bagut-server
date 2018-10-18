@if (Request::is('admin/*')) 
    <?php $layout = 'backpack::layout'; $admin = "admin/"; ?>
@else
    <?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif
@extends($layout)

@section('header')
    <section class="content-header">
      <h1>
         Proveedor : {{$proveedor->nombre}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ url('admin/proveedores') }}">Listado de Proveedores</a></li>
        <li class="active">{{$proveedor->nombre}}</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="row">
  <div class="col-md-9">
      @include('proveedores.partials.ficha-proveedor', ['proveedor' => $proveedor])
  </div>
  <div class="col-md-3">
    @include('proveedores.partials.tabla-documentos', ['documentos' => $proveedor->archivos])
      

    {!! Form::open(['method' => 'POST', 'url' => 'admin/cargar-archivo/proveedor/'. $proveedor->id, 'class' => 'form-inline', 'files' => true]) !!}
            {!! Form::file('archivo', ['id' => 'archivo', 'required' => 'required', 'class'=> 'btn']) !!}      
            {!! Form::submit("Subir", ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}
  </div>


  <div class="col-md-12 nav-tabs-custom">
      <ul class="nav nav-tabs">
        {{-- <li class="active"><a href="#tickets" data-toggle="tab">@choice('literales.ticket', 10)</a></li> --}}

        @if(config('modulos.facturas'))
          <li class="active text-capitalize"><a href="#facturas" data-toggle="tab">@choice('literales.invoice', 10)</a></li>
        @endif

        @if(config('modulos.evaluaciones'))
          <li class="text-capitalize" ><a href="#evaluaciones" data-toggle="tab">@choice('literales.evaluacion', 10)</a></li>
        @endif
      </ul>
      <div class="tab-content">
          @if(config("modulos.evaluaciones"))
          <div class="tab-pane text-capitalize" id="evaluaciones">
                <div class="col-md-12">
                    @include('evaluaciones.partials.tabla-evaluaciones-proveedor',['proveedor' => $proveedor])
                </div>
          </div>
          @endif
          <div class="tab-pane active text-capitalize" id="facturas">
                <div class="col-md-12">
                    @include('proveedores.partials.tabla-invoices-proveedor',['proveedor' => $proveedor])
                </div>
          </div>
      </div>



  </div>
</div>

      
</div>
@endsection


