<?php

namespace App\Http\Controllers;

use App\DetalleRuta;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DetalleRutaController extends Controller
{
    //
    public function ListarDetalleRutaporRutaIdJson(Request $request){
        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        $data=null;
        try{
            $ruta_id=$request["ruta_id"];

            $detalles=DetalleRuta::where("ruta_id",$ruta_id)->get();
            if($detalles){
                foreach($detalles as $detalle){
                    $detalle->tienda=$detalle->tienda()->first();
                }
            }
            $data=$detalles;
            $mensaje="Listando registros";
            $respuesta=true;
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["data"=>$data, "mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
}
