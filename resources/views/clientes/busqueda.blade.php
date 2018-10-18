@if (Request::is('admin/*')) 
    <?php $layout = 'backpack::layout'; $admin = "admin/"; ?>
@else
    <?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif

@extends($layout)

@section('header')
<section class="content-header">
  <h1>
    Busqueda
 </h1>
</section>
@endsection


@section('content')
<div class="row">
  <div class="col-md-offset-1 col-md-10">
    <div class="box">
       <div class="box-header">
         Busqueda
       </div>
       <div class="box-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
             @forelse ($clientes as $cliente)
              <tr>
                <td>{{ $cliente->full_name_cedula }}</td>
                <td><a href="{{url($admin . 'ver-cliente/'  . $cliente->id)}}" class="btn btn-primary"> Ver </a></td>
              </tr>
             @empty
              <h2>No se encontraron registros</h2>
             @endforelse
            </tbody>
          </table>
       </div>
    </div>
  </div>
</div>
<style type="text/css" media="screen">
  .hover
  {
    -webkit-transition: .1s linear;
    -moz-transition: .1s linear;
    -ms-transition: .1s linear;
    -o-transition: .1s linear;
    transition: .1s linear;
  }
  .hover:hover
  {
    box-shadow: 5px 5px 20px #D4D4D4;
  }
</style>    
@endsection


