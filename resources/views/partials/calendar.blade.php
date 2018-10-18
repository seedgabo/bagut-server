@php
$events = Auth::user()->eventsCalendar();
@endphp

{{-- <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.print.css"> --}}
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.css">
<div class="box box-primary hover">
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
			<div class="form-inline" id="calendar-filters">
				<input type="search" class="form-control input-sm" value="" style=" max-width: 100% " placeholder="Buscar" id="search-event">
				<select class="form-control input-sm" id="type-event" style=" max-width: 100% ">
					<option value="all" selected="selected">Todos</option>
					<option value="alert">Eventos</option>
					<option value="ticket">@choice('literales.ticket', 10)</option>
				</select>
				<input type="text" class="form-control datepicker" style=" max-width: 100% " value="{{Carbon\Carbon::today()->format('Y-m-d')}}" id="date-event">
			</div>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/basic/jquery.qtip.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js"></script>

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
			        	@if (Auth::user()->hasRole("Administrar Alertas") || Auth::user()->hasRole("SuperAdmin"))
			        		window.location.href= "{{url('/admin/alertas/create')}}"
			        	@else
			            	window.location.href= "{{url('notificaciones?add-alerta=true')}}"
			        	@endif
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
		    	navLinks: true, // can click day/week names to navigate views
		    	editable: false,
		    	// eventLimit: true, // allow "more" link when too many events
		    	eventRender: function eventRender( event, element, view ) {
		    		element.qtip( {   
		    			content: {
					        text: event.description,
					        title: event.title
					    }});
		    	    return event.title.indexOf($("#search-event").val()) != -1 &&
		    	    	['all', event.type].indexOf($("#type-event").val()) != -1 
		    	    	// ['all', event.className].indexOf($("#estado-event").val()) != -1
		    	    	// || event.type ==  "alert"
						;
		    	},
		    	events:	{!! $events !!}
		    });
		$(".chosen").select2({});
	});
		$('#search-event,#type-event').on("change paste keyup", function(){
		    $('#calendar').fullCalendar('rerenderEvents');
		})
		$('#date-event').on("change",function(){
			$('#calendar').fullCalendar('gotoDate',$('#date-event').val());
		});
</script>
@stop