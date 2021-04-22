<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>
</head>
<style>
body {
    font-family: Arial, Helvetica, sans-serif;
}

table {
    font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
    font-size: 12px;
    margin-top: 45px;
    width: 100%;
    text-align: left;
    border-collapse: collapse;
}

th {
    font-size: 13px;
    font-weight: normal;
    padding: 8px;
    background: #ffffff;
    border-top: 1px solid #000000;
    border-bottom: 1px solid #000000;
    color: #000000;
}

td {
    padding: 8px;
    background: #ffffff;
    border-bottom: 1px solid #000000;
    color: #000000;
    border-top: 1px solid transparent;
}

tr:hover td {
    background: #000000;
    color: #ffffff;
}
h1,h2,h3,h4,h5,h6{
    padding:0;
    margin:0
}
.container{
    height: 150px;
}
.right {
  float: right;
}
.left {
  float: left;
}
</style>
<body>
    <h2 align="center">CheckList por Visita</h2>
    <h4 align="right" style="padding-bottom:15px">Fecha y Hora PDF: {{$fecha}}</h4>

    <div class="container">
        <div class="left">
            <h4>Datos Tienda</h4>
            <h5>Tienda:{{$visita->detalle_ruta->tienda->nombres}}</h5>
            <h5>Direccion:{{$visita->detalle_ruta->tienda->direccion}}</h5>
            <h5>Latitud:{{$visita->detalle_ruta->tienda->latitud}}</h5>
            <h5>Longitud:{{$visita->detalle_ruta->tienda->longitud}}</h5>
        </div>
        <div class="right">
            <h4>Datos Visita</h4>
            <h5>Supervisor:{{$visita->supervisor->nombres}}</h5>
            <h5>Latitud:{{$visita->latitud}}</h5>
            <h5>Longitud:{{$visita->longitud}}</h5>
        </div>
    </div>

  <div>
  <table style="margin: 0 auto;">
      <thead>
        <tr>
          <th>Numero</th>
          <th>Titulo</th>
          <th>Opcion</th>
          <th>Observacion</th>
        </tr>
      </thead>
      <tbody>
      <tr><td colspan="4" align="center"><h3>Responsabilidad del Cajero<h3></td></tr>
        {{$indice=1}}

            @foreach ($visita->respuestas as $respuesta)
                @if($respuesta->pregunta->tipo==1)
                <tr>
                    <td>{{$indice}}</td>
                    <td>{{$respuesta->pregunta->titulo}}</td>
                    <td>{{$respuesta->opcion->opcion}}</td>
                    <td>{{$respuesta->observacion}}</td>
                    {{$indice++}}
                </tr>
                @endif
            @endforeach
            <tr><td colspan="4" align="center"><h3>Responsabilidad del Supervisor<h3></td></tr>
        {{$indice=1}}
            @foreach ($visita->respuestas as $respuesta)

             @if($respuesta->pregunta->tipo==2)
                <tr>
                    <td>{{$indice}}</td>
                    <td>{{$respuesta->pregunta->titulo}}</td>
                    <td>{{$respuesta->opcion->opcion}}</td>
                    <td>{{$respuesta->observacion}}</td>
                    {{$indice++}}
                </tr>
                @endif
            @endforeach
      </tbody>
    </table>
  </div>
</body>
</html>
