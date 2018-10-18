
<!DOCTYPE html>
<html lang="es">

<head>
    @include('layouts.partials.htmlheader')
    
</head>
<body>

    {{-- <div id="wrapper"> --}}

    <!-- Navigation -->
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a id="titulo-brand" class="navbar-brand" href="{{ url('/')}}" style="color: white;">
                <img src="{{asset('img/logo.png')}}" alt="logo" class="img-responsive" style="height: 50px; display:inline">
                {{Config::get('settings.nombre_empresa', 'Matriz Seguimiento')}}
            </a>
        </div>
     
        <!-- /.navbar-header -->
        <div class="navbar-collapse">

            <ul class="nav navbar-top-links navbar-right">
                
                <li class="">
                    <a href="{{url('clientes/invoices')}}" class="btn btn-link @if(!config("modulos.facturas")) disabled  @endif"> 
                        <i class="fa fa-inbox"></i> Mis @choice('literales.invoice', 10)
                    </a>
                </li>

                <li><a href="{{url('clientes/profile')}}"><i class="fa fa-user"></i> Ver Perfil</a></li>

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
                                        <div>{{$notificacion->data['texto']}}</div>
                                    @else
                                        <p text-muted>{{$notificacion->data['titulo']}} </p>
                                        <div>{{$notificacion->data['texto']}}</div>
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
                            <a class="text-center" href="{{ url('clientes/notificaciones') }}">
                                <strong style="color:black">Ver todos</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>

                <li><a href="{{url('logout')}}"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <br>
            @yield('content')
        </div>
    </div>
    
    {{-- </div> --}}
    @include('backpack::inc.alerts')
    @include('layouts.partials.scripts')
    @stack('scripts')

</body>

</html>
