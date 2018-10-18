<div class="box box-default hover">
    <div class="box-header">
        <h5 class="text-primary">
           Evaluación {{$evaluacion->evaluacion->nombre}} a <small><a href="{{url('admin/ver-vehiculo/'. $vehiculo->id)}}">{{$vehiculo->full_name}} </a></small></h5>
        <div class="box-tools pull-right">
            <a href="{{url('admin/evaluaciones-vehiculos/'. $evaluacion->id .'/edit')}}"><i class="fa fa-edit"></i> Editar</a>
          <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">
            
            <div class="col-md-12">
                <b>Tipo Evaluación: </b>  {{ $evaluacion->evaluacion->nombre }} <br>
                <b>Descripción de la  Evaluación: </b>  {{ $evaluacion->evaluacion->descripcion }} <br>
                <b>Puntuación: </b>  {{ $evaluacion->puntuacion }} <br>
                <b>Fecha de Realización: </b>  {{ \App\Funciones::transdate($evaluacion->fecha_evaluacion) }} <br>
                <b>Fecha de Reevaluación: </b>  {{ \App\Funciones::transdate($evaluacion->fecha_evaluacion) }} <br>
                <b>Estado: </b>  <span style="font-size: 1.2em; text-transform: uppercase;">{{ $evaluacion->estado }} </span><br>
            </div>

            <div class="col-md-12">
                <h2 class="text-center">Registros:</h2>
                <hr>
                @forelse ($evaluacion->respuestas as $key => $resp)
                   <div class="col-md-6 text-right">
                       <b> {{$key}} : </b>
                   </div>
                   <div class="col-md-6">
                    {{$resp}} <br>   
                   </div>  
                @empty
                    Esta prueba no tiene registros
                @endforelse
            </div>
            
    </div>
</div>
