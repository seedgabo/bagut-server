@if (Auth::user()->canAny(['Agregar Vehiculos', 'Editar Vehiculos','Eliminar Vehiculos',
	'Agregar Conductores', 'Editar Conductores','Eliminar Conductores',
	'Agregar Puestos', 'Editar Puestos','Eliminar Puestos'
  ]) ||  Auth::user()->hasRole('SuperAdmin'))

<li class="treeview">
<a href="#"><i class="fa fa-database"></i><span>Listas</span> <i class="fa fa-angle-left pull-right"></i></a>
<ul class="treeview-menu">



	@if (Auth::user()->canAny(['Agregar Vehiculos', 'Editar Vehiculos','Eliminar Vehiculos']) || Auth::user()->hasRole('SuperAdmin'))   
          <li><a href="{{ url('admin/vehiculos') }}"><i class="fa fa-truck"></i> <span>Vehiculos</span></a></li>
	@endif

	@if (Auth::user()->canAny(['Agregar Conductores', 'Editar Conductores','Eliminar Conductores']) || Auth::user()->hasRole('SuperAdmin'))   
          <li><a href="{{ url('admin/conductores') }}"><i class="fa fa-users"></i> <span>Conductores</span></a></li>
	@endif

	@if (Auth::user()->canAny(['Agregar Puestos', 'Editar Puestos','Eliminar Puestos']) || Auth::user()->hasRole('SuperAdmin'))   
   	 <li><a href="{{ url('admin/puestos') }}"><i class="fa fa-map-pin"></i> <span>Puestos de Trabajo</span></a></li>
	@endif
	  
	@if(Auth::user()->can('Administar Tablas Medicos') ||  Auth::user()->hasRole('SuperAdmin'))

      <li><a href="{{ url('admin/fondos-de-pensiones') }}"><i class="fa fa-table"></i> <span>Fondos de Pensiones</span></a></li>
      <li><a href="{{ url('admin/eps') }}"><i class="fa fa-building"></i> <span>Eps</span></a></li>
      <li><a href="{{ url('admin/arl') }}"><i class="fa fa-building"></i> <span>Arl</span></a></li>
      <li><a href="{{ url('admin/cie10') }}"><i class="fa fa-table"></i> <span>Cie10</span></a></li>

	@endif

</ul>
</li>

@endif