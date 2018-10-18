<?php
    $selected = isset($field['value']) ? $field['value']: null;
    $id = isset($entry) ? $entry->id : '';
    $disableds = \App\Models\CategoriaDocumentos::where("parent_id","=",$id)->orwhere("id","=",$id)->pluck("id")->toArray();
    $html = \App\Models\CategoriaDocumentos::radio_menu(\App\Models\CategoriaDocumentos::all(),0,[$selected], $disableds, false);
?>

    <h3>{!! Form::label('parent_id', 'Categorias Documentos', ['class' => 'col-sm-12 control-label']) !!} </h3><br>

	<div class="col-md-12">
    	{!! $html !!}
	</div>