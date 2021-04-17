<?php

namespace App\Console\Commands;
use App\Ruta;
use App\Venta;
use App\Tienda;
use App\Supervisor;
use App\DetalleRuta;
use App\ApiApuestaTotal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CrearRutas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crear:rutas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        // $url='http://supervisores.api:8080/api/v1/dataHojaRuta';
        $nombreDiaHoy=date("l");
        if($nombreDiaHoy=='Monday'){
            echo "Hoy es lunes, se actualizaran las visitas de la semana a 0\n";
            DB::table("tienda")->update(['nro_visitas_semana' => 0]);
            echo "Nro Visitas semana actualizado\n";
        }
        echo "Consultando API \n";
        $respuestaApi=CrearRutas::listarSupervisoresAPI();
        if($respuestaApi["success"]==true){

            echo "data API Obtenida \n";
            $supervisoresApi=$respuestaApi["data"];
            CrearRutas::LlenarDataApi($supervisoresApi);
            echo "Data Insertada \n";

        }
        echo "creando rutas \n";
        CrearRutas::CrearRutas();
        echo "rutas creadas \n";
    }
    public function listarSupervisoresAPI(){
        $apiApuesta = new ApiApuestaTotal();
        $respuesta_api = $apiApuesta->listarDataRutasAPI();
        $respuesta_api = (string)$respuesta_api;
        $respuesta = json_decode($respuesta_api,true);
        return $respuesta;
    }
    public function CrearRutas(){
        $fechaHoy=date("Y-m-d");
        $supervisores=Supervisor::all();

        //llenado de data
        foreach($supervisores as $supervisor){
            $tiendas=$supervisor->tiendas()->orderBy('frecuencia_semanal','DESC')->get();
            foreach($tiendas as $tienda){
                $tienda->ventahoy=$tienda->ventas()
                ->where('fecha',$fechaHoy)->first();
            }
            $supervisor->tienda=$tiendas;
        }
        //creacion de rutas
        foreach($supervisores as $supervisor){

            $orden=0;
            $ruta=new Ruta();

            $ruta->supervisor_id = $supervisor->supervisor_id;
            $ruta->fecha = date("Y-m-d H:i:s");
            $ruta->estado_completo = 0;//0:pendiente;1:completo
            $ruta->tiendas_visitadas = 0;
            $ruta->tiendas_pendientes = 4;
            $ruta->tipo_ruta = 1;//tipo de ruta sistema
            $ruta->save();
            $ruta->ruta_id;
            $tiendas=$supervisor->tienda;

            //llenando array con montos de venta por tienda perteneciente a supervisor
            $arrayVentas=array();
            foreach($tiendas as $tienda){
                $ventaDiaria=$tienda->ventahoy;
                // dd($ventaDiaria->monto);
                $venta=new Venta();
                $venta->venta_id=$ventaDiaria->venta_id;
                $venta->fecha=$ventaDiaria->fecha;
                $venta->tienda_id=$ventaDiaria->tienda_id;
                $venta->monto=$ventaDiaria->monto;
                $venta->moneda=$ventaDiaria->moneda;
                array_push($arrayVentas,$venta);
            }
            //ordenando ventas

            usort($arrayVentas, CrearRutas::object_sorter('monto','DESC'));

            //creacion de detalle de rutas -4- detalles
            foreach($arrayVentas as $ventaTienda){
                // 2 detalles por montos de venta
                if($orden<=1){
                    $detalle_ruta=new DetalleRuta();
                    $detalle_ruta->ruta_id=$ruta->ruta_id;
                    $detalle_ruta->orden=$orden+1;
                    $detalle_ruta->tienda_id=$ventaTienda->tienda_id;
                    $detalle_ruta->tipo_detalle=1;//1.-Montos de Venta;2.- Frecuencia Semanal
                    $detalle_ruta->observacion="";
                    $detalle_ruta->save();
                    $orden++;
                }
            }
            foreach($tiendas as $tienda){
                // 2 detalles por frecuencia semanal
                if($orden<=3){
                    $detalleEncontrado=DetalleRuta::where('tienda_id',$tienda->tienda_id)->where('ruta_id',$ruta->ruta_id)->first();
                    if($detalleEncontrado==null){
                        if($tienda->frecuencia_semanal>$tienda->nro_visitas_semana){
                            $detalle_ruta=new DetalleRuta();
                            $detalle_ruta->ruta_id=$ruta->ruta_id;
                            $detalle_ruta->orden=$orden+1;
                            $detalle_ruta->tienda_id=$tienda->tienda_id;
                            $detalle_ruta->tipo_detalle=2;//1.-Montos de Venta;2.- Frecuencia Semanal
                            $detalle_ruta->observacion="";
                            $detalle_ruta->save();
                            $orden++;
                        }
                    }
                }
            }
        }
    }
    public function LlenarDataApi($respuestaApi){
        echo "insertando data \n";
        $supervisoresApi=$respuestaApi;
        $fechaHoy=date("Y-m-d");
        foreach($supervisoresApi as $supervisorAPI){
            //Buscar supervisor en BD
            $supervisorEncontrado=Supervisor::where('usuario',$supervisorAPI["usuario"])->where('estado',1)->first();

            $tiendasAPI=$supervisorAPI["tienda"];
            if($supervisorEncontrado){
                // seccion tiendas
                foreach($tiendasAPI as $tiendaAPI){

                    $tiendaEncontrada=Tienda::where('cc',$tiendaAPI["cc"])->where('supervisor_id',$supervisorEncontrado->supervisor_id)->where('estado',1)->first();

                    $ventaDiara=array_shift($tiendaAPI["ventahoy"]);
                    $venta=new Venta();
                    $venta->fecha=$ventaDiara["fecha"];
                    $venta->monto=$ventaDiara["monto"];
                    $venta->moneda=$ventaDiara["moneda"];
                    if($tiendaEncontrada){
                        $ventaEncontrada=Venta::where('fecha',$fechaHoy)->where('tienda_id',$tiendaEncontrada->tienda_id)->first();
                        if($ventaEncontrada==null){

                            $venta->tienda_id=$tiendaEncontrada->tienda_id;
                            $venta->save();

                        }
                    }
                    else{
                        Tienda::where('cc',$tiendaAPI["cc"])->update(['estado'=>0]);
                        //Insertar tienda
                        $tienda=new Tienda();
                        $tienda->supervisor_id=$supervisorEncontrado->supervisor_id;
                        $tienda->cc = $tiendaAPI["cc"];
                        $tienda->nombres = $tiendaAPI["nombre"];
                        $tienda->direccion=$tiendaAPI["direccion"];
                        $tienda->razon_social_id=$tiendaAPI["razon_social_id"];
                        $tienda->latitud=$tiendaAPI["latitud"];
                        $tienda->longitud=$tiendaAPI["longitud"];
                        $tienda->correo_sop=$tiendaAPI["correo_sop"];
                        $tienda->correo_jop=$tiendaAPI["correo_jop"];
                        $tienda->frecuencia_semanal=random_int(1, 4);
                        $tienda->estado=1;
                        $tienda->nro_visitas_semana=0;
                        $tienda->save();

                        $tienda->tienda_id;
                        $venta->tienda_id=$tienda->tienda_id;
                        $venta->save();
                    }
                }
            }
            else{
                //Insertar supervisor, tiendas y ventas, ya que es nuevo
                $supervisor=new Supervisor();
                $supervisor->usuario=$supervisorAPI["usuario"];
                $supervisor->nombres=$supervisorAPI["nombres"]." ".$supervisorAPI["apellidos"];
                $supervisor->estado=1;
                $supervisor->save();
                //obteniendo id insertado
                $supervisor->supervisor_id;

                foreach($tiendasAPI as $tiendaApi){
                    //Desactivar otras tiendas con el mismo [cc]
                    DB::table('tienda')->where('cc',$tiendaApi["cc"])->update(['estado'=>0]);

                    //Insertar tienda
                    $tienda=new Tienda();
                    $tienda->supervisor_id=$supervisor->supervisor_id;
                    $tienda->cc = $tiendaApi["cc"];
                    $tienda->nombres = $tiendaApi["nombre"];
                    $tienda->direccion=$tiendaApi["direccion"];
                    $tienda->razon_social_id=$tiendaApi["razon_social_id"];
                    $tienda->latitud=$tiendaApi["latitud"];
                    $tienda->longitud=$tiendaApi["longitud"];
                    $tienda->correo_sop=$tiendaApi["correo_sop"];
                    $tienda->correo_jop=$tiendaApi["correo_jop"];
                    $tienda->frecuencia_semanal=random_int(1, 4);
                    $tienda->estado=1;
                    $tienda->nro_visitas_semana=0;
                    $tienda->save();

                    //obteniendo id insertado
                    $tienda->tienda_id;

                    //Insertar venta
                    $venta=new Venta();
                    $ventaDiara=array_shift($tiendaApi["ventahoy"]);
                    $venta->fecha=$ventaDiara["fecha"];
                    $venta->tienda_id=$tienda->tienda_id;
                    $venta->monto=$ventaDiara["monto"];
                    $venta->moneda=$ventaDiara["moneda"];
                    $venta->save();
                }
            }
        }
    }
    function object_sorter($clave,$orden=null) {
        return function ($a, $b) use ($clave,$orden) {
              $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
              return $result;
        };
    }
}
