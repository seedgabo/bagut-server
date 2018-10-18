    <div class="box box-primary box-solid hover">
        <div class="box-header">
            <h5>Evaluaciones A {{$proveedor->nombre}}</h5>
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
                    @forelse ($proveedor->evaluaciones()->orderBy('fecha_evaluacion')->get() as $eval)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$eval->evaluacion->nombre}}</td>
                            <td>{{\App\Funciones::transdate($eval->fecha_evaluacion)}}</td>
                            <td>{{$eval->estado}}</td>
                            <td>{{$eval->puntaje}}</td>
                            <td>{{$eval->accion}}</td>
                            <td>{{$eval->nota}}</td>
                            <td>{{\App\Funciones::transdate($eval->fecha_proxima)}}</td>
                            <td><a class="btn btn-sm" href="{{url('admin/ver-evaluacion-proveedor/' . $eval->id)}}">Ver</a></td>
                        </tr>
                    @empty
                    <tr>
                        No hay ninguna Evaluación registrada
                    </tr>
                    @endforelse

                    </tbody>
                </table>
            <a class="btn btn-primary" href="{{url('admin/evaluaciones-proveedores/create?proveedor_id='. $proveedor->id)}}">Agregar Evaluación</a>
            </div>
        </div>
    </div>