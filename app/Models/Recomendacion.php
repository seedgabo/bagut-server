<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class Recomendacion extends Model
{
	use CrudTrait;
	use softDeletes;
    use \Venturecraft\Revisionable\RevisionableTrait;


     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'recomendaciones';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ['titulo','descripcion','caso_id','user_id'];
	// protected $hidden = [];
    protected $dates = ['deleted_at'];

    public function user()
    {
    	return $this->belongsTo('\App\User','user_id','id');
    }

    public function caso()
    {
    	return $this->belongsTo('\App\Models\casos_medicos','caso_id','id');
    }

    public function getButtonVerCaso(){
     return '<a href="'. url('admin\ver-caso\\' . $this->caso_id) .'" class="btn btn-xs btn-info"><i class="fa fa-file-code-o"></i> Ver Caso </a>';
    }

}
