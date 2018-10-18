<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ConductoresRequest as StoreRequest;
use App\Http\Requests\ConductoresRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;

class ConductoresCrudController extends CrudController {
	
	public function __construct() {
		parent::__construct();
		
		
		
		/*
		|--------------------------------------------------------------------------
						| BASIC CRUD INFORMATION
						|--------------------------------------------------------------------------
						*/ 
		
		$this->crud->setModel("App\Models\Conductores");
		$this->crud->setRoute("admin/conductores");
		$this->crud->setEntityNameStrings('conductor', 'conductores');
		$this->verificarPermisos();
		
		
		/*
		|--------------------------------------------------------------------------
						| BASIC CRUD INFORMATION
						|--------------------------------------------------------------------------
						*/
		
		$this->crud->setFromDb();
		
		// 		------ CRUD FIELDS
				        $this->crud->addField(['name' => 'cedula','type' => 'number','attributes' => ['required' => 'required']]);
		$this->crud->addField(['name' => 'nombres','type' => 'text','attributes' => ['required' => 'required']]);
		$this->crud->addField(['name' => 'apellidos','type' => 'text','attributes' => ['required' => 'required']]);
		$this->crud->addField(['name' => 'sexo','label'=>'Sexo','type'=>'select_from_array','options' => ['M' => 'Masculino', 'F' => 'Femenino'],'allows_null' => false,'attributes' => ['required' => 'required']]);
		$this->crud->addField(['name' => 'email','type' => 'email']);
		$this->crud->addField(['name' => 'nacimiento','type' => 'date','attributes' => ['required' => 'required']]);
		$this->crud->addField(['name' => 'estadoCivil','label'=>'Estado Civil','type'=>'select_from_array',
				          'options' => ['soltero' => 'Soltero', 'casado' => 'Casado','divorciado' => 'Divorciado','viudo' => 'Viudo'],'allows_null' => false]);
		$this->crud->addField(['name' => 'ingreso','label' => 'Fecha de Ingreso','type' => 'date']);
		$this->crud->addField(['name' => 'egreso','label' => 'Fecha de Egreso','type' => 'date']);
		
		$this->crud->addField([
				               'label' => "Eps",
				               'type' => 'select2',
				               'name' => 'eps_id', 
				               'entity' => 'eps', 
				               'attribute' => 'nombre',
				               'model' => "\App\Models\Eps" 
				            ]);
		$this->crud->addField([
				               'label' => "Arl",
				               'type' => 'select2',
				               'name' => 'arl_id', 
				               'entity' => 'arl', 
				               'attribute' => 'nombre',
				               'model' => "\App\Models\Arl" 
				            ]);
		$this->crud->addField([
				               'label' => "Fondo de Pensiones",
				               'type' => 'select2',
				               'name' => 'fondosDePensiones_id', 
				               'entity' => 'fondo', 
				               'attribute' => 'nombre',
				               'model' => "\App\Models\FondosDePensiones" 
				            ]);
		
		
		$this->crud->removeColumns(['email', 'nacimiento', 'sangre', 'sexo', 'estadoCivil', 'nota', 'direccion', 'ingreso', 'egreso', 'eps_id', 'arl_id', 'fondosDePensiones_id', 'ibc']);
		
		$this->crud->addColumn([
				               'label' => "Eps",
				               'type' => 'select',
				               'name' => 'eps_id', 
				               'entity' => 'eps', 
				               'attribute' => 'nombre',
				               'model' => "\App\Models\Eps" 
				            ]);
		
		$this->crud->addColumn([
				               'label' => "Arl",
				               'type' => 'select',
				               'name' => 'arl_id', 
				               'entity' => 'arl', 
				               'attribute' => 'nombre',
				               'model' => "\App\Models\Arl" 
				            ]);
		
		$this->crud->addField([
				          'name' => 'foto',
				          'label' => 'Foto',
				          'type' => 'foto_file'
				          ]);
		$this->crud->addColumn([
				         'label' => "Foto",
				         'type' => 'model_function',
				         'name' => 'foto',
				         'function_name' => 'getFoto',
				      ]);
		
		
	}
	
	public function store(StoreRequest $request)
		  {
		$data = $request->except("_method","_token");
		$conductor = \App\Models\conductores::create($data);
		Static::saveFoto($request , $conductor);
		\Alert::success(trans('backpack::crud.insert_success'))->flash();
		return redirect('admin/conductores');
	}
	
	public function update(UpdateRequest $request)
		  {
		$conductor = \App\Models\conductores::find($request->id);
		$data = $request->except("_method","_token");
		
		$conductor->fill($data);
		$conductor->save();
		Static::saveFoto($request , $conductor);
		\Alert::success(trans('backpack::crud.insert_success'))->flash();
		return redirect('admin/conductores');
	}
	
	public  static function saveFoto($request , $conductor)
		  {
		if($request->hasFile('foto'))
				    {
			$archivo = $request->file('foto');
			$nombre  =  $conductor->id . "." . $archivo->getClientOriginalExtension();
			$archivo->move(public_path("/img/conductores/"), $nombre);
			$conductor->foto = $nombre;
			$conductor->save();
		}
	}
	public function verificarPermisos()
		  {
		if(!Auth::user()->can('Agregar Conductores') &&  !Auth::user()->hasRole('SuperAdmin'))
				        {
			$this->crud->denyAccess(['create']);
		}
		if(!Auth::user()->can('Editar Conductores') &&  !Auth::user()->hasRole('SuperAdmin'))
				        {
			$this->crud->denyAccess(['update']);
		}
		if(!Auth::user()->can('Eliminar Conductores') &&  !Auth::user()->hasRole('SuperAdmin'))
				        {
			$this->crud->denyAccess(['delete']);
		}
	}
}
