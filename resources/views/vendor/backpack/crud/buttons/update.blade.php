@if ($crud->hasAccess('update'))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-outline btn-primary"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</a>
@endif