


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Ventas por Tienda</title>

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

  <h2 align="center">Lista de Ventas por Tienda</h2>
  <h4 align="right" >Supervisor: {{$supervisor->nombres}}</h4>
  <h5 align="right" >Fecha y Hora de Reporte: {{$fecha}}</h5>
  <table style="margin: 0 auto;">
      <thead>
        <tr>
          <th>ID</th>
          <th>Fecha</th>
          <th>Tienda</th>
          <th>Monto</th>
          <th>Moneda</th>
        </tr>
      </thead>
      <tbody>
        @foreach($ventas as $valor)
        <tr>
          <td>{{$valor->venta_id}}</td>
          <td>{{$valor->fecha}}</td>
          <td>{{$valor->tienda->nombres}}</td>
          <td>{{$valor->monto}}</td>
          <td>{{$valor->moneda}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
</body>
</html>
