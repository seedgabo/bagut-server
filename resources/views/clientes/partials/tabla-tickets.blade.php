<div class="box box-primary box-solid">
    <div class="box-header">
        <h5 class="text-default">@choice('literales.ticket', 10)</h5>
        <div class="box-tools pull-right">
          <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-hover table-condensed datatable ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titulo</th>
                        <th>Fecha de Apertura</th>
                        <th>Estado</th>
                        <th>Categoría</th>
                        <th>Creador</th>
                        <th>@choice('literales.guardian', 1)</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($tickets as $ticket)
                    <tr>
                        <td>{{$ticket->id}}</td>
                        <td>{{ $ticket->titulo }}</td>
                        <td>{{\App\Funciones::transdate($ticket->created_at) or 'no vence'}}</td>
                        <td>{{ $ticket->estado }} </td>
                        <td>{{ $ticket->categoria->nombre  or '' }} </td>
                        <td>{{$ticket->user->nombre or 'nadie'}}</td>
                        <td>{{$ticket->guardian->nombre or 'nadie'}}</td>
                        <td><a class="btn btn-sm" href="{{url('ticket/ver/' . $ticket->id)}}">Ver</a></td>
                    </tr>
                @empty
                <tr>
                    Este cliente no tiene ningún @choice('literales.ticket', 1)
                </tr>
                @endforelse
                </tbody>
            </table>
            <a class="btn btn-primary" href="{{url('agregar-ticket?cliente_id='.$cliente->id)}}">Agregar @choice('literales.ticket', 1)</a>
        </div>
    </div>
</div>