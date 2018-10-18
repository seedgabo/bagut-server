<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class Producto extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;
     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'productos';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	protected $guarded = ['id','categorias'];
	// protected $fillable = ['referencia', 'name', 'description', 'notas', 'stock', 'categoria_id', 'parent_id', 'precio', 'precio2', 'precio3', 'precio4', 'destacado', 'active', 'data', 'impuesto', 'image_id', 'disccount', 'es_vendible_sin_stock', 'mostrar_stock', 'user_id',]; 
	// // protected $hidden = [];
    // protected $dates = [];
    protected $appends =["image_html",'image'];
    // protected $casts =['data' => 'collection'];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public  function getImageHtmlAttribute(){
		if(isset($this->image))
			return '<img src="'. $this->image->url .'" style="width:65px;" class="img-thumbnail"/>';
		else
			return "Sin Imagen";
	}

	public function verDetallesButton(){
		return '<a class="btn btn-xs btn-warning" href="' .url("admin/ver-producto/". $this->id) .'"> <i class="fa fa-shopping-bag"></i> Ver Detalles </a>';
	}

	public function administrarImagenes(){
		return '<a class="btn btn-xs btn-info" href="' .url("admin/ver-producto/". $this->id ."/imagenes") .'"> <i class="fa fa-picture-o"></i>Administrar Imagenes </a>';
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function Categoria(){
		return $this->belongsTo("\App\Models\CategoriaProducto","categoria_id",'id');
	}

	public function Categorias(){
	  return $this->belongsToMany('App\Models\CategoriaProducto','categoria_producto' ,"producto_id","categoria_id");
	}

	public function Image(){
		return $this->belongsTo("\App\Models\Image","image_id",'id');
	}

	public function Images(){
		return $this->belongsToMany("\App\Models\Image","image_producto" ,"producto_id",'image_id');
	} 

	public function User(){
		return $this->belongsTo("\App\User","user_id",'id');
	}

	public function Parent()
	{
	  return $this->belongsTo(get_class(), 'parent_id');
	}



	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeActive($query){
		return $query->where("active","1");
	}
	public function scopeInactive($query){
		return $query->where("active","0");
	}
	public function scopeOutOfStock($query){
		return $query->where("stock","<=","0");
	}

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

	public function getValorAttributte(){
		return $this->precio * $this->impuesto / 100;
	} 

	public function getDisponibleAttribute(){
		if($this->active == true)
			return true;
		else if ($this->es_vendible_sin_stock == true && $this->stock > 0)
			return true;
		else 
			return false;
	}
	
	public function getDatosAttribute(){
		return collect(json_decode($this->data));	
	}
}
