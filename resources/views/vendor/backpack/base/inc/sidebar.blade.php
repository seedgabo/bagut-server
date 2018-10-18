@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="{{Auth::user()->imagen()}}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->nombre }}</p>
            <a href="{{url('profile')}}"><i class="fa fa-circle text-success"></i> Editar Mi Usuario</a>
          </div>
        </div>


        <ul class="sidebar-menu">

          <li class="header">{{ trans('backpack::base.administration') }}</li>

          <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>



          @include('Admin.sidebar-menus.usuarios')  
          
          @include('Admin.sidebar-menus.tickets')  
            
          @include('Admin.sidebar-menus.documentos')


          @if(config("modulos.clientes"))
            @include('Admin.sidebar-menus.clientes')
          @endif

          @if(config("modulos.proveedores"))
            @include('Admin.sidebar-menus.proveedores')
          @endif

          @if(config("modulos.pedidos"))
            @include('Admin.sidebar-menus.pedidos')
          @endif

          @if(config("modulos.procesos_masivos"))
             @include('Admin.sidebar-menus.procesos-masivos')
          @endif

          @if(config("modulos.historias_clinicas"))
            @include('Admin.sidebar-menus.clinicas')
          @endif

          @if(config("modulos.evaluaciones"))
            @include('Admin.sidebar-menus.evaluaciones')
          @endif


          @if(config("modulos.tablas"))
            @include('Admin.sidebar-menus.tablas')
          @endif

          <li class="treeview">
            <a href="#"><i class="fa fa-globe"></i> <span>{{trans_choice('literales.avanced_menu',10)}}</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                @include('Admin.sidebar-menus.otros')    
            </ul>
          </li>

        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
