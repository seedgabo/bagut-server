<!DOCTYPE html> 
<html >

@include('layouts.partials.htmlheader')	
	<body class="container-login animsition">
	<div style="background-color: rgba(0,0,0,0.6); min-height:100vh;">
		
		<center><img src="{{asset('img/logo.png')}}" alt="" height="100px"></center>
		<br><br>
		<center>
			<div class="login-card text-center">
				@yield('content')
			</div>
		</center>
	</div>
	</body>

</html>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js"></script>
@include('layouts.partials.animsition')
<script type="text/javascript">
	  $.backstretch([
      "{{asset('img/fondo (1).jpg')}}",
      "{{asset('img/fondo (2).jpg')}}",
      "{{asset('img/fondo (3).jpg')}}",
      "{{asset('img/fondo (4).jpg')}}",
  ], {duration: 5000, fade: 750});
</script>


