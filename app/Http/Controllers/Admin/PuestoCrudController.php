<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\PuestoRequest as StoreRequest;
use App\Http\Requests\PuestoRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;

class PuestoCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Puesto");
        $this->crud->setRoute("admin/puestos");
        $this->crud->setEntityNameStrings('puesto', 'puestos');

        $this->verificarPermisos();


		$this->crud->setFromDb();

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
      if(!Auth::user()->can('Agregar Puestos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['create']);
      }
      if(!Auth::user()->can('Editar Puestos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['update']);
      }
      if(!Auth::user()->can('Eliminar Puestos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['delete']);
      }
  }
}
