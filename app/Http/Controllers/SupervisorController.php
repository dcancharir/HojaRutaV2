<?php

namespace App\Http\Controllers;

use App\Supervisor;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function ListarSupervisores(){
        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        $data=null;
        try{
            $supervisores=Supervisor::all();
            $data=supervisores;
            $mensaje="Listando Registros";
            $respuesta=true;
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json([
            'data' => $data,
            'respuesta'=>$respuesta,
            'mensaje' => $mensaje
        ]);
    }
}
