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
 <div class="col-md-10">
     
    <div class="form-group{{ $errors->has('categoria_id') ? ' has-error' : '' }}">
        {!! Form::label('categoria_id', 'Categoria donde desea colocar los archivos') !!}
        {!! Form::select('categoria_id', $categorias->pluck('full_name','id')->toArray(), null, ['id' => 'categoria_id', 'class' => 'form-control chosen', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('categoria_id') }}</small>
    </div>

    <div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }}">
        {!! Form::label('archivo', 'Archivos') !!}
        {!! Form::file('archivo', ['required' => 'required', 'class' => 'file-bootstrap', 'multiple', 'id' => 'archivero']) !!}
    </div>

 </div>
</div>
@endsection

@section('after_scripts')
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/css/fileinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput_locale_es.min.js"></script>
<script type="text/javascript">
        var $input = $("#archivero");
        $input.fileinput({
            language: 'es',
            uploadUrl: "{{url('cargar-archivo')}}",
            uploadAsync:  true,
            showUpload:  false,
            showRemove:  false, 
            minFileCount: 1,
            maxFileCount: 1000,
            showPreview: true,
            uploadExtraData: function() { 
                return { categoria_id: $('#categoria_id').val()};
            }
        }).on("filebatchselected", function(event, files) {
            $input.fileinput("upload");
        });
</script>
@stop
