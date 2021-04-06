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
