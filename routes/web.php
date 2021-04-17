<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('dashboard.home');
// });
Route::get('/', function () {
    if(request()->session()->has('supervisor_id')){
        return view('dashboard.home');
    }
    return view('auth.login');
});

// Auth::routes();
Route::get('login',function(){
    if(request()->session()->has('supervisor_id')){
        return view('dashboard.home');
    }
    return view('auth.login');
});

Route::post('logout','Auth\LoginController@logout')->name('logout');
Route::post('login','Auth\LoginController@login')->name('login');
Route::get('home', 'HomeController@index')->name('home');

//Supervisores
Route::get('supervisores', 'SupervisorController@listarSupervisores');

//Tiendas
Route::get('Tienda', 'TiendaController@TiendaVista')->name('Tienda');
Route::post('ListarTiendasporSupervisorJson', 'TiendaController@ListarTiendasporSupervisorJson');

//ventas
Route::get('Venta/{tienda_id}', 'VentaController@VentaVista');
Route::post('VentaListarJson','VentaController@listarVentasporTiendaJson');
Route::get('VentaExportarPdf/{tienda_id}','VentaController@VentaExportarPdf');
Route::get('VentaExportarExcel/{tienda_id}','VentaController@VentaExportarExcel');
//Ruta
Route::get('Ruta','RutaController@RutaVista')->name('Ruta');
Route::post('RutaDiariaJson','RutaController@listarRutaDiaporUsuarioJson');
Route::post('RutaListarporUsuarioJson','RutaController@listarRutasporUSuarioJson');

//Visitas, Preguntas y Opciones a responder
Route::get('Visita','VisitaController@VisitaVista')->name('Visita');
Route::post('ListarPreguntasyOpcionesJson','VisitaController@ListarPreguntasyOpcionesJson');
Route::post('VisitaGuardarJson','VisitaController@VisitaGuardarJson');
Route::post('listarVisitasporSupervisorJson','VisitaController@listarVisitasporSupervisorJson');
Route::post('ObtenerVisitaIdJson','VisitaController@ObtenerVisitaIdJson');
Route::post('ListarVisitasporTienda','VisitaController@ListarVisitasporTienda');

//Detalle Ruta
Route::post('listarDetalleRutaporRutaIdJson','DetalleRutaController@listarDetalleRutaporRutaIdJson');


//Admin
Route::get('AdminTienda','TiendaController@AdminTiendaVista')->name('AdminTienda');
Route::post('ListarTiendasJson', 'TiendaController@ListarTiendasJson');
