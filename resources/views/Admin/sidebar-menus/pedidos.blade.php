@if (Auth::user()->canAny([
  'Agregar Productos', 'Editar Productos','Eliminar Productos',
  'Agregar Pedidos', 'Editar Pedidos','Eliminar Pedidos',
  ]) ||  Auth::user()->hasRole('SuperAdmin'))
  <li class="treeview text-capitalize">

    <a href="#">
      <i class="fa fa-shopping-cart"></i><span>@choice('literales.pedido', 10)</span> 
        <i class="fa fa-angle-left pull-right"></i>
    </a>

    <ul class="treeview-menu">
      
      @if (Auth::user()->canAny(['Agregar Productos', 'Editar Productos','Eliminar Productos']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li>
        <span class="label label-warning pull-right">{{\App\Models\Producto::count()}}</span>
        <a href="{{ url('admin/productos') }}"><i class="fa fa-shopping-cart"></i> <span>@choice('literales.producto', 10)</span></a>
      </li>

      <li><a href="{{ url('admin/categoriasproductos') }}"><i class="fa fa-list-alt"></i> <span>@choice('literales.categoria', 10)</span></a></li>

      @endif

      @if (Auth::user()->canAny(['Agregar Pedidos', 'Editar Pedidos','Eliminar Pedidos']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li><a href="{{ url('admin/pedidos') }}"><i class="fa fa-list"></i> <span> @choice('literales.pedido', 10)</span></a></li>        
      @endif

    </ul>
  </li>

  @endif    