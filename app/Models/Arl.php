<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Arl extends Model
{
	use CrudTrait;

     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'arl';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ['nombre','descripcion','icono'];
	// protected $hidden = [];
    // protected $dates = [];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/

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

	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
