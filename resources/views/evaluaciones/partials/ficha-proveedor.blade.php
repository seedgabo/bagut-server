    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">
                <a href="{{url('admin/ver-proveedor/'. $proveedor->id)}}">{{$proveedor->nombre}} </a></h5>
            <div class="box-tools pull-right">
                <a href="{{url('admin/proveedores/'. $proveedor->id .'/edit?proveedor_id='. $proveedor->id)}}"><i class="fa fa-edit"></i> Editar</a>
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-3 text-center">
                <img src="{{$proveedor->foto_url}}" class="img-thumbnail"  style="height:120px;">
            </div>
            <div class="col-md-9">                
                <div class="col-md-12">
                    <b>Nombre: </b>  {{ $proveedor->nombre }}
                </div>
                <div class="col-md-6">
                    <b>NIT:</b>  {{ $proveedor->documento }}
                </div>
                <div class="col-md-6">
                    <b>Bien o Servicio:</b>  {{ $proveedor->bien_o_servicio == 'bien_servicio' ? 'Bien y Servicio' : $proveedor->bien_servicio }}
                </div>
                  <div class="col-md-12">
                    <b>Dirección:</b>  {{ $proveedor->direccion }}
                </div>
                <div class="col-md-6">
                    <b>Email:</b>  {{$proveedor->email}}
                </div>
                <div class="col-md-6">
                    <b>Telefono:</b> {{$proveedor->telefono}}
                </div>
               <div class="col-md-4">
                    <b>Ubicacion:</b> {{$proveedor->ubicacion}}
                </div>
                <div class="row"></div>
                <div class="col-md-4">
                    <b>Fecha de Ingreso:</b> {{$proveedor->fecha_ingreso->format('d-m-Y')}}
                </div>
                <div class="col-md-4">
                    <b>Tipo:</b> {{$proveedor->tipo}}
                </div>
                <div class="col-md-4">
                    <b>Nota:</b>{{$proveedor->nota}}
                </div>
        </div>
    </div>
    </div>