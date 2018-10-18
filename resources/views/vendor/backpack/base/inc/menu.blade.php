<div class="navbar-custom-menu pull-left">

        <ul class="nav navbar-nav">
            <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                  Acceso Rapido <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu" style=" width:400px !important; left: 1% !important;">
                  <li class="header">Atajos</li>
                  <li>
                    <ul class="menu">
                    @forelse (Auth::user()->atajos as $atajo)
                      <li>
                        <a href="{{$atajo->url}}" class="col-xs-11">
                          <h5 class="text-default">
                            {{ strlen($atajo->texto) >0 ? $atajo->texto : $atajo->url }}
                          </h5>
                          <small class="">{{$atajo->url}}</small>
                        </a>
                        <div class="col-xs-1">
                            <a href="{{url('admin/eliminar-atajo/'. $atajo->id)}}">
                                <i class="fa fa-trash fa-lg text-danger" style="vertical-align: bottom;"></i>
                            </a>
                        </div>
                      </li>
                    @empty
                        <span>Sin Atajos</span>
                    @endforelse
                    
                    </ul>
                  </li>
                  <li class="footer">
                    <form action="{{url('admin/crear-atajo')}} "method="get" accept-charset="utf-8">
                        {!! csrf_field() !!}
                        <input type="hidden" name="url" value="{!! Request::url() !!}">
                        <input type="hidden" name="texto" value="{{ isset($title) ? $title : 'Admin' }}">
                        <button type="submit" class="btn btn-link btn-block btn-sm text-center" > <i class="fa fa-plus"></i> Agregar Esta Pagina</button>
                    </form>
                  </li>
                </ul>
            </li>
        </ul>
</div>



<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
     
       <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">{{Auth::user()->unreadNotifications()->count()}}</span>
          </a>
          <ul class="dropdown-menu" style=" width:480px !important;">
            <li class="header">
              Tienes {{Auth::user()->unreadNotifications()->count()}} notificationes sin leer
              </li>
            <li>
              <!-- inner menu: contains the actual data -->
              <ul class="menu">
                @forelse (Auth::user()->Notifications()->take(12)->orderby("created_at","desc")->get() as $notificacion)
                    <li>
                        <a href="{{ url('notificacion/' . $notificacion->id) }}">
                            <div>
                            @if (!isset($notificacion->read_at))
                                <strong>{{$notificacion->data['titulo']}}</strong>
                                <div>{{$notificacion->data['texto']}}</div>
                            @else
                                <p class="text-muted">{{$notificacion->data['titulo']}} </p>
                                <div style="white-space: normal">{{$notificacion->data['texto']}}</div>
                            @endif
                                <span class="pull-right text-muted">
                                    <em>{{\App\Funciones::transdate($notificacion->created_at, 'd-m-y h:m')}}</em>
                                </span>
                            </div>
                        </a>
                    </li>
                @empty
                @endforelse
              </ul>
            </li>
            <li class="footer"><a href="{{ url('notificaciones') }}">Ver Todas</a></li>
          </ul>
        </li>


      <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> <span></span></a></li>

        @if (Auth::guest())
            <li><a href="{{ url('admin/login') }}">{{ trans('backpack::base.login') }}</a></li>
            @if (config('backpack.base.registration_open'))
            <li><a href="{{ url('admin/register') }}">{{ trans('backpack::base.register') }}</a></li>
            @endif
        @else
        @endif
    </ul>
</div>
