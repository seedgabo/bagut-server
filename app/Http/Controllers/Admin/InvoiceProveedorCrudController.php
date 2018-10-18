<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InvoiceProveedorRequest as StoreRequest;
use App\Http\Requests\InvoiceProveedorRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class InvoiceProveedorCrudController extends CrudController {

	public function setUp() {

    /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
    $this->crud->setModel("App\Models\InvoiceProveedor");
    $this->crud->setRoute("admin/invoices-proveedores");
    $this->crud->setEntityNameStrings(trans_choice('literales.invoice',1),trans_choice('literales.invoice',10));
    $this->verificarPermisos();

    /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

    $this->crud->setFromDb();
    $this->crud->removeColumns(['user_id','proveedor_data','pie','orden_compra','archivo_id','regimen','cabecera','items']); 
    $this->crud->removeFields(['user_id','pie','proveedor_data','orden_compra','archivo_id','regimen','cabecera','archivo_id']);

    $this->crud->setColumnDetails('proveedor_id',[
       'label' => "Proveedor",
       'type' => 'select',
       'entity'=> 'Proveedor',
       'name' => 'proveedor_id',
       'attribute' => 'full_name_cedula',
       'model' => "\App\Models\Proveedor" 
     ]);

    $this->crud->addField([
     'label' => "Proveedor",
     'type' => 'select2',
     'attribute' => 'full_name_cedula',
     'name' => 'proveedor_id',
     'model' => "\App\Models\Proveedor" ,
     'value' => Input::input('proveedor_id',1)
     ]);




    $this->crud->addField(["label" => "Fecha de Factura", 
      'type' => 'text',
      "name" => "fecha", 
      "attributes" => [ "class" => "form-control datepicker", 'required' => 'required']
      ]);

    $this->crud->addField(["label" => "Fecha de Vencimiento de la  Factura (Opcional)", 
      'type' => 'text',
      "name" => "vencimiento", 
      "attributes" => [ "class" => "form-control datepicker"]
      ]);

    $this->crud->addField(["label" => "Estado de La Factura", 
      'type' => 'select_from_array',
      'options' => ["emitida" =>  "Emitida","pagada" =>  "Pagada", "vencida" => "Vencida", "rechazada" => "rechazada", "anulada" => "Anulada"],
      "name" => "estado", 
      "default" => "emitida"]);

    $this->crud->addField([ 
      'name' => 'items',
      'label' => 'Items de la factura:',
      'type' => 'table',
      'entity_singular' => 'Item', 
      'columns' => [
      'name' => 'Nombre',
      "cantidad" => "Cantidad",
      "precio" => "Valor Unitario",
      "id" => "Referencia",
      'desc' => 'Descripción',
      ],
      'types' => ["text","number","number","text","textarea"],
      'max' => -1, 
      'min' => 1 
      ]);


    $this->crud->addFilter([
      'name' =>'Estado', 
      'type' =>'select2_multiple', 
      'label' =>'Estado'
      ], 
      [
      "abierto" =>'abierto', 
      "vencida" =>'vencida',
      "emitida" => "emitida",  
      "pagada"  => "pagada",
      "anulada" => "anulada", 
      "rechazada" => "rechazada" 
      ], 
      function($values) {
        foreach (json_decode($values) as $value) {
          $this->crud->addClause('orWhere', 'estado', $value);
        }
      }
      );

    if(Input::has('proveedor_id')){
      $this->crud->addClause('where','proveedor_id',Input::get('proveedor_id'));
    }

        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

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

    $item = $this->crud->create($request->except(['redirect_after_save', '_token']));

    // $item;

    \Alert::success(trans('backpack::crud.insert_success'))->flash();

    switch ($request->input('redirect_after_save')) {
        case 'current_item_edit':
            return \Redirect::to($this->crud->route.'/'.$item->getKey().'/edit');

        default:
            return \Redirect::to($request->input('redirect_after_save'));
      }
    

  }

  public function update(UpdateRequest $request)
  {
    $response = parent::updateCrud();
    // $this->crud->entry;
    return $response;
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
