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
     {!! Form::open(['method' => 'POST', 'url' => 'admin/procesos_masivos/cargar-archivo', 'class' => 'form-horizontal col-md-12 hover','files' => true]) !!}
     
          <div class="form-group{{ $errors->has('proceso_masivo_id') ? ' has-error' : '' }}">
             {!! Form::label('proceso_masivo_id', 'Proceso al que pertenece:') !!}
             {!! Form::select('proceso_masivo_id',$procesos, Request::input('proceso_masivo_id'), ['id' => 'proceso_masivo_id', 'class' => 'form-control chosen', 'required' => 'required']) !!}
         </div>

         <div class="form-group{{ $errors->has('cliente_id[]') ? ' has-error' : '' }}">
             {!! Form::label('cliente_id[]', 'Cliente al que pertenece:') !!}
             {!! Form::select('cliente_id[]',$clientes, null, ['id' => 'cliente_id', 'class' => 'form-control chosen', 'required' => 'required','multiple']) !!}
         </div>
     

         <div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }}">
             {!! Form::label('archivo', 'Archivo') !!}
             {!! Form::file('archivo', ['required' => 'required', 'id' => 'archivo_cliente', ]) !!}         
         </div>
        <button type="submit" class="btn btn-success">Subir</button>
     {!! Form::close() !!}
     </div>
@endsection

@section('after_scripts')
    <link href="{{asset('css/dependent-dropdown.min.css')}}" media="all" rel="stylesheet" type="text/css" />
    <script src="{{asset('js/dependent-dropdown.min.js')}}" type="text/javascript"></script>
    
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/css/fileinput.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput_locale_es.min.js"></script>
    
    <script>
        $("#cliente_id").depdrop({
            depends: ['proceso_masivo_id'],
            url: '{{url('ajax/getClientsbyProcesosMasivos')}}',
            placeholder: false
        });
    </script>
    <script type="text/javascript">

            var $input = $("#archivo_cliente");
            $input.fileinput({
                language: 'es',
                uploadUrl: "{{url('admin/procesos_masivos/cargar-archivo')}}",
                uploadAsync:  true,
                showUpload:  false,
                showRemove:  false, 
                minFileCount: 1,
                maxFileCount: 1000,
                showPreview: false,
                uploadExtraData: function() { 
                    return { cliente_id: $('#cliente_id').val(), proceso_masivo_id: $('#proceso_masivo_id').val()};
                },
                slugCallback: function(filename) {
                    return filename.replace('(', '(');
                }
            })
	    .on('filebatchuploadsuccess', function(ev,data,prvid,index){
			alert("Archivo Subido");
		})
            // .on("filebatchselected", function(event, files) {
            //     $input.fileinput("upload");
            // });
    </script>
@stop
