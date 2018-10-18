<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ConsultaRequest as StoreRequest;
use App\Http\Requests\ConsultaRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ConsultaCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        $this->crud->setModel("App\Models\Consulta");
        $this->crud->setRoute("admin/consultas");
        $this->crud->setEntityNameStrings('consulta', 'consultas');
        $this->crud->allowAccess("revisions");
        $this->verificarPermisos();

        $this->crud->addField([
           'label' => "Cliente",
           'type' => 'select2',
           'name' => 'cliente_id',
           'attribute' => 'full_name',
           'model' => "\App\Models\Cliente" 
          ]);
        $this->crud->addField([
           'label' => "Abogado Asociado",
           'type' => 'select2',
           'name' => 'user_id',
           'attribute' => 'nombre',
           'model' => "\App\User" 
          ]);

        $this->crud->addField(['name' => 'consulta','label'=>'Consulta','type'=>'ckeditor', 'attributes' => ['required' => 'required']]);

        $this->crud->addField(['name' => 'fecha_consulta','label'=>'Fecha De Consulta','type'=>'text', 'attributes' => ['required' => 'required', 'class' => 'form-control datepicker']]);

        $this->crud->addField(['name' => 'detalles','label'=>'Detalles','type'=>'textarea', 'attributes' => []]);

        $this->crud->addField(['name' => 'descripcion','label'=>'Descripción','type'=>'textarea', 'attributes' => []]);

        $this->crud->addField(['name' => 'notas','label'=>'Notas','type'=>'textarea', 'attributes' => []]);


        $this->crud->addColumn([
           'label' => "Cliente",
           'type' => 'select',
           'name' => 'cliente_id',
           'entity' =>  'cliente',
           'attribute' => 'full_name',
           'model' => "\App\Models\Cliente" 
          ]);
        $this->crud->addColumn([
           'label' => "Abogado Asociado",
           'type' => 'select',
           'name' => 'user_id',
           'entity' =>  'user',
           'attribute' => 'nombre',
           'model' => "\App\User" 
          ]);
        $this->crud->addColumn([ 'name' =>'fecha_consulta',  'type' => 'date ']);
        
        $this->crud->addColumn('consulta');

        $this->crud->addColumn([
             'label' => "Seguimiento",
             'type' => "model_function",
             'function_name' => 'verButtonTicket', 
          ]);

        $this->crud->addButtonFromModelFunction("line", "VerCliente", "getButtonVerCliente", "end");

        $this->crud->addButtonFromModelFunction("line", "verDetalles", "getButtonverDetalles", "end");

        if (Input::has('cliente')) 
        {
          $cliente = \App\Models\Cliente::find(Input::get('cliente'));
          $this->crud->setEntityNameStrings('Consulta', 'Consulta de: ' . $cliente->full_name);
          $this->crud->addClause('where', 'cliente_id', '=', $cliente->id);
          $this->crud->addField(['name'=>'redirect', 'type'  => 'hidden', 'value' => url('admin/ver-cliente/'. $cliente->id)]);
        }
        if(Input::has('user_id'))
        {
          $this->crud->addClause('where', 'user_id', '=', Input::get('user_id'));
        }
    }

	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}

    public function verificarPermisos()
    {
          if(!Auth::user()->can('Agregar Procesos') &&  !Auth::user()->hasRole('SuperAdmin'))
          {
            $this->crud->denyAccess(['create']);
          }
          if(!Auth::user()->can('Editar Procesos') &&  !Auth::user()->hasRole('SuperAdmin'))
          {
            $this->crud->denyAccess(['update']);
          }
          if(!Auth::user()->can('Eliminar Procesos') &&  !Auth::user()->hasRole('SuperAdmin'))
          {
            $this->crud->denyAccess(['delete']);
          }
    }
}
