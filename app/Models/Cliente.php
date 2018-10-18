<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Laravel\Scout\Searchable;
class Cliente extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;

	protected $table = 'clientes';
	protected $primaryKey = 'id';
	protected $fillable = ['id', 'user_id', 'cedula', 'nit', 'nombres', 'apellidos', 'email', 'nacimiento', 'sexo', 'estadoCivil', 'telefono', 'foto', 'nota', 'direccion', 'ingreso', 'egreso', 'condiciones', 'eps_id', 'arl_id', 'fondosDePensiones_id', 'ibc','categoria_id_procesos','categoria_id_consultas'];
	protected $dates = [];
	protected $appends = ["full_name","full_name_cedula","foto_url"];

	public function getFullNameAttribute($value)
	{
		return $this->nombres  . " " . $this->apellidos;
	}

	public function getFullNameCedulaAttribute($value)
	{
		return $this->nombres  . " " . $this->apellidos . " - " . (isset($this->nit)?  $this->nit : $this->cedula);

	}

	public function getFotoUrlAttribute($value)
	{
		if ($this->foto != null && $this->foto != "")
		{
			return asset("/img/clientes/" . $this->foto);
		}
		else
		{
			return $url = asset('/img/user.jpg');
		}
	}

	public function eps()
	{
		return $this->belongsTo('\App\Models\Eps','eps_id');
	}

	public function arl()
	{
		return $this->belongsTo('\App\Models\Arl','arl_id');
	}

	public function fondo()
	{
		return $this->belongsTo('\App\Models\FondosDePensiones','fondosDePensiones_id');
	}

	public function procesos()
	{
		return $this->hasMany('\App\Models\Proceso');
	}

	public function consultas()
	{
		return $this->hasMany('\App\Models\Consulta');
	}

	public function Facturas()
	{
		return $this->hasMany('\App\Models\Invoice');
	}

	public function tickets()
	{
		return $this->hasMany("\App\Models\Tickets","cliente_id","id");
	}

	public function procesosMasivos()
	{
		return $this->belongsToMany('App\Models\ProcesoMasivo', 'procesos_masivos_clientes', 'cliente_id', 'proceso_masivo_id')
		->withTimestamps()
		->withPivot(array_keys(ProcesosMasivosCliente::fields));
	}

	public function getProcesosCountAttribute()
	{
		return $this->procesos()->count();
	}

	public function getProcesosMasivosCountAttribute()
	{
		return $this->procesos()->count();
	}

	public function ticketsMasivos(){
		return $this->belongsToMany('App\Models\Tickets','cliente_ticket','cliente_id','ticket_id');
	}


	public function user()
	{
		return $this->belongsTo('\App\User','user_id','id');
	}

	public function pedidos(){
		return $this->hasMany('App\Models\Pedido','pedido_id','id');
	}

	public function puesto()
	{
		return $this->belongsTo('\App\Models\Puesto','puesto_id','id');
	}

	public function archivos()
	{
		return $this->belongsToMany('App\Models\Archivos','archivos_clientes', 'cliente_id','archivo_id');
	}


	public function archivosProcesosMasivos()
	{
		return $this->belongsToMany('App\Models\Archivos','archivos_procesosmasivos', 'cliente_id','archivo_id')
		->withTimestamps()
		->withPivot(['procesoMasivo_id']);
	}
	


	public function getButtonVerProcesos()
	{
		return '<a href="'. url('admin\procesos?cliente_id='. $this->id) .'" class="btn btn-xs btn-outline btn-info"><i class="fa fa-file-text"></i> Ver Procesos </a>';
	}

	public function getButtonVerDetalles()
	{
		return '<a href="'. url('admin\ver-cliente\\' . $this->id) .'" class="btn btn-xs btn-outline btn-warning"><i class="fa fa-user"></i> Ver Detalles </a>';
	}

	public function getButtonVerConsultas()
	{
		return '<a href="'. url('admin/consultas?cliente_id=' . $this->id) .'" class="btn btn-xs btn-outline btn-info"><i class="fa fa-files-o"></i> Ver Consultas </a>';
	}
}
