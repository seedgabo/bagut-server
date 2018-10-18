<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\UsuariosRequest as StoreRequest;
use App\Http\Requests\UsuariosRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuariosCrudController extends CrudController {
    public function __construct() {
        parent::__construct();

        $this->verificarPermisos();
        $this->crud->allowAccess('revisions');
        $this->crud->setModel("App\Models\Usuarios");
        $this->crud->setRoute("admin/usuarios");
        $this->crud->setEntityNameStrings('usuario', 'usuarios');

        $this->crud->setFromDb();
        $this->crud->removeFields(['categorias_id',"medico","cliente_id"]);
        $this->crud->removeColumns(['categorias_id','admin','medico', 'cliente_id']);
        
	 $this->crud->addField([
            'name' => 'password',
            'label' => 'Contrasena',
            'type' => 'password'
         ], 'both');

        
        $this->crud->addField([
            'name' => 'categorias',
            'label' => 'Categorias',
            'type' => 'categorias_user_checkbox'
            ], 'both');


        $this->crud->addField([
            'name'=> 'admin',
            'label' => 'Administrador',
            'type' => 'checkbox'
        ], 'both');


        $this->crud->addColumn([
            'name' => 'Administrador',
            'label' => "Administrador", // Table column heading
            'type' => "model_function",
            "function_name" => 'getAdmin'
        ]);

        $this->crud->addColumn([
            'label' => "Categorias", // Table column heading
            'type' => "model_function",
            'function_name' => 'getCategoriasText', // the method in your Model
        ]);


        
        $this->crud->addField([ // image
            'label' => "Foto",
            'name' => 'image',
            'label' => 'Image',
            'type' => 'upload',
            'upload' => true,
        ]);

        $this->crud->addColumn([ // image
           'label' => "Foto", // Table column heading
           'type' => "model_function",
           'function_name' => 'imagen_html', // the method in your Model
        ]);

        $this->crud->addButtonFromModelFunction("line", "boton", "getButtonAuditar", "end");


        if(config("modulos.historias_clinicas"))
        {
            $this->crud->addField([
                'name'=> 'medico',
                'label' => 'Es Médico?',
                'type' => 'checkbox'
            ], 'both');
            $this->crud->addColumn([
                'label' => "Médico", // Table column heading
                'type' => "model_function",
                'function_name' => 'getMedicoText', // the method in your Model
            ]);
        }

        if(config("modulos.clientes"))
        {
            $this->crud->addField([
               'label' => "Asociar usuario a un cliente",
               'type' => 'select2',
               'name' => 'cliente_id', 
               'entity' => 'cliente', 
               'attribute' => 'full_name_cedula',
              'model' => "\App\Models\Cliente" 
            ], 'both');
            
            $this->crud->addColumn([
               'label' => "Cliente asociado",
               'type' => 'select',
               'name' => 'cliente_id', 
               'entity' => 'cliente', 
               'attribute' => 'full_name_cedula',
               'model' => "\App\Models\Cliente",
               'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('nombre', 'like', '%'.$searchTerm.'%');
                    $query->orWhere('email', 'like', '%'.$searchTerm.'%');
	       }
            ], 'both');
        }

        if (config("modulos.gestion_documental")) {
            $this->crud->addField([
                'name' => 'categorias_documentos',
                'label' => 'Categorias Documentos',
                'type' => 'categorias_documentos_user',
                'tab' => 'Documentos'
                ], 'both');
        }


        // $this->crud->addFilter([ // add a "simple" filter called Draft 
        //       'type' => 'simple',
        //       'name' => 'Administradores',
        //       'label'=> 'Administradores'
        //     ],
        //     false, // the simple filter has no values, just the "Draft" label specified above
        //     function() { // if the filter is active (the GET parameter "draft" exits)
        //         $this->crud->addClause("where","admin","1");
        // });

        // if (Auth::user()->hasRole('SuperAdmin')) {

        //     $this->crud->addField(                              
        //     [
        //     // two interconnected entities
        //     'label'             => trans('backpack::permissionmanager.user_role_permission'),
        //     'field_unique_name' => 'user_role_permission',
        //     'type'              => 'checklist_dependency',
        //     'name'              => 'roles_and_permissions', // the methods that defines the relationship in your Model
        //     'subfields'         => [
        //             'primary' => [
        //                 'label'            => trans('backpack::permissionmanager.roles'),
        //                 'name'             => 'roles', // the method that defines the relationship in your Model
        //                 'entity'           => 'roles', // the method that defines the relationship in your Model
        //                 'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
        //                 'attribute'        => 'name', // foreign key attribute that is shown to user
        //                 'model'            => "Backpack\PermissionManager\app\Models\Role", // foreign key model
        //                 'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
        //                 'number_columns'   => 3, //can be 1,2,3,4,6
        //             ],
        //             'secondary' => [
        //                 'label'          => ucfirst(trans('backpack::permissionmanager.permission_singular')),
        //                 'name'           => 'permissions', // the method that defines the relationship in your Model
        //                 'entity'         => 'permissions', // the method that defines the relationship in your Model
        //                 'entity_primary' => 'roles', // the method that defines the relationship in your Model
        //                 'attribute'      => 'name', // foreign key attribute that is shown to user
        //                 'model'          => "Backpack\PermissionManager\app\Models\Permission", // foreign key model
        //                 'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
        //                 'number_columns' => 3, //can be 1,2,3,4,6
        //             ],
        //         ],
        //     ]);    

        // }
    }

    

    public function store(StoreRequest $request)
    {

        $data = $request->except("_method","_token");
        $usuario= new \App\Models\Usuarios;
        $usuario->fill($data);
        $usuario->admin = $request->input('admin', '0');
        $usuario->password = Hash::make(strtolower($request->input('password','casos6325')));

        $usuario->save();
        if (!$request->has('categorias_id')) {
            $usuario->categorias_id = [];
            $usuario->CategoriasTickets()->detach();
        }else{
            $usuario->CategoriasTickets()->sync($request->input('categorias_id'));
        }
        if (!$request->has('categoria_documentos_id')) {
            $usuario->categoriasdocumentos()->sync([]);
            $usuario->categoriasdocumentos()->detach();
        } else {
            $usuario->categoriasdocumentos()->sync($request->input('categoria_documentos_id'));
        }

        \App\Funciones::sendMailUser($usuario);
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        if($request->hasFile('image'))
        {
            array_map('unlink', glob(public_path("img/users/". $usuario->id .".*")));
            $request->file('image')->move(public_path('img/users/'), $usuario->id ."." . $request->file('image')->getClientOriginalExtension());
        }
        return redirect('admin/usuarios');
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->except("_method","_token","deleted_at");
        $usuario= \App\Models\Usuarios::find($id);
        $usuario->fill($data);
        $usuario->admin = $request->input('admin', false);
        $usuario->medico = $request->input('medico', false);
	if($request->has('password'))
	    $usuario->password = Hash::make(strtolower($request->input('password')));
        $usuario->save();
        if (!$request->has('categorias_id')) {
            $usuario->categorias_id = "[]";
            $usuario->CategoriasTickets()->detach();
        }else{
            $usuario->CategoriasTickets()->sync($request->input('categorias_id'));
        }

        if (!$request->has('categoria_documentos_id')) {
            $usuario->categoriasdocumentos()->sync([]);
            $usuario->categoriasdocumentos()->detach();
        } else {
            $usuario->categoriasdocumentos()->sync($request->input('categoria_documentos_id'));
        }


        if($request->hasFile('image'))
        {
            array_map('unlink', glob(public_path("img/users/". $usuario->id .".*")));
            $request->file('image')->move(public_path('img/users/'), $usuario->id ."." . $request->file('image')->getClientOriginalExtension());
        }
        \Alert::success(trans('backpack::crud.update_success'))->flash();
        return redirect('admin/usuarios');
    }

    public function verificarPermisos()
    {
        if(!Auth::user()->can('Agregar Usuarios') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['create']);
        }
        if(!Auth::user()->can('Editar Usuarios') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['update']);
        }
        if(!Auth::user()->can('Eliminar Usuarios') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['delete']);
        }
    }
}
