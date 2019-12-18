<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Practicas extends CI_Controller {

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
            $this->load->model('Periodo','periodo');
            if(!$this->session->userdata('usuario'))
                    header('Location: '.base_url());
    }
    
    public function index(){
        $esps = $this->esp->getEspecialidades();
        $data = [
                'parameters'		=> $this->parameters,
                'usuario'			=> $this->session->userdata('usuario'),
                'module'			=> 'Impresos',
                'content'			=> $this->load->view('practicas/index',[
                        'parameters'                    => $this->parameters,
                        'especialidades'                => $esps,
                        'usuario'			=> $this->session->userdata('usuario')
                        ],true)
        ];
        $this->load->view('body',$data);
    }

    public function registro(){
        $esps = $this->esp->getEspecialidades();
        $data = [
                'parameters'        => $this->parameters,
                'usuario'           => $this->session->userdata('usuario'),
                'module'            => 'Evaluaciones',
                'content'           => $this->load->view('practicas/registro',[
                        'parameters'                    => $this->parameters,
                        'especialidades'                => $esps,
                        'usuario'           => $this->session->userdata('usuario')
                        ],true)
        ];
        $this->load->view('body',$data);
    }

    public function registrarNota(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $notas = $this->input->post('notas');
        foreach ($notas as $key => $value) 
            if($value['eval'] != ''){
                $data = [
                    'nombre_practica'               => '',
                    'id_modulo'                     => $value['id'],
                    'cod_alumno'                    => $this->input->post('cod_alumno'),
                    'id_especialidad'               => $this->input->post('id_especialidad'),
                    'id_turno'                      => $value['turno'],
                    'id_profesor'                   => $value['profe'],
                    'fecha_acta'                    => date('Y-m-d',strtotime(date($value['fecha_acta']))),
                    'fecha_reg'                     => date('Y-m-d'),
                    'id_tipo_evaluacion'            => $value['tipo_eval'],
                    'valor_nota'                    => $value['eval'],
                ];
                if(!isset($value['id_eval']) && !$value['editado'])
                    $this->practica->newPractica($data);
                else
                    $this->practica->updatePractica($data,['id'=>$value['id_eval']]);
            }
        echo json_encode(['status'=>200,'data'=>[],'message'=>'Registro exitoso']);
        exit();
    }

    public function eliminarEvaluacionPractica(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $this->practica->deletePractica($this->input->post('id'));
        echo json_encode(['status'=>200,'data'=>[],'message'=>'Eliminacion Satisfactoria']);
    }

    public function consulta(){
        if(!$this->input->post())
            header('Location: '.base_url());
        if($this->input->post('fechaStart'))
            $where = [
                'fechaStart'    =>      date('Y-m-d',strtotime(date($this->input->post('fechaStart')))),
                'fechaEnd'      =>      date('Y-m-d',strtotime(date($this->input->post('fechaEnd'))))
            ];
        if($this->input->post('turno') != 0)
            $where['id_turno'] = $this->input->post('turno');
        if($this->input->post('especialidad') != 0)
            $where['id_especialidad'] = $this->input->post('especialidad');
        if($this->input->post('cod_alumno'))
            $where['cod_alumno'] = $this->input->post('cod_alumno');
        $pract = $this->practica->getPracticasAlumnoData($where);
        if(!is_numeric($pract)){
            $this->session->set_userdata('datos_acta_practica',$pract);
            echo json_encode(['status'=>200,'data'=>$pract,'message'=>"resultados encontrados"]);
        }
        else
            echo json_encode(['status'=>202,'data'=>[],'message'=>"Sin resultados"]);
        exit();
    }

    public function imprimiracta($cod_alumno){
        if($cod_alumno == '')
            header('Location: '.base_url());
        $this->load->library('impreactas',$this->parameters);
        $alumnos = $this->practica->getPracticasAlumnoData(['cod_alumno'=>$cod_alumno]);
        /*echo '<pre>';
        print_r($alumno);
        echo '</pre>';
        exit();*/
        foreach ($alumnos as $k => $val) {
            $nota = $this->practica->getNota_(['cod_alumno'=>$cod_alumno]);
            $alumnos[$k]->nota = $nota;
            if(!is_numeric($nota))
            foreach ($nota as $key => $value) {
                $cursos = $this->periodo->getCursosModulo([
                    'id_modulo'             => $value->id_modulo,
                    'id_especialidad'       => $value->id_especialidad,
                    'estado'                => 1
                ]);
                if(!is_numeric($cursos))
                    $nota[$key]->cursos = $cursos;
                else
                    $nota[$key]->cursos = 0;
            }
            else
                header('Location: '.base_url());
            $informacion = $this->alumno->getAlumnoInf($cod_alumno);
            $alumnos[$k]->informacion = $informacion;
            $profesor = $this->seccion->getProfesorForId($alumnos[$k]->id_profesor);
            $alumnos[$k]->profesor = $profesor;
        }
        /*$data = [
            'notas'             => $nota,
            'cod_alumno'        => $cod_alumno,
            'informacion'       => $informacion
        ];*/
        $this->impreactas->imprimirActaPracticasenMasa(['alumnos'=>$alumnos]);
    }

    public function imprimiractas(){
        if(!$this->session->userdata('datos_acta_practica'))
            header('Location: '.base_url());
        $this->load->library('impreactas',$this->parameters);
        $alumnos = $this->session->userdata('datos_acta_practica');
        foreach ($alumnos as $k => $val) {
            $nota = $this->practica->getNota_(['cod_alumno'=>$val->cod_alumno]);
            foreach ($nota as $key => $value) {
                $cursos = $this->periodo->getCursosModulo([
                    'id_modulo'             => $value->id_modulo,
                    'id_especialidad'       => $value->id_especialidad,
                    'estado'                => 1
                ]);
                if(!is_numeric($cursos))
                    $nota[$key]->cursos = $cursos;
                else
                    $nota[$key]->cursos = 0;
            }
            $alumnos[$k]->nota = $nota;
            $informacion = $this->alumno->getAlumnoInf_($val->cod_alumno);
            /*echo '<pre>';
            print_r($val);
            echo '</pre>';
            exit();*/
            $alumnos[$k]->informacion = $informacion;
            $profesor = $this->seccion->getProfesorForId($val->id_profesor);
            $alumnos[$k]->profesor = $profesor;
        }
        $data = [
            'alumnos'           => $alumnos
        ];
        $this->impreactas->imprimirActaPracticasenMasa($data);
    }
}