<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class Proceso extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;

  const  categoria_id = 100;
  protected $table = 'procesos';
  protected $primaryKey = 'id';
  protected $fillable = ['id', 'cliente_id', 'user_id', 'ticket_id', 'partes', 'radicado', 'juzgado_instancia_1', 'juzgado_instancia_2', 'descripcion', 'notas', 'fecha_proceso','fecha_cierre', 'demandado','demandante'];
  protected $dates = ['fecha_proceso','fecha_cierre'];
  protected $casts = ['partes' => 'array'];
  protected $touches = ['cliente'];

  public function user()
  {
    return $this->belongsTo('\App\User','user_id','id');
  }

  public function ticket()
  {
    return $this->belongsTo('\App\Models\Tickets','ticket_id','id');
  }


  public function cliente()
  {
    return $this->belongsTo('\App\Models\Cliente','cliente_id','id');
  }

  public function archivos()
  {
   return $this->belongsToMany('App\Models\Archivos','archivos_procesos', 'proceso_id','archivo_id');
 }


 public function getButtonVerCliente(){
   return '<a href="'. url('admin\clientes?cliente_id='. $this->cliente_id) .'" class="btn btn-xs btn-info"><i class="fa fa-user"></i> Ver Cliente </a>';
 }

 public function getButtonVerDetalles(){
   return '<a href="'. url('admin\ver-proceso\\' . $this->id) .'" class="btn btn-xs btn-warning"><i class="fa fa-file-code-o"></i> Ver Detalles </a>';
 }


 public function verButtonTicket(){
   if($this->ticket_id != null && $this->ticket_id != "")
   {
    return '<a href="'. url('ticket/ver/' . $this->ticket_id) . '" class="btn btn-sm btn-info"> Ver Seguimiento </a>';
  }
  else
  {
    return "No iniciado";
  }
 }

}
