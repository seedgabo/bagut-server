<div class="col-md-4">
  <div class="box box-widget widget-user-2">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header bg-primary">
      <div class="widget-user-image">
        <img class="img-circle" src="{{asset('img/tickets.png')}}" alt="ticket image">
      </div>
      <!-- /.widget-user-image -->
      <h3 class="widget-user-username text-uppercase">@choice('literales.ticket', 10)</h3>
      <h5 class="widget-user-desc">&nbsp;</h5>
    </div>
    <div class="box-footer no-padding">
      <ul class="nav nav-stacked">
        <li class="text-capitalize"><a href="{{url('mis-tickets')}}">Mis @choice('literales.ticket', 10) 
          <span class="pull-right badge bg-blue">{{$misTickets}}</span></a></li>

          <li class="text-capitalize"><a href="{{url('ticket')}}">@choice('literales.ticket', 10) Abiertos 
            <span class="pull-right badge bg-aqua">{{$tickets}}</span></a></li>

            <li class="text-capitalize"><a href="{{url('tickets/todos')}}">@choice('literales.ticket', 10)  Todos 
              <span class="pull-right badge bg-green">{{$todos}}</span></a></li>

              <li class="text-capitalize dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <a href="#!" title=""> Categorias <span class="caret"></span> <span class="pull-right badge bg-yellow">{{Auth::user()->categorias()->count()}}</span></a>
              </li>

              <ul class="dropdown-menu">
                @foreach (Auth::user()->categorias()->sortBy('id') as $categoria)
                <li>
                  <a href="{{url('tickets/categoria/' . $categoria->id)}}"> Categoria {{$categoria->full_name}}
                   <span class="pull-right badge bg-purple">{{$categoria->tickets->count()}}</span> 
                 </a>
               </li>
               @endforeach
             </ul>
           </ul>
         </div>
       </div>
     </div>