<?php

namespace App\Http\Controllers;

use App\Funciones;
use App\Http\Requests;
use App\Models\Casos_medicos;
use App\Models\Tickets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;

class ApiController extends Controller
{

    public function doLogin (Request $request)
    {

        $usuario = Auth::user();
        $usuario->notificaciones_count = $usuario->unreadNotifications()->count();
        $usuario->modulos = config("modulos");
        if(isset($usuario->cliente))
         {
            $usuario->iscliente = true;
            $usuario->cliente =  $usuario->cliente;

         }  
        else
        {
            $usuario->isCliente = false;
        }
        $usuario->token = Crypt::encrypt(Auth::user()->id);
        $usuario->csrf_token = csrf_token();
        $usuario->events = $usuario->EventsCalendar(false);
        \App\Models\Auditorias::create(['tipo' => 'login app', 'user_id' => Auth::user()->id]);
        return $usuario;
    }

    public function getUser (Request $request, $id)
    {
        $usuario = \App\User::find($id);
        $usuario->modulos = config("modulos");
        if(isset($usuario->cliente))
         {
            $usuario->iscliente = true;
            $usuario->cliente =  $usuario->cliente;

         }  
        else
        {
            $usuario->isCliente = false;
        }
        return $usuario;
    }

    public function getUsers (Request $request)
    {
        $usuarios = \App\User::all();
        return $usuarios;
    }

    public function getEventos (Request $request){
        return Auth::user()->EventsCalendar(false);
    }


    public function getCategorias (Request $request)
    {

        $categorias = Auth::user()->categorias()->whereIn("parent_id",["",null])->toArray();

        return \Response::json(array_values($categorias), 200);
    }

    public function getAllCategorias (Request $request)
    {

        $categorias = \App\Models\Categorias::orderBy("nombre")->get();

        return \Response::json($categorias, 200);
    }

    public function getTickets(Request $request, $categoria)
    {

        $tickets = \App\Models\Tickets::where("categoria_id",$categoria)
        ->with("user")->with("guardian")->withCount('comentarios')
        ->get();

        $subcategorias = Auth::user()->categorias()->where("parent_id", $categoria)->toArray();
        return \Response::json(['tickets' => $tickets, 'categorias' => array_values($subcategorias)], 200);
    }

 	public function getCategoriasDocumentos (Request $request)
 	{
        $categorias = \App\Models\CategoriaDocumentos::
        where(function($q){
            $q->where("parent_id","");
            $q->orWhereNull("parent_id");
        })
        ->get();

        return \Response::json($categorias, 200);
    }

    public function getDocumentos(Request $request, $categoria)
    {
        $documentos = \App\Models\Documentos::where("activo","=","1")->where("categoria_id","=",$categoria)->get();
        $subcategorias = \App\Models\CategoriaDocumentos::where("parent_id",$categoria)->get();
        return \Response::json(['documentos'=>$documentos, 'categorias' => $subcategorias], 200);
    }

    public function getTicket (Request $request, $ticket_id)
    {

        $ticket = \App\Models\Tickets::
        with("user",'guardian','categoria')
        ->find($ticket_id);
        if(!isset($ticket))
            abort(404,"Recurso Eliminado");
        if($ticket->archivo != null)
            $ticket->path  = $ticket->archivo();
        $ticket->mime  = substr(strrchr($ticket->archivo,'.'),1);

        if(Auth::user()->has('cliente'))
            $comentarios = $ticket->comentarios()->publicos()->with("user")->orderBy('created_at','desc')->get();            
        else    
            $comentarios = $ticket->comentarios()->with("user")->orderBy('created_at','desc')->get();

        $comentarios->each(function($c) {
            if($c->archivo != null)
                $c->path = $c->file();
            $c->mime = substr(strrchr($c->archivo,'.'),1);
        });

        return \Response::json(['comentarios' => $comentarios, 'ticket' => $ticket], 200);
    }

    public function getMisTickets(Request $request)
    {
       $ticketsCreados = Auth::user()->tickets()->with('user','guardian')->get();
       $ticketsResponsables = Auth::user()->tickets_guardian()->with('user','guardian')->get();
       return \Response::json(['ticketsCreados'=>$ticketsCreados, 'ticketsResponsables' => $ticketsResponsables], 200);
    }

    public function getAllTickets(Request $request)
    {
        $tickets = Tickets::
        orwhereIn("categoria_id",Auth::user()->categorias_id)
        ->orwhere("user_id",Auth::user()->id)
        ->orWhere("guardian_id",Auth::user()->id)
        ->orwhere("invitados_id", "LIKE", '%"'. Auth::user()->id . '"%')
        ->with('user','guardian')->get();


       return \Response::json(['tickets'=>$tickets], 200);
    }

    public function getTicketsAbiertos(Request $request)
    {
        $tickets = Auth::user()->tickets()->where("estado","<>", "completado")->with('user','guardian')->get();
       return \Response::json(['tickets'=>$tickets], 200);
    }

    public function getTicketsVencidos(Request $request)
    {
        $tickets = Auth::user()->tickets()->where("vencimiento", new Carbon())->with('user','guardian')->get();
       return \Response::json(['tickets'=>$tickets], 200);
    }

    public function getUsuarios(Request $request)
    {
        $usuarios = \App\User::all();
        return \Response::json(['usuarios'=>$usuarios], 200);
    }


    public function getUsuariosCategoria(Request $request ,$categoria_id)
    {
        $categoria =  \App\Models\CategoriasTickets::find($categoria_id);
        $users = $categoria->users();
        return json_encode($users);
    }

    public function addTicket(Request $request)
    {

        if($request->hasFile('archivo') && $request->get("encriptado") == "true" && !$request->has("clave"))
        {
            return "Debe Ingresar Una contraseña para encriptar el archivo";
        }

        $input = $request->except("archivo","enriptado","clave","vencimiento");
        $input =  array_add($input,'user_id',Auth::user()->id);
        $input =  array_add($input,'estado','abierto');
        $tickets = \App\Models\Tickets::create($input);
        $tickets->vencimiento = new Carbon($request->input('vencimiento'));
        $tickets->save();
        if($request->hasFile('archivo'))
        {
            $nombre = $request->file("archivo")->getClientOriginalName();
            // Si Se pidio Encriptar El Archivo
            if($request->get("encriptado") == "true")
            {
                $tickets->encriptado = true;
                $tickets->archivo = $nombre;
                $tickets->clave = $request->get("clave");

                $encriptado = Crypt::encrypt(file_get_contents($request->file("archivo")));
                Storage::put("tickets/". $tickets->id . "/" . $nombre , $encriptado);
            }
            // Si no
            else
            {
                $request->file('archivo')->move(public_path("archivos/tickets/" . $tickets->id . "/"), $nombre );
                $tickets->archivo = $nombre;
            }

            $tickets->save();
        }

        Funciones::sendMailNewTicket($tickets, $tickets->user, $tickets->guardian);
        return $tickets;
    }

    public function editTicket(Request $request, $id)
    {

        $tickets = \App\Models\Tickets::find($id);
        $tickets->fill($request->except('vencimiento'));
        if ($request->has('vencimiento') && $tickets->vencimiento != Carbon::parse($request->input('vencimiento'))) {
            $tickets->vencimiento = Carbon::parse($request->input('vencimiento'));
            if($ticket->estado == "vencido")
            {
              $ticket->estado = "en curso";
            }
            $ticket->mail_alert_manual_1  = null;
            $ticket->mail_alert_manual_2  = null;
            $ticket->mail_alert_manual_3  = null;
            $ticket->mail_alert_vencido = null;
        }
        $tickets->save();
        Funciones::sendMailCambioEstado($tickets->guardian, $tickets->user,$tickets);
        return $tickets;
    }

    public function addComentarioTicket (Request $request, $ticket_id)
    {
        $ticket = \App\Models\Tickets::find($ticket_id);
        if($request->hasFile('archivo') && $request->get("encriptado") == "true" && !$request->has("clave"))
        {
            return "Error: Sin Clave de encriptacion";
        }
        $publico =  $request->input('publico', false);
        if(Auth::user()->has('cliente'))
        {
            $publico = true;
        }
        $comentario = \App\Models\ComentariosTickets::create([
            'user_id'    => Auth::user()->id,
            'texto' => $request->input('texto'),
            'publico' => $publico,
            'ticket_id'  => $ticket_id
            ]);

        if($request->hasFile('archivo'))
        {
            $nombre = $request->file("archivo")->getClientOriginalName();
            if($request->get("encriptado") == "true")
            {
                $comentario->encriptado = true;
                $comentario->archivo = $nombre;
                $comentario->clave = $request->get("clave");
                $encriptado = Crypt::encrypt(file_get_contents($request->file("archivo")));
                Storage::put("ComentariosTickets/". $comentario->id . "/" . $nombre , $encriptado);
                Flash::success('Archivo Encriptado');
            }
            else
            {
                $request->file('archivo')->move(public_path("archivos/ComentariosTickets/"), $nombre );
                $comentario->archivo =  $nombre;
            }

            $comentario->save();
        }

        \App\Funciones::sendMailNewComentario([$ticket->user->id,$ticket->guardian->id], $comentario);
        return $comentario;
    }
    
    public function addAlerta(Request $request)
    {

       $data= $request->except('inmediato','programado');
       $data['programado'] = Carbon::parse($request->input('programado'));
       $alerta = \App\Models\Alerta::create($data);
       $alerta->user_id = Auth::user()->id;
       if(!$request->has('users_id'))
       {
          $alerta->usuarios = [Auth::user()->id];
       }
       else
       {
            $alerta->usuarios = $request->input('users_id');
       }

       $alerta->save();

       if($request->input('inmediato',false) == 'true')
       {
            $alerta->emitir();
       }
       return $alerta;
    }

    public function deleteComentarioTicket(Request $request,$id)
    {
        $comentario = \App\Models\ComentariosTickets::find($id);
        if($comentario->user_id != Auth::user()->id)
        {
            abort(503);
        }
        $comentario->delete();
        return "true";
    }

    public function getFileTicketEncrypted(Request $request, $id, $clave)
    {
        $ticket = Tickets::find($id);
        if($clave != $ticket->clave)
            return response()->make($decryptedContents, 200, array(
                'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer("Clave Incorrecta"),
                'Content-Disposition' => 'attachment; filename="error.txt"'
            ));

        $encryptedContents = Storage::get("tickets/". $id . "/" . $ticket->archivo);
        $decryptedContents = Crypt::decrypt($encryptedContents);

        return response()->make($decryptedContents, 200, array(
            'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer($decryptedContents),
            'Content-Disposition' => 'attachment; filename="' . $ticket->archivo . '"'
        ));
    }

    public function getFileComentarioTicketEncrypted(Request $request, $id, $clave)
    {
        $comentario = ComentariosTickets::find($id);
        if($clave != $comentario->clave)
            return response()->make($decryptedContents, 200, array(
                'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer("Clave Incorrecta"),
                'Content-Disposition' => 'attachment; filename="error.txt"'
            ));

        $encryptedContents = Storage::get("ComentariosTickets/". $id . "/" . $comentario->archivo);
        $decryptedContents = Crypt::decrypt($encryptedContents);

        return response()->make($decryptedContents, 200, array(
            'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer($decryptedContents),
            'Content-Disposition' => 'attachment; filename="' . $comentario->archivo . '"'
        ));
    }

    public function busqueda(Request $request)
    {
        $query = $request->input('query');
        $documentos = \App\Models\Documentos::where("activo","=","1")->where("titulo","like","%".$query."%")
        ->orwhere("descripcion","like","%".$query."%")->with('categoria')->get();

        $tickets = Tickets::where(function($q) use ($query){
            $q->orwhere("titulo","like","%".$query."%")
            ->orwhere("contenido","like","%".$query."%");
        })
        ->where(function($q){
           $q->orwhereIn("categoria_id",Auth::user()->categorias_id)
            ->orwhere("user_id",Auth::user()->id)
            ->orWhere("guardian_id",Auth::user()->id)
            ->orwhere("invitados_id", "LIKE", '%"'. Auth::user()->id . '"%');
        })->with("user",'guardian','categoria')->get();

        $categorias = \App\Models\CategoriasTickets::where("nombre","like", "%". $query ."%")
        ->whereIn("id",Auth::user()->categorias()->pluck("id"))
        ->get();

        return \Response::json(['tickets' => $tickets, 'documentos' => $documentos, 'categorias' => $categorias]);
    }

    public function getParameters(Request $request){
        return config("settings");
    }

    public function getNotificaciones(Request $request)
    {
        $notificaciones = Auth::user()->notifications()->take(50)->get()->each(function($not){
            $not->titulo = $not->data['titulo'];
            $not->mensaje = $not->data['texto'];
            $not->leido = isset($not->read_at) ?  true : false;
            $not->ticket_id = isset($not->data['ticket_id']) ? $not->data['ticket_id'] : null;
        });

        return $notificaciones;
    }


    public function Leernotificacion(Request $request , $id)
    {
        $notificacion = \Illuminate\Notifications\DatabaseNotification ::findorFail($id);
        $notificacion->markAsRead();
        return $notificacion;
    }

    public function NoLeernotificacion(Request $request , $id)
    {
        $notificacion = \Illuminate\Notifications\DatabaseNotification ::findorFail($id);
        $notificacion->read_at = null;
        $notificacion->save();
        return $notificacion;
    }


    //medicos
    public function getPacientes(Request $request)
    {
        $pacientes =  \App\Models\Paciente::with('casos','incapacidades','medico','arl','eps','puesto','fondo','historias');
        if($request->has("query"))
        {
            $query = $request->input('query');
            $pacientes = $pacientes->orWhere("nombres","LIKE","%".  $query . "%");
            $pacientes = $pacientes->orWhere("apellidos","LIKE","%". $query . "%");
            $pacientes = $pacientes->orWhere("cedula","LIKE","%". $query . "%");
        }
        $pacientes =$pacientes->get();
        return  response()->json(['pacientes'=> $pacientes]);
    }


    public function getCaso (Request $request, $id)
    {
        $caso = \App\Models\Casos_medicos::with('paciente','medico','incapacidades','archivos','puesto')->find($id);
        $recomendaciones = $caso->recomendaciones()->with("user")->get();
        $incapacidades = $caso->incapacidades()->with("medico","eps","cie10","paciente")->get();
        return  response()->json(['caso'=> $caso, 'recomendaciones' => $recomendaciones , 'incapacidades' => $incapacidades]);
    }

    public function getIncapacidad(Request $request, $id)
    {
        $incapacidad = \App\Models\Incapacidad::with('paciente','eps','caso','cie10','medico')->find($id);
        return  response()->json(['incapacidad' => $incapacidad]);
    }

    public function iniciarSeguimiento(Request $request, $id)
    {
      $caso_medico = Casos_medicos::findorFail($id);
      $ticket = \App\Models\Tickets::Create([
            "titulo" => "Seguimiento al Caso del paciente " . $caso_medico->paciente->full_name,
            "contenido" => "Origen del Caso: " . $caso_medico->origen_del_caso,
            "categoria_id" => Casos_medicos::categoria_id_casos,
            "user_id" =>  Auth::user()->id,
            "guardian_id" => Auth::user()->id,
            "estado" => "abierto",
            "transferible" => "1",
        ]);
      $caso_medico->ticket_id = $ticket->id;
      $caso_medico->save();
      \App\Funciones::sendMailNewTicket($ticket, $ticket->user, $ticket->guardian);
      return $ticket;
    }
}
