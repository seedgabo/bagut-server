@hasanyrole(['Traductor','SuperAdmin'])

<li class="treeview">
	<a href="#"><i class="fa fa-globe"></i> <span>{{trans_choice('literales.translate',10)}}</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
	<li><a href="{{ url('admin/language') }}"><i class="fa fa-flag-checkered"></i> {{trans_choice('literales.language',10)}}</a></li>
		<li><a href="{{ url('admin/language/texts') }}"><i class="fa fa-language"></i> {{trans_choice('literales.text',10)}}</a></li>
	</ul>
</li>

@endhasanyrole