<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class HistoriaClinica extends Model
{
	use CrudTrait;
	use softDeletes;
    use \Venturecraft\Revisionable\RevisionableTrait;



	protected $table = 'historias_clinicas';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ['id', 'paciente_id', 'medico_id', 'cie10_id', 'fecha', 'ingreso', 'egreso', 'motivo_de_consulta', 'enfermedad_actual', 'revision_por_sistema', 'patologicos', 'quirurjicos', 'farmacologicos', 'traumaticos', 'inmunologicos', 'familiares', 'hospitalarios', 'toxico_alergicos', 'ginecobstreticos', 'frecuencia_cardiaca', 'frecuencia_respiratoria', 'tension_arterial', 'temperatura', 'peso', 'talla', 'aspecto_general', 'cabeza_cuello', 'orl', 'cardio_pulmonar', 'abdomen', 'extremidades', 'piel', 'neurologico', 'notas', 'analisis',]; // protected $hidden = [];
    protected $dates = ['fecha','deleted_at'];


    public function medico()
    {
    	return $this->belongsTo('App\User','medico_id','id');
    }


    public function paciente()
    {
    	return $this->belongsTo('\App\Models\Paciente','paciente_id','id');
    }

    public function cie10()
    {
    	return $this->belongsTo('App\Models\Cie10','cie10_id','id');
    }

    public function getButtonVerPaciente(){
       return '<a href="'. url('admin\pacientes?paciente_id='. $this->paciente_id) .'" class="btn btn-xs btn-info"><i class="fa fa-user-md"></i> Ver paciente </a>';
    }

    public function getButtonVerDetalles()
    {
      return '<a href="'. url('admin\ver-historia-clinica/'. $this->id) .'" class="btn btn-xs btn-warning"><i class="fa fa-list"></i> Ver Detalles</a>';
    }    


}
