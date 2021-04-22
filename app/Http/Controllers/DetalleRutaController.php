<?php

namespace App\Http\Controllers;

use App\Ruta;
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
    public function InsertarDetalleRutaManualJson(Request $request){
        $mensaje="No se pudo insertar los registros";
        $respuesta=false;
        $listaTiendas=array();
        try{
            $ruta_id=$request["ruta_id"];
            $listaTiendas= json_decode($request["listaTiendas"]);
            $observacion=$request["observacion"];
            $detalles=DetalleRuta::where("ruta_id",$ruta_id)->get();
            $ruta=Ruta::findOrFail($ruta_id);

            $totalDetalles=count($detalles);
            $totalTiendasInsertar=count($listaTiendas);
            if($totalTiendasInsertar>0){

                foreach($listaTiendas as $tienda_id){
                    $totalDetalles++;

                    $detalle_ruta=new DetalleRuta();
                    $detalle_ruta->ruta_id=$ruta_id;
                    $detalle_ruta->tipo_detalle=3;//1.- Montos de venta, 2.- Frecuencia Semanal, 3.- Manual
                    $detalle_ruta->observacion=$observacion;
                    $detalle_ruta->tienda_id=$tienda_id;
                    $detalle_ruta->orden=$totalDetalles;
                    $detalle_ruta->save();
                }

                $tiendas_pendientes=$ruta->tiendas_pendientes+$totalTiendasInsertar;
                $ruta->tiendas_pendientes=$tiendas_pendientes;
                $ruta->save();

                $mensaje="Registros insertados";
                $respuesta=true;
            }
            else{
                $mensaje="No se selecciono ninguna Tienda";
            }
            $mensaje="Registros insertados";

            $respuesta=true;
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
}
