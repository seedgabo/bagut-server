@php
	$items = json_encode(isset($entry) ? $entry->items :  []);
@endphp

<producto-searcher
	url = "{!! url('api/productos') !!}",
   :items ="{{ $items }}"
>
</producto-searcher>
