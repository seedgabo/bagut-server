@extends('backpack::layout') 
@section('header')
{{-- <section class="content-header">
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
    </ol>
</section> --}}
@endsection 
@section('content')
<div class="row">
    {!! Form::open(['method' => 'GET', 'class' => 'form-inline']) !!}
        <div class="form-group{{ $errors->has('desde') ? ' has-error' : '' }}">
            {!! Form::label('desde', 'Desde:') !!} 
			{!! Form::date('desde', $desde, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('desde') }}</small>
        </div>
        <div class="form-group{{ $errors->has('hasta') ? ' has-error' : '' }}">
            {!! Form::label('hasta', 'Hasta:') !!} 
			{!! Form::date('hasta',$hasta, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('hasta') }}</small>
        </div>
        <div class="form-group{{ $errors->has('usuarios') ? ' has-error' : '' }}">
            {!! Form::label('usuarios[]', 'Por Usuario:') !!} 
			{!! Form::select('usuarios[]',['' => ''] + \App\User::all()->pluck('nombre','id')->toArray(), $usuarios, ['id' => 'Usuario', 'class' => 'form-control chosen', 'multiple', 'data-placeholder' => 'Seleccionar Usuarios']) !!}
            <small class="text-danger">{{ $errors->first('Usuario') }}</small>
        </div>
        <div class="form-group">
            {!! Form::submit("Buscar", ['class' => 'btn btn-success']) !!}
        </div>
    {!! Form::close() !!}
    <br>
    <div class="col-md-12">
    
        {{-- Graficas Pedidos --}}
        @if (config("modulos.pedidos") && Auth::user()->hasRole(['Administrar Pedidos','SuperAdmin']))
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h4>{{\App\Models\Pedido::where("estado","=","pedido")->whereBetween("created_at",[$desde,$hasta])->count()}}</h4>

                      <p>Nuevas Ordenes</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{url('admin/pedidos')}}" class="small-box-footer">Ver Más <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                  <div class="small-box bg-green">
                    <div class="inner">
                      <h4>@currency(App\Models\Pedido::whereBetween("created_at",[$desde,$hasta])->get()->sum("total"))<sup style="font-size: 20px">$</sup></h4>

                      <p>Total Ventas</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <a  href="{{url('admin/pedidos')}}" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-yellow">
                    <div class="inner">
                        @php
                        $bestClient = \App\Models\Pedido::whereBetween("created_at",[$desde,$hasta])->groupBy('cliente_id')->with('cliente')->first();
                        if (isset($bestClient))
                            $bestClient = $bestClient->cliente;
                        $nombre  = isset($bestClient) ? $bestClient->full_name : 'No Disponible';
                        @endphp
                      <h5>{{ $nombre }}</h5>

                      <p>Cliente con mas Pedidos</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-person-add"></i>
                    </div>
					@if(isset($bestClient))
                    <a  href="{{url('admin/ver-cliente/' . $bestClient->id)}}" class="small-box-footer">Ver Cliente <i class="fa fa-arrow-circle-right"></i></a>
					@endif
                  </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                    @php
                        $producto = \App\Models\PedidoProducto::whereBetween("created_at",[$desde,$hasta])->groupBy('producto_id')->with('producto')->first();
                    @endphp
                      <h4>{{$producto->name or 'No Disponible'}}</h4>

                      <p>Mas Vendido</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-pie-graph"></i>
                    </div>
					@if(isset($producto))

                    	<a href="{{url('admin/ver-producto/' . $producto->producto_id)}}" class="small-box-footer"> Ver Producto <i class="fa fa-arrow-circle-right"></i></a>
					@endif
				  </div>
                </div>

            </div>



            <div class="col-lg-6 col-md-6">
                <div class="box  box-default">
                    <div class="box-header with-border text-center">
                        <div class="box-title text-capitalize">{{$chartVentasPorDepartamento->title}}</div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                       {!! $chartVentasPorDepartamento->render() !!}
                       
                    </div>
                </div>            
            </div>
        @endif
        
        {{-- Graficas Tickets --}}
        @if (config("modulos.tickets") && Auth::user()->hasRole(['Administrar Casos','SuperAdmin']))
            <div class="col-lg-6 col-md-6">
            <div class="box  box-default">
                <div class="box-header with-border text-center">
                    <div class="box-title">{{$chartPorEstados->title}}</div>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                   {!! $chartPorEstados->render() !!}
                </div>
            </div>            
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="box  box-default">
                    <div class="box-header with-border text-center">
                        <div class="box-title">{{ $chartMasComentados->title }}</div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                       {!! $chartMasComentados->render()  !!}
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="table-responsive box  box-default">
                    <div class="box-header with-border text-center">
                        <div class="box-title">Casos Por Vencerse</div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-hover display">
                            <thead>
                                <tr>
                                    <th>Caso:</th>
                                    <th>Responsable:</th>
                                    <th>Vence El:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ticketsPorVencer as $ticket)
                                <tr>
                                    <td>{{$ticket->titulo}}</td>
                                    <td>@if($ticket->guardian){{$ticket->guardian->nombre}}@endif</td>
                                    <td>{{\App\Funciones::transdate($ticket->vencimiento)}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td>No hay Datos en este periodo</td>
                                </tr>
                                @endforelse
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="table-responsive box  box-default">
                    <div class="box-header with-border text-center">
                        <div class="box-title">Casos Vencidos</div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-hover display">
                            <thead>
                                <tr>
                                    <th>Caso:</th>
                                    <th>Responsable:</th>
                                    <th>Vence El:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ticketsVencidos as $ticket)
                                <tr>
                                    <td>{{$ticket->titulo}}</td>
                                    <td>@if($ticket->guardian){{$ticket->guardian->nombre}}@endif</td>
                                    <td>{{\App\Funciones::transdate($ticket->vencimiento)}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td>No hay Datos en este periodo</td>
                                </tr>
                                @endforelse
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        
        {{-- Graficas Documentos --}}
        @if (Auth::user()->hasRole(['Administrar Documentos', 'SuperAdmin']))
            <div class="col-lg-6 col-md-6">
                <div class="box  box-default">
                    <div class="box-header with-border text-center">
                        <div class="box-title">{{ $chartMasDescargados->title }}</div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        {!! $chartMasDescargados->render() !!}
                    </div>
                </div>
            </div>
        @endif

        @if (config("modulos.calendario"))
            <div class="col-lg-6 col-md-6">
                @include('partials.calendar')
            </div> 
        @endif
    
    </div>
</div>
@endsection
