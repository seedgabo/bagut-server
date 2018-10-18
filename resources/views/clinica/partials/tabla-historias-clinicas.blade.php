    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">Historias Clínicas</h5>
            <div class="box-tools pull-right">
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Resultado - CIE10</th>
                            <th>Analisis</th>
                            <th>Médico</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1 ?>
                    @forelse ($historias as $hist)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{\App\Funciones::transdate($hist->fecha, 'l j \d\e F \d\e Y')}}</td>
                            <td>@if($hist->cie10){{$hist->cie10->titulo}}@endif</td>
                            <td>{{str_limit($hist->analisis)}}</td>
                            <td>@if($hist->medico){{$hist->medico->nombre}}@endif</td>
                            <td><a class="btn btn-sm" href="{{url('admin/ver-historia-clinica/' . $hist->id)}}">Ver</a></td>
                        </tr>
                    @empty
                    <tr>
                        Este paciente no tiene ninguna historia clinica
                    </tr>
                    @endforelse

                    </tbody>
                </table>
            <a class="btn btn-primary" href="{{url('admin/historias_clinicas/create?paciente_id='. $paciente->id)}}">Agregar Historia Clínica</a>
            </div>
        </div>
    </div>