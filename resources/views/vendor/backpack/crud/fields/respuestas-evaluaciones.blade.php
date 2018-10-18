<?php
   $preguntas = isset($entry) && isset($entry->evaluacion)? json_decode($entry->evaluacion->opciones) : null;
?>

<!-- field_type_name -->

<div class="form-group col-md-12" >
    <h4><label>{!! $field['label'] !!}</label></h4>
     <h5><span class="label label-warning">Si el tipo de evaluacón ha cambiado primero guarde los cambios antes  de rellenar este formulario</span></h4>
    <hr>
    @forelse ($preguntas as $pregunta)
      <label>{{ $pregunta->name}} 
          <i class="fa fa-question-circle fa-lg text-info" title="{{ isset($pregunta->desc) ? $pregunta->desc : '' }}"></i>
      </label>
      <input
        type="{{isset($pregunta->type)? $pregunta->type  : 'text'}}"
        name="respuestas[{{ $pregunta->name }}]"
        value="{{ isset($entry->respuestas) && isset($entry->respuestas[$pregunta->name]) ?  $entry->respuestas[$pregunta->name] : '' }}"
        class="form-control"
      >
    @empty
       <p> No Hay Campos para Agregar</p>
    @endforelse


    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>




@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))
  {{-- FIELD EXTRA CSS  --}}
  {{-- push things in the after_styles section --}}

      @push('crud_fields_styles')
          <!-- no styles -->
      @endpush


  {{-- FIELD EXTRA JS --}}
  {{-- push things in the after_scripts section --}}

      @push('crud_fields_scripts')
          <!-- no scripts -->
      @endpush
@endif