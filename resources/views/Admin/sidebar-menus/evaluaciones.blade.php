@if (Auth::user()->canAny(['Agregar Evaluaciones', 'Editar Evaluaciones','Eliminar Evaluaciones',
	'Evaluar Proveedores','Evaluar Conductores','Evaluar Vehiculos']) 
|| Auth::user()->hasRole('SuperAdmin'))   

    <li class="treeview">
        <a href="#"><i class="fa fa-sitemap"></i><span>Evaluaciones</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">


          @if (Auth::user()->canAny(['Agregar Evaluaciones', 'Editar Evaluaciones','Eliminar Evaluaciones']) || Auth::user()->hasRole('SuperAdmin'))
          <li><a href="{{ url('admin/evaluaciones') }}"><i class="fa fa-check-square-o"></i> <span>Lista de Evaluaciones</span></a></li>
          @endif

          @if (Auth::user()->canAny(['Evaluar Proveedores']) || Auth::user()->hasRole('SuperAdmin'))
          <li><a href="{{ url('admin/evaluaciones-proveedores') }}"><i class="fa fa-users"></i> <span>Evaluaciones a Proveedores</span></a></li>
          @endif
          
          @if (Auth::user()->canAny(['Evaluar Conductores']) || Auth::user()->hasRole('SuperAdmin'))
          <li><a href="{{ url('admin/evaluaciones-conductores') }}"><i class="fa fa-certificate"></i> <span>Evaluaciones a Conductores</span></a></li>
          @endif
          
          @if (Auth::user()->canAny(['Evaluar Vehiculos']) || Auth::user()->hasRole('SuperAdmin'))
          <li><a href="{{ url('admin/evaluaciones-vehiculos') }}"><i class="fa fa-car"></i> <span>Evaluaciones a Vehiculos</span></a></li>
          @endif


        </ul>
    </li>


@endif