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
        if(request()->user()->HasRole('administrador')){
            return view('admin.admintiendavista');
        }
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

Route::group(["middleware"=>'permiso:supervisor'],function(){

    //home
    Route::get('home', 'HomeController@index')->name('home');

    // //Supervisores
    // Route::get('Supervisores', 'SupervisorController@ListarSupervisoresJson');

    //Tiendas
    Route::get('Tienda', 'TiendaController@TiendaVista')->name('Tienda');
    Route::post('ListarTiendasporSupervisorJson', 'TiendaController@ListarTiendasporSupervisorJson');
    Route::get('TiendaExportarExcel','TiendaController@TiendaExportarExcel');
    Route::get('TiendaExportarPdf','TiendaController@TiendaExportarPdf');
    //ventas
    Route::get('Venta/{tienda_id}', 'VentaController@VentaVista');
    Route::post('VentaListarJson','VentaController@ListarVentasporTiendaJson');
    Route::get('VentaExportarPdf/{tienda_id}','VentaController@VentaExportarPdf');
    Route::get('VentaExportarExcel/{tienda_id}','VentaController@VentaExportarExcel');
    //Ruta
    Route::get('Ruta','RutaController@RutaVista')->name('Ruta');
    Route::post('RutaDiariaJson','RutaController@listarRutaDiaporUsuarioJson');
    Route::post('RutaListarporUsuarioJson','RutaController@listarRutasporUSuarioJson');
    Route::post('ReporteEfectividadJson','RutaController@ReporteEfectividadJson');
    Route::post('ReporteEfectividadExportarPdf','RutaController@ReporteEfectividadExportarPdf');
    Route::post('ReporteEfectividadExportarExcel','RutaController@ReporteEfectividadExportarExcel');

    //Visitas, Preguntas y Opciones a responder
    Route::get('Visita','VisitaController@VisitaVista')->name('Visita');
    Route::get('ReporteEfectividad','VisitaController@ReporteEfectividadVista')->name('ReporteEfectividad');

    Route::post('ListarPreguntasyOpcionesJson','VisitaController@ListarPreguntasyOpcionesJson');
    Route::post('VisitaGuardarJson','VisitaController@VisitaGuardarJson');
    Route::post('ListarVisitasporSupervisorJson','VisitaController@listarVisitasporSupervisorJson');
    Route::post('ObtenerVisitaIdJson','VisitaController@ObtenerVisitaIdJson');
    Route::post('ListarVisitasporTienda','VisitaController@ListarVisitasporTienda');
    Route::get('RespuestasporVisitaExportarPdf/{visita_id}','VisitaController@RespuestasporVisitaExportarPdf');
    Route::get('RespuestasporVisitaExportarExcel/{visita_id}','VisitaController@RespuestasporVisitaExportarExcel');

    //Detalle Ruta
    Route::post('ListarDetalleRutaporRutaIdJson','DetalleRutaController@ListarDetalleRutaporRutaIdJson');
    Route::post('InsertarDetalleRutaManualJson','DetalleRutaController@InsertarDetalleRutaManualJson');
});




//Admin
Route::group(["middleware"=>'permiso:administrador'],function(){
    //Tiendas
    Route::get('AdminTienda','TiendaController@AdminTiendaVista')->name('AdminTienda');
    Route::post('ListarTiendasJson', 'TiendaController@ListarTiendasJson');
    Route::post('EditarFrecuenciaSemanalTiendaJson','TiendaController@EditarFrecuenciaSemanalTiendaJson');
    //Supervisores
    Route::get('AdminSupervisores','SupervisorController@AdminSupervisoresVista')->name('AdminSupervisores');
    Route::post('ListarSupervisoresJson', 'SupervisorController@ListarSupervisoresJson');
    Route::post('AsignarRolSupervisorJson','SupervisorController@AsignarRolSupervisorJson');
    //Roles
    Route::get('AdminRole','RoleController@AdminRoleVista')->name('AdminRole');
    Route::post('ListarRoleJson','RoleController@ListarRoleJson');
    Route::post('GuardarRoleJson','RoleController@GuardarRoleJson');
    Route::post('ActualizarRoleJson','RoleController@ActualizarRoleJson');
    Route::post('EliminarRoleJSon','RoleController@EliminarRoleJSon');
});

