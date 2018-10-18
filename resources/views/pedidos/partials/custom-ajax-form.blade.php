<script>
	$("#cliente_id").change(function(e){
		val = $("#cliente_id").val();
		$.ajax({
			url: "{{url('api/clientes/')}}"+ "/" + val,
			type: 'GET',
			data: {},
		})
		.done(function(data) {
			console.log(data);
			$("input[name='direccion_envio']").val(data.direccion);
			$("input[name='direccion_facturado']").val(data.direccion);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});
</script>