<?php

namespace App\Models;

use App\Models\ProcesosMasivosCliente;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProcesoMasivo extends Model
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

	protected $table = 'procesos_masivos';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ['titulo','entidad_id', 'referencia', 'user_id', 'inicio', 'cierre', 'estado']; 
	// protected $hidden = [];
    // protected $dates = [];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public function buttonProcesoClientes(){
		return '<a href="' . url('admin/ver-procesoMasivo/'. $this->id).'"  class="btn btn-xs btn-warning"/> <i class="fa fa-list"></i> Ver Proceso</a>';
	}

	public function getClientesCountAttribute(){
		return $this->clientes()->count();
	}
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function archivos()
	{
		return $this->belongsToMany('App\Models\Archivos','archivos_procesosmasivos', 'procesoMasivo_id','archivo_id')
		->withTimestamps()
		->withPivot(['cliente_id']);
	}



	
	public function clientes(){
		return $this->belongsToMany('App\Models\Cliente','procesos_masivos_clientes', 'proceso_masivo_id', 'cliente_id')
		->withTimestamps()
		->withPivot(array_keys(ProcesosMasivosCliente::fields));
	}

	public function entradas(){
		return $this->hasMany('App\Models\ProcesosMasivosCliente','proceso_masivo_id', 'id');
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

	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
