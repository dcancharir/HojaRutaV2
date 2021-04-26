<?php

namespace App\Http\Controllers;

use App\Ruta;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
    public function ReporteEfectividadExportarPdf(Request $request){
        $my_date = date("Y-m-d H:i:s");
        $title="ReporteEfectividad".$my_date;
        $pdf=null;
        try{
            $fechaInicio=$request["fechaInicio"];
            $fechaFin=$request["fechaFin"];
            $hidden_html=$request["hidden_html"];

            $supervisor_id=request()->session()->get('supervisor_id');
            $rutas=Ruta::whereBetween('fecha',[$fechaInicio,$fechaFin])
                            ->where('supervisor_id',$supervisor_id)
                            ->orderBy('fecha','desc')
                            ->get();
            $supervisor=auth()->user();
            $pdf = \PDF::loadView('Visita.ReporteEfectividadExportarPdf',['hidden_html'=>$hidden_html,'supervisor'=>$supervisor,'rutas'=>$rutas,'my_date'=>$my_date]);
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return $pdf->download($title.'.pdf');
    }
    public function ReporteEfectividadExportarExcel(Request $request){

        $fechaInicio=$request["fechaInicio"];
        $fechaFin=$request["fechaFin"];
        $hidden_html=$request["hidden_html"];

        $supervisor_id=request()->session()->get('supervisor_id');

        $supervisor=auth()->user();

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
        ->setTitle('Efectividad')
        ->setSubject('Reporte de Efectividad')
        ->setDescription('Excel generado de Reporte de Efectividad')
        ->setKeywords('Ventas')
        ->setCategory('Ventas');

        //heading
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1',"Reporte de Efectividad");

        //merge heading
        $spreadsheet->getActiveSheet()->mergeCells("A1:D1");

        // set font style
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);

        // set cell alignment
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        //setting column width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);

        //header text
        $spreadsheet->getActiveSheet()
            ->setCellValue('A2',"Fecha Creación Ruta")
            ->setCellValue('B2',"Tiendas Visitadas")
            ->setCellValue('C2',"Tiendas Pendientes")
            ->setCellValue('D2',"Efectividad (%)");

        $rutas=Ruta::whereBetween('fecha',[$fechaInicio,$fechaFin])
        ->where('supervisor_id',$supervisor_id)
        ->orderBy('fecha','desc')
        ->get();
        $row=3;
        foreach($rutas as $valor){
            $efectividad=($valor->tiendas_visitadas*100)/($valor->tiendas_visitadas+$valor->tiendas_pendientes);
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$row , $valor->fecha)
                ->setCellValue('B'.$row , $valor->tiendas_visitadas)
                ->setCellValue('C'.$row , $valor->tiendas_pendientes)
                ->setCellValue('D'.$row , round($efectividad, 2));
             //increment row
            $row++;
        }
        //set the autofilter
        $filename = 'ReporteEfectividadExcel'.$my_date;
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
