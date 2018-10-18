@if (Request::is('admin/*')) 
    <?php $layout = 'backpack::layout'; $admin = "admin/"; ?>
@elseif(Request::is('clientes/*'))
    <?php $layout = 'clientes.formularios.layout'; $admin = "clientes/"; ?>
@else
    <?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif

@extends($layout)


@section('content')
<div class="row">
  <div class="list-group col-md-8 col-md-offset-2">
      @forelse ($clientes as $cliente)
          <li  class="list-group-item">
              <a href="{{url($admin .'ver-cliente/'.$cliente->id)}}" class=""> 
                {{$cliente->full_name_cedula}}
              </a>
              <span class='pull-right'>
                de {{$cliente->user->nombre or 'nadie'}}
              </span>
          </li>            
      @empty
          <b> No hay Clientes Disponibles</b>
      @endforelse
  </div>  
</div>
@endsection


