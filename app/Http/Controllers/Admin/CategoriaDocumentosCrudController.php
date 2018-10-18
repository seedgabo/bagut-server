<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CategoriaDocumentosRequest as StoreRequest;
use App\Http\Requests\CategoriaDocumentosRequest as UpdateRequest;

class CategoriaDocumentosCrudController extends CrudController {

    public function __construct() {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel("App\Models\CategoriaDocumentos");
        $this->crud->setRoute("admin/categoriadocumentos");
        $this->crud->setEntityNameStrings('Categoria', 'Categorias de Documentos');
        $this->crud->enableReorder("nombre",255);
        $this->crud->allowAccess("reorder");
        $this->crud->allowAccess('revisions');
        $this->crud->addColumn(
            [
            // 1-n relationship
            'label' => "Categoría Padre", // Table column heading
            'type' => "select",
            'name' => 'padre', // the method that defines the relationship in your Model
            'entity' => 'parent', // the method that defines the relationship in your Model
            'attribute' => "full_name", // foreign key attribute that is shown to user
            'model' => "App\Models\CategoriaDocumentos", // foreign key model
        ]); // add a single column, at the end of the stack


        $this->crud->setFromDb();
                // ------ CRUD FIELDS
        $this->crud->addField([
            'name' => 'padre',
            'label' => 'Categoria',
            'type' => 'categoria_documentos_radio'
        ], 'both');
        $this->crud->removeField('parent_id','both');
        $this->crud->removeColumn('parent_id');

        
        $this->crud->orderBy('parent_id','asc');
        // $this->crud->groupBy();
        // $this->crud->limit();
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
