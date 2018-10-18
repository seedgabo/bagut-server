<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\RecomendacionRequest as StoreRequest;
use App\Http\Requests\RecomendacionRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class RecomendacionCrudController extends CrudController {

	public function __construct() {
        parent::__construct();
        $this->verificarPermisos();

        $this->crud->setModel("App\Models\Recomendacion");
        $this->crud->setRoute("admin/recomendaciones");
        $this->crud->setEntityNameStrings('recomendacion', 'recomendaciones');
        $this->crud->allowAccess('revisions');

		  $this->crud->setFromDb();

        $this->crud->removeFields(['user_id']);
        $this->crud->removeColumns(['caso_id', 'user_id']);
        
        if (Input::has('caso_id')) {
           $this->crud->addField([
               'label' => "Caso Médico",
               'type' => 'select2',
               'name' => 'caso_id', 
               'entity' => 'caso_id', 
               'attribute' => 'titulo_caso_fecha',
               'model' => "\App\Models\Casos_medicos",
               'attributes' => ['disabled' => 'disabled']
          ]);
          $this->crud->addField([
               'type' => 'custom_html',
               'name' => 'caso', 
               'value' => '<input type="hidden" name="caso_id" value="'. Input::get('caso_id') .'">', 
          ]);
          $this->crud->addField(['name'=>'redirect', 'type'  => 'hidden', 'value' => url('admin/ver-caso/'. Input::get('caso_id'))]);
        }
        else
        {
              $this->crud->addField([
                   'label' => "Caso Médico",
                   'type' => 'select2',
                   'name' => 'caso_id', 
                   'entity' => 'caso', 
                   'attribute' => 'titulo_caso_fecha',
                   'model' => "\App\Models\Casos_medicos"
              ]); 
        }

        $this->crud->addField([
                'label' => "Recomendación",
                'type' => 'textarea',
                'name' => 'descripcion'
            ]);


        $this->crud->addColumn([
             'label' => "Caso Médico",
             'type' => 'select',
             'name' => 'caso_id', 
             'entity' => 'caso', 
             'attribute' => 'titulo_caso',
             'model' => "\App\Models\Casos_medicos",
        ]);

        $this->crud->addColumn([
             'label' => "Médico",
             'type' => 'select',
             'name' => 'user_id', 
             'entity' => 'user', 
             'attribute' => 'nombre',
             'model' => "\App\User",
        ]); 


        $this->crud->addButtonFromModelFunction("line", "VerCaso", "getButtonVerCaso", "end");
    }

	public function store(StoreRequest $request)
	{
		$data  = $request->except("_token");
        $data['user_id'] = Auth::user()->id;
        \App\Models\Recomendacion::create($data);
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect('admin/ver-caso/' . $data['caso_id']);
	}

	public function update(UpdateRequest $request)
	{
        $data  = $request->except("_token");
        \App\Models\Recomendacion::create($data);
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect('admin/ver-caso/' . $data['caso_id']);
	}

  public function verificarPermisos()
  {
      if(!Auth::user()->can('Agregar Recomendaciones') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['create']);
      }
      if(!Auth::user()->can('Editar Recomendaciones') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['update']);
      }
      if(!Auth::user()->can('Eliminar Recomendaciones') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['delete']);
      }
  }
}
