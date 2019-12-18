<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH."/third_party/fpdf.php";

//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class Constancias {

    public $pdf = null;
    public $parameters = null;
    public $meses = null;

    public function __construct($parameters){
            $this->pdf = new FPDF();
            $this->parameters = $parameters;

            $this->meses = ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'];
    }

    public function cargaCertificado($data){
    	$notas = [
                '0'     => 'cero',
                '1'     => 'uno',
                '1'    => 'uno',
                '2'    => 'dos',
                '3'    => 'tres',
                '4'    => 'cuatro',
                '5'    => 'cinco',
                '6'    => 'seis',
                '7'    => 'siete',
                '8'    => 'ocho',
                '9'    => 'nueve',
                '01'    => 'uno',
                '02'    => 'dos',
                '03'    => 'tres',
                '04'    => 'cuatro',
                '05'    => 'cinco',
                '06'    => 'seis',
                '07'    => 'siete',
                '08'    => 'ocho',
                '09'    => 'nueve',
                '10'    => 'diez',
                '11'    => 'once',
                '12'    => 'doce',
                '13'    => 'trece',
                '14'    => 'catorce',
                '15'    => 'quince',
                '16'    => 'dieciseis',
                '17'    => 'diecisiete',
                '18'    => 'dieciocho',
                '19'    => 'diecinueve',
                '20'    => 'veinte'
            ];
    	$this->pdf->AddPage('P','A4-3');
	    $this->pdf->SetMargins(10,10,20);
	    $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],75,11,65);//logo del documento
	    $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],11,11,25,15);
	    $this->pdf->SetFont('Helvetica','B',10);
	    $this->pdf->Rect(177,15,20,30);
	    $this->pdf->Ln(18);
	    $title = iconv('UTF-8', 'windows-1252', 'CERTIFICADO DE ESTUDIOS DE EDUCACIÓN SUPERIOR TECNOLÓGICA');
	    $this->pdf->Cell(0,0,$title,0,0,'C');
	    $this->pdf->SetFont('Helvetica','',8);
	    $this->pdf->Ln(7);
	    $title = iconv('UTF-8', 'windows-1252', 'El Instituto de Educación Superior Tecnológico Público "Morropón" - Morropón');
	    $this->pdf->Cell(0,0,$title,0,0,'L');
	    $this->pdf->Ln(4);
	    $title = iconv('UTF-8', 'windows-1252', 'El que suscribe');
	    $this->pdf->Cell(0,0,$title,0,0,'L');
	    $this->pdf->SetFont('Helvetica','B',14);
	    $this->pdf->Ln(7);
	    $title = iconv('UTF-8', 'windows-1252', 'CERTIFICA');
	    $this->pdf->Cell(0,0,$title,0,0,'C');
	    $this->pdf->SetFont('Helvetica','',11);
	    $this->pdf->Ln(7);
	    $title = iconv('UTF-8', 'windows-1252', 'Que,');
	    $this->pdf->Cell(1,0,$title,0,0,'L');
	    $alumno = $data['alumno'];
	    $this->pdf->SetFont('Helvetica','',14);
	    $title = iconv('UTF-8', 'windows-1252', $alumno->apell_pat.' '.$alumno->apell_mat.' '.$alumno->nombre);
	    $this->pdf->Cell(0,0,$title,0,0,'C');
	    $this->pdf->SetFont('Helvetica','',8);
	    $this->pdf->Ln(7);
	    $title = iconv('UTF-8', 'windows-1252', 'Ha cursado las Unidades Didácticas/Asignaturas o Cursos que se indican, en la Carrera de:');
	    $this->pdf->Cell(0,0,$title,0,0,'L');
	    $this->pdf->SetFont('Helvetica','B',14);
	    $this->pdf->Ln(7);
	    /*var_dump($data['informacion']->especialidad);
	    exit();*/
	    if(strlen($data['informacion']->especialidad) >= 40)
	    	$this->pdf->SetFont('Helvetica','B',9);
	    $title = iconv('UTF-8', 'windows-1252', $data['informacion']->especialidad);
	    $this->pdf->Cell(160,0,$title,0,0,'C');
	    $this->pdf->SetFont('Helvetica','',12);
	    $title = iconv('UTF-8', 'windows-1252', 'TURNO : '.$data['informacion']->turno);
	    $this->pdf->Cell(20,0,$title,0,0,'C');
	    $this->pdf->SetFont('Helvetica','B',10);
	    $this->pdf->Ln(7);
	    $title = iconv('UTF-8', 'windows-1252', 'Siendo el resultado final de las evaluaciones lo siguiente:');
	    $this->pdf->Cell(0,0,$title,0,0,'L');
	    $this->pdf->Ln(4);
	    $title = iconv('UTF-8', 'windows-1252', 'Unidades Didácticas/ cursos o asignaturas');
	    $this->pdf->Cell($data['modular'] ? 117 : 125,8,$title,1,0,'C');
	    $this->pdf->SetFont('Helvetica','B',7);
	    $title = iconv('UTF-8', 'windows-1252', '');
	    if($data['modular'])
	    	$this->pdf->Cell(8,8,$title,1,0,'C');
	    $title = iconv('UTF-8', 'windows-1252', 'Nota');
	    $this->pdf->Cell(26,4,$title,1,0,'C');
	    $title = iconv('UTF-8', 'windows-1252', 'Periodo');
	    $this->pdf->Cell(23,4,$title,1,0,'C');
	    $title = iconv('UTF-8', 'windows-1252', 'OBSERV.');
	    $this->pdf->Cell(15,8,$title,1,0,'C');
	    if($data['modular']){
		    $title = iconv('UTF-8', 'windows-1252', 'N°');
		    $this->pdf->Text(130,81,$title);
		    $title = iconv('UTF-8', 'windows-1252', 'Créd.');
		    $this->pdf->Text(128,84,$title);
		}
	    $title = iconv('UTF-8', 'windows-1252', 'Núm.');
	    $this->pdf->Text(136,85,$title);
	    $title = iconv('UTF-8', 'windows-1252', 'Letras');
	    $this->pdf->Text(148,85,$title);
	    $title = iconv('UTF-8', 'windows-1252', 'Lectivo');
	    $this->pdf->Text(161,85,$title);
	    $title = iconv('UTF-8', 'windows-1252', 'Acádemico');
	    $this->pdf->Text(171,85,$title);
	    $this->pdf->Rect(135,82,8,4);
	    $this->pdf->Rect(143,82,18,4);
	    $this->pdf->Rect(161,82,10,4);
	    $this->pdf->Rect(171,82,13,4);
	    $orden = ['','PRIMER','SEGUNDO','TERCER','CUARTO','QUINTO','SEXTO'];
	    $ciclo = ['','I','II','III','IV','V','VI'];
	    $this->pdf->Ln(8);
	    $x1 = 10;
	    $y1 = 91;
	    $x2 = 25;
	    $y2 = 0;
	    $modulares = $data['modulares'];
	    $malla = $data['malla'];
	    for ($i=1; $i <= 6; $i++) { 
		    $y1 = $y1 + $y2 + ($i == 1 ? 0 : 5);
		    $cus = 0;
		    $y11 = $y1;
		    if($data['modular']){
		    	if(isset($modulares[$i]))
			    foreach ($modulares[$i] as $ky => $val) {
			    	$this->pdf->SetFont('Helvetica','',5);
			    	$c = array_values($val)[0];
				    $title = iconv('UTF-8', 'windows-1252', $c->modulo);
				    $cade = $this->partir_string($c->modulo,25);
				    if(count($cade) == 1)
				    	$this->pdf->Text($x1+1,$y11+4,$c->modulo);
				    else{
					    foreach ($cade as $k => $v) {
					    	$this->pdf->Text($x1+0.5+($k != 0 ? 0.5 : 0),$y11+2*$k+3,$v);
					    }
					}
			    	$this->pdf->Rect($x1,$y11,$x2,count($val)*6);
			    	$y11 +=  count($val) * 6;
			    	$cus += count($val);
			    }
			    $y2 = $cus*6;
			    $this->pdf->Rect($x1,$y1,$x2,$y2);
			}
		    if($i == 3){
		    	$y1 = 10;
		    	$y2 = 0;
		    }
		    $this->pdf->SetFont('Helvetica','B',7);
		    $ano = '';
		    if(isset($malla[$i]))
		    foreach ($malla[$i] as $key => $value) {
		    	if(isset($value->nota))
		    		$ano = $value->nota->periodo;
		    }
		    $ano = explode('-', $ano);
		    $title = iconv('UTF-8', 'windows-1252', $orden[$i].' SEMESTRE - ' . $ano[0]);
		    $this->pdf->Cell(174,5,$title,1,0,'L');
		    $this->pdf->Cell(15,5,'',1,0,'L');
		    if(isset($data['malla'][$i]))
		    	$cursos = $data['malla'][$i];
		    else
		    	$cursos = [];
		    $this->pdf->Ln(5);
		    if(isset($malla[$i]))
		    	$curs = $malla[$i];
		    foreach ($cursos as $key => $value) {
		    	if($data['modular'])
		    	$this->pdf->Cell(25,6,' ',0,0,'L');
		    	$this->pdf->SetFont('Helvetica','',6);
		    	$title = iconv('UTF-8', 'windows-1252', $value->curso);
		    	$this->pdf->Cell($data['modular'] ? 92 : 125,6,$title,1,0,'L');
			    $title = iconv('UTF-8', 'windows-1252', $value->creditos);
			    if($data['modular'])
			    	$this->pdf->Cell(8,6,$title,1,0,'C');
			    if(isset($curs[$key]) && isset($curs[$key]->nota)){
			    	$nota = $curs[$key]->nota;
			    }
			    else{
			    	$nota = new stdClass();
			    	$nota->valor_nota = '-';
			    	$nota->periodo = '-';
			    }
			    $title = iconv('UTF-8', 'windows-1252', $nota->valor_nota);
			    $this->pdf->Cell(8,6,$title,1,0,'C');
			    $title = iconv('UTF-8', 'windows-1252', is_numeric($nota->valor_nota) ? strtoupper($notas[$nota->valor_nota]) : '-');
			    $this->pdf->Cell(18,6,$title,1,0,'C');
			    $p = explode('-', $value->periodo);
			    $title = iconv('UTF-8', 'windows-1252', $nota->periodo);
			    $this->pdf->Cell(10,6,$title,1,0,'C');
			    $fecha = '-';
			    if(isset($nota->fecha))
			    	$fecha = date('d.m.Y',strtotime(date($nota->fecha)));
			    if(isset($nota->fecha_acta))
			    	$fecha = date('d.m.Y',strtotime(date($nota->fecha_acta)));
			    $title = iconv('UTF-8', 'windows-1252', $fecha);
			    $this->pdf->Cell(13,6,$title,1,0,'C');
			    $abrev = '';
			    if(isset($nota->abrev))
			    	$abrev = $nota->abrev;
			    $title = iconv('UTF-8', 'windows-1252', $abrev);
			    $this->pdf->Cell(15,6,$title,1,0,'C');
		    	$this->pdf->Ln(6);
		    }
		    if($i == 3){
		    	$this->pdf->AddPage('P','A4-3');
			    $this->pdf->SetMargins(10,10,20);
			}
	    }
	    $this->pdf->SetFont('Helvetica','',10);
	    $this->pdf->Ln(6);
		$this->pdf->Cell(0,10,'Morropon, '.date('d').' de '.$this->meses[date('m')].' de '.date('Y'),0,0,'R');
		$this->pdf->SetFont('Helvetica','',8);
		$this->pdf->Text(30,280,'Director General');
		$this->pdf->Text(24,284,'Sello, Firma y Post Firma');
		$title = iconv('UTF-8', 'windows-1252', 'Secretario Académico');
		$this->pdf->Text(150,280,$title);
		$this->pdf->Text(148,284,'Sello, Firma y Post Firma');
        $this->pdf->Close();
        $this->pdf->Output(base_url()."tmp/".$data['cod_alumno'].".pdf", 'I');
    }

    public function partir_string($cadena,$length){
        $cads = explode(' ',$cadena);
        $cadenas = [];
        $string = '';
        foreach ($cads as $value) {
            if(strlen($string.' '.$value) >= $length){
                array_push($cadenas, $string);
                $string = $value;
            }else{
                $string = $string.' '.$value;
            }
        }
        if($string != '')
            array_push($cadenas,iconv('UTF-8', 'windows-1252', $string));
        return $cadenas;
    }

    public function imprimir_lista_egresados($consulta){
    	$this->pdf->AddPage('P','A4-3');
	    $this->pdf->SetMargins(10,10,20);
	    $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],130,11,65);//logo del documento
	    $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],11,11,25,15);
	    $this->pdf->SetFont('Helvetica','B',10);
	    $this->pdf->Ln(18);
	    $title = iconv('UTF-8', 'windows-1252', 'Lista de egresados desde '.date('d-m-Y',strtotime(date($consulta['desde']))).' hasta '.date('d-m-Y',strtotime(date($consulta['hasta']))));
	    $this->pdf->Cell(0,0,$title,0,0,'C');
	    $this->pdf->SetFont('Helvetica','',6);
	    $this->pdf->Ln(7);
	    $lista = $consulta['lista'];
	    foreach ($lista as $key => $value) {
	    	$this->pdf->Cell(5,7,($key+1),1,0,'L');
	    	$title = iconv('UTF-8', 'windows-1252', $value->cod_alumno);
	    	$this->pdf->Cell(15,7,$title,1,0,'L');
	    	$title = iconv('UTF-8', 'windows-1252', $value->apell_pat.' '.$value->apell_mat.' '.$value->nombre);
	    	$this->pdf->Cell(75,7,$title,1,0,'L');
	    	$title = iconv('UTF-8', 'windows-1252', $value->especialidad);
	    	$this->pdf->Cell(55,7,$title,1,0,'L');
	    	$title = iconv('UTF-8', 'windows-1252', $value->periodo);
	    	$this->pdf->Cell(10,7,$title,1,0,'L');
	    	$title = iconv('UTF-8', 'windows-1252', $value->turno);
	    	$this->pdf->Cell(15,7,$title,1,0,'L');
	    	$title = iconv('UTF-8', 'windows-1252', $value->fch_egreso);
	    	$this->pdf->Cell(15,7,$title,1,0,'L');
	    	$this->pdf->Ln(7);
	    }
	    $this->pdf->Ln(7);
        $this->pdf->Close();
        $this->pdf->Output(base_url()."tmp/".date('Y-m-d').".pdf", 'I');
    }

}