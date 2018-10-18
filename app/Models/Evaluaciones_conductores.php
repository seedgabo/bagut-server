<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluaciones_conductores extends Model
{
	use CrudTrait;
	use softDeletes;
     use \Venturecraft\Revisionable\RevisionableTrait;


     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
	const estados = ['aprobado' => 'Aprobado','reprobado' => 'Reprobado','prorroga' => 'Prorroga'];
	protected $table = 'evaluaciones_conductores';
	protected $primaryKey = 'id';
	protected $fillable = ['conductor_id', 'evaluacion_id', 'puntaje', 'estado', 'accion', 'nota', 'fecha_evaluacion', 'fecha_proxima','<archivo_id></archivo_id>'];
	protected $dates = ['fecha_evaluacion','fecha_proxima'];

	public function getArchivoLinkAttribute()
	{
		if($this->archivo)
			return '<a class="btn btn-success btn-sm" href="'. $this->archivo->url .'"> <i class="fa fa-download"></i> descargar </a>';
		else
			return "No Posee";
	}

    public function archivo ()
    {
    	return $this->belongsTo('\App\Models\Archivos','archivo_id','id');
    }

    public function conductor()
    {
    	return $this->belongsTo('\App\Models\Conductores','conductor_id','id');
    }

    public function Evaluacion()
    {
    	return $this->belongsTo('\App\Models\Evaluacion','evaluacion_id','id');
    }
}
