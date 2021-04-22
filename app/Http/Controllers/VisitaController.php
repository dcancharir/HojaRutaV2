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
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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

            $tiendas_pendientes=$ruta->tiendas_pendientes;
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
    public function RespuestasporVisitaExportarPdf($visita_id){
        try{
            $my_date = date("Y-m-d H:i:s");
            $title="Reporte-RespuestasporVisita".$my_date;
            $visita=Visita::findOrFail($visita_id);
            //llenar respuestas
            $respuestas=$visita->respuestas()->get();
            foreach($respuestas as $respuesta){
                $respuesta->pregunta=$respuesta->pregunta()->first();
                $respuesta->opcion=$respuesta->opcion()->first();
            }

            $detalle_ruta=$visita->detalle_ruta()->first();
            $tienda=$detalle_ruta->tienda()->first();
            $detalle_ruta->tienda=$tienda;

            $supervisor=$visita->supervisor()->first();

            $visita->respuestas=$respuestas;
            $visita->detalle_ruta=$detalle_ruta;
            $visita->supervisor=$supervisor;
            $pdf = \PDF::loadView('visita.RespuestasporVisitaExportarPdf',[
                                    'visita'=>$visita,
                                    'fecha'=>$my_date
                                ]);
            $pdf->setPaper('a4','landscape');
            return $pdf->download($title.'.pdf');
        }catch(Exception $ex){

        }
    }

    public function RespuestasporVisitaExportarExcel($visita_id){
        $my_date = date("Y-m-d H:i:s");
        //styling arrays end
        //make a new spreadsheet object
        $spreadsheet = new Spreadsheet();
        //get current active sheet (first sheet)
        $sheet = $spreadsheet->getActiveSheet();

        //set default font
        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(10);
        $spreadsheet
        ->getProperties()
        ->setCreator("Software 3000 S.A.C")
        ->setLastModifiedBy('Software 3000 S.A.C')
        ->setTitle('CheckList')
        ->setSubject('Respuestas por Visita')
        ->setDescription('Excel generado de Respuesta por Visitas')
        ->setKeywords('Respuestas')
        ->setCategory('Respuestas');

        //heading
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1',"Listado de Rutas por Supervisor");

        //merge heading
        $spreadsheet->getActiveSheet()->mergeCells("A1:D1");

        // set font style
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);

        // set cell alignment
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        //setting column width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(78);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(75);

        //Subtitulo : Responsabilidad Cajero
        $spreadsheet->getActiveSheet()->setCellValue('A2',"Responsabilidad del Cajero");
        $spreadsheet->getActiveSheet()->mergeCells("A2:D2");
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        //header text
        $spreadsheet->getActiveSheet()
            ->setCellValue('A3',"#")
            ->setCellValue('B3',"Pregunta")
            ->setCellValue('C3',"Respuesta")
            ->setCellValue('D3',"Observaciones");

        $visita=Visita::findOrFail($visita_id);
        //llenar respuestas
        $respuestas=$visita->respuestas()->get();
        foreach($respuestas as $respuesta){
            $respuesta->pregunta=$respuesta->pregunta()->first();
            $respuesta->opcion=$respuesta->opcion()->first();
        }

        $detalle_ruta=$visita->detalle_ruta()->first();
        $tienda=$detalle_ruta->tienda()->first();
        $detalle_ruta->tienda=$tienda;

        $supervisor=$visita->supervisor()->first();

        $visita->respuestas=$respuestas;
        $visita->detalle_ruta=$detalle_ruta;
        $visita->supervisor=$supervisor;

        //loop through the data
        //current row
        $row=4;
        $indice=0;
        foreach($visita->respuestas as $valor){
            if($valor->pregunta->tipo==1){
                $indice++;
                $spreadsheet->getActiveSheet()
                    ->setCellValue('A'.$row , $indice)
                    ->setCellValue('B'.$row , $valor->pregunta->titulo)
                    ->setCellValue('C'.$row , $valor->opcion->opcion)
                    ->setCellValue('D'.$row , $valor->observacion);
                //increment row
                $row++;
            }
        }

        //Seccion Responsabilidad del Supervisor
        //Subtitulo : Responsabilidad del Supervosir
        $spreadsheet->getActiveSheet()->setCellValue('A'.$row,"Responsabilidad del Supervisor");
        $spreadsheet->getActiveSheet()->mergeCells("A".$row.":D".$row);
        $spreadsheet->getActiveSheet()->getStyle('A'.$row)->getFont()->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $row++;
          //header text
          $spreadsheet->getActiveSheet()
          ->setCellValue('A'.$row,"#")
          ->setCellValue('B'.$row,"Pregunta")
          ->setCellValue('C'.$row,"Respuesta")
          ->setCellValue('D'.$row,"Observaciones");

      //set font style and background color
        $indice=0;
        foreach($visita->respuestas as $valor){
            if($valor->pregunta->tipo==2){
                $indice++;
                $spreadsheet->getActiveSheet()
                    ->setCellValue('A'.$row , $indice)
                    ->setCellValue('B'.$row , $valor->pregunta->titulo)
                    ->setCellValue('C'.$row , $valor->opcion->opcion)
                    ->setCellValue('D'.$row , $valor->observacion);
                //increment row
                $row++;
            }
        }


        //set the autofilter
        // $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":D".$lastRow);
        $filename = 'Reporte-RespuestaporVisitas'.$my_date;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        //save into php output
        ob_end_clean();
        $writer->save('php://output');
    }
}
