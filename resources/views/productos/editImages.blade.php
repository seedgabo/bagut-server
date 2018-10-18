@extends('backpack::layout')
@section('before_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.5/css/fileinput.min.css" />
@stop

@section('header')
    <section class="content-header">
      <h1>
         Producto : {{$producto->name}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ url('admin/productos') }}">Listado de Productos</a></li>
        <li class="active">{{$producto->name}}</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row" id="fotos">
    @forelse ($images as $image)
      <div class="col-md-3" id="{{$image->id}}">
            <span style="position: absolute; right:15px;">
              <a onclick="deleteImage({{$image->id}})" href="#!" class="btn btn-xs btn-danger"> <i class="fa fa-trash"></i></a>
            </span>
            @if ($image->id != $producto->image_id)
              <span style="position: absolute;">
                <a href="{{url('admin/producto/'. $producto->id .'/image/'. $image->id ."/default")}}" class="btn btn-xs btn-primary"> Marcar como principal</a>
              </span>
            @else
              <span style="position: absolute;" class="label label-primary">
                Principal
              </span>
            @endif

            <a href="#" class="thumbnail">
              <img data-src="{{$image->url}}" src="{{$image->url}}" alt="">
            </a>
      </div>
    @empty
    @endforelse
      
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
            <input type="file" name="image" multiple="" id="input-image"  accept="image/*">
            </div>
        </div>
    </div>

@endsection

@section('after_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.5/js/fileinput.js"></script>
<script>
$("#input-image").fileinput({
    uploadUrl: "{{ url('admin/producto/'. $producto->id .'/uploadImage')}}", // server upload action
    uploadAsync: true,
    maxFileCount: 10
})
.on("filebatchselected", function(event, files) {
    // trigger upload method immediately after files are selected
    $("#input-image").fileinput("upload");
})
.on('fileuploaded', function(event, data, previewId, index) {
  console.log(data.response);
  $("#fotos").append('<div class="col-md-3" id="'+ data.response.id +'"><span style="position: absolute; right:15px;"><a href="#!" onclick="deleteImage(' +data.response.id+')" class="btn btn-xs btn-danger"> <i class="fa fa-trash"></i></a> </span> <span style="position: absolute;"><a href="{{url('admin/producto/'.$producto->id)}}/image/' + data.response.id +'/default" class="btn btn-xs btn-primary"> Marcar como principal</a></span> <a href="#" class="thumbnail"> <img data-src="'+ data.response.url +'" src="'+ data.response.url +'" alt=""> </a> </div>');
});

function deleteImage(id){
	$.ajax({
		url: "{{url('api/images/')}}"  + "/" + id,
		type: 'DELETE',
		data: {},
	})
	.done(function() {
		$("#"+id).hide('fast');
		new PNotify({
		    title: "Exito!",
		    text: "Eliminado Correctamente",
		    type: "success"
		});
	})
	.fail(function(err) {
		new PNotify({
		    title: "Error!",
		    text: "No se pudo eliminar",
		    type: "error"
		});
		console.error(err);
	});
	
}
</script>
@stop

