<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class PedidoProducto extends Model
{
	use CrudTrait;

     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'pedido_producto';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	protected $guarded = ['id'];
	protected $appends =["image_html"];
	// protected $fillable = [];
	// protected $hidden = [];
    // protected $dates = [];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public  function getImageHtmlAttribute(){
		if($this->image !==null)
			return '<img src="'. $this->image->url .'"> style="width:55px;" class="img-thumbnail"/>';
		else
			return "Sin Imagen";
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function Pedido(){
		return $this->belongsTo('\App\Models\Pedido',"pedido_id","id");
	}
	public function Producto(){
		return $this->belongsTo('\App\Models\Producto',"producto_id","id");
	}

	public function Image(){
		return $this->belongsTo("\App\Models\Image","image_id",'id');
	}

	public function Images(){
		return $this->belongsToMany("\App\Models\Image","image_producto" ,"product_id",'image_id');
	} 


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
