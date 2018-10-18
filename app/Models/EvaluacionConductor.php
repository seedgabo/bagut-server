<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluacionConductor extends Model
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
	protected $fillable = ['conductor_id', 'evaluacion_id', 'puntaje', 'estado', 'accion', 'nota', 'fecha_evaluacion', 'fecha_proxima','archivo_id','respuestas'];
	protected $dates = ['fecha_evaluacion','fecha_proxima'];
	protected $casts = ['respuestas' => 'array'];


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
    	return $this->belongsTo('\App\Models\Conductor','conductor_id','id');
    }

    public function Evaluacion()
    {
    	return $this->belongsTo('\App\Models\Evaluacion','evaluacion_id','id');
    }

	public function verDetallesButton()
	{
		return '<a href="'. url('admin/ver-evaluacion-conductor/'. $this->id) .'" class="btn btn-warning btn-xs"> <i class="fa fa-info"></i> Ver Detalles </a>';
	}	
}
