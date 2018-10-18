<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProveedorRequest as StoreRequest;
use App\Http\Requests\ProveedorRequest as UpdateRequest;
use App\Models\Proveedor;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;

class ProveedorCrudController extends CrudController {

	public function setUp() {

    $this->crud->setModel("App\Models\Proveedor");
    $this->crud->setRoute("admin/proveedores");
    $this->crud->setEntityNameStrings(trans_choice('literales.proveedor',1),trans_choice('literales.proveedor',10));
    
    $this->verificarPermisos();
    $this->crud->allowAccess('revisions');

    $this->crud->setFromDb();

    $this->crud->addField(['name' => 'nombre', 'label' => 'Nombre del Proveedor','attributes' => ['required' => 'required']]);
    $this->crud->addField(['name' => 'bien_o_servicio', 'label' => 'Bien o Servicio Ofrecido']);
    $this->crud->addField(['name' => 'fecha_ingreso', 'label' => 'Fecha de Ingreso a la Empresa']);
    $this->crud->addField(['name' => 'documento', 'label' => 'NIT/ID','attributes' => ['required' => 'required']]);
    $this->crud->addField(['name' => 'email', 'type' => 'email']);
    $this->crud->addField(['name' => 'direccion', 'type' => 'address']);
    $this->crud->addField(['name' => 'activo', 'type' => 'select_from_array', 'options' => ['1' => 'Si', '0' => 'No'], 'allows_null' => false]);
    $this->crud->addField(['name' => 'tipo', 'type' => 'select_from_array', 'options' => Proveedor::tipos, 'allows_null' => false]);
    $this->crud->addField(['name' => 'foto','label' => 'Foto','type' => 'foto_file']);


    $this->crud->removeFields(['created_at']);
    $this->crud->removeColumns(['documento','fecha_ingreso','activo','nota','created_at','foto']);

    $this->crud->addColumn(['name' => 'documento', 'label' => 'NIT']);
    $this->crud->addColumn(['name' => 'fecha_ingreso', 'label' => 'Fecha de Ingreso a la Empresa','type' => 'date']);
    $this->crud->addColumn(['name' => 'activo','label'=>'Activo','type'=>'check']);
    $this->crud->addColumn(['name' => 'bien_o_servicio','label'=>'Bien o Servicio']);

    $this->crud->addColumn([
     'label' => "Foto",
     'type' => 'model_function',
     'name' => 'foto',
     'function_name' => 'getFoto',
     ]);

    $this->crud->addButtonFromModelFunction("line", "verDetallesButton", "verDetallesButton", "end");


    $this->crud->enableAjaxTable();

    $this->crud->addFilter([
        'type' =>'simple', 
        'name' =>'bien', 
        'label' =>'Proveedor de Bien'
        ], 
        false, 
        function () {
            $this->crud->addClause('whereIn' ,'tipo',['bien','bien_servicio']);
        }
        );

    $this->crud->addFilter([
        'type' =>'simple', 
        'name' =>'servicio', 
        'label' =>'Proveedor de Servicio'
        ], 
        false, 
        function () {
            $this->crud->addClause('whereIn' ,'tipo',['servicio','bien_servicio']);
        }
        );
  }

  public function store(StoreRequest $request)
  {
    $data = $request->except("_method","_token");
    $proveedor = \App\Models\proveedor::create($data);
    Static::saveFoto($request , $proveedor);
    \Alert::success(trans('backpack::crud.insert_success'))->flash();
    return redirect('admin/proveedores');
  }

  public function update(UpdateRequest $request)
  {
    $proveedor = \App\Models\proveedor::find($request->id);
    $data = $request->except("_method","_token");

    $proveedor->fill($data);
    $proveedor->save();
    Static::saveFoto($request , $proveedor);
    \Alert::success(trans('backpack::crud.insert_success'))->flash();
    return redirect('admin/proveedores');
  }

  public  static function saveFoto($request , $proveedor)
  {
    if($request->hasFile('foto'))
    {
      $archivo = $request->file('foto');
      $nombre  =  $proveedor->id . "." . $archivo->getClientOriginalExtension();
      $archivo->move(public_path("/img/proveedores/"), $nombre);
      $proveedor->foto = $nombre;
      $proveedor->save();
    }
  }

  public function verificarPermisos()
  {
    if(!Auth::user()->can('Agregar Proveedores') &&  !Auth::user()->hasRole('SuperAdmin'))
    {
      $this->crud->denyAccess(['create']);
    }
    if(!Auth::user()->can('Editar Proveedores') &&  !Auth::user()->hasRole('SuperAdmin'))
    {
      $this->crud->denyAccess(['update']);
    }
    if(!Auth::user()->can('Eliminar Proveedores') &&  !Auth::user()->hasRole('SuperAdmin'))
    {
      $this->crud->denyAccess(['delete']);
    }
  }
}
