@push('initialScripts')
	{!! Charts::assets(['google', 'chartjs', 'highcharts']) !!}
@endpush
<div class="box box-default">
	<div class="box-header with-border text-center">
		<div class="box-title">{{$chart->title}}</div>
		<div class="box-tools pull-right">
		  <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
	<div class="box-body">
      	{!! $chart->render() !!}
	</div>
</div>