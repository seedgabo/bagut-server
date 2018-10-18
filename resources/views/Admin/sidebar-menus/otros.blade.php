@include('Admin.sidebar-menus.idiomas')       
@include('Admin.sidebar-menus.correos')       
@include('Admin.sidebar-menus.paginas')    
@include('Admin.sidebar-menus.respaldos')


@if (Auth::user()->hasAnyRole(['SuperAdmin','Administrar Opciones']))
  <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/setting') }}"><i class="fa fa-cog"></i> <span>Parametros</span></a></li>
@endif

@if (Auth::user()->hasAnyRole(['SuperAdmin','Administrar Alertas']))
  <li><a href="{{ url('admin/alertas') }}"><i class="fa fa-bell"></i> <span>Alertas</span></a></li>
@endif 

@if (Auth::user()->hasAnyRole(['SuperAdmin']))
	<li><a href="{{ url('admin/auditar/resumen') }}"><i class="fa fa-bar-chart-o"></i> <span>Resumen de Uso</span></a></li>
	<li><a href="{{ url('admin/elfinder') }}"><i class="fa fa-files-o"></i> <span>Administrar Archivos</span></a></li>
@endif      


