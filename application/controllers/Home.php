<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
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
                $this->load->model('Pagos','pagos');
	}

	public function index()
	{
		$data = [
			'parameters'		=> $this->parameters
		];
		if($this->input->post()){
			$u = $this->usuario->getUsuario(['usuario'=>$this->input->post('username')]);
			$user = $this->login($u);
			header('Location: '.base_url());
		}
		$u = $this->session->userdata('usuario');
		if(!isset($u['id_usuario']))
			$this->load->view('login',$data);
		else{
			$data['usuario'] = $this->session->userdata('usuario');
			$data['module']	= '';
                        //Data para el dashboard
                        $data['user_rol'] = $data['usuario']['rol_actual'];
                        $periodo = $this->periodo->getPeriodoActivo();
                        if(!is_numeric($periodo))
                            $alumnos = $this->alumno->getCantidadAlumnos($periodo->id);
                        else
                            $alumnos = 0;
                        $secciones = $this->seccion->getSeccionesAbiertas();
                        $cachimbos = $this->alumno->getCachimbos();
                        $cursos_a_cargo = 0;
                        if($data['user_rol'] == 'Profesor' && !is_numeric($periodo)){
                            $p = $this->persona->getProfesorForUsuario($data['usuario']['id_usuario']);
//                            $periodo = $this->periodo->getPeriodoActivo();
                            $cursos_a_cargo = $this->seccion->getCantSeccionesForProfesor($p->id,$periodo->id);
                        }
                        $data['data'] = [
                            'periodo'                   => $periodo,
                            'cantidad_estudiantes'      => $alumnos,
                            'cantidad_secciones'        => $secciones,
                            'cantidad_cachimbos'        => $cachimbos,
                            'cantidad_cursos'           => $cursos_a_cargo
                        ];
			$data['content'] = $this->load->view('home/dashboard',$data,true);
			$this->load->view('body',$data);
		}
	}

	public function login($u){
		if(!$u){
			$this->session->set_flashdata('login_check', 'Usuario y Password no válidos');
			return false;
		}
		if($u->password != sha1(md5($this->input->post('password')))){
			$this->session->set_flashdata('login_check', 'Usuario y Password no válidos');
			return false;
		}
		if(!$u->estado){
			$this->session->set_flashdata('login_check', 'Usuario inhabilitado');
			return false;
		}
		$data = [
			'id_usuario'	=> $u->id_usuario,
			'usuario'		=> $u->usuario,
			'id_persona'	=> $u->id_persona,
			'url_imagen'	=> $u->url_imagen ? $u->url_imagen : '',
			'nombre'		=> $u->nombre,
			'apell_pat'		=> $u->apell_pat,
			'apell_mat'		=> $u->apell_mat,
			'dni'			=> $u->dni,
			'telefono'		=> $u->telefono ? $u->telefono : '',
			'celular_1'		=> $u->celular_1 ? $u->celular_1 : '',
			'celular_2'		=> $u->celular_2 ? $u->celular_2 : '',
			'direccion'		=> $u->direccion,
			'email'			=> $u->email,
			'fch_nac'		=> $u->fch_nac,
			'fch_registro'	=> $u->fch_registro,
			'id_genero'		=> $u->id_genero,
			'genero'		=> $u->genero,
			'id_estado_civil'=>$u->id_estado_civil,
			'estado_civil'	=> $u->estado_civil
		];
		$roles = $this->usuario->getRoles($u->id_usuario);
		$data['rol_actual']	= $roles[0]->rol;
		$data['id_rol_actual'] = $roles[0]->id_rol;
		//carga menu
		$modulos = $this->usuario->getModules($roles[0]->id_rol);
		/*echo '<pre>';
		print_r($modulos);
		echo '</pre>';
		exit();*/
		$mods = [];
		foreach ($modulos as $key => $value) {
			if($value['id_padre'] == 0)
				$mods[$value['id']] = $value;
		}
		foreach ($modulos as $key => $value) {
			if($value['id_padre'] != 0){
				if(!isset($mods[$value['id_padre']]['hijos']))
					$mods[$value['id_padre']]['hijos'] = [];
				array_push($mods[$value['id_padre']]['hijos'],$value);
			}
		}
		/*echo '<pre>';
		print_r($mods);
		echo '</pre>';
		exit();*/
		$data['modulos'] = $mods;
		$data['roles'] = $roles;
		$this->session->set_userdata('usuario',$data);
		return $data;
	}

	public function logout(){
		$this->session->set_userdata('usuario',false);
		$this->session->unset_userdata('usuario');
		session_destroy();
		header('Location: '.base_url());
	}

	public function cambiorol($i){
		$u = $this->session->userdata('usuario');
		$r = null;
		foreach ($u['roles'] as $key => $value) {
			if($value->id_rol == $i)
				$r = $value;
		}
		$u['rol_actual'] = $r ? $r->rol : 'usuario';
		$u['id_rol_actual'] = $r ? $r->id_rol : 1;
		$modulos = $this->usuario->getModules($r->id_rol);
		$mods = [];
		foreach ($modulos as $key => $value) {
			if($value['id_padre'] == 0)
				$mods[$value['id']] = $value;
		}
		foreach ($modulos as $key => $value) {
			if($value['id_padre'] != 0){
				if(!isset($mods[$value['id_padre']]['hijos']))
					$mods[$value['id_padre']]['hijos'] = [];
				array_push($mods[$value['id_padre']]['hijos'],$value);
			}
		}
		$u['modulos'] = $mods;
		$this->session->set_userdata('usuario',$u);
		header('Location: '.base_url());
	}

	public function profile(){
		$u = $this->session->userdata('usuario');
		$infor_alumno = null;
		if($u['id_rol_actual'] == 2){
			$al = $this->alumno->getAlumnoForWhere(['id_usuario'=>$u['id_usuario']]);
			if(!is_numeric($al))
				$infor_alumno = $this->alumno->getEspecialidadAlumno($al->codigo);
			else
				$infor_alumno = 0;
		}
		$data = [
                'parameters'        => $this->parameters,
                'usuario'           => $this->session->userdata('usuario'),
                'module'            => 'Home',
                'content'           => $this->load->view('home/profile',[
                        'parameters'                    => $this->parameters,
                        'infor_alumno'				=> $infor_alumno,
                        'usuario'           => $this->session->userdata('usuario')
                        ],true)
        ];
        $this->load->view('body',$data);
	}

	public function actualizarPersona(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$data_actualizar = [
			'telefono'				=> $this->input->post('telefono'),
			'celular_1'				=> $this->input->post('celular_1'),
			'celular_2'				=> $this->input->post('celular_2'),
			'direccion'				=> $this->input->post('direccion'),
			'email'				=> $this->input->post('email'),
			'fch_nac'				=> $this->input->post('fch_nac'),
			'id_genero'				=> $this->input->post('genero'),
			'id_estado_civil'				=> $this->input->post('estado_civil')
		];
		$u = $this->session->userdata('usuario');
		foreach ($data_actualizar as $key => $value) 
			$u[$key] = $value;
		$this->session->set_userdata('usuario',$u);
		$this->persona->updatePersona($data_actualizar,['id'=>$this->input->post('id_persona')]);
		echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualización Satisfactoria']);
	}

	public function actualizarPassword(){
		if(!$this->input->post())
			header('Location: '.base_url());
		if(!$this->input->post('type'))
			if($this->input->post('password') != $this->input->post('repeat_password')){
				echo json_encode(['status'=>202,'data'=>[],'message'=>'Contraseñas incorrectas']);
				exit();
			}
		if($this->input->post('id_usuario'))
			$id_usuario = $this->input->post('id_usuario');
		else{
			$u = $this->session->userdata('usuario');
			$id_usuario = $u['id_usuario'];
		}
		$this->usuario->updateUsuario(['password'=>sha1(md5($this->input->post('password')))],['id'=>$id_usuario]);
		echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualización Satisfactoria']);
	}

	public function preparaperfil(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$u = $this->session->userdata('usuario');
        $genero = $this->alumno->getGenero();
        $estado_civil = $this->alumno->getEstadoCivil();
		echo json_encode(['status'=>200,'data'=>['usuario'=>$u,'generos'=>$genero,'estado_civil'=>$estado_civil],'message'=>'Datos de la persona']);
	}

}
