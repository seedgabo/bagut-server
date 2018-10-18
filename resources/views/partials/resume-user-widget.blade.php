<div class="box box-widget hover">
  <div class="box-header">
    <h3 class="box-title">
      Próximos @choice('literales.ticket', 10)
    </h3>
  </div>
  <div class="box-body">
    <ul class="nav nav-stacked">
      @forelse ($proximos as $ticket)
      <li><a href="{{url('ticket/ver/'. $ticket->id)}}">{{$ticket->titulo}}
        <span class="text-muted"> {{$ticket->vencimiento->format('d/m/Y')}}</span>
        <span class=" pull-right label {{$ticket->estado}}"> {{$ticket->estado}}</span>
      </a></li>
      @empty
      Sin proximos a vencerse
      @endforelse
    </ul>
  </div>
</div>
