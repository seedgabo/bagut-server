<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\CrudTrait;
use Baum\Node;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class CategoriasTickets extends Node
{
  use SoftDeletes;
  use CrudTrait;
  use \Venturecraft\Revisionable\RevisionableTrait;
  protected $revisionCreationsEnabled = true;

  public $table = "categorias_tickets";

  protected $dates = ['deleted_at'];


  public $fillable = [
  "id",
  "nombre",
  "descripción",
  "parent_id",
  ];


  protected $casts = [
  "nombre" => "string",
  "descripción" => "string",
  "parent_id" => "integer",
  ];

  public static $rules = [
  "nombre" => "required|min:3|max:50",
  "descripción" => "min:3",
  ];


  public function parent()
  {
    return $this->belongsTo(get_class(), 'parent_id');
  }

  public function children()
  {
    return $this->hasMany(get_class(), 'parent_id');
  }

  public function tickets()
  {
    return $this->hasMany('App\Models\Tickets',"categoria_id");
  }

  public function guardian()
  {
    return $this->belongsTo('App\User',"guardian_id");
  }

  public function users()
  {
    return $users = User::where("categorias_id", "LIKE", '%"'. $this->id. '"%')->get();
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

  public function getButtonAddMasive()
  {
    return "<a class='btn btn-default btn-xs' href='categorias-masivas/" . $this->id . "'><i class='fa fa-user-plus'></i> Agregar Usuarios</a>";
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

  public static function radio_menu_categorias($array ,$parent_id = 0 ,$value= [], $disabled = [], $required = false)
  {
    $menu_html = '<ul class="fa-ul">';
    foreach($array as $element)
    {
      if($element['parent_id']==$parent_id)
      {
        $menu_html .= '<li><input type="radio" value="'.$element['id'] .'" name="categoria_id" id="'.$element['nombre'] .'" ';
        if($element['id'] != null && in_array($element['id'],$disabled))  $menu_html .= " disabled ";
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

  public static function menu($categorias, $tickets = true)
  {
    $menu_html = '<ol class="fa-ul" id="accordion">';
    foreach ($categorias as $key => $cat) {
      
        $menu_html .= '<li> <i class="fa fa-folder fa-li" style="color:blue;"></i> '. $cat->nombre;
       
        $menu_html .= static::menu($cat->children,true);
        
        if($tickets)
          $menu_html  .= static::menu_tickets($cat->id);

        $menu_html .= '</li>';
    }
    $menu_html .= '</ol>';
    return $menu_html;
  }

  public static function menu_tickets($categoria_id = null)
  {
    $tickets = \App\Models\Tickets::where("categoria_id","=",$categoria_id)->pluck("titulo","id");
    $menu = "<ul class='fa-ul'>";
    foreach ($tickets as $key => $value)
    {
      $menu .= "<li><i class='fa fa-ticket fa-li' style='color:red;'></i> ". $value ."</li>";
    }
    return $menu ."</ul>";
  }
}
