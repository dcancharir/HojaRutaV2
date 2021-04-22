<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    //
    public function AdminRoleVista(){
        return view('admin.adminrolevista');
    }
    public function GuardarRoleJson(Request $request){
        $mensaje="No se pudo insertar el registro";
        $respuesta =false;
        try{
            $name=$request["name"];
            $role = Role::create(['name' => $name]);
            $mensaje="Registro insertado";
            $respuesta=true;
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
    public function ActualizarRoleJson(Request $request){
        $mensaje="No se pudo editar el registro";
        $respuesta =false;
        try{
            $name=$request["name"];
            $role_id=$request["role_id"];
            $role=Role::findOrFail($role_id);
            $role->name=$name;
            $role->save();
            $mensaje="Registro editado";
            $respuesta=true;
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
    public function ListarRoleJson(){
        $mensaje="No se listar los registros";
        $respuesta =false;
        $data=null;
        try{
            $roles=Role::all();
            $data=$roles;
            $mensaje="Listando registros";
            $respuesta=true;
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["data"=>$data,"mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
    public function EliminarRoleJSon(Request $request){
        $mensaje="No se pudo eliminar el registro";
        $respuesta =false;
        try{
            $role_id=$request["role_id"];
            $role=Role::findOrFail($role_id);
            $role->delete();
            $mensaje="Registro eliminado";
            $respuesta=true;
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json(["mensaje"=>$mensaje,"respuesta"=>$respuesta]);
    }
}
