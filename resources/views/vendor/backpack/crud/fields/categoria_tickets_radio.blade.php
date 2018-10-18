<?php
    $selected = isset($entry) ? $entry->parent_id : '';
    $id = isset($entry) ? $entry->id : '';
    if($id != "")
    	$disableds = \App\Models\CategoriasTickets::where("parent_id","=",$id)->orwhere("id","=",$id)->pluck("id")->toArray();
    else
    	$disableds = [];
    
    $html = \App\Models\CategoriasTickets::radio_menu(\App\Models\CategoriasTickets::all(),0,[$selected], $disableds, false);
?>

    <h3>{!! Form::label('parent_id', 'Categoria Padre', ['class' => 'col-sm-12 control-label']) !!} </h3><br>

	<div class="col-md-12">
    	{!! $html !!}
	</div>