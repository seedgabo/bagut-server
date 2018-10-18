<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\HistoriaClinicaRequest as StoreRequest;
use App\Http\Requests\HistoriaClinicaRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class HistoriaClinicaCrudController extends CrudController {

	public function __construct() {
    parent::__construct();

    $this->crud->setModel("App\Models\HistoriaClinica");
    $this->crud->setRoute("admin/historias_clinicas");
    $this->crud->setEntityNameStrings('historia clinica', 'historias Clínicas');
    $this->verificarPermisos();
    $this->crud->allowAccess('revisions');
    $this->default_antecedentes = 'Niega';
    if(Input::has('paciente_id'))
    {
      $paciente = \App\Models\Paciente::find(Input::get('paciente_id'));
      $antiguos  = \App\Models\HistoriaClinica::where('paciente_id',"=", $paciente->id)->orderBy('updated_at', 'desc')->first();
      $this->crud->setEntityNameStrings('historia clinica', 'historias Clínicas de '  . $paciente->full_name  );
      $this->crud->addClause("where","paciente_id","=",$paciente->id);

      $this->crud->addField(['name'=>'redirect', 'type'  => 'hidden', 'value' => url('admin/ver-paciente/'. $paciente->id)]);
    }
        // $this->crud->setFromDb();

    $this->crud->addColumn(['name'=>'fecha', 'type' => 'date']); 
    $this->crud->addColumn([
     'label' => "Paciente",
     'type' => 'select',
     'name' => 'paciente_id', 
     'entity' => 'Paciente', 
     'attribute' => 'full_name_cedula',
     'model' => "\App\Models\Paciente" 
     ]);
    $this->crud->addColumn([
     'label' => "Médico",
     'type' => 'select',
     'name' => 'medico_id', 
     'entity' => 'Medico', 
     'attribute' => 'nombre',
     'model' => "\App\User"
     ]);
    $this->crud->addColumn([
     'label' => "Analisis",
     'type' => 'text',
     'name' => 'analisis', 
     ]);

    $this->crud->addField(['name' => 'separator', 'type' => 'custom_html', 'value' => '<h2> Datos</h2>']);

        //Paciente
    $this->crud->addField([
     'label' => "Paciente",
     'type' => 'select2',
     'name' => 'paciente_id',
     'value' => Input::get('paciente_id'),
     'entity' => 'Paciente', 
     'attribute' => 'full_name_cedula',
     'model' => "\App\Models\Paciente",
     'attributes' =>['required' => 'required'],
     'wrapperAttributes' => [
     'class' => 'form-group col-md-6'
     ] 
     ]);

        //Medico
    $this->crud->addField([
     'label' => "Médico",
     'type' => 'select2',
     'name' => 'medico_id', 
     'entity' => 'Medico', 
     'attribute' => 'nombre',
     'model' => "\App\User",
     'attributes' =>['required' => 'required'],
     'wrapperAttributes' => [
     'class' => 'form-group col-md-6'
     ] 
     ],'create');

        //Fecha
    $this->crud->addField([
      'name'=>'fecha', 
      'type' => 'text', 
      'default' => Carbon::today()->format('Y-m-d'),
      'attributes' =>['required' => 'required', "class" => "datepicker form-control"],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-6'
      ] 
      ]); 

        //Hora Ingreso
    $this->crud->addField([
      'label' => 'Hora de Ingreso:',
      'name'=>'ingreso', 
      'type' => 'time', 
      'default' => Carbon::now()->format('h:m'),
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-3'
      ] 
      ]); 

        //Hora Egreso
    $this->crud->addField([
      'label' => 'Hora de Egreso:',
      'name'=>'egreso', 
      'type' => 'time', 
      'default' => Carbon::now()->format('h:m'),
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-3'
      ] 
      ]);



    $this->crud->addField(['name' => 'separator2', 'type' => 'custom_html', 'value' => '<h2> Anamnesis </h2>']);

        //motivo_de_consulta
    $this->crud->addField([
      'label' => 'Motivo de la Consulta',
      'name'=>'motivo_de_consulta', 
      'type' => 'textarea', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-6'
      ] 
      ]);
        // enfermedad_actual
    $this->crud->addField([
      'label' => 'Enfermedad Actual',
      'name'=>'enfermedad_actual', 
      'type' => 'textarea', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-6'
      ] 
      ]);
        // revision_por_sistema
    $this->crud->addField([
      'label' => 'Revisión por Sistema',
      'name'=>'revision_por_sistema', 
      'type' => 'text', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

    $this->crud->addField(['name' => 'separator3', 'type' => 'custom_html', 'value' => '<h2> Antecedentes </h2>']);  

        //patologicos
    $this->crud->addField([
      'label' => 'Patologicos:',
      'name'=>'patologicos', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->patologicos  : $this->default_antecedentes,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //quirurjicos
    $this->crud->addField([
      'label' => 'Quirurjicos:',
      'name'=>'quirurjicos', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->quirurjicos  : $this->default_antecedentes,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //farmacologicos
    $this->crud->addField([
      'label' => 'Farmacologicos:',
      'name'=>'farmacologicos', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->farmacologicos  : $this->default_antecedentes,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //Traumaticos
    $this->crud->addField([
      'label' => 'Traumáticos:',
      'name'=>'traumaticos', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->traumaticos  : $this->default_antecedentes,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //inmunologicos
    $this->crud->addField([
      'label' => 'Inmunologicos:',
      'name'=>'inmunologicos', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->inmunologicos  : $this->default_antecedentes,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //Familiares
    $this->crud->addField([
      'label' => 'Familiares:',
      'name'=>'familiares', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->familiares  : $this->default_antecedentes,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //hospitalarios
    $this->crud->addField([
      'label' => 'Hospitalarios:',
      'name'=>'hospitalarios', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->hospitalarios  : $this->default_antecedentes,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //toxicos_alergicos
    $this->crud->addField([
      'label' => 'Toxicos Alergicos:',
      'name'=>'toxicos_alergicos', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->toxicos_alergicos  : $this->default_antecedentes,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //ginecoEstrebticos
    $this->crud->addField([
      'label' => 'GinecoEstrebticos:',
      'name'=>'ginecoestrebticos', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->ginecoestrebticos  : $this->default_antecedentes,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

    $this->crud->addField(['name' => 'separator4', 'type' => 'custom_html', 'value' => '<h2> Examen Físico </h2>']);         

        //frecuencia_cardiaca
    $this->crud->addField([
      'label' => 'Frecuencia Cardiaca:',
      'name'=>'frecuencia_cardiaca', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->frecuencia_cardiaca  : 0,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-2'
      ] 
      ]);

        // frecuencia_respiratoria
    $this->crud->addField([
      'label' => 'Frecuencia Respiratoria:',
      'name'=>'frecuencia_respiratoria', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->frecuencia_respiratoria  : 0,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-2'
      ] 
      ]);

        // tension_arterial
    $this->crud->addField([
      'label' => 'Tensión Arterial:',
      'name'=>'tension_arterial', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->tension_arterial  : 0,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-2'
      ] 
      ]);

        // temperatura
    $this->crud->addField([
      'label' => 'Temperatura:',
      'name'=>'temperatura', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->temperatura  : 0,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-2'
      ] 
      ]);


    $this->crud->addField([
      'label' => 'Peso:',
      'name'=>'peso', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->peso  : 0,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-2'
      ] 
      ]);
        // Talla
    $this->crud->addField([
      'label' => 'Talla:',
      'name'=>'talla', 
      'type' => 'text', 
      'default' => isset($antiguos) ?   $antiguos->talla  : 0,
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-2'
      ] 
      ]);


        // Aspecto General
    $this->crud->addField([
      'label' => 'Aspecto General:',
      'name'=>'aspecto_general', 
      'type' => 'text', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        // Cabeza Cuello
    $this->crud->addField([
      'label' => 'Cabeza/Cuello:',
      'name'=>'cabeza_cuello', 
      'type' => 'text', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //orl
    $this->crud->addField([
      'label' => 'Orl:',
      'name'=>'orl', 
      'type' => 'text', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //cardio_pulmonar
    $this->crud->addField([
      'label' => 'Cardio/Pulmonar:',
      'name'=>'cardio_pulmonar', 
      'type' => 'text', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //extremidades
    $this->crud->addField([
      'label' => 'Extremidades:',
      'name'=>'extremidades', 
      'type' => 'text', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //Abdomen
    $this->crud->addField([
      'label' => 'Abdomen:',
      'name'=>'abdomen', 
      'type' => 'text', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //piel
    $this->crud->addField([
      'label' => 'Piel:',
      'name'=>'piel', 
      'type' => 'text', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

        //neurologico
    $this->crud->addField([
      'label' => 'neurologico:',
      'name'=>'neurologico', 
      'type' => 'text', 
      'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

    $this->crud->addField(['name' => 'separator5', 'type' => 'custom_html', 'value' => '<h2> Resultados </h2>']);  


       //Analisis
    $this->crud->addField([
      'label' => 'Analisis:',
      'name'=>'analisis', 
      'type' => 'summernote', 
            // 'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);
        //Diagnostico Cie10
    $this->crud->addField([
     'label' => "Diagnostico CIE10:",
     'type' => 'select',
     'name' => 'cie10_id',
     'entity' => 'cie10', 
     'attribute' => 'titulo',
     'model' => "\App\Models\cie10",
     'attributes' =>['class' => 'chosen form-control'],
     'wrapperAttributes' => [
     'class' => 'form-group col-md-12'
     ] 
     ]);


    $this->crud->addField(['name' => 'separator6', 'type' => 'custom_html', 'value' => '<h2> Adicional </h2>']);  

       //Notas
    $this->crud->addField([
      'label' => 'Notas Adicionales:',
      'name'=>'notas', 
      'type' => 'summernote', 
            // 'attributes' =>['required' => 'required'],
      'wrapperAttributes' => [
      'class' => 'form-group col-md-12'
      ] 
      ]);

    $this->crud->addButtonFromModelFunction("line", "VerPaciente", "getButtonVerPaciente", "end");
    $this->crud->addButtonFromModelFunction("line", "VerDetalles", "getButtonVerDetalles", "end");

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
    if(!Auth::user()->can('Agregar Historias Clinicas') &&  !Auth::user()->hasRole('SuperAdmin'))
    {
      $this->crud->denyAccess(['create']);
    }
    if(!Auth::user()->can('Editar Historias Clinicas') &&  !Auth::user()->hasRole('SuperAdmin'))
    {
      $this->crud->denyAccess(['update']);
    }
    if(!Auth::user()->can('Eliminar Historias Clinicas') &&  !Auth::user()->hasRole('SuperAdmin'))
    {
      $this->crud->denyAccess(['delete']);
    }
  }
}
