<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
	use CrudTrait;

	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;

	protected $table = 'invoices';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	protected $guarded = ['id'];
	protected $fillable = ["cliente_id", "user_id", "resolucion", "regimen", "cabecera", "fecha", "vencimiento", "cliente_data", "direccion", "estado", "visto", "nota", "pie", "items", "archivo_id", "orden_compra"]; 

	// protected $hidden = [];
    protected $dates = ["fecha","vencimiento"];
    protected $appends = ["sub_total","total"];
    protected $casts = [
    	// "items" => "array",
    	"visto" => "boolean",
    ];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public function getButtonVer(){
		return '<a class="btn btn-xs btn-success" href="' . url("admin/ver-invoice/". $this->id) . '"><i class="fa fa-eye"></i> Ver </a>';
	}

	public function getSubTotalAttribute(){
		$sum = 0;
		foreach (json_decode($this->items) as $item) {
			$item->iva = isset($item->iva) ? $item->iva : 19;
			$item->descuento = isset($item->descuento)?$item->descuento : 0;

			$sum += intval($item->precio) * intval($item->cantidad);
		}
		return $sum;
	}

	public function getTotalAttribute(){
		$sum = 0;
		foreach (json_decode($this->items) as $item) {
			$item->iva = isset($item->iva) ? $item->iva : 19;
			$item->descuento = isset($item->descuento)?$item->descuento : 0;

			$sum += intval($item->precio  +$item->precio * $item->iva/100) * intval($item->cantidad)  - $item->descuento;
		}
		return $sum;
	}
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function cliente(){
		return $this->belongsTo("\App\Models\Cliente","cliente_id","id");
	}

	public function user(){
		return $this->belongsTo("\App\User","user_id","id");
	}

	public function archivo(){
		return $this->belongsTo("\App\User","archivo_id","id");
	}
	
	public function Pedido(){
		return $this->hasOne("\App\Models\Pedido","pedido_id","id");
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
