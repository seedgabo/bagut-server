<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PedidoRequest as StoreRequest;
use App\Http\Requests\PedidoRequest as UpdateRequest;
use App\Models\Pedido;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;

class PedidoCrudController extends CrudController {

	public function setUp() {
        $date_options = [
           'todayBtn' => true,
           'format' => 'yyyy-mm-dd',
           'language' => 'es'
        ];

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Pedido");
        $this->crud->setRoute("admin/pedidos");
        $this->crud->setEntityNameStrings(trans_choice('literales.pedido',1), trans_choice('literales.pedido',10));

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

		// $this->crud->setFromDb();

        $this->crud->addColumns([
            ["label" => "# Pedido", "name" => "numero_pedido"],
            ["name" => "fecha_envio","label" => "Fecha de Envio", "type" => "date"],
            ["name" => "fecha_pedido","label" => "Fecha de Pedido", "type" => "date"],
            ["name" => "estado" ,"label" => "Estado"],
            ["name"=> "descuento","type"=> "currency"], 
            ["name"=> "total","type"=> "currency"],
            ["name" => "items_count", 'type' => 'model_function',"label" => "# Items",'function_name' => 'getItemsCountAttribute'],
            ["name" => "listname", 'type' => 'model_function',"label" => "# Items",'function_name' => 'getListNameItemsAttribute']
        ]);

        $this->crud->addColumn([
            'name' => 'user_id',
            'label' => trans_choice('literales.vendedor',1),
            'type' => 'select',
            'entity' => 'User',
            'attribute' => 'nombre',
            'model' => "\App\User",
        ]);

        if (config('modulos.clientes')) {
            $this->crud->addField([
                'name' => 'cliente_id',
                'label' => 'Cliente',
                'type' => 'select2ajax',
                'text' => 'full_name_cedula',
                'image' => 'foto_url',
                'description' => 'cedula',
                'url' => url('select2/clientes'),
            ]);
            $this->crud->addColumn([
                'name' => 'cliente_id',
                'label' => trans_choice('literales.cliente',1),
                'type' => 'select',
                'entity' => 'Cliente',
                'attribute' => 'full_name_cedula',
                'model' => "\App\Models\Cliente",
            ]);
        }

        $this->crud->addFields([
            ['name'=>'numero_pedido' ,'label'=>'# Pedido', "default" => \App\Models\Pedido::max("id") + 1, 'attributes'=> ["required" => "required"]],            
            ['name'=>'fecha_pedido' ,'type'=>'date_picker', 'label'=> 'Fecha de Pedido','default'=>Carbon::today()->format('Y-m-d'), 'date_picker_options' => $date_options,'attributes'=> ["required" => "required"]],
            ['name'=>'fecha_envio' ,'type'=>'date_picker', 'label'=> 'Fecha de Envio', 'date_picker_options' => $date_options],
            ['name'=>'fecha_entrega' ,'type'=>'date_picker', 'label'=> 'Fecha de Recibido', 'date_picker_options' => $date_options],
            ['name'=>'direccion_envio' ,'type'=>'text', 'label' => 'Direccion de Envio'],
            ['name'=>'direccion_facturado' ,'type'=>'text', 'label' => 'Direccion de Facturacion'],
            ['name' =>'estado','type'=>'select_from_array', 'options'=>Pedido::estados,'attributes'=>['class'=> 'form-control select2']],
            ['name'=>'notas' ,'type'=>'textarea'],
        ]);

        $this->crud->addField([
            'name' => 'facturar',
            'type' => 'checkbox',
            'label' => 'Generar ' . trans_choice('literales.invoice',1).  ' a ' . trans_choice('literales.cliente',1),
            'value' => true,
        ],'create');

        $this->crud->addField([
            'name' => 'facturar',
            'type' => 'checkbox',
            'label' => 'Regenerar ' . trans_choice('literales.invoice',1).  ' a ' . trans_choice('literales.cliente',1),
            'value' => true,
        ],'update');


        $this->crud->addField([
            'name' => 'custom-ajax-form',
            'type' => 'view',
            'view' => 'pedidos/partials/custom-ajax-form'
        ]);



        $this->crud->addField([
            'name'=> 'user_id',
            'label' => trans_choice('literales.vendedor',1),
            'type' => 'select2',
            'attribute' => 'nombre',
            'model' => "\App\User",
        ]);

        $this->crud->addField([
            'name'=>'items',
            'type'=> 'view',
            'view'=> 'pedidos.form-pedidos',
            'data' => $this->crud->entry
        ]);

        $this->crud->addFilter(
            [ // select2 filter
              'name' => 'Estado',
              'type' => 'dropdown',
              'label'=> 'Estado'
            ], 
            function() {
                return Pedido::estados;
            }, function($value) { // if the filter is active
                $this->crud->addClause('where', 'estado', $value);
            }
        );


        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
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
        // $this->crud->denyAccess(['delete']);

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
        $this->crud->enableAjaxTable();
        
        
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

        $item->saveItems($request->input("items"));

        if ($request->input("facturar",true)== true ) {
            $item->facturar();
        }
        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        $item->facturar();
        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return \Redirect::to($this->crud->route.'/'.$item->getKey().'/edit');

            default:
                return \Redirect::to($request->input('redirect_after_save'));
        }
	}

	public function update(UpdateRequest $request)
	{
		$this->crud->hasAccessOrFail('update');

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

        // update the row in the db
         $this->crud->update($request->get($this->crud->model->getKeyName()),
                            $request->except('redirect_after_save', '_token'));
        $item = \App\Models\Pedido::find($request->input("id"));
        $item->saveItems($request->input("items"));


        if ($request->input("facturar",true) == true ) {
            $item->facturar();
        }

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        return \Redirect::to($this->crud->route);
	}
}
