<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
img{
    width:100%
}
</style>
<body>

    <h2 align="center">Reporte Efectividad</h2>
    <h5 align="right" >Supervisor: {{$supervisor->nombres}}</h5>
    <h5 align="right" >Fecha y Hora: {{$my_date}}</h5>
    <div class="container" style="width: 1000px;">
        {!! $hidden_html !!}
    </div>
    <table style="margin: 0 auto; padding-top:20px;">
      <thead>
        <tr>
          <th>Fecha Creacion Ruta</th>
          <th>Tiendas Visitadas</th>
          <th>Tiendas Pendientes</th>
          <th>Efectividad</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rutas as $ruta)
        {{$efectividad=($ruta->tiendas_visitadas*100)/($ruta->tiendas_visitadas+$ruta->tiendas_pendientes)}}
        <tr>
          <td>{{$ruta->fecha}}</td>
          <td>{{$ruta->tiendas_visitadas}}</td>
          <td>{{$ruta->tiendas_pendientes}}</td>
          <td>{{round($efectividad, 2)}}%</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</body>
</html>
