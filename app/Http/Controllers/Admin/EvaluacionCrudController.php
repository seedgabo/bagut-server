<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\EvaluacionRequest as StoreRequest;
use App\Http\Requests\EvaluacionRequest as UpdateRequest;
use App\Models\Evaluacion;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Collective\Html\textarea;
use Illuminate\Support\Facades\Auth;

class EvaluacionCrudController extends CrudController {

	public function __construct() {
        parent::__construct();


        $this->crud->setModel("App\Models\Evaluacion");
        $this->crud->setRoute("admin/evaluaciones");
        $this->crud->setEntityNameStrings('evaluacion', 'evaluaciones');
        $this->verificarPermisos();
        $this->crud->allowAccess('revisions');
		// $this->crud->setFromDb();

        $this->crud->addField(['name' => 'nombre','label'=>'Nombre de la prueba','type'=>'text','attributes' => ['required' => 'required']]);
        $this->crud->addField(['name' => 'descripcion','label'=>'Descripción','type'=>'textarea','attributes' => []]);
        $this->crud->addField(['name' => 'tipo','label'=>'Tipo (a quien va dirigida la evaluación)','type'=>'select_from_array','options' => Evaluacion::tipos,'allows_null' => false]);

        $this->crud->addField(['name' => 'activo','label'=>'Activo','type'=>'select_from_array','options' => ['1' => 'Si', '0' => 'No'],'allows_null' => false]);


         $this->crud->addField([ 
            'name' => 'opciones',
            'label' => 'Registros de la Prueba',
            'type' => 'table',
            'entity_singular' => 'opcion', 
            'columns' => [
                'name' => 'Nombre',
                'desc' => 'Descripción',
            ],
            'max' => -1, 
            'min' => -1 
        ]);


        $this->crud->addColumn(['name' => 'nombre','label'=>'Nombre de la Prueba','type'=>'text','attributes' => ['required' => 'required']]);
        $this->crud->addColumn(['name' => 'descripcion','label'=>'Descripción','type'=>'textarea','attributes' => []]);
        $this->crud->addColumn(['label' => "Tipo",'type' => "model_function",'function_name' => 'getTipo','attribute' => 'Tipo']);
        $this->crud->addColumn(['name' => 'opciones','label'=>'Registros','type'=>'multidimensional_array','visible_key' => 'name']);


        $this->crud->addColumn(['name' => 'activo','label'=>'Activo','type'=>'check']);

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
        if(!Auth::user()->can('Agregar Evaluaciones') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['create']);
        }
        if(!Auth::user()->can('Editar Evaluaciones') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['update']);
        }
        if(!Auth::user()->can('Eliminar Evaluaciones') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['delete']);
        }
    }
}
