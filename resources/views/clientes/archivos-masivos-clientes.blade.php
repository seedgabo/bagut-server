@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Agregar Archivos Masivamente
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Archivos Masivos</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="row">
 {!! Form::open(['method' => 'POST', 'url' => 'admin/clientes/cargar-archivo', 'class' => 'form-horizontal container' , 'files' => true]) !!}
     <div class="form-group{{ $errors->has('cliente_id') ? ' has-error' : '' }}">
         {!! Form::label('cliente_id', 'Cliente:') !!}
         {!! Form::select('cliente_id',$clientes, null, ['id' => 'cliente_id', 'class' => 'form-control chosen', 'required' => 'required']) !!}
     </div>
 

     <div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }}">
         {!! Form::label('archivo', 'Archivo') !!}
         {!! Form::file('archivo', ['required' => 'required', 'id' => 'archivo_cliente', 'multiple']) !!}         
     </div>
 
 {!! Form::close() !!}
@endsection

@section('after_scripts')
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/css/fileinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput_locale_es.min.js"></script>
<script type="text/javascript">

        var $input = $("#archivo_cliente");
        $input.fileinput({
            language: 'es',
            uploadUrl: "{{url('admin/clientes/cargar-archivo')}}",
            uploadAsync:  true,
            showUpload:  false,
            showRemove:  false, 
            minFileCount: 1,
            maxFileCount: 1000,
            showPreview: true,
            uploadExtraData: function() { 
                return { cliente_id: $('#cliente_id').val()};
            }
        }).on("filebatchselected", function(event, files) {
            $input.fileinput("upload");
        });
</script>
@stop
