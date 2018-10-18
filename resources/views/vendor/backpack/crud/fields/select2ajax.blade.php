@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/es.js"></script>
@endif

<div @include('crud::inc.field_wrapper_attributes') >
    <label class="text-capitalize"> {!! $field['label'] !!}</label>

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <select id="{{ $field['name'] }}"
            name="{{ $field['name'] }}@if(isset($field['multiple']) && $field['multiple'])[]@endif"
            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
            @include('crud::inc.field_attributes')
            class="select2-ajax-{{ $field['name'] }} primary form-control"
            @if(isset($field['multiple']) && $field['multiple']) multiple @endif >

          @if(isset($field['value']) && is_array($field['value']))
            @forelse ($field['value'] as $val)
              <option value="{{$val->id}}" selected="selected">{{$val->{$field['text']} }}</option>
            @empty
          @endforelse
          @elseif(isset($field['value']) && !is_a($field['value'], 'Illuminate\Database\Eloquent\Collection')  && !is_array($field['value']))
            @if ( \App\Models\Cliente::find($field['value']) !== null )
             <option value="{{ $field['value'] }}" selected="selected"> {{\App\Models\Cliente::find($field['value'])->{$field['text']} }}</option>
            @endif
            
          @endif

        </select>
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
<script>
  $('select#{{ $field['name'] }}').select2({
      language: "es",
      ajax: {
        url: "{{ $field['url'] }}",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            query: params.term,
          };
        },
        processResults: function (data, params) {
          return {
            results: data,
          };
        },
        cache: true
      },
      escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
      minimumInputLength: 2,
      templateResult: formatRepo{{$field['name']}}, // omitted for brevity, see the source of this page
      templateSelection: formatRepoSelection{{$field['name']}} // omitted for brevity, see the source of this page
    });

  function formatRepo{{$field['name']}} (repo) {
        if (repo.loading) return repo.text;
        var markup = "<div class='container'><div class'col-sm-10 col-sm-offset-1'>";
            @if (isset($field['image']))
            markup +=  "<img class='img-responsive img-circle' style='width:30px; display: inline-block;' src='" + repo['{{$field['image']}}'] + "' />  ";
            @endif

           markup += " " + repo["{{$field['text']}}"] + " ";

          @if ($field['description'])
            markup += "<span class='text-muted'>" + repo['{{$field['description']}}'] + "</span>";
          @endif
        markup += "</div></div>";

        return markup;
  }

  function formatRepoSelection{{$field['name']}}(repo){
        return repo["{{$field['text']}}"] || repo.text;
        var markup = "";
        @if (isset($field['image']))
            markup += "<img class='img-responsive img-circle' style='width:30px; display: inline-block;' src='" + repo.  foto_url + "' /> ";
        @endif
        markup += repo['{{$field['text']}}'];
  }
</script>

