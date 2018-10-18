@if (Request::is('admin/*')) 
    <?php $layout = 'backpack::layout'; $admin = "admin/"; ?>

@elseif(Request::is('proveedores/*'))
    <?php $layout = 'proveedores.formularios.layout'; $admin = "proveedores/"; ?>
@else
    <?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif

@extends($layout)

@section('header')
    <section class="content-header">
      <h1>
         @choice('literales.proveedor', 1) : {{$proveedor->full_name or ""}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ url('admin/invoices') }}">Listado de @choice('literales.invoice', 10)</a></li>
        <li class="active">{{$proveedor->full_name or ""}}</li>
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
            {{ config("settings.nombre_empresa", "NewtonApp")}}
            <small class="pull-right">Fecha: {{$invoice->fecha->format("d/m/Y")}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info text-center">
        <div class="col-sm-4 invoice-col">
          <p class="text-center"> {!! $invoice->cabecera !!} </p>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b><img src="{{$invoice->proveedor->foto_url}}" style="height: 30px;"> {{$invoice->proveedor->full_name}}</b>
          <p>NIT: {{ $invoice->proveedor->documento }}</p>
          <span>Telefono: {{$invoice->proveedor->telefono}}</span>
          <address>
            {{$invoice->direccion}}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>@choice('literales.invoice', 1)  # {{str_pad ( $invoice->id , 5 ,"0", STR_PAD_LEFT)}} </b><br>
          <br>
          @if($invoice->vencimiento)<b>Fecha de Vencimiento:</b> {{$invoice->vencimiento->format("d/m/Y")}}<br>@endif
          <div class="hidden-print">
          <b>Estado de la @choice('literales.invoice', 1):</b> <span class="{{$invoice->estado}} label text-capitalize" style="font-size:15px;">{{$invoice->estado}}</span></div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      @if (!empty($items))
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
            @forelse(json_decode($invoice->items) as $item)
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
      @endif
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead">Nota:</p>
          <p>{{ $invoice->nota }}</p>

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            {!! $invoice->pie !!}
          </p>  
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Total a pagar:</p>

          <div class="table-responsive">
            <table class="table">
              <tbody><tr>
                <th style="width:50%">Subtotal:</th>
                <td>$ {{number_format( $invoice->sub_total, 2 ,",","." ) }}</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>$ {{ number_format( $invoice->total, 2 ,",","." )}}</td>
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
          <a type="button" class="btn btn-primary pull-right" style="margin-right: 5px;" href="{{url($admin . 'ver-invoice/pdf/' . $invoice->id)}}">
            <i class="fa fa-download"></i> Descargar PDF
          </a>
        </div>
      </div>
    </section>

  
</div>   
@endsection


