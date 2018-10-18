<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcesosMasivosCliente extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;
     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
	const fields = array(
		"user_id" => ["type" => "manual", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'], 
		"cliente_id" =>["type" => "manual", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'], 
		"proceso_masivo_id" => ["type" => "manual", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'], 
		"entidad_id" => ["type" => "manual", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"updated_at" => ["type" => "manual","label" => "actualizado el" , 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"fecha_agregado" => ["type" => "manual",'label' => 'fecha de Agregado' ,'background' => 'rgba(0,0,255,0.3)', 'color' => 'black', 'no_migrate' => true],
		"estado" => ["type" => "select", "options" => [ "Activo"=>"Activo", "Finalizado"=>"Finalizado", "Via Gobernativa"=>"Via Gobernativa", "Devuelto"=>"Devuelto"], 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"nota" => ["type" => "text","label" => "Notas", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"numero_carpeta" => ["type" => "text","label" => "Numero de Poderdante", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"numero_carpeta_2" => ["type" => "text","label" => "Numero de Carpeta", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		// "poder_desconocido"=> ["type" => "select", "label" => "PODER DESCONOCIDO", "options" => ["Si" => "Si","No" => "No", "No Aplica" => "No Aplica"], 'background' => 'rgba(255,0,0,.1)', 'color' => 'white'],
		"posee_cedula"=> ["type" => "select","label" => "CEDULA", "options" => ["Si" => "Si","No" => "No", "No Aplica" => "No Aplica"], 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"contrato"=> ["type" => "select", "options" => ["Si" => "Si","No" => "No", "No Aplica" => "No Aplica"], 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"hoja_de_datos"=> ["type" => "select", "options" => ["Si" => "Si","No" => "No", "No Aplica" => "No Aplica"], 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"poder_alcalde" => ["label" => "PODER ALCALDE" ,"type" => "select", "options" => ["Si" => "Si","No" => "No", "No Aplica" => "No Aplica"], 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"poder_procurador" => ["label"=>"poder procurador", "type" => "select", "options" => ["Si" => "Si","No" => "No", "No Aplica" => "No Aplica"], 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"poder_juez_laboral" => ["label" => "PODER JUEZ LABORAL", "type" => "select", "options" => ["Si" => "Si","No" => "No", "No Aplica" => "No Aplica"], 'background' => 'rgba(255,0,0,0.1)', 'color' => 'black'],
		"poder_juez_contencioso" => ["label" => "PODER JUEZ CONTENCIOSO ADMINISTRATIVO", "type" => "select", "options" => ["Si" => "Si","No" => "No", "No Aplica" => "No Aplica"], 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"poder_tutela" => ["label" => "PODER TUTELA","type" => "select", "options" => ["Si" => "Si","No" => "No", "No Aplica" => "No Aplica"], 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"envio_derecho_peticion" => ["type" => "date","label" => "Fecha de Envío Derecho de Petición", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"notificacion_respuesta_derecho_peticion" => ["type" => "date","label" => "Notificación de Respuesta de Derecho de Petición", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"fecha_radicado_derecho_peticion" => ["type" => "date","label" => "Fecha de Radicado Derecho de Petición", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"numero_radicado_derecho_peticion" => ["type" => "text" ,"label" => "NÚMERO DE RADICADO DEL DERECHO DE PETICIÓN", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"fecha_notificacion_respuesta_derecho_de_peticion" => ["type" => "date", "label" => "fecha notificacion respuesta derecho de peticion", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"fecha_de_respuesta_derecho_peticion" => ["type" => "date", "label" => "FECHA DE RESPUESTA DEL DERECHO DE  PETICIÓN", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"numero_radicado_respuesta_derecho_peticion"=>  ["type" => "text" ,"label" => "NÚMERO RADICADO RESPUESTA DEL DERECHO DE PETICIÓN", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"fecha_envio_recurso_reposicion" =>  ["type" => "date" ,"label" => "FECHA DEL ENVIÓ DEL RECURSO DE REPOSICION", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"fecha_radicacion_recurso_reposicion"=>  ["type" => "date" ,"label" => "FECHA DE RADICACIÓN DEL RECURSO DE REPOSICION", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"numero_radicacion_recurso_reposicion"=>  ["type" => "text" ,"label" => "NÚMERO DE RADICACIÓN DEL RECURSO DE REPOSICIÓN", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"fecha_notificacion_respuesta_recurso_de_reposicion" =>  ["type" => "date" ,"label" => "FECHA DE Notificacion respuesta recurso de reposicion", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"fecha_respuesta_recurso_reposicion" =>  ["type" => "date" ,"label" => "FECHA DE RESPUESTA DEL RECURSO DE REPOSICION", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],
		"numero_radicacion_respuesta_recurso_reposicion" =>  ["type" => "text" ,"label" => "NÚMERO DE RADICADO DE LA RESPUESTA DEL RECURSO DE REPOSICIÓN", 'background' => 'rgba(0,0,255,0.3)', 'color' => 'black'],



		//ETAPA EXTRAJUDICIAL

		"fecha_envio_solicitud_de_conciliacion" =>  ["type" => "text" ,"label" => "Fecha de Envio Solicitud de Conciliación", 'background' => 'rgba(0,255,0,0.3)', 'color' => 'black'],

		"fecha_de_radicacion_de_la_solicitud" =>  ["type" => "date" ,"label" => "FECHA DE RADICACIÓN DE LA SOLICITUD DE CONCILIACIÓN", 'background' => 'rgba(0,255,0,0.3)', 'color' => 'black'],

		"fecha_de_audiencia_de_la_solicitud" =>  ["type" => "date" ,"label" => "FECHA DE LA AUDIENCIA DE LA SOLICITUD DE CONCILIACIÓN", 'background' => 'rgba(0,255,0,0.3)', 'color' => 'black'],



		// PROCESO ORDINARIO ADMINISTRATIVO LABORAL


		"fecha_de_envio_de_la_demanda" =>  ["type" => "date" ,"label" => "Fecha de Envío de la demanda", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
		"fecha_radicado_demanda" =>  ["type" => "date" ,"label" => "Fecha de Radicado de la demanda", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
		"numero_radicado_demanda" =>  ["type" => "text" ,"label" => "NO. DE RADICADO DE LA DEMANDA", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
		"nuevo_numero_radicado_demanda" =>  ["type" => "text" ,"label" => "NUEVO NO. DE RADICADO DE DEMANDA", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
		"juzgado_que_avoca_conocimiento" =>  ["type" => "text" ,"label" => "JUZGADO QUE AVOCA CONOCIMIENTO", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
		"juzgado_que_continua_el_tramite" =>  ["type" => "text" ,"label" => "JUZGADO QUE CONTINUA EL TRÁMITE", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],

		"auto_inadmite_demanda"=> ["type" => "date","label" => "Fecha Auto Inadmite Demanda", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 

		"auto_inadmite_demanda_text"=> ["type" => "text","label" => "Auto Inadmite Demanda", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 

		"subsanacion_demanda"=> ["type" => "text","label" => "SUBSANACION DE LA DEMANDA", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"rechazo_demanda"=> ["type" => "date","label" => "RECHAZO DEMANDA", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		
		"apelacion_contra_rechazo_demanda"=> ["type" => "text","label" => "APELACION CONTRA EL RECHAZO DE DEMANDA", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 

		"tribunal_revoco"=> ["type" => "text","label" => "TRIBUNAL REVOCO?", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 

		"auto_admite_demanda"=> ["type" => "date","label" => "Fecha Auto Admite Demanda", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"auto_admite_demanda_text"=> ["type" => "text","label" => "Auto Admite Demanda", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		
		"gastos_procesales"=> ["type" => "text","label" => "Radicado Gastos Procesales", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"valor_gastos_procesales"=> ["type" => "text","label" => "Valor Gastos Procesales", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"traslado_excepciones"=> ["type" => "text","label" => "TRASLADO DE EXCEPCIONES", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"audiencia_inicial"=> ["type" => "text","label" => "Audiencia Inicial", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		// "tramite_oficios"=> ["type" => "text","label" => "Tramite de Oficios", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"audiencia_pruebas"=> ["type" => "text","label" => "Audiencia de Pruebas", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"alegatos_conclusion"=> ["type" => "text","label" => "Alegatos de Conclusión", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"sentencia_primera_instancia"=> ["type" => "text","label" => "Sentencia Primera Instancia", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"rad_recurso_apelacion"=> ["type" => "text","label" => "RAD. DEL RECURSO DE APELACION", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		"audiencia_conciliacion"=> ["type" => "text","label" => "Audiencia de Conciliación", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
		"tribunal_segunda_instancia"=> ["type" => "text","label" => "Tribunal Segunda Instancia", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 
		// "niega_recurso_apelacion"=> ["type" => "date","label" => "Fecha Auto Admite/ Niega Recurso de Apelacion", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'], 

		// "niega_recurso_apelacion_text"=> ["type" => "text","label" => "Auto Admite/ Niega Recurso de Apelacion", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],

		"alegatos_conclusion_segunda_instancia"=> ["type" => "text","label" => "ALEGATOS DE CONCLUSION 2DA INSTANCIA", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
		"sentencia_segunda_instancia"=> ["type" => "text","label" => "SENTENCIA SEGUNDA INSTANCIA", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
		"devolucion_juzgado_origen"=> ["type" => "text","label" => "DEVOLUCIÓN AL JUZGADO DE ORIGEN", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
		"conocimiento_consejo_de_estado"=> ["type" => "text","label" => "CONOCIMIENTO DEL CONSEJO DE ESTADO", 'background' => 'rgba(255,255,0,0.3)', 'color' => 'black'],
	);

	protected $table = 'procesos_masivos_clientes';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	protected $guarded = ['id','proceso_masivo','deleted_at','created_at','updated_at','cliente','entidad','editable','cedula','selected'];
	// protected $hidden = [];
    // protected $dates = [];
    protected $touches = ['ProcesoMasivo'];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function ProcesoMasivo(){
		return $this->belongsTo("\App\Models\ProcesoMasivo","proceso_masivo_id","id");
	}
	
	public function Cliente(){
		return $this->belongsTo("\App\Models\Cliente","cliente_id","id");
	}

	public function archivos()
	{
	 return $this->belongsToMany('App\Models\Archivos','archivos_clientes', 'cliente_id','archivo_id');
	}

	public function entidad(){
		return $this->belongsTo('\App\Models\Entidad',"entidad_id",'id');
	}
	public function getClienteName(){
		return  isset($this->cliente) ?$this->cliente->full_name : '' ;
	}
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| ACCESORS
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
