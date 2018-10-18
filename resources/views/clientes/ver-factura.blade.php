@if (Request::is('admin/*')) 
    <?php $layout = 'backpack::layout'; $admin = "admin/"; ?>
@elseif(Request::is('clientes/*'))
    <?php $layout = 'clientes.formularios.layout'; $admin = "clientes/"; ?>
@else
    <?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif

@extends($layout)

@section('header')
    <section class="content-header">
      <h1>
         Cliente : {{$cliente->full_name or ""}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ url('admin/invoices') }}">Listado de Facturas</a></li>
        <li class="active">{{$cliente->full_name or ""}}</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="row">
  <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
          <img src="{{asset('img/logo.png')}}" style="height:70px;">
            {{ config("settings.nombre_empresa", "Newton")}}
            <small class="pull-right">Fecha: {{$factura->fecha->format("d/m/Y")}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info text-center">
        <div class="col-sm-4 invoice-col">
          <p class="text-center"> {!! $factura->cabecera !!} </p>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b><img src="{{$factura->cliente->foto_url}}" style="height: 30px;"> {{$factura->cliente->full_name}}</b>
          <p>NIT: {{ $factura->cliente->nit }}</p>
          <span>Telefono: {{$factura->cliente->telefono}}</span>
          <address>
            {{$factura->direccion}}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Factura  # {{str_pad ( $factura->id , 5 ,"0", STR_PAD_LEFT)}} </b><br>
          <br>
          @if($factura->vencimiento)<b>Fecha de Vencimiento:</b> {{$factura->vencimiento->format("d/m/Y")}}<br>@endif
          <div class="hidden-print">
          <b>Estado de la Factura:</b> <span class="{{$factura->estado}} label text-capitalize" style="font-size:15px;">{{$factura->estado}}</span></div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped text-center">
            <thead>
            <tr>
              <th>Cantidad</th>
              <th>Articulos/Servicios</th>
              <th>Referencia</th>
              <th>Valor Unitario</th>
              <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @forelse(json_decode($factura->items) as $item)
            <tr>            
              <td>@if(isset($item->cantidad)){{ $item->cantidad }}@endif</td>
              <td>@if(isset($item->name)){{ $item->name }}@endif</td>
              <td>@if(isset($item->id)){{ $item->id }}@endif</td>
              <td>$ {{ number_format($item->precio,2,",",".") }}</td>
              <td>$ {{ number_format( (intval($item->precio) * intval($item->cantidad)),2 ,",",".") }}</td>
            </tr>
            @empty 
            <tr>No hay Items</tr>
            @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead">Nota:</p>
          <p>{{ $factura->nota }}</p>

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            {!! $factura->pie !!}
          </p>  
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Total a pagar:</p>

          <div class="table-responsive">
            <table class="table">
              <tbody><tr>
                <th style="width:50%">Subtotal:</th>
                <td>$ {{number_format( $factura->sub_total, 2 ,",","." ) }}</td>
              </tr>
              <tr>
                <th>IVA (16%):</th>
                <td>$ {{ number_format( $factura->sub_total*0.16, 2 ,",","." )}}</td>
              </tr>
              <tr>
                <th>Descuento:</th>
                <td>$ {{ number_format( $factura->sub_total*0, 2 ,",","." )}}</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>$ {{ number_format( $factura->total, 2 ,",","." )}}</td>
              </tr>
            </tbody></table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a onclick="print()" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>
          <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Reportar Pago
          </button>
          <a type="button" class="btn btn-primary pull-right" style="margin-right: 5px;" href="{{url($admin . 'ver-invoice/pdf/' . $factura->id)}}">
            <i class="fa fa-download"></i> Descargar PDF
          </a>
        </div>
      </div>
    </section>

  
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


