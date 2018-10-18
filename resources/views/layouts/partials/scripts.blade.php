


<!-- Bootstrap Core JavaScript -->
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>



<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.datetimepicker.css') }}">

<script src="{{ asset('/js/jquerytoast.min.js') }}" type="text/javascript"></script>


<script src="{{ asset('/js/jquery.datetimepicker.full.min.js') }}"></script>
<script type="text/javascript">
    jQuery.datetimepicker.setLocale('es');
    jQuery('.datepicker').datetimepicker({format:'Y-m-d',mask:true, allowBlank : true, timepicker: false});
    jQuery('.datetimepicker').datetimepicker({format:'Y-m-d H:i:s',mask:true, allowBlank : true});
    jQuery('.datetimepicker:not(.pre)').val("{{Carbon\Carbon::now()->addHours(24)->format('Y-m-d H:i:s')}}");
</script>

<script type="text/javascript">var userId = {{Auth::user()->id or null }};</script>
<script>
	$(document).ready(function(){
			$('.chosen').select2();
			$(".file-bootstrap").fileinput({
		        maxFileSize: 10000,
				showUpload:  false,
		        browseClass: "btn btn-default",
		        browseLabel: "Subir",
		        browseIcon: "<i class=\"glyphicon glyphicon-upload\"></i> ",
		        removeClass: "btn btn-danger",
		        removeLabel: "",
		        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
		        uploadClass: "btn btn-info",
			});

		$(".select2ajax").each(function(i){
				$(this).select2({
			      language: "es",
			      ajax: {
			        url: $(this).data('url'),
			        dataType: 'json',
			        delay: 250,
			        data: function (params) {
			          return {
			            query: params.term,
			          };
			        },
			        processResults: function (data, params) {
			        	// console.log(data);
			          return {
			            results: data,
			          };
			        },
			        cache: true,
			      },
			      escapeMarkup: function (markup) { return markup; }, 
			      minimumInputLength: 2,
			    });		
			});
		});
</script>


<script src="{{asset('js/app.js')}}"></script>

