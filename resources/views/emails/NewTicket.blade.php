<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <h3>Hola, {{$guardian->nombre}}</h3>
             A Usted se le ha asignado un nuevo ticket por: {{$user->nombre}}.
        </div
        <br><br><br>
        <div style="background-color: #A4EDFF; border: 1px dashed black; border-radius: 50px; padding: 30px;" >
            <h1>{{$ticket->titulo}}</h1>
            <h3>Categoria: {{$ticket->categoria->nombre}}</h3>
            <p> {!! str_limit($ticket->contenido, 200)  !!}</p>
            Con fecha limite el 
            <b><span style="color:red"> {{\App\Funciones::transdate($ticket->vencimiento)}} </span></b>  
        </div>
    </table>

      <table class="button">
      <tr>
        <td>
            <a href="{{url('/ticket/ver/'.$ticket->id)}}"> Ver Caso</a>
        </td>
      </tr>
    </table>
    <small> Recuerde responder este ticket lo mas pronto posible en el sistema</small>


    
    <a href="{{url('/')}}"  style="width:300px;">
        {{Html::image(asset('img/logo.png'))}}
    </a>
</body>
</html>