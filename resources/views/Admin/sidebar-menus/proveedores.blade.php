@if (Auth::user()->canAny(['Agregar Proveedores', 'Editar Proveedores','Eliminar Proveedores'])||  Auth::user()->hasRole('SuperAdmin'))


<li class="treeview text-capitalize">
<a href="#"><i class="fa fa-address-book"></i><span>Proveedores</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">	
		@if (Auth::user()->canAny(['Agregar Proveedores', 'Editar Proveedores','Eliminar Proveedores']) || Auth::user()->hasRole('SuperAdmin'))   
		<li><a href="{{ url('admin/proveedores') }}"><i class="fa fa-address-book"></i> <span>@choice('literales.proveedor', 10)</span></a></li>
		@endif

		@if (Auth::user()->canAny(['Agregar Proveedores', 'Editar Proveedores','Eliminar Proveedores']) || Auth::user()->hasRole('SuperAdmin'))   
		<li><a href="{{ url('admin/invoices-proveedores') }}"><i class="fa fa-file-pdf-o"></i> <span>@choice('literales.invoice', 10)</span></a></li>
		@endif
	</ul>
</li>


@endif