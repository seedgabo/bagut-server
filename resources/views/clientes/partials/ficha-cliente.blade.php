    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">
                <a href="{{url($admin . 'ver-cliente/'. $cliente->id)}}">{{$cliente->fullname}} </a></h5>
            <div class="box-tools pull-right">
                <a href="{{url('admin/clientes/'. $cliente->id .'/edit?cliente_id='. $cliente->id)}}"><i class="fa fa-edit"></i> Editar</a>
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-3 text-center">
                <img src="{{$cliente->foto_url}}" class="img-thumbnail"  style="height:120px;">
            </div>
            <div class="col-md-9">                
                <div class="col-md-4">
                    <b>Nombres:</b>  {{$cliente->nombres}}
                </div>
                <div class="col-md-4">
                    <b>NIT:</b>  {{$cliente->nit}}
                </div>
                <div class="col-md-4">
                    <b>Email:</b>  {{$cliente->email}}
                </div>
                <div class="col-md-4">
                    <b>Dirección:</b>  {{$cliente->direccion}}
                </div>
                <div class="col-md-4">
                    <b>Telefono:</b>  {{$cliente->telefono }}
                </div>
                <div class="col-md-4">
                    <b>Fecha de Ingreso:</b>  {{ $cliente->fecha_ingreso }}
                </div>
                  <div class="col-md-4">
                    <b>Fecha de Egreso:</b>  {{ $cliente->fecha_egreso }}
                </div>
                <div class="col-md-4">
                    <b>Condiciones:</b>  {{$cliente->condiciones}}
                </div>
                <div class="col-md-6">
                    <b>Notas:</b>  {{$cliente->notas}}
                </div>
            </div>
        </div>
    </div>