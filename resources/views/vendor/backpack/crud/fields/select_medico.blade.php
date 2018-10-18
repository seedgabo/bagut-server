<!-- field_type_name -->
<?php $medicos = \App\User::where("medico","=",1)->get() ?>
<div class="form-group col-md-12">
    <label>{!! $field['label'] !!}</label>
    <select
        name="{{ $field['name'] }}"
        value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
        class="select2 form-control"
    >
     {{-- <option value="">-</option> --}}
     @foreach ($medicos as $medico)
      @if((isset($field['value'])  && $field['value'] ==  $medico->id))
       <option value="{{$medico->id}}" selected >{{$medico->nombre}}</option>
      @else
       <option value="{{$medico->id}}">{{$medico->nombre}}</option>
      @endif
     @endforeach

    </select>

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