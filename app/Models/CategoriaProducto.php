<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Baum\Node;
use Illuminate\Database\Eloquent\SoftDeletes;
class CategoriaProducto extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;
     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'categorias_productos';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	protected $guarded = ['id'];
	// protected $fillable = [];
	// protected $hidden = [];
    // protected $dates = [];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
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
	      if($element["id"] != "" && in_array($element['id'],$disabled))  $menu_html .= " disabled ";
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

	public static function radio_menu_categorias($array,$parent_id=0,$value=[], $disabled=[], $required=false,$name ="parent_id")
	{
	  $menu_html = '<ul class="fa-ul">';
	  foreach($array as $element)
	  {
	    if($element['parent_id']==$parent_id)
	    {
	      $menu_html .= '<li><input type="radio" value="'.$element['id'] .'" name="'.$name .'" id="'.$element['nombre'] .'" ';
	      if($element['id'] !== null && in_array($element['id'],$disabled))  $menu_html .= " disabled ";
	      if($required)  $menu_html .= " required ";
	      if(in_array($element['id'],$value))  $menu_html .= " checked ";
	      $menu_html .= '/><label for="'.$element['nombre'].'><i class="fa fa-folder"></i> '. $element['nombre'].'</label>';
	      $menu_html .= static::radio_menu_categorias($array,$element['id'], $value, $disabled ,$required);
	      $menu_html .= '</li>';
	    }
	  }
	  $menu_html .= '</ul>';
	  return $menu_html;
	}

	public static function checkbox_menu_categorias($array ,$parent_id = 0 ,$value= [], $disabled = [], $required = false)
	{
	  $menu_html = '<ul class="fa-ul">';
	  foreach($array as $element)
	  {
	    if($element['parent_id']==$parent_id)
	    {
	      $menu_html .= '<li><input class="categoria" type="checkbox" value="'.$element['id'] .'" name="categorias_id[]" id="'.$element['nombre'] .'" ';
	      if(in_array($element['id'],$disabled))  $menu_html .= " disabled ";
	      if($required)  $menu_html .= " required ";
	      if(in_array($element['id'],$value))  $menu_html .= " checked ";
	      $menu_html .= '/><label for="'.$element['nombre'].'><i class="fa fa-folder"></i> '. $element['nombre'].'</label>';
	      $menu_html .= static::checkbox_menu_categorias($array,$element['id'], $value, $disabled ,$required);
	      $menu_html .= '</li>';
	    }
	  }
	  $menu_html .= '</ul>';
	  return $menu_html;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function parent()
	{
	  return $this->belongsTo(get_class(), 'parent_id');
	}

	public function children()
	{
	  return $this->hasMany(get_class(), 'parent_id');
	}

	public function productos()
	{
	  return $this->belongsToMany('App\Models\Producto','categoria_producto' ,"categoria_id","producto_id");
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| ACCESORS
	|--------------------------------------------------------------------------
	*/

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

	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
