<?php

namespace App\Http\Controllers;

use App\Funciones;
use App\Http\Requests;
use App\Http\Requests\CreateTicketsRequest;
use App\Http\Requests\UpdateTicketsRequest;
use App\Models\Tickets;
use App\Repositories\TicketsRepository;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use InfyOm\Generator\Controller\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class TicketsController extends AppBaseController
{
    /** @var  TicketsRepository */
    private $ticketsRepository;

    function __construct(TicketsRepository $ticketsRepo)
    {
        $this->ticketsRepository = $ticketsRepo;
        $this->middleware('auth');
    }

    public function store(Request $request, $ajax = false)
    {
        if($request->hasFile('archivo') && $request->get("encriptado") == "true" && !$request->has("clave"))
        {
            Flash::error("Debe Ingresar Una contraseña para encriptar el archivo");
            return back();
        }
        
        $input = $request->except("archivo","enriptado","clave","vencimiento", "tipo","consulta","proceso");
        if (!isset($input['user_id'])) {
            $input['user_id'] = Auth::user()->id;
        }
        $ticket = \App\Models\Tickets::create($input);
        if($request->has('vencimiento')  && $request->vencimiento  != "")
        {
            $ticket->vencimiento = $request->vencimiento;
        }

        if($request->hasFile('archivo'))
        {   
            $nombre = $request->file("archivo")->getClientOriginalName();

            // Si Se pidio Encriptar El Archivo
            if($request->get("encriptado") == "true")
            {
                $ticket->encriptado = true;
                $ticket->archivo = $nombre;       
                $ticket->clave = $request->get("clave");              

                $encriptado = Crypt::encrypt(file_get_contents($request->file("archivo")));
                Storage::put("tickets/". $ticket->id . "/" . $nombre , $encriptado);

                Flash::success('Archivo Encriptado');
            }
            // Si no
            else
            {
                $request->file('archivo')->move(public_path("archivos/tickets/" . $ticket->id . "/"), $nombre );
                $ticket->archivo = $nombre;                
            }

        }
        $ticket->save();

        Funciones::sendMailNewTicket($ticket, $ticket->user, $ticket->guardian);
        Flash::success('Ticket Agregado correctamente.');
        
        if($request->has('tipo'))
        {
            if($request->input('tipo') == "proceso")
            {
               return  $this->storeProceso($request , $ticket, $ajax);
            }
            if($request->input('tipo') == "consulta")
            {
                return $this->storeConsulta($request, $ticket, $ajax);
            }
        }

        $this->variablesOcultas($ticket);

        if($ajax)
            return $ticket;
        else
            return redirect('mis-tickets');
    }

    public function storeAjax(Request $request)
    {
        $ticket = $this->store($request, true);
        return response()->json($ticket);
    }

    public function update($id, Request $request)
    {
        $tickets = $this->ticketsRepository->findWithoutFail($id);
        if (empty($tickets)) {
            Flash::error('Tickets no encontrado');

            return redirect(route('tickets.index'));
        }

        $tickets = $this->ticketsRepository->update($request->except("archivo"), $id);

        if($request->hasFile('archivo'))
        {
            $nombre = $request->file("archivo")->getClientOriginalName();
            $request->file('archivo')->move(public_path("archivos/tickets/"), $nombre );
            $tickets->archivo = $nombre;
            $tickets->save();
        }

        Flash::success('Tickets guardado correctamente.');
        

        if($request->ajax())
            return $ticket;
        else
            return back();
    }

    public function destroy($id)
    {
        $tickets = $this->ticketsRepository->findWithoutFail($id);

        if (empty($tickets)) {
            Flash::error('Tickets no encontrado');

            return redirect(route('tickets.index'));
        }

        $this->ticketsRepository->delete($id);

        Flash::success('Tickets eliminado correctamente.');
        \App\Models\Proceso::where("ticket_id", $id)->delete();
        \App\Models\Consulta::where("ticket_id", $id)->delete();
        return redirect(route('tickets.index'));
    }


    

    private function  storeProceso($request, $ticket, $ajax = false)
    {
        $proceso =  new \App\Models\Proceso($request->input('proceso'));
        $proceso->ticket_id = $ticket->id;
        $proceso->cliente_id = $request->input('cliente_id');
        $proceso->descripcion = $ticket->contenido;
        $proceso->user_id = $ticket->user_id;
        $proceso->save();
        if($ajax)
            return $ticket;
        else
            return redirect("ver-proceso/" .$proceso->id);
    }

    private function  storeConsulta($request, $ticket, $ajax = false)
    {
        $consulta =  new \App\Models\Consulta($request->input('consulta'));
        $consulta->ticket_id = $ticket->id;
        $consulta->cliente_id = $request->input('cliente_id');
        $consulta->descripcion = $ticket->contenido;
        $consulta->user_id = $ticket->user_id;
        $consulta->save();

        if($ajax)
            return $ticket;
        else
            return redirect("ver-consulta/" .$consulta->id);
    }

    private function variablesOcultas($ticket){
        if(Input::has('proceso_masivo_cliente_id')){
            $proceso = ProcesosMasivosCliente::find(Input::get('proceso_masivo_cliente_id'));
            $proceso->ticket_id =  $ticket->id;
        }

        if(Input::has('clientes_id')){
            $ticket->clientes()->attach(explode(",",Input::get("clientes_id")));
        }
    }


}
