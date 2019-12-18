<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regulares extends CI_Controller {

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
            $this->load->model('Evaluaciones','evaluaciones');
            $this->load->model('Periodo','periodo');
            if(!$this->session->userdata('usuario'))
                    header('Location: '.base_url());
    }
    
    public function index(){
        $especialidades = $this->esp->getEspecialidades();
        $periodos = $this->esp->getPeriodosCerrados();
        $data = [
                'parameters'		=> $this->parameters,
                'usuario'			=> $this->session->userdata('usuario'),
                'module'			=> 'Evaluaciones',
                'content'			=> $this->load->view('regulares/index',[
                        'parameters'                    => $this->parameters,
                        'especialidades'            => $especialidades,
                        'periodos'                  => $periodos,
                        'usuario'			=> $this->session->userdata('usuario')
                        ],true)
        ];
        $this->load->view('body',$data);
    }

    public function getInfoAlumnoCurso(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $nota = $this->evaluaciones->getNotaAlumnoCursoRegular_($this->input->post('id'));
        if(is_numeric($nota))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'Registros no encontrados']);
        else
            echo json_encode(['status'=>200,'data'=>$nota,'message'=>'Registros']);
    }

    public function getNotaAlumnoCurso(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $notas = $this->evaluaciones->getNotaAlumnoCursoRegular($this->input->post('id_periodo'),$this->input->post('id_especialidad'),$this->input->post('id_turno'),$this->input->post('id_ciclo'));
        if(is_numeric($notas))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'Registros no encontrados']);
        else
            echo json_encode(['status'=>200,'data'=>$notas,'message'=>'Registros']);
    }

    public function actualizarNota(){
        if(!$this->input->post())
            header('Location: '.base_url());
        if($this->input->post('eval_minima') <= $this->input->post('nota_nueva'))
            $status = 1;
        else
            $status = 0;
        $this->evaluaciones->updateNotaAlumnoCursoRegular($this->input->post('id'),['valor_nota'=>$this->input->post('nota_nueva'),'estado'=>$status,'estado_final'=>$status]);
        echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualizacion completa']);
    }

    public function chargeRegistroNota(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $periodos = $this->alumno->getPeriodosCursosNoEncontrados($this->input->post('id_curso'));
        $tipos_nota = $this->evaluaciones->getTipoNotaNoRegulares();
        $tipo_eval = $this->evaluaciones->getTipoEvalForCurso($this->input->post('id_curso'));
        echo json_encode(['status'=>200,'data'=>['periodos'=>$periodos,'tipos_nota'=>$tipos_nota,'tipo_eval'=>$tipo_eval],'message'=>'Consulta completa']);
    }

    public function chargeTurnosForPeriodo(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $turnos = $this->alumno->getTurnosPeriodosCursosNoEncontrados($this->input->post('id_curso'),$this->input->post('id_periodo'));
        if(is_numeric($turnos))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No existen registros de turnos']);
        else
            echo json_encode(['status'=>200,'data'=>$turnos,'message'=>'Consulta completa']);
    }

    public function registroNotaAux(){
        if(!$this->input->post())
            header('Location: '.base_url());
        /*echo '<pre>';
        print_r($this->input->post(null,true));
        echo '</pre>';
        exit();*/
        $where = [
            'id_tipo_nota_no_regular'   => $this->input->post('id_tipo_nota_no_regular'),
            'id_especialidad'           => $this->input->post('id_especialidad'),
            'id_periodo'                => $this->input->post('id_periodo'),
            'id_ciclo'                  => $this->input->post('id_ciclo'),
            'id_turno'                  => $this->input->post('id_turno'),
            'fecha'                     => date('Y-m-d',strtotime(date($this->input->post('fecha'))))
        ];
        if($this->input->post('crear_acta')){
            $acta = $this->evaluaciones->getActaNoRegular($where);
            if(is_numeric($acta))
                $id_acta = $this->evaluaciones->newActaNoRegular($where);
            else{
                $id_acta = $acta->id;
            }
        }else{
            $id_acta = 0;
        }
        $estado = 0;
        if($this->input->post('eval_minima') <= $this->input->post('valor_nota'))
            $estado = 1;
        $data_no_regular_aux = [
            'cod_alumno'                    => $this->input->post('cod_alumno'),
            'id_especialidad'               => $this->input->post('id_especialidad'),
            'id_periodo'                    => $this->input->post('id_periodo'),
            'id_turno'                      => $this->input->post('id_turno'),
            'id_ciclo'                      => $this->input->post('id_ciclo'),
            'id_tipo_nota_no_regular'       => $this->input->post('id_tipo_nota_no_regular'),
            'id_curso'                      => $this->input->post('id_curso'),
            'valor_nota'                    => $this->input->post('valor_nota'),
            'estado'                        => $estado,
            'fecha_acta'                    => date('Y-m-d',strtotime(date($this->input->post('fecha')))),
            'fecha_registro'                => date('Y-m-d'),
            'id_profesor'                   => $this->input->post('id_profesor'),
            'id_acta'                       => $id_acta
        ];
        $this->evaluaciones->newNotaNoRegularAux($data_no_regular_aux);
        echo json_encode(['status'=>200,'data'=>['id_acta'=>$id_acta],'message'=>'Registro satisafactorio']);
    }
}