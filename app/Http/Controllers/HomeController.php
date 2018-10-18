<?php

namespace App\Http\Controllers;
use App\Funciones;
use App\Http\Requests;
use App\Models\CategoriasTickets;
use App\Models\Cliente;
use App\Models\ComentariosTickets;
use App\Models\Documentos;
use App\Models\Paciente;
use App\Models\ProcesoMasivo;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Tickets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            $categorias = Auth::user()->categorias()->wherein("parent_id",["",null]);
            $tickets = Tickets::where("guardian_id",Auth::user()->id)->take(6)->get();
            $documentos = Documentos::where("activo","=","1")->orderby("updated_at","desc")->with('categoria')->take(6)->get();
            $procesosMasivos = ProcesoMasivo::orderBy('updated_at','desc')->take(6)->get();
            return view('menu')
            ->withCategorias($categorias)
            ->withTickets($tickets)
            ->withDocumentos($documentos)
            ->with("procesosMasivos", $procesosMasivos);
        }
        else{
            return redirect("login");
        }
    }

    public function home(Request $request)
    {
        if (Auth::check()) {
            $categorias = Auth::user()->categorias()->wherein("parent_id",["",null]);
            $tickets = Tickets::where("guardian_id",Auth::user()->id)->take(6)->get();
            $documentos = Documentos::where("activo","=","1")->orderby("updated_at","desc")->with('categoria')->take(6)->get();
	    $procesosMasivos = ProcesoMasivo::orderBy('updated_at','desc')->take(6)->get();
            return view('menu')
            ->withCategorias($categorias)
            ->withTickets($tickets)
            ->withDocumentos($documentos)
            ->with("procesosMasivos", $procesosMasivos);
        }
        else{
            return redirect("login");
        }
    }

    public function verCalendario(Request $request)
    {
        return view('ver-calendario');
    }

    public function profile(Request $request)
    {
        $qr = array("email" => Auth::user()->email, "url" => url(""), "token" => Crypt::encrypt(Auth::user()->id));
        $qr = json_encode($qr);
        return view('profile')->withUser(Auth::user())->withQr($qr);
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $user->nombre =  $request->input('nombre');
        $user->email =  $request->input('email');
        $user->cargo =  $request->input('cargo');
        $user->departamento =  $request->input('departamento');

        if($request->hasFile('photo'))
        {
            array_map('unlink', glob(public_path("img/users/". $user->id .".*")));
            $request->file('photo')->move(public_path('img/users/'), $user->id ."." . $request->file('photo')->getClientOriginalExtension());
        }

        if($request->has('password'))
        {

            if (!Hash::check($request->input('oldpassword'), Auth::user()->password)) {
                Session::flash("error",'La contraseña no coincide con la actual');
                return back();
            }
            if ($request->input('password') != $request->input('password_confirm')) {
                Session::flash("error",'Las contraseñas no coinciden');
                return back();
            }
            $user->password = Hash::make($request->input('password'));
            Session::flash("success","Contraseña Actualizada");
            Funciones::sendMailPasswordChanged($user);
        }
        
        $user->save();
        Session::flash("success","Usuario actualizado correctamente");
        return  back();
    }

    public function tickets(Request $request)
    {
        $desde = Input::get('desde', Carbon::now()->startOfYear());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());

        $categorias =  Auth::user()->categorias();
        $tickets = Tickets::where('estado',"<>","completado")
        ->whereIn("categoria_id",$categorias->pluck("id"))
        ->orderBy("categoria_id","asc")
        ->orderBy("created_at")
        ->whereBetween("created_at",[$desde,$hasta])
        ->get();

        return  view('tickets')->withTickets($tickets)->withDesde($desde)->withHasta($hasta);
    }

    public function misTickets(Request $request)
    {

        $desde = Input::get('desde', Carbon::now()->startOfYear());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());

        $tickets= Tickets::misTickets()
        ->whereBetween("created_at",[$desde,$hasta]);
        if ($request->has('estado')) {
            $tickets = $tickets->where('estado',"=",$request->input('estado'));
        };
        $tickets = $tickets->get();
        return  view('tickets')->withTickets($tickets)->withDesde($desde)->withHasta($hasta);
    }

    public function todostickets(Request $request)
    {
        $desde = Input::get('desde', Carbon::now()->startOfYear());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());
        $tickets = Tickets::todos()
        ->whereBetween("created_at",[$desde,$hasta]);
        if ($request->has('estado')) {
            $tickets = $tickets->where('estado',"=",$request->input('estado'));
        };
        $tickets = $tickets->get();

        return  view('tickets')->withTickets($tickets)->withDesde($desde)->withHasta($hasta);
    }

    public function porCategoria(Request $request, $categoria)
    {
        $desde = Input::get('desde', Carbon::now()->startOfYear());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());
        $tickets = CategoriasTickets::find($categoria)->tickets()->whereBetween("created_at",[$desde,$hasta])->get();
        $subCategorias = Auth::user()->categorias()->where("parent_id",$categoria);
        return  view('tickets')
        ->withTickets($tickets)->withSubcategorias($subCategorias)
        ->withDesde($desde)->withHasta($hasta);
    }

    public function ticketAgregar(Request $request)
    {
        return view("agregar-caso");
    }

    public function ticketVer(Request $request, $id)
    {
        $ticket= Tickets::find($id);
        abort_if(!isset($ticket),404, "Este ". trans_choice("literales.ticket",1)  ." fue eliminado");
        if(!Auth::user()->hasRole("SuperAdmin")  && !in_array(Auth::user()->id, $ticket->participantes()->pluck('id')->toArray()))
        {
            abort(401, "No posee los permisos para ver este recurso");
        }
        $comentarios = ComentariosTickets::where("ticket_id",$ticket->id)
        ->orderBy("created_at", "desc")
        ->get();
        return view("verTicket")->withTicket($ticket)->withComentarios($comentarios);
    }

    public function ticketEliminar(Request $request, $id)
    {
        $ticket =Tickets::find($id);
        if($ticket->user_id == Auth::user()->id  || Auth::user()->admin ==1 )
        {
            $ticket->delete();
            \App\Models\Proceso::where("ticket_id", $id)->delete();
            \App\Models\Consulta::where("ticket_id", $id)->delete();
        }
        else
        {
           Session::flash("error","No tiene los permisos necesarios");
        }
        return back();
    }

    public function ticketEditar(Request $request, $id)
    {
        $ticket =Tickets::find($id);
        if($ticket->user_id == Auth::user()->id || Auth::user()->id == $ticket->guardian_id )
        {
            $ticket->titulo = $request->input('titulo');
            $ticket->contenido = $request->input('contenido');
            
            \App\Models\ComentariosTickets::Create(['ticket_id' => $ticket->id, 'user_id' => Auth::user()->id,
                'texto' => "<b style='color:green'><em> ". Auth::user()->nombre . " actualizó el contenido del ticket </em></b>"
                ]);
            $ticket->save();
            Session::flash("success","Contenido Actualizado");
            Funciones::sendMailContenidoActualizado($ticket,$ticket->user,$ticket->guardian);
        }
        else
        {
            Session::flash("error","No tiene los permisos necesarios");
        }
        return back();
    }

    public function ticketExcel(Request $request, $id)
    {
        $ticket =Tickets::find($id);
       return Excel::create($ticket->titulo, function($excel) use($ticket) {
            $excel->sheet(substr($ticket->titulo, 0, 31), function($sheet)use($ticket)  {
                    $sheet->loadView('excel.ticket-excel', array('ticket' => $ticket));
            });

        })->download('xlsx');
    }

    public function listarDocumentos (Request $request, $categoria)
    {
        $categorias = \App\Models\CategoriaDocumentos::where("parent_id","=",$categoria)->get();
        $documentos = \App\Models\Documentos::where("categoria_id", "=", $categoria)->where("activo","=","1")->orderby('titulo','asc')->get();

        return view('ver-documentos')->withDocumentos($documentos)->withCategoria($categoria)->withCategorias($categorias);
    }

    public function listarCategorias (Request $request)
    {
        $categorias = \App\Models\CategoriaDocumentos::where("parent_id","=","0")
        ->orwhereNull("parent_id")
        ->distinct()->get();
        return view('ver-categoriasDocumentos')->withCategorias($categorias);
    }

    public function agregarAlerta(Request $request)
    {
       $data= $request->except('inmediato');
       $alerta = \App\Models\Alerta::create($data);
       $alerta->user_id = Auth::user()->id;
       if(!$request->has('usuarios'))
       {
          $alerta->usuarios = [Auth::user()->id];
       }
       $alerta->save();

       if($request->input('inmediato',false) == 'true')
       {
            $alerta->emitir();
       }
       Session::flash("success","Alerta Agregada Correctamente");
       return back();
    }

    public function verNotificaciones(Request $request)
    {
        $notificaciones = Auth::user()->notifications()->take(100)->get();
        return view("ver-notificaciones")->withNotificaciones($notificaciones);
    }

    public function verNotificacion(Request $request , $id)
    {
        $notificacion = \Illuminate\Notifications\DatabaseNotification ::findorFail($id);
        if (isset($notificacion->data['ticket_id']))
        {
            $redirect = url('ticket/ver/'.$notificacion->data['ticket_id']);
        }
        else
        {
            $redirect = url('notificaciones');
        }

        $notificacion->markAsRead();
        return redirect($redirect);
    }

    public function Leernotificacion(Request $request , $id)
    {
        $notificacion = \Illuminate\Notifications\DatabaseNotification::findorFail($id);
        $notificacion->markAsRead();
        return back();
    }


    public function NoLeernotificacion(Request $request , $id)
    {
        $notificacion = \Illuminate\Notifications\DatabaseNotification ::findorFail($id);
        $notificacion->read_at = null;
        $notificacion->save();
        return back();
    }

    public function getFileTicketEncrypted(Request $request, $id, $clave)
    {
        $ticket = Tickets::find($id);
        if($clave != $ticket->clave)
            return "Clave Incorrecta. No Autorizado";

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
            return "Clave Incorrecta. No Autorizado";

        $encryptedContents = Storage::get("ComentariosTickets/". $id . "/" . $comentario->archivo);
        $decryptedContents = Crypt::decrypt($encryptedContents);

        return response()->make($decryptedContents, 200, array(
            'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer($decryptedContents),
            'Content-Disposition' => 'attachment; filename="' . $comentario->archivo . '"'
        ));
    }

    public function getDocumento(Request $request, $id)
    {
        $documento = \App\Models\Documentos::find($id);
        \App\Models\Auditorias::Create(['user_id' => Auth::user()->id, 'documento_id'=> $id , 'tipo' => 'descarga']);
        return response()->download(storage_path("app/documentos/" . $documento->id), $documento->titulo);
    }

    public function descargarArchivo(Request $request, $id)
    {
        $archivo = \App\Models\Archivos::findorFail($id);

        $file = Storage::get("archivos/". $id);

        return response()->make($file, 200, array(
            'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer($file),
            'Content-Disposition' => 'attachment; filename="' . $archivo->nombre . '"'
        ));
    }

    public function busqueda(Request $request)
    {
        $query = $request->input('query');

        $documentos = \App\Models\Documentos::where("activo","=","1")->where("titulo","like","%".$query."%")
        ->orwhere("descripcion","like","%".$query."%")->get();

        $tickets = Tickets::where(function($q) use ($query){
            $q->orwhere("titulo","like","%".$query."%")
            ->orwhere("contenido","like","%".$query."%");
        })
        ->where(function($q){
           $q->orwhereIn("categoria_id",Auth::user()->categorias_id)
            ->orwhere("user_id",Auth::user()->id)
            ->orWhere("guardian_id",Auth::user()->id)
            ->orwhere("invitados_id", "LIKE", '%"'. Auth::user()->id . '"%');
        })->with("user",'guardian')->get();

        $categorias = CategoriasTickets::where("nombre","like", "%". $query ."%")
        ->whereIn("id",Auth::user()->categorias()->pluck("id"))
        ->get();

        $pacientes = Paciente::
        orwhere("nombres","LIKE", "%" . $query . "%")
        ->orWhere("apellidos", "LIKE","%" . $query . "%")
        ->orWhere('cedula',"LIKE","%" . $query . "%")
        ->get();

        $clientes = Cliente::
        orwhere("nombres","LIKE", "%" . $query . "%")
        ->orWhere("apellidos", "LIKE","%" . $query . "%")
        ->orWhere('cedula',"LIKE","%" . $query . "%")
        ->orWhere('nit',"LIKE","%" . $query . "%")
        ->get();

        $proveedores = Proveedor::
        orwhere("nombre","LIKE", "%" . $query . "%")
        ->orWhere("documento", "LIKE","%" . $query . "%")
        ->orWhere("descripcion", "LIKE","%" . $query . "%")
        ->get();

        $procesosMasivos = ProcesoMasivo::where("titulo","LIKE","%" . $query . "%")
        ->orWhere("referencia", "LIKE", "%" .  $query . "%")
        ->get();

        $productos = Producto::where("name","LIKE","%" . $query . "%")
        ->orWhere("descripcion", "LIKE", "%" .  $query . "%")
        ->orWhere("referencia", "LIKE", "%" .  $query . "%")
        ->get();

        return view('busqueda')
        ->withTickets($tickets)
        ->withDocumentos($documentos)
        ->withCategorias($categorias)
        ->withPacientes($pacientes)
        ->withClientes($clientes)
        ->withProveedores($proveedores)
        ->withProductos($productos)
        ->with("procesosMasivos", $procesosMasivos);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // logout user
        return redirect('/');
    }

    public function test(Request $request){
        return \App\Models\Proveedor::with('invoices')->first();
    }


    public function ticketsconclientes(Request $request){
        $clientes_id =  explode(",",$request->input("clientes_id"));
        $proceso = \App\Models\ProcesoMasivo::find($request->input("proceso_masivo_id"));
        $clientes = \App\Models\Cliente::whereIn("id", $clientes_id)->get();

        $titulo ="Ticket General para proceso: ".  $proceso->titulo;
        $contenido = view("procesosMasivos.partials.contenido-proceso-con-clientes")
        ->with("clientes",$clientes)
        ->with("proceso",$proceso);
        return view("agregar-caso")
            ->withTitulo($titulo)
            ->withContenido($contenido);
    }

}
