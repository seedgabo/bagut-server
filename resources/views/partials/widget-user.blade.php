<div class="box box-widget widget-user hover">
  <div class="box-tools pull-right">            
    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
  </div>

  <div class="widget-user-header" style="background-image: url({{asset('img/user-background.jpg')}}); background-size: initial;">
    <h3 class="widget-user-username"><a href="{{url('profile')}}" style="color:white;">{{Auth::user()->nombre}}</a></h3>
    <h5 class="widget-user-desc" style="color:white;">{{Auth::user()->cargo}} - {{Auth::user()->departamento}}</h5>
  </div>

  <div class="widget-user-image">
    <img class="img-circle" src="{{Auth::user()->imagen()}}" alt="User Avatar" style="height: 100px; width: 100px">
  </div>

  <div class="box-footer">
    <div class="row">
      <div class="col-sm-4 border-right">
        <div class="description-block">
          <a href="{{url('mis-tickets')}}" title="mis-tickets">
            <h5 class="description-header">{{$misTickets}}</h5>
            <span class="description-text">@choice('literales.ticket', $misTickets)</span>
          </a>
        </div>
        <!-- /.description-block -->
      </div>
      <!-- /.col -->
      <div class="col-sm-4 border-right">
        <div class="description-block">
          <a href="{{url('ticket')}}" title=ticket>
            <h5 class="description-header">{{$tickets}}</h5>
            <span class="description-text">@choice('literales.open', $tickets)</span>
          </a>
        </div>
        <!-- /.description-block -->
      </div>
      <!-- /.col -->
      <div class="col-sm-4">
        <div class="description-block">
          <a href="{{url('tickets/todos?estado=vencido')}}" title="tickets-todos">
            <h5 class="description-header">{{Auth::user()->tickets()->whereEstado("vencido")->count()}}</h5>
            <span class="description-text">@choice('literales.outdated', 2)</span>
          </a>
        </div>
        <!-- /.description-block -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
</div>