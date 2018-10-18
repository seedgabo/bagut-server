    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">
                <a href="{{url('admin/ver-conductor/'. $conductor->id)}}">{{$conductor->full_name}} </a></h5>
            <div class="box-tools pull-right">
                <a href="{{url('admin/conductores/'. $conductor->id .'/edit?conductor_id='. $conductor->id)}}"><i class="fa fa-edit"></i> Editar</a>
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-3 text-center">
                <img src="{{$conductor->foto_url}}" class="img-thumbnail"  style="height:120px;">
            </div>
            <div class="col-md-9">                
                <div class="col-md-12">
                    <b>Nombres: </b>  {{ $conductor->nombres }}
                </div>
                <div class="col-md-6">
                    <b>Apellidos:</b>  {{ $conductor->apellidos }}
                </div>
                <div class="col-md-6">
                    <b>{{Lang::get('cedula')}}:</b>  {{ $conductor->cedula }}
                </div>
                  <div class="col-md-12">
                    <b>Fecha de Nacimiento:</b>  {{ \App\Funciones::transdate($conductor->nacimiento,'d/m/Y') }}
                </div>
                <div class="col-md-6">
                    <b>Tipo de Sangres:</b>  {{$conductor->sangre}}
                </div>
                <div class="col-md-6">
                    <b>Sexo:</b> {{$conductor->sexo}}
                </div>
               <div class="col-md-4">
                    <b>Estado Civil:</b> {{$conductor->estadoCivil}}
                </div>
                <div class="row"></div>
                <div class="col-md-4">
                    <b>Telefono:</b> {{$conductor->telefono}}
                </div>
                <div class="col-md-12"></div>
                <div class="col-md-6">
                    <b>dirección:</b> {{$conductor->dirección}}
                </div>
                <div class="col-md-6">
                    <b>Nota:</b> {{$conductor->nota}}
                </div>
                <div class="col-md-4">
                    <b>Fecha de Ingreso a la empresa:</b> {{$conductor->ingreso->format('d/m/Y')}}
                </div>
                <div class="col-md-4">
                    <b>EPS:</b>@if(isset($conductor->eps)){{$conductor->eps->nombre}}@endif
                </div>
                <div class="col-md-4">
                    <b>ARL:</b>@if(isset($conductor->arl)){{$conductor->arl->nombre}}@endif
                </div>
                <div class="col-md-4">
                    <b>Fondo de Pensiones:</b>@if(isset($conductor->fondo)){{$conductor->fondo->nombre}}@endif
                </div>
        </div>
    </div>
    </div>