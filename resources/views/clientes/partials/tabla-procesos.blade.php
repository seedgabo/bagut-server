    <div class="box box-primary box-solid">
        <div class="box-header">
            <h5 class="text-default">Procesos</h5>
            <div class="box-tools pull-right">
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed datatable ">
                    <thead>
                        <tr>
                            {{-- <th>#</th> --}}
                            {{-- <th>Fecha de Apertura</th> --}}
                            <th>Estado</th>
                            <th>Categoría</th>
                            <th>Radicado</th>
                            <th>Demandado</th>
                            <th>Demandante</th>
                            <th>Abogado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1 ?>
                    @forelse ($procesos as $proceso)
                        <tr>
                            {{-- <td>{{$i++}}</td> --}}
                            {{-- <td>{{\App\Funciones::transdate($proceso->fecha_proceso)}}</td> --}}
                            <td>@if($proceso->ticket) {{ $proceso->ticket->estado }} @endif</td>
                            <td>@if($proceso->ticket) {{ $proceso->ticket->categoria->nombre }} @endif</td>
                            <td>{{$proceso->radicado }} </td> 
                            <td>{{$proceso->demandado }} </td>
                            <td>{{$proceso->demandante }} </td>
                            <td>@if($proceso->user) {{$proceso->user->nombre}} @endif</td>
                            <td><a class="btn btn-sm" href="{{url($admin . 'ver-proceso/' . $proceso->id)}}">Ver</a></td>
                        </tr>
                    @empty
                    <tr>
                        Este cliente no tiene ningún proceso
                    </tr>
                    @endforelse
                    </tbody>
                </table>
                @if($admin == "admin/")
                <a class="btn btn-primary" href="{{url('admin/procesos/create?cliente_id='. $cliente->id)}}">Agregar Proceso</a>
                @else
                <a class="btn btn-primary" href="{{url('agregar-ticket?cliente_id='.$cliente->id."&tipo_selected=proceso")}}">Agregar Proceso</a>
                @endif

            </div>
        </div>
    </div>