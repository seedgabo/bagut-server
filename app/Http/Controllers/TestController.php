<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\ProcesosMasivosCliente;
use App\Models\Producto;
use App\Models\Tickets;
use App\User;
use Carbon\Carbon;
use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
  public function test(Request $request){
    return view("test.cargar-excel");
  }
  
  public function test2(Request $request){
    $proceso  = \App\Models\ProcesoMasivo::findOrFail($request->input('proceso_masivo_id'));
    $clientes  = \App\Models\Cliente::whereIn('nit',explode(",",$request->input('clientes_ids')))->get();
    $archivo = $request->file('archivo');
    $nombre =  $request->file('archivo')->getClientOriginalName();
    
    $paginas = \App\Funciones::getPDFPages($archivo);
    $entrada = \App\Models\Archivos::create(['nombre' => $nombre, 'paginas' => $paginas]);
    $archivo->move(storage_path("app/archivos/"), $entrada->id);
    
    foreach($clientes as $cliente){
      DB::table("archivos_procesosMasivos")->insert(["archivo_id" => $entrada->id, 'cliente_id' => $cliente->id, "procesoMasivo_id" => $proceso_id]);
    }
    
    Session::flash("success","Archivo subido correctamente");
    return back();
  }
  

  public function test3(Request $request, $proceso_id){
    $files = Storage::files();
    $resultados = [];
    foreach ($files as $file) {
      $resultados[]= $this->procesarArchivo($file,$file ,$proceso_id);
    }
    return response()->json(collect($resultados)->groupBy('status'));
  }
  
  
  public function test4(Request $request){
    $cupon = \App\Models\Cupon::create(['pedido_id' => \App\Models\Pedido::first()->id, 'code' => '1234']);
    
    return $cupon->load('pedido.cliente','pedido.user','pedido.vendedor');
  }
  
  public function test5(Request $request){
    $this->cargarClientesAProcesoConEntidad($request);
  }

  public function test6(Request $request){
    $fields =\App\Models\ProcesosMasivosCliente::fields;
    $list = [];
    $fields= collect($fields);
    foreach ($fields as $key => $field){
      if(array_key_exists('label', $field))
        $list[$key] = $field['label'];
    };
    return $list;
    return Excel::create('variables', function($excel) use($list) {

        $excel->sheet('nombres', function($sheet) use($list) {

            $sheet->fromArray($list);

        });

    })->export('xls');
  }
  

  
  
  
  private function procesarArchivo($file,$nombre,$proceso_id){
    if((strrpos($nombre, "(") !== false) && (strrpos($nombre, ")") !== false))
                                                                        {
      $cedula = substr($nombre , strrpos($nombre, "(") +1, strrpos($nombre, ")") - strrpos($nombre, "(")-1);
      $cliente = \App\Models\Cliente::where("cedula",$cedula)->orWhere("nit",$cedula)->first();
      
      $procesoMasivo = \App\Models\ProcesoMasivo::find($proceso_id);
      $originalname = $nombre;
      $nombre =str_replace(substr($nombre , strrpos($nombre, "("), strrpos($nombre, ")") - strrpos($nombre, "(")+1), "",$nombre);
      $paginas = \App\Funciones::getPDFPages(storage_path("app/".$file));
      
      $entrada = \App\Models\Archivos::create(['nombre' => $nombre, 'paginas' => $paginas]);
      
      
      if(!isset($cliente)){
        Storage::copy($file, 'archivos/'.$entrada->id);
        $this->saveDocumentosinCliente($file,$originalname);
        return [  
		'status' => 'error',
        	'message' => "El cliente con cedula/nit: ". $cedula ." no existe para el archivo: " . $nombre
        ];
      }
      
      Storage::move($file, 'archivos/'.$entrada->id);
      DB::table("archivos_procesosmasivos")->insert(["archivo_id" => $entrada->id, 'cliente_id' => $cliente->id, "procesoMasivo_id" => $procesoMasivo->id]);
      $procesoMasivo->clientes()->syncWithoutDetaching([$cliente->id]);
      return ["status" => "success" ,"message" => "Cargado a Cliente: ". $cliente->full_name, 'paginas' => $paginas];
    }
    else{
      $paginas = \App\Funciones::getPDFPages(storage_path("app/".$file));
      $entrada = \App\Models\Archivos::create(['nombre' => $nombre, 'paginas' => $paginas]);
      $this->saveDocumentosinFormato($file,$nombre);
      return [
	'status' => 'error',
	'message' => "El documento " . $nombre . " no posee la cedula en el formato correcto"
      ];
    }
  }
  
  private function saveDocumentosinCliente($file, $nombre){
    $data = ['titulo' => $nombre, 'descripcion' => "Documento sin cliente asociado o existente",'categoria_id' => '50'];
    $data['activo'] = true;
    $data['editable'] = false;
    
    $documento = \App\Models\Documentos::Create($data);
    //    File::delete(storage_path("documentos/". $documento->id));
    
    Storage::move($file, 'documentos/'. $documento->id);
    $documento->save();
    return $documento;
  }
  
  private function saveDocumentosinFormato($file, $nombre){
    $data = ['titulo' => $nombre, 'descripcion' => "El Documento no posee el formato correcto, la cedula del cliente debe ir entre parentesis",'categoria_id' => '51'];
    $data['activo'] = true;
    $data['editable'] = false;
    
    $documento = \App\Models\Documentos::Create($data);
    //    File::delete(storage_path("documentos/". $documento->id));
    
    Storage::move($file, 'documentos/'. $documento->id);
    $documento->save();
    return $documento;
  }
  
  private function cargarClientesAProcesoConEntidad(Request $request){
    $archivo = $request->file('archivo');
    $nombre_archivo =  $request->file('archivo')->getClientOriginalName();
    $valores = Excel::selectSheetsByIndex(0)->load($archivo, function($reader){
    }
    )->get()->toArray();
    //    return $valores;
    $proceso_id = $request->input("proceso_masivo_id");
    $entidad_id = $request->input("entidad_id");
    $res = [];
    $sinres = [];
    
    foreach ($valores as $valor) {
      $cliente = Cliente::where("nit",$valor['cedula'])->first();
      $aux= [];
      if(!array_key_exists('nombre',$valor) ||
         $valor['nombre'] == "" || $valor['nombre'] == null){
         continue;
      }
      if(!isset($cliente)){
        $cliente = Cliente::create
          ([
            'nombres' => $valor['nombre'],
            'cedula'  => $valor['cedula'],
            'nit'  => $valor['cedula']
          ]);
      }
      $aux["nombre"] = $valor['nombre'];
      $aux["proceso_masivo_id"] = $proceso_id;
      $aux["entidad_id"] = $entidad_id;
      
      $aux["cedula"] = $cliente->cedula;
      $aux['cliente_id'] = $cliente->id;
      
      if(isset($aux['fecha_llegada']))
                                                                                                              $aux['fecha_agregado'] = $valor["fecha_llegada"];
      
      
      foreach (\App\Models\ProcesosMasivosCliente::fields as $key => $field) {
        if (isset($valor[$key]) &&  $valor[$key] != "") {
          $aux[$key] = $valor[$key];
        }
      }
      
      
      $res[]= $aux;
    }
    
    //    return response()->json(["con_clientes" =>  $res, "sin_clientes" => $sinres]);
    $tosql = [];
    $i = 0;
    //    $today = Carbon::now();
    foreach ($res as $entrada) {
      $tosql[$i] = $entrada;
      unset($tosql[$i]['cedula']);
      unset($tosql[$i]['nombre']);
      //      $tosql[$i]['created_at'] = $today;
      //      $tosql[$i]['updated_at'] = $today;
      $dato = ProcesosMasivosCliente::where("proceso_masivo_id",intval($proceso_id))
                                                                                                              ->where("entidad_id", intval($entidad_id))
                                                                                                              ->where("cliente_id",$tosql[$i]['cliente_id'])->first();
      if($dato != null){
        $dato->fill($tosql[$i]);
        $dato->save();
      }
      else{
        ProcesosMasivosCliente::create($tosql[$i]);
      }
      $i++;
    }
    
    //    ProcesosMasivosCliente::insert($tosql);
    
    
    return Excel::create($nombre_archivo . " -PROCESADO", function($excel) use ($res,$sinres){
      
      $excel->sheet('clientes', function($sheet)use($res) {
        
        $sheet->fromArray($res);
        
      }
      );
      
      $excel->sheet('sin clientes', function($sheet)use($sinres) {
        
        $sheet->fromArray($sinres);
        
      }
      );
      
    }
    )->export('xls');
  }
}
