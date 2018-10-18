@extends('layouts.app')

@section('content')

<div>

  <div class="col-md-4">
    <h4 class="text-uppercase text-center text-primary">@choice('literales.ticket', 10)</h4>
    <div class="list-group">
        @forelse ($tickets as $ticket)
            <a href="{{url('ticket/ver/'.$ticket->id)}}" class="list-group-item"><i class="fa fa-ticket"></i> {{$ticket->titulo}}
                <span class="badge"> en {{$ticket->categoria->full_name}}</span>
            </a>
        @empty
            No hay ningun @choice('literales.ticket', 1) que coincida con la busqueda
        @endforelse
    </div>
  </div>

  @if (config('modulos.clientes') && Auth::user()->anyPermission('Clientes'))
    <div class="col-md-4">
      <h4 class="text-uppercase text-center text-primary">@choice('literales.cliente', 10)</h4>
      <div class="list-group">
          @forelse ($clientes as $cliente)
              <a href="{{url('ver-cliente/'.$cliente->id)}}" class="list-group-item"><i class="fa fa-briefcase"></i> {{$cliente->full_name}}
               @if($cliente->user)<span class="badge"> de {{$cliente->user->nombre}}</span>@endif
              </a>
          @empty
              No hay ningun cliente que coincida con la busqueda
          @endforelse
      </div>
    </div>
  @endif

  @if (config('modulos.pedidos') && Auth::user()->anyPermission('Productos'))
    <div class="col-md-4">
      <h4 class="text-uppercase text-center text-primary">@choice('literales.producto', 10)</h4>
      <div class="list-group">
          @forelse ($productos as $producto)
              <a href="{{url('ver-producto/'.$producto->id)}}" class="list-group-item"><i class="fa fa-cart-plus"></i> {{$producto->name}}
              </a>
          @empty
              No hay ningun @choice('literales.producto', 1) que coincida con la busqueda
          @endforelse
      </div>
    </div>
  @endif

  @if (config('modulos.historias_clinicas')  && Auth::user()->medico == 1)
    <div class="col-md-4">
       <h4 class="text-uppercase text-center text-primary">@choice('literales.paciente', 1)</h4>
        <div class="list-group">
          @forelse ($pacientes as $pac)
              <a href="{{url('admin/ver-paciente/'.$pac->id)}}" class="list-group-item"><i class="fa fa-user-md"></i> {{$pac->full_name}}
               @if($pac->medico)<span class="badge"> de {{$pac->medico->nombre}}</span>@endif
              </a>
          @empty
              No hay ningun @choice('literales.paciente', 1) que coincida con la busqueda
          @endforelse
        </div>
    </div>
  @endif

  @if (config('modulos.procesos_masivos') && Auth::user()->anyPermission('Procesos Masivos'))
    <div class="col-md-4">
      <h4 class="text-uppercase text-center text-primary">@choice('literales.proceso_masivo', 10)</h4>
      <div class="list-group">
          @forelse ($procesosMasivos as $proceso)
              <a href="{{url('ver-procesoMasivo/'.$proceso->id)}}" class="list-group-item"><i class="fa fa-briefcase"></i> {{$proceso->titulo}}
              </a>
          @empty
              No hay ningun Proceso Masivo que coincida con la busqueda
          @endforelse
      </div>
    </div>
  @endif

  @if (config('modulos.proveedores') && Auth::user()->anyPermission('Proveedores'))
    <div class="col-md-4">
      <h4 class="text-uppercase text-center text-primary">@choice('literales.proveedor', 10)</h4>
      <div class="list-group">
          @forelse ($proveedores as $proveedor)
              <a href="{{url('ver-proveedor/'.$proveedor->id)}}" class="list-group-item"><i class="fa fa-briefcase"></i> {{$proveedor->full_name_cedula}}
              </a>
          @empty
              No hay ningun @choice('literales.proveedor', 1) que coincida con la busqueda
          @endforelse
      </div>
    </div>
  @endif

  @if (!Auth::user()->can('Ocultar Gestion Documental'))
    <div class="col-md-4">
      <h4 class="text-uppercase text-center text-primary">Documentos</h4>
      <div class="list-group">
          @forelse ($documentos as $doc)
              <a href="{{url('getDocumento/'.$doc->id)}}" class="list-group-item">
              <i class="fa fa-file"></i> {{$doc->titulo}}
              <span class="badge"> en {{$doc->categoria->full_name}}</span>
              <span class="badge">{{$doc->mime}}</span>
              </a>
          @empty
              No hay ningun documento que coincida con la busqueda
          @endforelse
      </div>
    </div>
  @endif

</div>

@endsection
