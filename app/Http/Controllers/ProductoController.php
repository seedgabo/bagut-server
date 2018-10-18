<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function verProducto(Request $request,$id){
        $producto = Producto::find($id);
        return view("productos.ver-producto")->withProducto($producto)->withTitle($producto->name);
    }

    public function verProductoImagenes(Request $request, $id){
    	$producto  = Producto::find($id);
    	$images = $producto->images;

    	return view("productos.editImages")->withProducto($producto)->withImages($images);
    }

    public function uploadImage(Request $request, $id){
    	$producto  = Producto::findorFail($id);
    	$file = $request->file("image");

        $image = Image::create(['title' => $file->getClientOriginalName()]);
        $image->url = url('img/productos/' . $image->id . "." . $file->getClientOriginalExtension());
        $image->save();
        $producto->images()->attach($image);
        $file->move(public_path("img/productos/"), $image->id . "." . $file->getClientOriginalExtension());
        return response()->json(["url" => $image->url, 'id' => $image->id]);
    }

    public function defaultImage(Request $request, $producto_id, $image_id){
    	$producto  = Producto::findorFail($producto_id);
    	$producto->image_id =  $image_id;
    	$producto->save();
    	return back();		
    }
}
