<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Combo;
use App\Models\Image;
use App\Models\Producto;
class ImagesController extends ApiController
{
	public $model = "\App\Models\Image";

	public function uploadResource(Request $request, $resource, $id)
	{
        if($resource == 'producto'){
            return $this->uploadImage($request,$id);
        }
		$class = "\App\Models\\" . title_case($resource);
		if ($resource === "user")
			$class = "\App\User";
		$model = $class::findorFail($id);
		$base64_str = substr($request->image, strpos($request->image, ",") + 1);
		$data = base64_decode($base64_str);
		$image = \App\Models\Image::create([
			'name' => $model->name . " imagen",
			'url' => 'img/images/',
			'path' => public_path('img/images/')
		]);
		file_put_contents(public_path('img/images/' . $image->id . ".jpg"), $data);
		$image->url = url('img/images/' . $image->id . ".jpg");
		$image->path = public_path('img/images/' . $image->id . ".jpg");
		$image->save();
		$model->image_id = $image->id;
		// app(\Spatie\ImageOptimizer\OptimizerChain::class)->optimize(public_path('img/images/' . $image->id . ".jpg"));
		$model->save();
		return response()->json(['resource' => $model, 'image' => $image]);
	}

	public function getBase64(Request $request, $id)
	{
		$path = \App\Models\Image::find($id)->path;
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		return response($data, 200)->header('Content-Type', 'image/jpeg');
		return $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    public function uploadImage(Request $request, $id){
    	$producto  = Producto::findorFail($id);
    	$file = $request->file("image");
        $image = Image::create(['title' => $producto->name . ".jpg"]);
        $base64_str = substr($request->image, strpos($request->image, ",") + 1);
		$data = base64_decode($base64_str);
        $image->url = url('img/productos/' . $image->id . ".jpg");
        $image->save();
        $producto->images()->attach($image);
        $producto->image_id = $image->id;
        $producto->save();
        file_put_contents(public_path('img/productos/' . $image->id . ".jpg"), $data);
        return response()->json(["url" => $image->url, 'id' => $image->id,'image' => $image]);
    }

    public function defaultImage(Request $request, $producto_id, $image_id){
    	$producto  = Producto::findorFail($producto_id);
    	$producto->image_id =  $image_id;
    	$producto->save();
    	return back();		
    }
}
