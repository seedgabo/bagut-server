@if (Auth::user()->canAny([
  'Agregar Clientes', 'Editar Clientes','Eliminar Clientes',
  'Agregar Procesos', 'Editar Procesos','Eliminar Procesos',
  'Agregar Facturas', 'Editar Facturas','Eliminar Facturas',
  ]) ||  Auth::user()->hasRole('SuperAdmin'))
  <li class="treeview text-capitalize">

    <a href="#"><i class="fa fa-briefcase"></i><span>
      @choice('literales.cliente', 10)</span> <i class="fa fa-angle-left pull-right"></i>
    </a>

    <ul class="treeview-menu">

      <form action="{{url('admin/cliente/buscar')}}" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="search" name="query" class="form-control" placeholder="Buscar Cliente">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </form>
      
      @if (Auth::user()->canAny(['Agregar Clientes', 'Editar Clientes','Eliminar Clientes']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li><a href="{{ url('admin/clientes') }}"><i class="fa fa-users"></i> <span>@choice('literales.cliente', 10)</span></a></li>
      <span class="label label-warning pull-right">{{\App\Models\Cliente::count()}}</span>
      @endif

      @if(config('modulos.procesos'))
      @if (Auth::user()->canAny(['Agregar Procesos', 'Editar Procesos','Eliminar Procesos']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li><a href="{{ url('admin/procesos') }}"><i class="fa fa-file-text"></i> <span> Procesos</span></a></li>        
      @endif
      @endif

      @if(config('modulos.consultas'))
      @if (Auth::user()->canAny(['Agregar Consultas', 'Editar Consultas','Eliminar Consultas']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li><a href="{{ url('admin/consultas') }}"><i class="fa fa-file-text"></i> <span> Consultas</span></a></li>     
      @endif
      @endif

      @if(config("modulos.facturas"))
        @if (Auth::user()->canAny(['Agregar Facturas', 'Editar Facturas','Eliminar Facturas']) ||  Auth::user()->hasRole('SuperAdmin'))
        <li><a href="{{ url('admin/facturas') }}"><i class="fa fa-file-pdf-o"></i> <span> Facturas</span></a></li>               
        @endif
      @endif
      @if('modulos.facturas')
      @if (Auth::user()->canAny(['Agregar Clientes', 'Editar Clientes','Eliminar Clientes']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li><a href="{{ url('admin/cliente/archivos-masivos') }}"><i class="fa fa-files-o"></i> <span>Cargar Documentos a Clientes</span></a></li>
      @endif
      @endif
    </ul>
  </li>

  @endif    