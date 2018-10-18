
<!DOCTYPE html>
<html lang="es">

<head>
  @include('layouts.partials.htmlheader')
</head>

<body class="animsition">

  {{-- <div id="wrapper"> --}}

  <!-- Navigation -->
  <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
      <a id="titulo-brand" class="navbar-brand" href="{{ url('/')}}" style="color: white;">
        <img src="{{asset('img/logo-newton.png')}}" alt="logo" class="img-responsive" style="height: 50px; display:inline">
        {{-- <img src="{{asset('img/tipografia.png')}}" alt="logo" class="img-responsive" style="height: 50px; display:inline"> --}}
        {{-- {{Config::get('settings.nombre_empresa', 'Matriz Seguimiento')}} --}}
      </a>
    </div>

    <!-- /.navbar-header -->
    <div class="navbar-collapse">

      <ul class="nav navbar-top-links navbar-right">
        <li><a href="{{url('calendar')}}">
          <i class="fa fa-calendar"></i> Calendario
        </a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            Acceso Rapido <i class="fa fa-caret-down"></i>
          </a>
          <ul class="dropdown-menu" style=" width:400px !important; left: 1% !important;">
            <li class="header">Atajos</li>
            @forelse (Auth::user()->atajos as $atajo)
            <li>
              <a href="{{$atajo->url}}" class="col-xs-10">
                <h5 class="text-default">
                  {{ strlen($atajo->texto) >0 ? $atajo->texto : $atajo->url }}
                </h5>
                <small class="">{{$atajo->url}}</small>
              </a>
              <div class="col-xs-2">
                <a href="{{url('eliminar-atajo/'. $atajo->id)}}">
                  <i class="fa fa-trash fa-lg text-danger"></i>
                </a>
              </div>
            </li>
            @empty
            <span>Sin Atajos</span>
            @endforelse

            <li class="footer">
              <form action="{{url('crear-atajo')}} "method="get" accept-charset="utf-8">
                {!! csrf_field() !!}
                <input type="hidden" name="url" value="{!! Request::url() !!}">
                <input type="hidden" name="texto" value="{{ isset($title) ? $title : 'Menu' }}">
                <button type="submit" class="btn btn-link btn-block btn-sm text-center" > <i class="fa fa-plus"></i> Agregar Esta Pagina</button>
              </form>
            </li>
          </ul>
        </li>

        <li class="dropdown text-center">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <img src="{{Auth::user()->imagen()}}" alt="perfil" class="img-circle" style="height: 40px; width: 40px;">
            {{Auth::user()->nombre}}
            <i class="fa fa-caret-down"></i>
          </a>
          <ul class="dropdown-menu">
            <li><a href="{{url('profile')}}"><i class="fa fa-user"></i> Ver Perfil</a></li>
            <li><a href="{{url('logout')}}"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
          </ul>
        </li>
        @if (Auth::user()->admin == 1)
        <li><a href="{{url('admin')}}"><i class="fa fa-lock fa-fw"></i> Administrar</i></a></li>
        @endif
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
           <i class="fa fa-bell fa-fw"></i>
           <span class="label label-primary">{{Auth::user()->unreadNotifications()->count()}}</span>
           <i class="fa fa-caret-down"></i>
         </a>
         <ul class="dropdown-menu dropdown-messages">
          @if(Auth::check())
          @forelse (Auth::user()->unreadNotifications as $notificacion)
          <li>
            <a href="{{ url('notificacion/' . $notificacion->id) }}">
              <div>
                @if (!isset($notificacion->read_at))
                <strong>{{$notificacion->data['titulo']}}</strong>
                <div>@if(array_key_exists ('texto',$notificacion->data)){{$notificacion->data['texto']}}@endif</div>
                @else
                <p text-muted>{{$notificacion->data['titulo']}} </p>
                <div>@if(array_key_exists ('texto',$notificacion->data)){{$notificacion->data['texto']}}@endif</div>
                @endif
                <span class="pull-right text-muted">
                  <em>{{\App\Funciones::transdate($notificacion->created_at, 'd-m-y h:m')}}</em>
                </span>
              </div>
            </a>
          </li>
          @empty
          <li><a href="#!"><div>No hay notificaciones</div></a></li>
          @endforelse
          @endif
          <li class="divider"></li>
          <li>
            <a class="text-center" href="{{ url('notificaciones') }}">
              <strong style="color:black">Ver todos</strong>
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
        </ul>
        <!-- /.dropdown-messages -->
      </li>

      <li><a href="#" data-toggle="control-sidebar"><i class="fa fa-bars"></i></a></li>
    </ul>
  </div>
</nav>

<div class="container">
  <div class="row">
  <br>
    <form action="{{url('busqueda')}}" class="col-md-6 col-md-offset-3 hidden-print">
      <div class="input-group">
        <input type="text" name="query" class="form-control input-lg" placeholder="Buscar..." required="">
        <span class="input-group-btn">
          <button class="btn btn-default btn-lg" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </span>
      </div>
    </form>
  </div>
  <div class="row">
    <br>
    @include('flash::message')
    @yield('content')
  </div>
</div>



<aside class="control-sidebar control-sidebar-dark" style="z-index: 0">

  <div id="control-sidebar-home-tab">
    <h3 class="control-sidebar-heading text-center"> MENU</h3>
    <ul class="control-sidebar-menu">
      <li>
        <a href="{{url('mis-tickets')}}">
          <i class="menu-icon fa fa-ticket bg-red"></i>

          <div class="menu-info">
            <h4 class="control-sidebar-subheading">mis @choice('literales.ticket', 2)</h4>
          </div>
        </a>
      </li>
      <li>
        <a href="{{url('ticket')}}">
          <i class="menu-icon fa fa-ticket bg-yellow"></i>

          <div class="menu-info">
            <h4 class="control-sidebar-subheading">@choice('literales.ticket', 2) abiertos</h4>
          </div>
        </a>
      </li>
      <li>
        <a href="{{url('/tickets/todos')}}">
          <i class="menu-icon fa fa-ticket bg-light-blue"></i>

          <div class="menu-info">
            <h4 class="control-sidebar-subheading">Todos @choice('literales.ticket', 2)</h4>
          </div>
        </a>
      </li>
      @if (config('modulos.gestion_documental')   && !Auth::user()->can('Ocultar Gestion Documental'))
      <li>
        <a href="{{url('ver-documentos')}}">
          <i class="menu-icon fa fa-file bg-green"></i>

          <div class="menu-info">
            <h4 class="control-sidebar-subheading">@choice('literales.documento', 2)</h4>
          </div>
        </a>
      </li>
      @endif
    </ul>
  </div>
</aside>   

{{-- </div> --}}
{{-- @include('layouts.partials.animsition') --}}
@include('backpack::inc.alerts')
@include('layouts.partials.scripts')
@yield('after_scripts')
</body>

</html>
