<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProcesoMasivoRequest as StoreRequest;
use App\Http\Requests\ProcesoMasivoRequest as UpdateRequest;

class ProcesoMasivoCrudController extends CrudController {

    public function setUp() {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel("App\Models\ProcesoMasivo");
        $this->crud->setRoute("admin/procesos-masivos");
        $this->crud->setEntityNameStrings('proceso masivo', 'procesos masivos');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'name' => 'id',
            'label' => '#ID',
            'type' => 'text'
        ]);
        $this->crud->setFromDb();
        $this->crud->removeField('user_id');
        $this->crud->removeColumns(['user_id','entidad_id']);

        $this->crud->addField(['name' => 'estado' , 'type' => 'select_from_array', 
            'options' => ['abierto' => 'Abierto', 'en curso' => 'En Curso' , 'completado' => 'Completado']]);


        $this->crud->addField([
            'name' => 'clientes',
            'label' => 'Clientes',
            'type' => 'select2ajax',
            'text' => 'full_name_cedula',
            'image' => 'foto_url',
            'description' => 'cedula',
            'url' => url('select2/clientes'),
            'multiple' => true,
        ]);

        $this->crud->addColumn([
            'name' => 'clientes_count',
            'label' => '# de Clientes',
            'type' => 'model_function',
            'function_name' => 'getClientesCountAttribute',
        ]);

        $this->crud->setColumnDetails('inicio', ['label' => 'Fecha Creacion del Caso']);
        $this->crud->setColumnDetails('cierre', ['label' => 'Cierre del Caso']);
        $this->crud->addField(['name'=> 'inicio', 'label' => 'Fecha Creacion del Caso'], 'both');
        $this->crud->addField(['name'=> 'cierre', 'label' => 'Cierre del Caso'], 'both');

        $this->crud->addButtonFromModelFunction('line', 'buttonProcesoClientes', 'buttonProcesoClientes', 'end'); 
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
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

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
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
    }

    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        // fallback to global request instance
        if (is_null($request)) {
            $request = \Request::instance();
        }

        // replace empty values with NULL, so that it will work with MySQL strict mode on
        foreach ($request->input() as $key => $value) {
            if (empty($value) && $value !== '0') {
                $request->request->set($key, null);
            }
        }

        // insert item in the db
        $item = $this->crud->create($request->except(['redirect_after_save', '_token']));

        if ($request->input('clientes') !== null ) {
            $item->clientes()->syncWithoutDetaching($request->input('clientes'));
        }
        
        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return \Redirect::to($this->crud->route.'/'.$item->getKey().'/edit');

            default:
                return \Redirect::to($request->input('redirect_after_save'));
        }
    }

    public function update(UpdateRequest $request,$id)
    {

        $response = parent::updateCrud();
        
        $model = $this->crud->model->find($id);
        if ($request->input('clientes') !== null ) {
            $model->clientes()->syncWithoutDetaching($request->input('clientes'));
        }
        // dd($model->clientes);
        
        return $response;
    }
}
