<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProcesoRequest as StoreRequest;
use App\Http\Requests\ProcesoRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ProcesoCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        $this->crud->setModel("App\Models\Proceso");
        $this->crud->setRoute("admin/procesos");
        $this->crud->setEntityNameStrings('proceso', 'procesos');
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

        $this->crud->addField(['name' => 'radicado','label'=>'Radicado','type'=>'text']);

        $this->crud->addField(['name' => 'juzgado_instancia_1','label'=>'Primera Instancia Juzgado','type'=>'textarea']);

        $this->crud->addField(['name' => 'juzgado_instancia_2','label'=>'Segunda Instancia Juzgado','type'=>'textarea']);

        $this->crud->addField(['name' => 'fecha_proceso','label'=>'Fecha del Proceso','type'=>'text', 'attributes' => ['class' => 'datepicker form-control']]);

        $this->crud->addField(['name' => 'fecha_cierre','label'=>'Fecha de Cierre del Proceso','type'=>'text', 'attributes' => ['class' => 'datepicker form-control']]);

        $this->crud->addField(['name' => 'descripcion','label'=>'Descripción del Caso','type'=>'ckeditor']);

        $this->crud->addField(['name' => 'notas','label'=>'Nota','type'=>'textarea']);

        $this->crud->addField(['name' => 'demandado','label'=>'Demandado','type'=>'text']);
 
        $this->crud->addField(['name' => 'demandante','label'=>'Demandante','type'=>'text']);



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

        $this->crud->AddColumns([ 
            'radicado', 
            ['name' => 'demandado','label' => "Demandado"], 
            ['name' => 'demandante','label' => "Demandante"], 
            ['label' => 'Primera instancia Juzgado', 'name' => 'juzgado_instancia_1'], 
            ['label' => 'Segunda instancia Juzgado', 'name' => 'juzgado_instancia_2'],
        ]);


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
          $this->crud->setEntityNameStrings('Proceso', 'Proceso de: ' . $cliente->full_name);
          $this->crud->addClause('where', 'cliente_id', '=', $cliente->id);
          $this->crud->addField(['name'=>'redirect', 'type'  => 'hidden', 'value' => url('admin/ver-cliente/'. $cliente->id)]);
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
