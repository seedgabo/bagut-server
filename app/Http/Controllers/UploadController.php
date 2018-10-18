<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
  public function  cargarImagenes(Request $request, $id)
  {
   $empresa = \App\Empresas::find($id);
   $imagen = $request->file('imagenes');
   $imagen->move(public_path() ."/img/".$id ."/productos", $imagen->getClientOriginalname());
   if ($imagen->getClientOriginalExtension() == "zip")
   {
    $zip = (new \Chumper\Zipper\Zipper)->make(public_path() ."/img/".$id ."/productos/" . $imagen->getClientOriginalname());
    if ($zip) {
      $zip
      ->extractTo(public_path() ."/img/".$id ."/productos/");
      return "true";
    }
    else
    {
      return "error";
    }   
  }
  if ($imagen->getClientOriginalExtension() == "jpg")
    return "true";
  else
    return "el archivo no esta en el formato correcto";
}

  public function cargarArchivoProveedor(Request $request, $id)
  {
   $archivo = $request->file("archivo");
   $nombre = $archivo->getClientOriginalName();
   $entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
   $archivo->move(storage_path("app/archivos/"), $entrada->id);
   DB::table("archivo_proveedor")->insert(["archivo_id" => $entrada->id, 'proveedor_id' => $id]);
   \Alert::success("archivo Cargado Correctamente")->flash();
   return back();
  }

  public function cargarArchivoVehiculo(Request $request, $id)
  {
    $archivo = $request->file("archivo");
    $nombre = $archivo->getClientOriginalName();

    $entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
    $archivo->move(storage_path("app/archivos/"), $entrada->id);

    DB::table("archivo_vehiculo")->insert(["archivo_id" => $entrada->id, 'vehiculo_id' => $id]);
    \Alert::success("archivo Cargado Correctamente")->flash();
    return back();
  }

  public function cargarArchivoConductor(Request $request, $id)
  {
    $archivo = $request->file("archivo");
    $nombre = $archivo->getClientOriginalName();

    $entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
    $archivo->move(storage_path("app/archivos/"), $entrada->id);

    DB::table("archivo_conductor")->insert(["archivo_id" => $entrada->id, 'conductor_id' => $id]);
    \Alert::success("archivo Cargado Correctamente")->flash();
    return back();
  }


  public function loadExcel(Request $request)
  {
    return view("excel.form-upload");
  }

  public function seeExcel(Request $request)
  {
    $request->file("file")->move(storage_path(), "file.xls");
    return Excel::load(storage_path("file.xls"), function($reader) {
      return $reader->get();
    })->get();
  }
}
