<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permisos extends CI_Controller {

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
		//$usuarios = $this->usuario->getUsuarios();
		$roles = $this->usuario->getRoles_();
		$usuarios = [];
		/*echo '<pre>';
		print_r($this->session->userdata('usuario'));
		echo '</pre>';
		exit();*/
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Personal',
			'content'			=> $this->load->view('permisos/index',[
				'parameters'		=> $this->parameters,
				'usuario'			=> $this->session->userdata('usuario'),
				'usuarios'			=> $usuarios,
				'roles'				=> $roles
				],true)
		];
		$this->load->view('body',$data);
	}

	public function guardarPermisos(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$id_perfil = $this->input->post('id_perfil');
		$modulos = $this->input->post('modulos');
		$padres = [];
		foreach ($modulos as $key => $value) {
			$data = [
				'id_rol'			=> $id_perfil,
				'id_modulo'			=> $value['id_modulo']
			];
			$permiso = $this->usuario->getPermisoWhere($data);
			if ( is_numeric($permiso) ) {
				$data['estado'] = $value['valor'];
				$this->usuario->newPermiso($data);
			}else{
				$this->usuario->updatePermiso($data,['estado'=>$value['valor']]);
			}
			if(!isset($padres[$value['id_padre']]))
				$padres[$value['id_padre']] = 0;
			if($value['valor'] == 1)
				$padres[$value['id_padre']] += 1;
		}
		foreach ($padres as $key => $value) {
			$data = [
				'id_rol'			=> $id_perfil,
				'id_modulo'			=> $key
			];
			$permiso = $this->usuario->getPermisoWhere($data);
			if ( is_numeric($permiso) ) {
				$data['estado'] = $value == 0 ? 0 : 1;
				$this->usuario->newPermiso($data);
			}else{
				$this->usuario->updatePermiso($data,['estado'=>$value == 0 ? 0 : 1]);
			}
		}
		//$padres = $this->usuario->getPermisoPadre();
		echo json_encode(['status'=>200,'data'=>[],'message'=>'Registro satisfactorio']);
	}

	public function gestionroles(){
		$roles = $this->usuario->getRoles_();
		$modulos = $this->esp->getModules([]);
		/*echo '<pre>';
		print_r($modulos);
		echo '</pre>';
		exit();*/
		$usuarios = [];
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Personal',
			'content'			=> $this->load->view('permisos/gestion',[
				'parameters'		=> $this->parameters,
				'usuario'			=> $this->session->userdata('usuario'),
				'usuarios'			=> $usuarios,
				'modulos'			=> $modulos,
				'roles'				=> $roles
				],true)
		];
		$this->load->view('body',$data);
	}

	public function getPermisosPerfil(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$permisos = $this->usuario->getPermisosForPerfil($this->input->post('id_perfil'));
		if(!is_numeric($permisos))
			echo json_encode(['status'=>200,'data'=>['permisos'=>$permisos],'message'=>'Consulta satisfactoria']);
		else
			echo json_encode(['status'=>202,'data'=>[],'message'=>'Error consulte con su administrador']);
	}

	public function editar($id){
		$usuario = $this->usuario->getUsuario(['tu.id'=>$id]);
		/*echo '<pre>';
		print_r($usuario);
		echo '</pre>';
		exit();*/
		$genero = $this->alumno->getGenero();
		$estado_civil = $this->alumno->getEstadoCivil();
		$roles = $this->usuario->getRoles_();
		$modulos = $this->esp->getModules([]);
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Personal',
			'content'			=> $this->load->view('permisos/editar',[
				'parameters'		=> $this->parameters,
				'usuario'			=> $this->session->userdata('usuario'),
				'usuario_edit'		=> $usuario,
				'genero'			=> $genero,
				'estado_civil'		=> $estado_civil,
				'roles'				=> $roles,
				'modulos'			=> $modulos
				],true)
		];
		$this->load->view('body',$data);
	}

	public function nuevo(){
		$genero = $this->alumno->getGenero();
		$estado_civil = $this->alumno->getEstadoCivil();
		$roles = $this->usuario->getRoles_();
		$modulos = $this->esp->getModules([]);
		/*echo '<pre>';
		print_r($modulos);
		echo '</pre>';
		exit();*/
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Personal',
			'content'			=> $this->load->view('permisos/editar',[
				'parameters'		=> $this->parameters,
				'usuario'			=> $this->session->userdata('usuario'),
				'genero'			=> $genero,
				'estado_civil'		=> $estado_civil,
				'roles'				=> $roles,
				'modulos'			=> $modulos
				],true)
		];
		$this->load->view('body',$data);
	}

	public function consulta(){
		if(!$this->input->post())
			header('Location: '.base_url());
		if($this->input->post('id_usuario'))
			$usuarios = $this->usuario->getUsuarioForId($this->input->post('id_usuario'));
		else
			$usuarios = $this->usuario->getUsuariosForPerfil($this->input->post('id_rol'));
		if(!is_numeric($usuarios))
			echo json_encode(['status'=>200,'data'=>['usuarios'=>$usuarios],'message'=>'Consulta satisfactoria']);
		else
			echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron registros']);
	}
}