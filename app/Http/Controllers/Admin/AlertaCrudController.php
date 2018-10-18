<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\AlertaRequest as StoreRequest;
use App\Http\Requests\AlertaRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AlertaCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Alerta");
        $this->crud->setRoute("admin/alertas");
        $this->crud->setEntityNameStrings('alerta', 'alertas');
        $this->crud->allowAccess('revisions');

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

		$this->crud->setFromDb();
        $this->crud->removeFields(['usuarios','emitido','user_id']);
        $this->crud->removeColumns(['usuarios']); // remove an array of columns from the stack


        $this->crud->addField([
            'type' => 'textarea',
            'name' => 'mensaje',
            'label' => "Mensaje de la notificación"
        ]);         
        $this->crud->addField([
            'type' => 'ckeditor',
            'name' => 'correo',
            'label' => "Cuerpo del Correo",
        ]);
        $this->crud->addField([
                'type' => 'select_from_array',
                'name' => 'emitido',
                'options' => ["0" => "No", "1" => "Emitida"],
                'label' => "Emitida?"
            ],'update');

        $this->crud->addField([
            'type' => 'text',
            'name' => 'programado',
            'attributes' => ['class'=>'form-control datetimepicker'],
            'label' => "Programada para:"
        ]);

        $this->crud->addField([
            'type' => 'text',
            'name' => 'culminacion',
            'attributes' => ['class'=>'form-control datetimepicker'],
            'label' => "Culmina  el:"
        ]);

        $this->crud->addField([
            'type' => 'usuarios_alertas',
            'name' => 'usuarios[]',
            'label' => "Usuarios:"
        ]);
        $this->crud->addField([
                'type' => 'select_from_array',
                'name' => 'inmediato',
                'options' => ["false" => "No", "true" => "Si"],
                'label' => "Emitida?"
            ],'create');


        $this->crud->removeColumns(['user_id','emitido']);

        $this->crud->addColumn([
            'type' => 'model_function',
            'function_name' => 'getUsuariosText',
            'label' => "Usuarios:"
        ]);
        $this->crud->addColumn([
               'label' => "Usuario Dueño:", // Table column heading
               'type' => "select",
               'name' => 'user', // the method that defines the relationship in your Model
               'entity' => 'user', // the method that defines the relationship in your Model
               'attribute' => "nombre", // foreign key attribute that is shown to user
               'model' => "App\User", // foreign key model
        ]);

        if (Input::get('no_emitidas', 'false') == 'true') 
        {
            $this->crud->addClause('where', 'emitido', '=', '0');
        }

        // $this->crud->addButtonFromModelFunction("top", "boton", "boton", "end");



    }

	public function store(StoreRequest $request)
	{
       $data= $request->except('inmediato');
       $alerta = \App\Models\Alerta::create($data);
       if($request->input('inmediato',false) == 'true')
       {
            $alerta->emitir();
       }
       \Alert::success(trans('backpack::crud.insert_success'))->flash();
       return back();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}

  public function verificarPermisos()
  {
      if(!Auth::user()->hasRole('Administrar Alertas') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['create']);
        $this->crud->denyAccess(['update']);
        $this->crud->denyAccess(['delete']);
      }
  }
}
