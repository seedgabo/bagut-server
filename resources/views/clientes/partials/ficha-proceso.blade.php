    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">Proceso</h5>
            <div class="box-tools pull-right">
                <a href="{{url('admin/procesos/'. $proceso->id . '/edit')}}" class="btn btn-link">Editar</a>
                <b>Fecha de Apertura: </b> {{\App\Funciones::transdate($proceso->fecha_proceso)}}
                <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>            
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <p><b>Radicado:</b>      {!! $proceso->radicado !!}</p>
                </div>
                <div class="col-md-12">
                    <p><b>Primera Instancia del juzgado:</b> {!! $proceso->juzgado_instancia_1 !!}</p>
                </div>
                <div class="col-md-12">
                    <p><b>Segunda Instancia del juzgado:</b> {!! $proceso->juzgado_instancia_2 !!}</p>
                </div>
                <div class="col-md-6">
                    <b>Cerrado el:</b> {{\App\Funciones::transdate($proceso->fecha_cierre)}}
                </div>
                <div class="col-md-6">
                    <b>Abogado:</b> @if(isset($proceso->user)){{ $proceso->user->nombre }}@endif
                </div>
                <div class="col-md-12">
                    <b>Descripción:</b> {!! $proceso->descripcion !!} 
                </div>
                <div class="col-md-12">
                    <b>Notas:</b> {!! $proceso->notas !!} 
                </div>
                <div class="col-md-6">
                    <b>Demandante:</b> {!! $proceso->demandante !!} 
                </div>
                <div class="col-md-6">
                    <b>Demandado:</b> {!! $proceso->demandado !!} 
                </div>

            </div>

        </div>
            <div class="box-footer text-center">
                    @if ($proceso->ticket_id != null && $proceso->ticket_id != 0)
                        <a href="{{url('/ticket/ver/'. $proceso->ticket_id)}}">  <i class="fa fa-ticket"></i> Ver Seguimiento</a> 
                    @else
                        <a href="{{url('admin/iniciar-seguimiento/proceso/' .$proceso->id)}}"> <i class="fa fa-ticket"></i> Iniciar el seguimiento</a>
                    @endif
            </div>
    </div>