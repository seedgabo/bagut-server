<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tickets;
use Carbon\Carbon;
use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
class DashboardController extends Controller
{
	protected $data = []; 
	
	public function __construct()
	{
		$this->middleware('auth');
	    $this->middleware('isAdmin');
	}
	public function dashboard()
	{
		$desde = Input::get('desde', Carbon::now()->startOfMonth());
		$hasta = Input::get('hasta', Carbon::now()->tomorrow());
		$usuarios = Input::get('usuarios', []);
		$limit = Input::get('limit',20);

		$chartPorEstados = 
		Charts::create('pie','highcharts')
		->title(trans_choice('literales.ticket',10) .' por Estado')
		->labels(['Abiertos','Completados'])
		->height(300)->responsive(false)
		->values([$this->getSinCompletar() , $this->getCompletados()]);

		$masComentados = $this->getMasComentados($limit);
		$chartMasComentados = 
		Charts::create('bar','highcharts')
		->title(trans_choice('literales.ticket',10) .' con mas '. trans_choice('literales.comentario',10) )
		->elementLabel(trans_choice('literales.ticket',10))
		->labels($masComentados->pluck('titulo'))
		->height(300)->responsive(false)
		->values($masComentados->pluck('count'));

		$documentosMasDescargados= $this->getMasDescargados($limit);
		$chartMasDescargados = 
		Charts::create('pie','highcharts')
		->title(trans_choice('literales.documento',10) .' mas descargados')
		->labels($documentosMasDescargados->pluck('titulo'))
		->height(300)->responsive(false)
		->values($documentosMasDescargados->pluck('count'));

		$ticketsPorVencer = $this->getTicketsPorVencer(10);
		$ticketsVencidos = $this->getTicketsVencidos(10);


		$chartVentasPorDepartamento =
		Charts::create('geo','google')
		->region('CO')
		->title(trans_choice('literales.pedido',10) .' por ' .trans_choice('literales.entidad',1))
		->labels(['Cundinamarca','Antioquia','Santander','Amazonas'])
		->colors(['#00E641','#FF0000'])
		->height(280)->responsive(false)
		->values([10,15,30,1]);
		$chartVentasPorDepartamento->resolution = "provinces";
        // $chartVentasPorDepartamento->displayMode = "markers";

		return view('backpack::dashboard')
		->withDesde($desde)
		->withHasta($hasta)
		->withUsuarios($usuarios)

		->with("chartPorEstados", $chartPorEstados)    
		->with("chartMasComentados", $chartMasComentados)    
		->with("chartMasDescargados", $chartMasDescargados)    
		->with("chartVentasPorDepartamento", $chartVentasPorDepartamento)

		->with("ticketsPorVencer",$ticketsPorVencer)
		->with("ticketsVencidos",$ticketsVencidos);
	}

	private function getSinCompletar()
	{
		$desde = Input::get('desde', Carbon::now()->startOfMonth());
		$hasta = Input::get('hasta', Carbon::now()->tomorrow());
		$usuarios = Input::get('usuarios', []);

		$ticketsPorEstadoSinCompletar  =\App\Models\Tickets::where("estado","<>","completado")
		->whereBetween("created_at",[$desde,$hasta]);

		if(sizeof($usuarios) >0)
		{
			$ticketsPorEstadoSinCompletar = $ticketsPorEstadoSinCompletar->whereIn("guardian_id",$usuarios);
		}

		$ticketsPorEstadoSinCompletar = $ticketsPorEstadoSinCompletar->count();
		return $ticketsPorEstadoSinCompletar;
	}

	private function getCompletados()
	{
		$desde = Input::get('desde', Carbon::now()->startOfMonth());
		$hasta = Input::get('hasta', Carbon::now()->tomorrow());
		$usuarios = Input::get('usuarios', []);

		$ticketPorEstadoCompletados  =\App\Models\Tickets::where("estado","=","completado")
		->whereBetween("created_at",[$desde,$hasta]);

		if(sizeof($usuarios) >0)
		{
			$ticketPorEstadoCompletados = $ticketPorEstadoCompletados->whereIn("guardian_id",$usuarios);
		}

		$ticketPorEstadoCompletados = $ticketPorEstadoCompletados->count();
		return $ticketPorEstadoCompletados;
	}

	private function getMasComentados($limit)
	{
		$desde = Input::get('desde', Carbon::now()->startOfMonth());
		$hasta = Input::get('hasta', Carbon::now()->tomorrow());
		$usuarios = Input::get('usuarios', []);

		$q = \App\Models\ComentariosTickets::select(DB::raw('count(*) as count'),'tickets.titulo')
		->groupBy("ticket_id")
		->rightjoin("tickets","tickets.id","=","comentarios_tickets.ticket_id")
		->orderby('count','desc')
		->whereBetween("comentarios_tickets.created_at",[$desde,$hasta]);

		if (sizeof($usuarios) >0) {
			$q->whereIn("comentarios_tickets.user_id",$usuarios);
		}

		return $q->limit($limit)
		->get();
	}

	private function getMasDescargados($limit)
	{
		$desde = Input::get('desde', Carbon::now()->startOfMonth());
		$hasta = Input::get('hasta', Carbon::now()->tomorrow());
		$usuarios = Input::get('usuarios', []);
		$q = \App\Models\Auditorias::select(DB::raw('count(*) as count'),'documentos.titulo')
		->where('tipo',"=","descarga")
		->groupBy('documento_id')
		->rightJoin('documentos','documentos.id',"=",'auditorias.documento_id')
		->orderBy('count','desc')
		->whereBetween("auditorias.created_at",[$desde,$hasta]);

		if (sizeof($usuarios) >0) {
			$q->whereIn("auditorias.user_id",$usuarios);
		}

		return $q->limit($limit)
		->get();
	}

	private function getTicketsPorVencer($limit)
	{
		$desde = Input::get('desde', Carbon::now()->startOfMonth());
		$hasta = Input::get('hasta', Carbon::now()->tomorrow());
		$usuarios = Input::get('usuarios', []); 
		$q = Tickets::where("vencimiento",">", $hasta);
		if(sizeof($usuarios)>0)
			$q = $q->whereIn("guardian_id",$usuarios);
		$tickets= $q->limit($limit)->orderBy("vencimiento", "asc")->get();
		return $tickets;
	}

	private function getTicketsVencidos($limit)
	{
		$desde = Input::get('desde', Carbon::now()->startOfMonth());
		$hasta = Input::get('hasta', Carbon::now()->tomorrow());
		$usuarios = Input::get('usuarios', []); 
		$q = Tickets::where("vencimiento","<=", Carbon::now())->where("estado","<>","completado");
		if(sizeof($usuarios)>0)
			$q = $q->whereIn("guardian_id",$usuarios);
		$tickets= $q->limit($limit)->orderBy("vencimiento", "asc")->get();
		return $tickets;
	}
}
