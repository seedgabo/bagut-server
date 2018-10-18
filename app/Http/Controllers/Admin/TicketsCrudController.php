<?php namespace App\Http\Controllers\Admin;


use App\Http\Requests\TicketsRequest as StoreRequest;


use App\Http\Requests\TicketsRequest as UpdateRequest;


use Backpack\CRUD\app\Http\Controllers\CrudController;


use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Input;

use Carbon\Carbon;


class TicketsCrudController extends CrudController {

	public function setup() {
		// parent::__construct();
		$this->crud->setModel("App\Models\Tickets");
		$this->crud->setRoute("admin/tickets");
		$this->crud->setEntityNameStrings('Caso', 'Casos');
		$this->verificarPermisos();
		$this->crud->allowAccess('revisions');
		$this->crud->enableExportButtons();
		$this->crud->setFromDb();
		$this->crud->addField([
			'name' =>'user_id', 
			'type' =>'select', 
			'entity' =>'user', 
			'label' =>'Creador', 
			'attribute' =>'nombre', 
			'model' =>'App\Models\Usuarios'
			], 'both');
		$this->crud->addField([
			'name' =>'guardian_id', 
			'type' =>'select', 
			'entity' =>'guardian', 
			'label' =>'Responsable', 
			'attribute' =>'nombre', 
			'model' =>'App\Models\Usuarios'
			], 'both');
		$this->crud->addField([
			'name' =>'categoria_id', 
			'type' =>'categorias_ticket_radio', 
			'label' =>'Categoria', 
			'entity' =>'categoria', 
			'attribute' =>'nombre', 
			'model' =>'App\Models\categorias'
			], 'both');
		$this->crud->addField([
			'name' =>'estado', 
			'label' =>"Estado", 
			'type' =>'select_from_array', 
			'options' =>['abierto' =>'abierto', 'completado' =>'completado', 'en curso' =>'en curso', 'rechazado' =>'rechazado'], 
			'allows_null' =>false
			], 'both');
		$this->crud->addField([
			'name' =>'transferible', 
			'label' =>"Transferible", 
			'type' =>'select_from_array', 
			'options' =>['0' =>'No', '1' =>'Si'], 
			'allows_null' =>false
			], 'both');
		$this->crud->addField([
			'name' =>'encriptado', 
			'label' =>"Encriptado", 
			'type' =>'select_from_array', 
			'options' =>['0' =>'No', '1' =>'Si'], 
			'allows_null' =>false
			], 'both');
		$this->crud->addField([
			'name' =>'invitados_id', 
			'label' =>"Invitados", 
			'type' =>'select_invitados', 
			'allows_null' =>true
			], 'update');
		$this->crud->addField([
			'name' =>'canSetVencimiento', 
			'label' =>"El responsable puede cambiar la fecha de vencimiento", 
			'type' =>'select_from_array', 
			'options' =>['0' =>'No', '1' =>'Si'], 
			'allows_null' =>false
			], 'both');
		$this->crud->addField([
			'name' =>'canSetGuardian', 
			'label' =>"El responsable puede cambiar Transferir", 
			'type' =>'select_from_array', 
			'options' =>['0' =>'No', '1' =>'Si'], 
			'allows_null' =>false
			], 'both');
		$this->crud->addField([
			'name' =>'canSetEstado', 
			'label' =>"El responsable puede cambiar el estado", 
			'type' =>'select_from_array', 
			'options' =>['0' =>'No', '1' =>'Si'], 
			'allows_null' =>false
			], 'both');
		$this->crud->addField([
			'name' =>'contenido', 
			'type' =>'ckeditor'
			], 'both');
		$this->crud->addField([
			'label' =>"vencimiento", 
			'type' =>"text", 
			'name' =>'vencimiento', 
			'attributes' =>['class' =>'form-control datetimepicker']
			]);

		$this->crud->removeColumns(['transferible', 'encriptado', 'clave', 'archivo', 'user_id', 'guardian_id', 'categoria_id', 'canSetGuardian', 'canSetVencimiento', 'invitados_id', 'cliente_id']);

		$this->crud->addColumn([
			'label' =>"Usuario", 
			'type' =>"select", 
			'name' =>'Usuario', 
			'entity' =>'user', 
			'attribute' =>"nombre", 
			'model' =>"App\User", 
			]);
		$this->crud->addColumn([
			'label' =>"Guardian", 
			'type' =>"select", 
			'name' =>'Guardian', 
			'entity' =>'guardian', 
			'attribute' =>"nombre", 
			'model' =>"App\User", 
			]);
		$this->crud->addColumn([
			'label' =>"Categoria", 
			'type' =>"select", 
			'name' =>'Categorias', 
			'entity' =>'categoria', 
			'attribute' =>"nombre", 
			'model' =>"App\Models\Categorias", 
			]);
		$this->crud->addColumn([
			'label' =>"Ultimo Seguimiento", 
			'type' =>"model_function", 
			'function_name' =>'getLastSeguimiento', 
			]);
		
		$this->crud->addButtonFromModelFunction("line", "verDetalles", "verDetalles");
		
		$this->crud->addFilter([
			'type' =>'simple', 
			'name' =>'deleted', 
			'label' =>'Eliminados'
			], 
			false, 
			function () {
				$this->crud->addClause('trashed');
				$this->crud->addColumn(['name' =>'deleted_at', 'label' =>'Eliminado el', 'type' =>'datetime']);
				$this->crud->removeButton('delete');
				$this->crud->removeButton('verDetalles');
				$this->crud->addButtonFromModelFunction("line", "recover", "restoreItem");
				$this->crud->orderBy('deleted_at');
			}
			);
		$this->crud->addFilter([
			'type' =>'simple', 
			'name' =>'clientes', 
			'label' =>'De clientes'
			], 
			false, 
			function () {
				$this->crud->addClause('where', "cliente_id", "<>", "0");
				$this->crud->addClause('orwhere', "cliente_id", "<>", null);
				$this->crud->addClause('orwhere', "cliente_id", "<>", "");
			}
			);
		$this->crud->addFilter([
			'name' =>'Estado', 
			'type' =>'select2_multiple', 
			'label' =>'Estado'
			], 
			[
			"abierto" =>'abierto', 
			"en curso" =>'en curso', 
			"vencido" =>'vencido', 
			"completado" =>'completado', 
			"rechazado" =>'rechazado', 
			], function($values) {
				foreach (json_decode($values) as $value) {
					$this->crud->addClause('orWhere', 'estado', $value);
				}
			}
			);

		if (config('modulos.clientes')){
			$this->crud->addField([
				'name' =>'cliente_id', 
				'type' =>'select2', 
				'entity' =>'cliente', 
				'label' =>'Cliente asignado: (Opcional)', 
				'attribute' =>'full_name', 
				'model' =>'App\Models\Cliente'
				], 'both');
			$this->crud->addColumn([
				'name' =>'cliente_id', 
				'type' =>'select', 
				'entity' =>'cliente', 
				'label' =>'Cliente asignado', 
				'attribute' =>'full_name', 
				'model' =>'App\Models\Cliente',
				'searchLogic' => false,
				], 'both');
		}

		$this->crud->removeFields(['clave', 'archivo']);
	}
	
	
	public function store(StoreRequest $request) {
		$data = $request->except("vencimiento","redirect_after_save","_token","_method");

		
		$ticket = new \App\Models\Tickets;
		$ticket->fill($data);
		if($request->has('vencimiento') &&  $request->input('vencimiento') != "____-__-__ __:__:__")
			$ticket->vencimiento = Carbon::parse($request->input('vencimiento'));

		if ($request->has('invitados_id')) {
			$ticket->invitados_id =$request->input("invitados_id");
		}
		$ticket->save();
		return  redirect('admin/tickets');
	}
	
	
	public function update(UpdateRequest $request, $id) {
		$data = $request->except("vencimiento","redirect_after_save","_token","_method");

		
		$ticket =\App\Models\Tickets::find($id);
		$ticket->fill($data);
		if($request->has('vencimiento') &&  $request->input('vencimiento') != "____-__-__ __:__:__")
			$ticket->vencimiento = Carbon::parse($request->input('vencimiento'));

		if ($request->has('invitados_id')) {
			$ticket->invitados_id =$request->input("invitados_id");
		}
		$ticket->save();
		return  redirect('admin/tickets');
	}
	
	
	public function verificarPermisos() {
		if ( !Auth::user()->can('Agregar Casos') && !Auth::user()->hasRole('SuperAdmin')) {
			$this->crud->denyAccess(['create']);
		}
		if ( !Auth::user()->can('Editar Casos') && !Auth::user()->hasRole('SuperAdmin')) {
			$this->crud->denyAccess(['update']);
		}
		if ( !Auth::user()->can('Eliminar Casos') && !Auth::user()->hasRole('SuperAdmin')) {
			$this->crud->denyAccess(['delete']);
		}
	}
	
	
}
