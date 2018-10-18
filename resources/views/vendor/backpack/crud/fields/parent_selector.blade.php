@php
    $selected = isset($field['value']) ? $field['value']: null;
    if(isset($entry)){
    	$id = $entry->id;
		$disableds = ($crud->model)::where("parent_id","=",$id)->orwhere("id","=",$id)->pluck("id")->toArray();
	}
	else{
		$id = '';
		$disableds = [];
	}

    $html = ($crud->model)::radio_menu_categorias(($crud->model)::all(),0,[$selected], $disableds, false ,$field['name']);
@endphp

    <h3>{!! Form::label('parent_id', 'Categoria Padre', ['class' => 'col-sm-12 control-label']) !!} </h3><br>
	<div class="col-md-12">
    	{!! $html !!}
	</div>