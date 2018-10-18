<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;

	Const tipos = ['bien' => 'Bien' , 'servicio' => 'Servicio', 'bien_servicio' => 'Bien y Servicio'];
	protected $table = 'proveedores';
	protected $primaryKey = 'id';
	protected $fillable = ['nombre','descripcion','documento','bien_o_servicio','activo','direccion','email','telefono','ubicacion','tipo' ,'nota','fecha_ingreso','foto', 'fecha_egreso'];
	protected $dates = ['deleted_at','fecha_ingreso'];


	public function archivos()
	{
		return $this->belongsToMany('App\Models\Archivos','archivo_proveedor', 'proveedor_id','archivo_id');
	}

	public function getFoto()	
	{
		if ($this->foto != null && $this->foto != "")
		{
			return '<img  src=' .asset("/img/proveedores/" . $this->foto). ' style="width:45px; height:45px"/>' ;
		}
		else
		{
			return '<img  src=' .asset("/img/user.jpg"). ' style="width:45px; height:45px"/>' ;
		}
	}

	public function getFullNameAttribute($value)
	{
	  return $this->nombre;
	}

	public function getFullNameCedulaAttribute($value)
	{
	  return $this->nombre  . " - " . $this->documento;
	}


	public function getFotoUrlAttribute($value)
	{
		if ($this->foto != null && $this->foto != "")
		{
			return asset("/img/proveedores/" . $this->foto);
		}
		else
		{
			return $url = asset('/img/user.jpg');
		}
	}

	public function Invoices(){
		return $this->hasMany("App\Models\InvoiceProveedor",'proveedor_id','id');
	}

	public function evaluaciones()
	{
		return $this->hasMany('\App\Models\EvaluacionProveedor','proveedor_id','id');
	}

	public function verDetallesButton()
	{
		return '<a href="'. url('admin/ver-proveedor/'. $this->id) .'" class="btn btn-warning btn-xs"> <i class="fa fa-info"></i> Ver Detalles </a>';
	}


}
