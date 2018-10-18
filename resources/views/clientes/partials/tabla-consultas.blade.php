    <div class="box box-primary box-solid">
        <div class="box-header">
            <h5 class="text-default">Consultas</h5>
            <div class="box-tools pull-right">
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="datatable table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Consulta</th>
                            <th>Detalles</th>
                            <th>Abogado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1 ?>
                    @forelse ($consultas as $cons)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{\App\Funciones::transdate($cons->fecha_consulta, 'l j \d\e F \d\e Y')}}</td>
                            <td>{!! $cons->consulta !!}</td>
                            <td>{!! str_limit($cons->detalles) !!}</td>
                            <td>{{$cons->user->nombre}}</td>
                            <td><a class="btn btn-sm" href="{{url($admin . 'ver-consulta/' . $cons->id)}}">Ver</a></td>
                        </tr>
                    @empty
                    <tr>
                        Este cliente no tiene ninguna consulta
                    </tr>
                    @endforelse

                    </tbody>
                </table>
                @if($admin == "admin/")
                <a class="btn btn-primary" href="{{url('admin/consultas/create?cliente_id='. $cliente->id)}}">Agregar Consulta</a>
                @else
                <a class="btn btn-primary" href="{{url('agregar-ticket?cliente_id='.$cliente->id."&tipo_selected=consulta")}}">Agregar Consulta</a>
                @endif
            </div>
        </div>
    </div>