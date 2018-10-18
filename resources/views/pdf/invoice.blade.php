<?php $sum = 0; ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <link rel="stylesheet" href="{{asset('css/bootstrap-email.css')}}">
    <!-- Latest compiled and minified CSS & JS -->
    <style type="text/css" media="screen">
    body
    {
        font-family: 'Helvetica Neue';
        font-size: 14px;
        font-weight: 12px;
    }
    </style>
</head>

<body>
    <table class="table" style="background-color: #EAEAEA">
        <thead>
            <tr>
                <td>
                	<img src="{{asset('img/logo.png')}}" style="min-width: 120px; max-width: 120px; width: 120px;"><br>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <strong>Factura #: {{$factura->id}} </strong> 
                    <br><strong>Fecha: </strong>  {{ $factura->fecha->format("d/m/Y") }}
                    <p style="text-align: center">{!! $factura->cabecera !!}</p>
                </td>
            </tr>
        </thead>
    </table>
    <table class="table" style=" text-transform: uppercase;">
        <div style="text-align: center !important;">
            <a href="{{url('')}}">{{ config("settings.nombre_empresa") }}</a>
        </div>
        <thead>
            <tr>
                <td></td>
                <td style="text-align: right; vertical-align: middle;">
                </td>
            </tr>
        </thead>
    </table>
    <div style="border-top: 1px solid #E5E4FF;"></div>

    <table class="table table-bordered">
        <thead>
            <tr style="background-color: #E5E4FF;">
                <th style="text-align: center;">Cantidad:</th>
                <th style="text-align: center;">Nombre:</th>
                <th style="text-align: center;">Referencia:</th>
                <th style="text-align: center;">Precio Unitario:</th>
                <th style="text-align: center;">Subtotal:</th>
            </tr>
        </thead>
        <tbody>
            @forelse(json_decode($factura->items) as $item)
            <tr>            
              <td>@if(isset($item->cantidad)){{ $item->cantidad }} @endif</td>
              <td>@if(isset($item->name)){{ $item->name }} @endif</td>
              <td>@if(isset($item->id)){{ $item->id }} @endif</td>
              <td style="text-align: right !important;">$ {{ number_format($item->precio,2,",",".") }}</td>
              <td style="text-align: right !important;">$ {{ number_format( (intval($item->precio) * intval($item->cantidad)),2 ,",",".") }}</td>
            </tr>
            @empty 
            <tr>No hay Items</tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right !important;">Total:</th>
                <th style="text-align: right !important;">$ {{ number_format( $factura->subtotal ,2 ,",",".") }}</th>
            </tr>
        </tfoot>
    </table>
         <div class="well" >
              <p class="lead">Total a pagar:</p>

              <div class="">
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

    <div class="well well-lg">
		{!! $factura->pie !!}
    </div>
    <div style="text-align: center">
        {!! $factura->nota !!}
    </div>
</body>
</html>