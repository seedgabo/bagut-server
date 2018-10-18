@extends('clientes.formularios.layout')
@php $admin = "clientes/" @endphp
@section('content')
	    <div class="box box-primary  box-solid">
        <div class="box-header">
            <h5 class="text-default">Facturas</h5>
            <div class="box-tools pull-right">
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed datatable ">
                    <thead>
                        <tr>
                            {{-- <th>#</th> --}}
                            {{-- <th>Fecha de Apertura</th> --}}
                            <th>#</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1 ?>
                    @forelse ($facturas as $factura)
                        <tr>
                            <td>{{str_pad ( $factura->id , 5 ,"0", STR_PAD_LEFT)}}</td>
                            <td><label class="{{$factura->estado}} badge">{{$factura->estado}}</label></td>
                            <td>{{$factura->fecha->format("d/m/Y")}}</td>
                            <td>$ {{number_format($factura->total, 2 ,",",".")}}</td>
                            <td><a class="btn btn-sm" href="{{url($admin . 'ver-invoice/' . $factura->id)}}">Ver</a></td>
                        </tr>
                    @empty
                    <tr>
                        Este cliente no tiene ningún proceso
                    </tr>
                    @endforelse
                    </tbody>
                </table>
                @if($admin == "admin/")
                <a class="btn btn-primary" href="{{url('admin/facturas/create?cliente_id='. $cliente->id)}}">Agregar Factura</a>
                @else
                @endif

            </div>
        </div>
    </div>
@stop