<div class="col-md-4">
    <div class="box box-widget widget-user-2">
      <!-- Add the bg color to the header using any of the bg-* classes -->
      <div class="widget-user-header bg-primary">
        <div class="widget-user-image">
            <img class="img-circle" src="{{asset('img/documentos.png')}}" alt="proceso image">
        </div>
        <!-- /.widget-user-image -->
          <h3 class="widget-user-username text-uppercase">Ultimos @choice('literales.proceso_masivo', 10)</h3>
          <h5 class="widget-user-desc">&nbsp;</h5>
      </div>
      <div class="box-footer no-padding">
        <ul class="nav nav-stacked"> 
        @forelse ($procesosMasivos as $proceso)
            <li><a href="{{url('ver-procesoMasivo/'.$proceso->id)}}">
              <i class="fa fa-list pull-right"></i> {{ $proceso->titulo}}
            </a></li>
        @empty
        @endforelse
        @if (Auth::user()->canAny(["Agregar Procesos Masivos","Editar Procesos Masivos", "Eliminar Procesos Masivos"]) or Auth::user()->hasRole("SuperAdmin"))
            <li><a href="{{url('admin/procesos-masivos')}}">
                <i class="fa fa-list"></i> Todos los @choice('literales.proceso_masivo', 10)
            </a></li>
        @endif
        </ul>
      </div>
    </div>
</div>