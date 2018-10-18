<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class ComentariosTickets extends Model
{
  use SoftDeletes;
  use \Venturecraft\Revisionable\RevisionableTrait;
  protected $revisionCreationsEnabled = true;
  public $table = "comentarios_tickets";

  protected $dates = ['deleted_at'];


  public $fillable = [
    "texto",
    "user_id",
    "ticket_id",
    "archivo",
    "encriptado",
    "archivo",
    "publico"
  ];

  protected $casts = [
    "texto" => "string",
    "user_id" => "integer",
    "ticket_id" => "integer",
    "archivo" => "string",
    "encriptado" => "boolean",
    "clave" => "string",
    "publico" => "boolean"
  ];

  public static $rules = [
    "texto" => "min:8",
    "user_id" => "",
    "ticket_id" => "",
    "archivo"  => "max:80000"
  ];

  protected $touches = ['ticket'];

  public function file()
  {
    if($this->archivo == null)
      return null;

    if($this->encriptado == true)
    {
      return url("getEncryptedFile/comentario/" . $this->id);
    }
    else
    {
      return  asset("archivos/ComentariosTickets/" . $this->archivo);
    }
  }

  public function getHasImageAttribute(){

    if($this->archivo !== null && $this->encriptado != true && (endsWith($this->archivo,".png") || endsWith($this->archivo,".jpg")))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  public static function countByTickets($limit)
  {
    return   \App\Models\ComentariosTickets::select(Db::raw('count(*) as count'),'tickets.titulo')
    ->groupBy("ticket_id")
    ->rightjoin("tickets","tickets.id","=","comentarios_tickets.ticket_id")
    ->orderby('count','desc')
    ->limit($limit)
    ->get();
  }

  public function user(){
    return $this->belongsTo("\App\User","user_id","id");
  }
  
  public function ticket(){
    return $this->belongsTo("\App\Models\Tickets","ticket_id","id");
  }

  public function scopePublicos($q){
    return $q->where("publico","=","1");
  }

}
