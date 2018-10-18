<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProcesosMasivosClienteRequest as StoreRequest;
use App\Http\Requests\ProcesosMasivosClienteRequest as UpdateRequest;

class ProcesosMasivosClienteCrudController extends CrudController {

	public function setUp() {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $proceso_masivo_id = \Route::current()->parameter('proceso_masivo_id');
        $proceso = \App\Models\ProcesoMasivo::find($proceso_masivo_id);
        $this->crud->setModel("App\Models\ProcesosMasivosCliente");
        $this->crud->setRoute("admin/".$proceso_masivo_id  ."/procesos-masivos-clientes");
        $this->crud->setEntityNameStrings('Proceso ' . $proceso->titulo, 'Registros de ' . $proceso->titulo);

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

        $this->crud->addColumn([
            'label' => "Cliente", // Table column heading
            'type' => "model_function",
            'name' => 'cliente_id', // the column that contains the ID of that connected entity;
            'function_name' => 'getClienteName'
            ]);
        $this->crud->setFromDb();
        $this->crud->removeColumns(['user_id' ,'proceso_masivo_id']);
        $this->crud->removeFields(['user_id','proceso_masivo_id']);
        $this->crud->addField([
            'name' => 'cliente_id',
            'type' => 'select2',
            'entity' => 'cliente',
            'attribute' => "titulo",
            'model' => '\App\Models\Cliente',
            'attributes'  => ['disabled' => 'disabled']]);
        
        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Entidad",
            'type' => 'select2',
            'name' => 'entidad_id', // the method that defines the relationship in your Model
            'entity' => 'entidad', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Entidad", // foreign key model
        ]);
        $this->crud->addColumn([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Entidad",
            'type' => 'select',
            'name' => 'entidad_id', // the method that defines the relationship in your Model
            'entity' => 'entidad', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Entidad", // foreign key model
        ]);
        // $this->crud->addColumn([
        //     'label' => "Proceso", // Table column heading
        //     'type' => "select",
        //     'name' => 'proceso_masivo', // the column that contains the ID of that connected entity;
        //     'entity' => 'ProcesoMasivo', // the method that defines the relationship in your Model
        //     'attribute' => "titulo", // foreign key attribute that is shown to user
        //     'model' => "App\Models\ProcesoMasivo", // foreign key model
        //     ]);
		// ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);
        
        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        $this->crud->denyAccess(['create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable();
        
        
        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        $this->crud->addClause('where', 'proceso_masivo_id', '=', $proceso_masivo_id);
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->orderBy();
        // $this->crud->groupBy();
    }

    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        $id =\Route::current()->parameter('procesos_masivos_cliente');
        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;
    
        $this->data['id'] = $id;
    
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('crud::edit', $this->data);
    }

	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
