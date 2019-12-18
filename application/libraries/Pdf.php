<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH."/third_party/fpdf.php";

//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class Pdf {

	public $pdf = null;
	public $parameters = null;
        public $meses = null;

	public function __construct($parameters){
		$this->pdf = new FPDF();
		$this->parameters = $parameters;
                
                $this->meses = ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'];
                $this->orden = ['','Primer','Segundo','Tercer','Cuarto','Quinto','Sexto'];
	}

        public function cursos_matricula($data = array()){
                if(file_exists(BASEPATH."../pdfs/".$data['codigo'].'.pdf')){
                        header("Content-type: application/pdf");
                        header("Content-Disposition: inline; filename=filename.pdf");
                        @readfile(BASEPATH."../pdfs/".$data_venta->num_serie.'-'.$data_venta->num_documento.'-A7.pdf');
                        exit();
                }
                //$this->pdf = new Pdf();
                $this->pdf->AddPage('P','A4');
                $this->pdf->SetMargins(20,10,20);
                $this->pdf->AliasNbPages();
                $this->pdf->SetTitle($data['codigo']);
                $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],8,8,65);//logo del documento
                $this->pdf->SetFont('Arial','B',10);
                $title = iconv('UTF-8', 'windows-1252', '"'.$this->parameters['nombre_anio'].'"');
                //$this->pdf->Cell(1);
                $this->pdf->Ln(26);
                $this->pdf->Cell(0,0,$title,0,0,'C');
                $this->pdf->Close();
                        //      $this->pdf->Output(BASEPATH."../pdfs/".$data_venta->num_serie.'-'.$data_venta->num_documento."-A7.pdf", 'F');
                $this->pdf->Output(base_url()."tmp/".$data['codigo'].".pdf", 'I');
        }
	
        //AQUI IMPRIME LA CONSTANCIA DE MATRICULA
	public function terminar_matricula($data = array()){
		if(file_exists(BASEPATH."../pdfs/".$data['codigo'].'.pdf')){
			header("Content-type: application/pdf");
			header("Content-Disposition: inline; filename=filename.pdf");
			@readfile(BASEPATH."../pdfs/".$data_venta->num_serie.'-'.$data_venta->num_documento.'-A7.pdf');
			exit();
		}
                $CI =& get_instance();
                //--------------
                $CI->load->model('seccion'); 
		//$this->pdf = new Pdf();
		$this->pdf->AddPage('P','A4');
		$this->pdf->SetMargins(20,10,20);
		$this->pdf->AliasNbPages();
		$this->pdf->SetTitle($data['codigo']);
		$this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],8,8,35);//logo del documento
                $this->pdf->SetFont('Arial','B',10);
                $title = iconv('UTF-8', 'windows-1252', '"'.$this->parameters['nombre_anio'].'"');
                //$this->pdf->Cell(1);
                $this->pdf->Ln(26);
                $this->pdf->Cell(0,0,$title,0,0,'C');
                $this->pdf->Ln(13);
                $this->pdf->SetFont('Arial','BU',14);
                $this->pdf->Cell(0,0,'CONSTANCIA DE MATRICULA',0,0,'C');
                $this->pdf->Ln(12);
                $this->pdf->SetFont('Arial','B',14);
                $sm = iconv('UTF-8', 'windows-1252', '            EL SECRETARIO ACADÉMICO DEL INSTITUTO DE EDUCACIÓN');
                $this->pdf->Cell(170,0,$sm,0,1,'FJ');
                $this->pdf->Ln(8);
                $sm = iconv('UTF-8', 'windows-1252', 'SUPERIOR TECNOLÓGICO PÚBLICO "MORROPÓN"');
                $this->pdf->Cell(170,0,$sm,0,1,'FJ');
                $this->pdf->Ln(8);
                $sm = iconv('UTF-8', 'windows-1252', 'DE MORROPÓN.');
                $this->pdf->Cell(170,0,$sm,0,1,'L');

                $this->pdf->Ln(22);
                $this->pdf->SetFont('Arial','',14);
                $sm = iconv('UTF-8', 'windows-1252', 'HACE CONSTAR:');
                $this->pdf->Cell(170,0,$sm,0,1,'L');
                $this->pdf->Ln(22);
                $this->pdf->SetFont('Arial','',12);
                $this->pdf->Cell(17);
                $sm = iconv('UTF-8', 'windows-1252', 'Que don: ');
                $this->pdf->Cell(10,0,$sm,0,1,'L');
                $sm = iconv('UTF-8', 'windows-1252', strtoupper($data['data']->nombre.' '.$data['data']->apell_pat.' '.$data['data']->apell_mat).',');
                $this->pdf->Cell(50);
                $this->pdf->SetFont('Arial','B',12);
                $this->pdf->Cell(120,0,$sm,0,1,'FJ');
                $this->pdf->SetFont('Arial','',12);
                $this->pdf->Ln(8);
                $this->pdf->SetFont('Arial','',14);
                $sm = iconv('UTF-8', 'windows-1252', 'con DNI '.$data['data']->dni.'; se encuentra Matriculado en la Carrera Profesional');
                $this->pdf->Cell(170,0,$sm,0,1,'FJ');
                $this->pdf->Ln(8);
                $sm = iconv('UTF-8', 'windows-1252', 'de');
                $this->pdf->Cell(18,0,$sm,0,1,'L');
                $this->pdf->Cell(8);
                if( strlen( $data['especialidad']->especialidad) <= 40)
                    $sm = iconv('UTF-8', 'windows-1252', strtoupper($data['especialidad']->especialidad).', cursa el '.$this->orden[$data['especialidad']->id_ciclo].' Semestre,');
                else{
                    $cad = $this->partir_string($data['especialidad']->especialidad,50);
                    $sm = iconv('UTF-8', 'windows-1252', strtoupper($cad[0]));
                }
                $this->pdf->Cell(165,0,$sm,0,1,'FJ');
                $this->pdf->Ln(8);
                $sm = iconv('UTF-8', 'windows-1252', (strlen($data['especialidad']->especialidad) <= 40 ? '' : $cad[1].', cursa el '.$this->orden[$data['especialidad']->id_ciclo].' Semestre,'));
                $this->pdf->Cell(strlen($data['especialidad']->especialidad) > 40 ? 0 : 170,0,$sm,0,1,strlen($data['especialidad']->especialidad) >= 40 ? 'FJ': 'L');
                if( strlen( $data['especialidad']->especialidad) >= 40){
                    $this->pdf->Ln(8);
                }
                    $sm = iconv('UTF-8', 'windows-1252', 'turno '.$data['especialidad']->turno.'.');
                    $this->pdf->Cell(170,0,$sm,0,1,'L');
                $this->pdf->Ln(12);
                $sm = iconv('UTF-8', 'windows-1252', '   La especialidad consta de seis (06) Semestres Académicos');
                $this->pdf->Cell(170,0,$sm,0,1,'FJ');
                $this->pdf->Ln(12);
                //$this->pdf->Ln(12);
                if($data['especialidad']->expediente_ingreso != ''){
                    $sm1 = iconv('UTF-8', 'windows-1252', '            Se expide la presente, a petición del interesado, según Expediente de');
                    $sm2 = iconv('UTF-8', 'windows-1252', 'Ingreso N° '.$data['especialidad']->expediente_ingreso.', para los fines que estime conveniente.');
                }
                else{
                    $sm1 = iconv('UTF-8', 'windows-1252', '            Se expide la presente, a petición del interesado para los fines ');
                    $sm2 = iconv('UTF-8', 'windows-1252', ' que estime conveniente.');
                }
                $this->pdf->Cell(170,0,$sm1,0,1,'FJ');
                $this->pdf->Ln(8);
                $this->pdf->Cell(170,0,$sm2,0,1,'L');
                $this->pdf->Ln(20);
                /*Cursos*/
                $this->pdf->SetFont('Arial','',14);
                /*$sm = iconv('UTF-8', 'windows-1252', 'Cursos Inscritos:');
                $this->pdf->Cell(170,0,$sm,0,1,'L');
                $this->pdf->Ln(12);
                $this->pdf->Cell(25);
                $this->pdf->SetFont('Arial','',10);
                $this->pdf->Cell(20,9,'Cod.',1,0,'C');
                $this->pdf->Cell(60,9,'Cursos',1,0,'C');
                $this->pdf->Cell(20,9,'Ciclo',1,0,'C');
                $sm = iconv('UTF-8', 'windows-1252', 'Sección');
                $this->pdf->Cell(20,9,$sm,1,0,'C');
                $this->pdf->Ln(9);
                $this->pdf->SetFont('Arial','',8);
                foreach ($data['cursos'] as $key => $value) {
                        $band = false;
                        if(strlen($value->curso) >= 30)
                                $band = true;
                        else
                                $band = false;
                        $this->pdf->Cell(25);
                        $this->pdf->Cell(20,$band ? 15 : 9,$value->codigo_curso,1,0,'C');
                        $this->pdf->Cell(60,$band ? 15 : 9,$band ? substr($value->curso,0,30) : $value->curso,1,0,'C');
                        $this->pdf->Cell(20,$band ? 15 : 9,$value->nombre_ciclo,1,0,'C');
                        $seccion = $CI->seccion->getSeccionForMalla($value->id_malla_periodo);
                        $this->pdf->Cell(20,$band ? 15 : 9,isset($seccion->seccion) ? $seccion->seccion : '-',1,0,'C');
                        if($band){
                                $this->pdf->Ln(7.5);
                                $this->pdf->Cell(55);
                                $this->pdf->Cell(60,9,substr($value->curso, 30),0,0,'C');
                                $this->pdf->Ln(7.5);
                        }else{
                                $this->pdf->Ln(9);
                        }
                }*/
                $this->pdf->Cell(0,10,iconv('UTF-8', 'windows-1252','Morropón, ').date('d').' de '.$this->meses[date('m')].' de '.date('Y'),0,0,'R');
                /*$this->pdf->Ln(8);
                $sm = iconv('UTF-8', 'windows-1252', 'Cuya especialidad consta de ');
                $this->pdf->Cell(170,0,$sm,0,1,'FJ');*/


        	    //Cerrar
                $this->pdf->Close();
        		//	$this->pdf->Output(BASEPATH."../pdfs/".$data_venta->num_serie.'-'.$data_venta->num_documento."-A7.pdf", 'F');
        		$this->pdf->Output(base_url()."tmp/".$data['codigo'].".pdf", 'I');
        }
        
        //AQUI IMPRIME LA FICHA DE PAGO POR LA MATRICULA
        public function ficha_matricula($data = array()){
            $this->pdf->AddPage('P','A4-2');
            $this->pdf->SetMargins(20,10,20);
            $this->pdf->AliasNbPages();
            $this->pdf->SetTitle('Ficha de Matrícula');
            $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo'],8,12,20);//logo del documento
            $this->pdf->SetFont('Arial','',10);
            $cod = str_repeat(0, 11-strlen($data['id_pago'])).$data['id_pago'];
            $title = iconv('UTF-8', 'windows-1252', 'CÓDIGO DE MATRÍCULA  '.$cod);
            $this->pdf->Cell(0,0,$title,0,0,'R');
            $this->pdf->SetFont('Arial','B',10);
            $title = iconv('UTF-8', 'windows-1252', $this->parameters['nombre_instituto']);
            //$this->pdf->Cell(1);
            $this->pdf->Ln(10);
            $this->pdf->Cell(8,0,'',0,0,'L');
            $this->pdf->Cell(0,0,$title,0,0,'L');
            $this->pdf->Ln(5);
            $meses = ['01'=>'enero','02'=>'febrero','03'=>'marzo','04'=>'abril','05'=>'mayo','06'=>'junio','07'=>'julio','08'=>'agosto','09'=>'septiembre',
                '10'=>'octubre','11'=>'noviembre','12'=>'diciembre'];
            $this->pdf->SetFont('Arial','B',8);
            $this->pdf->Cell(140,0,'Morropon, ',0,0,'R');
            $this->pdf->SetFont('Arial','',8);
            $this->pdf->Cell(30,0,date('d').' de '.$meses[date('m')].' de '.date('Y'),0,0,'R');
            $this->pdf->Ln(8);
            $this->pdf->SetFont('Arial','B',12);
            $this->pdf->Cell(0,0,'FICHA DE MATRICULA',0,0,'C');
            //datos del alumno
            $this->pdf->Ln(10);
            $this->pdf->SetFont('Courier','B',12);
            $data_alumno = $data['data'];
            $per = explode('-', $data_alumno->periodo);
            if($per[1] == '01')
                $periodo = 'I';
            else
                $periodo = 'II';
            $title = iconv('UTF-8', 'windows-1252', 'SEMESTRE ACADÉMICO '.$periodo.'-'.$per[0]);
            $this->pdf->Cell(0,0,$title,0,0,'C');
            //Nombres y apellidos
            $this->pdf->Ln(10);
            $this->pdf->SetFont('Helvetica','',10);
            $title = iconv('UTF-8', 'windows-1252', 'APELLIDOS Y NOMBRES ');
            $this->pdf->Cell(50,0,$title,0,0,'L');
            $this->pdf->SetFont('Helvetica','B',10);
            $credenciales = $data['credenciales'];
            $title = iconv('UTF-8', 'windows-1252', $credenciales->apell_pat.' '.$credenciales->apell_mat.', '.$credenciales->nombre);
            $this->pdf->Cell(120,0,$title,0,0,'L');
            //Carrera profesional
            $this->pdf->Ln(10);
            $this->pdf->SetFont('Helvetica','',10);
            $title = iconv('UTF-8', 'windows-1252', 'CARRERA PROFESIONAL');
            $this->pdf->Cell(50,0,$title,0,0,'L');
            $this->pdf->SetFont('Helvetica','B',10);
            $credenciales = $data['credenciales'];
            $title = iconv('UTF-8', 'windows-1252', $data_alumno->especialidad);
            $this->pdf->Cell(120,0,$title,0,0,'L');
            //semestre
            $this->pdf->Ln(10);
            $this->pdf->SetFont('Helvetica','',10);
            $title = iconv('UTF-8', 'windows-1252', 'SEMESTRE Y TURNO');
            $this->pdf->Cell(50,0,$title,0,0,'L');
            $this->pdf->SetFont('Helvetica','B',10);
            $credenciales = $data['credenciales'];
            $sem = explode(' ', $data_alumno->ciclo);
            $title = iconv('UTF-8', 'windows-1252', $sem[1].' SEMESTRE - '.$data_alumno->turno);
            $this->pdf->Cell(120,0,$title,0,0,'L');
            //DNI
            $this->pdf->Ln(10);
            $this->pdf->SetFont('Helvetica','',10);
            $title = iconv('UTF-8', 'windows-1252', 'DNI');
            $this->pdf->Cell(50,0,$title,0,0,'L');
            $this->pdf->SetFont('Helvetica','B',10);
            $credenciales = $data['credenciales'];
            $title = iconv('UTF-8', 'windows-1252', $credenciales->dni);
            $this->pdf->Cell(120,0,$title,0,0,'L');
            //FECHA NACIMIENTO
            $this->pdf->Ln(10);
            $this->pdf->SetFont('Helvetica','',10);
            $title = iconv('UTF-8', 'windows-1252', 'FECHA DE NACIMIENTO');
            $this->pdf->Cell(50,0,$title,0,0,'L');
            $this->pdf->SetFont('Helvetica','B',10);
            $credenciales = $data['credenciales'];
            $d1 = date_create(date($credenciales->fch_nac));
            $d2 = date_create(date('Y-m-d'));
            $intervalo = date_diff($d1,$d2);
            $title = iconv('UTF-8', 'windows-1252', date('d-m-Y',strtotime($credenciales->fch_nac)));
            $this->pdf->Cell(40,0,$title,0,0,'L');
            $this->pdf->Cell(20,0,'EDAD     '.$intervalo->y,0,0,'L');
            //Direccion
            $this->pdf->Ln(10);
            $this->pdf->SetFont('Helvetica','',10);
            $title = iconv('UTF-8', 'windows-1252', 'DIRECCIÓN');
            $this->pdf->Cell(50,0,$title,0,0,'L');
            $this->pdf->SetFont('Helvetica','B',10);
            $credenciales = $data['credenciales'];
            $title = iconv('UTF-8', 'windows-1252', $credenciales->direccion);
            $this->pdf->Cell(120,0,$title,0,0,'L');
            //Observaciones
            $this->pdf->Ln(10);
            $this->pdf->SetFont('Helvetica','',10);
            $title = iconv('UTF-8', 'windows-1252', 'OBSERVACIÓN');
            $this->pdf->Cell(50,0,$title,0,0,'L');
            $this->pdf->SetFont('Helvetica','B',10);
            $title = iconv('UTF-8', 'windows-1252', $data['data_matricula']['observacion']);
            $this->pdf->Cell(120,0,$title,0,0,'L');
            //Firma alumno
            $this->pdf->Ln(10);
            $this->pdf->SetFont('Helvetica','',10);
            $title = iconv('UTF-8', 'windows-1252', '___________________________________________________________________________');
            $this->pdf->Cell(0,0,$title,0,0,'C');
            $this->pdf->Ln(5);
            $this->pdf->SetFont('Helvetica','B',10);
            $title = iconv('UTF-8', 'windows-1252', 'FIRMA DEL ALUMNO');
            $this->pdf->Cell(0,0,$title,0,0,'C');
            $this->pdf->Close();
            $this->pdf->Output(base_url()."tmp/".$data['id_pago'].".pdf", 'I');
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
                array_push($cadenas,$string);
            return $cadenas;
        }
        
        //AQUI EL PROFESOR IMPRIME SU ACTA DE EVALUACIONES DE LOS ALUMNOS
        public function imprimir_acta_notas_profesor($data = array()){
            $CI =& get_instance();
            //--------------
//            $CI->load->model('seccion'); 
            //$this->pdf = new Pdf();
            //Caratula
            $especialidad = $data['especialidad'];
            $curso = $data['curso'];
            $turno = $data['turno'];
            $malla = $data['malla'];
            $periodo = $data['periodo'];
            $profesor = $data['profesor'];
                $this->pdf->AddPage('P','A4-3');
                $this->pdf->SetMargins(10,10,20);
    //            $this->pdf->SetTitle($seccion->nombre);
                $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],11,11,65);//logo del documento
                $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-grau'],140,11,50,15);
                $this->pdf->SetFont('Times','B',12);
                $this->pdf->Ln(22);
                $title = iconv('UTF-8', 'windows-1252', 'INSITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO');
                $this->pdf->Cell(0,0,$title,0,0,'C');
                $this->pdf->Ln(7);
                $this->pdf->Cell(20,0,'',0,0,'L');
                $this->pdf->SetFont('Times','B',16);
                $title = iconv('UTF-8', 'windows-1252', $this->parameters['name_institute']);
                $this->pdf->Cell(110,0,$title,0,0,'L');
                $this->pdf->SetFont('Times','B',10);
                $title = iconv('UTF-8', 'windows-1252', $this->parameters['url_institute']);
                $this->pdf->Cell(30,0,$title,0,0,'L');
                //para el cuadrado con bordes redondos
                $this->pdf->SetFillColor(192);
                $this->pdf->RoundedRect(30, 45, 140, 27, 5, '1234', 'D');
                $this->pdf->Ln(15);
                $this->pdf->SetFont('Courier','B',36);
                $title = iconv('UTF-8', 'windows-1252', 'REGISTRO DE');
                $this->pdf->Cell(0,0,$title,0,0,'C');
                $this->pdf->Ln(13);
                $title = iconv('UTF-8', 'windows-1252', ' EVALUACIÓN ');
                $this->pdf->Cell(0,0,$title,0,0,'C');
                $this->pdf->SetFont('Times','B',16);
                $this->pdf->Ln(9);
                $title = iconv('UTF-8', 'windows-1252', 'CARRERA PROFESIONAL');
                $this->pdf->Cell(0,0,$title,0,0,'C');
                $this->pdf->SetFont('Times','B',10);
                $this->pdf->Ln(5);
                $title = iconv('UTF-8', 'windows-1252', $especialidad->nombre);
                $this->pdf->Cell(0,8,$title,1,1,'C');
                $this->pdf->SetFont('Times','B',12);
                $this->pdf->Ln(8);
                $title = iconv('UTF-8', 'windows-1252', 'Capacidades Específicas (Módulo Técnico Profesional)');
                $this->pdf->Cell(0,0,$title,0,0,'C');
                $this->pdf->SetFont('Times','B',14);
                $this->pdf->Ln(5);
                $title = iconv('UTF-8', 'windows-1252', 'Unidad Didáctica');
                $this->pdf->Cell(0,8,$title,1,0,'C');
                $this->pdf->SetFont('Times','B',14);
                $this->pdf->Ln(8);
                $title = iconv('UTF-8', 'windows-1252', $curso->nombre);
                $this->pdf->Cell(0,8,$title,1,0,'C');
                $this->pdf->SetFont('Times','B',10);
                $this->pdf->Ln(22);
                $title = iconv('UTF-8', 'windows-1252', 'TURNO');
                $this->pdf->Cell(50,5,$title,0,0,'L');
                $title = iconv('UTF-8', 'windows-1252', $turno->nombre);
                $this->pdf->Cell(30,5,$title,1,0,'C');
                $this->pdf->Ln(10);
                $title = iconv('UTF-8', 'windows-1252', 'SEMESTRE DE ESTUDIOS');
                $this->pdf->Cell(50,5,$title,0,0,'L');
                $semestre = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI'];
                $title = iconv('UTF-8', 'windows-1252', $semestre[$malla->id_ciclo]);
                $this->pdf->Cell(30,5,$title,1,0,'C');
                $this->pdf->Cell(20,0,'',0,0,'C');
                $title = iconv('UTF-8', 'windows-1252', 'PERIODO ACADÉMICO');
                $this->pdf->Cell(50,5,$title,0,0,'L');
                $p = explode('-', $periodo->nombre);
                $title = iconv('UTF-8', 'windows-1252', $p[0].'-'.$semestre[(int)$p[1]]);
                $this->pdf->Cell(30,5,$title,1,0,'C');
                $this->pdf->Ln(10);
                $title = iconv('UTF-8', 'windows-1252', 'CRÉDITOS');
                $this->pdf->Cell(50,5,$title,0,0,'L');
                $title = iconv('UTF-8', 'windows-1252', $curso->creditos);
                $this->pdf->Cell(30,5,$title,1,0,'C');
                $this->pdf->Ln(10);
                $title = iconv('UTF-8', 'windows-1252', 'HORAS SEMANALES');
                $this->pdf->Cell(50,5,$title,0,0,'L');
                $title = iconv('UTF-8', 'windows-1252', $curso->horas);
                $this->pdf->Cell(30,5,$title,1,0,'C');
                $this->pdf->Ln(10);
                $title = iconv('UTF-8', 'windows-1252', 'DOCENTE');
                $this->pdf->Cell(50,5,$title,0,0,'L');
                $title = iconv('UTF-8', 'windows-1252', $profesor->nombre.' '.$profesor->apell_pat.' '.$profesor->apell_mat);
                $this->pdf->Cell(100,5,$title,1,0,'C');
                $this->pdf->Ln(10);
                $this->pdf->Cell(150,5,'HORARIO',1,0,'C');
                $this->pdf->Ln(5);
                for($i = 0; $i < 5; $i++){
                    $this->pdf->Cell(20,5,' ',1,0,'L');
                    $this->pdf->Cell(40,5,' ',1,0,'L');
                    $this->pdf->Cell(90,5,' ',1,0,'L');
                    $this->pdf->Ln(5);
                }
                $this->pdf->Ln(30);
                $this->pdf->Cell(0,0,'________________________________________________________',0,0,'C');
                $this->pdf->Ln(5);
                $this->pdf->Cell(0,0,'Firma del Docente',0,0,'C');
            
            //HOJA PARA LOS INDICADORES
            if(count($data['capacidades'])){
                $this->pdf->AddPage('P','A4-3');
                $this->pdf->SetMargins(10,10,20);
                $this->pdf->SetFont('Times','B',16);
                $this->pdf->Ln(12);
                $title = iconv('UTF-8', 'windows-1252', 'INDICADORES DE EVALUACIÓN');
                $this->pdf->Cell(0,0,$title,0,0,'C');
                $this->pdf->Ln(10);
                $capacidades = $data['capacidades'];
                foreach ($capacidades as $key => $value) {
                    $cap = $value['capacidad'];
                    $this->pdf->Cell(10,10,'  '.($key + 1),1,0,'L');
                    $title = iconv('UTF-8', 'windows-1252', $cap->nombre);
                    $this->pdf->Cell(170,10,$title,1,0,'C');
                    $this->pdf->Ln(10);
                    $items  = $value['items'];
                    foreach ($items as $k => $v) {
                        $this->pdf->SetFont('Arial','',9);
                        $this->pdf->Cell(10,10,' ',0,0,'L');
                        $this->pdf->Cell(170,10,' ',1,0,'L');
                        $this->pdf->Ln(2);
                        $this->pdf->Cell(10,0,' ',0,0,'L');
                        $lineas = $this->partir_string(($k+1).'. '.$v->contenido,115);
                        foreach ($lineas as $value) {
                            $this->pdf->Cell(170,0,$value,0,0,'L');
                            $this->pdf->Ln(3);
                            $this->pdf->Cell(10,10,' ',0,0,'L');
                        }
                        //$this->pdf->Cell(170,0,substr($v->contenido,0,115),0,0,'L');
                        $this->pdf->Ln(8-count($lineas)*3);
                        $this->pdf->SetFont('Times','B',16);
                    }
                }
                
                //acta de practicas
                $this->pdf->AddPage('P','A4-3');
                $this->pdf->SetMargins(10,10,20);
                $this->pdf->AliasNbPages();
                $pageWidth = 200;
                $pageHeight = 255;
                $margin = 10;
                $this->pdf->Rect( $margin, $margin , $pageWidth - $margin , $pageHeight-$margin-25);
                $seccion = $data['seccion'];
                $this->pdf->SetTitle($seccion->nombre);
                $capacidades = $data['capacidades'];
                $this->pdf->Rect(10, 10, 7, 20);
                $this->pdf->Rect(17, 10, 70, 20);
                $cap = count($capacidades) == 0 ? 1 : count($capacidades);
                $x = 87;
                $xx = $x;
                $dimension = number_format(86/$cap,0,'','');
                $this->pdf->SetFont('Arial','B',8);
                foreach ($capacidades as $key => $value) {
                    $this->pdf->Rect($x, 10, $dimension, 10);
                    $this->pdf->Text($x+$dimension/3-5, 15, 'CAPACIDAD '.($key+1));
                    $items = $value['items'];
                    $di = number_format($dimension/count($items),0,'','');
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
                $this->pdf->Rect(174, 10, 8, 20);
                $this->pdf->Rect(182, 10, 10, 20);
                $this->pdf->Rect(192, 10, 8, 20);
                $this->pdf->SetFont('Arial','B',8);
                $title = iconv('UTF-8', 'windows-1252', 'N° DE ORDEN');
                $this->pdf->TextWithDirection(15,29.5,  $title,'U');
                $this->pdf->Text(25, 20, 'APELLIDOS Y NOMBRES(Riguroso orden');
                $title = iconv('UTF-8', 'windows-1252', 'alfabético)');
                $this->pdf->Text(45, 25, $title);
                $this->pdf->TextWithDirection(179,29.5,  'PROMEDIO','U');
                $this->pdf->SetFont('Arial','B',6);
                $title = iconv('UTF-8', 'windows-1252', 'EVALUACIÓN DE');
                $this->pdf->TextWithDirection(187,29.5,  $title,'U');
                $title = iconv('UTF-8', 'windows-1252', 'RECUPERACIÓN');
                $this->pdf->TextWithDirection(190,29.5,  $title,'U');
                $this->pdf->SetFont('Arial','B',8);
                $this->pdf->TextWithDirection(195,29.5,  'PROMEDIO','U');
                $this->pdf->TextWithDirection(198,29.5,  'FINAL','U');
                //$this->pdf->Cell(40,0,'APELLIDOS Y NOMBRES',0,0,'C');
                $alumnos = $data['alumnos'];
                $y = 30;
                $this->pdf->Ln(22);
                $this->pdf->SetFont('Arial','',8);
                $yy = 30;
                $xx = 87;
                for($i = 0; $i < 50; $i++){
                    $this->pdf->Rect(10, $y+4*$i, 7, 4);
                    $this->pdf->Rect(17, $y+4*$i, 70, 4);
                    $this->pdf->Cell(7,0,($i+1),0,0,'C');
                    if(isset($alumnos[$i])){
                        $title = iconv('UTF-8', 'windows-1252', $alumnos[$i]->apell_pat.' '.$alumnos[$i]->apell_mat.', '.$alumnos[$i]->nombre);
                        $this->pdf->Cell(71,0,$title,0,0,'L');
                        //$this->pdf->Cell(183,0,' ',0,0,'L');
                        //$this->pdf->Cell(7,0,$alumnos[$i]->valor_nota,0,0,'L');
                        if((int)$alumnos[$i]->valor_nota < 13)
                            $this->pdf->SetTextColor(255,0,0);
                        else
                            $this->pdf->SetTextColor(0,0,0);
                        $this->pdf->Text(177, $y+4*$i+3, $this->completardigitos($alumnos[$i]->valor_nota,2));
                            $this->pdf->SetTextColor(0,0,0);
                        $this->pdf->Text(185, $y+4*$i+3, $alumnos[$i]->id_recuperacion != 0 ? $this->completardigitos($alumnos[$i]->valor_nota_recuperacion,2) : '');
                        if((int)$alumnos[$i]->valor_nota < (int)$alumnos[$i]->eval_minima && $alumnos[$i]->id_recuperacion == 0)
                            $this->pdf->SetTextColor(255,0,0);
                        else
                            $this->pdf->SetTextColor(0,0,0);
                        $this->pdf->Text(195, $y+4*$i+3,$alumnos[$i]->id_recuperacion != 0 ? $this->completardigitos($alumnos[$i]->valor_nota_recuperacion,2) : $this->completardigitos($alumnos[$i]->valor_nota,2));
                        $this->pdf->SetTextColor(0,0,0);
                    }
                    foreach ($capacidades as $key => $value) {
                        //$this->pdf->Rect($xx, $yy, $dimension, 10);
                        $items = $value['items'];
                        $di = number_format($dimension/count($items),0,'','');
                        foreach ($items as $k => $v) {
                            if(isset($alumnos[$i])){
                                $notas = $alumnos[$i]->notas;
                                if($k == count($items)-1)
                                    $l = $dimension - $di*(count($items) -1);
                                else
                                    $l = $di;
                                if((int)$notas[$v->id] < (int)$alumnos[$i]->eval_minima)
                                    $this->pdf->SetTextColor(255,0,0);
                                else
                                    $this->pdf->SetTextColor(0,0,0);
                                $this->pdf->Cell($l,0,isset($notas[$v->id]) ? $this->completardigitos($notas[$v->id],2) : '',0,0,'L');
                                $this->pdf->SetTextColor(0,0,0);
                            }
                            //$this->pdf->Text($xx+$di/3, $yy, 'I'.($k+1));
                            if($k == count($items)-1){
                                $this->pdf->Rect($xx, $yy, $dimension - $di*(count($items)-1), 4);
                                $xx+=number_format($dimension - $di*(count($items)-1),0,'','');
                            }
                            else{
                                $this->pdf->Rect($xx, $yy, $di, 4);
                                $xx+=number_format($di,0,'','');
                            }
                        }
                        $x+=number_format($dimension,0,'','');
                    }
                    $this->pdf->Rect($xx, $yy, 8, 4); $xx += 8;
                    $this->pdf->Rect($xx, $yy, 10, 4); $xx += 10;
                    $this->pdf->Rect($xx, $yy, 8, 4);
                    $yy += 4;
                    $xx = 87;
                    $this->pdf->Ln(4);
                }
                $this->pdf->Line(145, 270, 195, 270);
                $this->pdf->Text(160, 273, 'Firma del docente');
            }
            
            //Cuadro de acta de evaluacion final
            $this->pdf->AddPage('P','A4-3');
            $this->pdf->SetMargins(10,10,20);
            $pageWidth = 200;
            $pageHeight = 287;
            $margin = 10;
            $this->pdf->Rect( $margin, $margin , $pageWidth - $margin , 63);
            //seccion
            $seccion = $data['seccion'];
            $this->pdf->SetTitle($seccion->nombre);
            $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-ministerio'],11,11,65);//logo del documento
            $this->pdf->Image(BASEPATH.'../assets/assets/img/'.$this->parameters['logo-grau'],140,11,50,15);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Ln(18);
            $title = iconv('UTF-8', 'windows-1252', 'ACTA DE EVALUACIÓN FINAL DE LA UNIDAD DIDÁCTICA');
            $this->pdf->Cell(0,0,$title,0,0,'C');
            $this->pdf->Rect($margin, $margin, $pageWidth-$margin, 21);
            $this->pdf->Rect($margin, $margin+21, $pageWidth-$margin, 6);
            $this->pdf->Ln(6);
            $title = iconv('UTF-8', 'windows-1252', 'INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO "MORROPON"');
            $this->pdf->Cell(0,0,$title,0,0,'C');
            $this->pdf->Rect($margin, $margin+27, 60, 6);
            $this->pdf->Ln(6);
            $title = iconv('UTF-8', 'windows-1252', 'Carrera Profesional: ');
            $this->pdf->Cell(60,0,$title,0,0,'L');
            $this->pdf->Rect($margin+60, $margin+27, 130, 6);
            $this->pdf->SetFont('Arial','',10);
            $title = iconv('UTF-8', 'windows-1252', $especialidad->nombre);
            $this->pdf->Cell(130,0,$title,0,0,'L');
            $this->pdf->Ln(6);
            $this->pdf->Rect($margin, $margin+33, 60, 6);
            $this->pdf->Rect($margin+60, $margin+33, 40, 6);
            $this->pdf->Rect($margin+100, $margin+33, 50, 6);
            $this->pdf->Rect($margin+150, $margin+33, 40, 6);
            $this->pdf->SetFont('Arial','B',10);
            $title = iconv('UTF-8', 'windows-1252', 'Semestre: ');
            $this->pdf->Cell(60,0,$title,0,0,'L');
            $semestre = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI'];
            $title = iconv('UTF-8', 'windows-1252', $semestre[$malla->id_ciclo]);
            $this->pdf->Cell(40,0,$title,0,0,'L');
            $title = iconv('UTF-8', 'windows-1252', 'Sección:');
            $this->pdf->Cell(50,0,$title,0,0,'C');
            $this->pdf->SetFont('Arial','',10);
            $title = iconv('UTF-8', 'windows-1252', 'Única');
            $this->pdf->Cell(40,0,$title,0,0,'C');
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Rect($margin, $margin+39, 60, 6);
            $this->pdf->Rect($margin+60, $margin+39, 130, 6);
            $this->pdf->Ln(6);
            $title = iconv('UTF-8', 'windows-1252', 'Turno');
            $this->pdf->Cell(60,0,$title,0,0,'L');
            $this->pdf->SetFont('Arial','',10);
            $title = iconv('UTF-8', 'windows-1252', $turno->nombre);
            $this->pdf->Cell(130,0,$title,0,0,'L');
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Rect($margin, $margin+45, 60, 6);
            $this->pdf->Rect($margin+60, $margin+45, 130, 6);
            $this->pdf->Ln(6);
            $title = iconv('UTF-8', 'windows-1252', 'Unidad Didáctica: ');
            $this->pdf->Cell(60,0,$title,0,0,'L');
            $this->pdf->SetFont('Arial','',10);
            $title = iconv('UTF-8', 'windows-1252', $curso->nombre);
            $this->pdf->Cell(130,0,$title,0,0,'L');
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Rect($margin, $margin+51, 60, 6);
            $this->pdf->Rect($margin+60, $margin+51, 130, 6);
            $this->pdf->Ln(6);
            $title = iconv('UTF-8', 'windows-1252', 'Créditos: ');
            $this->pdf->Cell(60,0,$title,0,0,'L');
            $this->pdf->SetFont('Arial','',10);
            $title = iconv('UTF-8', 'windows-1252', $curso->creditos);
            $this->pdf->Cell(130,0,$title,0,0,'L');
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Rect($margin, $margin+57, 60, 6);
            $this->pdf->Rect($margin+60, $margin+57, 130, 6);
            $this->pdf->Ln(6);
            $title = iconv('UTF-8', 'windows-1252', 'Docente: ');
            $this->pdf->Cell(60,0,$title,0,0,'L');
            $this->pdf->SetFont('Arial','',10);
            $title = iconv('UTF-8', 'windows-1252', $profesor->apell_pat.' '.$profesor->apell_mat.' '.$profesor->nombre);
            $this->pdf->Cell(130,0,$title,0,0,'L');
            $this->pdf->SetFont('Arial','B',8);
            
            //AQUI COMIENZA LA LISTA DE ALUMNOS
            $this->pdf->Rect( $margin, $margin+65 , $pageWidth - $margin , $pageHeight-$margin-65);
            $this->pdf->Rect($margin, $margin+65, 6, 12);
            $this->pdf->Rect($margin+6, $margin+65, 25, 12);
            $this->pdf->Rect($margin+31, $margin+65, 20, 12);
            $this->pdf->Rect($margin+51, $margin+65, 70, 12);
            $this->pdf->Rect($margin+121, $margin+65, 69, 4);
            $this->pdf->Rect($margin+121, $margin+69, 40, 4);
            $this->pdf->Rect($margin+161, $margin+69, 29, 8);
            $this->pdf->Rect($margin+121, $margin+73, 15, 4);
            $this->pdf->Rect($margin+136, $margin+73, 25, 4);
            $this->pdf->Ln(11);
            $title = iconv('UTF-8', 'windows-1252', 'N°');
            $this->pdf->Cell(6,0,$title,0,0,'C');
            $title = iconv('UTF-8', 'windows-1252', 'COD. MAT.');
            $this->pdf->Cell(25,0,$title,0,0,'C');
            $title = iconv('UTF-8', 'windows-1252', 'DNI');
            $this->pdf->Cell(20,0,$title,0,0,'C');
            $title = iconv('UTF-8', 'windows-1252', 'APELLIDOS Y NOMBRE');
            $this->pdf->Cell(70,0,$title,0,0,'C');
            $this->pdf->SetXY($pageWidth-$margin-40,$margin+67);
            $title = iconv('UTF-8', 'windows-1252', 'EVALUACION FINAL');
            $this->pdf->Cell(40,0,$title,0,0,'C');
            $this->pdf->SetXY($pageWidth-$margin-54,$margin+71);
            $title = iconv('UTF-8', 'windows-1252', 'LOGRO FINAL');
            $this->pdf->Cell(29,0,$title,0,0,'C');
            $this->pdf->SetXY($pageWidth-$margin-59,$margin+75);
            $title = iconv('UTF-8', 'windows-1252', 'NUMERO');
            $this->pdf->Cell(15,0,$title,0,0,'C');
            $this->pdf->SetXY($pageWidth-$margin-44,$margin+75);
            $title = iconv('UTF-8', 'windows-1252', 'LITERAL');
            $this->pdf->Cell(25,0,$title,0,0,'C');
            $this->pdf->SetXY($pageWidth-$margin-18,$margin+73);
            $title = iconv('UTF-8', 'windows-1252', 'PUNTAJE');
            $this->pdf->Cell(25,0,$title,0,0,'C');
            $this->pdf->Ln(5.5);
            $alumnos = $data['alumnos'];
            $notas = [
                '0'     => 'cero',
                '1'     => 'uno',
                '2'    => 'dos',
                '3'    => 'tres',
                '4'    => 'cuatro',
                '5'    => 'cinco',
                '6'    => 'seis',
                '7'    => 'siete',
                '8'    => 'ocho',
                '9'    => 'nueve',
                '00'    => 'cero',
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
            $i = 77;
            $a = 0;
            while($a < 50){
                $this->pdf->Rect($margin, $margin+$i, 6, 3);
                $this->pdf->Rect($margin+6, $margin+$i, 25, 3);
                $this->pdf->Rect($margin+31, $margin+$i, 20, 3);
                $this->pdf->Rect($margin+51, $margin+$i, 70, 3);
                $this->pdf->Rect($margin+121, $margin+$i, 15, 3);
                $this->pdf->Rect($margin+136, $margin+$i, 25, 3);
                $this->pdf->Rect($margin+161, $margin+$i, 29, 3);
                $this->pdf->Cell(6,0,($a+1),0,0,'C');
                $this->pdf->SetFont('Arial','',8);
                if(isset($alumnos[$a])){
                    $this->pdf->Cell(25,0,$alumnos[$a]->dni,0,0,'C');
                    $this->pdf->Cell(20,0,$alumnos[$a]->dni,0,0,'C');
                    $title = iconv('UTF-8', 'windows-1252', $alumnos[$a]->apell_pat.' '.$alumnos[$a]->apell_mat.' '.$alumnos[$a]->nombre);
                    $this->pdf->Cell(70,0,$title,0,0,'L');
                    if($alumnos[$a]->id_recuperacion == 0){
                        if((int)$alumnos[$a]->valor_nota < (int)$alumnos[$a]->eval_minima)
                            $this->pdf->SetTextColor(255,0,0);
                        else
                            $this->pdf->SetTextColor(0,0,0);
                        $this->pdf->Cell(15,0,$this->completardigitos($alumnos[$a]->valor_nota,2),0,0,'C');
                        $this->pdf->Cell(25,0,isset($notas[$alumnos[$a]->valor_nota]) ? $notas[$alumnos[$a]->valor_nota] : '-',0,0,'L');
                        $this->pdf->Cell(25,0,  number_format((int)$alumnos[$a]->valor_nota*(int)$curso->creditos,0).'.00',0,0,'C');
                        $this->pdf->SetTextColor(0,0,0);
                    }
                    else{
                        $this->pdf->Cell(15,0,$alumnos[$a]->valor_nota_recuperacion,0,0,'C');
                        $this->pdf->Cell(25,0,isset($notas[$alumnos[$a]->valor_nota_recuperacion]) ? $notas[$alumnos[$a]->valor_nota_recuperacion] : '-',0,0,'L');
                        $this->pdf->Cell(25,0,  number_format((int)$alumnos[$a]->valor_nota_recuperacion*(int)$curso->creditos,0).'.00',0,0,'C');
                    }
                }
                $this->pdf->SetFont('Arial','B',8);
                $i+=3;
                $a++;
                $this->pdf->Ln(3);
            }
            $this->pdf->Ln(8);
            $this->pdf->Cell(10,0,'',0,0,'C');
            $this->pdf->Cell(80,0,'LUGAR Y FECHA: ',0,0,'L');
            $this->pdf->SetFont('Arial','',8);
            $title = iconv('UTF-8', 'windows-1252', $this->parameters['lugar-instituto'].', '.date('d').' de '.$this->meses[date('m')].' de '.date('Y'));
            $this->pdf->Cell(80,0,$title,0,0,'L');
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
            $this->pdf->Close();
            $this->pdf->Output(base_url()."tmp/".$seccion->nombre.".pdf", 'I');
        }
        
    public function imprimirActaGrupal($data = array()){
        //$this->pdf = new Pdf();
        $this->pdf->AddPage('P','A4-H');
        $this->pdf->SetMargins(10,10,20);
        $this->pdf->AliasNbPages();
        $pageWidth = 287;
        $pageHeight = 200;
        $margin = 10;
        $this->pdf->Rect( $margin, $margin , $pageWidth - $margin , $pageHeight - $margin);
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
        foreach ($cursos as $key => $value) if($key < 12) {
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
        $title = iconv('UTF-8', 'windows-1252', 'NÚMERO DE UNIDADES DIDÁCTICAS APROBADAS');
        $this->pdf->TextWithDirection(205,99,  $title,'U'); 
        $title = iconv('UTF-8', 'windows-1252', 'NÚMERO DE UNIDADES DIDÁCTICAS');
        $this->pdf->TextWithDirection(212,99,  $title,'U'); 
        $this->pdf->TextWithDirection(215,99,  'DESAPROBADAS','U'); 
        $this->pdf->TextWithDirection(222,99,  'PUNTAJE PROMEDIO PONDERADO','U'); 
        $this->pdf->SetFont('Arial','B',9);
        $esp = 'CARRERA: '. $especialidad->especialidad;
        $title = $this->partir_string( iconv('UTF-8', 'windows-1252', $esp) , 30);
        $cont = 0;
        foreach ($title as $value) {
            $this->pdf->Text(227,33+$cont*4,  $value); 
            $cont ++;
        }
        
        $ciclo_romano = ['','I','II','III','IV','V','VI'];
        $title = iconv('UTF-8', 'windows-1252', 'MENCIÓN: ');
        $this->pdf->Text(227,53, $title); 
        $title = iconv('UTF-8', 'windows-1252', 'SECCIÓN: UNICA');
        $this->pdf->Text(227,60, $title); 
        $title = iconv('UTF-8', 'windows-1252', 'PERIODO ACADÉMICO:     '.($periodo[1] == '01' ? 'I' : 'II').' - '.$periodo[0]);
        $this->pdf->Text(227,67, $title); 
        $title = iconv('UTF-8', 'windows-1252', 'SEMESTRE ACADÉMICO:      '.$ciclo_romano[$data['filtro']['id_ciclo']]);
        $this->pdf->Text(227,74, $title); 
        $title = iconv('UTF-8', 'windows-1252', 'TURNO: '.$data['turno']->nombre);
        $this->pdf->Text(227,81, $title); 
        $title = iconv('UTF-8', 'windows-1252', 'OBSERVACIONES');
        $this->pdf->Text(237,93, $title); 
        $this->pdf->SetFont('Arial','B',9);
        $this->pdf->Text(144, 91, 'CREDITOS');
        $this->pdf->Ln(32);
        $aluns = $data['alumnos'];
        $this->pdf->SetFont('Arial','B',6);
        $notas = $data['notas'];
        /*echo '<pre>';
        print_r($cursos);
        echo '</pre>';
        exit();*/
        for($i = 0; $i < 25; $i++){
            $this->pdf->Rect( 10, 100+($i*4) , 7 , 4);
            $this->pdf->Rect( 17, 100+($i*4) , 20 , 4);
            $this->pdf->Rect( 37, 100+($i*4) , 68 , 4);
            $this->pdf->Rect( 105, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 113, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 121, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 129, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 137, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 145, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 153, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 161, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 169, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 177, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 185, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 193, 100+($i*4) , 8 , 4);
            $this->pdf->Cell(7,0,($i+1),0,0,'L');
            $cant_notas_aprob = 0;
            $cant_notas_desp = 0;
            $puntaje = 0;
            $suma_creditos = 0;
            if(isset($aluns[$i])){
                $this->pdf->SetTextColor(0,0,0);
                $this->pdf->SetFont('Arial','B',7);
                $this->pdf->Cell(20,0,$aluns[$i]->dni,0,0,'C');
                $title = iconv('UTF-8', 'windows-1252', $aluns[$i]->apell_pat.' '.$aluns[$i]->apell_mat.', '.$aluns[$i]->nombre);
                $this->pdf->Cell(68,0,$title,0,0,'L');
                $nts = $notas[$aluns[$i]->codigo];
                foreach ($nts as $k => $v) {
                    if(isset($v->valor_nota)){
                        if($v->tipo_eval == 1){
                            if((int)$v->valor_nota < $v->eval_minima)
                                $cant_notas_desp += 1;
                            else
                                $cant_notas_aprob += 1;
                        }else{
                            if($v->valor_nota > $v->eval_minima)
                                $cant_notas_desp += 1;
                            else
                                $cant_notas_aprob += 1;
                        }
                        //$cant_notas_desp += 1;
                    }
                    if($v->tipo_eval == 1){
                        $puntaje += isset($v->valor_nota) ? (int)$v->valor_nota*$cursos[$k]->creditos : 0;
                        $suma_creditos += isset($v->valor_nota) ? $cursos[$k]->creditos : 0;
                    }
                }
                for($a = 0; $a < 12; $a++) {
                    if(isset($nts[$a]->valor_nota)){
                        if($nts[$a]->tipo_eval == 1){
                            if((int)$nts[$a]->valor_nota < $nts[$a]->eval_minima)
                                $this->pdf->SetTextColor(255,0,0);
                            else
                                $this->pdf->SetTextColor(0,0,0);
                        }else{
                            if($nts[$a]->valor_nota > $nts[$a]->eval_minima)
                                $this->pdf->SetTextColor(255,0,0);
                            else
                                $this->pdf->SetTextColor(0,0,0);
                        }
                        //$this->pdf->SetTextColor(255,0,0);
                    }else
                        $this->pdf->SetTextColor(0,0,0);
                    $this->pdf->Cell(8,0,isset($nts[$a]->valor_nota) ? $this->completardigitos($nts[$a]->valor_nota,2) : ' ',0,0,'C');
                }
                $this->pdf->SetTextColor(0,0,0);
            $this->pdf->Cell(8,0,$cant_notas_aprob == 0 ? '0' : $cant_notas_aprob,0,0,'C');
            $this->pdf->Cell(8,0,$cant_notas_desp == 0 ? '0' : $cant_notas_desp,0,0,'C');
            $this->pdf->Cell(8,0,$puntaje == 0 ? '0' : number_format($puntaje/$suma_creditos,2,'.',''),0,0,'C');
            }
            $this->pdf->Rect( 201, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 209, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 217, 100+($i*4) , 8 , 4);
            $this->pdf->Rect( 225, 100+($i*4) , 62 , 4);
            $puntaje = 0;
            $cant_notas_desp = 0;
            $cant_notas_aprob = 0;
            $this->pdf->SetFont('Arial','B',6);
            $this->pdf->Ln(4);
        }
        //if(count($aluns) > 20){
            $this->pdf->AddPage('P','A4-H');
            $this->pdf->SetMargins(10,10,20);
            $this->pdf->AliasNbPages();
            $pageWidth = 287;
            $pageHeight = 175;
            $margin = 10;
            $this->pdf->Rect( $margin, $margin , $pageWidth - $margin , $pageHeight - $margin);
            $this->pdf->Rect( 10, 10 , 7 , 65);
            $this->pdf->Rect( 17, 10 , 20 , 65);
            $this->pdf->Rect( 37, 10 , 68 , 65);
            $this->pdf->Rect( 105, 10 , 120 , 8);
            for ($i=0; $i < 12; $i++) { 
                $this->pdf->Rect( 105+$i*8, 18 , 8 , 43);
                $this->pdf->Rect( 105+$i*8, 68 , 8 , 7);
            }
            $this->pdf->Rect( 105, 61 , 96 , 7);
            $this->pdf->Rect( 201, 18 , 8 , 57);
            $this->pdf->Rect( 209, 18 , 8 , 57);
            $this->pdf->Rect( 217, 18 , 8 , 57);
            $this->pdf->Rect( 225, 10 , 62 , 65);
            $this->pdf->SetFont('Arial','B',6);
            $title = iconv('UTF-8', 'windows-1252', 'N°');
            $this->pdf->Text(13,42, $title); 
            $title = iconv('UTF-8', 'windows-1252', 'Número de');
            $this->pdf->Text(21,39, $title); 
            $title = iconv('UTF-8', 'windows-1252', 'documento de');
            $this->pdf->Text(19,42, $title); 
            $title = iconv('UTF-8', 'windows-1252', 'identidad');
            $this->pdf->Text(22,45, $title); 
            $title = iconv('UTF-8', 'windows-1252', 'APELLIDOS Y NOMBRES');
            $this->pdf->Text(55,41, $title); 
            $title = iconv('UTF-8', 'windows-1252', '(En orden alfabético)');
            $this->pdf->Text(57,44, $title); 
            $this->pdf->SetFont('Arial','B',6);
            $title = iconv('UTF-8', 'windows-1252', 'UNIDADES DIDÁCTICAS');
            $this->pdf->Text(149,15, $title); 
            $this->pdf->SetFont('Arial','',6);
            foreach ($cursos as $key => $value) if($key < 12) {
                $nombre = $value->curso;
                if(strlen($nombre) > 70){
                    $this->pdf->TextWithDirection($w+$key*8-2,60,  substr($nombre, 0, 30),'U');   
                    $this->pdf->TextWithDirection($w+$key*8,60,  substr($nombre, 30, 30),'U');
                    $this->pdf->TextWithDirection($w+$key*8+2,60,  substr($nombre, 60),'U');
                    //$this->pdf->Text($w+$key*8, 93, $value->creditos);
                }
                if(strlen($nombre) > 30 && strlen($nombre) <= 70){
                    $this->pdf->TextWithDirection($w+$key*8-1,60,  substr($nombre, 0, 30),'U');   
                    $this->pdf->TextWithDirection($w+$key*8+1,60,  substr($nombre, 30),'U');
                }
                if(strlen($nombre) <= 30){
                    $this->pdf->TextWithDirection($w+$key*8,60,  substr($nombre, 0, 30),'U');   
                }
                $this->pdf->Text($w+$key*8-3, 72, $value->creditos);
                //$suma_creditos += $value->creditos;
            }
            $title = iconv('UTF-8', 'windows-1252', 'NÚMERO DE UNIDADES DIDÁCTICAS APROBADAS');
            $this->pdf->TextWithDirection(205,74,  $title,'U'); 
            $title = iconv('UTF-8', 'windows-1252', 'NÚMERO DE UNIDADES DIDÁCTICAS');
            $this->pdf->TextWithDirection(212,74,  $title,'U'); 
            $this->pdf->TextWithDirection(215,74,  'DESAPROBADAS','U'); 
            $this->pdf->TextWithDirection(223,74,  'PUNTAJE PROMEDIO PONDERADO','U'); 
            $title = iconv('UTF-8', 'windows-1252', 'OBSERVACIONES');
            $this->pdf->Text(248,40, $title); 
            //$this->pdf->Ln(33);
            $this->pdf->SetXY(10,77);
            for($i = 0; $i < 25; $i++){
                $this->pdf->Rect( 10, 75+($i*4) , 7 , 4);
                $this->pdf->Rect( 17, 75+($i*4) , 20 , 4);
                $this->pdf->Rect( 37, 75+($i*4) , 68 , 4);
                $this->pdf->Rect( 105, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 113, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 121, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 129, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 137, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 145, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 153, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 161, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 169, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 177, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 185, 75+($i*4) , 8 , 4);
                $this->pdf->Rect( 193, 75+($i*4) , 8 , 4);
                    $this->pdf->SetFont('Arial','B',7);
                $this->pdf->Cell(7,0,($i+26),0,0,'C');
                $cant_notas_aprob = 0;
                $cant_notas_desp = 0;
                $puntaje = 0;
                if(isset($aluns[$i+25])){
                    $this->pdf->SetTextColor(0,0,0);
                    $this->pdf->Cell(21,0,$aluns[$i+25]->dni,0,0,'C');
                    $title = iconv('UTF-8', 'windows-1252', $aluns[$i+25]->apell_pat.' '.$aluns[$i+25]->apell_mat.', '.$aluns[$i+25]->nombre);
                    $this->pdf->Cell(68,0,$title,0,0,'L');
                    $nts = $notas[$aluns[$i+21]->codigo];
                    foreach ($nts as $k => $v) {
                        if(isset($v->valor_nota)){
                            if($v->tipo_eval == 1){
                                if((int)$v->valor_nota < $v->eval_minima)
                                    $cant_notas_desp += 1;
                                else
                                    $cant_notas_aprob += 1;
                            }else{
                                if($v->valor_nota > $v->eval_minima)
                                    $cant_notas_desp += 1;
                                else
                                    $cant_notas_aprob += 1;
                            }
                            //$cant_notas_desp += 1;
                        }
                        if($v->tipo_eval == 1){
                            $puntaje += isset($v->valor_nota) ? (int)$v->valor_nota*$cursos[$k]->creditos : 0;
                            $suma_creditos += isset($v->valor_nota) ? $cursos[$k]->creditos : 0;
                        }
                        /*if(isset($v->valor_nota) && (int)$v->valor_nota < 13){
                            $cant_notas_desp += 1;
                        }else{
                            $cant_notas_aprob += 1;
                        }
                        $puntaje += isset($v->valor_nota) ? (int)$v->valor_nota*$cursos[$k]->creditos : 0;*/
                    }
                    for($a = 0; $a < 12; $a++) {
                        if(isset($nts[$a]->valor_nota)){
                            if($nts[$a]->tipo_eval == 1){
                                if((int)$nts[$a]->valor_nota < $nts[$a]->eval_minima)
                                    $this->pdf->SetTextColor(255,0,0);
                                else
                                    $this->pdf->SetTextColor(0,0,0);
                            }else{
                                if($nts[$a]->valor_nota > $nts[$a]->eval_minima)
                                    $this->pdf->SetTextColor(255,0,0);
                                else
                                    $this->pdf->SetTextColor(0,0,0);
                            }
                            //$this->pdf->SetTextColor(255,0,0);
                        }else
                            $this->pdf->SetTextColor(0,0,0);
                        $this->pdf->Cell(8,0,isset($nts[$a]->valor_nota) ? $this->completardigitos($nts[$a]->valor_nota,2) : ' ',0,0,'C');
                        /*if(isset($nts[$a]->valor_nota) && (int)$nts[$a]->valor_nota < 13){
                            $this->pdf->SetTextColor(255,0,0);
                        }
                        else
                            $this->pdf->SetTextColor(0,0,0);
                        $this->pdf->Cell(8,0,isset($nts[$a]->valor_nota) ? $this->completardigitos($nts[$a]->valor_nota,2) : ' ',0,0,'C');*/
                    }
                    $this->pdf->SetTextColor(0,0,0);
                    $this->pdf->Cell(8,0,$cant_notas_aprob == 0 ? '0' : $cant_notas_aprob,0,0,'C');
                    $this->pdf->Cell(7,0,$cant_notas_desp == 0 ? '0' : $cant_notas_desp,0,0,'C');
                    $this->pdf->Cell(8,0,$puntaje == 0 ? '0' : number_format($puntaje/$suma_creditos,2,'.',''),0,0,'C');
                $puntaje = 0;
                $cant_notas_desp = 0;
                $cant_notas_aprob = 0;
                }
                    $this->pdf->Rect( 201, 75+($i*4) , 8 , 4);
                    $this->pdf->Rect( 209, 75+($i*4) , 8 , 4);
                    $this->pdf->Rect( 217, 75+($i*4) , 8 , 4);
                    $this->pdf->Rect( 225, 75+($i*4) , 62 , 4);
                $this->pdf->Ln(4);
            }
        //}

            //$this->pdf->Rect( 60, 195 , 7 , 5);
            $fecha = explode('-', $data['periodo']->fch_fin);

            $this->pdf->Text(130,210,'Morropon, '.$fecha[2].' de '.$this->meses[$fecha[1]].' de '.$fecha[0]);
            $this->pdf->Close();
            $this->pdf->Output(base_url()."tmp/".date('Y-m-d').".pdf", 'I');
    }

    public function completardigitos($char,$length = 2){
        if(is_numeric($char))
            return str_repeat('0', $length-strlen($char)).$char;
        else
            return $char;
    }
}
?>