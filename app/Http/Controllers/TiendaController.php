<?php

namespace App\Http\Controllers;
use App\Tienda;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TiendaController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }
    public function TiendaVista(){
        return view('tienda.tiendavista');
    }
    public function AdminTiendaVista(){
        return view('admin.admintiendavista');
    }
    public function ListarTiendasJson(){
        $data=null;
        $mensaje="No se pudo listar los registros";
        $respuesta =false;
        try{
            $tiendas=Tienda::all();
            $data=$tiendas;
            $mensaje="Listando registros";
            $respuesta =true;
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
    public function ListarTiendasporSupervisorJson(){
        $data;
        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        try{
            $supervisor_id = request()->session()->get('supervisor_id');
            $data=Tienda::where('supervisor_id',$supervisor_id)->get();
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
