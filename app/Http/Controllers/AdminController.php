<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\CategoriasTickets;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class AdminController extends Controller
{
    
    //Auditoria
    public function resumen(Request $request)
    {
        $users = \App\User::all();
        foreach ($users as $user)
        {
            $desde = Input::get('desde', Carbon::now()->startOfMonth());
            $hasta = Input::get('hasta', Carbon::now()->tomorrow());
            $user->creados = $user->tickets()->whereBetween("created_at",[$desde, $hasta])->count();
            $user->responsables = $user->ticketsResponsables()->whereBetween("created_at",[$desde, $hasta])->count();
            $user->comentarios = $user->comentarios()->whereBetween("created_at",[$desde, $hasta])->count();
            $user->abiertos = $user->ticketsResponsables()->whereBetween("created_at",[$desde, $hasta])->whereNotIn('estado',['completado','vencido'])->count();
            $user->completados = $user->ticketsResponsables()->whereBetween("created_at",[$desde, $hasta])->whereEstado('completado')->count();
            $user->vencidos = $user->ticketsResponsables()->whereBetween("created_at",[$desde, $hasta])->whereEstado('vencido')->count();
            $user->logins = $user->auditorias()->whereTipo('login')->whereBetween("created_at",[$desde, $hasta])->count();
            $user->puntuacion = $user->creados + $user->responsables + $user->comentarios;
        }
        return  view('pdf.resumen', ['users' => $users, 'desde' => $desde, 'hasta' => $hasta]);
    }

    public function auditarUsuario(Request $request,$user_id = null)
    {
        $desde = Input::get('desde', Carbon::now()->startOfMonth());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());
        $limit = Input::get('limit', 200);

        if($user_id != null)
            $registros =\App\Models\Auditorias::whereBetween("created_at",[$desde,$hasta])->where("user_id","=",$user_id)->get();
        else
            $registros =\App\Models\Auditorias::whereBetween("created_at",[$desde,$hasta])->get();

        return view("auditorias.usuario")
        ->withDesde($desde)
        ->withHasta($hasta)
        ->withLimit($desde)
        ->withRegistros($registros);
    }



    //Email
    public function emailPorDepartamento(Request $request)
    {
        $correos = \App\Models\Paciente::whereIn("departamento",$request->input("filtro",[]))->get()
        ->pluck("email","full_name");
        $filtro = \App\Models\Paciente::distinct()->select("departamento")->pluck("departamento","departamento")->toArray();

        return view("Admin.email")
        ->withFiltro($filtro)
        ->withCorreos($correos)
        ->withActivo("Por Departamento");
    }

    public function emailporPuesto(Request $request)
    {
        $correos = \App\Models\Paciente::whereIn("puesto_id",$request->input("filtro" ,[]))->get()
        ->pluck("email","full_name");
        $filtro = \App\Models\Puesto::distinct()->select("id","nombre")->pluck("nombre","id")->toArray();

        return view("Admin.email")
        ->withFiltro($filtro)
        ->withCorreos($correos)
        ->withActivo("Por Puesto");
    }

    public function emailAUsuarios(Request $request)
    {
        $correos = \App\User::get()->pluck("email","nombre");
        // $filtro = \App\Models\Puesto::distinct()->select("id","nombre")->pluck("nombre","id")->toArray();

        return view("Admin.email")
        ->withFiltro([])
        ->withCorreos($correos)
        ->withActivo("Por Usuarios");
    }

    public function emailAUsuariosPorDepartamento(Request $request)
    {
        $correos = \App\User::whereIn("departamento",$request->input("filtro",[]))->get()
        ->pluck("email","nombre");
        $filtro = \App\User::distinct()->select("departamento")->pluck("departamento","departamento")->toArray();

        return view("Admin.email")
        ->withFiltro($filtro)
        ->withCorreos($correos)
        ->withActivo("Por Departamento");
    }






    /*  Evaluaciones */

    public function verVehiculo(Request $request, $vehiculo)
    {
        $vehiculo = \App\Models\Vehiculo::find($vehiculo);

          return view("evaluaciones.ver-vehiculo")
          ->withVehiculo($vehiculo)
          ->withTitle($vehiculo->nombre);
    }

    public function verConductor(Request $request, $conductor)
    {
        $conductor = \App\Models\Conductor::find($conductor);
         return view("evaluaciones.ver-conductor")
          ->withConductor($conductor)
          ->withTitle($conductor->full_name);
    }

    public function verEvaluacionProveedor(Request $request ,$evaluacion)
    {
        $evaluacion = \App\Models\EvaluacionProveedor::find($evaluacion);
          return view("evaluaciones.ver-evaluacion-proveedor")
          ->withEvaluacion($evaluacion)
          ->withProveedor($evaluacion->proveedor);
    }

    public function verEvaluacionVehiculo(Request $request ,$evaluacion)
    {
        $evaluacion = \App\Models\EvaluacionVehiculo::find($evaluacion);
          return view("evaluaciones.ver-evaluacion-vehiculo")
          ->withEvaluacion($evaluacion)
          ->withVehiculo($evaluacion->vehiculo);
    }
    
    public function verEvaluacionConductor(Request $request ,$evaluacion)
    {
        $evaluacion = \App\Models\EvaluacionConductor::find($evaluacion);
          return view("evaluaciones.ver-evaluacion-conductor")
          ->withEvaluacion($evaluacion)
          ->withConductor($evaluacion->conductor);
    }   


    // Documentos    
    public function categoriasUsuarios(Request $request, $categoria)
    {
        $categoria = CategoriasTickets::find($categoria);
        $users = \App\User::all();
        return view('Admin.agregarmasivamente')->withUsers($users)->withCategoria($categoria);
    }

    public function agregarmasivamente(Request $request, $categoria)
    {
        $usuarios = $request->input("usuarios");
        foreach ($usuarios as $usuario_id) {
            $usuario = \App\User::find($usuario_id);
            $usuario->categorias_id = $usuario->categorias_id == null ? [] : $usuario->categorias_id;
            if(!in_array($categoria,$usuario->categorias_id))
            {
                $array = array_values($usuario->categorias_id);
                if (($key = array_search($categoria, $array)) == false) {
                    $array[] = $categoria;
                }
                $usuario->categorias_id = array_values($array);
                echo $usuario->nombre;
                $usuario->save();
            }
        }

        $nousuarios = \App\User::whereNotIn("id",$usuarios)->get();
        foreach ($nousuarios as $usuario) {
          if(in_array($categoria,$usuario->categorias_id))
          {
             $array = array_values($usuario->categorias_id);
             if (($key = array_search($categoria, $array)) !== false) {
                unset($array[$key]);}
                $usuario->categorias_id = $array;
                $usuario->save();
            }
        }


        \Alert::success("Agregado Masivamente Correcto")->flash();
        return redirect('admin/categorias');
    }

    public function archivosMasivos(Request $request)
    {
        if(!Auth::user()->can('Agregar Documentos') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
            abort(403, 'No tiene permisos para esta acción');
        }
        $categorias = \App\Models\CategoriaDocumentos::orderby('parent_id')->get();
        return view('Admin.archivos-masivos')->withCategorias($categorias);
    }

    public function cargarArchivosMasivos(Request $request)
    {
        if(!Auth::user()->can('Agregar Documentos') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
            abort(403, 'No tiene permisos para esta acción');
        }

        $data = $request->except("archivo");
        $data['activo'] = 1;
        $data['editable'] = 1;
        $data['nombre'] = $request->file('archivo')->getClientOriginalName();

        $documento = \App\Models\Documentos::Create($data);

        $nombre = $request->file("archivo")->getClientOriginalName() . "." . $request->file("archivo")->getclientOriginalExtension();
        $documento->archivo = $nombre;
        // $paginas = \App\Funciones::getPDFPages($archivo);
        $documento->titulo = preg_replace('/\\.[^.\\s]{3,4}$/', '', $nombre);
        $documento->descripcion = preg_replace('/\\.[^.\\s]{3,4}$/', '', $nombre);
        $request->file("archivo")->move(storage_path("app/documentos/"), $documento->id);
        $documento->save();

        return $documento;
    }

    public function CargarArchivosClientes(Request $request)
    {
        if(!Auth::user()->canAny(['Agregar Clientes', 'Editar Clientes']) &&  !Auth::user()->hasRole('SuperAdmin'))
        {
            abort(403, 'No tiene permisos para esta acción');
        }
         $nombre = $request->file('archivo')->getClientOriginalName();
         $nombre = explode("_",$nombre);
         $nombre[sizeof($nombre)-1] = str_replace(".pdf","",$nombre[sizeof($nombre)-1]);
        
        $cliente = \App\Models\Cliente::find($request->input('cliente_id'));
        $archivo = $request->file("archivo");
        $nombre = $archivo->getClientOriginalName();
        $paginas = \App\Funciones::getPDFPages($archivo);
        $entrada = \App\Models\Archivos::create(['nombre' => $nombre,'paginas' => $paginas]);
        $archivo->move(storage_path("app/archivos/"), $entrada->id);
        DB::table("archivos_clientes")->insert(["archivo_id" => $entrada->id, 'cliente_id' => $cliente->id]);
        return $cliente;


         // return  \App\Models\Cliente::where("nit",$nombre[0])->first();
    }


    
    public function eliminarDocumento(Request $request, $id)
    {
        \App\Models\Documentos::destroy($id);
        Flash::Success("Documento Eliminado Correctamente");
        return back();
    }






    // Miscelaneos
    public function getListaCategoriasDocumentos(Request $request){
        return view('lista-documentos-tree');
    }

    public function getListaCategoriasTickets(Request $request){
        return view('lista-tickets-tree');
    }

    public function emitirAlerta(Request $request){
        $alerta =\App\Models\Alerta::findorFail(Input::get('alerta_id'));
        $alerta->emitir();
    }
    
    public function agregarAccesoRapido(Request $request)
    {
        $atajo =\App\Models\Atajo::create(
            ['user_id' => Auth::user()->id,
             'url' => $request->input('url'),
             'texto' => $request->input('texto')
            ]);
        \Alert::success(trans('Atajo creado'))->flash();
        return redirect($atajo->url);
    }

    public function eliminarAccesoRapido(Request $request, $id)
    {
        $atajo =\App\Models\Atajo::destroy($id);
       \Alert::success(trans('Atajo eliminado'))->flash();
        return back();
    }


}
