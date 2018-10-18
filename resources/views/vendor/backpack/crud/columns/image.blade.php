@if(isset($entry->image))
	<img src="{{$entry->image->{$column['attribute']} }}" style="width:65px;" class="img-thumbnail"/>
@else
	 <span class="text-muted">Sin Imagen</span>
 @endif
