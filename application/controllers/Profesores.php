<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profesores extends CI_Controller {

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
		if(!$this->session->userdata('usuario'))
			header('Location: '.base_url());
	}

	public function index(){
		//$especialidades = $this->esp->getEspecialidades();
		$profesores = $this->esp->getProfesores();
		foreach ($profesores as $key => $value) {
			$consulta = $this->esp->consultProfesorJefe($value->id_profesor);
			if(!is_numeric($consulta))
				$profesores[$key]->jefe = $consulta;
		}
		/*echo '<pre>';
		print_r($profesores);
		echo '</pre>';
		exit();*/
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Personal',
			'content'			=> $this->load->view('profesores/index',[
				'parameters'		=> $this->parameters,
				'profesores'	=> $profesores,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function setQuitarAsignacion(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$this->esp->deleteProfesorJefe($this->input->post('id_jefe'));
		$this->usuario->deletePermisoRol(['id_usuario'=>$this->input->post('id_usuario'),'id_rol'=>5]);
		echo json_encode(['status'=>200,'data'=>['id_jefe'=>$this->input->post('id_jefe')],'message'=>'Actualizacion satisfactorio']);
		/*$consulta = $this->esp->getProfesorJefe($id_profesor,$id_especialidad);
		if(!is_numeric($consulta)){
			echo json_encode(['status'=>202,'data'=>[],'message'=>''])
		}*/
	}

	public function setAsignar(){
		if(!$this->input->post())
			header('Location: '.base_url());
		//$consulta = $this->esp->getProfesorJefe($id_profesor,$id_especialidad);
		/*if(!is_numeric($consulta)){
			echo json_encode(['status'=>202,'data'=>[],'message'=>''])
		}*/
		$data = [
			'id_profesor'			=> $this->input->post('id_profesor'),
			'id_especialidad'		=> $this->input->post('id_especialidad'),
			'estado'				=> 1
		];
		$id_jefe = $this->esp->setProfesorJefe($data);
		$data_perfil = [
			'id_usuario'			=> $this->input->post('id_usuario'),
			'id_rol'				=> 5,
			'estado'				=> 1,
			'fch_inicio'			=> date('Y-m-d')
		];
		$id_perfil = $this->usuario->newPermisoRol($data_perfil);
		echo json_encode(['status'=>200,'data'=>['id_jefe'=>$id_jefe,'id_perfil'=>$id_perfil],'message'=>'Registro satisfactorio']);
	}

	public function preparaAsignacion(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$espe = $this->esp->getEspecialidades();
		echo json_encode(['status'=>200,'data'=>['especialidades'=>$espe],'message'=>'Consulta satisfactoria']);
	}

	public function nuevo(){
		if($this->input->post()){
			$data = [
				'nombre'			=> mb_strtoupper($this->input->post('nombre')),
				'apell_pat'			=> mb_strtoupper($this->input->post('apell_pat')),
				'apell_mat'			=> mb_strtoupper($this->input->post('apell_mat')),
				'dni'				=> $this->input->post('dni'),
				'telefono'			=> $this->input->post('telefono') ? $this->input->post('telefono') : '',
				'celular_1'			=> $this->input->post('celular_1') ? $this->input->post('celular_1') : '',
				'direccion'			=> $this->input->post('direccion') ? mb_strtoupper($this->input->post('direccion')) : '',
				'email'				=> $this->input->post('email') ? $this->input->post('email') : 'email@email.com',
				'fch_nac'			=> $this->input->post('fch_nac') ? date('Y-m-d',strtotime($this->input->post('fch_nac'))) : date('Y-m-d'),
				'fch_registro'		=> date('Y-m-d'),
				'fch_salida'		=> date('Y-m-d'),
				'id_genero'			=> $this->input->post('genero') != 'Seleccione genero' ? $this->input->post('genero') : 3,
				'id_estado_civil'	=> $this->input->post('estado_civil') != 'Seleccione Estado Civil' ? $this->input->post('estado_civil') : 1,
				'estado'			=> 1
			];
			if(!$this->input->post('id_profesor'))
				$id = $this->persona->newPersona($data);
			else{
				$id = $this->input->post('id_persona');
				$this->persona->updatePersona($data,['id'=>$this->input->post('id_persona')]);
			}
			$u = [
				'usuario'			=> $this->input->post('dni'),
				'password'			=> sha1(md5('123456')),
				'estado'			=> 1,
				'id_persona'		=> $id,
				'url_imagen'		=> ''
			];
			if(!$this->input->post('id_profesor'))
				$id_usuario = $this->alumno->registerUsuario($u);
			else{
				$id_usuario = $this->input->post('id_usuario');
				$this->alumno->updateUsuario($u,['id'=>$this->input->post('id_usuario')]);
			}
			//asignacion para el rol de profesor
			$rol = $this->persona->getUsuarioRol($id_usuario,3);
			if(is_numeric($rol)){
				$rol = [
					'id_usuario'			=> $id_usuario,
					'id_rol'				=> 3,
					'estado'				=> 1,
					'fch_inicio'			=> date('Y-m-d'),
					'fch_fin'				=> date('Y-m-d')
				];
				$this->persona->newUsuarioRol($rol);
			}
			$p = [
				'codigo'			=> $this->input->post('codigo'),
				'id_usuario'		=> $id_usuario,
				'fch_ingreso'		=> date('Y-m-d'),
				'estado'			=> 1
			];
			if(!$this->input->post('id_profesor'))
				$data = $this->esp->newProfesor($p);
			else{
				$this->esp->updateProfesor($p,['id'=>$this->input->post('id_profesor')]);
				$data = new StdClass;
				$data->codigo_prof = $p['codigo'];
				$data->id_profesor = $id;
			}
			echo json_encode(['status'=>200,'data'=>$data,'message'=>'Registro Satisafactorio']);
			exit();
		}
		$genero = $this->alumno->getGenero();
		$estado_civil = $this->alumno->getEstadoCivil();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Personal',
			'content'			=> $this->load->view('profesores/nuevo',[
				'parameters'		=> $this->parameters,
				'genero'			=> $genero,
				'estado_civil'		=> $estado_civil,
				'profesor'			=> 0,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function editar($id=0){
		if($id == 0)
			header('Location: '.base_url());
		$prof = $this->esp->getProfesor($id);
		$genero = $this->alumno->getGenero();
		$estado_civil = $this->alumno->getEstadoCivil();
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Personal',
			'content'			=> $this->load->view('profesores/nuevo',[
				'parameters'		=> $this->parameters,
				'genero'			=> $genero,
				'estado_civil'		=> $estado_civil,
				'profesor'			=> $prof,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
		//$this->esp->
	}

	public function getProfesoresAutocomplete(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$espe = $this->seccion->searchProfesor($this->input->post('data'));
		$d = array();
		if(!is_numeric($espe))
			foreach ($espe as $key => $value) 
				array_push($d, array('value'=>$value->apell_pat.' '.$value->apell_mat.' '.$value->nombre,'data'=>json_encode(array('id'=>$value->id_profesor,'codigo'=>$value->codigo,'nombre'=>$value->nombre,'apellidos'=>$value->apell_pat.' '.$value->apell_mat))));
        print json_encode(array('suggestions'=>$d));
	}

	public function asignacionProfesor(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$this->seccion->updateTSeccionCurso([
			'id'			=> $this->input->post('id_seccion_curso')
		],[
			'id_profesor'	=> $this->input->post('id_profesor')
		]);
		$prof = $this->seccion->getProfesorForId($this->input->post('id_profesor'));
		echo json_encode(['status'=>200,'data'=>$prof,'message'=>'Actualizacion satisfactoria']);
	}
        
        public function cerrarCursoForProfesor(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $this->seccion->updateTSeccionCurso([
                'id'                            => $this->input->post('id_seccion_curso')
            ],[
                'estado'                        => 1
            ]);
            echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualizacion satisfactoria']);
        }
        
        public function cerrarSeccionCursoForProfesor(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $notas = $this->input->post('notas');
            /*echo '<pre>';
            print_r($notas);
            echo '</pre>';
            exit();*/
            foreach($notas as $k => $v){
            	$nota = $this->alumno->getNotaCursoAlumno($v['id']);
            	if(!is_numeric($nota)){
            		if($nota->tipo_eval == 1){
            			if($nota->valor_nota >= $nota->eval_minina)
            				$estado_mat_curso = 1;
            			else
            				$estado_mat_curso = 0;
            		}else{
            			if($nota->valor_nota >= $nota->eval_minina)
            				$estado_mat_curso = 0;
            			else
            				$estado_mat_curso = 1;
            		}
            		$this->alumno->updateAlumnoMatriculaCurso(['id_curso'=>$nota->id_curso,'id_alumno_matricula'=>$nota->id_alumno_matricula],['estado'=>$estado_mat_curso,'valor_nota'=>$nota->valor_nota]);
            	}
                //$resp = $this->alumno->actualizaEstadoSemestre(['id_nota'=>$v['id']]);
            }
            $this->seccion->updateTSeccionCurso([
                'id'                            => $this->input->post('id_seccion_curso')
            ],[
                'estado'                        => 2
            ]);
            $this->seccion->updateTseccion([
                'id'                            => $this->input->post('id_seccion')
            ],[
                'estado'                        => 0
            ]);
            echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualizacion satisfactoria']);
        }

}