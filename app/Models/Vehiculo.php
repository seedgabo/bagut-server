<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiculo extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;

	protected $table = 'vehiculos';
	protected $primaryKey = 'id';
	protected $fillable = ['modelo','placa', 'marca', 'linea', 'tipo', 'color', 'motor', 'chasis','cilindraje' , 'direccion', 'uso', 'licencia_transito', 'dueño', 'fecha_ingreso', 'activo', 'nota','foto']; 
	protected $dates = ['fecha_ingreso'];

	public function getNombreAttribute()
    {
		return $this->marca . " " . $this->modelo . " - " . $this->placa;
	}
	public function getFullNameAttribute()
    {
		return $this->marca . " " . $this->modelo . " - " . $this->placa;
	}

	public function archivos()
	{
		return $this->belongsToMany('App\Models\Archivos','archivo_vehiculo', 'vehiculo_id','archivo_id');
	}

	public function evaluaciones()
	{
		return $this->hasMany('\App\Models\EvaluacionProveedor','proveedor_id','id');
	}

	public function getFoto()	
	{
		if ($this->foto != null && $this->foto != "")
		{
			return '<img  src=' .asset("/img/vehiculos/" . $this->foto). ' style="width:45px; height:45px"/>' ;
		}
		else
		{
			return '<img  src=' .asset("/img/vehiculo.jpg"). ' style="width:45px; height:45px"/>' ;
		}
	}




	public function getFotoUrlAttribute($value)
	{
		if ($this->foto != null && $this->foto != "")
		{
			return asset("/img/vehiculos/" . $this->foto);
		}
		else
		{
			return $url = asset('/img/user.jpg');
		}
	}
	
	public function verDetallesButton()
	{
		return '<a href="'. url('admin/ver-vehiculo/'. $this->id) .'" class="btn btn-warning btn-xs"> <i class="fa fa-info"></i> Ver Detalles </a>';
	}

}