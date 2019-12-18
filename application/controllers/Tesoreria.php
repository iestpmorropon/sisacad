<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tesoreria extends CI_Controller {

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
            if(!$this->session->userdata('usuario'))
                header('Location: '.base_url());
    }

    public function tupa(){
        $tupa = $this->pagos->getAllTupa();
//        echo '<pre>';
//        print_r($tupa);
//        echo '</pre>';
//        exit();
        $categorias_tupa = $this->pagos->getCategoriasTupa();
        $data = [
                'parameters'		=> $this->parameters,
                'usuario'			=> $this->session->userdata('usuario'),
                'module'			=> 'Tesoreria',
                'content'			=> $this->load->view('tesoreria/tupa',[
                        'parameters'		=> $this->parameters,
                        'tupa'                  => $tupa,
                        'categoria'             => $categorias_tupa,
                        'usuario'               => $this->session->userdata('usuario')
                        ],true)
        ];
        $this->load->view('body',$data);
    }
    
    public function newConcepto(){
        if(!$this->input->post())
            header ('Location: '.base_url());
        $data = [
            'nombre'                => $this->input->post('nombre_concepto'),
            'costo'                 => $this->input->post('costo_concepto'),
            'id_categoria_tupa'     => $this->input->post('categoria')
        ];
        if($this->input->post('id_tupa') != 0){
            $this->pagos->updateConcepto($data,$this->input->post('id_tupa'));
            $id = $this->input->post('id_tupa');
        }
        else
            $id = $this->pagos->newConcepto($data);
        $data['id'] = $id;
        echo json_encode(['status'=>200,'data'=>$data,'message'=>'Registro Satisfactorio']);
    }
    
    public function consultaFiltro(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $where = '';
        if($this->input->post('alumno') == '0'){
            $hasta  = date('Y-m-d',strtotime(date($this->input->post('hasta')).' +1 days'));
            $where = "tp.fch_pago BETWEEN '".$this->input->post('desde')."' and '".$hasta."'";
        }else{
            $where = "tp.cod_alumno = '".$this->input->post('alumno')."'";
        }
        $pagos = $this->pagos->getPagos($where);
        if(!is_numeric($pagos))
            echo json_encode(['status'=>200,'data'=>['pagos'=>$pagos],'message'=>'Consulta satisfactoria']);
        else
            echo json_encode(['status'=>202,'data'=>[],'message'=>'Error en la consulta']);
        exit();
        var_dump($this->input->post(null,true));
        exit();
    }

    public function caja(){
        //$usuarios_caja = $this->usuario->getUsuarioPersonalice(['tpr.id_rol'=>4]);
        $data = [
                'parameters'		=> $this->parameters,
                'usuario'			=> $this->session->userdata('usuario'),
                'module'			=> 'Tesoreria',
                'content'			=> $this->load->view('tesoreria/caja',[
                        'parameters'		=> $this->parameters,
                        'pagos'                 => [],
                        //'usuarios'              => $usuarios_caja,
                        'usuario'			=> $this->session->userdata('usuario')
                        ],true)
        ];
        $this->load->view('body',$data);
    }
    
    public function getPago(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $pago = $this->pagos->getPagoForId($this->input->post('id_pago'));
        if(is_numeric($pago))
            echo json_encode (['status'=>202,'data'=>[],'message'=>'Sin resultados']);
        else
            echo json_encode (['status'=>200,'data'=>$pago,'message'=>'Consulta satisfactoria']);
    }
    
    public function emitirpago(){
        $tupa = $this->pagos->getTupa();
        $periodo_actual = $this->periodo->getPeriodoActivo();
        $u = $this->session->userdata('usuario');
        $data = [
                'parameters'		=> $this->parameters,
                'usuario'			=> $u,
                'module'			=> 'Pagos',
                'content'			=> $this->load->view('tesoreria/emitirpagos',[
                        'parameters'		=> $this->parameters,
                        'tupa'                  => $tupa,
                        'periodo_actual'        => $periodo_actual,
                        'usuario'		=> $this->session->userdata('usuario')
                    ],true)
        ];
        $this->load->view('body',$data);
    }
    
    public function procesar_pago(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $u = $this->session->userdata('usuario');
        $pago = $this->pagos->getPagoForId($this->input->post('id_tupa'));
        switch ($this->input->post('concepto')){
            case 'Matrícula Regular':
                //evaluando si el alumno tiene problemas
                /*$data = [
                    'cod_alumno'                            => $this->input->post('codigo_alumno'),
                    'id_especialidad'                       => $this->input->post('id_especialidad'),
                    'id_periodo'                            => $this->input->post('periodo'),
                    'fch_registro'                          => date('Y-m-d'),
                    'id_ciclo'                              => $this->input->post('id_ciclo'),
                    'id_grupo'                              => $this->input->post('id_grupo'),
                    'id_turno'                              => $this->input->post('id_turno'),
                    'estado_semestre'                       => 1,
                    'pagado'                                => 1
                ];
                $matricula = $this->alumno->newMatricula($data);
                $this->alumno->updateCicloInEspecialidad($this->input->post('codigo_alumno'),$this->input->post('id_ciclo'));
                $cursos = $this->periodo->getCursosMalla($this->input->post('id_especialidad_periodo'),$this->input->post('id_ciclo'));
        		if(!is_numeric($cursos)){
        			foreach ($cursos as $key => $value) {
        				$curso_matricula = [
        					'id_alumno_matricula'                           => $matricula,
        					'id_curso'					=> $value->id_curso,
        					'id_malla_periodo'                              => $value->id_malla_periodo,
        					'id_modulo'					=> $value->id_modulo,
        					'id_ciclo'					=> $value->id_ciclo,
        					'estado'					=> 1
        				];
        				$this->alumno->newMatriculaCurso($curso_matricula);
        			}
                        $this->session->set_userdata('cursos_matriculados',$cursos);
                }
                $this->session->set_userdata('data_matricula',$this->input->post(null,true));*/
                $data_pago = [
                    'id_tupa'                   => $this->input->post('id_tupa'),
                    'id_alumno_matricula'       => $matricula,
                    'id_usuario'                => $u['id_usuario'],
                    'monto'                     => $pago->costo,
                    'estado'                    => 1,
                    'cod_voucher'               => $this->input->post('voucher') ? $this->input->post('voucher') : '',
                    'fch_pago'                  => date('Y-m-d H:i:s'),
                    'fch_registro'              => date('Y-m-d'),
                    'url_comprobante'           => ''
                ];
                $id_pago = $this->pagos->newPago($data_pago);
                echo json_encode(['status'=>200,'data'=>[
                    'id_matricula'              => $matricula,
                    'id_pago'                   => $id_pago,
                    'id_ciclo'                  => $this->input->post('id_ciclo'),
                    'codigo_alumno'             => $this->input->post('codigo_alumno'),
                    'fch_pago'                  => $data_pago['fch_pago']
                        ],'message'=>'Registro Satisfactorio']);
                exit();
                break;
            default:{
                $data_pago = [
                    'id_tupa'                   => $this->input->post('id_tupa'),
                    'id_alumno_matricula'       => $this->input->post('id_alumno_matricula'),
                    'id_usuario'                => $u['id_usuario'],
                    'monto'                     => $pago->costo,
                    'estado'                    => 1,
                    'cod_voucher'               => $this->input->post('voucher') ? $this->input->post('voucher') : '',
                    'fch_pago'                  => date('Y-m-d H:i:s'),
                    'fch_registro'              => date('Y-m-d'),
                    'url_comprobante'           => '',
                    'observacion'               => $this->input->post('observacion')
                ];
                $id_pago = $this->pagos->newPago($data_pago);
                $data_solicitud = [
                    'id_pago'               => $id_pago,
                    'cod_alumno'            => $this->input->post('codigo_alumno'),
                    'id_usuario'            => $u['id_usuario'],
                    'fecha'                 => date('Y-m-d H:i:s'),
                    'observacion'           => ''
                ];
                $solicitud = $this->pagos->newSolicitudPago($data_solicitud);
                echo json_encode(['status'=>200,'data'=>[
                    'id_pago'                   => $id_pago,
                    'codigo_alumno'             => $this->input->post('codigo_alumno'),
                    'fch_pago'                  => $data_pago['fch_pago']
                ],'message'=>'Registro Satisfactorio']);
            }
                break;
        }
    }

    public function registrarPago(){
        if(!$this->input->post())
            header('Location: '.base_url());
        /*echo '<pre>';
        print_r($this->input->post(null,true));
        echo '</pre>';
        exit();*/
        $u = $this->session->userdata('usuario');
        $pago = $this->pagos->getPagoForId($this->input->post('id_tupa'));
        $data_pago = [
            'id_tupa'                   => $this->input->post('id_tupa'),
            'cod_alumno'                => $this->input->post('id_alumno'),
            'id_usuario'                => $u['id_usuario'],
            'monto'                     => $pago->costo,
            'estado'                    => 1,
            'cod_voucher'               => $this->input->post('voucher') ? $this->input->post('voucher') : '',
            'fch_pago'                  => date('Y-m-d H:i:s'),
            'fch_registro'              => date('Y-m-d'),
            'observacion'               => $this->input->post('observacion')
        ];
        $id_pago = $this->pagos->newPago($data_pago);
        echo json_encode(['status'=>200,'data'=>[
                    'id_pago'                   => $id_pago,
                    'cod_alumno'                => $this->input->post('id_alumno'),
                    'fch_pago'                  => $data_pago['fch_pago']
                ],'message'=>'Registro Satisfactorio']);
    }
    
    public function fichapago($id_pago=0){
        if($id_pago == 0)
            header('Location: '.base_url());
	$this->load->library('pdf',$this->parameters);
        $pago = $this->pagos->getPago($id_pago);
        $data_alumno = $this->alumno->getEspecialidadPeriodoForId($pago->id_alumno_matricula);
        $credentials = $this->alumno->getDataAlumno(['codigo'=>$data_alumno->cod_alumno]);
        $data_matricula = $this->session->userdata('data_matricula');
        $data = [
            'id_pago'               => $id_pago,
            'data'                  => $data_alumno,
            'credenciales'          => $credentials,
            'data_matricula'        => $data_matricula
//            'cursos'		=> $cursos
        ];
        $this->pdf->ficha_matricula($data,$this->parameters);
    }
    
    public function comprobantepago($id_pago = 0){
        if($id_pago == 0)
            header('Location: '.base_url());
	    $this->load->library('impresion',$this->parameters);
        $pago = $this->pagos->getPago($id_pago);
        $tupa = $this->pagos->getPagoForId($pago->id_tupa);
        $periodo = $this->periodo->getPeriodoActivo();
        $credentials = $this->alumno->getDataAlumno(['codigo'=>$pago->cod_alumno]);
//        echo '<pre>';
//        print_r($pago);
//        print_r($data_alumno);
//        echo '</pre>';
//        exit();
        $data = [
            'id_pago'               => $id_pago,
            'periodo'               => $periodo,
            'credenciales'          => $credentials,
            'pago'                  => $pago,
            'tupa'                  => $tupa,
//            'data_matricula'        => $data_matricula
//            'cursos'		=> $cursos
        ];
        $this->impresion->impresion_comprobante($data);
    }
    
    public function preparar_pago(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $periodos = $this->periodo->getPeriodoActivo();
        if(is_numeric($periodos)){
            echo json_encode (['status'=>202,'data'=>[],'message'=>'No existe ningún periodo activo']);
            exit();
        }
            echo json_encode(['status'=>200,'data'=>['periodos'=>[$periodos]],'message'=>'Resultados encontrados']);
            /*
        else
            echo json_encode(['status'=>202,'data'=>[],'message'=>'El alumno no se encuentra habilitado por repitencia']);
        $alumno_matricula = $this->alumno->getEspecialidadAlumno($this->input->post('alumno'));
        if(is_numeric($alumno_matricula))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'Error en la consulta consulte con su administrador']);
        else{
            if($alumno_matricula->estado == 1)
        }*/
    }
}