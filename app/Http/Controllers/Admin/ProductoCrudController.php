<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductoRequest as StoreRequest;
use App\Http\Requests\ProductoRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;

class ProductoCrudController extends CrudController {

	public function setUp() {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Producto");
        $this->crud->setRoute("admin/productos");
        $this->crud->setEntityNameStrings(trans_choice('literales.producto',1), trans_choice('literales.producto',10));
        $this->verificarPermisos();
        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();
        $this->crud->removeColumns(['precio2', 'precio3','precio4','precio5','user_id','es_vendible_sin_stock','mostrar_stock','disccount','parent_id','description','notas','image_id']); 
        $this->crud->removeFields(['precio2','precio3','precio4','precio5','user_id','parent_id','disccount','image_id']);

        $this->crud->addButtonFromModelFunction('line', 'verDetallesButton', 'verDetallesButton', 'beginning'); 
        $this->crud->addButtonFromModelFunction('line', 'administrarImagenes', 'administrarImagenes', 'beginning'); 

        $this->crud->addFields([
            ['name' => 'name' , 'label'=>'Nombre'], 
            ['name' => 'description' , 'label'=>'Descripcion','type'=>'ckeditor'], 
            ['name' => 'stock' , 'label'=>'Stock','type'=>'number','attributes'=> ['min'=>'0']], 
            ['name' => 'active' , 'label'=>'Activo','type'=>'checkbox'], 
            ['name' => 'data' , 'label'=>'Datos adicionales','type'=>'table','entity_singular' => 'Dato', 'columns'=> ['nombre'=>'Nombre','valor'=>'Valor']], 
            ['name' => 'es_vendible_sin_stock' , 'label'=>'Es Vendible Sin Stock' , 'type' => 'checkbox'], 
            ['name' => 'mostrar_stock' , 'label'=>'Mostrar el stock en el catalogo' , 'type' => 'checkbox'],
            ['name' => 'destacado' , 'label'=>'Destacar en el catalogo' , 'type' => 'checkbox'],
            ['name' => 'precio' , 'type'=>'number', 'attributes'=> ['min'=>'0']],
            ['name' => 'impuesto' , 'type'=>'number', 'label' =>'IVA(%)', 'attributes'=> ['min'=>'0'], 'default'=>'19'],
            // ['name' => 'addcional-data','type'=> 'view', "productos/partials/form"],
            ]);      


        $this->crud->addField([
            'name'=> 'categoria_id',
            'label' => trans_choice('literales.categoria',1) . ' Principal',
            'type' => 'select2',
            'attribute' => 'nombre',
            'model' => "\App\Models\CategoriaProducto",
        ]);

        $this->crud->setColumnDetails('categoria_id',[
            'label' => trans_choice('literales.categoria',1) . ' principal',
            'type' => 'select',
            'attribute' => 'nombre',
            'entity' => 'categoria',
            'model' => "\App\Models\CategoriaProducto",
        ]);
        $this->crud->setColumnDetails('name', ['label' => 'nombre']); 
        $this->crud->setColumnDetails('precio', ['type' => 'currency']); 
        $this->crud->setColumnDetails('data', ['label' => 'Adicional','type' => 'multidimensional_array','visible_key' => 'valor']); 
        $this->crud->setColumnDetails('description', ['label' => 'Descripcion']); 
        $this->crud->setColumnDetails('impuesto', ['label' => 'IVA(%)']); 
        $this->crud->setColumnDetails('active', ['label' => 'Activo','type' =>'boolean', 'options' => ['0' => 'Inactivo','1' => 'Activo']]);
        $this->crud->addColumn(['name'=>'image_id','label' => 'Imagen','type' =>'image', 'attribute' => 'url']);


        $this->crud->addField([      
            'label' => trans_choice('literales.categoria',10) . ' que pertenece',
            'type' => 'select2_multiple',
            'name' => 'categorias',
            'entity' => 'Categorias',
            'attribute' => 'nombre',
            'model' => "App\Models\CategoriaProducto",
            'pivot' => true,
        ]);

        $this->crud->addColumn([       
            'label' => trans_choice('literales.categoria',10) . ' que pertenece',
            'type' => 'select_multiple',
            'name' => 'categorias',
            'entity' => 'Categorias', 
            'attribute' => 'nombre', 
            'model' => "App\Models\CategoriaProducto", 
            'pivot' => true,
        ]);
        
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);
        
        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
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
        
        $this->crud->addFilter([
          'type' => 'simple',
          'name' => 'outOfStock',
          'label'=> 'Sin Existencias'
        ],
        false, 
        function() { 
            $this->crud->addClause('where','stock','<=','0'); 
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

    public function verificarPermisos()
    {
          if(!Auth::user()->can('Agregar Productos') &&  !Auth::user()->hasRole('SuperAdmin'))
          {
            $this->crud->denyAccess(['create']);
          }
          if(!Auth::user()->can('Editar Productos') &&  !Auth::user()->hasRole('SuperAdmin'))
          {
            $this->crud->denyAccess(['update']);
          }
          if(!Auth::user()->can('Eliminar Productos') &&  !Auth::user()->hasRole('SuperAdmin'))
          {
            $this->crud->denyAccess(['delete']);
          }
    }
}
