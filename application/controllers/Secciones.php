<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Secciones extends CI_Controller {

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
    
    public function index(){
        $periodos = $this->esp->getPeriodos();
        $esps = $this->esp->getEspecialidades();
        $data = [
            'parameters'		=> $this->parameters,
            'usuario'			=> $this->session->userdata('usuario'),
            'module'			=> 'Periodos',
            'content'			=> $this->load->view('secciones/index',[
                    'parameters'		=> $this->parameters,
                    ///'especialidades'	=> $especialidades,
                    //'tipoalumno'		=> 'regular',
                    'especialidades'            => $esps,
                    'periodos'                  => $periodos,
                    'usuario'			=> $this->session->userdata('usuario')
                    ],true)
        ];
        $this->load->view('body',$data);
    }
    
    public function traeSecciones(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $secciones = $this->seccion->getSeccionesInfo($this->input->post('id_periodo'),$this->input->post('id_especialidad'));
        if(!is_numeric($secciones))
            echo json_encode (['status'=>200,'data'=>$secciones,'message'=>'Resultados']);
        else
            echo json_encode (['status'=>202,'data'=>[],'message'=>'Sin resultados']);
    }
    
    public function alumnos($id_seccion_curso = 0){
        if($id_seccion_curso == 0)
            header ('Location: '.base_url('secciones'));
        $seccion_curso = $this->seccion->getSeccion($id_seccion_curso);
        $alumnos = $this->seccion->getAlumnosForSeccion($seccion_curso->id_malla_periodo);
        $malla_periodo = $this->periodo->getMallaPeriodoForId($seccion_curso->id_malla_periodo);
        $especialidad_periodo = $this->periodo->getEspecialidadPeriodo($malla_periodo->id_especialidad_periodo);
        $curso = $this->esp->getCursoForId($seccion_curso->id_curso);
        /*echo '<pre>';
        print_r($especialidad_periodo);
        echo '</pre>';
        exit();*/
        $data = [
            'parameters'		=> $this->parameters,
            'usuario'			=> $this->session->userdata('usuario'),
            'module'			=> 'Periodos',
            'content'			=> $this->load->view('secciones/alumnos',[
                    'parameters'		=> $this->parameters,
                    ///'especialidades'	=> $especialidades,
                    //'tipoalumno'		=> 'regular',
                    'seccion_curso'             => $seccion_curso,
                    'alumnos'                   => $alumnos,
                    'especialidad_periodo'      => $especialidad_periodo,
                    'curso'                     => $curso,
                    'malla_periodo'             => $malla_periodo,
                    'usuario'			=> $this->session->userdata('usuario')
                    ],true)
        ];
        $this->load->view('body',$data);
    }
        
}