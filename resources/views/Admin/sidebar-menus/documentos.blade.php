@if (Auth::user()->canAny(['Agregar Documentos', 'Editar Documentos','Eliminar Documentos']) 
  || Auth::user()->hasRole('SuperAdmin'))   


    <li class="treeview">
        <a href="#"><i class="fa fa-sitemap"></i><span>Documentos</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li><a href="{{ url('admin/documentos') }}"><i class="fa fa-files-o"></i> <span> Documentos </span></a></li>
          <li><a href="{{ url('admin/categoriadocumentos') }}"><i class="fa fa-folder-o"></i> <span>Categorias</span></a></li>
          <li><a href="{{url('getListaCategoriasDocumentos')}}" target="tree"><i class="fa-list fa"></i> Ver Arbol</a></li>
			@if(Auth::user()->canAny(['Agregar Documentos'])   || Auth::user()->hasRole('SuperAdmin'))		 
		 			<li><a href="{{ url('admin/archivos-masivos') }}"><i class="fa fa-files-o"></i> <span>Cargar Documentos Masivos</span></a></li>
		 	@endif
        </ul>
      </li>


@endif