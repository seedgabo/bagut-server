<div class="box box-primary box-solid">
    <div class="box-header">
        <h5 class="text-default">Procesos Masivos</h5>
        <div class="box-tools pull-right">
          <a href="{{url($admin . 'ver-documentos-clientes-procesosMasivos/'. $cliente->id)}}" title="">
            <i class="fa fa-files-o"></i> Ver Documentos en los Procesos
          </a class="btn btn-primary btn-sm">
          <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-hover table-condensed datatable ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Estado</th>
                        <th>Abierto el</th>
                        <th>Clientes en el proceso</th>
                        <th>Ultima Modificaciòn</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1 ?>
                @forelse ($procesos as $proceso)
                    <tr>
                        <td>{{$proceso->id}}</td>
                        <td>{{$proceso->titulo}}</td>
                        <td><span class="label {{$proceso->estado}}">{{$proceso->estado}}</span></td>
                        <td>{{$proceso->created_at->format('d/m/Y')}}</td>
                        <td>{{$proceso->clientes()->count()}}</td>
                        <td>{{$proceso->updated_at->format('d/m/Y')}}</td>
                        <td>
                            <a class="btn btn-sm" href="{{url($admin . 'ver-procesoMasivo/' . $proceso->id)}}">Ver</a>
                            <a class="btn btn-sm" href="{{url($admin . 'ver-documentos-procesoMasivo/' . $proceso->id . "#" 
                            . $cliente->nit)}}">Ver Documentos</a>
                        </td>
                    </tr>
                @empty
                <tr>
                    Este cliente no tiene ningún proceso masivo
                </tr>
                @endforelse
                </tbody>
            </table>
            @if($admin == "admin/")
                {{-- <a class="btn btn-primary" href="{{url('admin/procesos-masivos/create?cliente_id='. $cliente->id)}}">Agregar Proceso Masivo</a> --}}
            @endif

        </div>
    </div>
</div>