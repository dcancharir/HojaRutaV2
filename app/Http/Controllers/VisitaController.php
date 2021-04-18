<?php

namespace App\Http\Controllers;

use App\Ruta;
use App\Opcion;
use App\Tienda;
use App\Visita;
use App\Pregunta;
use App\Respuesta;
use App\DetalleRuta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VisitaController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }
    public function VisitaVista(){
        return view('visita.visitavista');
    }
    public function ReporteEfectividadVista(){
        return view('visita.reporteefectividadVista');
    }
    public function ObtenerVisitaIdJson(Request $request){
        $mensaje="No se pudo obtener el registro";
        $respuesta=false;
        $data=null;
        try{
            $visita_id=$request["visita_id"];
            $visita=Visita::find($visita_id);
            if($visita){
                $respuestas=$visita->respuestas()->get();
                foreach($respuestas as $respuesta){
                    $respuesta->pregunta=$respuesta->pregunta()->first();
                    $respuesta->opcion=$respuesta->opcion()->first();
                }
                $visita->respuestas=$respuestas;
            }
            $data=$visita;
            $respuesta=true;
            $mensaje="Obteniendo registro";
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["data"=>$data,"mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
    public function ListarVisitasporSupervisorJson(){
        $data=null;
        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        try{
            $supervisor_id = request()->session()->get('supervisor_id');
            $visitas=Visita::where('supervisor_id',$supervisor_id)->get();

            foreach($visitas as $visita){
                $detalle_ruta=$visita->detalle_ruta()->first();
                $detalle_ruta->tienda=$detalle_ruta->tienda()->first();
                $detalle_ruta->ruta=$detalle_ruta->ruta()->first();
                $visita->detalle_ruta=$detalle_ruta;
            }
            $data=$visitas;
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
    public function ListarPreguntasyOpcionesJson(){
        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        $data=null;
        try{

            $preguntas=Pregunta::where('estado',1)->get();
            $opciones=Opcion::where('estado',1)->get();
            $data = array(
                'preguntas' => $preguntas,
                'opciones'=>$opciones
            );
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
    public function VisitaGuardarJson(Request $request){
        $mensaje="Error al registrar visita";
        $respuesta=false;

        try{
            // $results=array();
            $ruta_id=$request["ruta_id"];
            $detalle_ruta_id=$request["detalle_ruta_id"];
            $listaRespuestas=$request["listaRespuestas"];
            $supervisor_id = request()->session()->get('supervisor_id');

            $ruta=Ruta::find($ruta_id);
            $detalle_ruta=DetalleRuta::find($detalle_ruta_id);
            //insertando visitas
            $visita=new Visita();
            $visita->fecha_visita=date('Y-m-d H:i:s');
            $visita->latitud="";
            $visita->longitud="";
            $visita->imei="";
            $visita->estado_visita=1;
            $visita->observacion="";
            $visita->orden=$ruta->tiendas_visitadas+1;
            $visita->supervisor_id=$supervisor_id;
            $visita->save();

            // //insertando masiva de respuestas
            foreach($listaRespuestas as $respuesta){
                $results[]=array(
                    'pregunta_id'=>$respuesta["pregunta_id"],
                    'opcion_id'=>$respuesta["opcion_id"],
                    'observacion'=>$respuesta["observacion"]==null ? "" : $respuesta["observacion"],
                    'visita_id' =>$visita->visita_id,
                    'tipo'=>1,
                    'estado'=>1
                );
            }
            DB::table("respuesta")->insert($results);
            // //Edicion de tablas ruta y detalle_ruta

            $detalle_ruta->visita_id = $visita->visita_id;
            $detalle_ruta->save();

            $tiendas_visitadas=$ruta->tiendas_visitadas;
            $tiendas_pendientes=$ruta->tiendas_pendientes;
            $ruta->tiendas_visitadas=$tiendas_visitadas+1;
            $ruta->tiendas_pendientes=$tiendas_pendientes-1;
            if($tiendas_pendientes==0){
                $ruta->estado_completo=1;
            }
            $ruta->save();

            //Editar tienda
            $tienda=Tienda::find($detalle_ruta->tienda_id);

            $nro_visitas_semana=$tienda->nro_visitas_semana;
            $tienda->nro_visitas_semana=$nro_visitas_semana+1;

            $tienda->save();
            //Retornar respuesta
            $mensaje="Visita registrada";
            $respuesta=true;

        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(['respuesta'=>$respuesta,'mensaje' => $mensaje]);
    }
    public function ListarVisitasporTienda(Request $request){
        $data=null;
        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        $data=null;
        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        try{
            $arrayTiendas=$request["arrayTiendas"];
            $fechaInicio=$request["fechaInicio"];
            $fechaFin=$request["fechaFin"];
            $detalle_ruta=DetalleRuta::join('visita','detalle_ruta.visita_id','=','visita.visita_id')
            ->whereIn('detalle_ruta.tienda_id',$arrayTiendas)
            ->whereBetween('visita.fecha_visita',[$fechaInicio,$fechaFin])
            ->get();
            foreach($detalle_ruta as $detalle){
                $tienda=$detalle->tienda()->first();
                $visita=$detalle->visita()->first();
                $detalle->tienda=$tienda;
                $detalle->visita=$visita;
            }
            $data=$detalle_ruta;

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
}
