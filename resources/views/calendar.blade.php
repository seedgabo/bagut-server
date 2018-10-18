@php
 $events = Auth::user()->eventsCalendar();
@endphp

{{-- <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.print.css"> --}}
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.css">
<div class="box box-primary">
	<div class="box-header with-border">
	<h5 class="box-title">Calendario de Eventos</h5>
		<div class="box-tools pull-right"> 
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>         
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>		
	</div>
	<div class="box-body">
		<div class="col-md-10">
			<div id="calendar"></div>
		</div>
		<div class="col-md-2">
			<h4 class="text-center">Leyenda:</h4>
			<p><i class="fa fa-square-o abierto"></i> <b>Abierto</b></p>
			<p><i class="fa fa-square-o completado"></i> <b>Completado</b></p>
			<p><i class="fa fa-square-o vencido"></i> <b>Vencido</b></p>
			<p><i class="fa fa-square-o en curso"></i> <b>En Curso</b></p>
			<p><i class="fa fa-square-o" style="background-color: yellow; color: white;"></i> <b>Alertas Programadas</b></p>
		</div>
	</div>
</div>

@section('after_scripts')
<script src="///cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.js"></script>
<script src="///cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/locale-all.js"></script>
<script>
	$(document).ready(function() {
		$('#calendar').fullCalendar({
			locale: 'es',
			customButtons: {
			    addAlerta: {
			        text: 'Agregar Alerta',
			        click: function() {
			            window.location.href= "{{url('notificaciones?add-alerta=true')}}"
			        }
			    },
			    addTicket: {
			        text: 'Agregar @choice('literales.ticket', 1)',
			        click: function() {
			            window.location.href= "{{url('agregar-ticket')}}"
			        }
			    }
			},
			header: {
				left: 'prev,next today',
				center: 'addAlerta,addTicket,title',
				right: 'month,agendaWeek,agendaDay,listMonth'
			},
		    	buttonIcons: true, // show the prev/next text
		    	// navLinks: true, // can click day/week names to navigate views
		    	editable: false,
		    	eventLimit: true, // allow "more" link when too many events
		    	events:	{!! $events !!}
		    })
	});
</script>
@stop