
<?php
    $id = isset($entry) ? $entry->id: null;
    $selected = isset($entry) ? $entry->categoriasdocumentos()->get()->pluck('id')->toArray(): [];
    $html = \App\Models\CategoriaDocumentos::checkbox_menu_documentos(\App\Models\CategoriaDocumentos::all(),0,$selected,[], false);
?>

    <h3>{!! Form::label('categoria_documentos_id', 'Categorias Documentos', ['class' => 'col-sm-12 control-label']) !!}  </h3><br>

	<div class="col-md-12">
    	{!! $html !!}
	</div>