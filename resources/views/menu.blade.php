@php
$misTickets= \App\Models\Tickets::misTickets()->count();

$todos  = \App\Models\Tickets::todos()->count();

$tickets = \App\Models\Tickets::where('estado',"<>","completado")
->whereIn("categoria_id",$categorias->pluck("id"))
->orderBy("categoria_id","asc")
->orderBy("created_at")
->count();

$categorias = \App\Models\CategoriaDocumentos::where("parent_id","=","0")
->orwhereNull("parent_id")
->distinct()->get();
$proximos = \App\Models\Tickets::todos()
->where('estado',"<>","completado")
->where('vencimiento',"<>","null")
->orderBy("vencimiento","asc")
->take(6)
->get();
@endphp
@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-8 hidden-xs hidden-sm">
    @include('partials.calendar')
  </div>
  <div class="col-md-4">
      @include('partials.widget-user') 
      @include('partials.resume-user-widget') 
  </div>
</div>

@include('partials.tickets-widget')

@if( config('modulos.clientes'))
    @include('clientes.menus.principal')
@endif


@if (config('modulos.gestion_documental')   && !Auth::user()->can('Ocultar Gestion Documental'))
  @include('documentos.menus.principal')
@endif
@if (config('modulos.historias_clinicas')  && Auth::user()->medico == 1)
<div class="row">
   @include('clinica.menus.principal')
</div>
@endif
@if (config('modulos.procesos_masivos'))
<div class="row">
   @include('procesosMasivos.menus.principal')
</div>
@endif
@stop
