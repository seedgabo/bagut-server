    <div class="box box-solid box-primary hover">
        <div class="box-header">
            <h5>Consulta de <a href="{{url($admin . 'ver-cliente/'. $consulta->cliente_id)}}"> <span>{{$consulta->cliente->full_name}}</a></span></h5>
            
            <div class="box-tools pull-right">
              <a href="{{url('admin/consultas/'. $consulta->id . '/edit')}}" class="btn btn-link">Editar</a>
              <b><i class="fa fa-clock-o"></i> </b> {{\App\Funciones::transdate($consulta->fecha_consulta , 'l j \d\e F \d\e Y')}}
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>            
      </div>
    <div class="box-body">
        <hr>
        <div class="col-md-12">
            <b class="text-uppercase text-primary">Consulta:</b> <p class="text-justify"> {!! $consulta->consulta !!} </p>
        </div>

        @if (isset($consulta->descripcion))
        <div class="col-md-12">
            <b class="text-uppercase text-primary">Detalles:</b> <p class="text-justify">{!! $consulta->detalles !!}</p>
        </div>
        @endif

        @if (isset($consulta->descripcion))
        <div class="col-md-12">
            <b class="text-uppercase text-primary">Descripción:</b> <p class="text-justify">{!! $consulta->descripcion !!}</p>
        </div>
        @endif

        <div class="col-md-6">
            <b class="text-uppercase text-primary">Notas:</b> <p class="text-justify">{!! $consulta->notas !!}</p>
        </div>  

        <div class="col-md-12 text-center">
                @if ($consulta->ticket_id != null && $consulta->ticket_id != 0)
                    <a href="{{url('/ticket/ver/'. $consulta->ticket_id)}}">  <i class="fa fa-ticket"></i> Ver Seguimiento</a> 
                @else
                    {{-- <a href="{{url('admin/iniciar-seguimiento/consulta/' .$consulta->id)}}"> <i class="fa fa-ticket"></i> Iniciar el seguimiento</a> --}}
                @endif
        </div>     
    </div>




    <div class="box-footer ">
        <p class="pull-left">
        </p>
        <p class="pull-right">
            <b>Abogado:</b>  <span>{{$consulta->user->nombre}}</span>
        </p>
    </div>
</div>