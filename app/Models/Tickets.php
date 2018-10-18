<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
class Tickets extends Model
{

  use \Venturecraft\Revisionable\RevisionableTrait;
  protected $revisionCreationsEnabled = true;
  use CrudTrait;
  use SoftDeletes;
  public $table = "tickets";


  protected $dates = ['deleted_at', 'vencimiento'];


    public $fillable = [
      "titulo",
      "contenido",
      "user_id",
      "guardian_id",
      "estado",
      "categoria_id",
      "archivo",
      "vencimiento",
      "transferible",
      "encriptado",
      'canSetVencimiento',
      'canSetGuardian',
  	  'canSetEstado',
      'invitados_id',
      'cliente_id',
      ];

  protected $hidden = [
    'clave'
    ];
  /**
  * The attributes that should be casted to native types.
  *
  * @var array
  */
  protected $casts = [
    "titulo" => "string",
    "contenido" => "string",
    "user_id" => "string",
    "guardian_id" => "string",
    "estado" => "string",
    "categoria_id" => "string",
    "archivo" => "string",
    "transferible" => "boolean",
    "encriptado" => "boolean",
    "clave" => "string",
    "invitados_id" => "array"
    ];

  /**
  * Validation rules
  *
  * @var array
  */
  public static $rules = [
    "titulo" => "required|min:3|max:50",
    "contenido" => "required|min:3",
    "user_id" => "required",
    "guardian_id" => "required",
    "estado" => "required",
    "categoria_id" => "required",
    "archivo" => "unique:tickets"
  ];

  public function clientes(){
      return $this->belongsToMany('App\Models\Cliente','cliente_ticket','ticket_id','cliente_id');
  }


  public static function byCategorias($categorias)
  {
    $tickets = Tickets::all();
    $permitidas = [];
    foreach ($tickets as $ticket)
    {
      if (in_array($ticket->categoria_id  ,$categorias->id))
        $permitidas[] =  $ticket;
    }
    return $permitidas;
  }

  public function archivo()
  {
    if($this->encriptado == true)
    {
      return url("getEncryptedFile/ticket/" . $this->id);
    }
    else
    {
      return asset("archivos/tickets/". $this->id ."/". $this->archivo);
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


  public function categoria()
  {
    return $this->belongsTo('App\Models\CategoriasTickets', 'categoria_id', 'id');
  }

  public function invitados()
  {
    return \App\User::whereIn("id",$this->invitados_id ? $this->invitados_id : [])->get();
  }

  public function guardian()
  {
    return $this->belongsTo("\App\User","guardian_id");
  }

  public function user()
  {
    return $this->belongsTo("\App\User","user_id");
  }

  public function comentarios()
  {
    return $this->hasMany("\App\Models\ComentariosTickets","ticket_id","id");
  }

  public function participantes()
  {
    $users = \App\User::orwhere("categorias_id", "LIKE", '%"'. $this->categoria_id . '"%')
    ->orwhereIn("id", [$this->user_id, $this->guardian_id]);
    if($this->invitados_id != null)
      $users->orWhereIn("id", $this->invitados_id);
    $users = $users->get();
    return $users;
  }

  public function cliente()
  {
    return $this->belongsTo("\App\Models\Cliente","cliente_id");
  }

  public function getLastSeguimiento(){
    $lastComentario = $this->comentarios()->orderby("id","desc")->first();
    if(isset($lastComentario) && isset($lastComentario->user))
      return "<b>".  $lastComentario->user->nombre .": </b> " . str_limit(strip_tags($lastComentario->texto), 80, "[...]");
    else
      return "Sin Seguimiento";
  }


  public function restoreItem()
  {
    return '<a href="' .  url('ajax/tickets/restore/' .$this->id) . '" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Recuperar </a>';
  }

  public function verDetalles(){
    return '<a href="'. url("ticket/ver/". $this->id).'" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Ver</a> ';
  }

  public function scopeTrashed($q)
  {
    return  $q->onlyTrashed();
  }


  public function scopeMisTickets($q)
  {
    return $q->where(function($q){
      $q->orWhere("user_id",Auth::user()->id);
      $q->orWhere("guardian_id",Auth::user()->id);
    })
    ->orderBy("categoria_id","asc")
    ->orderBy("created_at");
  }

  public function scopeTodos($q)
  {
    return  $q->orwhereIn("categoria_id",Auth::user()->categorias_id)
    ->orwhere("user_id",Auth::user()->id)
    ->orWhere("guardian_id",Auth::user()->id)
    ->orwhere("invitados_id", "LIKE", '%"'. Auth::user()->id . '"%');
  }



  public function setInvitadosIdAttribute($value)
  {

    if($value == "" || $value == null)
        $this->attributes['invitados_id'] = "[]";
    else
      $this->attributes['invitados_id'] = $value;
  }
}
