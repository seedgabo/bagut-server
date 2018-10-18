@if (Request::is('admin/*')) 
    <?php $layout = 'backpack::layout'; $admin = "admin/"; ?>
@else
    <?php $layout = 'layouts.app'; $admin = "/"; ?>
@endif

@extends($layout)

@section('header')
    <section class="content-header">
      <h1>
         @choice('literales.producto', 1) : {{$producto->name}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ url('admin/productos') }}">Listado de @choice('literales.producto', 10)</a></li>
        <li class="active">{{$producto->name}}</li>
      </ol>
    </section>
@endsection


@section('content')      
  @include('productos.partials.ficha-producto')
  @include('productos.partials.ficha-imagenes',['images' => $producto->images])
@endsection


