<div class="col-md-4">
    <div class="box box-widget widget-user-2">
      <!-- Add the bg color to the header using any of the bg-* classes -->
      <div class="widget-user-header bg-primary">
        <div class="widget-user-image">
            <img class="img-circle" src="{{asset('img/medico.png')}}" alt="ticket image">
        </div>
        <!-- /.widget-user-image -->
          <h3 class="widget-user-username text-uppercase">@choice('literales.historia_clinica', 10)</h3>
          <h5 class="widget-user-desc">&nbsp;</h5>
      </div>
      <div class="box-footer no-padding">
        <ul class="nav nav-stacked"> 
            <li><a href="{{'admin/pacientes'}}">@choice('literales.paciente', 10) <i class="fa fa-user-md pull-right"></i></a></li>
            <li><a href="{{'admin/casos-medicos'}}">@choice('literales.caso_medico', 10) <i class="fa fa-medkit pull-right"></i></a></li>
            <li><a href="{{'admin/historias_clinicas'}}">@choice('literales.historia_clinica', 10) <i class="fa fa-history pull-right"></i></a></li>
        </ul>
      </div>
    </div>
</div>