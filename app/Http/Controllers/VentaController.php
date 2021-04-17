<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VentaController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }
    public function VentaVista($tienda_id){
        return view('Venta.VentaVista',['tienda_id'=> $tienda_id]);
    }
    public function listarVentasporTiendaJson(Request $request){

        $mensaje="No se pudo listar los registros";
        $respuesta=false;
        $data=null;
        $tienda_id=$request->input('tienda_id');
        try{
            $ventas=Venta::where('tienda_id',$tienda_id)->get();
            foreach($ventas as $venta){
                $venta->tienda=$venta->tienda()->first();
            }
            $data=$ventas;
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
    public function VentaExportarPdf($tienda_id){
        $my_date = date("Y-m-d H:i:s");
        $title="reporteVenta".$my_date;
        try{
            $ventas=Venta::where('tienda_id',$tienda_id)->orderBy('fecha','desc')->get();
            foreach($ventas as $venta){
                $venta->tienda=$venta->tienda()->first();
            }

            $supervisor_id = request()->session()->get('supervisor_id');
            $supervisor=Supervisor::find($supervisor_id);
            $pdf = \PDF::loadView('Venta.VentaExportarPdf',['ventas'=>$ventas,'supervisor'=>$supervisor,'fecha'=>$my_date]);
            // $pdf->setPaper('a4','landscape');
            return $pdf->download($title.'.pdf');
            // return $pdf->stream($title.'.pdf');
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
    }
    public function VentaExportarExcel($tienda_id){
        $my_date = date("Y-m-d H:i:s");
        $tableHead = [
            'font'=>[
                'color'=>[
                    'rgb'=>'000000'
                ],
                'bold'=>true,
                'size'=>11
            ],
            'fill'=>[
                'fillType' => Fill::FILL_NONE,
                'startColor' => [
                    'rgb' => 'ffffff'
                ]
            ],
        ];
        //even row
        $evenRow = [
            'fill'=>[
                'fillType' => Fill::FILL_NONE,
                'startColor' => [
                    'rgb' => 'fafafa'
                ]
            ],
        ];
        //odd row
        $oddRow = [
            'fill'=>[
                'fillType' => Fill::FILL_NONE,
                'startColor' => [
                    'rgb' => 'ffffff'
                ]
            ]
        ];
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
        ->setTitle('Ventas')
        ->setSubject('Ventas por Tienda')
        ->setDescription('Excel generado de Listado de Ventas por Tienda')
        ->setKeywords('Ventas')
        ->setCategory('Ventas');

        //heading
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1',"Listado de Ventas por Tienda");

        //merge heading
        $spreadsheet->getActiveSheet()->mergeCells("A1:E1");

        // set font style
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);

        // set cell alignment
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        //setting column width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);

        //header text
        $spreadsheet->getActiveSheet()
            ->setCellValue('A2',"Id")
            ->setCellValue('B2',"Fecha")
            ->setCellValue('C2',"Tienda")
            ->setCellValue('D2',"Monto")
            ->setCellValue('E2',"Moneda");
        //set font style and background color
        $spreadsheet->getActiveSheet()->getStyle('A2:E2')->applyFromArray($tableHead);


        $ventas=Venta::where('tienda_id',$tienda_id)->orderBy('fecha','desc')->get();
        foreach($ventas as $venta){
            $venta->tienda=$venta->tienda()->first();
        }


        //loop through the data
        //current row
        $row=3;
        foreach($ventas as $valor){
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$row , $valor->venta_id)
                ->setCellValue('B'.$row , $valor->fecha)
                ->setCellValue('C'.$row , $valor->tienda->nombres)
                ->setCellValue('D'.$row , $valor->monto)
                ->setCellValue('E'.$row , $valor->moneda);

            //set row style
            if( $row % 2 == 0 ){
                //even row
                $spreadsheet->getActiveSheet()->getStyle('A'.$row.':E'.$row)->applyFromArray($evenRow);
            }else{
                //odd row
                $spreadsheet->getActiveSheet()->getStyle('A'.$row.':E'.$row)->applyFromArray($oddRow);
            }
            //increment row
            $row++;
        }

        //autofilter
        //define first row and last row
        $firstRow=2;
        $lastRow=$row-1;
        //set the autofilter
        $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":E".$lastRow);
        $filename = 'ListaVentasporTienda'.$my_date;
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
