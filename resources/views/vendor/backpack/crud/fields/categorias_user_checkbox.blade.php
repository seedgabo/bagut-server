<?php
    $selected = isset($entry) ? $entry->getCategorias()->pluck("id")->toArray(): [];
    $id = isset($entry) ? $entry->id : '';
    $disableds = [];
    $html = \App\Models\CategoriasTickets::checkbox_menu_categorias(\App\Models\CategoriasTickets::all()->toArray(),0,$selected, $disableds, false);
?>

    <h3>{!! Form::label('categorias_id', 'Categoria Padre', ['class' => 'col-sm-12 control-label']) !!} </h3><br>
    <div class="text-center">
	    <a class="button-select-all btn btn-xs btn-default"  href="#!">Seleccionar Todos</a>
	    <a class="button-deselect-all btn btn-xs btn-default"  href="#!">Deseleccionar Todos</a>
    </div>
	<div class="col-md-12">
    	{!! $html !!}
	</div>
    <div class="text-center">
	    <a class="button-select-all btn btn-xs btn-default"  href="#!">Seleccionar Todos</a>
	    <a class="button-deselect-all btn btn-xs btn-default"  href="#!">Deseleccionar Todos</a>
    </div>
<script>
$(".button-select-all").click(function(){
	$(".categoria").prop('checked', true);	
});
$(".button-deselect-all").click(function(){
	$(".categoria").prop('checked', false);	
});
</script>