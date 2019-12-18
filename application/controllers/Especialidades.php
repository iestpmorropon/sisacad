<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Especialidades extends CI_Controller {

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
		$this->load->model('Practica','practica');
		$this->load->model('Evaluaciones','evaluaciones');
		if(!$this->session->userdata('usuario'))
			header('Location: '.base_url());
	}

	public function index(){
		$periodos = $this->esp->getPeriodos();
		$esps = [];
		/*if(!is_numeric($periodos))
			$esps = $this->esp->getEspecialidadesPeriodoForNombre(substr ($periodos[0]->nombre,0,4));*/
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Especialidad',
			'content'			=> $this->load->view('especialidad/index',[
				'parameters'		=> $this->parameters,
				'periodos'			=> $periodos,
				'especialidades'	=> $esps,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function forNewEspecialidadPeriodo(){
		echo json_encode(['status'=>200,'data'=>[],'message'=>'Resultados satisfactorios']);
	}

	public function nueva(){
		if($this->input->post()){
			if($this->input->post('id_especialidad') == 0){
				$especialidad = [
					'codigo'			=> mb_strtoupper($this->input->post('codigo')),
					'nombre'			=> mb_strtoupper($this->input->post('nombre')),
					'estado'			=> 1
				];
				$id_especialidad = $this->esp->newEspecialidad($especialidad);
			}else{
				$id_especialidad = $this->input->post('id_especialidad');
			}
			$relacio = $this->esp->searchEspecialidadPeriodo([
				'id_especialidad'=>$this->input->post('id_especialidad'),
				'id_periodo'=> $this->input->post('id_periodo'),
				'id_turno'	=> $this->input->post('turno')
			]);
			$existe_especialidad_periodo = false;
			if(is_numeric($relacio)){
				$espe_periodo = [
					'id_especialidad'		=> $id_especialidad,
					'id_periodo'			=> $this->input->post('id_periodo'),
					'vacantes'				=> $this->input->post('vacantes'),
					'estado'				=> 1,
					'modular'				=> $this->input->post('modular'),
					'id_turno'				=> $this->input->post('turno')
				];
				$id_especialidad_periodo = $this->esp->newEspecialidadPeriodo($espe_periodo);
			}else{
				$this->esp->updateEspecialidadPeriodo(['estado'=>1],[
					'id_especialidad'=>$this->input->post('id_especialidad'),
					'id_periodo'=> $this->input->post('id_periodo')
				]);
				$id_especialidad_periodo = $relacio->id;
				$existe_especialidad_periodo = true;
			}
			/*$busca_grupo = $this->esp->searchGrupoEspecialidad($id_especialidad_periodo);
			if(is_numeric($busca_grupo) && $this->input->post('apertura_grupo')){
				$data = [
					'id_especialidad'			=> $this->input->post('id_especialidad'),
					'id_periodo'				=> $this->input->post('id_periodo'),
					'id_turno'					=> $this->input->post('turno'),
					'id_especialidad_periodo'	=> $id_especialidad_periodo
				];
				$g = $this->alumno->newGrupo($data);
			}*/
			echo json_encode(['status'=>200,'message'=>'Registro Satisfactorio','data'=>[
				'codigo'			=> mb_strtoupper($this->input->post('codigo')),
				'nombre'			=> mb_strtoupper($this->input->post('nombre')),
				'vacantes'			=> $this->input->post('vacantes'),
				'id'				=> $id_especialidad,
				'existe'			=> $existe_especialidad_periodo ? 1 : 0
			]]);
			//echo json_encode(['status'=>200,'data'=>[],'message'=>'Registro Satisfactorio']);
			exit();
		}
		$periodos = $this->esp->getPeriodos();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Especialidad',
			'content'			=> $this->load->view('especialidad/nuevo',[
				'parameters'		=> $this->parameters,
				'periodos'			=> $periodos,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function malla($id_periodo,$id_especialidad,$id_turno){
		$periodo = $this->esp->getPeriodo($id_periodo);
		$especialidad = $this->esp->getEspecialidad($id_especialidad);
		$ciclos = $this->esp->getCiclos();
		$turno = $this->esp->getTurno($id_turno);
		$relacio = $this->esp->searchEspecialidadPeriodo([
				'id_especialidad'=>$id_especialidad,
				'id_periodo'=> $id_periodo,
				'id_turno'	=> $id_turno
			]);
		$malla = $this->CargaMalla($relacio->id);
		/*echo '<pre>';
		print_r($malla);
		echo '</pre>';
		exit();*/
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Especialidad',
			'content'			=> $this->load->view('especialidad/malla',[
				'parameters'		=> $this->parameters,
				'periodo'			=> $periodo,
				'especialidad'		=> $especialidad,
				'especialidad_perio'=> $relacio,
				'malla'				=> $malla,
				'ciclos'			=> $ciclos,
                'id_especialidad'   => $id_especialidad,
                'turno'				=> $turno,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}
        
        public function traePeriodosEspecialidad(){
            if(!$this->input->post())
                header ('Location: '.base_url());
            $periodos = $this->esp->getEspecialidadesPeriodo_($this->input->post('id_especialidad'),$this->input->post('id_especialidad_periodo'));
            if(!is_numeric($periodos))
                echo json_encode (['status'=>200,'data'=>['periodos'=>$periodos],'message'=>'Resultados encontrados']);
            else
                echo json_encode (['status'=>202,'data'=>[],'message'=>'Error en la consulta']);
        }
        
        public function importarMalla(){
            if(!$this->input->post())
                header ('Location: '.base_url());
            $relacio = $this->esp->searchEspecialidadPeriodo([
                            'id_especialidad'=>$this->input->post('id_especialidad'),
                            'id_periodo'=> $this->input->post('id_last_periodo'),
                            'id_turno'=> $this->input->post('id_last_turno')
                    ]);
            $relacion_ = $this->esp->searchEspecialidadPeriodo([
                            'id_especialidad'=>$this->input->post('id_especialidad'),
                            'id_periodo'=> $this->input->post('id_periodo'),
                            'id_turno'	=> $this->input->post('id_turno')
                    ]);
            $malla = [];
            if(!is_numeric($relacio)){
                $malla = $this->CargaMalla($relacio->id);
                if(count($malla) != 0){
                    foreach ($malla as $key => $value) {
                        foreach($value as $k => $v){
                            $data = array(
                                'id_curso'				=> $v->id_curso,
                                'id_ciclo'				=> $v->id_ciclo,
                                'id_especialidad'                   => $this->input->post('id_especialidad'),
                                'orden'                             => ($k+1),
                                'id_turno'				=> $this->input->post('id_turno')
                            );
                            $id_cursoCiclo = $this->esp->newCursoCiclo($data);	
                            $periodo_malla = array(
                                'id_malla'                          => $id_cursoCiclo,
                                'id_ciclo'                          => $v->id_ciclo,
                                'id_especialidad_periodo'           => $relacion_->id,
                                'estado'                            => 1
                            );
                            $id_periodo_malla = $this->esp->newMallaPeriodo($periodo_malla);
                        }
                    }
                }
            }
            else{
                echo json_encode(['status'=>202,'data'=>[],'message'=>'No existen registros de malla en este periodo']);
                exit();
            }
            if(count($malla) == 0)
                echo json_encode(['status'=>202,'data'=>[],'message'=>'No existen registros de malla en este periodo']);
            echo json_encode(['status'=>200,'data'=>['malla'=>$malla,'relacion'=>$relacio],'message'=>'Resultados']);
        }

	public function newMalla(){
		if(!$this->input->post())
			header('Location: '.base_url());
			$data = array(
				'id_curso'				=> $this->input->post('id_curso'),
				'id_ciclo'				=> $this->input->post('id_ciclo'),
				'id_especialidad'		=> $this->input->post('id_especialidad'),
				'id_turno'				=> $this->input->post('id_turno'),
				'orden'					=> $this->input->post('orden')
			);
			$id_cursoCiclo = $this->esp->newCursoCiclo($data);
		/*$cursoCiclo = $this->esp->getCursoCiclo($this->input->post('id_curso'),$this->input->post('id_ciclo'),$this->input->post('id_especialidad'));
		if(is_numeric($cursoCiclo)){
			//$reg = $this->esp->getCursoCiclo()
		}else{
			$id_cursoCiclo = $cursoCiclo->id;
		}*/
			$periodo_malla = array(
				'id_malla'					=> $id_cursoCiclo,
				'id_ciclo'					=> $this->input->post('id_ciclo'),
				'id_especialidad_periodo'	=> $this->input->post('id_especialidad_periodo'),
				'estado'					=> 1
			);
			$id_periodo_malla = $this->esp->newMallaPeriodo($periodo_malla);
		/*$periodo_ciclo = $this->esp->getPeriodoCiclo($this->input->post('id_especialidad_periodo'),$id_cursoCiclo);
		if(is_numeric($periodo_ciclo)){
		}
		else{
			$id_periodo_malla = $periodo_ciclo->id;
			echo json_encode(['status'=>202,'data'=>['id_periodo_ciclo'=>$id_periodo_ciclo],'message'=>'El curso ya se encuentra programado para este periodo']);
			exit();
		}*/
		echo json_encode(['status'=>200,'data'=>['id_malla'=>$id_cursoCiclo,'id_malla_periodo'=>$id_periodo_malla],'message'=>'Registro Satisfactorio']);
	}

	function CargaMalla($id_especialidad_periodo){
		$data = $this->esp->getMallaPeriodo($id_especialidad_periodo);
		$cursos = [];
		if(is_numeric($data))
			return false;
		else{
			$cur = [];
			foreach ($data as $key => $value) {
				if(isset($cursos[$value->id_ciclo]))
					$cur = $cursos[$value->id_ciclo];
				array_push($cur, $value);
				$cursos[$value->id_ciclo] = $cur;
				$cur = [];
			}
		}
		return $cursos;
	}

	public function updateMalla(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$cursoCiclo = $this->esp->getCursoCiclo($this->input->post('id_curso'),$this->input->post('id_ciclo'),$this->input->post('id_especialidad'));
		$this->esp->updateMallaPeriodo([
			'id_malla'					=> $this->input->post('id_malla'),
			'id_especialidad_periodo'	=> $this->input->post('id_especialidad_periodo')
		],['estado'=>0]);
		echo json_encode(['status'=>200,'data'=>$cursoCiclo,'message'=>'Actualización Satisfactoria']);
		exit();
	}

	public function retiraCursoMalla(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$this->esp->quitaCursoMallaPeriodo(['id_malla'=>$this->input->post('id_malla'),'id_especialidad_periodo'=>$this->input->post('id_especialidad_periodo')]);
		$this->esp->quitaCursoMalla(['id'=>$this->input->post('id_malla')]);
		echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualización Satisfactoria']);
	}

	public function baja(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$this->esp->updateEspecialidadPeriodo(['estado'=>0],[
			'id'=>$this->input->post('id')
			//'id_periodo'=>$this->input->post('id_periodo')
			]);
		echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualización Satisfactoria']);
	}

	public function autocompleteespecialidad(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$espe = $this->esp->searchEspecialidad($this->input->post('patron'));
		$d = array();
		if(!is_numeric($espe))
			foreach ($espe as $key => $value) 
				array_push($d, array('value'=>$value->nombre,'data'=>json_encode(array('id'=>$value->id,'codigo'=>$value->codigo))));
        print json_encode(array('suggestions'=>$d));
	}

	public function traeEspecialidades(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$esps = $this->esp->getEspecialidades();
		echo json_encode(['status'=>200,'data'=>$esps,'message'=>'Consulta Satisfactoria']);
	}

	public function getEsepecialidades(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$esps = $this->esp->getEspecialidadesPeriodoForNOmbre($this->input->post('nombre'));
		if(!is_numeric($esps))
			echo json_encode(['status'=>200,'data'=>$esps,'message'=>'Consulta Satisfactoria']);
		else
			echo json_encode(['status'=>202,'data'=>$esps,'message'=>'No existen especialidades abiertas en este periodo']);
	}

	public function forNuewCurso(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$ciclos = $this->esp->getCiclos();
		$modulos = $this->esp->getModulosEspecialidad($this->input->post('id_especialidad'));
		$espe_periodo = $this->esp->getEspecialidadPeriodo($this->input->post('id_periodo'),$this->input->post('id_especialidad'),1);
		if(is_numeric($ciclos))
			echo json_encode(['status'=>202,'message'=>'No existen datos correctos','data'=>['ciclos'=>$ciclos,'modulos'=>$modulos]]);
		else
			echo json_encode(['status'=>200,'message'=>'Consulta Satisfactoria','data'=>['ciclos'=>$ciclos,'modulos'=>$modulos,'espe_periodo'=>$espe_periodo]]);
	}

	public function cursos(){
		/*if(!$this->input->post())
			header('Location: '.base_url());*/
			$esps = $this->esp->getEspecialidades();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Especialidad',
			'content'			=> $this->load->view('especialidad/cursos',[
				'parameters'		=> $this->parameters,
				'especialidades'	=> $esps,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function cargaTipoModulos(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$modulos = 0;
		if($this->input->post('id_tipo') == '1')
			$modulos = $this->esp->getTiposModulos($this->input->post('id_tipo'),$this->input->post('id_especialidad'));
		if($this->input->post('id_tipo') == '2')
			$modulos = $this->esp->getTiposModulos_($this->input->post('id_tipo'),$this->input->post('id_especialidad'));
		if($this->input->post('id_tipo') == '3')
			$modulos = $this->esp->getTiposModulos__($this->input->post('id_tipo'),$this->input->post('id_especialidad'));
		if(is_numeric($modulos))
			echo json_encode(['status'=>202,'message'=>'No existen modulos','data'=>['modulos'=>$modulos]]);
		else
			echo json_encode(['status'=>200,'message'=>'Consulta Satisfactoria','data'=>['modulos'=>$modulos]]);
	}

	public function traeCursos(){
		if(!$this->input->post())
			header('Location: '.base_url());
                if($this->input->post('id_especialidad') == 0)
                    $cursos = $this->esp->getCursosEspecialidadTransversal();
                else
                    $cursos = $this->esp->getCursosEspecialidadPeriodo($this->input->post('id_especialidad'),$this->input->post('all'));
            foreach ($cursos as $key => $value) {
            	if($value->id_padre != 0){
            		$mod = $this->esp->getModuloForId($value->id_padre);
            		$cursos[$key]->modulo_padre = $mod->nombre;
            	}else{
            		$cursos[$key]->modulo_padre = '-';
            	}
            }
		if(is_numeric($cursos)){
			echo json_encode(['status'=>202,'data'=>[],'message'=>'No existen cursos en esta especialidad y este periodo']);
			exit();
		}
		echo json_encode(['status'=>200,'data'=>$cursos,'message'=>'Consulta Satisfactoria']);
		//echo json_encode(value)
	}

	public function getCursoAutocomplete(){
		if(!$this->input->post())
            header('Location: '.base_url());
        $cursos = $this->esp->getCursosAutocomplete($this->input->post('data'));
        $a = array();
        if(!is_numeric($cursos))
        foreach ($cursos as $key => $value) 
            array_push($a, array('value'=>$value->nombre,'data'=>json_encode($value)));
        print json_encode(array('suggestions'=>$a));
	}

	public function traeDetalles(){
		if(!$this->input->post())
            header('Location: '.base_url());
        $detalle = $this->esp->getCursoModulo($this->input->post('id_curso'));
        $nota_minima = $this->esp->getCursoNotaMinima($this->input->post('id_curso'));
        echo json_encode(['status'=>200,'data'=>['detalle'=>$detalle,'nota_minima'=>$nota_minima],'message'=>'Consulta satisfactoria']);
        exit();
	}

	public function registroCurso(){
		if(!$this->input->post())
			header('Location: '.base_url());
		if($this->input->post('nombre') == ''){
			echo json_encode(['status'=>202,'data'=>[],'message'=>'Ingrese los datos correctos']);
			exit();
		}
		if($this->input->post('creditos') == ''){
			echo json_encode(['status'=>202,'data'=>[],'message'=>'Ingrese los datos correctos']);
			exit();
		}
		if($this->input->post('horas') == ''){
			echo json_encode(['status'=>202,'data'=>[],'message'=>'Ingrese los datos correctos']);
			exit();
		}
		$nom = explode(' ', mb_strtoupper($this->input->post('nombre')));
		//if($this->input->post('id_curso') == 0){
			$data = [
				'nombre'					=> mb_strtoupper($this->input->post('nombre')),
				//
				'subcodigo_curso'			=> substr($nom[0], 0,1).''.(count($nom)>1 ? substr($nom[1], 0,1) :'0'),
				'estado'					=> 1,
				'id_especialidad'			=> $this->input->post('especialidad'),
				'creditos'					=> $this->input->post('creditos'),
				'horas'						=> $this->input->post('horas'),
				'descripcion'				=> $this->input->post('descripcion') ? $this->input->post('descripcion') : 'Descripción',
				'codigo'					=> $this->input->post('codigo'),
				'electivo'					=> $this->input->post('electivo') ? 1 : 0,
				'temas'						=> $this->input->post('temas') ? $this->input->post('temas') : 'Temas'
			];
			$curso = $this->esp->newCurso($data);
			$id_curso = $curso->id_curso;
		$curs = $this->esp->getCursoEspecialidad($this->input->post('especialidad'),$id_curso,$this->input->post('modulo'));
		if(is_numeric($curs)){
			$cursoModulo = [
				'id_curso'			=> $id_curso,
				'id_especialidad'	=> $this->input->post('especialidad'),
				'id_tipo_curso'		=> $this->input->post('tipo_curso'),
				'id_modulo'			=> $this->input->post('tipo_curso') == 2 || $this->input->post('tipo_curso') == 4 ? 0 : $this->input->post('modulo') ,
				'estado'			=> 1
			];
			$this->esp->newCursoEspecialidad($cursoModulo);
		}else{
			$this->esp->updateCursoEspecialidad([
				'id_especialidad'		=> $this->input->post('especialidad'),
				'id_curso'				=> $id_curso,
				'id_modulo'				=> $this->input->post('id_modulo')
			],['estado'=>1]);
		}
		$tipo_evaluacion = [
			'id_curso'			=> $id_curso,
			'tipo_eval'			=> $this->input->post('tipo_eval'),
			'eval_minima'		=> $this->input->post('tipo_eval') == 1 ? $this->input->post('notaminimavalor') : $this->input->post('notaminimaletra')
		];
		$this->esp->newTipoEvalCursoRegistro($tipo_evaluacion);
		echo json_encode(['status'=>200,'data'=>[],'message'=>'Registro Satisfactorio']);
		exit();	
	}

	public function modulos(){
		$especialidades = $this->esp->getEspecialidades();
		$modulos = $this->esp->getModulos();
		$periodos = $this->esp->getPeriodos_();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Especialidad',
			'content'			=> $this->load->view('especialidad/modulos',[
				'parameters'		=> $this->parameters,
				'especialidades'	=> $especialidades,
				'modulos'			=> $modulos,
				'periodos'			=> $periodos,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function getOnlyModules(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $modulos = $this->esp->getModulosOnly($this->input->post('id_especialidad'));
        if(is_numeric($modulos))
            echo json_encode (['status'=>202,'data'=>[],'message'=>'Sin resultados']);
        else
            echo json_encode (['status'=>200,'data'=>$modulos,'message'=>'Resultados encontrados']);
	}
        
    public function getModules(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $modulos = $this->esp->getModulosForEspecialidadAlumno($this->input->post('id_especialidad'),$this->input->post('cod_alumno'));
        foreach ($modulos as $key => $value) {
        	$where = [
        		'id_modulo'				=> $value->id_modulo,
        		'id_especialidad'		=> $value->id_especialidad,
        		'cod_alumno'			=> $this->input->post('cod_alumno')
        	];
        	$nota = $this->practica->getNota($where);
        	if(is_numeric($nota)){
        		$modulos[$key]->nota = 0;
        	}else{
        		$profesor = $this->seccion->getProfesorForId($nota->id_profesor);
        		$modulos[$key]->nota = [
        			'id'					=> $nota->id,
        			'tipo_eval'				=> $nota->id_tipo_evaluacion,
        			'eval'					=> $nota->valor_nota,
        			'fecha_acta'			=> $nota->fecha_acta,
        			'id_turno'				=> $nota->id_turno,
        			'nombre_prof'			=> !is_numeric($profesor) ? $profesor->apell_pat.' '.$profesor->apell_mat.' '.$profesor->nombre : '' , 
        			'id_prof'				=> !is_numeric($profesor) ? $profesor->id_profesor : 0
        		];
        	}
        }
        if(is_numeric($modulos))
            echo json_encode (['status'=>202,'data'=>[],'message'=>'Sin resultados']);
        else
            echo json_encode (['status'=>200,'data'=>$modulos,'message'=>'Resultados encontrados']);
    }

    public function getNotasAlumno(){
    	if(!$this->input->post())
            header('Location: '.base_url());
        $notas = $this->esp->getNotasCursoAlumnoMatricula($this->input->post('cod_alumno'));
        foreach ($notas as $key => $value) {
        	$no_regular = $this->evaluaciones->getConsultaEvaluacionesNoRegulares($value->id_alumno_matricula_curso,2,1);
        	if(!is_numeric($notas))
        		$notas[$key]->nota_no_regular = $no_regular;
        }
        if(is_numeric($notas))
            echo json_encode (['status'=>202,'data'=>[],'message'=>'Sin resultados']);
        else
            echo json_encode (['status'=>200,'data'=>$notas,'message'=>'Resultados encontrados']);
    }

    public function getModulesNotas(){
    	if(!$this->input->post())
            header('Location: '.base_url());
        $modulos = $this->esp->getModulosForEspecialidadAlumno($this->input->post('id_especialidad'), $this->input->post('cod_alumno'));
        if(!is_numeric($modulos))
	        foreach ($modulos as $key => $value) {
	        	$where = [
	        		'id_modulo'				=> $value->id_modulo,
	        		'id_especialidad'		=> $value->id_especialidad,
	        		'cod_alumno'			=> $this->input->post('cod_alumno')
	        	];
	        	$nota = $this->practica->getNota($where);
	        	if(is_numeric($nota)){
	        		$modulos[$key]->nota = 0;
	        	}else{
	        		$modulos[$key]->nota = [
	        			'id'		=> $nota->id,
	        			'tipo_eval'	=> $nota->id_tipo_evaluacion,
	        			'eval'		=> $nota->valor_nota
	        		];
	        	}
	        }
        if(is_numeric($modulos))
            echo json_encode (['status'=>202,'data'=>[],'message'=>'Sin resultados']);
        else
            echo json_encode (['status'=>200,'data'=>$modulos,'message'=>'Resultados encontrados']);
    }
    
    public function getOrder(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $orders = $this->esp->getOrders($this->input->post('id_especialidad'));
        $or = [];
        if(!is_numeric($orders))
            foreach ($orders as $key => $value) 
                $or[$value->orden] = $value->id;
        $cant = $this->esp->getCantidadOrders($this->input->post('id_especialidad'));
        $c = 0;
        if(!is_numeric($cant))
            $c = $cant->cantidad;
        echo json_encode(['status'=>200,'data'=>['cantidad'=>$c,'orders'=>$or],'message'=>'Resultados']);
    }

	public function newModulo(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$mod = [
                    'nombre'			=> mb_strtoupper($this->input->post('nombre')),
                    'estado'			=> 1,
                    'orden'             => $this->input->post('orden'),
                    'tipo'              => $this->input->post('tipo'),
                    'id_padre'			=> $this->input->post('tipo') == 1 ? 0 : 1
		];
		if($this->input->post('id_modulo') != 0){
			$this->esp->updateModulooRegistro($mod,['id'=>$this->input->post('id_modulo')]);
			$id_modulo = $this->input->post('id_modulo');
		}
		else
			$id_modulo = $this->esp->newModuloRegistro($mod);
		$rel = [
			'id_especialidad'	=> $this->input->post('especialidad'),
			'id_modulo'			=> $id_modulo,
			'estado'			=> 1
		];
		if($this->input->post('id_especialidad_modulo') != 0)
			$this->esp->updateEspecialidadModulo($rel,['id'=>$this->input->post('id_especialidad_modulo')]);
		else
			$this->esp->newEspecialidadModulo($rel);
		echo json_encode(['status'=>200,'data'=>[],'message'=>'Registro Satisfactorio']);
		exit();
	}

	public function getEspecialidadModulos(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$mo = $this->esp->getEspecialidadModulo($this->input->post('id_especialidad_modulo'));
		if(is_numeric($mo))
			echo json_encode(['status'=>202,'data'=>[],'message'=>'No existen datos correctos']);
		else
			echo json_encode(['status'=>200,'data'=>$mo,'message'=>'Consulta Satisfactoria']);
	}

	public function getInforCurso(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$curso = $this->esp->getCurso($this->input->post('codigo'));
		$seccion = $this->esp->getSeccion($curso->id);
		if(!is_numeric($seccion)){
			echo json_encode(['status'=>200,'data'=>['curso'=>$curso,'seccion'=>$seccion],'message'=>'Consulta Satisfactoria']);
		}else{
			echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron resultados']);
		}
	}
        
        public function getInforCurso_(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$curso = $this->esp->getCurso($this->input->post('codigo'));
                $infor = $this->esp->getInfoCurso($curso->id);
		if(!is_numeric($curso)){
			echo json_encode(['status'=>200,'data'=>['curso'=>$curso,'infor'=>$infor],'message'=>'Consulta Satisfactoria']);
		}else{
			echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron resultados']);
		}
	}
	
}