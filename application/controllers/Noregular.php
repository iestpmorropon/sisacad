<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Noregular extends CI_Controller {

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
        $data = [
                'parameters'		=> $this->parameters,
                'usuario'			=> $this->session->userdata('usuario'),
                'module'			=> 'Evaluaciones',
                'content'			=> $this->load->view('noregular/index',[
                        'parameters'                    => $this->parameters,
                        'usuario'			=> $this->session->userdata('usuario')
                        ],true)
        ];
        $this->load->view('body',$data);
    }

    public function cargaCursosNoEncontrados(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $cursos = $this->periodo->getCursosNoEncontrados($this->input->post('cod_alumno'));
        if(!is_numeric($cursos))
        foreach ($cursos as $key => $value) {
            //$alumno_matricula_curso = $this->
            $nota = $this->evaluaciones->getNotaNoRegularForAlumnoAux($this->input->post('cod_alumno'),$value->id_curso);
            if(!is_numeric($nota))
                $cursos[$key]->valor_nota = $nota->valor_nota;
        }
        if(!is_numeric($cursos))
            echo json_encode(['status'=>200,'data'=>$cursos,'message'=>'Registros encontrados']);
        else
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No existen registros']);
    }

    public function getTipoNotaNoRegular(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $evaluaciones = $this->evaluaciones->getTipoNotaNoRegulares();
        $curso = $this->evaluaciones->getCursoAlumno($this->input->post('id_alumno_matricula_curso'));
        if(!is_numeric($evaluaciones))
            echo json_encode(['status'=>200,'data'=>['evaluaciones'=>$evaluaciones,'curso'=>$curso],'message'=>'Resultados encontrados']);
        else
            echo json_encode(['status'=>200,'data'=>[],'message'=>'Sin resultados']);
    }

    public function impresion(){
        $data = [
                'parameters'        => $this->parameters,
                'usuario'           => $this->session->userdata('usuario'),
                'module'            => 'Evaluaciones',
                'content'           => $this->load->view('noregular/impresion',[
                        'parameters'                    => $this->parameters,
                        'usuario'           => $this->session->userdata('usuario')
                        ],true)
        ];
        $this->load->view('body',$data);
    }

    public function consulta(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $where = [
            'desde'                 => date('Y-m-d',strtotime(date($this->input->post('fechaStart')))),
            'hasta'                 => date('Y-m-d',strtotime(date($this->input->post('fechaEnd'))))
        ];
        $actas = $this->evaluaciones->getActasForFecha($where);
        $registros_actas = $this->evaluaciones->getActasForFecha_($where);
        $actas_aux = $this->evaluaciones->getActasForFechaAux($where);
        $actas = array_merge(is_numeric($actas) ? [] : $actas,is_numeric($actas_aux) ? [] : $actas_aux);
        if(is_numeric($actas))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron actas']);
        else{
            $this->session->unset_userdata('actas_no_regulares');
            $this->session->set_userdata('actas_no_regulares',$registros_actas);
            echo json_encode(['status'=>200,'data'=>$actas,'message'=>'Registros encontrados']);
        }
    }

    public function imprimiractas(){
        if(!$this->session->userdata('actas_no_regulares'))
            header('Location: '.base_url());
        $actas = $this->session->userdata('actas_no_regulares');
            /*echo '<pre>';
            print_r($actas);
            echo '</pre>';
            exit();*/
        $r = [];
        foreach ($actas as $k => $val) {
            $tipo_evaluacion_acta = $this->evaluaciones->getTipoNotaNoRegular($val->id_tipo_nota_no_regular);
            $evaluaciones = $this->evaluaciones->getEvaluacionesNoRegulares($val->id);
            $periodo = $this->periodo->getPeriodoForId($val->id_periodo);
            $especialidad = $this->esp->getEspecialidad($val->id_especialidad);
            $turno = $this->esp->getTurno($val->id_turno);
            $ciclo = $this->esp->getCiclo($val->id_ciclo);
            $cursos = $this->evaluaciones->getCursosAlumnoMatriculaNotaNoRegular($val->id);
            if(!is_numeric($cursos))
                foreach ($cursos as $key => $value) {
                    $r[$value->id] = $value;
                }
            else
                $cursos = [];
            $notas = [];
            $alumnos = [];
            if(!is_numeric($evaluaciones))
            foreach ($evaluaciones as $key => $value) {
                $alumno = $this->evaluaciones->getAlumno($value->id_alumno_matricula_curso);
                $curs  = [];
                $n = $this->evaluaciones->getAlumnoNotaNoRegular($value->id_curso,$value->id_alumno_matricula_curso);
                if(!is_numeric($n))
                    $curs[$value->id_curso] = $n;
                if(!isset($alumnos[$alumno->cod_alumno])){
                    $alumno->cursos = $curs;
                    $alumnos[$alumno->cod_alumno] = $alumno;
                }else{
                    $alu = $alumnos[$alumno->cod_alumno];
                    if($alu->cursos){
                        $c = $alumnos[$alumno->cod_alumno]->cursos;
                        $c[$value->id_curso] = $n;
                        //$c = array_merge($alumnos[$alumno->cod_alumno]->cursos,$curs);
                    }
                    else
                        $c = $curs;
                    $alu->cursos = $c;
                    $alumnos[$alumno->cod_alumno] = $alu;
                }
            }
            $s = [];
            foreach ($alumnos as $key => $value) {
                array_push($s, $value);
            }
            $alumnos = [];
            $evaluaciones_aux = $this->evaluaciones->getEvaluacionesNoRegularesAux($val->id);
            if(!is_numeric($evaluaciones_aux))
                foreach ($evaluaciones_aux as $key => $value) {
                    $alumno = $this->alumno->getAlumnoForCodigo($value->cod_alumno);
                    $curs = [];
                    $curso = $this->evaluaciones->getCursosAuxiliares($value->cod_alumno);
                    foreach ($curso as $ky => $v) {
                        if(!is_numeric($curso))
                            $curs[$v->id_curso] = $v;
                        if(!isset($alumnos[$value->cod_alumno])){
                            $alumno->cursos = $curs;
                            $alumnos[$value->cod_alumno] = $alumno;
                        }else{
                            $alu = $alumnos[$value->cod_alumno];
                            if($alu->cursos){
                                $c = $alumnos[$value->cod_alumno]->cursos;
                                $c[$v->id_curso] = $v;
                                //$c = array_merge($alumnos[$alumno->cod_alumno]->cursos,$curs);
                            }
                            else
                                $c = $curs;
                            $alu->cursos = $c;
                            $alumnos[$alumno->cod_alumno] = $alu;
                        }
                        if(!isset($r[$v->id_curso]))
                            array_push($cursos, $v);
                    }
                }
            foreach ($alumnos as $key => $value) {
                array_push($s, $value);
            }
            $alumnos = $s;
            $data = [
                'periodo'               => $periodo,
                'evaluaciones'          => $evaluaciones,
                'acta'                  => $val,
                'especialidad'          => $especialidad,
                'turno'                 => $turno,
                'cursos'                => $cursos,
                'alumnos'               => $alumnos,
                'tipo_eval_acta'        => $tipo_evaluacion_acta,
                'ciclo'                 => $ciclo
            ];
            $actas[$k]->data = $data;
        }
        $this->load->library('impreactas',$this->parameters);
        /*echo '<pre>';
            print_r($actas);
            echo '</pre>';
            exit();*/
        $this->impreactas->imprimeActaNoRegular($actas,$this->parameters);
    }

    public function imprimeActaNoRegular($id = 0){
        if($id == 0)
            header('Location: '.base_url());
        $acta = $this->evaluaciones->getActaNoRegularForId($id);
        $tipo_evaluacion_acta = $this->evaluaciones->getTipoNotaNoRegular($acta->id_tipo_nota_no_regular);
        $evaluaciones = $this->evaluaciones->getEvaluacionesNoRegulares($id);
        $periodo = $this->periodo->getPeriodoForId($acta->id_periodo);
        $especialidad = $this->esp->getEspecialidad($acta->id_especialidad);
        $turno = $this->esp->getTurno($acta->id_turno);
        $ciclo = $this->esp->getCiclo($acta->id_ciclo);
        $cursos = $this->evaluaciones->getCursosAlumnoMatriculaNotaNoRegular($id);
        /*echo '<pre>';
        print_r($cursos);
        echo '</pre>';
        exit();*/
        $notas = [];
        $alumnos = [];
        if(!is_numeric($evaluaciones))
        foreach ($evaluaciones as $key => $value) {
            $alumno = $this->evaluaciones->getAlumno($value->id_alumno_matricula_curso);
            $curs  = [];
            $n = $this->evaluaciones->getAlumnoNotaNoRegular($value->id_curso,$value->id_alumno_matricula_curso);
            if(!is_numeric($n))
                $curs[$value->id_curso] = $n;
            if(!isset($alumnos[$alumno->cod_alumno])){
                $alumno->cursos = $curs;
                $alumnos[$alumno->cod_alumno] = $alumno;
            }else{
                $alu = $alumnos[$alumno->cod_alumno];
                if($alu->cursos){
                    $c = $alumnos[$alumno->cod_alumno]->cursos;
                    $c[$value->id_curso] = $n;
                    //$c = array_merge($alumnos[$alumno->cod_alumno]->cursos,$curs);
                }
                else
                    $c = $curs;
                $alu->cursos = $c;
                $alumnos[$alumno->cod_alumno] = $alu;
            }
        }
        $s = [];
        foreach ($alumnos as $key => $value) {
            array_push($s, $value);
        }
        $alumnos = $s;
        $this->load->library('impreactas',$this->parameters);
        $data = [
            'periodo'               => $periodo,
            'evaluaciones'          => $evaluaciones,
            'acta'                  => $acta,
            'especialidad'          => $especialidad,
            'turno'                 => $turno,
            'cursos'                => $cursos,
            'alumnos'               => $alumnos,
            'tipo_eval_acta'        => $tipo_evaluacion_acta,
            'ciclo'                 => $ciclo
        ];
        $obj = new stdClass();
        $obj->data = $data;
        $this->impreactas->imprimeActaNoRegular([$obj],$this->parameters);
    }

    public function registrarNotaNoRegular(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $where = [
            'id_tipo_nota_no_regular'   => $this->input->post('id_tipo_nota_no_regular'),
            'id_especialidad'           => $this->input->post('id_especialidad'),
            'id_periodo'                => $this->input->post('id_periodo'),
            'id_ciclo'                  => $this->input->post('id_ciclo'),
            'id_turno'                  => $this->input->post('id_turno'),
            'fecha'                     => date('Y-m-d',strtotime(date($this->input->post('fecha'))))
        ];
        $acta = $this->evaluaciones->getActaNoRegular($where);
        if(is_numeric($acta))
            $id_acta = $this->evaluaciones->newActaNoRegular($where);
        else{
            $id_acta = $acta->id;
        }
        $data_no_regular = [
            'id_alumno_matricula_curso'             => $this->input->post('id_alumno_matricula_curso'),
            'id_tipo_nota_no_regular'               => $this->input->post('id_tipo_nota_no_regular'),
            //'id_tipo_evaluacion'                    => $this->input->post('id_tipo_evaluacion')
            'valor_nota'                            => $this->input->post('valor_nota'),
            'estado'                                => $this->input->post('valor_nota') >= $this->input->post('eval_minima') ? 1 : 0,
            'fecha_acta'                            => date('Y-m-d',strtotime(date($this->input->post('fecha')))),
            'fecha_registro'                        => date('Y-m-d'),
            'id_profesor'                           => $this->input->post('id_profesor'),
            'id_acta'                               => $id_acta
        ];
        $this->evaluaciones->newNotaNoRegular($data_no_regular);
        //actualizar el estado_final de alumno_matricula_curso
        if($this->input->post('eval_minima') <= $this->input->post('valor_nota'))
            $this->alumno->updateAlumnoMatriculaCurso(['id'=>$this->input->post('id_alumno_matricula_curso')],['estado_final' => 1]);
        echo json_encode(['status'=>200,'data'=>['id_acta'=>$id_acta],'message'=>'Registro satisafactorio']);
    }

    public function listarNotas(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $notas = $this->evaluaciones->getConsultaEvaluacionesNoRegulares($this->input->post('id_alumno_matricula_curso'));
        if(is_numeric($notas))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron registros']);
        else
            echo json_encode(['status'=>200,'data'=>$notas,'message'=>'Consulta satisafactorio']);
    }

    public function listarNotasAux(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $notas = $this->evaluaciones->getConsultaEvaluacionesNoRegularesAux($this->input->post('cod_alumno'),$this->input->post('id_curso'));
        if(is_numeric($notas))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron registros']);
        else
            echo json_encode(['status'=>200,'data'=>$notas,'message'=>'Consulta satisafactorio']);
    }

    public function cargaForEdicion(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $tipos = $this->evaluaciones->getTipoNotaNoRegulares();
        $nota_no_reg = $this->evaluaciones->getNotaNoRegular($this->input->post('id_nota_no_regular'));
        $curso = $this->evaluaciones->getEvalMinimaNoRegular($this->input->post('id_nota_no_regular'));
        $profesor = $this->persona->getProfesor($nota_no_reg->id_profesor);
        if(is_numeric($tipos))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron registros']);
        else
            echo json_encode(['status'=>200,'data'=>['tipos'=>$tipos,'nota_no_reg'=>$nota_no_reg,'curso'=>$curso,'profesor'=>$profesor],'message'=>'Consulta satisafactorio']);
    }

    public function cargaForEdicionAux(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $tipos = $this->evaluaciones->getTipoNotaNoRegulares();
        $nota_no_reg = $this->evaluaciones->getNotaNoRegularAux($this->input->post('id_nota_no_regular_aux'));
        $curso = $this->evaluaciones->getEvalMinimaNoRegularAux($this->input->post('id_nota_no_regular_aux'));
        $profesor = $this->persona->getProfesor($nota_no_reg->id_profesor);
        $periodos = $this->alumno->getPeriodosCursosNoEncontrados($curso->id_curso);
        $turnos = $this->alumno->getTurnosPeriodosCursosNoEncontrados($curso->id_curso,$nota_no_reg->id_periodo);
        if(is_numeric($tipos))
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron registros']);
        else
            echo json_encode(['status'=>200,'data'=>['tipos'=>$tipos,'nota_no_reg'=>$nota_no_reg,'curso'=>$curso,'profesor'=>$profesor,'periodos'=>$periodos,'turnos'=>$turnos],'message'=>'Consulta satisafactorio']);
    }

    public function actualizarNotaNoRegular(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $registro_anterior = $this->evaluaciones->getNotaNoRegular($this->input->post('id_nota_no_regular'));
        $cont1 = $this->evaluaciones->consultaCantidadRegistrosForActa($registro_anterior->id_acta);
        $cont2 = $this->evaluaciones->consultaCantidadRegistrosForActaAux($registro_anterior->id_acta);
        if($cont1+$cont2 == 1){
            $this->evaluaciones->deleteActa($registro_anterior->id_acta);
        }
        $curso = $this->evaluaciones->getEvalMinimaNoRegular($this->input->post('id_nota_no_regular'));
        $where = [
            'id_tipo_nota_no_regular'   => $this->input->post('id_tipo_nota_no_regular'),
            'id_especialidad'           => $this->input->post('id_especialidad'),
            'id_periodo'                => $this->input->post('id_periodo'),
            'id_ciclo'                  => $this->input->post('id_ciclo'),
            'id_turno'                  => $this->input->post('id_turno'),
            'fecha'                     => date('Y-m-d',strtotime(date($this->input->post('fecha'))))
        ];
        $acta = $this->evaluaciones->getActaNoRegular($where);
        if(is_numeric($acta))
            $id_acta = $this->evaluaciones->newActaNoRegular($where);
        else{
            $id_acta = $acta->id;
        }
        if($curso->eval_minima <= $this->input->post('valor_nota'))
            $estado_final = 1;
        else
            $estado_final = 0;
        $data_no_regular = [
            'valor_nota'                            => $this->input->post('valor_nota'),
            'id_tipo_nota_no_regular'               => $this->input->post('id_tipo_nota_no_regular'),
            'fecha_acta'                            => date('Y-m-d',strtotime(date($this->input->post('fecha')))),
            'id_profesor'                           => $this->input->post('id_profesor'),
            'id_acta'                               => $id_acta,
            'estado'                                => $estado_final
        ];
        $this->evaluaciones->updateNotaAlumnoCursoRegular($curso->id_alumno_matricula_curso, ['estado_final'=>$estado_final]);
        $this->evaluaciones->updateNotaNoRegular($data_no_regular,$this->input->post('id_nota_no_regular'));
        echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualizacion satisafactoria']);
    }

    public function actualizarNotaNoRegularAux(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $registro_anterior = $this->evaluaciones->getNotaNoRegularAux($this->input->post('id_nota_no_regular_aux'));
        $cont1 = $this->evaluaciones->consultaCantidadRegistrosForActa($registro_anterior->id_acta);
        $cont2 = $this->evaluaciones->consultaCantidadRegistrosForActaAux($registro_anterior->id_acta);
        if($cont1+$cont2 == 1){
            $this->evaluaciones->deleteActa($registro_anterior->id_acta);
        }
        $curso = $this->evaluaciones->getEvalMinimaNoRegularAux($this->input->post('id_nota_no_regular_aux'));
        $where = [
            'id_tipo_nota_no_regular'   => $this->input->post('tipo_nota'),
            'id_especialidad'           => $this->input->post('id_especialidad'),
            'id_periodo'                => $this->input->post('id_periodo'),
            'id_ciclo'                  => $this->input->post('id_ciclo'),
            'id_turno'                  => $this->input->post('id_turno'),
            'fecha'                     => date('Y-m-d',strtotime(date($this->input->post('fecha'))))
        ];
        $acta = $this->evaluaciones->getActaNoRegular($where);
        if(is_numeric($acta))
            $id_acta = $this->evaluaciones->newActaNoRegular($where);
        else{
            $id_acta = $acta->id;
        }
        if($curso->eval_minima <= $this->input->post('valor_nota'))
            $estado_final = 1;
        else
            $estado_final = 0;
        $data_no_regular = [
            'valor_nota'                            => $this->input->post('valor_nota'),
            'id_tipo_nota_no_regular'               => $this->input->post('tipo_nota'),
            'fecha_acta'                            => date('Y-m-d',strtotime(date($this->input->post('fecha')))),
            'id_profesor'                           => $this->input->post('id_profesor'),
            'id_periodo'                            => $this->input->post('periodo'),
            'id_turno'                              => $this->input->post('turno'),
            'id_acta'                               => $id_acta,
            'estado'                                => $estado_final
        ];
        //$this->evaluaciones->updateNotaAlumnoCursoRegular($curso->id_alumno_matricula_curso, ['estado_final'=>$estado_final]);
        $this->evaluaciones->updateNotaNoRegularAux($data_no_regular,$this->input->post('id_nota_no_regular_aux'));
        echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualizacion satisafactoria']);
    }

    public function eliminarRegistroNoRegular(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $registro_anterior = $this->evaluaciones->getNotaNoRegular($this->input->post('id_nota_no_regular'));
        $cont1 = $this->evaluaciones->consultaCantidadRegistrosForActa($registro_anterior->id_acta);
        $cont2 = $this->evaluaciones->consultaCantidadRegistrosForActaAux($registro_anterior->id_acta);
        if($cont1+$cont2 == 1){
            $this->evaluaciones->deleteActa($registro_anterior->id_acta);
        }
        $this->evaluaciones->deleteRegistroNoRegular($this->input->post('id_nota_no_regular'));
        echo json_encode(['status'=>200,'data'=>[],'message'=>'Eliminacion satisafactoria']);
    }

    public function eliminarRegistroNoRegularAux(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $registro_anterior = $this->evaluaciones->getNotaNoRegularAux($this->input->post('id_nota_no_regular_aux'));
        $cont1 = $this->evaluaciones->consultaCantidadRegistrosForActa($registro_anterior->id_acta);
        $cont2 = $this->evaluaciones->consultaCantidadRegistrosForActaAux($registro_anterior->id_acta);
        if($cont1+$cont2 == 1){
            $this->evaluaciones->deleteActa($registro_anterior->id_acta);
        }
        $this->evaluaciones->deleteRegistroNoRegularAux($this->input->post('id_nota_no_regular_aux'));
        echo json_encode(['status'=>200,'data'=>[],'message'=>'Eliminacion satisafactoria']);
    }

}