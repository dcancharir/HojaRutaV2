<?php

namespace App\Http\Controllers;
use App\Tienda;

use Illuminate\Http\Request;

class TiendaController extends Controller
{
    //
    public function listarTiendas(){
        $tiendas=Tienda::all();
        return $tiendas;
    }
}
