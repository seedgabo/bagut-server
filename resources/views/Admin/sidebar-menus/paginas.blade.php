@if(config("modulos.paginas") &&Auth::user()->hasAnyRole(['SuperAdmin', 'Administrar Paginas']))
	<li class="treeview">
	  <a href="#"><i class="fa fa-files-o"></i> <span>Paginas Web</span> <i class="fa fa-angle-left pull-right"></i></a>
	  <ul class="treeview-menu">
		  <li><a href="{{ url('admin/page') }}"><i class="fa fa-files-o"></i> <span>Paginas</span></a></li>
		  <li><a href="{{ url('admin/menu-item') }}"><i class="fa fa-list"></i> <span>Menu de Páginas</span></a></li>
	  </ul>
	</li>

@endif  