<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH."/third_party/fpdf.php";

//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class Impresion {

    public $pdf = null;
    public $parameters = null;
    public $meses = null;

    public function __construct($parameters){
            $this->pdf = new FPDF();
            $this->parameters = $parameters;

            $this->meses = ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'];
    }

    public function imprimir_borrador($data){
        $CI =& get_instance();
        //--------------
        $CI->load->model('seccion'); 
        //$this->pdf = new Pdf();
        $this->pdf->AddPage('P','A4-3');
        $this->pdf->SetMargins(10,10,20);
        $this->pdf->AliasNbPages();
        $pageWidth = 200;
        $pageHeight = 255;
        $margin = 10;
        $this->pdf->Rect( $margin, $margin , $pageWidth - $margin , $pageHeight-$margin);
        $seccion = $data['seccion'];
        $this->pdf->SetTitle($seccion->nombre);
        $capacidades = $data['capacidades'];
        $this->pdf->Rect(10, 10, 7, 20);
        $this->pdf->Rect(17, 10, 70, 20);
        $cap = count($capacidades);
        $x = 87;
        $xx = $x;
        $dimension = 106/$cap;
        $this->pdf->SetFont('Arial','B',8);
        foreach ($capacidades as $key => $value) {
            $this->pdf->Rect($x, 10, $dimension, 10);
            $this->pdf->Text($x+$dimension/3, 15, $value['capacidad']->nombre);
            $items = $value['items'];
            $di = $dimension/count($items);
            foreach ($items as $k => $v) {
                $this->pdf->Text($xx+$di/3, 25, 'I'.($k+1));
                if($k == count($items)-1){
                    $this->pdf->Rect($xx, 20, $dimension - $di*(count($items)-1), 10);
                    $xx+=number_format($dimension - $di*(count($items)-1),0,'','');
                }
                else{
                    $this->pdf->Rect($xx, 20, $di, 10);
                    $xx+=number_format($di,0,'','');
                }
            }
            $x+=number_format($dimension,0,'','');
        }
        $this->pdf->Rect(193, 10, 7, 20);
        $this->pdf->SetFont('Arial','B',8);
        $title = iconv('UTF-8', 'windows-1252', 'N° DE ORDEN');
        $this->pdf->TextWithDirection(15,29.5,  $title,'U');
        $this->pdf->Text(25, 20, 'APELLIDOS Y NOMBRES(Riguroso orden');
        $title = iconv('UTF-8', 'windows-1252', 'alfabético)');
        $this->pdf->Text(45, 25, $title);
        $this->pdf->TextWithDirection(197,29.5,  'PROMEDIO','U');
        //$this->pdf->Cell(40,0,'APELLIDOS Y NOMBRES',0,0,'C');
        $alumnos = $data['alumnos'];
        $y = 30;
        $this->pdf->Ln(23);
        $this->pdf->SetFont('Arial','',8);
        $yy = 30;
        $xx = 87;
        for($i = 0; $i < 45; $i++){
            $this->pdf->Rect(10, $y+5*$i, 7, 5);
            $this->pdf->Rect(17, $y+5*$i, 70, 5);
            $this->pdf->Cell(7,0,($i+1),0,0,'C');
            if(isset($alumnos[$i])){
                $this->pdf->Cell(70,0,$alumnos[$i]->apell_pat.' '.$alumnos[$i]->apell_mat.', '.$alumnos[$i]->nombre,0,0,'L');
                //$this->pdf->Cell(183,0,' ',0,0,'L');
                //$this->pdf->Cell(7,0,$alumnos[$i]->valor_nota,0,0,'L');
                $this->pdf->Text(194, $y+5*$i+4, $alumnos[$i]->valor_nota);
            }
            foreach ($capacidades as $key => $value) {
                //$this->pdf->Rect($xx, $yy, $dimension, 10);
                $items = $value['items'];
                $di = $dimension/count($items);
                foreach ($items as $k => $v) {
                    if(isset($alumnos[$i])){
                        $notas = $alumnos[$i]->notas;
                        $this->pdf->Cell($di,0,isset($notas[$v->id]) ? $notas[$v->id] : '',0,0,'L');
                    }
                    //$this->pdf->Text($xx+$di/3, $yy, 'I'.($k+1));
                    if($k == count($items)-1){
                        $this->pdf->Rect($xx, $yy, $dimension - $di*(count($items)-1), 5);
                        $xx+=number_format($dimension - $di*(count($items)-1),0,'','');
                    }
                    else{
                        $this->pdf->Rect($xx, $yy, $di, 5);
                        $xx+=number_format($di,0,'','');
                    }
                }
                $x+=number_format($dimension,0,'','');
            }
            $this->pdf->Rect($xx, $yy, 7, 5);
            $yy += 5;
            $xx = 87;
            $this->pdf->Ln(5);
        }
        $this->pdf->Line(145, 280, 195, 280);
        $this->pdf->Text(160, 283, 'Firma del docente');
        $this->pdf->Close();
        $this->pdf->Output(base_url()."tmp/".$seccion->nombre.".pdf", 'I');
    }
    
    
    public function impresion_comprobante($data){
        $CI =& get_instance();
        //--------------
        $CI->load->model('seccion'); 
        //$this->pdf = new Pdf();
        $this->pdf->AddPage('P','A4-2');
        $this->pdf->SetMargins(10,10,20);
        $this->pdf->AliasNbPages();
        $pageWidth = 205;
        $pageHeight = 145;
        $margin = 5;
        $this->pdf->Rect( $margin, $margin , $pageWidth - $margin , $pageHeight-$margin);
        $this->pdf->SetTitle('pago');
        $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],8,8,16);//logo del documento
        $this->pdf->SetFont('Arial','',10);
        $id_pago = str_repeat('0', 11-strlen($data['id_pago'])).$data['id_pago'];
        $title = iconv('UTF-8', 'windows-1252', 'CÓDIGO DE COMPROBANTE  '.$id_pago);
        $this->pdf->Cell(0,0,$title,0,0,'R');
        //nombre del insituto
        $title = iconv('UTF-8', 'windows-1252', $this->parameters['nombre_instituto']);
        //$this->pdf->Cell(1);
        $this->pdf->Ln(8);
        $this->pdf->Cell(12,0,' ',0,0,'L');
        $this->pdf->Cell(0,0,$title,0,0,'L');
        $this->pdf->Ln(1);
        $meses = ['01'=>'enero','02'=>'febrero','03'=>'marzo','04'=>'abril','05'=>'mayo','06'=>'junio','07'=>'julio','08'=>'agosto','09'=>'septiembre',
            '10'=>'octubre','11'=>'noviembre','12'=>'diciembre'];
        $this->pdf->SetFont('Arial','B',8);
        $this->pdf->Cell(140,0,'Morropon, ',0,0,'R');
        $this->pdf->SetFont('Arial','',8);
        $this->pdf->Cell(30,0,date('d').' de '.$meses[date('m')].' de '.date('Y'),0,0,'R');
        $this->pdf->Ln(8);
        $this->pdf->SetFont('Arial','B',12);
        $this->pdf->Cell(0,0,'COMPROBANTE DE PAGO',0,0,'C');
        //datos del alumno
        $this->pdf->Ln(10);
        $this->pdf->SetFont('Courier','B',12);
        //$data_alumno = $data['data'];
        /*$per = explode('-', '2019-01');
        if($per[1] == '01')
            $periodo = 'I';
        else
            $periodo = 'II';*/
        $title = iconv('UTF-8', 'windows-1252', 'PERIODO ACADÉMICO '.$data['periodo']->nombre);
        $this->pdf->Cell(0,0,$title,0,0,'C');
        //Nombres y apellidos
        $this->pdf->Ln(15);
        $this->pdf->SetFont('Helvetica','',10);
        $title = iconv('UTF-8', 'windows-1252', 'ALUMNO Y CODIGO');
        $this->pdf->Cell(50,0,$title,0,0,'L');
        $this->pdf->SetFont('Helvetica','B',10);
        $credenciales = $data['credenciales'];
        $title = iconv('UTF-8', 'windows-1252', $credenciales->apell_pat.' '.$credenciales->apell_mat.', '.$credenciales->nombre);
        $this->pdf->Cell(120,0,$title,0,0,'L');
        //Carrera profesional
        /*$this->pdf->Ln(8);
        $this->pdf->SetFont('Helvetica','',10);
        $title = iconv('UTF-8', 'windows-1252', 'ESPECIALIDAD Y SEMESTRE');
        $this->pdf->Cell(50,0,$title,0,0,'L');
        $this->pdf->SetFont('Helvetica','B',10);
        $this->pdf->Cell(120,0,$title,0,0,'L');*/
        //Concepto
        $tupa = $data['tupa'];
        $this->pdf->Ln(8);
        $this->pdf->SetFont('Helvetica','',10);
        $title = iconv('UTF-8', 'windows-1252', 'CONCEPTO');
        $this->pdf->Cell(50,0,$title,0,0,'L');
        $this->pdf->SetFont('Helvetica','B',10);
        $title = iconv('UTF-8', 'windows-1252', $tupa->nombre);
        $this->pdf->Cell(120,0,$title,0,0,'L');
        $pago = $data['pago'];
        $this->pdf->Ln(8);
        $this->pdf->SetFont('Helvetica','',10);
        $title = iconv('UTF-8', 'windows-1252', 'MONTO');
        $this->pdf->Cell(50,0,$title,0,0,'L');
        $this->pdf->SetFont('Helvetica','B',10);
        $title = iconv('UTF-8', 'windows-1252','S/'. $pago->monto);
        $this->pdf->Cell(120,0,$title,0,0,'L');
        $this->pdf->Ln(8);
        $this->pdf->SetFont('Helvetica','',10);
        $title = iconv('UTF-8', 'windows-1252', 'FECHA Y HORA');
        $this->pdf->Cell(50,0,$title,0,0,'L');
        $this->pdf->SetFont('Helvetica','B',10);
        $title = iconv('UTF-8', 'windows-1252',  substr($pago->fch_pago,0,16));
        $this->pdf->Cell(50,0,$title,0,0,'L');
        $this->pdf->Ln(8);
        $this->pdf->SetFont('Helvetica','',10);
        $title = iconv('UTF-8', 'windows-1252', 'OBSERVACION');
        $this->pdf->Cell(50,0,$title,0,0,'L');
        $widtLeter = 80;
        $cant = strlen($pago->observacion)/$widtLeter;
        for($i = 0; $i < $cant; $i++){
            $this->pdf->SetFont('Helvetica','B',10);
//                $title = iconv('UTF-8', 'windows-1252',  substr($pago->observacion,$widtLeter*$i,$widtLeter*($i+1)));
            if($i == 0)
                $title = iconv('UTF-8', 'windows-1252',  substr($pago->observacion,0,$widtLeter));
            if($i != 0 && $i != $cant-1){
                $this->pdf->Cell(50,0,'',0,0,'L');
                $title = iconv('UTF-8', 'windows-1252',  substr($pago->observacion,$widtLeter*$i,$widtLeter*($i+1)));
            }
            if($i == $cant-1){
                $this->pdf->Cell(50,0,'',0,0,'L');
                $title = iconv('UTF-8', 'windows-1252',  substr($pago->observacion,$widtLeter*$i));
            }
            $this->pdf->Cell(120,0,$title,0,0,'L');
            $this->pdf->Ln(5);
        }
        //$this->pdf->Ln(35);
        $this->pdf->SetFont('Helvetica','',10);
        $title = iconv('UTF-8', 'windows-1252', '________________________________________________');
        $this->pdf->Text(55,125,$title);
        //$this->pdf->Cell(0,0,$title,0,0,'C');
        //$this->pdf->Ln(5);
        $this->pdf->SetFont('Helvetica','B',10);
        $title = iconv('UTF-8', 'windows-1252', 'SELLO DE CAJA');
        $this->pdf->Text(85,130,$title);
        //$this->pdf->Cell(0,0,$title,0,0,'C');
        $this->pdf->Close();
        $this->pdf->Output(base_url()."tmp/pago.pdf", 'I');
    }

    public function lista_alumnos($data){
        $this->pdf->AddPage('P','A4-3');
        $this->pdf->SetMargins(10,10,20);
//            $this->pdf->SetTitle($seccion->nombre);
        $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],11,11,45);//logo del documento
        $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],160,11,25,15);
        $this->pdf->Ln(3);
        $this->pdf->SetFont('Helvetica','',8);
        $title = iconv('UTF-8', 'windows-1252', $this->parameters['nombre_instituto_publico']);
        $this->pdf->Cell(0,6,$title,0,0,'C');
        $this->pdf->Ln(7);
        $this->pdf->SetFont('Helvetica','B',12);
        $title = iconv('UTF-8', 'windows-1252', 'LISTA DE ALUMNOS');
        $this->pdf->Cell(0,0,$title,0,0,'C');
        $this->pdf->Ln(7);
        $profesor = $data['profesor'];
        $this->pdf->SetFont('Helvetica','',8);
        $this->pdf->Cell(20,0,'Profesor',0,0,'L');
        $this->pdf->SetFont('Helvetica','B',8);
        $title = iconv('UTF-8', 'windows-1252', ': '.$profesor->apell_pat.' '.$profesor->apell_mat.' '.$profesor->nombre);
        $this->pdf->Cell(0,0,$title,0,0,'L');
        $curso = $data['curso'];
        $this->pdf->Ln(5);
        $this->pdf->SetFont('Helvetica','',8);
        $this->pdf->Cell(20,0,'Curso',0,0,'L');
        $this->pdf->SetFont('Helvetica','B',8);
        $title = iconv('UTF-8', 'windows-1252', ': '.$curso->nombre);
        $this->pdf->Cell(0,0,$title,0,0,'L');
        $this->pdf->Ln(5);
        $this->pdf->SetFont('Helvetica','',8);
        $this->pdf->Cell(20,0,'Cod. Curso',0,0,'L');
        $this->pdf->SetFont('Helvetica','B',8);
        $title = iconv('UTF-8', 'windows-1252', ': '.$curso->codigo);
        $this->pdf->Cell(40,0,$title,0,0,'L');
        //$this->pdf->Ln(5);
        $this->pdf->SetFont('Helvetica','',8);
        $this->pdf->Cell(20,0,'Creditos',0,0,'L');
        $this->pdf->SetFont('Helvetica','B',8);
        $title = iconv('UTF-8', 'windows-1252', ': '.$curso->creditos);
        $this->pdf->Cell(40,0,$title,0,0,'L');
        $this->pdf->Ln(5);
        $this->pdf->SetFont('Helvetica','',8);
        $this->pdf->Cell(20,0,'Periodo',0,0,'L');
        $this->pdf->SetFont('Helvetica','B',8);
        $title = iconv('UTF-8', 'windows-1252', ': '.$data['periodo']->nombre);
        $this->pdf->Cell(40,0,$title,0,0,'L');
        //$this->pdf->Ln(5);
        $this->pdf->SetFont('Helvetica','',8);
        $this->pdf->Cell(20,0,'Ciclo',0,0,'L');
        $this->pdf->SetFont('Helvetica','B',8);
        $title = iconv('UTF-8', 'windows-1252', ': '.$data['ciclo']->ciclo);
        $this->pdf->Cell(40,0,$title,0,0,'L');
        //$this->pdf->Ln(5);
        $this->pdf->SetFont('Helvetica','',8);
        $this->pdf->Cell(20,0,'Turno',0,0,'L');
        $this->pdf->SetFont('Helvetica','B',8);
        $title = iconv('UTF-8', 'windows-1252', ': '.$data['turno']->nombre);
        $this->pdf->Cell(40,0,$title,0,0,'L');
        $alumnos = $data['alumnos'];
        $this->pdf->Ln(7);
        $this->pdf->SetFont('Helvetica','',7);
        $title = iconv('UTF-8', 'windows-1252', 'N°');
        $this->pdf->Cell(7,5,$title,1,0,'C');
        $this->pdf->Cell(18,5,'DNI',1,0,'C');
        $this->pdf->Cell(95,5,'APELLIDOS Y NOMBRES',1,0,'C');
        $this->pdf->Ln(5);
        foreach ($alumnos as $key => $value) {
            $this->pdf->Cell(7,5,($key+1),1,0,'L');
            $title = iconv('UTF-8', 'windows-1252', $value->dni);
            $this->pdf->Cell(18,5,$title,1,0,'L');
            $title = iconv('UTF-8', 'windows-1252', $value->apell_pat.' '.$value->apell_mat.' '.$value->nombre);
            $this->pdf->Cell(95,5,$title,1,0,'L');
            $this->pdf->Ln(5);
        }
        $this->pdf->Close();
        $this->pdf->Output(base_url()."tmp/lista_alumnos.pdf", 'I');
    }
}