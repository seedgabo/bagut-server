<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conductor extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;

     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'conductores';
	protected $primaryKey = 'id';
	protected $fillable = ['cedula', 'nombres', 'apellidos', 'email', 'nacimiento', 'sangre', 'sexo', 'estadoCivil', 'telefono', 'nota', 'direccion', 'ingreso', 'egreso', 'eps_id', 'arl_id', 'fondosDePensiones_id', 'ibc'];
	protected $dates = ['nacimiento','egreso','ingreso'];

	public function getFullNameCedulaAttribute()
	{
		return $this->nombres . " " . $this->apellidos . "-"  . $this->cedula;
	}

	public function getFullNameAttribute()
	{
		return $this->nombres . " " . $this->apellidos;
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

	public function archivos()
	{
	    return $this->belongsToMany('App\Models\Archivos','archivo_conductor', 'conductor_id','archivo_id');
	}

	public function evaluaciones()
	{
		return $this->hasMany('\App\Models\EvaluacionConductor','conductor_id','id');
	}

	public function getFoto()
	{
		if ($this->foto != null && $this->foto != "")
		{
			return '<img  src=' .asset("/img/conductores/" . $this->foto). ' style="width:45px; height:45px"/>' ;
		}
		else
		{
			return '<img  src=' .asset("/img/user.jpg"). ' style="width:45px; height:45px"/>' ;
		}
	}

	public function getFotoUrlAttribute($value)
	{
		if ($this->foto != null && $this->foto != "")
		{
			return asset("/img/conductores/" . $this->foto);
		}
		else
		{
			return $url = asset('/img/user.jpg');
		}
	}

	public function verDetallesButton()
	{
		return '<a href="'. url('admin/ver-conductor/'. $this->id) .'" class="btn btn-warning btn-xs"> <i class="fa fa-info"></i> Ver Detalles </a>';
	}


}
