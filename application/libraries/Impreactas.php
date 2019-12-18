<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH."/third_party/fpdf.php";

//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class impreactas {

    public $pdf = null;
    public $parameters = null;
    public $meses = null;

    public function __construct($parameters){
            $this->pdf = new FPDF();
            $this->parameters = $parameters;

            $this->meses = ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'];
    }

    public function imprimeRegistroMatricula($data){
    	/*echo '<pre>';
    	print_r($data['alumnos']);
    	echo '</pre>';
    	exit();*/
    	$ciclo_romano = ['01'=>'I','02'=>'II','03'=>'III','04'=>'IV','05'=>'V','06'=>'VI'];
    	$this->pdf->AddPage('P','A5-H');
        $this->pdf->SetMargins(5,0,5);
        $this->pdf->SetTitle('Registro de matriculas');
        $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],5,10,26);//logo del documento
        //$this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],160,11,25,15);
        $ciclo = ['','I','II','III','IV','V','VI'];
        $this->pdf->SetFont('Arial','B',5);
       	$this->pdf->Ln(1);
        $title = iconv('UTF-8', 'windows-1252', 'REGISTRO DE MATRÍCULA');
        $this->pdf->Cell(0,0,$title,0,0,'C');
        //$this->pdf->SetFont('Helvetica','B',14);
        $this->pdf->Ln(2);
        $title = iconv('UTF-8', 'windows-1252', 'EDUCACIÓN SUPERIOR TECNOLÓGICA');
        $this->pdf->Cell(0,0,$title,0,0,'C');
        $this->pdf->Ln(2);
        $per = explode('-', $data['periodo']->nombre);
        $title = iconv('UTF-8', 'windows-1252', 'PERIODO LECTIVO: '.$per[0].'-'.$ciclo_romano[$per[1]]);
        $this->pdf->Cell(0,0,$title,0,0,'C');
        $this->pdf->Ln(4);
        $title = iconv('UTF-8', 'windows-1252', 'Datos del Instituto de Educación Superior Tecnológico');
        $this->pdf->SetFillColor(230,230,230);
        $this->pdf->Cell(0,4,$title,1,0,'C',true);
        $this->insertar(20,4,'DRE-GRE','L',1,4);
        $this->insertar(50,0,'','L',0,4);
        $this->insertar(20,0,'Nombre del IEST','L',1,4);
        $this->insertar(50,0,$this->parameters['nombre_instituto'],'L',0,4);
        $this->insertar(20,0,'Codigo Modular','L',1,4);
        $this->insertar(40,0,$this->parameters['codigo_institucion'],'L',0,4);
        $this->insertar(20,4,'Departamento','L',1,4);
        $this->insertar(50,0,$this->parameters['departamento'],'L',0,4);
        $this->insertar(20,0,'Carrera','L',1,4);
        $this->insertar(110,0,$data['especialidad']->nombre,'L',0,4);
        $this->insertar(20,4,'Provincia','L',1,4);
        $this->insertar(50,0,$this->parameters['provincia'],'L',0,4);
        $this->insertar(20,0,'Nivel Formativa','L',1,4);
        $this->insertar(50,0,'SUPERIOR','L',0,4);
        $this->insertar(20,0,'Tipo Gestion','L',1,4);
        $this->insertar(40,0,strtoupper($this->parameters['tipo-gestion']),'L',0,4);
        $this->insertar(20,4,'Distrito','L',1,4);
        $this->insertar(50,0,$this->parameters['distrito'],'L',0,4);
        $this->insertar(20,0,'Periodo Académico','L',1,4);
        $this->insertar(50,0,$data['periodo']->nombre,'L',0,4);
        $this->insertar(20,0,'Turno','L',1,4);
        $this->insertar(40,0,$data['turno']->nombre,'L',0,4);
        $this->insertar(20,4,'Dirección','L',1,4);
        $this->insertar(180,0,$this->parameters['direccion-instituto'],'L',0,4);
        $this->pdf->SetXY(5,46);
        $this->pdf->Cell(200,26,' ',0,0,'C',true);
        $this->pdf->SetXY(5,38);

        //armando caja
        $this->insertar(4,8,'N°','C',1,26);
        $this->insertar(20,0,'Documento Identidad','C',1,26);
        $this->insertar(50,0,'APELLIDOS Y NOMBRES','C',1,26);
        $this->insertar(5,0,'SEXO','C',1,26);
        $this->insertar(6,0,'EDAD','C',1,26);
        $this->insertar(15,0,'PERSONA CON','C',1,23);
        $this->insertar(72,0,'UNIDADES DIDACTICAS','C',1,3);
        $this->insertar(28,0,'OBSERVACIONES','C',1,26);
        $this->pdf->Rect(105, 49, 6, 20);
        $this->pdf->Rect(111, 49, 6, 20);
        $this->pdf->Rect(117, 49, 6, 20);
        $this->pdf->Rect(123, 49, 6, 20);
        $this->pdf->Rect(129, 49, 6, 20);
        $this->pdf->Rect(135, 49, 6, 20);
        $this->pdf->Rect(141, 49, 6, 20);
        $this->pdf->Rect(147, 49, 6, 20);
        $this->pdf->Rect(153, 49, 6, 20);
        $this->pdf->Rect(159, 49, 6, 20);
        $this->pdf->Rect(165, 49, 6, 20);
        $this->pdf->Rect(171, 49, 6, 20);
        $this->pdf->Rect(90, 69, 15, 3);
        $this->pdf->Rect(105, 69, 6, 3);
        $this->pdf->Rect(111, 69, 6, 3);
        $this->pdf->Rect(117, 69, 6, 3);
        $this->pdf->Rect(123, 69, 6, 3);
        $this->pdf->Rect(129, 69, 6, 3);
        $this->pdf->Rect(135, 69, 6, 3);
        $this->pdf->Rect(141, 69, 6, 3);
        $this->pdf->Rect(147, 69, 6, 3);
        $this->pdf->Rect(153, 69, 6, 3);
        $this->pdf->Rect(159, 69, 6, 3);
        $this->pdf->Rect(165, 69, 6, 3);
        $this->pdf->Rect(171, 69, 6, 3);
        $this->pdf->Text(91, 60, 'DISCAPACIDAD');
        $this->pdf->Text(95, 71, 'SI/NO');
        $this->pdf->Text(107, 71, '1');
        $this->pdf->Text(113, 71, '2');
        $this->pdf->Text(119, 71, '3');
        $this->pdf->Text(125, 71, '4');
        $this->pdf->Text(131, 71, '5');
        $this->pdf->Text(137, 71, '6');
        $this->pdf->Text(143, 71, '7');
        $this->pdf->Text(149, 71, '8');
        $this->pdf->Text(155, 71, '9');
        $this->pdf->Text(161, 71, '10');
        $this->pdf->Text(167, 71, '11');
        $this->pdf->Text(173, 71, '12');
        $this->pdf->Ln(6);

        $cursos = $data['cursos'];
        $w = 107;
        $suma_creditos = 0;
        foreach ($cursos as $key => $value) {
            $nombre = iconv('UTF-8', 'windows-1252', $value->curso);
            $val = $this->partir_string($nombre,15);
            foreach ($val as $k => $v) if($k < 3) {
            	$this->pdf->TextWithDirection($w+$k*2,68,  substr($v, 0, 35),'U');
            }else{
            	//$this->pdf->TextWithDirection($w+2*2,68, '.','U');
            }
            $w += 6;
        }

        $this->pdf->SetXY(5,72);
        $alumnos = $data['alumnos'];
        for($i = 0; $i < 19;$i++) if(isset($alumnos[$i])){
        	$this->pdf->Cell(4,3,($i+1),1,0,'C');
        	$this->pdf->Cell(20,3,isset($alumnos[$i]) ? $alumnos[$i]->dni : '',1,0,'C');
        	$title = iconv('UTF-8', 'windows-1252', isset($alumnos[$i]) ? $alumnos[$i]->apell_pat.' '.$alumnos[$i]->apell_mat.' '.$alumnos[$i]->nombre : '');
        	$this->pdf->Cell(50,3,$title,1,0,'L');
        	$this->pdf->Cell(5,3,isset($alumnos[$i]) ? ($alumnos[$i]->codigo_genero == 'NP' ? '' : $alumnos[$i]->codigo_genero) : '',1,0,'L');
        	/*$datetime1 = date_create($alumnos[$i]->fch_nac == '' ? date('Y-m-d') : $alumnos[$i]->fch_nac);
		    $datetime2 = date_create(date('Y-m-d'));
		   
		    $interval = date_diff($datetime1, $datetime2);*/
		    //$interval->format('%y')
        	$this->pdf->Cell(6,3,'',1,0,'L');
        	$this->pdf->Cell(15,3,'',1,0,'L');
        	for($a = 0; $a < 12; $a++){
        		$this->pdf->Cell(6,3,isset($cursos[$a]) ? 'x' : '',1,0,'C');
        	}
        	$this->pdf->Cell(28,3,'',1,0,'L');
        	$this->pdf->Ln(3);
        }
        if(count($alumnos) >= 19){
	    	$this->pdf->AddPage('P','A5-H');
	        $this->pdf->SetXY(5,8);
	        $this->pdf->Cell(200,26,' ',0,0,'C',true);
	        $this->pdf->SetXY(5,0);

	        //armando caja
	        $this->insertar(4,8,'N°','C',1,26);
	        $this->insertar(20,0,'Documento Identidad','C',1,26);
	        $this->insertar(50,0,'APELLIDOS Y NOMBRES','C',1,26);
	        $this->insertar(5,0,'SEXO','C',1,26);
	        $this->insertar(6,0,'EDAD','C',1,26);
	        $this->insertar(15,0,'PERSONA CON','C',1,23);
	        $this->insertar(72,0,'UNIDADES DIDACTICAS','C',1,3);
	        $this->insertar(28,0,'OBSERVACIONES','C',1,26);
	        $this->pdf->Rect(105, 11, 6, 20);
	        $this->pdf->Rect(111, 11, 6, 20);
	        $this->pdf->Rect(117, 11, 6, 20);
	        $this->pdf->Rect(123, 11, 6, 20);
	        $this->pdf->Rect(129, 11, 6, 20);
	        $this->pdf->Rect(135, 11, 6, 20);
	        $this->pdf->Rect(141, 11, 6, 20);
	        $this->pdf->Rect(147, 11, 6, 20);
	        $this->pdf->Rect(153, 11, 6, 20);
	        $this->pdf->Rect(159, 11, 6, 20);
	        $this->pdf->Rect(165, 11, 6, 20);
	        $this->pdf->Rect(171, 11, 6, 20);
	        $this->pdf->Rect(90, 31, 15, 3);
	        $this->pdf->Rect(105, 31, 6, 3);
	        $this->pdf->Rect(111, 31, 6, 3);
	        $this->pdf->Rect(117, 31, 6, 3);
	        $this->pdf->Rect(123, 31, 6, 3);
	        $this->pdf->Rect(129, 31, 6, 3);
	        $this->pdf->Rect(135, 31, 6, 3);
	        $this->pdf->Rect(141, 31, 6, 3);
	        $this->pdf->Rect(147, 31, 6, 3);
	        $this->pdf->Rect(153, 31, 6, 3);
	        $this->pdf->Rect(159, 31, 6, 3);
	        $this->pdf->Rect(165, 31, 6, 3);
	        $this->pdf->Rect(171, 31, 6, 3);
	        $this->pdf->Text(91, 22, 'DISCAPACIDAD');
	        $this->pdf->Text(95, 33, 'SI/NO');
	        $this->pdf->Text(107, 33, '1');
	        $this->pdf->Text(113, 33, '2');
	        $this->pdf->Text(119, 33, '3');
	        $this->pdf->Text(125, 33, '4');
	        $this->pdf->Text(131, 33, '5');
	        $this->pdf->Text(137, 33, '6');
	        $this->pdf->Text(143, 33, '7');
	        $this->pdf->Text(149, 33, '8');
	        $this->pdf->Text(155, 33, '9');
	        $this->pdf->Text(161, 33, '10');
	        $this->pdf->Text(167, 33, '11');
	        $this->pdf->Text(173, 33, '12');
	        $w = 107;
	        $suma_creditos = 0;
	        foreach ($cursos as $key => $value) {
	            $nombre = iconv('UTF-8', 'windows-1252', $value->curso);
	            $val = $this->partir_string($nombre,20);
	            foreach ($val as $k => $v) if($k < 3) {
	            	$this->pdf->TextWithDirection($w+$k*2,30,  substr($v, 0, 35),'U');
	            }else{
	            	//$this->pdf->TextWithDirection($w+2*2,68, '.','U');
	            }
	            $w += 6;
	        }
	        $this->pdf->SetXY(5,34);
	        for($i = 19; $i < 50;$i++) if(isset($alumnos[$i])){
	        	$this->pdf->Cell(4,3,($i+1),1,0,'C');
	        	$this->pdf->Cell(20,3,isset($alumnos[$i]) ? $alumnos[$i]->dni : '', 1,0,'C');
	        	$title = iconv('UTF-8', 'windows-1252', isset($alumnos[$i]) ? $alumnos[$i]->apell_pat.' '.$alumnos[$i]->apell_mat.' '.$alumnos[$i]->nombre : '');
	        	$this->pdf->Cell(50,3,$title,1,0,'L');
	        	$this->pdf->Cell(5,3,isset($alumnos[$i]) ? ($alumnos[$i]->codigo_genero == 'NP' ? '' : $alumnos[$i]->codigo_genero) : '',1,0,'L');
	        	/*$datetime1 = date_create($alumnos[$i]->fch_nac == '' ? date('Y-m-d') : $alumnos[$i]->fch_nac);
			    $datetime2 = date_create(date('Y-m-d'));
			   
			    $interval = date_diff($datetime1, $datetime2);*/
			    //$interval->format('%y')
	        	$this->pdf->Cell(6,3,'',1,0,'L');
	        	$this->pdf->Cell(15,3,'',1,0,'L');
	        	for($a = 0; $a < 12; $a++){
	        		$this->pdf->Cell(6,3,isset($cursos[$a]) ? 'x' : '',1,0,'C');
	        	}
	        	$this->pdf->Cell(28,3,'',1,0,'L');
	        	$this->pdf->Ln(3);
	        }
        	$this->pdf->Ln(4);
	    }else{
	    	$this->pdf->Ln(2);
	    }
        $fecha = explode('-', date('Y-m-d'));
        $this->pdf->Cell(0,0,'Morropon, '.$fecha[0].' '.$this->meses[$fecha[1]].' '.$fecha[2],0,0,'R');
        $this->pdf->Ln(4);
        $this->pdf->Text(40,145,'Director General');
        $title = iconv('UTF-8', 'windows-1252', 'Secretario Académico');
        $this->pdf->Text(150,145,$title);
        //*$this->pdf->Cell(110,0,'Director General',0,0,'C');
        $this->pdf->Close();
        $this->pdf->Output(base_url()."tmp/Registro de matriculas.pdf", 'I');
    }

    public function insertar($long,$ln,$string,$pos,$sombra = 0,$ancho=0){
    	if($ln)
    		$this->pdf->Ln($ln);
    	$title = iconv('UTF-8', 'windows-1252', $string);
    	if($sombra)
    		$this->pdf->Cell($long,$ancho,$title,1,0,$pos,true);
    	else
    		$this->pdf->Cell($long,$ancho,$title,1,0,$pos);
    }

    public function imprimeBoletas($data){
    	$alumnos = $data['alumnos'];
    	foreach ($alumnos as $ky => $val) {
	    	$this->pdf->AddPage('P','A5-H');
	        $this->pdf->SetMargins(10,10,20);
	        $this->pdf->SetTitle($val->codigo);
            $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],8,12,48);//logo del documento
	        //$this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],160,11,25,15);
	        $ciclo = ['','I','II','III','IV','V','VI'];
	        $this->pdf->SetFont('Helvetica','B',9);
	        $title = iconv('UTF-8', 'windows-1252', 'INSTITUCION DE EDUCACION SUPERIOR TECNOLOGICO PUBLICO '.$this->parameters['name_institute'].' - '.$this->parameters['departamento']);
	        $this->pdf->Cell(0,0,$title,0,0,'C');
	        $this->pdf->Ln(6);
	        $this->pdf->SetFont('Helvetica','B',14);
	        $title = iconv('UTF-8', 'windows-1252', 'BOLETA DE NOTAS');
	        $this->pdf->Cell(0,0,$title,0,0,'C');
	        $this->pdf->SetFont('Helvetica','B',8);
	        $this->pdf->Ln(6);
	        $this->pdf->SetFillColor(230,230,230);
	        $title = iconv('UTF-8', 'windows-1252', 'Carrera Profesional');
	        $this->pdf->Cell(35,7,$title,1,0,'L',true);
	        $title = iconv('UTF-8', 'windows-1252', $data['especialidad']->nombre);
	        if(strlen($title) > 54){
	        	$cadenas = $this->partir_string($title,54);
	        	$title = ' ';
	        	foreach ($cadenas as $key => $value) {
	        		$this->pdf->Text(48,25+$key*4,$value);
	        	}
	        	//$cadena = iconv('UTF-8', 'windows-1252', $data['especialidad']->nombre);
	        }
	        $this->pdf->Cell(100,7,$title,1,0,'L');
	        $title = iconv('UTF-8', 'windows-1252', 'Periodo academico');
	        $this->pdf->Cell(28,7,$title,1,0,'L',true);
	        $periodo = explode('-', $data['periodo']->nombre);
	        $title = iconv('UTF-8', 'windows-1252', $ciclo[(int)$periodo[1]].'-'.$periodo[0]);
	        $this->pdf->Cell(27,7,$title,1,0,'C');

	        $this->pdf->Ln(7);
	        $title = iconv('UTF-8', 'windows-1252', 'Apellidos y Nombres');
	        $this->pdf->Cell(35,6,$title,1,0,'L',true);
	        $title = iconv('UTF-8', 'windows-1252', $val->apell_pat.' '.$val->apell_mat.' '.$val->nombre);
	        $this->pdf->Cell(100,6,$title,1,0,'C');
	        $title = iconv('UTF-8', 'windows-1252', 'Año de Ingreso');
	        $this->pdf->Cell(28,6,$title,1,0,'L',true);
	        $title = iconv('UTF-8', 'windows-1252', explode('-', $val->especialidad_periodo->periodo)[0]);
	        $this->pdf->Cell(27,6,$title,1,0,'C');
	        $this->pdf->Ln(6);
	        $title = iconv('UTF-8', 'windows-1252', 'N° DNI');
	        $this->pdf->Cell(35,6,$title,1,0,'L',true);
	        $title = iconv('UTF-8', 'windows-1252', $val->dni);
	        $this->pdf->Cell(30,6,$title,1,0,'C');
	        $title = iconv('UTF-8', 'windows-1252', 'Semestre de Estudios');
	        $this->pdf->Cell(35,6,$title,1,0,'L',true);
	        $title = iconv('UTF-8', 'windows-1252', $ciclo[$data['id_ciclo']]);
	        $this->pdf->Cell(35,6,$title,1,0,'C');
	        $title = iconv('UTF-8', 'windows-1252', 'Turno');
	        $this->pdf->Cell(28,6,$title,1,0,'L',true);
	        $periodo = explode('-', $data['periodo']->nombre);
	        $title = iconv('UTF-8', 'windows-1252', $val->turno->nombre);
	        $this->pdf->Cell(27,6,$title,1,0,'C');
	        $this->pdf->Ln(8);
	        $this->pdf->SetFont('Helvetica','B',6);
	        $title = iconv('UTF-8', 'windows-1252', 'N°');
	        $this->pdf->Cell(7,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'TIPO MOD');
	        $this->pdf->Cell(20,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'MODULOS');
	        $this->pdf->Cell(35,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'UNIDADES DIDACTICAS');
	        $this->pdf->Cell(73,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'CRÉD.');
	        $this->pdf->Cell(10,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'SEM.');
	        $this->pdf->Cell(8,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'NOTA');
	        $this->pdf->Cell(10,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'PUNTAJE');
	        $this->pdf->Cell(27,6,$title,1,0,'C',true);
	        $cursos = $val->cursos;
	        //para los modulos
	        $cant_trans = 0;
	        $cant_moduls = 0;
	        $cant_modul = [];
	        $cant_tran = [];
	        $modulo = '';
	        $cur = [];
	        foreach ($cursos as $key => $value) {
	        	$cur[$value->id] = $value;
	        	if($value->id_padre == 0){
	        		if(isset($cant_modul[$value->id_modulo]))
	        			$cant_modul[$value->id_modulo]['cant'] += 1;
	        		else
	        			$cant_modul[$value->id_modulo] = ['id'=>$value->id,'cant'=>1];
	        		$cant_moduls += 1;
	        		$modulo = $value->modulo;
	        	}
	        	else{
	        		if(isset($cant_tran[$value->id_modulo]))
	        			$cant_tran[$value->id_modulo]['cant'] += 1;
	        		else
	        			$cant_tran[$value->id_modulo] = ['id'=>$value->id,'cant'=>1];
	        		$cant_trans +=1;
	        	}
	        }
	        $this->pdf->Rect(17,49,20,5*$cant_trans);
	        $this->pdf->Rect(17,49+5*$cant_trans,20,5*$cant_moduls);
	        $this->pdf->Rect(37,49+5*$cant_trans,35,5*$cant_moduls);
	        $title_trans = '';
	        $title_mod = '';
	        if($cant_trans == 2)
	        	$title_trans = 'TRANS.';
	        if($cant_moduls == 2)
	        	$title_mod = 'PROFES.';
	        if($cant_trans == 3)
	        	$title_trans = 'TRANSVER.';
	        if($cant_moduls == 3)
	        	$title_mod = 'PROFESION.';
	        if($cant_trans > 3)
	        	$title_trans = 'TRANSVERSALES';
	        if($cant_moduls > 3)
	        	$title_mod = 'PROFESIONALES';
	        $this->pdf->TextWithDirection(28,47+5*$cant_trans, $title_trans,'U');
	        $this->pdf->TextWithDirection(28,47+5*$cant_trans+5*$cant_moduls, $title_mod,'U');
	        $row = 5;
	        $index = array_keys($cant_tran);
	        foreach ($cant_tran as $key => $value) {
	        	$this->pdf->Rect(37,44+$row,35,5*$value['cant']);
	        	$cad = $this->partir_string($cur[$value['id']]->modulo,25);
	        	$this->pdf->Text(38,48+$row,$cad[0].' '.($value['cant'] == 1 && isset($cad[1]) ? substr($cad[1], 0, 8).'.' : ''));
	        	if($value['cant'] > 1 && count($cad)>1 && $key != $index[count($index)-1])
	        		$this->pdf->Text(38,48+$row+5,$cad[1]);
	        	$row += 5*$value['cant'];
	        }
	        $index = array_keys($cant_modul);
	        foreach ($cant_modul as $key => $value) {
	        	$this->pdf->Rect(37,44+$row,35,5*$value['cant']);
	        	$cad = $this->partir_string($cur[$value['id']]->orden_modulo.'.- '.$cur[$value['id']]->modulo,25);
	        	$this->pdf->Text(38,count($cad) > 1 ? 47+$row : 47+$row,$cad[0]);
	        	if($key == $index[count($index)-1] && count($cad) > 1 && strlen($cad[0]) < 25){
		        	$cadenas = $this->partir_string(substr($cur[$value['id']]->orden_modulo.'.- '.$cur[$value['id']]->modulo,strlen($cad[0])),26);
		        	//$title = ' ';
		        	$i = 2;
		        	foreach ($cadenas as $k => $val) {
		        		$this->pdf->Text(41,48+$row+1+$k*$i,$val);
		        	}
		        	//$cadena = iconv('UTF-8', 'windows-1252', $data['especialidad']->nombre);
		        }else{
		        	if(count($cad)>1 && $key != $index[count($index)-1])
		        		$this->pdf->Text(40,48+$row+5,$cad[1]);
		        }
	        	$row += 5*$value['cant'];
	        }
	        //$this->pdf->TextWithDirection(48,58+6*$cant_trans+6*$cant_moduls, ' ','U');
	        //para los cursos
	        $puntajes = 0;
            $this->pdf->Ln(1);
	        foreach ($cursos as $key => $value) {
		        $this->pdf->Ln(5);
		        $this->pdf->SetFont('Helvetica','B',6);
		        $title = iconv('UTF-8', 'windows-1252', ($key+1));
		        $this->pdf->Cell(7,5,$title,1,0,'C');
		        $title = iconv('UTF-8', 'windows-1252', ' ');
		        $this->pdf->Cell(20,5,$title,0,0,'C');
		        $title = iconv('UTF-8', 'windows-1252', ' ');
		        $this->pdf->Cell(35,5,$title,0,0,'C');
		        $title = iconv('UTF-8', 'windows-1252', substr($value->curso,0,56));
		        $this->pdf->Cell(73,5,$title,1,0,'L');
		        $title = iconv('UTF-8', 'windows-1252', $value->creditos);
		        $this->pdf->Cell(10,5,$title,1,0,'C');
		        $title = iconv('UTF-8', 'windows-1252', $ciclo[$data['id_ciclo']]);
		        $this->pdf->Cell(8,5,$title,1,0,'C');
		        $title = iconv('UTF-8', 'windows-1252', $value->valor_nota);
		        $this->pdf->Cell(10,5,$title,1,0,'C');
		        $title = iconv('UTF-8', 'windows-1252', number_format($value->creditos*(int)$value->valor_nota,2,'.',''));
		        $puntajes += $value->creditos*(int)$value->valor_nota;
		        $this->pdf->Cell(27,5,$title,1,0,'C');
	        }
		    $this->pdf->Ln(5);
	        $this->pdf->Cell(163,6,' ',0,0,'C');
	        $this->pdf->Cell(27,6,number_format($puntajes,2,'.',''),1,0,'C');

            //
            $this->pdf->Ln(8);
            $this->pdf->Cell(80,10,'',0,0,'C');
            //$this->pdf->Cell(80,0,'LUGAR Y FECHA: ',0,0,'L');
            $this->pdf->SetFont('Arial','',8);
            $fecha = explode('-', $data['periodo']->fch_fin);
            $title = iconv('UTF-8', 'windows-1252', 'Morropón, '.$fecha[2].' de '.$this->meses[$fecha[1]].' de '.$fecha[0]);
            $this->pdf->Cell(80,0,$title,0,0,'L');
            //
	    }

        if ($ky == 1) {
            $this->pdf->Ln(8);
        $this->pdf->Cell(80,10,'',0,0,'C');
        //$this->pdf->Cell(80,0,'LUGAR Y FECHA: ',0,0,'L');
        $this->pdf->SetFont('Arial','',8);
        $fecha = explode('-', $data['periodo']->fch_fin);
        $title = iconv('UTF-8', 'windows-1252', 'Morropón, '.$fecha[2].' de '.$this->meses[$fecha[1]].' de '.$fecha[0]);
        $this->pdf->Cell(80,0,$title,0,0,'L');
        }
        


        $this->pdf->Close();
        $this->pdf->Output(base_url()."tmp/".$data['especialidad']->nombre.".pdf", 'I');
    }

    public function imprimirActaPracticas($data){
    	$notas = $data['notas'];
    	/*echo '<pre>';
    	print_r($data);
    	echo '</pre>';
    	exit();*/
    	foreach ($notas as $key => $value) {
	    	$this->pdf->AddPage('P','A4-3');
	        $this->pdf->SetMargins(10,10,20);
	//            $this->pdf->SetTitle($seccion->nombre);
	        $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],11,11,65);//logo del documento
	        $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],160,11,25,15);
	        $this->pdf->SetFont('Helvetica','B',12);
	        $this->pdf->Ln(22);
	        $title = iconv('UTF-8', 'windows-1252', 'EVALUACIÓN DE EXPERIENCIAS FORMATIVAS EN SITUACIONES REALES DE TRABAJO');
	        $this->pdf->Cell(0,0,$title,0,0,'C');
	        $this->pdf->Ln(7);
	        $this->pdf->SetFont('Helvetica','B',16);
	        $title = iconv('UTF-8', 'windows-1252', $this->parameters['nombre_instituto_publico']);
	        $this->pdf->Cell(0,8,$title,1,0,'C');
	        $this->pdf->Ln(8);
	        $this->pdf->SetFont('Helvetica','B',8);
	        $title = iconv('UTF-8', 'windows-1252', 'Número de código modular');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(40,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $this->pdf->Cell(50,6,$this->parameters['codigo_institucion'],1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'Tipo de Gestión');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(40,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $this->pdf->Cell(50,6,'PUBLICA',1,0,'C',true);
	        $this->pdf->Ln(6);
	        $title = iconv('UTF-8', 'windows-1252', 'Departamento');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(25,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $this->parameters['departamento']);
	        $this->pdf->Cell(25,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'Provincia');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(25,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $this->parameters['provincia']);
	        $this->pdf->Cell(25,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'Distrito');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(25,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $this->parameters['distrito']);
	        $this->pdf->Cell(55,6,$title,1,0,'C',true);
	        $this->pdf->Ln(6);
	        $title = iconv('UTF-8', 'windows-1252', 'Dirección del IEST');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(30,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $this->parameters['direccion-iest']);
	        $this->pdf->Cell(95,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'DRE-GRE');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(30,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $this->parameters['departamento']);
	        $this->pdf->Cell(25,6,$title,1,0,'C',true);
	        $this->pdf->Ln(6);
	        $title = iconv('UTF-8', 'windows-1252', 'Resolucion de Autorización');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(40,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $this->parameters['resolucion-autorizacion'].' de '.$this->parameters['ano-resol-autorizacion']);
	        $this->pdf->Cell(140,6,$title,1,0,'C',true);
	        $this->pdf->Ln(6);
	        $title = iconv('UTF-8', 'windows-1252', 'Resolucion de Revalidación');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(40,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $this->parameters['resolucion-revalidacion'].' de '.$this->parameters['ano-resol-revalidacion']);
	        $this->pdf->Cell(140,6,$title,1,0,'C',true);
	        $this->pdf->Ln(6);
	        $this->pdf->Ln(6);
	        $informacion = $data['informacion'];
	        $title = iconv('UTF-8', 'windows-1252', 'CARRERA PROFESIONAL');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(40,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $informacion->especialidad);
	        $this->pdf->Cell(100,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'TURNO');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(20,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $informacion->turno);
	        $this->pdf->Cell(20,6,$title,1,0,'C',true);
	        $this->pdf->Line(15, 280, 65, 280);
	        $this->pdf->Line(75, 280, 135, 280);
	        $this->pdf->Line(145, 280, 195, 280);
	        $this->pdf->SetFont('Arial','B',6);
	        $this->pdf->SetXY(15,282);
	        $this->pdf->Cell(50,0,'Firma del docente',0,0,'C');
	        $this->pdf->Cell(15,0,'',0,0,'C');
	        $this->pdf->Cell(50,0,'Firma del Jefe de Especialidad',0,0,'C');
	        $this->pdf->Cell(15,0,'',0,0,'C');
	        $this->pdf->Cell(50,0,'Firma del Jefe de Secretaria Academica',0,0,'C');
	    }
	        $this->pdf->Close();
	        $this->pdf->Output(base_url()."tmp/".$data['cod_alumno'].".pdf", 'I');
    }

    public function imprimirActaPracticasenMasa($data){
    	$alumnos = $data['alumnos'];
    	foreach ($alumnos as $key => $value) {
    	/*echo '<pre>';
    	print_r($value->profesor);
    	echo '</pre>';
    	exit();*/
			$this->pdf->AddPage('P','A4-3');
		    $this->pdf->SetMargins(10,10,20);
		//            $this->pdf->SetTitle($seccion->nombre);
		    $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],11,11,65);//logo del documento
		    $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],160,11,25,15);
		    $this->pdf->SetFont('Helvetica','B',12);
		    $this->pdf->Ln(22);
		    $title = iconv('UTF-8', 'windows-1252', 'EVALUACIÓN DE EXPERIENCIAS FORMATIVAS EN SITUACIONES REALES DE TRABAJO');
		    $this->pdf->Cell(0,0,$title,0,0,'C');
		    $this->pdf->Ln(7);
		    $this->pdf->SetFont('Helvetica','B',16);
		    $title = iconv('UTF-8', 'windows-1252', $this->parameters['nombre_instituto_publico']);
		    $this->pdf->Cell(0,8,$title,1,0,'C');
		    $this->pdf->Ln(8);
		    $this->pdf->SetFont('Helvetica','B',8);
		    $title = iconv('UTF-8', 'windows-1252', 'Número de código modular');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
		    $this->pdf->SetFillColor(255,255,255);
		    $this->pdf->Cell(50,6,$this->parameters['codigo_institucion'],1,0,'C',true);
		    $title = iconv('UTF-8', 'windows-1252', 'Tipo de Gestión');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
		    $this->pdf->SetFillColor(255,255,255);
		    $this->pdf->Cell(50,6,'PUBLICA',1,0,'C',true);
		    $this->pdf->Ln(6);
		    $title = iconv('UTF-8', 'windows-1252', 'Departamento');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
		    $this->pdf->SetFillColor(255,255,255);
		    $title = iconv('UTF-8', 'windows-1252', $this->parameters['departamento']);
		    $this->pdf->Cell(20,6,$title,1,0,'C',true);
		    $title = iconv('UTF-8', 'windows-1252', 'Provincia');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(20,6,$title,1,0,'L',true);
		    $this->pdf->SetFillColor(255,255,255);
		    $title = iconv('UTF-8', 'windows-1252', $this->parameters['provincia']);
		    $this->pdf->Cell(20,6,$title,1,0,'C',true);
		    $title = iconv('UTF-8', 'windows-1252', 'Distrito');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(30,6,$title,1,0,'L',true);
		    $this->pdf->SetFillColor(255,255,255);
		    $title = iconv('UTF-8', 'windows-1252', $this->parameters['distrito']);
		    $this->pdf->Cell(50,6,$title,1,0,'C',true);
		    $this->pdf->Ln(6);
		    $title = iconv('UTF-8', 'windows-1252', 'Dirección del IEST');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
		    $this->pdf->SetFillColor(255,255,255);
		    $title = iconv('UTF-8', 'windows-1252', $this->parameters['direccion-iest']);
		    $this->pdf->Cell(85,6,$title,1,0,'C',true);
		    $title = iconv('UTF-8', 'windows-1252', 'DRE-GRE');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(30,6,$title,1,0,'L',true);
		    $this->pdf->SetFillColor(255,255,255);
		    $title = iconv('UTF-8', 'windows-1252', $this->parameters['departamento']);
		    $this->pdf->Cell(25,6,$title,1,0,'C',true);
		    $this->pdf->Ln(6);
		    $title = iconv('UTF-8', 'windows-1252', 'Resolucion de Autorización');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
		    $this->pdf->SetFillColor(255,255,255);
		    $title = iconv('UTF-8', 'windows-1252', $this->parameters['resolucion-autorizacion'].' de '.$this->parameters['ano-resol-autorizacion']);
		    $this->pdf->Cell(140,6,$title,1,0,'C',true);
		    $this->pdf->Ln(6);
		    $title = iconv('UTF-8', 'windows-1252', 'Resolucion de Revalidación');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
		    $this->pdf->SetFillColor(255,255,255);
		    $title = iconv('UTF-8', 'windows-1252', $this->parameters['resolucion-revalidacion'].' de '.$this->parameters['ano-resol-revalidacion']);
		    $this->pdf->Cell(140,6,$title,1,0,'C',true);
		    $this->pdf->Ln(6);
		    $this->pdf->Ln(6);
		    $title = iconv('UTF-8', 'windows-1252', 'CARRERA PROFESIONAL');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $value->especialidad);
	        $this->pdf->Cell(100,6,$title,1,0,'C',true);
	        $title = iconv('UTF-8', 'windows-1252', 'TURNO');
	        $this->pdf->SetFillColor(230,230,230);
	        $this->pdf->Cell(20,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252', $value->turno);
	        $this->pdf->Cell(20,6,$title,1,0,'C',true);
		    $this->pdf->Ln(6);
		    $title = iconv('UTF-8', 'windows-1252', 'SEMESTRE DE ESTUDIOS');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252','-');
	        $this->pdf->Cell(60,6,$title,1,0,'C',true);
		    $title = iconv('UTF-8', 'windows-1252', 'PROMOCION');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252','-');
	        $this->pdf->Cell(40,6,$title,1,0,'C',true);
		    $this->pdf->Ln(6);
		    $title = iconv('UTF-8', 'windows-1252', 'DOCENTE EVALUADOR');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(40,6,$title,1,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        if(isset($value->profesor) && is_numeric($value->profesor))
	        	$profesor = '-';
	        else
	        	$profesor = strtoupper(iconv('UTF-8', 'windows-1252', $value->profesor->apell_pat.' '.$value->profesor->apell_mat.' '.$value->profesor->nombre));
	        $title = iconv('UTF-8', 'windows-1252',$profesor);
	        $this->pdf->Cell(140,6,$title,1,0,'C',true);
		    $this->pdf->Ln(6);
		    $title = iconv('UTF-8', 'windows-1252', 'DENOMINACION DEL');
	        $this->pdf->SetFillColor(255,255,255);
		    //$this->pdf->Rect(10,102,45,14);
		    $this->pdf->Cell(40,12,' ',1,0,'L',true);
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->SetXY(10.2,101.2);
		    $this->pdf->Cell(39.6,6,$title,0,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252',$value->modulo);
	        $this->pdf->SetXY(50,101);
	        $this->pdf->Cell(140,12,' ',1,0,'C',true);
	        $this->pdf->SetXY(50.2,101.2);
	        /*echo '<pre>';
	        print_r($title);
	        echo '</pre>';
	        exit();*/
	        if($value->orden != 0)
	        	$this->pdf->Cell(138.8,6,$this->partir_string($value->modulo, 70)[0],0,0,'C',true);
	        else
	        	$this->pdf->Cell(138.8,6,'POR ASIGNATURAS',0,0,'C',true);
		    $this->pdf->Ln(6);
		    $title = iconv('UTF-8', 'windows-1252', 'MODULO '.($value->orden != 0 ? $value->orden : ''));
	        //$this->pdf->SetFillColor(255,255,255);
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->SetXY(10.2,106.8);
		    $this->pdf->Cell(39.6,6,$title,0,0,'L',true);
	        $this->pdf->SetFillColor(255,255,255);
	        $title = iconv('UTF-8', 'windows-1252',$value->modulo);
	        if($value->orden != 0 && count($this->partir_string($value->modulo,70)) > 1){
		        $this->pdf->SetXY(55.2,106.8);
		        $this->pdf->Cell(133.8,6,$this->partir_string($value->modulo, 70)[1],0,0,'C',true);
		    }
		    $this->pdf->SetXY(10,120);
		    $title = iconv('UTF-8', 'windows-1252', 'N°');
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(8,12,$title,1,0,'C',true);
	        $this->pdf->SetFillColor(255,255,255);
		    //$this->pdf->Rect(10,102,45,14);
		    $this->pdf->Cell(32.2,12,' ',1,0,'L',true);
		    $this->pdf->SetFillColor(230,230,230);
		    $title = iconv('UTF-8', 'windows-1252', 'Número de');
		    $this->pdf->SetXY(18.2,120.2);
		    $this->pdf->Cell(31.8,5.8,$title,0,0,'C',true);
		    $this->pdf->SetFillColor(230,230,230);
		    //$this->pdf->Rect(10,102,45,14);
		    $this->pdf->SetXY(50,120);
	        $this->pdf->SetFillColor(255,255,255);
		    $this->pdf->Cell(103,12,' ',1,0,'L',true);
		    $title = iconv('UTF-8', 'windows-1252', 'APELLIDOS Y NOMBRES');
		    $this->pdf->SetXY(50.2,120.2);
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(102.6,5.8,$title,0,0,'C',true);
		    //$this->pdf->SetXY(63,120);
		    $this->pdf->SetXY(153,120);
		    $this->pdf->SetFillColor(255,255,255);
		    $this->pdf->Cell(37,12,' ',1,0,'L',true);
		    $title = iconv('UTF-8', 'windows-1252', 'EVALUACIÓN');
		    $this->pdf->SetXY(153.2,120.2);
		    $this->pdf->SetFillColor(230,230,230);
		    $this->pdf->Cell(36.6,11.6,$title,0,0,'C',true);
		    $this->pdf->SetXY(18.2,125.2);
		    $this->pdf->SetFillColor(230,230,230);
		    $title = iconv('UTF-8', 'windows-1252', 'documento de');
		    $this->pdf->Cell(31.6,3,$title,0,0,'C',true);
		    $this->pdf->SetXY(18.2,128.2);
		    $this->pdf->SetFillColor(230,230,230);
		    $title = iconv('UTF-8', 'windows-1252', 'identidad');
		    $this->pdf->Cell(31.6,3.6,$title,0,0,'C',true);
		    $this->pdf->SetXY(50.2,126);
		    $this->pdf->SetFillColor(230,230,230);
		    $title = iconv('UTF-8', 'windows-1252', '(EN ORDEN ALFABETICO)');
		    $this->pdf->Cell(102.7,5.8,$title,0,0,'C',true);
		    $this->pdf->Ln(6);
	        $this->pdf->SetFillColor(255,255,255);
	        for ($i=0; $i < 8; $i++) { 
		    	$this->pdf->Cell(8,6,($i+1),1,0,'C',true);
		    	if($i == 0){
		    		$dni = iconv('UTF-8', 'windows-1252', $value->dni);
		    		$nombres = iconv('UTF-8', 'windows-1252', $value->nombres);
		    		$valor_nota = iconv('UTF-8', 'windows-1252', $value->valor_nota);
		    	}
		    	else{
		    		$dni = '';
		    		$nombres = '';
		    		$valor_nota = '';
		    	}
		    	$this->pdf->Cell(32,6,$dni,1,0,'C',true);
		    	$this->pdf->Cell(103,6,$nombres,1,0,'L',true);
		    	$this->pdf->Cell(37,6,$valor_nota,1,0,'C',true);
		    	$this->pdf->Ln(6);
	        }
	        $this->pdf->Line(19, 141, 190, 141);
	        $this->pdf->Line(19, 177, 190, 141);
	        $this->pdf->Ln(1);
	        $this->pdf->SetFont('Arial','',8);
            $fc = explode('-', $value->fecha_acta);
            $this->pdf->Cell(0,10,iconv('UTF-8', 'windows-1252','Morropón, ').$fc[2].' de '.$this->meses[$fc[1]].' de '.$fc[0],0,0,'L');
	        $this->pdf->Line(15, 225, 65, 225);
	        $this->pdf->Line(135, 225, 185, 225);
	        $this->pdf->Line(15, 256, 65, 256);
	        $this->pdf->Line(135, 256, 185, 256);
	        $this->pdf->Line(75, 275, 125, 275);
	        $this->pdf->SetFont('Arial','B',6);
	        $this->pdf->SetXY(15,227);
	        $title = iconv('UTF-8', 'windows-1252', 'Docente Evaluador');
	        $this->pdf->Cell(50,0,$title,0,0,'C');
	        $this->pdf->SetXY(135,227);
	        $title = iconv('UTF-8', 'windows-1252', 'Jefe del Área Académica');
	        $this->pdf->Cell(50,0,$title,0,0,'C');
	        //$this->pdf->Cell(15,0,'',0,0,'C');
	        $this->pdf->SetXY(15,258);
	        $title = iconv('UTF-8', 'windows-1252', 'Secretario Académico');
	        $this->pdf->Cell(50,0,$title,0,0,'C');
	        $this->pdf->SetXY(135,258);
	        $title = iconv('UTF-8', 'windows-1252', 'Jefe de la Únidad Académica');
	        $this->pdf->Cell(50,0,$title,0,0,'C');
	        $this->pdf->SetXY(75,277);
	        $this->pdf->Cell(50,0,'Director General',0,0,'C');
		}
        $this->pdf->Close();
        $this->pdf->Output(base_url()."tmp/".date('Y-m-d').".pdf", 'I');
    }

    public function imprimeActaNoRegular($actas){
    	foreach ($actas as $k => $val) if(isset($val->data)){
    		$data = $val->data;
	    	//$this->pdf = new Pdf();
	        $this->pdf->AddPage('P','A4-H');
	        $this->pdf->SetMargins(10,10,20);
	        $this->pdf->AliasNbPages();
	        $pageWidth = 287;
	        $pageHeight = 200;
	        $margin = 10;
	        $this->pdf->Rect( $margin, $margin , $pageWidth - $margin , $pageHeight - $margin - 50);
	        $this->pdf->Rect( $margin, $margin , $pageWidth - $margin , 20);
	        //seccion
	        //$grupo = $data['data'];
	        $this->pdf->SetTitle('');
	        $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],11,11,65);//logo del documento
	        $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],250,11,20,15);
	        $this->pdf->SetFont('Arial','B',10);
	        $this->pdf->Ln(4);
	        $especialidad = $data['especialidad'];
	        $title = iconv('UTF-8', 'windows-1252', 'REGISTRO DE ACTA DE EVALUACIÓN');
	        $this->pdf->Cell(0,0,$title,0,0,'C');
	        $this->pdf->Ln(4);
	        $title = iconv('UTF-8', 'windows-1252', 'EDUCACIÓN SUPERIOR TECNOLÓGICA');
	        $this->pdf->Cell(0,0,$title,0,0,'C');
	        $this->pdf->Ln(4);
	        $periodo = explode('-', $data['periodo']->nombre);
	        $title = iconv('UTF-8', 'windows-1252', 'AÑO '.$periodo[0].' PERIODO LECTIVO '.$periodo[0].' - '.($periodo[1] == '01' ? 'I' : 'II'));
	        $this->pdf->Cell(0,0,$title,0,0,'C');
	        $this->pdf->Ln(12);
	        $this->pdf->SetFont('Arial','',8);
	        $this->pdf->Rect( 10, 30 , 25 , 7);
	        $this->pdf->Cell(25,0,'Nombre del IEST',0,0,'L');
	        $this->pdf->Rect( 35, 30 , 70 , 7);
	        //$this->pdf->Ln(12);
	        $this->pdf->Cell(70,0,'MORROPON',0,0,'C');
	        $this->pdf->Rect( 105, 30 , 120 , 7);
	        $this->pdf->Cell(118,0,'UNIDADES DIDACTICAS',0,0,'C');
	        $this->pdf->Rect( 10, 37 , 33 , 7);
	        $this->pdf->Ln(7);
	        $title = iconv('UTF-8', 'windows-1252', 'Num. Cód. modulo');
	        $this->pdf->Cell(25,0,$title,0,0,'L');
	        $this->pdf->Rect( 35, 37 , 45 , 7);
	        $this->pdf->Cell(45,0,$this->parameters['codigo_institucion'],0,0,'C');
	        $this->pdf->Rect( 80, 37 , 13 , 7);
	        $this->pdf->SetFont('Arial','',6);
	        $this->pdf->Cell(13,0,'Tipo de Gest.',0,0,'L');
	        $this->pdf->Rect( 93, 37 , 12 , 7);
	        $this->pdf->Cell(12,0,'PUBLICA',0,0,'L');
	        $this->pdf->Rect( 10, 44 , 25 , 7);
	        $this->pdf->Ln(7);
	        $this->pdf->Cell(25,0,'Departamento',0,0,'L');
	        $this->pdf->Rect( 35, 44 , 12 , 7);
	        $this->pdf->SetFont('Arial','B',6);
	        $this->pdf->Cell(12,0,'MORROPON',0,0,'L');
	        $this->pdf->Rect( 47, 44 , 12 , 7);
	        $this->pdf->SetFont('Arial','',6);
	        $this->pdf->Cell(12,0,'Provincia',0,0,'L');
	        $this->pdf->Rect( 59, 44 , 12 , 7);
	        $this->pdf->SetFont('Arial','B',6);
	        $this->pdf->Cell(12,0,'MORROPON',0,0,'L');
	        $this->pdf->Rect( 71, 44 , 12 , 7);
	        $this->pdf->SetFont('Arial','',6);
	        $this->pdf->Cell(12,0,'Distrito',0,0,'L');
	        $this->pdf->Rect( 83, 44 , 22 , 7);
	        $this->pdf->Cell(22,0,$this->parameters['lugar-instituto'],0,0,'L');
	        $this->pdf->Rect( 10, 51 , 25 , 8);
	        $this->pdf->Ln(8);
	        $title = iconv('UTF-8', 'windows-1252', 'Dirección del IEST');
	        $this->pdf->Cell(25,0,$title,0,0,'L');
	        $this->pdf->Rect( 35, 51 , 45 , 8);
	        $dir = $this->parameters['direccion-instituto'];
	        $this->pdf->Text(36, 53, substr($dir,0,33));
	        $this->pdf->Text(36, 56, substr($dir,33,30));
	        $this->pdf->Text(36, 59, substr($dir,63));
	        $this->pdf->Cell(45,0,' ',0,0,'L');
	        $this->pdf->Rect( 80, 51 , 13 , 8);
	        $this->pdf->Cell(13,0,'DRE-GRE',0,0,'L');
	        $this->pdf->Rect( 93, 51 , 12 , 8);
	        $this->pdf->SetFont('Arial','B',6);
	        $this->pdf->Cell(12,0,'MORROPON',0,0,'L');
	        $this->pdf->SetFont('Arial','',6);
	        $this->pdf->Rect( 10, 59 , 40 , 7);
	        $this->pdf->Ln(7);
	        $title = iconv('UTF-8', 'windows-1252', 'Resolución de Autorización (Tipo, núme.');
	        $this->pdf->Cell(40,0,$title,0,0,'L');
	        $this->pdf->Rect( 50, 59 , 55 , 7);
	        $this->pdf->SetFont('Arial','B',6);
	        //$this->pdf->Ln(7);
	        $title = iconv('UTF-8', 'windows-1252', 'R.S. N° 131-83-ED DEL 30.01.1983');
	        $this->pdf->Cell(40,0,$title,0,0,'L');
	        $this->pdf->Rect( 10, 66 , 40 , 7);
	        $this->pdf->SetFont('Arial','',6);
	        $this->pdf->Ln(7);
	        $title = iconv('UTF-8', 'windows-1252', 'Resolución de Revalidación (Tipo, núme.');
	        $this->pdf->Cell(40,0,$title,0,0,'L');
	        $this->pdf->Rect( 50, 66 , 55 , 7);
	        $this->pdf->SetFont('Arial','B',6);
	        //$this->pdf->Ln(7);
	        $title = iconv('UTF-8', 'windows-1252', 'R.D. N° 018-2008-ED DEL 30.01.2008');
	        $this->pdf->Cell(40,0,$title,0,0,'L');
	        $this->pdf->Rect( 10, 73 , 7 , 27);
	        $this->pdf->Rect( 17, 73 , 20 , 27);
	        $this->pdf->Rect( 37, 73 , 68 , 27);
	        $title = iconv('UTF-8', 'windows-1252', 'N°');
	        $this->pdf->Text(12, 88, $title);
	        $title = iconv('UTF-8', 'windows-1252', 'Número de');
	        $this->pdf->Text(21.5, 85, $title);
	        $title = iconv('UTF-8', 'windows-1252', 'documento de');
	        $this->pdf->Text(20, 88, $title);
	        $title = iconv('UTF-8', 'windows-1252', 'identidad');
	        $this->pdf->Text(22, 91, $title);
	        $title = iconv('UTF-8', 'windows-1252', 'APELLIDOS Y NOMBRE');
	        $this->pdf->Text(57, 86, $title);
	        $title = iconv('UTF-8', 'windows-1252', '(en orden alfabético)');
	        $this->pdf->Text(59, 89, $title);
	        $this->pdf->Rect( 105, 37 , 8 , 50);
	        $this->pdf->Rect( 113, 37 , 8 , 50);
	        $this->pdf->Rect( 121, 37 , 8 , 50);
	        $this->pdf->Rect( 129, 37 , 8 , 50);
	        $this->pdf->Rect( 137, 37 , 8 , 50);
	        $this->pdf->Rect( 145, 37 , 8 , 50);
	        $this->pdf->Rect( 153, 37 , 8 , 50);
	        $this->pdf->Rect( 161, 37 , 8 , 50);
	        $this->pdf->Rect( 169, 37 , 8 , 50);
	        $this->pdf->Rect( 177, 37 , 8 , 50);
	        $this->pdf->Rect( 185, 37 , 8 , 50);
	        $this->pdf->Rect( 193, 37 , 8 , 50);
	        $this->pdf->SetFont('Arial','',6);
	        $cursos = $data['cursos'];
	        $w = 110;
	        $suma_creditos = 0;
	        foreach ($cursos as $key => $value) {
	            $nombre = iconv('UTF-8', 'windows-1252', $value->curso);
	            if(strlen($nombre) > 70){
	                $this->pdf->TextWithDirection($w+$key*8-2,85,  substr($nombre, 0, 35),'U');   
	                $this->pdf->TextWithDirection($w+$key*8,85,  substr($nombre, 35, 35),'U');
	                $this->pdf->TextWithDirection($w+$key*8+2,85,  substr($nombre, 60),'U');
	                //$this->pdf->Text($w+$key*8, 93, $value->creditos);
	            }
	            if(strlen($nombre) > 35 && strlen($nombre) <= 70){
	                $this->pdf->TextWithDirection($w+$key*8-1,85,  substr($nombre, 0, 35),'U');   
	                $this->pdf->TextWithDirection($w+$key*8+1,85,  substr($nombre, 35),'U');
	            }
	            if(strlen($nombre) <= 35){
	                $this->pdf->TextWithDirection($w+$key*8,85,  substr($nombre, 0, 35),'U');   
	            }
	            $this->pdf->Text($w+$key*8-3, 97, $value->creditos);
	            $suma_creditos += $value->creditos;
	        }
	        $this->pdf->Rect( 201, 37 , 8 , 63);
	        $this->pdf->Rect( 209, 37 , 8 , 63);
	        $this->pdf->Rect( 217, 37 , 8 , 63);
	        //para los creditos
	        $this->pdf->Rect( 105, 87 , 96 , 6);
	        $this->pdf->Rect( 105, 93 , 8 , 7);
	        $this->pdf->Rect( 113, 93 , 8 , 7);
	        $this->pdf->Rect( 121, 93 , 8 , 7);
	        $this->pdf->Rect( 129, 93 , 8 , 7);
	        $this->pdf->Rect( 137, 93 , 8 , 7);
	        $this->pdf->Rect( 145, 93 , 8 , 7);
	        $this->pdf->Rect( 153, 93 , 8 , 7);
	        $this->pdf->Rect( 161, 93 , 8 , 7);
	        $this->pdf->Rect( 169, 93 , 8 , 7);
	        $this->pdf->Rect( 177, 93 , 8 , 7);
	        $this->pdf->Rect( 185, 93 , 8 , 7);
	        //datos y observaciones
	        $this->pdf->Rect( 225, 30 , 62 , 18);
	        $this->pdf->Rect( 225, 48 , 62 , 7);
	        $this->pdf->Rect( 225, 55 , 62 , 7);
	        $this->pdf->Rect( 225, 62 , 62 , 7);
	        $this->pdf->Rect( 225, 69 , 62 , 7);
	        $this->pdf->Rect( 225, 76 , 62 , 7);
	        $this->pdf->Rect( 225, 76 , 62 , 24);
	        $this->pdf->SetFont('Arial','',7);
	        /*
	         * AUN NO DECIDIMOS QUE ESTO VAYA AQUI 
	         */
	        $title = iconv('UTF-8', 'windows-1252', 'NÚMERO DE UNIDADES DIDÁCTICAS APROBADAS');
	        $this->pdf->TextWithDirection(205,99,  $title,'U'); 
	        $title = iconv('UTF-8', 'windows-1252', 'NÚMERO DE UNIDADES DIDÁCTICAS');
	        $this->pdf->TextWithDirection(212,99,  $title,'U'); 
	        $this->pdf->TextWithDirection(220,99,  'DESAPROBADAS','U'); 
	        $this->pdf->TextWithDirection(223,99,  'PUNTAJE PROMEDIO PONDERADO','U'); 
	        $this->pdf->SetFont('Arial','B',9);
	        $esp = 'CARRERA: '. $especialidad->nombre;
	        $title = $this->partir_string( iconv('UTF-8', 'windows-1252', $esp) , 30);
	        $cont = 0;
	        foreach ($title as $value) {
	            $this->pdf->Text(227,33+$cont*4,  $value); 
	            $cont ++;
	        }
	        $ciclo_romano = ['','I','II','III','IV','V','VI'];
	        $title = iconv('UTF-8', 'windows-1252', 'MENCIÓN: '.$data['tipo_eval_acta']->nombre);
	        $this->pdf->Text(227,53, $title); 
	        $title = iconv('UTF-8', 'windows-1252', 'SECCIÓN: UNICA');
	        $this->pdf->Text(227,60, $title); 
	        $title = iconv('UTF-8', 'windows-1252', 'PERIODO ACADÉMICO:     '.($periodo[1] == '01' ? 'I' : 'II').' - '.$periodo[0]);
	        $this->pdf->Text(227,67, $title); 
	        $title = iconv('UTF-8', 'windows-1252', 'SEMESTRE ACADÉMICO:      '.$ciclo_romano[$data['ciclo']->id]);
	        $this->pdf->Text(227,74, $title); 
	        $title = iconv('UTF-8', 'windows-1252', 'TURNO: '.$data['turno']->nombre);
	        $this->pdf->Text(227,81, $title); 
	        $title = iconv('UTF-8', 'windows-1252', 'OBSERVACIONES');
	        $this->pdf->Text(237,93, $title); 
	        $this->pdf->SetFont('Arial','B',9);
	        $this->pdf->Text(144, 91, 'CREDITOS');
	        $this->pdf->Ln(33);
	        $this->pdf->SetFont('Arial','B',6);
	        $aluns = isset($data['alumnos']) ? $data['alumnos'] : []; 
	        for($i = 0; $i < 15; $i++){
	            $this->pdf->Rect( 10, 100+($i*5) , 7 , 5);
	            $this->pdf->Rect( 17, 100+($i*5) , 20 , 5);
	            $this->pdf->Rect( 37, 100+($i*5) , 68 , 5);
	            $this->pdf->Rect( 105, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 113, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 121, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 129, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 137, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 145, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 153, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 161, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 169, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 177, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 185, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 193, 100+($i*5) , 8 , 5);
	            $this->pdf->Cell(7,0,($i+1),0,0,'L');
	            $cant_notas_aprob = 0;
	            $cant_notas_desp = 0;
	            $puntaje = 0;
	            if(isset($aluns[$i])){
	                $this->pdf->SetFont('Arial','B',7);
	                $this->pdf->Cell(20,0,$aluns[$i]->dni,0,0,'C');
	                $title = iconv('UTF-8', 'windows-1252', $aluns[$i]->apell_pat.' '.$aluns[$i]->apell_mat.', '.$aluns[$i]->nombre);
	                $this->pdf->Cell(68,0,$title,0,0,'L');
	                foreach ($cursos as $key => $value) {
	                	//if(isset($aluns[$i]->cursos[$value->id_curso]))
	                	if(isset($aluns[$i]->cursos[$value->id_curso]) && $aluns[$i]->cursos[$value->id_curso]->valor_nota < $aluns[$i]->cursos[$value->id_curso]->eval_minima){
	                		$this->pdf->SetTextColor(255,0,0);
	                	}else{
	                		$this->pdf->SetTextColor(0,0,0);
	                	}
	                	$this->pdf->Cell(8,0,isset($aluns[$i]->cursos[$value->id_curso]) ? $this->completardigitos($aluns[$i]->cursos[$value->id_curso]->valor_nota,2) : '-',0,0,'C');

	                		$this->pdf->SetTextColor(0,0,0);
	                }
	            }
	            $this->pdf->Rect( 201, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 209, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 217, 100+($i*5) , 8 , 5);
	            $this->pdf->Rect( 225, 100+($i*5) , 62 , 5);
	            /*$this->pdf->Cell(8,0,$cant_notas_aprob == 0 ? '0' : $cant_notas_aprob,0,0,'C');
	            $this->pdf->Cell(8,0,$cant_notas_desp == 0 ? '0' : $cant_notas_desp,0,0,'C');
	            $this->pdf->Cell(8,0,$puntaje == 0 ? '0' : number_format($puntaje/$suma_creditos,2,'.',''),0,0,'C');*/
	            $puntaje = 0;
	            $cant_notas_desp = 0;
	            $cant_notas_aprob = 0;
	            $this->pdf->SetFont('Arial','B',6);
	            $this->pdf->Ln(5);
	        }
	        $this->pdf->SetFont('Arial','B',10);
	        $fecha = explode('-', $data['acta']->fecha);
	        //$this->pdf->Cell(0,10,'Morropon, '.date('d').' de '.$this->meses[date('m')].' de '.date('Y'),0,0,'R');
	        $title = iconv('UTF-8', 'windows-1252', 'Morropón, '.$fecha[2].' de '.$this->meses[$fecha[1]].' de '.$fecha[0]);
	        $this->pdf->Text(127,206, $title); 
	    }
        $this->pdf->Close();
        $this->pdf->Output(base_url()."tmp/".date('Y-m-d').".pdf", 'I');
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

    public function completardigitos($char,$length = 2){
        return str_repeat('0', $length-strlen($char)).$char;
    }
}