    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">Evaluaciones A {{ $conductor->full_name }}</h5>
            <div class="box-tools pull-right">
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Evaluación</th>
                            <th>Fecha de la evaluacion</th>
                            <th>Estado</th>
                            <th>Analisis</th>
                            <th>Acción requerida</th>
                            <th>Nota</th>
                            <th>Proxima Evaluación</th>
                            <th><i class="fa fa-eye"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1 ?>
                    @forelse ($conductor->evaluaciones()->orderBy('fecha_evaluacion')->get() as $eval)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$eval->evaluacion->nombre}}</td>
                            <td>{{\App\Funciones::transdate($eval->fecha_evaluacion)}}</td>
                            <td>{{$eval->estado}}</td>
                            <td>{{$eval->puntaje}}</td>
                            <td>{{$eval->accion}}</td>
                            <td>{{$eval->nota}}</td>
                            <td>{{\App\Funciones::transdate($eval->fecha_proxima)}}</td>
                            <td><a class="btn btn-sm" href="{{url('admin/ver-evaluacion-conductor/' . $eval->id)}}">Ver</a></td>
                        </tr>
                    @empty
                    <tr>
                        No hay ninguna Evaluación registrada
                    </tr>
                    @endforelse

                    </tbody>
                </table>
            <a class="btn btn-primary" href="{{url('admin/evaluaciones-conductores/create?conductor_id='. $conductor->id)}}">Agregar Evaluación</a>
            </div>
        </div>
    </div>
    <!-- Datatable -->
    <link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css" />
    
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js" type="text/javascript"></script>
    

    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js" type="text/javascript"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js" type="text/javascript"></script> 
    @include('layouts.partials.initialscript')