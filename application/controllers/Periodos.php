<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periodos extends CI_Controller {

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
		if(!$this->session->userdata('usuario'))
			header('Location: '.base_url());
	}

	public function academicos(){
		//$especialidades = $this->esp->getEspecialidades();
		//$tipoalumno = $this->alumno->getTipoAlumno();
		$per = $this->esp->getPeriodos();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Periodos',
			'content'			=> $this->load->view('periodos/academicos/index',[
				'parameters'		=> $this->parameters,
				///'especialidades'	=> $especialidades,
				//'tipoalumno'		=> 'regular',
				'periodos'			=> $per,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function nuevo(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$data = [
			'nombre'				=> $this->input->post('anio').'-'.$this->input->post('ciclo'),
			'fch_inicio'			=> date('Y-m-d',strtotime($this->input->post('fch_inicio'))),
			'fch_fin'				=> date('Y-m-d',strtotime($this->input->post('fch_fin'))),
			'matricula_inicio'		=> date('Y-m-d',strtotime($this->input->post('matricula_inicio'))),
			'matricula_fin'			=> date('Y-m-d',strtotime($this->input->post('matricula_fin'))),
			'clases_inicio'			=> date('Y-m-d',strtotime($this->input->post('clases_inicio'))),
			'clases_fin'			=> date('Y-m-d',strtotime($this->input->post('clases_fin'))),
			'tipo'					=> $this->input->post('tipo'),
			'estado'				=> 0
		];
		if($this->input->post('id_periodo')){
			$this->esp->updatePeriodo($data,['id'=>$this->input->post('id_periodo')]);
			echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualización Satisfactoria']);
		}
		else{
			$this->esp->newPeriodo($data);
			echo json_encode(['status'=>200,'data'=>[],'message'=>'Registro Satisfactorio']);
		}
	}

	public function editar($id = 0){
		if($id == 0)
			header('Location: '.base_url());
		//
		$lista = $this->seccion->getSeccionesForPeriodo($id);
		$periodo = $this->esp->getPeriodo($id);
		$esps = $this->esp->getEspecialidadesPeriodo($id);
		//$profesores = $this->esp->getProfesores();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Periodos',
			'content'			=> $this->load->view('periodos/secciones/lista',[
				'parameters'		=> $this->parameters,
				'lista'				=> $lista,
				'periodo'			=> $periodo,
				'especialidades'	=> $esps,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function cerrarPeriodoAcademico(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$alumnos_matricula = $this->periodo->getAlumnosMatriculaCierre($this->input->post('id_periodo'));
		if(!is_numeric($alumnos_matricula))
			foreach ($alumnos_matricula as $key => $value) {
				$cantidad_desaprobados = 0;
				$cursos = $this->periodo->getCursosAlumnoMatriculaCierre($value->id);
				if(!is_numeric($cursos))
					foreach ($cursos as $k => $v) {
						if($v->tipo_eval == 1){
							if($v->valor_nota == '' || (int)$v->valor_nota < $v->eval_minima)
								$cantidad_desaprobados += 1;
						}else{
							if($v->valor_nota == '' || $v->valor_nota > $v->eval_minima)
								$cantidad_desaprobados += 1;
						}
					}
				$espe_periodo = $this->periodo->getEspecialdiadPer($this->input->post('id_periodo'),$value->id_especialidad,$value->id_ciclo,$value->id_turno);
				if(!is_numeric($espe_periodo)){
					if($espe_periodo->cursos_minimos_repite <= $cantidad_desaprobados)
						$this->alumno->updateAlumnoMatricula(['id'=>$value->id],['estado_semestre'=>2]);
					else
						$this->alumno->updateAlumnoMatricula(['id'=>$value->id],['estado_semestre'=>1]);
				}
			}
		$especialidades = $this->esp->updateEspecialidadPeriodo([
			'estado'			=> 0
		],[
			'id_periodo'		=> $this->input->post('id_periodo'),
			'id_ciclo_actual'	=> 6
		]);
		$secciones = $this->seccion->updateTSeccionCurso([
			'id_periodo'			=> $this->input->post('id_periodo')
		],[
			'estado'				=> 3
		]);
		$this->periodo->updatePeriodo([
			'id'					=> $this->input->post('id_periodo'),
		],[
			'estado'				=> 2
		]);
		echo json_encode(['status'=>200,'data'=>['especialidades'=>$especialidades,'secciones'=>$secciones],'message'=>'Actualización Satisfactoria']);
	}

	public function cargaSecciones(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$lista = $this->seccion->getSeccionesForPeriodo($this->input->post('id_periodo'));
		if(is_numeric($lista))
			echo json_encode(['status'=>202,'data'=>[],'message'=>'Sin resultados en la busqueda']);
		else
			echo json_encode(['status'=>200,'data'=>$lista,'message'=>'Consulta satisfactoria']);
		exit();
	}
        
        public function getSeccioneCurso(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $lista = $this->seccion->getSeccionesForPeriodo($this->input->post('id_periodo'));
            //$lista = $this->seccion->getSeccionesForPeriodo($this->input->post('id_periodo'));
            if(is_numeric($lista))
                    echo json_encode(['status'=>202,'data'=>[],'message'=>'Sin resultados en la busqueda']);
            else
                    echo json_encode(['status'=>200,'data'=>$lista,'message'=>'Consulta satisfactoria']);
            exit();
        }

	public function cursosForSections(){
		if(!$this->input->post())
			header('Location: '.base_url());
		if($this->input->post('id_turno'))
			$id_turno = $this->input->post('id_turno');
		else
			$id_turno = -1;
		$espe_periodo = $this->esp->getEspecialidadPeriodos($this->input->post('id_periodo'),$this->input->post('id_especialidad'),$id_turno);
		$cursos = [];
		if(!is_numeric($espe_periodo))
		foreach ($espe_periodo as $key => $value) {
			$curs = $this->periodo->getCursosMallaAnteriores($value->id_ciclo_actual+1,$value->id);
			if(!is_numeric($curs))
				$cursos = array_merge($curs,$cursos);
		}
		echo json_encode(['status'=>200,'data'=>['cursos'=>$cursos,'id_especialidad_periodo'=>is_numeric($espe_periodo) ? 0 : $espe_periodo[0]->id],'message'=>'Consulta Satisfactoria']);
		exit();
		/*$espe_periodo = $this->esp->getEspecialidadPeriodo($this->input->post('id_periodo'),$this->input->post('id_especialidad'));
		//del ciclo 1
		if(is_numeric($espe_periodo)){
			$cursos = [];
			$id_especialidad_periodo = 0;
		}
		else{
			$cursos = $this->periodo->getCursosMalla($espe_periodo->id,1);
			$id_especialidad_periodo = $espe_periodo->id;
		}
		//busqueda del periodo anterior
		$periodo = $this->esp->getPeriodo($this->input->post('id_periodo'));
		$periodo_anterior = $this->periodo->getPeriodoAnterior($periodo->fch_inicio);
		if(!is_numeric($periodo_anterior)){
			$secciones_anteriores = $this->seccion->getSecciones(['id_periodo'=>$periodo_anterior->id,'estado'=>2]);
			//jalar a que malla pertenece el curso abierto
			$ciclo = 0;
			if(!is_numeric($secciones_anteriores)) foreach ($secciones_anteriores as $key => $value) {
				$espe_periodo_anterior = $this->esp->getTmallaPeriodo($value->id_malla_periodo);
				$malla = $this->periodo->getMallaCursoEspecialidad($value->id_curso,$this->input->post('id_especialidad'),$espe_periodo_anterior->id_especialidad_periodo);
				if(!is_numeric($malla) && $ciclo != $malla->id_ciclo){
					//$espe_periodo_anterior = $this->esp->getEspecialidadPeriodo($periodo_anterior->id,$this->input->post('id_especialidad'));
					$curs = $this->periodo->getCursosMallaAnteriores($this->input->post('id_especialidad'),$malla->id_ciclo+1,$espe_periodo_anterior->id_especialidad_periodo);
					$cursos = array_merge(is_numeric($cursos) ? [] : $cursos,is_numeric($curs) ? [] : $curs);
					$ciclo = $malla->id_ciclo;
				}
			}

		}*/
		echo json_encode(['status'=>200,'data'=>['cursos'=>$cursos,'id_especialidad_periodo'=>$id_especialidad_periodo],'message'=>'Consulta Satisfactoria']);
		//$this->periodo
	}

	public function getPeriodo(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$per = $this->esp->getPeriodo($this->input->post('id_periodo'));
		$ob = new stdclass();
		$ob->id = $per->id;
		$ob->nombre = $per->nombre;
		$ob->fch_inicio = date('d-m-Y',strtotime($per->fch_inicio));
		$ob->fch_fin = date('d-m-Y',strtotime($per->fch_fin));
		$ob->matricula_inicio = date('d-m-Y',strtotime($per->matricula_inicio));
		$ob->matricula_fin = date('d-m-Y',strtotime($per->matricula_fin));
		$ob->clases_inicio = date('d-m-Y',strtotime($per->clases_inicio));
		$ob->clases_fin = date('d-m-Y',strtotime($per->clases_fin));
		$ob->estado = $per->estado;
		if(is_numeric($ob))
			echo json_encode(['status'=>202,'data'=>$ob,'message'=>'Resultados encontrados']);
		else
			echo json_encode(['status'=>200,'data'=>$ob,'message'=>'Resultados encontrados']);
	}

	public function nuevaSeccion(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$cur = $this->esp->getCurso($this->input->post('curso'));
		$periodo_promocional = $this->periodo->getPeriodoFechaPromocional($this->input->post('promocion'));
		$especialidad_periodo_promocional = $this->esp->getEspecialidadPeriodo($periodo_promocional->id,$this->input->post('especialidad'),$this->input->post('turno'));
		//consulto la malla que esta programada para esa promocion
		$malla_promocion = $this->esp->getMallaPeriodoCurso($especialidad_periodo_promocional->id,$cur->id);
		$seccion = $this->seccion->exitsSeccion($this->input->post('especialidad'),$malla_promocion->id,$cur->id,$this->input->post('turno'));
		if(is_numeric($seccion)){
			$data = array(
				'id_especialidad'				=> $this->input->post('especialidad'),
				'id_curso'						=> $cur->id,
				'id_profesor'					=> 1,
				'id_periodo'					=> $this->input->post('id_periodo'),
				'id_turno'						=> $this->input->post('turno'),
				'id_malla_periodo'				=> $malla_promocion->id
				);
			try{
				$resp = $this->seccion->newSeccion($data);
				//$this->seccion->updateEspecialidadPeriodo();
			}catch(Exception $e){
				echo json_encode(['status'=>202,'data'=>[],'message'=>'Error en el registro consulte con su Administrador']);
				exit();
			}
		}else{
			$resp = $seccion;
		}
		echo json_encode([
			'status'=>200,
			'data'=>[
				'curso'		=> $cur,
				'seccion'	=> $resp
			],
			'message'=>'Registro Satisfactorio'
		]);
		exit();
	}

	public function activaPeriodo(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$espe_periodo = $this->esp->getEspecialidadPeriodos($this->input->post('id_periodo'),$this->input->post('especialidad'),$this->input->post('turno'));
				foreach ($espe_periodo as $key => $value) {
					$this->esp->updateEspecialidadPeriodo([
						'id_ciclo_anterior'			=> $value->id_ciclo_actual,
						'id_periodo_anterior'		=> $value->id_periodo_actual,
						'id_ciclo_actual'			=> $value->id_ciclo_actual+1,
						'id_periodo_actual'			=> $this->input->post('id_periodo')
					],[
						'id'						=> $value->id
					]);
				}
		$this->periodo->updatePeriodo([
			'id'					=> $this->input->post('id_periodo'),
		],[
			'estado'				=> 1
		]);
	}

	public function traeSeccionesForProfesor(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$secciones = $this->seccion->getSeccionesForProfesor($this->input->post('id_profesor'),$this->input->post('id_periodo'));
		if(is_numeric($secciones))
			echo json_encode(['status'=>202,'data'=>[],'message'=>'No tiene secciones asiganadas.']);
		else
			echo json_encode(['status'=>200,'data'=>$secciones,'message'=>'Resultados encontrados']);
	}

}