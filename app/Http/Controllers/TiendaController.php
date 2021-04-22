<?php

namespace App\Http\Controllers;
use App\Tienda;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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
    public function EditarFrecuenciaSemanalTiendaJson(Request $request){
        $mensaje="No se pudo editar el registro";
        $respuesta=false;
        try{
            $tienda_id=$request["tienda_id"];
            $frecuencia_semanal=$request["frecuencia_semanal"];
            $tienda=Tienda::find($tienda_id);
            if($tienda){
                $tienda->frecuencia_semanal=$frecuencia_semanal;
                $tienda->save();
                $mensaje="Registro Editado";
                $respuesta=true;
            }
        }catch(Exception $ex){

        }catch (ModelNotFoundException $ex) {

        }catch (QueryException $ex) {

        }
        return response()->json([
            'respuesta'=>$respuesta,
            'mensaje' => $mensaje
        ]);
    }
    public function TiendaExportarExcel(){
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
        ->setTitle('Tiendas')
        ->setSubject('Tiendas por Supervisor')
        ->setDescription('Excel generado de Listado de Tiendas por Supervisor')
        ->setKeywords('Tiendas')
        ->setCategory('Tiendas');

        //heading
        $spreadsheet->getActiveSheet()
            ->setCellValue('A1',"Listado de Tiendas por usuario");

        //merge heading
        $spreadsheet->getActiveSheet()->mergeCells("A1:G1");

        // set font style
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);

        // set cell alignment
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        //setting column width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);

        //header text
        $spreadsheet->getActiveSheet()
            ->setCellValue('A2',"Id")
            ->setCellValue('B2',"CC")
            ->setCellValue('C2',"Nombre")
            ->setCellValue('D2',"Direccion")
            ->setCellValue('E2',"Latitud")
            ->setCellValue('F2',"Longitud")
            ->setCellValue('G2','Estado');

        //set font style and background color
        $spreadsheet->getActiveSheet()->getStyle('A2:G2')->applyFromArray($tableHead);

        //the content
        //read the json file
        $supervisor_id = request()->session()->get('supervisor_id');
        $lista = Tienda::where('supervisor_id',$supervisor_id)->where('estado',1)->get();

        //loop through the data
        //current row
        $row=3;
        foreach($lista as $valor){
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$row , $valor->tienda_id)
                ->setCellValue('B'.$row , $valor->cc)
                ->setCellValue('C'.$row , $valor->nombres)
                ->setCellValue('D'.$row , $valor->direccion)
                ->setCellValue('E'.$row , $valor->latitud)
                ->setCellValue('F'.$row , $valor->longitud)
                ->setCellValue('G'.$row , $valor->estado==1?'Activo':'Inactivo');

            //set row style
            if( $row % 2 == 0 ){
                //even row
                $spreadsheet->getActiveSheet()->getStyle('A'.$row.':G'.$row)->applyFromArray($evenRow);
            }else{
                //odd row
                $spreadsheet->getActiveSheet()->getStyle('A'.$row.':G'.$row)->applyFromArray($oddRow);
            }
            //increment row
            $row++;
        }

        //autofilter
        //define first row and last row
        $firstRow=2;
        $lastRow=$row-1;
        //set the autofilter
        $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":G".$lastRow);
        $filename = 'ReporteTiendas'.$my_date;
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
    public function TiendaExportarPdf(){
        $my_date = date("Y-m-d H:i:s");
        $title="reporteTiendas".$my_date;
        try{
            $supervisor_id = request()->session()->get('supervisor_id');
            $supervisor=auth()->user();
            $lista = Tienda::where('supervisor_id',$supervisor_id)->where('estado',1)->get();
            $pdf = \PDF::loadView('Tienda.TiendaExportarPdf', ['tiendas'=>$lista,'supervisor'=>$supervisor,'fecha'=>$my_date]);
            // $pdf->setPaper('a4','landscape');
            return $pdf->download($title.'.pdf');
        }
        catch(Exception $ex){

        }

    }
}
