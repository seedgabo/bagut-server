<html>
<head>
    <link rel="stylesheet" href= "{{asset('css/bootstrap-email.css')}}" />
    <style type="text/css" media="screen">
        body
        {
            font-family: 'Helvetica Neue';
            font-size: 14px;
            font-weight: 12px;
        }
    </style>
</head>

<body>
    <h3 class="container">Hola, {{$user->nombre}}</h3>
    <div class="well">
        Su usuario ha sido creado con exito

        Usuario:  {{$user->email}} <br>
        Contraseña: Casos6325 <br>
    </div>
    <strong style="color:red"> Se Recomienda cambia la clave lo antes posible</strong>


    <a href="{{url('/')}}" style="width:300px;">
        {{Html::image(asset('img/logo.png'))}}
    </a>