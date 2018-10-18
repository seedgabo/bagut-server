<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\EvaluacionVehiculoRequest as StoreRequest;
use App\Http\Requests\EvaluacionVehiculoRequest as UpdateRequest;
use App\Models\Evaluacion;
use App\Models\EvaluacionVehiculo;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Input;

class EvaluacionVehiculoCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\EvaluacionVehiculo");
        $this->crud->setRoute("admin/evaluaciones-vehiculos");
        $this->crud->setEntityNameStrings('evaluación a vehiculo', 'evaluaciones a vehiculos');



        $this->crud->setFromDb();

        $this->crud->removeField('respuestas','create');

        $this->crud->addField([
             'label' => "Vehiculo",
             'type' => 'select2',
             'name' => 'vehiculo_id',
             'value' => Input::get('vehiculo_id'),
             'entity' => 'Vehiculo',
             'attribute' => 'nombre',
             'model' => "\App\Models\Vehiculo",
             'attributes' =>['required' => 'required'],
             'wrapperAttributes' => [
             'class' => 'form-group col-md-6'
             ]
         ]);

        $this->crud->addField([
             'label' => "Evaluación",
             'type' => 'select_from_array',
             'name' => 'evaluacion_id',
             'value' => Input::get('evaluacion_id'),
             'options' => Evaluacion::whereTipo('2')->pluck('nombre','id'),
             'attributes' =>['required' => 'required'],
             'wrapperAttributes' => [
             'class' => 'form-group col-md-6'
             ]
         ]);

       $this->crud->addField([
             'label' => "Puntuación",
             'type' => 'number',
             'name' => 'puntaje',
             'attributes' =>['required' => 'required', 'step' => '0.01'],
             'wrapperAttributes' => [
             'class' => 'form-group col-md-6'
             ]
         ]);

        $this->crud->addField([
             'label' => "Estado",
             'type' => 'select_from_array',
             'name' => 'estado',
             'options' => EvaluacionVehiculo::estados,
             'allows_null' => true,
             'wrapperAttributes' => [
             'class' => 'form-group col-md-6'
             ]
         ]);

        $this->crud->addField([
             'label' => "Fecha de la Evaluación",
             'type' => 'text',
             'name' => 'fecha_evaluacion',
             'attributes' => ['required' => 'required', 'class'=> 'datetimepicker form-control']
        ]);


        $this->crud->addField([
             'label' => "fecha de la  Proxima Evaluación",
             'type' => 'text',
             'name' => 'fecha_proxima',
			 'attributes' => ['required' => 'required', 'class'=> 'datetimepicker form-control']
        ]);

        $this->crud->addField([
            'label' => "Archivo:",
            'name' => "archivo_id",
            'type' => 'file',
        ]);

        $this->crud->addField([
          'name' => 'respuestas',
          'label' => 'Datos de la evaluación',
          'type' => 'respuestas-evaluaciones'
          ],'update');

        $this->crud->removeColumns(['vehiculo_id','evaluacion_id','accion','nota','puntaje','fecha_evaluacion','fecha_proxima','estado', 'archivo_id' , 'respuestas']);

        $this->crud->addColumn([
             'label' => "Vehiculo",
             'type' => 'select',
             'name' => 'vehiculo_id',
             'entity' => 'Vehiculo',
             'attribute' => 'nombre',
             'model' => "\App\Models\Vehiculo"
         ]);

        $this->crud->addColumn([
             'label' => "Evaluación",
             'type' => 'select',
             'name' => 'evaluacion_id',
             'entity' => 'Evaluacion',
             'attribute' => 'nombre',
             'model' => "\App\Models\Evaluacion"
         ]);

        $this->crud->addColumn(['name' => 'fecha_evaluacion', 'type' => 'date']);
        $this->crud->addColumn(['name' => 'fecha_proxima', 'type' => 'date']);
        $this->crud->addColumn(['name' => 'estado', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'archivo', 'type' => 'model_function', 'function_name'  => 'getArchivoLinkAttribute']);

        $this->crud->addButtonFromModelFunction("line", "verDetallesButton", "verDetallesButton", "end");


    }

    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        if (is_null($request)) {
            $request = \Request::instance();
        }

        $item = $this->crud->create($request->except(['redirect_after_save', 'password']));
        if($request->hasFile('archivo'))
        {
           $archivo = $request->file("archivo");
           $nombre = $archivo->getClientOriginalName();
           $entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
           $archivo->move(storage_path("app/archivos/"), $entrada->id);
           $item->archivo_id = $entrada->id;
           $item->save();
        }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        return redirect('admin/evaluaciones-vehiculos');
    }

    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        if (is_null($request)) {
            $request = \Request::instance();
        }

        $item = \App\Models\EvaluacionVehiculo::find($request->input('id'));
        $item->fill($request->except(['redirect_after_save', 'password']));
        $item->respuestas = $request->input('respuestas');
        if($request->hasFile('archivo'))
        {
           $archivo = $request->file("archivo");
           $nombre = $archivo->getClientOriginalName();
           $entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
           $archivo->move(storage_path("app/archivos/"), $entrada->id);
           $item->archivo_id = $entrada->id;
        }
        $item->save();

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        return redirect('admin/evaluaciones-vehiculos');
    }
}
