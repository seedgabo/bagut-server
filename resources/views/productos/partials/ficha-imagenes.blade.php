<div class="box box-default hover">
    <div class="box-header">
        <h5 class="text-primary">
            <a href="{{url('admin/ver-producto/'. $producto->id . "/imagenes")}}"> @choice('literales.imagen', 10) </a>
        </h5>
        <div class="box-tools pull-right">
          <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
    @forelse ($images as $image)
        <div class="col-md-3 text-center">
            <span class="text-primary">{{$image->title}}</span>
            <img src="{{$image->url}}" alt="{{$image->title}}" class="img-thumbnail" style="height: 100px">
        </div>
    @empty
            Sin @choice('literales.imagen', 10)
    @endforelse
    </div>
</div>