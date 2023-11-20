<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta</title>
    <style>
        @page {
            margin: 3.5cm 0cm 4cm 0cm;
        }

        #header {
            position: fixed;
            top: -3.5cm;
            left: 0cm;
            width: 100%;
        }

        #footer {
            position: fixed;
            bottom: -4cm;
            left: 0cm;
            background-color: #0000cc;
            width: 100%;
            border-top-right-radius: 20px;
        }

        .imgHeader {
            float: left;
            width: 4.5cm;
            margin-top: 1.9cm;
            margin-left: 1.7cm;
        }

        .imgHeaderRigth {
            float: right;
            width: 3.8cm;
        }

        .infoHeader {
            float: left;
            margin-top: 2.6cm;
            margin-left: 1.7cm;
        }

        .textFooter {
            width: 100%;
            height: 0.5cm;
            color: #fff;
            text-align: center;
        }

        .hijo {
            background-color: blue !important;
            width: 100%;
            height: 1cm;
            margin: 1.5cm;
        }

        .section {
            margin: 0.5cm 2.5cm 0cm 2.5cm;
        }

        p {
            margin: 0.1cm 0cm 0cm 0cm;
            font-size: 14px;
            width: 100%;
            word-wrap: break-word;
        }

        .tarea {
            border-style: dashed;
            border-color: #581dfc;
            border-top: none;
            border-left: none;
            border-right: none;
            margin-bottom: 0.5cm;
            padding-bottom: 4px;
        }

        .lista-horizontal {
            list-style-type: none;
        }

        .lista-horizontal li {
            display: inline;
            margin-right: 40px;
            font-size: 14px;
        }

        .lista-horizontal li.m-0 {
            margin-right: -10px;
        }

        body {
            font-family: sans-serif;
        }
    </style>


</head>

<body>
    <div id="header">
        <img class="imgHeader" src="{{public_path('images/logo-horizontal-completo.png')}}" alt="">
        <div class="infoHeader">
            <p><strong>Acta sesión - {{$acta->fecha}}</strong></p>
        </div>
        <img class="imgHeaderRigth" src="{{public_path('images/icono-azul.png')}}" alt="">
    </div>
    <div id="footer">
        <div class="textFooter">
            <ul class="lista-horizontal">
                <li>info@mercigroup.co</li>
                <li>www.mercigroup.co</li>
                <li>+57 300 846 2669</li>
                <li class="m-0">
                    <a href="https://www.instagram.com/mercisalescol/" target="_blank">
                        <img src="{{public_path('images/icons/instagram-border@2x.png')}}" width="20">
                    </a>
                </li>
                <li class="m-0">
                    <a href="https://www.facebook.com/mercisales/" target="_blank">
                        <img src="{{public_path('images/icons/facebook-border@2x.png')}}" width="20">
                    </a>
                </li>
                <li class="m-0">
                    <a href="https://www.linkedin.com/company/mercisalessas" target="_blank">
                        <img src="{{public_path('images/icons/linkedin-border@2x.png')}}" width="20">
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container">
        <div class="section">
            <p>Hora de inicio: {{$acta->hora_inicio}}</p>
            <p>Hora de finalización: {{$acta->hora_finalizacion}}</p>
            <p>Modalidad encuentro: {{$acta->modalidad_encuentro}}</p>
        </div>
        <div class="section">
            <p><strong>Asistentes:<strong></p>
            {!! $acta->asistentes !!}
        </div>
        <div class="section">
            <p><strong>Temas:<strong></p>
            {!! $acta->temas !!}
        </div>
        <div class="section">
            <p><strong>Tareas:<strong></p>
            @foreach($tareas as $tarea)
            <div class="tarea">
                <p class=""><strong>Descripción:</strong>{!! $tarea->descripcion !!}</p>
                <p><strong>Categoría: </strong>{{$tarea->categoria_name}} - <strong>Actividad: </strong>{{$tarea->actividad_name}}</p>
                <p><strong>Responsable: </strong>{{$tarea->responsable}}</p>
                <p><strong>Fecha inicio: </strong>{{$tarea->fecha_inicio}}</p>
                <p><strong>Fecha final: </strong>{{$tarea->fecha_fin}}</p>
                <p><strong>Fecha finalización: </strong>{{$tarea->fecha_finalizacion}}</p>
                <p><strong>Estado: </strong>{{$tarea->estado_name}}</p>
            </div>
            @endforeach
        </div>

    </div>
</body>

</html>