{{-- Example Backpack CRUD filter --}}

<li filter-name="{{ $filter->name }}"
	filter-type="{{ $filter->type }}"
	class="dropdown {{ Request::get($filter->name)?'active':'' }}">
{{--     <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $filter->label }} <span class="caret"></span></a> --}}
    {{-- <div class="dropdown-menu padding-10"> --}}
    <div class="floating-label-form-group">
        
    <label for="" style="font-size: 0.7em">{{$filter->label}}</label>
       <input type="text" class="datepicker form-control input-sm" 
         id="filter-{{$filter->name}}"  name="filter_{{$filter->name}}" 
         placeholder="{{$filter->label}}"
         value="{{Request::get($filter->name)}}"
         parameter="{{ $filter->name }}"
         >
    </div>
    {{-- </div> --}}
  </li>


{{-- ########################################### --}}
{{-- Extra CSS and JS for this particular filter --}}

{{-- FILTERS EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_list_styles')
        <!-- no css -->
    @endpush --}}


{{-- FILTERS EXTRA JS --}}
{{-- push things in the after_scripts section --}}


{{-- FILTER JAVASCRIPT CHECKLIST

- redirects to a new URL for standard DataTables
- replaces the search URL for ajax DataTables
- users have a way to clear this filter (and only this filter)
- filter:clear event on li[filter-name], which is called by the "Remove all filters" button, clears this filter;

END OF FILTER JAVSCRIPT CHECKLIST --}}

@push('crud_list_scripts')
    <script>
    jQuery(document).ready(function($) {
    	$("[name=filter_{{ $filter->name }}]").change(function() {
    		var value = $(this).val();
    		var parameter = '{{ $filter->name }}';

    		@if (!$crud->ajaxTable())
    			// behaviour for normal table
    			var current_url = normalizeAmpersand('{{ Request::fullUrl() }}');
    			var new_url = addOrUpdateUriParameter(current_url, parameter, value);

    			// refresh the page to the new_url
    			new_url = normalizeAmpersand(new_url.toString());
    	    	window.location.href = new_url;
    	    @else
    	    	// behaviour for ajax table
    			var ajax_table = $("#crudTable").DataTable();
    			var current_url = ajax_table.ajax.url();
    			var new_url = addOrUpdateUriParameter(current_url, parameter, value);

    			// replace the datatables ajax url with new_url and reload it
    			new_url = normalizeAmpersand(new_url.toString());
    			ajax_table.ajax.url(new_url).load();

    			// mark this filter as active in the navbar-filters
    			if (URI(new_url).hasQuery('{{ $filter->name }}', true)) {
    				$("li[filter-name={{ $filter->name }}]").removeClass('active').addClass('active');
    			}
    			else
    			{
    				$("li[filter-name={{ $filter->name }}]").trigger("filter:clear");
    			}
    	    @endif
    	});

			// clear filter event (used here and by the Remove all filters button)
			$("li[filter-name={{ $filter->name }}]").on('filter:clear', function(e) {
				// console.log('dropdown filter cleared');
				$("li[filter-name={{ $filter->name }}]").removeClass('active');
			});
		});
	</script>

@endpush