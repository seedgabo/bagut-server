<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\VehiculoRequest as StoreRequest;
use App\Http\Requests\VehiculoRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;

class VehiculoCrudController extends CrudController {

	public function __construct() {
    parent::__construct();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
    $this->crud->setModel("App\Models\Vehiculo");
    $this->crud->setRoute("admin/vehiculos");
    $this->crud->setEntityNameStrings('vehiculo', 'vehiculos');
    $this->verificarPermisos();
    $this->crud->allowAccess('revisions');

    $this->crud->setFromDb();

    $this->crud->addField(['name' => 'color' ,'label' => 'color del vehiculo']);
    $this->crud->addField(['name' => 'fecha_ingreso', 'label' => 'Fecha de Ingreso a la Empresa']);
    $this->crud->addField(['name' => 'direccion', 'type' => 'address']);
    $this->crud->addField(['name' => 'activo', 'type' => 'select_from_array', 'options' => ['1' => 'Si', '0' => 'No'], 'allows_null' => false]);

    $this->crud->addField([
      'name' => 'foto',
      'label' => 'Foto',
      'type' => 'foto_file'
      ]);


    $this->crud->removeColumns(['fecha_ingreso','activo','nota', 'foto']);
    $this->crud->addColumn(['name' => 'fecha_ingreso', 'label' => 'Fecha de Ingreso a la Empresa','type' => 'date']);
    $this->crud->addColumn(['name' => 'activo','label'=>'Activo','type'=>'check']);

        $this->crud->addColumn([
         'label' => "Foto",
         'type' => 'model_function',
         'name' => 'foto',
         'function_name' => 'getFoto',
      ]);

    $this->crud->addButtonFromModelFunction("line", "verDetallesButton", "verDetallesButton", "end");

  }

  public function store(StoreRequest $request)
  {
    $data = $request->except("_method","_token");
    $vehiculo = \App\Models\Vehiculo::create($data);
    Static::saveFoto($request , $vehiculo);
    \Alert::success(trans('backpack::crud.insert_success'))->flash();
    return redirect('admin/vehiculos');
  }

  public function update(UpdateRequest $request)
  {
    $vehiculo = \App\Models\Vehiculo::find($request->id);
    $data = $request->except("_method","_token");

    $vehiculo->fill($data);
    $vehiculo->save();
    Static::saveFoto($request , $vehiculo);
    \Alert::success(trans('backpack::crud.insert_success'))->flash();
    return redirect('admin/vehiculos');
  }

  public  static function saveFoto($request , $vehiculo)
  {
    if($request->hasFile('foto'))
    {
      $archivo = $request->file('foto');
      $nombre  =  $vehiculo->id . "." . $archivo->getClientOriginalExtension();
      $archivo->move(public_path("/img/vehiculos/"), $nombre);
      $vehiculo->foto = $nombre;
      $vehiculo->save();
    }
  }
  
  public function verificarPermisos()
  {
      if(!Auth::user()->can('Agregar Vehiculos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['create']);
      }
      if(!Auth::user()->can('Editar Vehiculos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['update']);
      }
      if(!Auth::user()->can('Eliminar Vehiculos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['delete']);
      }
  }
}
