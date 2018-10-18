<div class="col-md-4">
    <div class="box box-widget widget-user-2">
      <!-- Add the bg color to the header using any of the bg-* classes -->
      <div class="widget-user-header bg-primary">
        <div class="widget-user-image">
            <img class="img-circle" src="{{asset('img/clientes.png')}}" alt="clientes image">
        </div>
        <!-- /.widget-user-image -->
          <h3 class="widget-user-username text-uppercase">mis @choice('literales.cliente', 10)</h3>
          <h5 class="widget-user-desc">&nbsp;</h5>
      </div>
      <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
        @forelse (Auth::user()->clientes()->orderBy('updated_at','desc')->take(10)->get() as $cliente)
            <li><a href="{{'ver-cliente/'. $cliente->id}}">  {{ $cliente->nombres}} <i class="fa fa-briefcase pull-right"></i>  </a></li>
         @empty
         @endforelse 
            <li><a href="{{url('clientes-todos')}}"> Todos los Clientes <i class="fa fa-briefcase pull-right"></i> </a></li>
        </ul>
      </div>
    </div>
</div>




{{-- <div class="col-md-4">
    <div class="box box-widget widget-user-2">
      <!-- Add the bg color to the header using any of the bg-* classes -->
      <div class="widget-user-header bg-purple">
        <div class="widget-user-image">
            <img class="img-circle" src="{{asset('img/clientes.png')}}" alt="clientes image">
        </div>
        <!-- /.widget-user-image -->
          <h3 class="widget-user-username text-uppercase">mis @choice('literales.proceso', 10)</h3>
          <h5 class="widget-user-desc">&nbsp;</h5>
      </div>
      <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
        @forelse (Auth::user()->procesos()->orderBy('updated_at','desc')->take(10)->get() as $proceso)
            <li><a href="{{'admin/ver-proceso/'. $proceso->id}}">@choice('literales.proceso', 1) de  {{ $proceso->cliente->nombres}} <i class="fa fa-briefcase pull-right"></i>  </a></li>
         @empty
         @endforelse 
        </ul>
      </div>
    </div>
</div>
<div class="col-md-4">
    <div class="box box-widget widget-user-2">
      <!-- Add the bg color to the header using any of the bg-* classes -->
      <div class="widget-user-header bg-red">
        <div class="widget-user-image">
            <img class="img-circle" src="{{asset('img/clientes.png')}}" alt="clientes image">
        </div>
        <!-- /.widget-user-image -->
          <h3 class="widget-user-username text-uppercase">mis @choice('literales.consulta', 10)</h3>
          <h5 class="widget-user-desc">&nbsp;</h5>
      </div>
      <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
        @forelse (Auth::user()->consultas()->orderBy('updated_at','desc')->take(10)->get() as $consulta)
            <li><a href="{{'admin/ver-consulta/'. $consulta->id}}">@choice('literales.consulta', 1) de  {{ $consulta->cliente->nombres}} <i class="fa fa-briefcase pull-right"></i>  </a></li>
         @empty
         @endforelse 
        </ul>
      </div>
    </div>
</div> --}}