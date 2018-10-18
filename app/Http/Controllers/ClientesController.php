<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\Proceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash;

class ClientesController extends Controller
{

	public function verCliente(Request $request, $id)
	{
		$cliente = Cliente::with("procesos","user","consultas")->findorFail($id);
		$procesos = $cliente->procesos;
		$consultas = $cliente->consultas;

		return view("clientes.ver-cliente")
		->withCliente($cliente)
		->withProcesos($procesos)
		->withConsultas($consultas)
		->withTitle($cliente->full_name);
	}

	public function verProceso(Request $request, $id)
	{
		$proceso = Proceso::findorFail($id);
		$cliente = $proceso->cliente;

		return view("clientes.ver-proceso")
		->withCliente($cliente)
		->withProceso($proceso)
		->withTitle("Proceso de cliente: " . $cliente->full_name);
	}

	public function verConsulta(Request $request, $id)
	{
		$consulta = Consulta::findorFail($id);
		$cliente = $consulta->cliente;

		return view("clientes.ver-consulta")
		->withCliente($cliente)
		->withConsulta($consulta)
		->withTitle("Consulta de cliente: " . $cliente->full_name);
	}

	public function verFactura(Request $request,$id){
		$factura = \App\Models\Invoice::findorFail($id);
		$cliente = $factura->cliente;
		abort_if(!isset($cliente),404, "El cliente no existe");
		return view("clientes.ver-factura")
		->withFactura($factura)
		->withCliente($cliente);
	}

	public function verFacturaPdf(Request $request,$id){
		$factura = \App\Models\Invoice::findorFail($id);
		$cliente = $factura->cliente;


		// return view("pdf.invoice")
		// ->withFactura($factura)
		// ->withCliente($cliente);

		$pdf = \PDF::loadView('pdf.invoice', ['factura' => $factura, 'cliente' => $cliente]);
		return $pdf->stream('invoice.pdf');
	}



	public function iniciarSeguimientoProceso(Request $request, $id)
	{
		$proceso = Proceso::findorFail($id);

		$ticket = \App\Models\Tickets::Create([
			"titulo" => "Seguimiento al proceso del cliente " . $proceso->cliente->full_name,
			"contenido" => "Proceso: " . $proceso->descripcion,
			"categoria_id" => isset($cliente->categoria_id_proceso)? $cliente->categoria_id_proceso  : Proceso::categoria_id,
			"user_id" =>  Auth::user()->id,
			"guardian_id" => $proceso->user->id,
			"estado" => "abierto",
			"transferible" => "1",
			"cliente_id" =>  $proceso->cliente->id
			]);

		$proceso->ticket_id = $ticket->id;
		$proceso->save();
		\App\Funciones::sendMailNewTicket($ticket, $ticket->user, $ticket->guardian);
		return redirect("/ticket/ver/" . $ticket->id);
	}


	public function iniciarSeguimientoConsulta(Request $request, $id)
	{
		$consulta = Consulta::findorFail($id);

		$ticket = \App\Models\Tickets::Create([
			"titulo" => "Seguimiento al consulta del cliente " . $consulta->cliente->full_name,
			"contenido" => "consulta: " . $consulta->descripcion,
			"categoria_id" => isset($cliente->categoria_id_consulta)? $cliente->categoria_id_consulta  : Consulta::categoria_id,
			"user_id" =>  Auth::user()->id,
			"guardian_id" => $consulta->user->id,
			"estado" => "abierto",
			"transferible" => "1",
			"cliente_id" =>  $consulta->cliente->id
			]);

		$consulta->ticket_id = $ticket->id;
		$consulta->save();
		\App\Funciones::sendMailNewTicket($ticket, $ticket->user, $ticket->guardian);
		return redirect("/ticket/ver/" . $ticket->id);
	}


	public function buscarcliente(Request $request)
	{
		$query = $request->input('query');

		$cliente = Cliente::where("nit","=", $query)->first();
		if (!isset($cliente))
		{
			$clientes = cliente::
			orwhere("nombres","LIKE", "%" . $query . "%")
			->orWhere("apellidos", "LIKE","%" . $query . "%")
			->orWhere('cedula',"LIKE","%" . $query . "%")
			->orWhere('nit',"LIKE","%" . $query . "%")
			->get();
			return view('clientes/busqueda')->withClientes($clientes);
		}
		return redirect('admin/ver-cliente/' . $cliente->id);
	}

	public function ArchivosMasivosClientes(Request $request){
        if(false)
        {
            abort(403, 'No tiene permisos para esta acción');
        }

        $clientes = \App\Models\Cliente::all()->pluck("full_name_cedula","id");
        return view('clientes.archivos-masivos-clientes')->withClientes($clientes);
	}

	public function cargarArchivocliente(Request $request, $id)
	{
		$archivo = $request->file("archivo");
		$nombre = $archivo->getClientOriginalName();
		$entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
		$archivo->move(storage_path("app/archivos/"), $entrada->id);
		DB::table("archivos_clientes")->insert(["archivo_id" => $entrada->id, 'cliente_id' => $id]);
		\Alert::success("archivo Cargado Correctamente")->flash();
		return back();
	}

	public function cargarArchivoProceso(Request $request, $id)
	{
		$archivo = $request->file("archivo");
		$nombre = $archivo->getClientOriginalName();

		$entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
		$archivo->move(storage_path("app/archivos/"), $entrada->id);

		DB::table("archivos_procesos")->insert(["archivo_id" => $entrada->id, 'proceso_id' => $id]);
		\Alert::success("archivo Cargado Correctamente")->flash();
		return back();
	}

	public function cargarArchivoConsulta(Request $request, $id)
	{
		$archivo = $request->file("archivo");
		$nombre = $archivo->getClientOriginalName();
		$entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
		$archivo->move(storage_path("app/archivos/"), $entrada->id);
		DB::table("archivos_consultas")->insert(["archivo_id" => $entrada->id, 'consulta_id' => $id]);
		\Alert::success("archivo Cargado Correctamente")->flash();
		return back();
	}


	public function menuCliente(Request $request)
	{
		return view("clientes.formularios.menu");
	}

	public function ClientesTodos(Request $request)
	{
		if (Auth::user()->canAny(['Agregar Clientes', 'Editar Clientes','Eliminar Clientes']) || Auth::user()->hasRole('SuperAdmin')) {
			return redirect(url('admin/clientes'));
		}
		$clientes = \App\Models\Cliente::all();
		return view("clientes.todos")->withClientes($clientes);
	}

	public function verFacturas( Request $request)
	{
		$facturas =  Auth::user()->cliente->facturas;
		return view("clientes.formularios.facturas")->withFacturas($facturas);
	}


	public function verFacturaCliente( Request $request , $id)
	{
		$factura = \App\Models\Invoice::findorFail($id);
		$cliente = Auth::user()->cliente;
		if($cliente->id != $factura->cliente_id)
		{
			abort(403);
		}
		return view("clientes.ver-factura")
		->withFactura($factura)
		->withCliente($cliente);
	}

	public function verTicket(Request $request, $ticket_id)
	{
		$ticket = \app\Models\Tickets::findorFail($ticket_id);
		if($ticket->cliente_id != Auth::user()->cliente_id)
			abort(401);

		$comentarios =  $ticket->comentarios()->with("user")->publicos()->orderby("created_at","desc")->get();

		return view("clientes.formularios.ticket")
		->withTicket($ticket)
	    ->withComentarios($comentarios);
	}

	public function verNotificaciones(Request $request)
	{
	    $notificaciones = Auth::user()->notifications()->take(100)->get();
	    return view("clientes.ver-notificaciones")->withNotificaciones($notificaciones);
	}

	public function verNotificacion(Request $request , $id)
	{
	    $notificacion = \Illuminate\Notifications\DatabaseNotification::findorFail($id);
	    if (isset($notificacion->data['ticket_id']))
	    {
	        $redirect = url('clientes/ticket/'.$notificacion->data['ticket_id']);
	    }
	    else
	    {
	        $redirect = url('notificaciones');
	    }

	    $notificacion->markAsRead();
	    return redirect($redirect);
	}

	public function leerTodasNotificaciones(Request $request)
	{
	   Auth::User()->unreadNotifications->markAsRead();
	   return back();
	}

	public function profile(Request $request)
	{
	    return view('clientes.profile')->withUser(Auth::user());
	}

	public function profileUpdate(Request $request)
	{
	    $user = Auth::user();
	    $user->nombre =  $request->input('nombre');
	    $user->email =  $request->input('email');

	    if($request->has('password'))
	    {

	        if (!Hash::check($request->input('oldpassword'), Auth::user()->password)) {
	            Flash::Error('La contraseña no coincide con la actual');
	            return back();
	        }
	        if ($request->input('password') != $request->input('password_confirm')) {
	            Flash::Error('Las contraseñas no coinciden');
	            return back();
	        }
	        $user->password = Hash::make($request->input('password'));
	        Flash::Success("Contraseña Actualizada");
	        Funciones::sendMailPasswordChanged($user);
	    }
	    
	    $user->save();
	    Flash::Success("Usuario actualizado correctamente");
	    return  back();
	}

	public function addComentario(Request $request, $ticket_id)
	{
		$data = $request->only("texto");
		$data['publico'] = 1;
		$data['user_id'] = Auth::user()->id;
		$data['cliente_id'] = Auth::user()->cliente_id;
		$data['ticket_id'] = $ticket_id;
		$comentario = \App\Models\ComentariosTickets::Create($data);

		if ($request->hasFile('archivo')) {
			if ($request->file('archivo')->isValid()) {
		   		$nombre =  $request->file("archivo")->getClientOriginalName();
	   		  	$request->file('archivo')->move(public_path("archivos/ComentariosTickets/"), $nombre );
	   		  	$comentario->archivo =  $nombre;
			}
		}
		$comentario->save();
		return back();
	}
	
	public function deleteComentario(Request $request, $comentario_id)
	{
		$comentario = \App\Models\ComentariosTickets::findorFail($comentario_id);
		if($comentario->user_id != Auth::user()->id)
			abort(401);

		$comentario->delete();

		return response()->json(["status" => "OK"]);
	}
}
