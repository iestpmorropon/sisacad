<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumnos extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->config->load('parameters',true);
		$this->load->library('form_validation');
		$this->parameters = $this->config->item('parameters');
		$this->load->model('Usuario','usuario');
		$this->load->model('Especialidad','esp');
		$this->load->model('Alumno','alumno');
		$this->load->model('Persona','persona');
		$this->load->model('Seccion','seccion');
		$this->load->model('Periodo','periodo');
        $this->load->model('Evaluaciones','evaluaciones');
            $this->load->model('Pagos','pagos');
		if(!$this->session->userdata('usuario'))
			header('Location: '.base_url());
	}

    public function alumnos(){
            $data = [
                'parameters'        => $this->parameters,
                'usuario'           => $this->session->userdata('usuario'),
                'module'            => 'Alumnos',
                'content'           => $this->load->view('alumnos/alumnos',[
                    'parameters'        => $this->parameters,
                    'usuario'           => $this->session->userdata('usuario')
                    ],true)
            ];
            $this->load->view('body',$data);
    }

    public function gestionmatricula(){
        $especialidades = $this->esp->getEspecialidades();
        $data = [
            'parameters'        => $this->parameters,
            'usuario'           => $this->session->userdata('usuario'),
            'module'            => 'Matricula',
            'content'           => $this->load->view('alumnos/gestionmatricula',[
                'parameters'        => $this->parameters,
                'especialidades'    => $especialidades,
                'tipoalumno'        => 'regular',
                'usuario'           => $this->session->userdata('usuario')
                ],true)
        ];
        $this->load->view('body',$data);
    }

    public function imprimecertificado($cod_alumno = '',$id_especialidad_alumno = 0){
        if($cod_alumno == '' || $id_especialidad_alumno == 0)
            header('Location: '.base_url());
        $this->load->library('constancias',$this->parameters);
        $informacion = $this->alumno->getAlumnoInf($cod_alumno,$id_especialidad_alumno);
        $alumno = $this->alumno->getDataAlumno(['codigo'=>$cod_alumno]);
        $especialidad_periodo = $this->esp->getEspecialidadesPeriodoForId($informacion->id_especialidad_periodo);
        //$alumno_matricula = $this->
        $malla = $this->esp->getMallaPeriodo($informacion->id_especialidad_periodo);
        $ml = [];
        $modular = 0;
        $modulares = [];
        foreach ($malla as $key => $value) {
            if($value->modular == 1)
                $modular = 1;
            if(isset($ml[$value->id_ciclo])){
                $m = $ml[$value->id_ciclo];
            }else{
                $m = [];
            }
            array_push($m, $value);
            $ml[$value->id_ciclo] = $m;
            $orden = [];
            if(isset($modulares[$value->id_ciclo]))
                $orden = $modulares[$value->id_ciclo];
            if(isset($orden[$value->id_modulo]))
                $curs = $orden[$value->id_modulo];
            else
                $curs = [];
            $curs[$value->id_curso] = $value;
            $orden[$value->id_modulo] = $curs;
            $modulares[$value->id_ciclo] = $orden;
        }
        /*echo '<pre>';
        print_r($ml);
        echo '</pre>';
        exit();*/
        foreach ($ml as $key => $value) {
            //echo 'Ciclo: '.$key.'<br>';//
            $alumno_matriculas = $this->alumno->getAlumnoMatriculaCertificado($cod_alumno,$key,$especialidad_periodo->id_especialidad,$especialidad_periodo->id_periodo);
            $id_alumno_matricula = 0;
            //recorriendo alumnos_matricula
            if(!is_numeric($alumno_matriculas))
                foreach ($alumno_matriculas as $als => $alms) {
                    $band = false;
                    //recorriendo cursos
                    foreach ($value as $c => $cr){
                        $consulta = $this->alumno->getCursosBusquedaCertificado($alms->id,$cr->id_curso);
                        if(!is_numeric($consulta))
                            $band = true;
                        else
                            $band = false;
                        if(!$band)
                            break;
                    }
                    if($band){
                        $id_alumno_matricula = $alms->id;
                    }
                }
            $alumno_matricula = $this->alumno->getCursosMatriculaAlumnoCertificado_($id_alumno_matricula);
            if(!is_numeric($alumno_matricula)){
                foreach ($alumno_matricula as $k => $v) if($index = $this->buscarCurso($v->id_curso,$value) != -1) {
                    if($v->estado == 1){
                        $ml[$key][$k]->nota = $v;
                    }else{
                        if($v->estado == 0){
                            $nota_no_regular = $this->evaluaciones->getAlumnoNotaNoRegularCertificado($v->id_curso,$cod_alumno,1);
                            if(!is_numeric($nota_no_regular) && $nota_no_regular->estado == 1)
                                $ml[$key][$k]->nota = $nota_no_regular;
                            else{
                                $ml[$key][$k]->nota = $v;
                            }
                        }
                    }
                }
            }else{
                foreach ($value as $ky => $val) {
                    $nota_no_regular_aux = $this->evaluaciones->getNotaNoRegularForAlumnoAuxCertificado($cod_alumno,$val->id_curso,$especialidad_periodo->id_especialidad,$key);
                    if(!is_numeric($nota_no_regular_aux))
                        $ml[$key][$ky]->nota = $nota_no_regular_aux;
                }
            }
        }
        /*echo '<pre>';
        print_r($ml);
        echo '</pre>';
        exit();*/
        $data = [
            'cod_alumno'            => $cod_alumno,
            'informacion'           => $informacion,
            'alumno'                => $alumno,
            'malla'                 => $ml,
            'modular'               => $modular,
            'modulares'             => $modulares
        ];
        $this->constancias->cargaCertificado($data);
    }

    public function buscarCurso($id_curso,$cursos){
        foreach ($cursos as $key => $value) {
            if($value->id_curso == $id_curso)
                return $key;
        }
        return -1;
    }
        
        public function index(){
            $especialidades = $this->esp->getEspecialidades();
            $periodos = $this->esp->getPeriodos();
            $data = [
                'parameters'		=> $this->parameters,
                'usuario'			=> $this->session->userdata('usuario'),
                'module'			=> 'Impresos',
                'content'			=> $this->load->view('alumnos/gestion',[
                    'parameters'		=> $this->parameters,
                    'especialidades'            => $especialidades,
                    'periodos'                  => $periodos,
                    'usuario'			=> $this->session->userdata('usuario')
                    ],true)
            ];
            $this->load->view('body',$data);
        }

        public function boletas($cod_alumno = '',$id_especialidad = 0,$id_ciclo = 0,$id_periodo = 0,$id_turno = 0){
        	if($cod_alumno == '' || $id_especialidad == 0 || $id_ciclo == 0 || $id_periodo == 0 || $id_turno == 0)
        		header('Location: '.base_url());
        	$this->load->library('impreactas',$this->parameters);
        	$alumno_especialidad = $this->esp->getAlumnoEspecialidad($cod_alumno);
        	$especialidad_periodo = $this->esp->getEspecialidadesPeriodoForId($alumno_especialidad->id_especialidad_periodo);
        	$especialidad = $this->esp->getEspecialidad($id_especialidad);
        	$periodo = $this->periodo->getPeriodoForId($id_periodo);
        	$alumno = $this->alumno->getDataAlumno(['codigo'=>$cod_alumno]);
        	$cursos = $this->alumno->getCursosMatriculaAlumno_($cod_alumno,$id_periodo,$id_ciclo,$id_especialidad,$id_turno);

        	$turno = $this->esp->getTurno($id_turno);
        	$alumno->codigo = $cod_alumno;
        	$alumno->cursos = $cursos;
        	$alumno->turno = $turno;
        	$alumno->especialidad_periodo = $especialidad_periodo;
        	$curs = [];

        	$data = [
        		'alumnos'				=> [$alumno],
        		'cod_alumno'			=> $cod_alumno,
        		'id_ciclo'				=> $id_ciclo,
        		'especialidad'			=> $especialidad,
        		//'cursos'				=> $cursos,
        		'periodo'				=> $periodo,
        		//'turno'					=> $turno,
        		//'especialidad_periodo'	=> $especialidad_periodo
        	];
        	$this->impreactas->imprimeBoletas($data);
        }

        public function imprimirregistromatriculas(){
            if(!$this->session->userdata('lista_alumnos_grupal'))
                header('Location: '.base_url());
            $this->load->library('impreactas',$this->parameters);
            $data = $this->session->userdata('lista_alumnos_grupal');
            $alumnos = $data['alumnos'];
            $filtro = $data['filtro'];
            $alumno_especialidad = $this->esp->getAlumnoEspecialidad($alumnos[0]->codigo);
            $especialidad_periodo = $this->esp->getEspecialidadesPeriodoForId($alumno_especialidad->id_especialidad_periodo);
            $especialidad = $this->esp->getEspecialidad($filtro['id_especialidad']);
            $periodo = $this->periodo->getPeriodoForId($filtro['id_periodo']);
            $cursos = $this->alumno->getCursosMatriculaAlumno($filtro['id_periodo'],$filtro['id_ciclo'],$filtro['id_especialidad'],$filtro['id_turno']);
            /*foreach ($alumnos as $key => $value) {
                $cursos = $this->alumno->getCursosMatriculaAlumno_($value->codigo,$filtro['id_periodo'],$filtro['id_ciclo'],$filtro['id_especialidad'],$filtro['id_turno']);
                $turno = $this->esp->getTurno($filtro['id_turno']);
                $alumnos[$key]->cursos = $cursos;
                $alumnos[$key]->turno = $turno;
                $alumnos[$key]->especialidad_periodo = $especialidad_periodo;
            }*/
            /*echo '<pre>';
            print_r($alumnos);
            echo '</pre>';
            exit();*/
            $turno = $this->esp->getTurno($filtro['id_turno']);
            $data = [
                'alumnos'               => $alumnos,
                //'cod_alumno'          => $cod_alumno,
                'id_ciclo'              => $filtro['id_ciclo'],
                'periodo'                   => $periodo,
                'turno'                 => $turno,
                'especialidad'          => $especialidad,
                'especialidad_periodo'    => $especialidad_periodo,
                'cursos'                => $cursos
            ];
            $this->impreactas->imprimeRegistroMatricula($data);
        }

        public function imprimirboletasgrupal(){
        	if(!$this->session->userdata('lista_alumnos_grupal'))
                header('Location: '.base_url());

            $this->load->library('impreactas',$this->parameters);
            $data = $this->session->userdata('lista_alumnos_grupal');
            $alumnos = $data['alumnos'];

            $filtro = $data['filtro'];
            $alumno_especialidad = $this->esp->getAlumnoEspecialidad($alumnos[0]->codigo);
	        $especialidad_periodo = $this->esp->getEspecialidadesPeriodoForId($alumno_especialidad->id_especialidad_periodo);
	        $especialidad = $this->esp->getEspecialidad($filtro['id_especialidad']);
	        $periodo = $this->periodo->getPeriodoForId($filtro['id_periodo']);
            foreach ($alumnos as $key => $value) {

	        	$cursos = $this->alumno->getCursosMatriculaAlumno_($value->codigo,$filtro['id_periodo'],$filtro['id_ciclo'],$filtro['id_especialidad'],$filtro['id_turno']);

	        	$turno = $this->esp->getTurno($filtro['id_turno']);
	        	$alumnos[$key]->cursos = $cursos;
	        	$alumnos[$key]->turno = $turno;
	        	$alumnos[$key]->especialidad_periodo = $especialidad_periodo;

            }
            $data = [
        		'alumnos'				=> $alumnos,
        		//'cod_alumno'			=> $cod_alumno,
        		'id_ciclo'				=> $filtro['id_ciclo'],
        		'especialidad'			=> $especialidad,
        		//'cursos'				=> $cursos,
        		'periodo'				=> $periodo,
        		//'turno'					=> $turno,
        		//'especialidad_periodo'	=> $especialidad_periodo
        	];
        	$this->impreactas->imprimeBoletas($data);
        }
        
        public function alumno($codigo){
            //$alumno = $this->alumno
            $data = [
                'parameters'		=> $this->parameters,
                'usuario'			=> $this->session->userdata('usuario'),
                'module'			=> 'Personal',
                'content'			=> $this->load->view('alumnos/alumno',[
                    'parameters'		=> $this->parameters,
                    'usuario'			=> $this->session->userdata('usuario')
                    ],true)
            ];
            $this->load->view('body',$data);
        }
        
        public function getGruposEspecialidad(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $grupos = $this->alumno->getGrupoForEespecialidad($this->input->post('id_especialidad'));
            echo json_encode(['status'=>200,'data'=>$grupos,'message'=>'Datos encontrados']);
        }

        public function getEspecialidades(){
        	if(!$this->input->post())
                header('Location: '.base_url());
            $especialidades = $this->periodo->getEspecialidadesForPeriodo($this->input->post('id_periodo'),$this->input->post('id_turno'));
            if(!is_numeric($especialidades))
            	echo json_encode(['status'=>200,'data'=>$especialidades,'message'=>'Resultados']);
            else
            	echo json_encode(['status'=>202,'data'=>[],'message'=>'No existen especialidades aperturadas en este periodo']);
        }

        public function getCiclosEspecialidadPeriodo(){
        	if(!$this->input->post())
                header('Location: '.base_url());
            $ciclos = $this->periodo->getCiclosForEspecialidadPeriodoTurno($this->input->post('id_periodo'),$this->input->post('id_especialidad'),$this->input->post('id_turno'));
            if(!is_numeric($ciclos))
            	echo json_encode(['status'=>200,'data'=>$ciclos,'message'=>'Resultados']);
            else
            	echo json_encode(['status'=>202,'data'=>[],'message'=>'No hay ciclos aperturados.']);
        }
        
        public function traeGrupoAlumnos(){
            if(!$this->input->post())
                header('Location: '.base_url());
            /*$where = [
            	'id_periodo'			=> $this->input->post('id_periodo'),
            	'id_turno'				=> $this->input->post('id_turno'),
            	'id_especialidad'		=> $this->input->post('id_especialidad')
            ];*/
            $alums = [];
            $conjunto = [];
            if(!$this->input->post('id_especialidad')){
            	$especialidades = $this->periodo->getEspecialidadesForPeriodo($this->input->post('id_periodo'));
            }
            else{
            	$o = new stdClass();
            	$o->id_especialidad = $this->input->post('id_especialidad');
            	$especialidades = [$o];
            }
            $esps = [];
        	foreach ($especialidades as $key => $value) {
        		$turs = [];
        		if(!$this->input->post('id_turno'))
        			$turnos = $this->periodo->getTurnosForEspecialidadPeriodo($this->input->post('id_periodo'),$value->id_especialidad);
        		else{
        			$o = new stdClass();
        			$o->id_turno = $this->input->post('id_turno');
        			$turnos = [$o];
        		}
        		$turns = [];
        		foreach ($turnos as $k => $v) {
        			if(!$this->input->post('id_ciclo'))
        				$ciclos = $this->periodo->getCiclosForEspecialidadPeriodoTurno($this->input->post('id_periodo'),$value->id_especialidad,$v->id_turno);
        			else{
        				$o = new stdClass();
            			$o->id_ciclo = $this->input->post('id_ciclo');
            			$ciclos = [$o];
        			}
        			$cicls = [];
        			foreach ($ciclos as $ky => $val) {
        				//print_r($ciclos);
	        			$where = [
	        				'id_periodo' 				=> $this->input->post('id_periodo'),
	        				'id_turno'					=> $v->id_turno,
	        				'id_especialidad'			=> $value->id_especialidad,
	        				'id_ciclo'					=> $val->id_ciclo
	        			];
	        			$alumnos = $this->alumno->getAlumnosMatriculaCurso($where);
	        			$cicls = [$val->id_ciclo] = $alumnos;
	        			if(!is_numeric($alumnos))
	        				$alums = array_merge($alums,$alumnos);
	        		}
	        		$turns[$v->id_turno] = $cicls;
        		}
        		$esps[$value->id_especialidad] = $turns;
        	}
        	$conjunto[$this->input->post('id_periodo')] = $esps;
            /*if(!$this->input->post('id_turno'))
            $alumnos = $this->alumno->getAlumnosMatriculaCurso($where);*/
            if(is_numeric($alums))
            	echo json_encode(['status'=>202,'data'=>$alums,'message'=>'No se encontraron registros']);
            else{
		        $wh = [
		        	'id_periodo'			=> $this->input->post('id_periodo'),
		        	'id_turno'				=> $this->input->post('id_turno'),
		        	'id_especialidad'		=> $this->input->post('id_especialidad'),
		        	'id_ciclo'				=> $this->input->post('id_ciclo')
		        ];
            	$this->session->set_userdata('lista_alumnos_grupal',['alumnos'=>$alums,'filtro'=>$wh]);
                $periodo = $this->periodo->getPeriodoForId($this->input->post('id_periodo'));
            	echo json_encode(['status'=>200,'data'=>['alumnos'=>$alums,'periodo'=>$periodo],'message'=>'Datos encontrados']);
            }
            exit();
            echo '<pre>';
            print_r($alumnos);
            echo '</pre>';
            $alumnos = $this->alumno->getGrupoAlumnos($this->input->post('id_grupo'));
            echo json_encode(['status'=>200,'data'=>$alumnos,'message'=>'Datos encontrados']);
        }
        
        public function imprimiractagrupal(){
        	if(!$this->session->userdata('lista_alumnos_grupal'))
                header('Location: '.base_url());

            //if($id_grupo == '')
            $this->load->library('pdf',$this->parameters);
            $data = $this->session->userdata('lista_alumnos_grupal');
            $alumnos = $data['alumnos'];
            $filtro = $data['filtro'];
            //$alumno_matricula = $this->alumno->getAlumnoMatricula_($alumnos[0]->id_alumno_matricula);
            $notas = [];
            	$cursos = $this->alumno->getCursosMatriculaAlumno($filtro['id_periodo'],$filtro['id_ciclo'],$filtro['id_especialidad'],$filtro['id_turno']);
            $id_malla_periodo = $cursos[0]->id_malla_periodo;
            $malla_periodo = $this->periodo->getMallaPeriodoForId($id_malla_periodo);
            $especialidad_periodo = $this->periodo->getEspecialidadPeriodo_($malla_periodo->id_especialidad_periodo);
            foreach ($alumnos as $value) {
	            //print_r($cursos);
                $curs = [];
                if(!is_numeric($cursos))
                foreach ($cursos as $key => $val) {
                    $nota = $this->alumno->getNotasCursoAlumnoMatricula($value->id_alumno_matricula,$val->id_curso,$val->id_ciclo);
                    $curs[$key] = isset($nota->valor_nota) ? $nota : 0;
                }
                $notas[$value->codigo] = $curs;
            }
            $turno = $this->esp->getTurno($filtro['id_turno']);
            $periodo = $this->periodo->getPeriodoForId($filtro['id_periodo']);
            $data = [
                //'id_grupo'          => $id_grupo,
                'periodo'			=> $periodo,
                'especialidad'      => $especialidad_periodo,
                'alumnos'           => $alumnos,
                'cursos'            => $cursos,
                'notas'             => $notas,
                'turno'             => $turno,
                'filtro'			=> $filtro
            ];
            $this->pdf->imprimirActaGrupal($data,$this->parameters);
        }

	public function regular(){
		$especialidades = $this->esp->getEspecialidades();
		//$tipoalumno = $this->alumno->getTipoAlumno();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Matricula',
			'content'			=> $this->load->view('alumnos/index',[
				'parameters'		=> $this->parameters,
				'especialidades'	=> $especialidades,
				'tipoalumno'		=> 'regular',
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function ingresante($codigo_alumno = 0){
		//$especialidades = $this->esp->getEspecialidades();
		//$tipoalumno = $this->alumno->getTipoAlumno();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Matricula',
			'content'			=> $this->load->view('alumnos/index',[
				'parameters'		=> $this->parameters,
				//'especialidades'	=> $especialidades,
				'tipoalumno'		=> 'registroIngresante',
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

    public function registroIngresante($codigo_alumno = ''){
        $alumno = $this->alumno->getAlumnoForCodigo($codigo_alumno);
        /*echo '<pre>';
        print_r($alumno);
        echo '</pre>';
        exit();*/
        $genero = $this->alumno->getGenero();
        $estado_civil = $this->alumno->getEstadoCivil();
        $especialidades = $this->esp->getEspecialidadesPeriod();
        $tipo_ingreso = $this->alumno->getTipoIngreso();
        $data = [
            'parameters'        => $this->parameters,
            'usuario'           => $this->session->userdata('usuario'),
            'module'            => 'Matricula',
            'content'           => $this->load->view('alumnos/nuevo',[
                'parameters'        => $this->parameters,
                'especialidades'    => $especialidades,
                'tipoalumno'        => 'ingresante',
                'alumno'            => $alumno,
                'genero'            => $genero,
                'estado_civil'      => $estado_civil,
                'tipo_ingreso'      => $tipo_ingreso,
                'usuario'           => $this->session->userdata('usuario')
                ],true)
        ];
        $this->load->view('body',$data);
    }

    public function trasladoExterno($cod_alumno = ''){
        $alumno = $this->alumno->getAlumnoForCodigo($cod_alumno);
        /*echo '<pre>';
        print_r($alumno);
        echo '</pre>';
        exit();*/
        $genero = $this->alumno->getGenero();
        $estado_civil = $this->alumno->getEstadoCivil();
        $especialidades = $this->esp->getEspecialidadesPeriod();
        $tipo_ingreso = $this->alumno->getTipoIngreso();
        $data = [
            'parameters'        => $this->parameters,
            'usuario'           => $this->session->userdata('usuario'),
            'module'            => 'Matricula',
            'content'           => $this->load->view('alumnos/nuevoTraslado',[
                'parameters'        => $this->parameters,
                'especialidades'    => $especialidades,
                'tipoalumno'        => 'ingresante',
                'alumno'            => $alumno,
                'genero'            => $genero,
                'estado_civil'      => $estado_civil,
                'tipo_ingreso'      => $tipo_ingreso,
                'usuario'           => $this->session->userdata('usuario')
                ],true)
        ];
        $this->load->view('body',$data);
    }

	public function traslado(){
		$especialidades = $this->esp->getEspecialidades();
		//$tipoalumno = $this->alumno->getTipoAlumno();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Matricula',
			'content'			=> $this->load->view('alumnos/traslado',[
				'parameters'		=> $this->parameters,
				'especialidades'	=> $especialidades,
				'tipoalumno'		=> 'traslado',
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

    public function preparaTraslado(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $periodo = $this->periodo->getPeriodoActivo();
        $especialidades = $this->esp->getEspecialidadesForPeriodo($periodo->id);
        echo json_encode(['status'=>200,'data'=>[
            'periodo'       => $periodo,
            'especialidades'=> $especialidades
        ],'message'=>'Consulta satisfactoria']);
    }

    public function cargaInformacionTraslado4(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $periodos_anteriores = $this->periodo->getPeriodosAnteriores($this->input->post('id_periodo'),$this->input->post('id_especialidad'),$this->input->post('id_turno'), $this->input->post('id_ciclo'));
        echo json_encode(['status'=> 200,'data'=>['periodos_anteriores'=>$periodos_anteriores],'message'=>'Consulta satisfactoria']);
    }

    public function cargaInformacionTraslado3(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $ciclos = $this->esp->getCiclosForEspecialidadSeccion($this->input->post('id_especialidad'),$this->input->post('id_turno'),$this->input->post('id_periodo'));
        $periodos_anteriores = $this->periodo->getPeriodosAnteriores($this->input->post('id_periodo'),$this->input->post('id_especialidad'),$this->input->post('id_turno'), $ciclos[0]->id_ciclo);
        echo json_encode(['status'=> 200,'data'=>['ciclos'=>$ciclos,'periodos_anteriores'=>$periodos_anteriores],'message'=>'Consulta satisfactoria']);
    }

    public function cargaInformacionTraslado2(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $turnos = $this->esp->getTurnosForEspecialidadSeccion($this->input->post('id_especialidad'),$this->input->post('id_periodo'));
        $ciclos = $this->esp->getCiclosForEspecialidadSeccion($this->input->post('id_especialidad'),$turnos[0]->id_turno,$this->input->post('id_periodo'));
        $periodos_anteriores = $this->periodo->getPeriodosAnteriores($this->input->post('id_periodo'),$this->input->post('id_especialidad'),$turnos[0]->id_turno, $ciclos[0]->id_ciclo);
        echo json_encode(['status'=> 200,'data'=>['turnos'=>$turnos,'ciclos'=>$ciclos,'periodos_anteriores'=>$periodos_anteriores],'message'=>'Consulta satisfactoria']);
    }

    public function matriculaTrasladoExterno(){
        if(!$this->input->post())
            header('Location: '.base_url());
        if($this->input->post('id_persona') == 0){
            $persona = [
                'nombre'            => mb_strtoupper($this->input->post('nombre')),
                'apell_pat'         => mb_strtoupper($this->input->post('apell_pat')),
                'apell_mat'         => mb_strtoupper($this->input->post('apell_mat')),
                'dni'               => $this->input->post('dni'),
                'telefono'          => $this->input->post('telefono'),
                'celular_1'         => $this->input->post('celular_1'),
                'celular_2'         => $this->input->post('celular_2'),
                'direccion'         => mb_strtoupper($this->input->post('direccion')),
                'email'             => mb_strtoupper($this->input->post('email')),
                'fch_nac'           => date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('fch_nac')))),
                'fch_registro'      => date('Y-m-d'),
                'fch_salida'        => date('Y-m-d'),
                'id_genero'         => is_numeric($this->input->post('genero')) ? $this->input->post('genero') : 1,
                'id_estado_civil'   => is_numeric($this->input->post('estado_civil')) ? $this->input->post('estado_civil') : 1,
                'estado'            => 1
            ];
            $id = $this->persona->newPersona($persona);
        }else{
            $id = $this->input->post('id_persona');
        }
        $id_alumno_tipo = 0;
        switch ($this->input->post('tipoalumno')) {
            case 'regular':
                    $id_alumno_tipo = 1;
                break;

            case 'ingresante':
                    $id_alumno_tipo = 2;
                break;

            case 'subsanacion':
                    $id_alumno_tipo = 3;
                break;

            case 'traslado':
                    $id_alumno_tipo = 4;
                break;
            
            default:
                $id_alumno_tipo = 1;
                break;
        }
        if($this->input->post('cod_alumno') == ''){
            $tur = $this->esp->getTurno($this->input->post('turno'));
            $data = [
                'id_especialidad'       => $this->input->post('especialidad'),
                'id_periodo'            => $this->input->post('plan'),
                'id_persona'            => $id,
                'fch_ingreso'           => date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('fch_ingreso')))),
                'turno'                 => $tur->codigo,
                'pass'                  => sha1(md5('123456')),
                'alumnno_tipo'          => $id_alumno_tipo,
                'dni'                   => $this->input->post('dni')
            ];
            $al = $this->alumno->newAlumno($data);
        }else{
            $al = new stdClass();
            $al->codigo_alumno = $this->input->post('cod_alumno');
        }
        $periodo = $this->periodo->getPeriodoActivo();
        $consulta = $this->alumno->getMatriculaExistente(['cod_alumno'=>$al->codigo_alumno,'id_periodo'=>$periodo->id]);
        if(!is_numeric($consulta)){
            echo json_encode(['status'=>202,'data'=>[],'message'=>'El alumno ya se encuentra matriculado en el periodo '.$periodo->nombre]);
            exit();
        }
        $espe_per = $this->esp->getEspecialidadPeriodoForWhere($this->input->post('especialidad'),$this->input->post('plan'),$this->input->post('turno'));
        $carga_malla = $this->periodo->getCursosMalla($espe_per->id,$this->input->post('ciclo'),$this->input->post('turno'));
        $estado = 1;
        foreach ($carga_malla as $key => $value) {
            $seccion = $this->alumno->getSeccion($this->input->post('ciclo'),$espe_per->id,$this->input->post('id_periodo'),$this->input->post('turno'),$value->id_curso);
            if(!is_numeric($seccion)){
                if($seccion->id_malla_periodo != $value->id_malla_periodo){
                    $estado = 0;
                    break;
                }
            }else{
                $estado = 0;
                break;
            }
        }
        
        if($estado == 0){
            echo json_encode(['status'=>202,'data'=>['estado'=>$estado],'message'=>'Plan de estudios diferente al actual. Elija otro plan de estudios.']);
            exit();
        }

        $this->alumno->updateAlumnoEspecialidad_(['cod_alumno'=>$al->codigo_alumno],['activo'=>0]);
        $dat = [
            'cod_alumno'                    => $al->codigo_alumno,
            'id_especialidad_periodo'       => $espe_per->id,
            'id_periodo_actual'             => $this->input->post('id_periodo'),
            'cursos'                        => 0,
            'cursos_forzado'                => 0,
            'actividades'                   => 0,
            'actividades_forzado'           => 0,
            'practicas'                     => 0,
            'practicas_forzado'             => 0,
            'lleva_cursos_actividades'      => 0,
            'estado_egreso'                 => 0,
            'estado_titulado'               => 0,
            'fch_ingreso'                   => date('Y-m-d'),
            'fch_egreso'                    => date('Y-m-d'),
            'fch_titulado'                  => date('Y-m-d'),
            'id_tipo_ingreso'               => 9,
            'id_periodo_ingreso'            => $this->input->post('plan'),
            'estado'                        => 1,
            'id_ciclo'                      => $this->input->post('ciclo'),
            //'id_grupo'                        => $this->input->post('id_grupo') ? $this->input->post('id_grupo') : 1,
            'id_turno'                      => $this->input->post('turno'),
            'activo'                        => 1,
            'expediente_ingreso'            => $this->input->post('nro_expediente')
        ];
        $this->alumno->newEspecialidadAlumno($dat);
        //matricula solo para ingresantes
        //primera matricula
        $mat = [
            'cod_alumno'                    => $al->codigo_alumno,
            'id_especialidad'               => $this->input->post('especialidad'),
            'id_periodo'                    => $this->input->post('id_periodo'),
            'fch_registro'                  => date('Y-m-d'),
            'id_ciclo'                      => $this->input->post('ciclo'),
            'id_turno'                      => $this->input->post('turno'),
            'estado_semestre'               => 2,
            'pagado'                        => 1
        ];
        $mat_alumno = $this->alumno->newMatricula($mat);
        //$cursos = $this->periodo->getCursosMalla($espe_per->id,$this->input->post('id_ciclo'),$this->input->post('id_turno'));
        //$cursos = $this->esp->getCursosEspecialidadMalla($this->input->post('id_especialidad_periodo'),1);
        //$cursos = $this->esp->getCursosEspecialidad($this->input->post('id_especialidad_periodo'),$this->input->post('turno'));
        if(!is_numeric($carga_malla))
            foreach ($carga_malla as $key => $value) {
                $curso_matricula = [
                    'id_alumno_matricula'       => $mat_alumno,
                    'id_curso'                  => $value->id_curso,
                    'id_malla_periodo'          => $value->id_malla_periodo,
                    'id_modulo'                 => $value->id_modulo,
                    'id_ciclo'                  => $value->id_ciclo,
                    'estado'                    => 1
                ];
                $this->alumno->newMatriculaCurso($curso_matricula);
                //$seccion = $this->alumno->getSeccionCursoForIdMallaPeriodo($value->id_malla_periodo,$value->id_curso,$value->id_turno);
            }

        echo json_encode(['status'=> 200,'data'=>['codigo_alumno'=>$al->codigo_alumno],'message'=>'Consulta satisfactoria']);
    }

    public function matriculaTraslado(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $periodo = $this->periodo->getPeriodoActivo();
        $consulta = $this->alumno->getMatriculaExistente(['cod_alumno'=>$this->input->post('cod_alumno'),'id_periodo'=>$periodo->id]);
        if(!is_numeric($consulta)){
            echo json_encode(['status'=>202,'data'=>[],'message'=>'El alumno ya se encuentra matriculado en el periodo '.$periodo->nombre]);
            exit();
        }
        $espe_per = $this->esp->getEspecialidadPeriodoForWhere($this->input->post('id_especialidad'),$this->input->post('id_periodo_promocion'),$this->input->post('id_turno'));
        $carga_malla = $this->periodo->getCursosMalla($espe_per->id,$this->input->post('id_ciclo'),$this->input->post('id_turno'));
        $estado = 1;
        //echo '<pre>';
        foreach ($carga_malla as $key => $value) {
            $seccion = $this->alumno->getSeccion($this->input->post('id_ciclo'),$espe_per->id,$this->input->post('id_periodo_activo'),$this->input->post('id_turno'),$value->id_curso);
            //print_r($seccion);
            if(!is_numeric($seccion)){
                if($seccion->id_malla_periodo != $value->id_malla_periodo){
                    $estado = 0;
                    break;
                }
            }else{
                $estado = 0;
                break;
            }
        }
        if($estado == 0){
            echo json_encode(['status'=>202,'data'=>['estado'=>$estado],'message'=>'Plan de estudios diferente al actual. Elija otro plan de estudios.']);
            exit();
        }
        $this->alumno->updateAlumnoEspecialidad_(['cod_alumno'=>$this->input->post('cod_alumno')],['activo'=>0]);
        $dat = [
            'cod_alumno'                    => $this->input->post('cod_alumno'),
            'id_especialidad_periodo'       => $espe_per->id,
            'id_periodo_actual'             => $this->input->post('id_periodo_activo'),
            'cursos'                        => 0,
            'cursos_forzado'                => 0,
            'actividades'                   => 0,
            'actividades_forzado'           => 0,
            'practicas'                     => 0,
            'practicas_forzado'             => 0,
            'lleva_cursos_actividades'      => 0,
            'estado_egreso'                 => 0,
            'estado_titulado'               => 0,
            'fch_ingreso'                   => date('Y-m-d'),
            'fch_egreso'                    => date('Y-m-d'),
            'fch_titulado'                  => date('Y-m-d'),
            'id_tipo_ingreso'               => 8,
            'id_periodo_ingreso'            => $this->input->post('id_periodo_promocion'),
            'estado'                        => 1,
            'id_ciclo'                      => $this->input->post('id_ciclo'),
            //'id_grupo'                        => $this->input->post('id_grupo') ? $this->input->post('id_grupo') : 1,
            'id_turno'                      => $this->input->post('id_turno'),
            'activo'                        => 1,
            'expediente_ingreso'            => $this->input->post('expediente_ingreso')
        ];
        $this->alumno->newEspecialidadAlumno($dat);
        //matricula solo para ingresantes
        //primera matricula
        $mat = [
            'cod_alumno'                    => $this->input->post('cod_alumno'),
            'id_especialidad'               => $this->input->post('id_especialidad'),
            'id_periodo'                    => $this->input->post('id_periodo_activo'),
            'fch_registro'                  => date('Y-m-d'),
            'id_ciclo'                      => $this->input->post('id_ciclo'),
            'id_turno'                      => $this->input->post('id_turno'),
            'estado_semestre'               => 2,
            'pagado'                        => 1
        ];
        $mat_alumno = $this->alumno->newMatricula($mat);
        //$cursos = $this->periodo->getCursosMalla($espe_per->id,$this->input->post('id_ciclo'),$this->input->post('id_turno'));
        //$cursos = $this->esp->getCursosEspecialidadMalla($this->input->post('id_especialidad_periodo'),1);
        //$cursos = $this->esp->getCursosEspecialidad($this->input->post('id_especialidad_periodo'),$this->input->post('turno'));
        if(!is_numeric($carga_malla))
            foreach ($carga_malla as $key => $value) {
                $curso_matricula = [
                    'id_alumno_matricula'       => $mat_alumno,
                    'id_curso'                  => $value->id_curso,
                    'id_malla_periodo'          => $value->id_malla_periodo,
                    'id_modulo'                 => $value->id_modulo,
                    'id_ciclo'                  => $value->id_ciclo,
                    'estado'                    => 1
                ];
                $this->alumno->newMatriculaCurso($curso_matricula);
                //$seccion = $this->alumno->getSeccionCursoForIdMallaPeriodo($value->id_malla_periodo,$value->id_curso,$value->id_turno);
            }

        echo json_encode(['status'=> 200,'data'=>[],'message'=>'Matricula satisfactoria']);
    }

	public function subsanacion(){
		$especialidades = $this->esp->getEspecialidades();
		//$tipoalumno = $this->alumno->getTipoAlumno();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Matricula',
			'content'			=> $this->load->view('alumnos/index',[
				'parameters'		=> $this->parameters,
				'especialidades'	=> $especialidades,
				'tipoalumno'		=> 'subsanacion',
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function regulares($cod_alumno = ''){
		$especialidades = $this->esp->getEspecialidades();
		$periodos = $this->esp->getPeriodos();
        if($cod_alumno != ''){
            $alumno = $this->alumno->getAlumnosCodigo($this->input->post('cod_alumno'));
        }
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Matricula',
			'content'			=> $this->load->view('alumnos/regulares',[
				'parameters'		=> $this->parameters,
				'especialidades'	=> $especialidades,
				'periodos'			=> $periodos,
				'tipoalumno'		=> 'regular',
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function alumnosEspecialidadPeriodo(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$alumnos = $this->alumno->getAlumnosEspecialidadPeriodo($this->input->post('id_especialidad'),$this->input->post('id_periodo'));
		if(!is_numeric($alumnos))
			echo json_encode(['status'=>200,'data'=>$alumnos,'message'=>'Consulta Satisfactoria']);
		else
			echo json_encode(['status'=>202,'data'=>$alumnos,'message'=>'No se encontraron resultados']);
		exit();
		var_dump($this->input->post(null,true));
	}

	public function nuevo($tipoalumno){
        var_dump($tipoalumno);
        exit();
		$especialidades = $this->esp->getEspecialidades();
		$genero = $this->alumno->getGenero();
		$estado_civil = $this->alumno->getEstadoCivil();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Matricula',
			'content'			=> $this->load->view('alumnos/nuevo',[
				'parameters'		=> $this->parameters,
				'especialidades'	=> $especialidades,
				'genero'			=> $genero,
				'estado_civil'		=> $estado_civil,
				'tipoalumno'		=> $tipoalumno,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function newAlumno(){
        if($this->input->post('id_persona') == 0){
    		$persona = [
    			'nombre'			=> mb_strtoupper($this->input->post('nombre')),
    			'apell_pat'			=> mb_strtoupper($this->input->post('apell_pat')),
    			'apell_mat'			=> mb_strtoupper($this->input->post('apell_mat')),
    			'dni'				=> $this->input->post('dni'),
    			'telefono'			=> $this->input->post('telefono'),
    			'celular_1'			=> $this->input->post('celular_1'),
    			'celular_2'			=> $this->input->post('celular_2'),
    			'direccion'			=> mb_strtoupper($this->input->post('direccion')),
    			'email'				=> mb_strtoupper($this->input->post('email')),
    			'fch_nac'			=> date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('fch_nac')))),
    			'fch_registro'		=> date('Y-m-d'),
    			'fch_salida'		=> date('Y-m-d'),
    			'id_genero'			=> is_numeric($this->input->post('genero')) ? $this->input->post('genero') : 1,
    			'id_estado_civil'	=> is_numeric($this->input->post('estado_civil')) ? $this->input->post('estado_civil') : 1,
    			'estado'			=> 1
    		];
    		$id = $this->persona->newPersona($persona);
        }else{
            $id = $this->input->post('id_persona');
        }
		$id_alumno_tipo = 0;
		switch ($this->input->post('tipoalumno')) {
			case 'regular':
					$id_alumno_tipo = 1;
				break;

			case 'ingresante':
					$id_alumno_tipo = 2;
				break;

			case 'subsanacion':
					$id_alumno_tipo = 3;
				break;

			case 'traslado':
					$id_alumno_tipo = 4;
				break;
			
			default:
				$id_alumno_tipo = 1;
				break;
		}
        if($this->input->post('cod_alumno') == ''){
            $tur = $this->esp->getTurno($this->input->post('turno'));
    		$data = [
    			'id_especialidad'		=> $this->input->post('especialidad'),
    			'id_periodo'			=> $this->input->post('id_periodo'),
    			'id_persona'			=> $id,
    			'fch_ingreso'			=> date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('fch_ingreso')))),
    			'turno'					=> $tur->codigo,
    			'pass'					=> sha1(md5('123456')),
    			'alumnno_tipo'			=> $id_alumno_tipo,
                'dni'                   => $this->input->post('dni')
    		];
    		$al = $this->alumno->newAlumno($data);
        }else{
            $al = new stdClass();
            $al->codigo_alumno = $this->input->post('cod_alumno');
        }
        $periodo = $this->periodo->getPeriodoActivo();
        $consulta = $this->alumno->getMatriculaExistente(['cod_alumno'=>$al->codigo_alumno,'id_periodo'=>$periodo->id]);
        if(!is_numeric($consulta)){
            echo json_encode(['status'=>202,'data'=>[],'message'=>'El alumno ya se encuentra matriculado en el periodo '.$periodo->nombre]);
            exit();
        }
        $this->alumno->updateAlumnoEspecialidad_(['cod_alumno'=>$al->codigo_alumno],['activo'=>0]);
		$dat = [
			'cod_alumno'					=> $al->codigo_alumno,
			'id_especialidad_periodo'		=> $this->input->post('id_especialidad_periodo'),
            'id_periodo_actual'             => $this->input->post('id_periodo'),
            'cursos'                        => 0,
            'cursos_forzado'                => 0,
            'actividades'                   => 0,
            'actividades_forzado'           => 0,
            'practicas'                     => 0,
            'practicas_forzado'             => 0,
            'lleva_cursos_actividades'      => 0,
			'estado_egreso'					=> 0,
			'estado_titulado'				=> 0,
			'fch_ingreso'					=> date('Y-m-d'),
			'fch_egreso'					=> date('Y-m-d'),
			'fch_titulado'					=> date('Y-m-d'),
            'id_tipo_ingreso'                  => $this->input->post('tipo_ingreso'),
			'id_periodo_ingreso'			=> $this->input->post('id_periodo'),
			'estado'						=> 1,
			'id_ciclo'						=> $this->input->post('hasta') == 'alumno' ? 0 : 1,
			//'id_grupo'						=> $this->input->post('id_grupo') ? $this->input->post('id_grupo') : 1,
			'id_turno'						=> $this->input->post('turno'),
            'activo'                        => 1,
            'expediente_ingreso'            => $this->input->post('nro_expediente')
		];
		$this->alumno->newEspecialidadAlumno($dat);
                if($this->input->post('hasta') == 'alumno'){
                    echo json_encode(['status'=>200,'data'=>$al,'message'=>'Registro satisfactorio codigo de alumno nuevo '.$al->codigo_alumno]);
                    exit();
                }
		//matricula solo para ingresantes
		//primera matricula
		$mat = [
			'cod_alumno'					=> $al->codigo_alumno,
			'id_especialidad'				=> $this->input->post('especialidad'),
			'id_periodo'					=> $this->input->post('id_periodo'),
			'fch_registro'					=> date('Y-m-d'),
			'id_ciclo'						=> 1,
			'id_turno'						=> $this->input->post('turno'),
			'estado_semestre'				=> 2,
			'pagado'						=> 1
		];
		$mat_alumno = $this->alumno->newMatricula($mat);
		$cursos = $this->periodo->getCursosMalla($this->input->post('id_especialidad_periodo'),1,$this->input->post('turno'));
		//$cursos = $this->esp->getCursosEspecialidadMalla($this->input->post('id_especialidad_periodo'),1);
		//$cursos = $this->esp->getCursosEspecialidad($this->input->post('id_especialidad_periodo'),$this->input->post('turno'));
		if(!is_numeric($cursos))
			foreach ($cursos as $key => $value) {
				$curso_matricula = [
					'id_alumno_matricula'		=> $mat_alumno,
					'id_curso'					=> $value->id_curso,
					'id_malla_periodo'			=> $value->id_malla_periodo,
					'id_modulo'					=> $value->id_modulo,
					'id_ciclo'					=> $value->id_ciclo,
					'estado'					=> 1
				];
				$this->alumno->newMatriculaCurso($curso_matricula);
                //$seccion = $this->alumno->getSeccionCursoForIdMallaPeriodo($value->id_malla_periodo,$value->id_curso,$value->id_turno);
			}
		if($al)
			echo json_encode(['status'=>200,'data'=>$al,'message'=>'Registro satisfactorio codigo de alumno nuevo '.$al->codigo_alumno]);
		else
			echo json_encode(['status'=>202,'data'=>[],'message'=>'Error en el registro']);
	}

	public function impresionnuevo($codigo,$id_ciclo = 1){
		if($codigo == '')
			header('Location: '.base_url());
		$this->load->library('pdf',$this->parameters);
		$da = $this->alumno->getDataAlumno(['codigo'=>$codigo]);
		$esp = $this->alumno->getEspecialidadPeriodoForAlumno($codigo);
        /*echo '<pre>';
        print_r($esp);
        echo '</pre>';
        exit();*/
		//$cursos = $this->esp->getCursosEspecialidad($esp->id_especialidad_periodo);
		$cursos = $this->periodo->getCursosMalla($esp->id_especialidad_periodo,1,$esp->id_turno);
		$data = [
			'codigo'		=> $codigo,
			'data'			=> $da,
			'especialidad'	=> $esp,
			'cursos'		=> $cursos
		];
		$this->pdf->terminar_matricula($data,$this->parameters);
		//var_dump($codigo);
	}

    public function getCursosForPeriodo_(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $espe_per = $this->esp->getEspecialidadPeriodoForWhere($this->input->post('id_especialidad'),$this->input->post('id_periodo'),$this->input->post('id_turno'));
        $cursos = $this->periodo->getCursosMalla($espe_per->id,1,$this->input->post('id_turno'));
        if(is_numeric($cursos))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No hay registro de cursos para este periodo']);
        else
            echo json_encode(['status'=>200,'data'=>['cursos'=>$cursos,'grupo'=>[],'especialidad_periodo'=>$espe_per->id],'message'=>'Consulta satisfactoria']);
    }

	public function getPeriodoEspecialidad(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$p = $this->esp->getPeriodosEspecialidad($this->input->post('id_especialidad'),1,$this->input->post('tipo'));
        $ciclos = $this->esp->getCiclosForEspecialidadSeccion($this->input->post('id_especialidad'),$p->id_turno,$p->id_periodo_actual);
        $turnos = $this->esp->getTurnosEspecialidadPeriodos($this->input->post('id_especialidad'),$p->id_periodo_actual);
        $periodo = $this->periodo->getPeriodoActivo();
        $periodos_anteriores = $this->periodo->getPeriodosAnteriores($periodo->id,$this->input->post('id_especialidad'),$turnos[0]->id_turno,$ciclos[0]->id_ciclo);
		if(is_numeric($p))
			echo json_encode(['status'=>202,'data'=>[]]);
		else
			echo json_encode(['status'=>200, 'data'=>['esp_p'=>$p,'turno'=>$turnos,'ciclos'=>$ciclos,'periodos_anteriores'=>$periodos_anteriores]]);
		//var_dump($p);
	}

	public function busquedarapida(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$where = [];
		if(is_numeric($this->input->post('id_especialidad')))
			$where['id_especialidad'] = $this->input->post('id_especialidad');
		//agregando mas filtros
		$where['nombre'] = $this->input->post('nombre') ? $this->input->post('nombre') : '';
		$where['apell_pat'] = $this->input->post('apell_pat') ? $this->input->post('apell_pat') : '';
		$where['apell_mat'] = $this->input->post('apell_mat') ? $this->input->post('apell_mat') : '';
		$almns = $this->alumno->getAlumnosFiltro($where);
		if($almns)
			echo json_encode(['status'=>200,'data'=>$almns,'message'=>'Resultados encontrados']);
		else
			echo json_encode(['status'=>200,'data'=>$almns,'message'=>'Sin resultados']);
	}

	public function getAlumnoAutocomplete_(){
		if(!$this->input->post())
            header('Location: '.base_url());
        if(is_numeric($this->input->post('data')))
            $alumnos = $this->alumno->getAlumnosForDni($this->input->post('data'));
        else
            $alumnos = $this->alumno->getAlumnosNombreorApellidos($this->input->post('data'));
        $a = array();
        if(!is_numeric($alumnos))
            foreach ($alumnos as $key => $value) 
                array_push($a, array('value'=>$value->dni .' - '. $value->apell_pat.' '.$value->apell_mat.' '.$value->nombre,'data'=>json_encode($value)));
        print json_encode(array('suggestions'=>$a));
	}
        
        public function getAlumnoAutocomplete(){
            if(!$this->input->post())
                header('Location: '.base_url());
            if(!ctype_alpha($this->input->post('data')))
                $alumnos = $this->alumno->getAlumnosCodigo($this->input->post('data'));
            else
                $alumnos = $this->alumno->getAlumnosNombreorApellidos($this->input->post('data'));
            $a = array();
            if(!is_numeric($alumnos))
                foreach ($alumnos as $key => $value) 
                    array_push($a, array('value'=>$value->codigo .' - '. $value->nombre.' '.$value->apell_pat.' '.$value->apell_mat,'data'=>json_encode($value)));
            print json_encode(array('suggestions'=>$a));
        }
        
        public function cargaMatriculasForAlumno(){
            if(!$this->input->post())
                header ('Location: '.base_url());
            $matriculas = $this->alumno->getMatriculaAlumno($this->input->post('cod_alumno'));
            if(is_numeric($matriculas))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'No hay registros de matriculas']);
            else
                echo json_encode (['status'=>200,'data'=>$matriculas,'message'=>'Registros']);
        }

        public function cargaMatriculas(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $matriculas = $this->alumno->getMatriculaAlumnoActivo($this->input->post('cod_alumno'));
            if(is_numeric($matriculas))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'No hay registros de matriculas']);
            else
                echo json_encode (['status'=>200,'data'=>$matriculas,'message'=>'Registros']);
        }

        public function cargaMatriculas_(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $matriculas = $this->alumno->getMatriculaAlumnoActivo($this->input->post('cod_alumno'));
            $periodo = $this->periodo->getPeriodoActivo();
            if(is_numeric($matriculas))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'No hay registros de matriculas']);
            else
                echo json_encode (['status'=>200,'data'=>['matriculas'=>$matriculas,'periodo_activo'=>$periodo],'message'=>'Registros']);
        }

        public function eliminarMatricula(){
            if(!$this->input->post('id_alumno_matricula'))
                header('Location: '.base_url());
            $matricula = $this->alumno->getAlumnoMatricula_($this->input->post('id_alumno_matricula'));
            //$espe_per = $this->esp->getEspecialidadPeriodoForWhere($this->input->post('id_especialidad'),$this->input->post('id_periodo'),$this->input->post('id_turno'));
            /*$alumno_espe = $this->alumno->getAlumnoEspecialidad($matricula->cod_alumno);*/
            $this->alumno->updateAlumnoEspecialidadEsp([
                //'id_especialidad'           => $this->input->post('id_especialidad'),
                'id_ciclo'                  => $this->input->post('id_ciclo'),
                'cod_alumno'                => $matricula->cod_alumno,
                'id_periodo_actual'         => $this->input->post('id_periodo') 
            ],[]);
            $this->alumno->deleteTnotaCapacidadCursoAlumno(['id_alumno_matricula'=>$this->input->post('id_alumno_matricula')]);
            $this->alumno->deleteTnotaCursoAlumno(['id_alumno_matricula'=>$this->input->post('id_alumno_matricula')]);
            $this->alumno->deleteTalumnoMatriculaCurso(['id_alumno_matricula'=>$this->input->post('id_alumno_matricula')]);
            $this->alumno->deleteTalumnoMatricula(['id'=>$this->input->post('id_alumno_matricula')]);
            echo json_encode (['status'=>200,'data'=>[],'message'=>'Eliminacion correcta']);
            //$this->alumno->updateAlumnoEspecialidad_();
        }

        public function preparaMatricula(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $periodo = $this->periodo->getPeriodoActivo();
            $consulta = $this->alumno->getMatriculaExistente(['cod_alumno'=>$this->input->post('cod_alumno'),'id_periodo'=>$periodo->id]);
            if(!is_numeric($consulta)){
                echo json_encode(['status'=>202,'data'=>[],'message'=>'El alumno ya se encuentra matriculado en el periodo '.$periodo->nombre]);
                exit();
            }
            $alumno = $this->alumno->getAlumnosCodigo($this->input->post('cod_alumno'));
            $alumno_espe = $this->esp->getAlumnoEspecialidad_($this->input->post('cod_alumno'));
            $especialidad = $this->esp->getEspecialidadPorId($alumno_espe->id_especialidad);
            $matricula_aprobada = $this->alumno->getMatriculaAlumnoAprobada($this->input->post('cod_alumno'),$alumno_espe->id_especialidad);
            $periodo = $this->periodo->getPeriodoActivo();
            //$carga_malla = $this->
            //echo '</pre>';
            //exit();
            echo json_encode (['status'=>200,'data'=>[
                'alumno'                    => $alumno[0],
                'alumno_especialidad'       => $alumno_espe,
                'matricula_aprobada'        => $matricula_aprobada,
                'especialidad'              => $especialidad,
                'periodo'                   => $periodo
            ],'message'=>'No hay registros de matriculas']);
        }

        public function matricularRegular(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $periodo = $this->periodo->getPeriodoActivo();
            $consulta = $this->alumno->getMatriculaExistente(['cod_alumno'=>$this->input->post('cod_alumno'),'id_periodo'=>$periodo->id]);
            if(!is_numeric($consulta)){
                echo json_encode(['status'=>202,'data'=>[],'message'=>'El alumno ya se encuentra matriculado en el periodo '.$periodo->nombre]);
                exit();
            }
            $carga_malla = $this->periodo->getCursosMalla($this->input->post('id_especialidad_periodo'),$this->input->post('id_ciclo'),$this->input->post('id_turno'));
            $estado = 1;
            //echo '<pre>';
            foreach ($carga_malla as $key => $value) {
                $seccion = $this->alumno->getSeccion($this->input->post('id_ciclo'),$this->input->post('id_especialidad_periodo'),$this->input->post('id_periodo'),$this->input->post('id_turno'),$value->id_curso);
                //print_r($seccion);
                if(!is_numeric($seccion)){
                    if($seccion->id_malla_periodo != $value->id_malla_periodo){
                        $estado = 0;
                        break;
                    }
                }else{
                    $estado = 0;
                    break;
                }
            }
            if($estado == 0){
                echo json_encode(['status'=>202,'data'=>['estado'=>$estado],'message'=>'Plan de estudios diferente al actual. Matricularlo como traslado interno.']);
                exit();
            }
            $this->alumno->updateAlumnoEspecialidadEsp2($this->input->post('id_alumno_especialidad'));
            $this->alumno->updateAlumnoEspecialidad($this->input->post('id_alumno_especialidad'),[
                'id_ciclo'                  => $this->input->post('id_ciclo'),
                'id_periodo_actual'         => $this->input->post('id_periodo')
            ]);
            //MATRICULA
            //matricula solo para ingresantes
                //primera matricula
                $mat = [
                    'cod_alumno'                    => $this->input->post('cod_alumno'),
                    'id_especialidad'               => $this->input->post('id_especialidad'),
                    'id_periodo'                    => $this->input->post('id_periodo'),
                    'fch_registro'                  => date('Y-m-d'),
                    'id_ciclo'                      => $this->input->post('id_ciclo'),
                    'id_turno'                      => $this->input->post('id_turno'),
                    'estado_semestre'               => 2,
                    'pagado'                        => 1
                ];
                $mat_alumno = $this->alumno->newMatricula($mat);
                if(!is_numeric($carga_malla))
                    foreach ($carga_malla as $key => $value) {
                        $curso_matricula = [
                            'id_alumno_matricula'       => $mat_alumno,
                            'id_curso'                  => $value->id_curso,
                            'id_malla_periodo'          => $value->id_malla_periodo,
                            'id_modulo'                 => $value->id_modulo,
                            'id_ciclo'                  => $value->id_ciclo,
                            'estado'                    => 1
                        ];
                        $this->alumno->newMatriculaCurso($curso_matricula);
                        //$seccion = $this->alumno->getSeccionCursoForIdMallaPeriodo($value->id_malla_periodo,$value->id_curso,$value->id_turno);
                    }
            echo json_encode(['status'=>200,'data'=>['estado'=>$estado],'message'=>'Matricula satisfactoria']);
        }
        
        public function cargaPagosForAlumno(){
            if(!$this->input->post())
                header ('Location: '.base_url());
//            if(is_numeric($this->input->post('concepto')))
//                $pagos = $this->pagos->getPagosForAlumnoConcepto($this->input->post('cod_alumno'),$this->input->post('concepto'));
//            else
                $pagos = $this->pagos->getPagosForAlumnoConcepto($this->input->post('cod_alumno'));
            if(is_numeric($pagos))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'No hay registros de matriculas']);
            else
                echo json_encode (['status'=>200,'data'=>$pagos,'message'=>'Registros']);
        }
        
        public function cargaDataForPagos(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $ultima_matricula = $this->alumno->getUltimaMatricula($this->input->post('cod_alumno'));
            if(is_numeric($ultima_matricula))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'No hay registros de matriculas']);
            echo json_encode(['status'=>200,'data'=>['ultima_matricula'=>$ultima_matricula],'message'=>'Resultados']);
        }
        
        public function getCargaInformacionAlumno(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $informacion = $this->alumno->getAlumnoInf_($this->input->post('cod_alumno'));
            //$solicitudes  = $this->pagos->getSolicitudesPago($this->input->post('cod_alumno'));
            if(is_numeric($informacion))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'Existe un error en la consulta']);
            echo json_encode(['status'=>200,'data'=>['infor'=>$informacion],'message'=>'Resultados']);
        }

        public function getCargaInformacionAlumnoHistorial(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $informacion = $this->alumno->getAlumnoInf_($this->input->post('cod_alumno'));
            $historial = $this->alumno->getAlumnoHisto($this->input->post('cod_alumno'));
            if(is_numeric($informacion))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'Existe un error en la consulta']);
            echo json_encode(['status'=>200,'data'=>['infor'=>$informacion,'historial'=>$historial],'message'=>'Resultados']);
        }
        
        public function consultaPagoMatricula(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $pagos = $this->pagos->getPagosForAlumnoConcepto_($this->input->post('cod_alumno'),29,$this->input->post('id_periodo'));
            if(is_numeric($pagos))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'No hay registros de matriculas']);
            else
                echo json_encode (['status'=>200,'data'=>$pagos,'message'=>'Registros']);
        }
        
        public function imprimirsolicitud($id = 0){
            if($id == 0)
                header ('Location: '.base_url());
            ///AQUI TE QUEDASTE GESTIONAR LAS IMPRESIONES
            $this->load->library('constancias',$this->parameters);
            switch ($id){}
        }
        
        public function nuevoAlumnoView(){
            if(!$this->input->post())
                header ('Location: '.base_url());
            $especialidades = $this->esp->getEspecialidades();
            $genero = $this->alumno->getGenero();
            $estado_civil = $this->alumno->getEstadoCivil();
            $periodo = $this->periodo->getPeriodoActivo();
            $view = $this->load->view('alumnos/newview',[
                'parameters'		=> $this->parameters,
                'especialidades'	=> $especialidades,
                'genero'		=> $genero,
                'estado_civil'		=> $estado_civil,
                'periodo'           => $periodo,
                'tipoalumno'		=> 'ingresante'
            ],true);
            echo $view;
        }

        public function newPersonaOnly(){
            if(!$this->input->post())
                header ('Location: '.base_url());
                $persona = [
                    'nombre'            => mb_strtoupper($this->input->post('nombre')),
                    'apell_pat'         => mb_strtoupper($this->input->post('apell_pat')),
                    'apell_mat'         => mb_strtoupper($this->input->post('apell_mat')),
                    'dni'               => $this->input->post('dni'),
                    'telefono'          => $this->input->post('telefono'),
                    'celular_1'         => $this->input->post('celular_1'),
                    'celular_2'         => $this->input->post('celular_2'),
                    'direccion'         => mb_strtoupper($this->input->post('direccion')),
                    'email'             => mb_strtoupper($this->input->post('email')),
                    'fch_nac'           => date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('fch_nac')))),
                    'fch_registro'      => date('Y-m-d'),
                    'fch_salida'        => date('Y-m-d'),
                    'id_genero'         => is_numeric($this->input->post('genero')) ? $this->input->post('genero') : 3,
                    'id_estado_civil'   => is_numeric($this->input->post('estado_civil')) ? $this->input->post('estado_civil') : 1,
                    'estado'            => 1
                ];
                $id = $this->persona->newPersona($persona);
                $id_alumno_tipo = 1;
                $tur = $this->esp->getTurno(1);
                $data = [
                    'id_especialidad'       => $this->input->post('especialidad'),
                    'id_periodo'            => $this->input->post('id_periodo'),
                    'id_persona'            => $id,
                    'fch_ingreso'           => date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('fch_ingreso')))),
                    'turno'                 => $tur->codigo,
                    'pass'                  => sha1(md5('123456')),
                    'alumnno_tipo'          => $id_alumno_tipo,
                    'dni'                   => $this->input->post('dni')
                ];
                $al = $this->alumno->newAlumno($data);
                $al->nombre = $persona['nombre'];
                $al->apell_pat = $persona['apell_pat'];
                $al->apell_mat = $persona['apell_mat'];
                $al->dni = $persona['dni'];
            echo json_encode(['status'=>200,'data'=>['id_persona'=>$id,'alumno'=>$al],'message'=>'Registro satisfactorio']);
        }

        public function generaDni(){
            $cant = $this->persona->getMaximoPersona();
            $dni = $this->completardigitos($cant->ultimo+1,8);
            echo json_encode(['status'=>200,'data'=>['cantidad'=>$cant,'dni'=>$dni],'message'=>'Consulta satisfactoria']);
        }

        public function completardigitos($char,$length = 2){
            return str_repeat('0', $length-strlen($char)).$char;
        }
        
        public function verificaEstadoAlumno(){
            if(!$this->input->post())
                header ('Location: '.base_url());
            $resp = $this->alumno->actualizaEstadoSemestre(['id_nota'=>$this->input->post('id_nota')]);
        }

        public function pagos(){
            if(!$this->buscarPermiso(2))
                header('Location: '.base_url());
            $u = $this->session->userdata('usuario');
            $data = [
                'parameters'        => $this->parameters,
                'usuario'           => $this->session->userdata('usuario'),
                'module'            => 'Consultar',
                'content'           => $this->load->view('alumnos/pagos',[
                    'parameters'        => $this->parameters,
                    'usuario'           => $this->session->userdata('usuario')
                    ],true)
            ];
            $this->load->view('body',$data);
        }

    public function buscarPermiso($id_rol){
        $usuario = $this->session->userdata('usuario');
        $permiso = false;
        foreach ($usuario['roles'] as $key => $value) 
            if($id_rol == $value->id_rol)
                $permiso = true;
        return $permiso;
    }

}