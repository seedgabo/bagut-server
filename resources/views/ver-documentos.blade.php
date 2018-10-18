<?php
  $categoria = \App\Models\CategoriaDocumentos::find($categoria);
  $parent    = $categoria->parent;
 ?>
@extends('layouts.app')
@section('content')
    <div class="col-md-12">
        
        <ol class="breadcrumb">
            <li>
                <a href="{{url('ver-documentos')}}"> Categorias </a>
            </li>
            @while(isset($parent))
                <li><a href="{{url('ver-documentos/'. $parent->id)}}"> {{$parent->nombre}} </a></li>
                <?php $parent = $parent->parent ?>    
            @endwhile
            <li class="active">{{$categoria->nombre}}</li>
        </ol>
        
    <h3 class="text-uppercase text-center">{{$categoria->nombre}}</h3>
        <div class="list-group col-md-8 col-md-offset-2">
            @forelse ($categorias as  $cat)
                <a href="{{url('ver-documentos/'. $cat->id)}}" class="list-group-item">
                    <i class="fa fa-folder"></i>
                    {{$cat->nombre}}
                </a>
            @empty            
            @endforelse
        </div>
        <div class="list-group col-md-8 col-md-offset-2">
            @forelse ($documentos as $doc)
                <div  class="list-group-item">
                    <span class="pull-right">
                        <span class="badge">{{$doc->mime}}</span>
                        <a href="{{url('getDocumento/'.$doc->id)}}" class="btn btn-default btn-xs"><i class="fa fa-download"></i> Descargar</a>
                        @if (Auth::user()->can('Eliminar Documentos'))
                            <a href="{{url('admin/eliminar-documento/' . $doc->id)}}" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                        @endif
                    </span>
                    <i class="fa fa-file pull-left fa-2x"></i>
                    <h4 class="list-group-item-heading">{{ $doc->titulo}}</h4>
                    <p class="list-group-item-text">{!! $doc->descripcion !!}
                    </p>
                </div >            
            @empty
                <b> No hay Documentos Disponibles</b>
            @endforelse
        </div>

    </div>
    
@endsection