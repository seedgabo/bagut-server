@if (Auth::user()->canAny([
  'Agregar Procesos Masivos', 'Editar Procesos Masivos','Eliminar Procesos Masivos',
  ]) ||  Auth::user()->hasRole('SuperAdmin'))
  <li class="treeview text-capitalize">

    <a href="#"><i class="fa fa-briefcase"></i><span>@choice('literales.proceso_masivo', 10)</span> <i class="fa fa-angle-left pull-right"></i></a>

    <ul class="treeview-menu">
        
        <li><a href="{{url('admin/procesos-masivos')}}"><i class="fa fa-list"></i><span> @choice('literales.proceso_masivo', 10) </span></a></li>
        
        <li><a href="{{url('admin/proceso-masivo/archivos-masivos')}}"><i class="fa fa-files-o"></i><span> Cargar Archivos a @choice('literales.proceso_masivo', 10)</a></li>

        <li><a href="{{url('admin/entidades')}}"><i class="fa fa-map-pin"></i><span> @choice('literales.entidad', 10) </a></li>
    </ul>
  </li>

  @endif 