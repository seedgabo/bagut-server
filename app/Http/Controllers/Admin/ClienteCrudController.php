<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClienteRequest as StoreRequest;
use App\Http\Requests\ClienteRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ClienteCrudController extends CrudController {

  public function setUp(){
    
        $this->crud->setModel("App\Models\Cliente");
        $this->crud->setRoute("admin/clientes");
        $this->crud->setEntityNameStrings(trans_choice('literales.cliente',1), trans_choice('literales.cliente',10));
        $this->verificarPermisos();
        $this->crud->allowAccess('revisions');
        $this->crud->enableExportButtons();
        $this->crud->addColumn([
            'name' => 'id',
            'label' => '#ID',
            'type' => 'text'
        ]);
        $this->crud->addField(['name' => 'nombres','label'=>'Nombres','type'=>'text','attributes' => ['required' => 'required']]);

        $this->crud->addField(['name' => 'nit','label'=>'NIT/cédula','type'=>'text','attributes' => ['required' => 'required']]);        

        $this->crud->addField(['name' => 'ingreso','label'=>'Fecha de Ingreso A la Empresa', 'type' => 'date_picker']);

        $this->crud->addField(['name' => 'egreso','label'=>'Fecha de Egreso A la Empresa', 'type' => 'date_picker']);

        $this->crud->addField(['name' => 'email','label'=>'Email','type'=>'email']);

        $this->crud->addField(['name' => 'telefono','label'=>'Telefono','type'=>'text']);

        $this->crud->addField(['name' => 'direccion','label'=>'Dirección','type'=>'address']);

        $this->crud->addField(['name' => 'nota','label'=>'Nota','type'=>'summernote']);

        $this->crud->addField([
           'label' => "Usuario Asociado",
           'type' => 'select2',
           'name' => 'user_id',
           'attribute' => 'nombre',
           'model' => "\App\User" 
          ]);

        $this->crud->addField([
            'name' => 'foto',
            'label' => 'Foto',
            'type' => 'foto_file'
            ]);


        $this->crud->addColumns(["nombres",["name"=>"nit","label"=>"NIT/cédula"],"telefono","email"]);
        $this->crud->addColumn([
           'label' => "Usuario Asociado",
           'type' => 'select',
           'name' => 'user_id', 
           'entity' => 'user', 
           'attribute' => 'nombre',
           'model' => "\App\User" 
        ]);



        if (config('modulos.procesos_masivos')) {
          $this->crud->addField([
            'name' => 'custom-procesoMasivo',
            'type' => 'view',
             'view' => 'procesosMasivos/partials/form-cliente'
            ],'create');

          $this->crud->addColumn([
               'label' => "# Procesos Masivos",
               'type' => 'model_function',
               'name' => 'procesosMasivos_count',
               'function_name' => 'getProcesosMasivosCountAttribute',
            ]);
        }

        $this->crud->addButtonFromModelFunction("line", "verDetalles", "getButtonVerDetalles", "end");

        if (config("modulos.procesos")) {
            $this->crud->addButtonFromModelFunction("line", "verProcesos", "getButtonVerProcesos", "end");
            $this->crud->addColumn([
                 'label' => "# Procesos Ordinarios",
                 'type' => 'model_function',
                 'name' => 'procesos_count',
                 'function_name' => 'getProcesosCountAttribute',
              ]);
        }

        if (config('modulos.consultas')) { 
          $this->crud->addButtonFromModelFunction("line","verHistorias", "getButtonVerConsultas", "end");
        } 

        if (Input::has('cliente_id')) 
        {
           $cliente = \App\Models\Cliente::find(Input::get('cliente_id'));
           
           $this->crud->setEntityNameStrings('Cliente', 'Cliente: ' . $cliente->full_name);

           $this->crud->denyAccess(['create','delete']);
           
           $this->crud->addClause('where', 'id', '=', $cliente->id);
        }
        
        $this->crud->enableAjaxTable();

  }

  public function store(StoreRequest $request)
  {
      $data = $request->except("_method","_token",'proceso','redirect_after_save');
      $cliente = \App\Models\Cliente::onlyTrashed()->where('nit',$data['nit'])->first();
      if($cliente !== null){
          $cliente->restore();
          \App\Models\ProcesosMasivosCliente::where('cliente_id',$cliente->id)->delete();
          \Alert::success('El cliente fue recuperado')->flash();
          if ($request->has('proceso.proceso_masivo_id')) {
            $proceso =$request->input('proceso');
            $proceso['cliente_id'] = $cliente->id;
            $proceso['user_id'] = Auth::user()->id;
            \App\Models\ProcesosMasivosCliente::create($proceso);
          }
          return \Redirect::to($this->crud->route.'/'.$cliente->id.'/edit');
      }

      $cliente = \App\Models\Cliente::create($data);
      Static::saveFoto($request , $cliente);


      if ($request->has('proceso.proceso_masivo_id')) {
        $proceso =$request->input('proceso');
        $proceso['cliente_id'] = $cliente->id;
        $proceso['user_id'] = Auth::user()->id;
        \App\Models\ProcesosMasivosCliente::create($proceso);
      }
      
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
    $cliente = \App\Models\Cliente::find($request->id);
    $data = $request->except("_method","_token");

    $cliente->fill($data);
    $cliente->save();
    Static::saveFoto($request , $cliente);
    \Alert::success(trans('backpack::crud.insert_success'))->flash();
    return redirect('admin/clientes');
  }

  public  static function saveFoto($request , $cliente)
  {
        if($request->hasFile('foto'))
        {
          $archivo = $request->file('foto');
          $nombre  =  $cliente->id . "." . $archivo->getClientOriginalExtension();
          $archivo->move(public_path("/img/clientes/"), $nombre);
          $cliente->foto = $nombre;
          $cliente->save();
        }
  }

  public function verificarPermisos()
  {
        if(!Auth::user()->can('Agregar Clientes') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['create']);
        }
        if(!Auth::user()->can('Editar Clientes') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['update']);
        }
        if(!Auth::user()->can('Eliminar Clientes') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['delete']);
        }
  }
  
}
