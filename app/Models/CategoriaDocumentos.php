<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Baum\Node;
use Backpack\CRUD\CrudTrait;

class CategoriaDocumentos extends Node
{
	use CrudTrait;
	use \Venturecraft\Revisionable\RevisionableTrait;

	protected $table = 'categorias_documentos';
	protected $primaryKey = 'id';
	// protected $guarded = [];
	protected $fillable = ['id','nombre','parent_id'];


	public function parent()
    {
        return $this->belongsTo(get_class(), 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(get_class(), 'parent_id');
    }

	public function documentos()
	{
		return $this->hasMany("\App\Models\Documenots","categoria_id","id");
	}

	public function FullName($value = "")
	{
		$full = $this->nombre .  $value;
		if(isset($this->parent))
		{
			return  $this->parent->FullName("&#8594;".$full);
		}
		else
		{
			return $full;
		}
	}

	public function getFullNameAttribute()
	{
		return $this->FullName();
	}


	public static function ordered_menu($array,$parent_id = 0)
	{
		$temp_array = array();
		foreach($array as $element)
		{
			if($element['parent_id']==$parent_id)
			{
				$element['subs'] = static::ordered_menu($array,$element['id']);
				$temp_array[] = $element;
			}
		}
		return $temp_array;
	}

	public static function radio_menu($array,$parent_id = 0 ,$value= [], $disabled = [], $required = false)
	{
		$menu_html = '<ul class="fa-ul">';
		foreach($array as $element)
		{
			if($element['parent_id']==$parent_id)
			{
				$menu_html .= '<li><input type="radio" value="'.$element['id'] .'" name="parent_id" id="'.$element['nombre'] .'" ';
				if(in_array($element['id'],$disabled))  $menu_html .= " disabled ";
				if($required)  $menu_html .= " required ";
				if(in_array($element['id'],$value))  $menu_html .= " checked ";
				$menu_html .= '><label for="'.$element['nombre'].'"> <i class="fa fa-folder"></i> '. $element['nombre'].'</label>';
				$menu_html .= static::radio_menu($array,$element['id'], $value, $disabled ,$required);
				$menu_html .= '</li>';
			}
		}
		$menu_html .= '</ul>';
		return $menu_html;
	}

	public static function radio_menu_documentos($array ,$parent_id = 0 ,$value= [], $disabled = [], $required = false)
	{
		$menu_html = '<ul class="fa-ul">';
		foreach($array as $element)
		{
			if($element['parent_id']==$parent_id)
			{
				$menu_html .= '<li><input type="radio" value="'.$element['id'] .'" name="categoria_id" id="'.$element['nombre'] .'" ';
				if(in_array($element['id'],$disabled))  $menu_html .= " disabled ";
				if($required)  $menu_html .= " required ";
				if(in_array($element['id'],$value))  $menu_html .= " checked ";
				$menu_html .= '><label for="'.$element['nombre'].'> <i class="fa fa-folder"></i> '. $element['nombre'].'</label>';
				$menu_html .= static::radio_menu_documentos($array,$element['id'], $value, $disabled ,$required);
				$menu_html .= '</li>';
			}
		}
		$menu_html .= '</ul>';
		return $menu_html;
	}
		
	public static function checkbox_menu_documentos($array ,$parent_id = 0 ,$value= [], $disabled = [], $required = false)
	{
    $menu_html = '<ul class="fa-ul">';
    foreach($array as $element)
    {
      if($element['parent_id']==$parent_id)
      {
        $menu_html .= '<li><input class="categoria" type="checkbox" value="'.$element['id'] .'" name="categoria_documentos_id[]" id="'.$element['nombre'] .'" ';
        if(in_array($element['id'],$disabled))  $menu_html .= " disabled ";
				if($required)  $menu_html .= " required ";
        if(in_array($element['id'],$value))  $menu_html .= " checked='checked' ";
        $menu_html .= '/><label for="'.$element['nombre'].'><i class="fa fa-folder"></i> '. $element['nombre'].'</label>';
        $menu_html .= static::checkbox_menu_documentos($array,$element['id'], $value, $disabled ,$required);
        $menu_html .= '</li>';
      }
    }
    $menu_html .= '</ul>';
    return $menu_html;
	}


	public static function menu($array,$parent_id = 0)
	{
		$menu_html = '<ol class="fa-ul"';
		if($parent_id == 0) $menu_html .= 'id="accordion"';
		$menu_html .= '>';
		foreach($array as $element)
		{
			if($element['parent_id']==$parent_id)
			{
				$menu_html .= '<li> <i class="fa fa-folder fa-li" style="color:blue;"></i> '. $element['nombre'];
				$menu_html .= static::menu($array,$element['id']);
				$menu_html .= static::menu_documentos($element['id']);
				$menu_html .= '</li>';
			}
		}
		$menu_html .= '</ol>';
		return $menu_html;
	}

	public static function menu_documentos($categoria_id = null)
	{
		$documentos = \App\Models\Documentos::where("categoria_id","=",$categoria_id)->pluck("titulo","id");
		$menu = "<ol class='fa-ul'>";
		foreach ($documentos as $key => $value)
		{
			$menu .= "<li><i class='fa fa-file fa-li' style='color:red;'></i> ". $value ."</li>";
		}
		return $menu ."</ol>";
	}
	
	public function setParentIdAttribute($value){
		if($value == null){
			return 0;
		}else{
			$this->attributes['parent_id'] = $value;
			return $value;
		}
	}

}
