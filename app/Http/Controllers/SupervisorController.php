<?php

namespace App\Http\Controllers;

use App\Supervisor;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SupervisorController extends Controller
{
    public function AdminSupervisoresVista(){
        return view('admin.adminsupervisoresvista');
    }
    public function ListarSupervisoresJson(){
        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        $data=null;
        $dataRoles=null;
        try{
            $supervisores=Supervisor::all();
            foreach($supervisores as $supervisor){
                $supervisor->roles;
            }
            $roles=Role::get();
            $dataRoles=$roles;
            $data=$supervisores;
            $mensaje="Listando Registros";
            $respuesta=true;

        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json([
            'data' => $data,
            'respuesta'=>$respuesta,
            'mensaje' => $mensaje,
            "dataRoles"=>$dataRoles,
        ]);
    }
    public function AsignarRolSupervisorJson(Request $request){
        $mensaje="No se pudo asignar el rol";
        $respuesta =false;
        try{
            $role_id=$request["role_id"];
            $supervisor_id=$request["supervisor_id"];
            $supervisor=Supervisor::find($supervisor_id);
            $role=Role::find($role_id);
            $supervisor->roles()->detach();

            $supervisor->assignRole($role->name);
            $mensaje="Rol Asignado";
            $respuesta=true;
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
}
