    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">
                <a href="{{url('admin/ver-vehiculo/'. $vehiculo->id)}}">{{$vehiculo->full_name}} </a></h5>
            <div class="box-tools pull-right">
                <a href="{{url('admin/vehiculos/'. $vehiculo->id .'/edit')}}"><i class="fa fa-edit"></i> Editar</a>
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-3 text-center">
                <img src="{{$vehiculo->foto_url}}" class="img-thumbnail"  style="height:120px;">
            </div>
            <div class="col-md-9">                
                <div class="col-md-4">
                    <b>Marca: </b>  {{ $vehiculo->marca }}
                </div>
                <div class="col-md-4">
                    <b>Modelo:</b>  {{ $vehiculo->modelo }}
                </div>
                <div class="col-md-4">
                    <b>Línea:</b>  {{ $vehiculo->linea }}
                </div>
                  <div class="col-md-4">
                    <b>Placa:</b>  {{ $vehiculo->placa }}
                </div>
                <div class="col-md-4">
                    <b>Tipo:</b>  {{$vehiculo->tipo}}
                </div>
                <div class="col-md-4">
                    <b>Color:</b> {{$vehiculo->color}}
                </div>
               <div class="col-md-4">
                    <b>Motor:</b> {{$vehiculo->motor}}
                </div>
                <div class="row"></div>
                <div class="col-md-4">
                    <b>Chasis:</b> {{$vehiculo->chasis}}
                </div>
                <div class="col-md-4">
                    <b>Cilindraje:</b> {{$vehiculo->cilindraje}}
                </div>
                <div class="col-md-6">
                    <b>Dirección:</b> {{$vehiculo->direccion}}
                </div>
                <div class="col-md-4">
                    <b>Tipo de Uso:</b> {{$vehiculo->uso}}
                </div>
                <div class="col-md-4">
                    <b>Licencia de Transito:</b>{{$vehiculo->licencia_transito}}
                </div>
                <div class="col-md-4">
                    <b>Dueño Registrado:</b> {{$vehiculo->dueño}}
                </div>
                <div class="col-md-4">
                    <b>Fondo de Ingreso:</b> {{$vehiculo->fecha_ingreso}}
                </div>
                <div class="col-md-12">
                    <b>Nota:</b> {{$vehiculo->nota}}
                </div>
        </div>
    </div>
    </div>