<?php

namespace App\Http\Controllers;

use App\Ruta;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RutaController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }
    public function RutaVista(){
        return View('Ruta.RutaVista');
    }
    public function ListarRutasporUSuarioJson(Request $request){
        $mensaje="No se pudo listar los registros";

        $respuesta=false;
        $data=null;

        try{
            $supervisor_id=request()->session()->get('supervisor_id');
            $fechaInicio=$request->input('fechaInicio');
            $fechaFin=$request->input('fechaFin');

            $data=Ruta::where('supervisor_id',$supervisor_id)->whereBetween('fecha',[$fechaInicio,$fechaFin])->orderBy('fecha','desc')->get();
            $mensaje="Listando registros";
            $respuesta=true;

        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["data"=>$data, "mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
    public function ListarRutaDiaporUsuarioJson(){
        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        $data=null;
        try{
            $fechaHoy=date("Y-m-d");
            $supervisor_id=request()->session()->get('supervisor_id');

            $ruta=Ruta::where('supervisor_id',$supervisor_id)->where('fecha','>=',$fechaHoy)->first();
            if($ruta){
                $ruta->detalle_ruta=$ruta->detalles_ruta()->orderBy('orden','asc')->get();

                foreach($ruta->detalle_ruta as $detalle){
                    $detalle->tienda=$detalle->tienda()->first();
                }
            }
            $data=$ruta;

            $mensaje="Listando registros";
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
    public function ReporteEfectividadJson(Request $request){
        $data=null;
        $mensaje="No se pudieron listar los registros";
        $respuesta=false;
        try{
            $fechaInicio=$request["fechaInicio"];
            $fechaFin=$request["fechaFin"];
            $supervisor_id=request()->session()->get('supervisor_id');
            $rutas=Ruta::whereBetween('fecha',[$fechaInicio,$fechaFin])
                            ->where('supervisor_id',$supervisor_id)
                            ->orderBy('fecha','desc')
                            ->get();
            $data=$rutas;
            $mensaje="Listando registros";
            $respuesta=true;
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["data"=>$data, "mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
}
