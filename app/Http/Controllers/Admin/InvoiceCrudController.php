<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InvoiceRequest as StoreRequest;
use App\Http\Requests\InvoiceRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;

class InvoiceCrudController extends CrudController {

	public function setUp() {

    $this->crud->setModel("App\Models\Invoice");
    $this->crud->setRoute("admin/facturas");
    $this->crud->setEntityNameStrings('factura', 'facturas');
    $this->verificarPermisos();
    $this->crud->allowAccess('revisions');
    $this->crud->denyAccess("delete");
    // $this->crud->enableAjaxTable(); 
    // ------ CRUD COLUMNS        
    $this->crud->addColumn([
     'name' => 'id', 
     'label' => "#:",
     'type' => 'text'
     ]);

    $this->crud->addColumn([
     'name' => 'visto', 
     'label' => "Visto por el Cliente:",
     'type' => 'check'
     ]);

    $this->crud->addColumn([
     'name' => 'items', 
     'label' => "Items de la factura",
     'type' => 'multidimensional_array',
     'visible_key' => 'name' 
     ]);

    $this->crud->addColumn([
     'name' => 'sub_total', 
     'label' => "Subtotal:",
     'type' => "model_function",
     'type' => 'currency'
     ]);

    $this->crud->addColumn([
     'name' => 'total', 
     'label' => "Total:",
     'type' => 'currency'
     ]);

    $this->crud->setFromDb();
    $this->crud->removeColumns(["visto","cliente_id","user_id" ,'cliente_data', 'archivo_id',"resolucion","regimen","cabecera","pie","items","nota","direccion"]); 

    $this->crud->addColumn([
     'label' => "Cliente",
     'type' => 'select',
     'name' => 'cliente_id',
     "entity" => "cliente",
     'attribute' => 'full_name',
     'model' => "\App\Models\cliente" 
     ]);

    $this->crud->addColumn([
     'label' => "Usuario",
     'type' => 'select',
     'name' => 'user_id',
     "entity" => "User",
     'attribute' => 'nombre',
     'model' => "\App\User" 
     ]);


		// ------ CRUD FIELDS
    $this->crud->addField([
     'label' => "Cliente",
     'type' => 'select2',
     'name' => 'cliente_id',
     'attribute' => 'full_name',
     'model' => "\App\Models\cliente" 
     ]);

    $this->crud->addField([
     'label' => "Usuario",
     'type' => 'select2',
     'name' => 'user_id',
     'attribute' => 'nombre',
     'model' => "\App\User" 
     ]);

    $this->crud->addField([
      "label" => "Resolución", 
      "type" => "hidden",
      "name" => "resolucion", 
      "default" => config("settings.resolucion_dian","0000000"),
      "attributes" => ["readOnly" => "readOnly"]
      ]);

        // $this->crud->addField(["label" => "Regimen", 
        //     'type' => 'select_from_array',
        //     'options' => ["Regimen Común" => "Regimen Común" ,"Regimen Simplificado" => "Regimen Común"],
        //     "default" => config("settings.regimen","Regimen Común"),
        //     "name" => "regimen", 
        //     "attributes" => ["readOnly" => "readOnly"]
        //     ]);

    $this->crud->addField(["label" => "Cabecera de la Factura", 
      'type' => 'hidden',
      "name" => "cabecera", 
      "default" => config("settings.cabecera_factura",""),
      "attributes" => ["readOnly" => "readOnly", "rows" => "7"]
      ]);

    $this->crud->addField(["label" => "Pie de Pagina  de la Factura", 
      'type' => 'hidden',
      "name" => "pie", 
      "default" => config("settings.pie_factura",""),
      "attributes" => ["readOnly" => "readOnly", "rows" => "7"]
      ]);

    $this->crud->addField(["label" => "Fecha de Factura", 
      'type' => 'text',
      "name" => "fecha", 
      "attributes" => [ "class" => "form-control datepicker"]
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
        "iva" => "IVA",
        "descuento" => "Descuento",
        "referencia" => "Referencia",
        'desc' => 'Descripción',
      ],
      'types' => ["text","number","number","number","number","text","textarea"],
      'max' => -1, 
      'min' => 1 
      ]);

    $this->crud->removeFields(["cliente_data" ,"archivo_id", "visto","regimen"]);


    $this->crud->addButtonFromModelFunction("line", "ver", "getButtonVer", "end");
    $this->crud->enableExportButtons();

    $this->crud->addFilter([
      'name' => 'from',
      'type' => 'datepicker',
      'label'=> 'Desde: '
      ], 
      []
      ,
      function($value) {
        $this->crud->addClause("where","fecha",">=",$value);
      });
    $this->crud->addFilter([
      'name' => 'to',
      'type' => 'datepicker',
      'label'=> 'Hasta: '
      ], 
      []
      ,
      function($value) {
        $this->crud->addClause("where","fecha","<=",$value);
      });

    $this->crud->addFilter([
      'name' => 'Clientes',
      'type' => 'dropdown',
      'label'=> 'Clientes '
      ], 
      \App\Models\Cliente::all()->pluck("full_name_cedula","id")->toArray()
      ,
      function($value) {
        $this->crud->addClause("where","cliente_id",$value);
      });

    $this->crud->addFilter([
      'name' => 'Estado',
      'type' => 'select2_multiple',
      'label'=> 'Estado '
      ], 
      ["emitida" =>  "Emitida","pagada" =>  "Pagada", "vencida" => "Vencida", "rechazada" => "rechazada", "anulada" => "Anulada"]
      ,
      function($values) {
        foreach (json_decode($values) as $value) {
          $this->crud->addClause("orWhere","estado",$value);
        }
      });
  }

  public function store(StoreRequest $request)
  {
    return parent::storeCrud();
  }

  public function update(UpdateRequest $request)
  {
    return parent::updateCrud();
  }

  public function verificarPermisos() {
    if ( !Auth::user()->can('Agregar Facturas') && !Auth::user()->hasRole('SuperAdmin')) {
      $this->crud->denyAccess(['create']);
    }
    if ( !Auth::user()->can('Editar Facturas') && !Auth::user()->hasRole('SuperAdmin')) {
      $this->crud->denyAccess(['update']);
    }
    if ( !Auth::user()->can('Eliminar Facturas') && !Auth::user()->hasRole('SuperAdmin')) {
      $this->crud->denyAccess(['delete']);
    }
  }
}
