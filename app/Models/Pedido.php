<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pedido extends Model
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
	const estados = [
	'Pedido'=>'Pedido',
	'Enviado' => 'Enviado',
	'Entregado' => 'Entregado',
	'Pagado' => 'Pagado',
	'cancelado' => 'Cancelado',
	'Reembolso' => 'Reembolso',
	'Error en el Pago' => 'Error en el Pago',
	'Retrasado' => 'Retrasado',
	'Parcialmente Enviado' => 'Parcialmente Enviado',
	'Desconocido' => 'Desconocido'
	];
	protected $table = 'pedidos';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	protected $guarded = ['id','facturar','items'];
	// protected $fillable = ["id","numero_pedido" ,"pedido_id", "producto_id", "image_id", "notas", "cantidad_pedidos", "cantidad_despachado", "precio", "total", "iva", "descuento", "fecha_entrega", "fecha_envio", "fecha_pedido","items"]; 

	protected $dates = ['fecha_entrega',
	'fecha_envio','fecha_pedido'];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public function calculate(){
		$total = 0;
		$subtotal = 0;
		$descuento = 0;
		foreach ($this->items as $key => $item) {
			$total += $item->total;
			$total += $item->subtotal;
			$descuento += $item->descuento;
		}
		$this->subtotal = $subtotal;
		$this->descuento = $descuento;
		$this->total = $total;
	}

	public function saveItems($productos){
		$items = [];
		foreach ($productos as $producto) {
			if(!array_key_exists("producto_id", $producto)){
				$producto["producto_id"] = $producto["id"];
			}
			$item = \App\Models\PedidoProducto::
			firstorNew(["producto_id" => $producto["producto_id"],"pedido_id" => $this->id]);

			$item->image_id = $producto["image_id"];
			$item->name = $producto["name"];
			$item->referencia = $producto["referencia"];
			$item->notas = isset($producto["notas"]) ? $producto["notas"]  : "";
			if (isset($item->id)) {
				$productoAsoc = $item->producto;
				if(isset($productoAsoc)){
					$productoAsoc->stock += $item->cantidad_pedidos;
					$productoAsoc->stock -= $producto["cantidad_pedidos"];
					$productoAsoc->save();
				}
			}
			else{
				$productoAsoc = \App\Models\Producto::find($producto["producto_id"]);
				if(isset($productoAsoc)){
					$productoAsoc->stock -= $producto["cantidad_pedidos"];
					$productoAsoc->save();
				}
			}
			$item->cantidad_pedidos = $producto["cantidad_pedidos"];
			$item->precio = $producto["precio"];
			$item->iva = $producto["iva"];
			$item->descuento = $producto["descuento"];
			$item->total = ($producto["precio"]* $producto["cantidad_pedidos"])*(1 + $producto["iva"]) - $producto["descuento"];
			
			if(isset($producto["cantidad_despachado"]))
				$item->cantidad_despachado = $producto["cantidad_despachado"];


			if(isset($producto["fecha_pedido"]))
				$item->fecha_pedido = $producto["fecha_pedido"];
			else
				$item->fecha_pedido = Carbon::now();

			if(isset($producto["fecha_entrega"]))
				$item->fecha_pedido = $producto["fecha_entrega"];
			else
				$item->fecha_entrega = Carbon::now();


			if(isset($producto["fecha_envio"]))
				$item->fecha_pedido = $producto["fecha_envio"];
			else
				$item->fecha_envio = Carbon::now();

			
			$item->save();
			$items[] = $item;
 		}

		return 	$items;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function Cliente(){
		return $this->belongsTo("\App\Models\Cliente",'cliente_id','id');
	}
	public function User(){
		return $this->belongsTo("\App\User",'user_id','id');
	}

	public function Items(){
		return $this->hasMany('\App\Models\PedidoProducto','pedido_id','id');
	}

	public function Productos(){
		return $this->BelongsToMany('\App\Models\Producto','pedido_producto','pedido_id','producto_id');
	}
	public function Invoice(){
		return $this->belongsTo('\App\Models\Invoice','invoice_id','id');
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

	public function getFullNameAttribute(){
		return $this->numero_pedido + " - " + $this->cliente->full_name;
	}

	public function getItemsCountAttribute(){
		return $this->Items()->count();
	}
	public function  getListNameItemsAttribute(){
		return implode($this->items->pluck("name")->toArray(),", ");
	}

	public function getTotalAttribute(){
		return $this->items()->sum("total");
	}
	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
	public function setItemsAttribute($items){
		$this->items()->delete();
		return $this->saveItems($items);
	}

	public function Facturar(){
		$invoice = $this->invoice;
		if(isset($invoice)){
			
		}else{
			 $invoice = new \App\Models\Invoice;
		}
		 $invoice->cliente_id = $this->cliente_id;
		 $invoice->user_id = $this->user_id;
		 $invoice->fecha = $this->fecha_pedido;
		 $invoice->vencimiento = $this->fecha_pedido->addDays(30);
		 $invoice->direccion = $this->direccion_facturado;
		 $invoice->estado = $this->estado;
		 foreach ($this->items as $item) {
		 	$item["cantidad"] = $item["cantidad_pedidos"];
		 }
		 $invoice->items = $this->items;
		 $invoice->orden_compra = $this->referencia;
		 $invoice->save();
		 
		 $this->invoice_id = $invoice->id;
		 $this->save();
	}
}
