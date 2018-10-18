<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class Archivos extends Model
{
	use CrudTrait;
	use softDeletes;
     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'archivos';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ['nombre','descripcion','tipo','paginas'];
	// protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['url'];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public function url(){
		return url("archivo/" . $this->id);
	}
	
	public function getUrlAttribute(){
		return url("archivo/" . $this->id);
	}

	public function verArchivo(){
		return '<a href="'. $this->url . '" class="btn btn-xs btn-warning" target="_blank"><i class="fa fa-download"></i> Ver Archivo </a>';
	}
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/

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
	public function getTipoAttribute($value){
		if(!isset($value)){
			 return substr(strrchr($this->nombre,'.'),1);
		}else{
			return $value;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
