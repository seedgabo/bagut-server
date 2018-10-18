<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $page->title }}</title>

  <link rel="shortcut icon" type="image/png" href="{{asset('img/favicon.png')}}"/>
  
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="http://getbootstrap.com/examples/blog/blog.css">
  <link href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

</head>
    <div class="blog-masthead">
      <div class="container">
        <nav class="blog-nav">
        @foreach (\Backpack\MenuCRUD\app\Models\MenuItem::getTree() as $menu)
         @if($menu->has('children'))
         <li class="dropdown blog-nav-item">
           <a href="#" class="blog-nav-item dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              {{$menu->name}} <span class="caret"></span>
            </a>
           <ul class="dropdown-menu">
           @foreach ($menu->children as $submenu)
             <li><a href="{{$menu->url()}}">{{$menu->name}}</a></li>
           @endforeach
           </ul>
         </li>
         @else
            <a class="blog-nav-item" href="{{$menu->url()}}" >{{$menu->name}} </a>
         @endif
         @endforeach

          <a class="blog-nav-item btn btn-primary" href="{{ url('home') }}"><i class="fa fa-backward"></i> Volvel al app</a>
        </nav>
      </div>
    </div>

    <div class="container">

      <div class="blog-header">
        <h1 class="blog-title text-center">Newton App</h1>
        <div class="text-justify">
          {!! $page->content !!}
        </div>

      </div><!-- /.row -->

    </div><!-- /.container -->

    <footer class="blog-footer">
      <p>Plantilla diseñada por  <a href="https://twitter.com/mdo"> Newton - App</a>.</p>
      <p>
        <a href="#">Arriba</a>
      </p>
    </footer>

<script   src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>


{{-- 
	VARIABLES:
	
	TITULO:  {!! $page->title  !!};
	CONTENIDO: {!! $page->content !!};
	
	EN GENERAL CUALQUIER VARIABLE

	VARIABLLE  {!!  $page->variable !!}

 --}}