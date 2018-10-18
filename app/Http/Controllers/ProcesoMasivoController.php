<?php

namespace App\Http\Controllers;

use App\Models\CategoriasTickets;
use App\Models\Cliente;
use App\Models\ProcesoMasivo;
use App\Models\ProcesosMasivosCliente;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
class ProcesoMasivoController extends Controller
{
    public function verproceso(Request $r, $id, $export = false)
    {
        $busqueda = $r->input("search","");
        $entidad_id = $r->input("entidad_id");
        $procesoMasivo = ProcesoMasivo::findorFail($id);
        $entradas = $procesoMasivo->entradas()
        ->select("procesos_masivos_clientes.*")
        ->rightJoin('clientes',"procesos_masivos_clientes.cliente_id","clientes.id")
        ->with('entidad','cliente')
        ->where(function($q) use($busqueda){
            $q->orWhere("clientes.nombres","LIKE","%". $busqueda ."%")
            ->orWhere("clientes.apellidos","LIKE","%". $busqueda ."%")
            ->orWhere("clientes.nit","LIKE","%". $busqueda ."%")
            ->orWhere("clientes.cedula","LIKE","%". $busqueda ."%");
        });
        
        if ($entidad_id != null) {
            $entradas = $entradas->whereIn("procesos_masivos_clientes.entidad_id",$entidad_id);
        }

        if($r->exists('query') && strlen($r->input('query'))!= 0 ){
            $entradas = $entradas->where($r->input('filtro'),"LIKE","%".$r->input('query')."%");
        }

        if($r->exists('order') && strlen($r->input('order'))!= 0 ){
            $entradas = $entradas->orderBy("procesos_masivos_clientes." .$r->input('order'),$r->input('order_type','asc'))->where("procesos_masivos_clientes." .$r->input('order'),"<>","");
        }

        $clientes = $entradas->with('cliente')->get()->pluck('cliente');
        if (!$export) {
            $entradas = $entradas->paginate($r->input('paginate',25));
        }else{
            $entradas = $entradas->get();
        }
        $entidades = $procesoMasivo->entradas()->with('entidad')->get()->pluck("entidad")->unique();

        if ($export) {
            $data = [];
            $i = 0;
            foreach ($entradas as $entrada) {
                $aux = [];
		if(!isset($entrada->cliente))
			continue;
		if($entrada->cliente->cedula != "")
	                $aux['cedula'] = $entrada->cliente->cedula;
		else
			$aux['cedula'] = $entrada->cliente->nit;
                $aux['poderdante'] = isset($entrada->cliente)? $entrada->cliente->full_name : '';
                $aux['proceso'] = $procesoMasivo->titulo;
                $aux['entidad'] = isset($entrada->entidad)? $entrada->entidad->name : '';
                foreach (ProcesosMasivosCliente::fields as $key => $value) {
                    if (isset($value['label'])) {
                        $aux[$value['label']] = $entrada->{$key};
                    }
                }
                $data[$i++]= $aux;
            }
            Excel::create($procesoMasivo->titulo, function($excel)  use ($data){
                $excel->sheet('Proceso', function($sheet) use ($data) {
                    $sheet->fromArray($data);

                });
            })->download('xls');

        }

        return view('procesosMasivos.ver-proceso')
        ->withProceso($procesoMasivo)
        ->withEntradas($entradas)
        ->withEntidades($entidades)
        ->withClientes($clientes);
    }


    public function verprocesoExcel(Request $r ,$id){
        return $this->verproceso($r,$id,true);
    }

    public function verDocumentosProceso(Request $r, $id)
    {
        $procesoMasivo = ProcesoMasivo::findorFail($id);
        $archivosporCliente = $procesoMasivo->archivos->groupBy('pivot.cliente_id');
        // return $archivos;
        return view('procesosMasivos.ver-documentos-proceso')
        ->withProceso($procesoMasivo)
        ->with('archivosporCliente',$archivosporCliente);
    }

    public function verDocumentosClienteProcesos(Request $r, $id)
    {
        $cliente = Cliente::findorFail($id);
        $archivosporProceso = $cliente->archivosProcesosMasivos->groupBy('pivot.procesoMasivo_id');
        return view('procesosMasivos.ver-documentos-clientes-procesos')
        ->withCliente($cliente)
        ->with('archivosporProceso',$archivosporProceso);
    }

    public function addClientetoProceso(Request $request){
        $entrada = ProcesosMasivosCliente::where("cliente_id", $request->input('cliente_id'))
        ->where("proceso_masivo_id", $request->input('proceso_masivo_id'))->with('entidad','cliente','ProcesoMasivo')->first();
        if (isset($entrada)) {
            return response()->json(['status' => 'exists' ,'data' => $entrada]);
        }else{
            $entrada = ProcesosMasivosCliente::create($request->all());
            $entrada = ProcesosMasivosCliente::where("cliente_id", $request->input('cliente_id'))
            ->where("proceso_masivo_id", $request->input('proceso_masivo_id'))->with('entidad','cliente','ProcesoMasivo')->first();
            return response()->json(['status' => 'success' ,'data' => $entrada]);
        }
    }




    public function ArchivosMasivosClientes(Request $r)
    {
        if(false)
        {
            abort(403, 'No tiene permisos para esta acción');
        }

        $clientes = \App\Models\Cliente::all()->pluck("full_name_cedula","id");
        $procesoMasivos = \App\Models\ProcesoMasivo::all()->pluck("titulo","id");
        return view('procesosMasivos.archivos-masivos')->withClientes($clientes)->withProcesos($procesoMasivos);
    }


    public function CargarArchivosProcesosMasivos(Request $request)
    {
        if(!Auth::user()->canAny(['Agregar Clientes', 'Editar Clientes']) &&  !Auth::user()->hasRole('SuperAdmin'))
        {
            abort(403, 'No tiene permisos para esta acción');
        }

        
        $archivo = $request->file("archivo");
        $nombre = $archivo->getClientOriginalName();
        if((strrpos($nombre, "(") !== false) && (strrpos($nombre, ")") !== false))
        {
            $cedula = substr($nombre , strrpos($nombre, "(") +1, strrpos($nombre, ")") - strrpos($nombre, "(")-1);
            $cliente = \App\Models\Cliente::where("cedula",$cedula)->orWhere("nit",$cedula)->first();
            $procesoMasivo = \App\Models\ProcesoMasivo::find($request->input('proceso_masivo_id'));
            
            $nombre =str_replace(substr($nombre , strrpos($nombre, "("), strrpos($nombre, ")") - strrpos($nombre, "(")+1), "",$nombre);
            $paginas = \App\Funciones::getPDFPages($archivo);
      

            $entrada = \App\Models\Archivos::create(['nombre' => $nombre, 'paginas' => $paginas]);
            $archivo->move(storage_path("app/archivos/"), $entrada->id);
      
            if(!isset($cliente))
            {
                $this->saveDocumentosinCliente($request);
                return response()->json(['error' => "El cliente con cedula/nit: ". $cedula ." no existe para el archivo: " . $nombre]);
            }

            DB::table("archivos_procesosmasivos")->insert(["archivo_id" => $entrada->id, 'cliente_id' => $cliente->id, "procesoMasivo_id" => $procesoMasivo->id]);
            return $procesoMasivo;            
        }
        else
        {
            $procesoMasivo = \App\Models\ProcesoMasivo::find($request->input('proceso_masivo_id'));
            
            // Si Proceso no existe
            if(!isset($procesoMasivo)){
                return response()->json(['error' => "El Proceso no existe"]);
            }
            
            // Archivo a base de datos y guardar
            $paginas = \App\Funciones::getPDFPages($archivo);
            $entrada = \App\Models\Archivos::create(['nombre' => $nombre, 'paginas' => $paginas]);
            $archivo->move(storage_path("app/archivos/"), $entrada->id);
            
            // Recorrer por cada uno de los clientes
            DB::beginTransaction();
            foreach ($request->input('cliente_id') as $id ) {
                $cliente = \App\Models\Cliente::find($id);

                // Si Cliente No Existe
                if(!isset($cliente))
                {
                    $entrada->delete();
                    Storage::delete('app/archivos/'. $id);
                    DB::rollBack();
                    $this->saveDocumentosinCliente($request);
                    return response()->json(['error' => "El cliente con el id: ". $id ." no existe para el archivo: " . $nombre . "\n si hubo clientes anexados para este archivos seran borrados para evitar inconsistencias"]);
                }

                DB::table("archivos_procesosmasivos")->insert(["archivo_id" => $entrada->id, 'cliente_id' => $cliente->id, "procesoMasivo_id" => $procesoMasivo->id]);
                
            }
            DB::commit();
            return back();
        }
    }

	public function CargarArchivosProcesosMasivosCliente(Request $request, $cliente_id,$proceso_id){
        $archivo = $request->file("archivo");
        $nombre = $archivo->getClientOriginalName();
		$cliente = \App\Models\Cliente::find($cliente_id);
		$paginas = \App\Funciones::getPDFPages($archivo);
		$entrada = \App\Models\Archivos::create(['nombre' => $nombre, 'paginas' => $paginas]);
		$archivo->move(storage_path("app/archivos/"), $entrada->id);
		DB::table("archivos_procesosmasivos")->insert(["archivo_id" => $entrada->id, 'cliente_id' => $cliente_id, "procesoMasivo_id" => $proceso_id]);
		Session::flash("success","Archivo subido correctamente");
		return back();
	}


    public function getFields(Request $request){
        return response()->json(ProcesosMasivosCliente::fields);
    }





    private function  saveDocumentosinCliente(Request $request, $archivo)
    {
        $nombre = $archivo->getClientOriginalName();      
        
        $data = ['titulo' => $nombre, 'descripcion' => "Documento sin cliente asociado o existente",'categoria_id' => '50'];
        $data['activo'] = true;
        $data['editable'] = false;
        
        $documento = \App\Models\Documentos::Create($data);
        $archivo->move(storage_path("documentos/". $documento->id), $nombre);
        $documento->save();
        return $documento;
    }



}
