<div class="box box-default hover">
    <div class="box-header">
        <h5 class="text-primary">
            <a href="{{url($admin . 'ver-producto/'. $producto->id)}}">{{$producto->name}} </a></h5>
        <div class="box-tools pull-right">
            <a href="{{url('admin/productos/'. $producto->id .'/edit?cliente_id='. $producto->id)}}"><i class="fa fa-edit"></i> Editar</a>
          <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="col-md-3 text-center">
            @if (isset($producto->image))
                <img src="{{$producto->image->url}}" class="img-thumbnail img-circle"  style="height:160px;">
            @endif
        </div>
        <div class="col-md-9">                
            <div class="col-md-4">
                <b>Nombre:</b>  {{$producto->name}}
            </div>
            <div class="col-md-4">
                <b>Referencia:</b>  {{$producto->referencia}}
            </div>
            <div class="col-md-4">
                <b>Precio:</b> $ {{ number_format($producto->precio,2,",",".")}}
            </div>
            <div class="col-md-8">
                <b>Descripción:</b>  {{$producto->description}}
            </div>
            <div class="col-md-4">
                <b>Notas:</b>  {{$producto->notas}}
            </div>
            <div class="col-md-4">
                <b>Stock:</b> {{ $producto->stock }} en existencia
            </div>
            <div class="col-md-4">
                <b>Categoria: </b> {{$producto->categoria->nombre or 'Ninguna'}}
            </div>
            <div class="col-md-4">
                <b>Destacado</b> @if($producto->destacado == 1)<i class="fa fa-star fa-lg text-yellow"></i>  @else <i class="fa fa-star text-muted"></i> @endif
            </div>
            <div class="col-md-4">
                <b>activo</b> @if($producto->active == 1) <i class="fa fa-check-square fa-lg"></i> @else NO @endif
            </div>

            <div class="col-md-4">
                <b>Impuesto por defecto:</b> {{$producto->impuesto }} %
            </div>

            <div class="col-md-4">
                <b>Mostrar el stock en los catalogo:</b> @if($producto->mostrar_stock == 1) <i class="fa fa-check-square fa-lg"></i> @else NO @endif
            </div>
            <div class="col-md-4">
                <b>Es Vendible sin Stock:</b> @if($producto->es_vendible_sin_stock == 1) <i class="fa fa-check-square fa-lg"></i> @else NO @endif
            </div>
            

        </div>
    </div>
</div>