<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluacion extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;

    const  tipos = ['1' => 'Proveedores','2' => 'Vehiculos', '3' => 'Conductores','4' => 'Personal'];  //1 : proveedores, 2: vehiculos, 3: conductores, 4: personal
	protected $table = 'evaluaciones';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];
	protected $fillable = ['nombre', 'descripcion', 'tipo', 'activo', 'opciones'];
	protected $casts = ['opciones' => 'array'];


	public function getTipo()
	{
		return  Evaluacion::tipos[$this->tipo];
	}

}
