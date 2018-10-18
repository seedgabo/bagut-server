
<?php
    $selected = isset($field['value']) ? $field['value']: null;
    $id = isset($entry) ? $entry->id: null;
    $html = \App\Models\CategoriaDocumentos::radio_menu_documentos(\App\Models\CategoriaDocumentos::all(),0,[$selected],[], true);
?>

    <h3>{!! Form::label('categoria_id', 'Categorias Documentos', ['class' => 'col-sm-12 control-label']) !!}  </h3><br>

	<div class="col-md-12">
    	{!! $html !!}
	</div>