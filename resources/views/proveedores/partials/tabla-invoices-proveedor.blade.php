<div class="box box-primary  box-solid">
    <div class="box-header">
        <h5 class="text-default">@choice('literales.invoice', 10)</h5>
        <div class="box-tools pull-right">
          <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-hover table-condensed datatable ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1 ?>
                @forelse ($proveedor->invoices as $factura)
                    <tr>
                        <td>{{str_pad ( $factura->id , 5 ,"0", STR_PAD_LEFT)}}</td>
                        <td><label class="{{$factura->estado}} badge">{{$factura->estado}}</label></td>
                        <td>{{$factura->fecha->format("d/m/Y")}}</td>
                        <td>$ {{number_format($factura->total, 2 ,",",".")}}</td>
                        <td><a class="btn btn-sm" href="{{url($admin . 'ver-invoice-proveedor/' . $factura->id)}}">Ver</a></td>
                    </tr>
                @empty
                <tr>
                    Este @choice('literales.proveedor', 1) no tiene ninguna @choice('literales.invoice', 1)
                </tr>
                @endforelse
                </tbody>
            </table>
            @if($admin == "admin/")
                <a class="btn btn-primary" href="{{url('admin/invoices-proveedores/create?proveedor_id='. $proveedor->id)}}">Agregar @choice('literales.invoice', 1)</a>
            @else
            @endif

        </div>
    </div>
</div>