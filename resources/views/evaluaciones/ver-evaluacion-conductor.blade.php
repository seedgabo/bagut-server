@extends('backpack::layout')
@section('header')
    <section class="content-header">
      <h1>
         Evaluación a conductor : {{$conductor->full_name}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ url('admin/conductores') }}">Listado de conductores</a></li>
        <li class="active">{{$conductor->full_name}}</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
      @include('evaluaciones.partials.ficha-conductor', ['conductor' => $conductor])
  </div>

  <div class="col-md-8 col-md-offset-2">
      @include('evaluaciones.partials.ficha-evaluacion-conductor',['evaluacion' => $evaluacion])
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


