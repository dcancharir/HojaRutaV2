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
</style>
<body>

  <h2 align="center">Lista de Tiendas por Usuario</h2>
  <h4 align="right" >Supervisor: {{$supervisor->apellidos}}, {{$supervisor->nombres}}</h4>
  <h5 align="right" >Fecha y Hora: {{$fecha}}</h5>
  <table style="margin: 0 auto;">
      <thead>
        <tr>
          <th>ID</th>
          <th>CC</th>
          <th>Nombre</th>
          <th>Direccion</th>
          <th>Latitud</th>
          <th>Longitud</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tiendas as $tienda)
        <tr>
          <td>{{$tienda->tienda_id}}</td>
          <td>{{$tienda->cc}}</td>
          <td>{{$tienda->nombres}}</td>
          <td>{{$tienda->direccion}}</td>
          <td>{{$tienda->latitud}}</td>
          <td>{{$tienda->longitud}}</td>
          <td>{{$tienda->estado==1?'Activo':'Inactivo'}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
</body>
</html>
