<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egresados extends CI_Controller {

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
        $this->load->model('Practica','practica');
        $this->load->model('Evaluaciones','evaluaciones');
            $this->load->model('Pagos','pagos');
		/*if(!$this->session->userdata('usuario'))
			header('Location: '.base_url());*/
	}

    public function index(){
        $data = [
            'parameters'        => $this->parameters,
            'usuario'           => $this->session->userdata('usuario'),
            'module'            => 'Egresados',
            'content'           => $this->load->view('egresados/index',[
                'parameters'        => $this->parameters,
                'egresados'         => 0,
                'usuario'           => $this->session->userdata('usuario')
                ],true)
        ];
        $this->load->view('body',$data);
    }

    public function extras(){
        $periodos = $this->esp->getPeriodosEspecialidadPeriodo();
        $data = [
            'parameters'        => $this->parameters,
            'usuario'           => $this->session->userdata('usuario'),
            'module'            => 'Egresados',
            'content'           => $this->load->view('egresados/extras',[
                'parameters'        => $this->parameters,
                'periodos'          => $periodos,
                'usuario'           => $this->session->userdata('usuario')
                ],true)
        ];
        $this->load->view('body',$data);
    }

    public function carga_egresados(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $desde = date('Y-m-d',strtotime(date($this->input->post('desde'))));
        $hasta = date('Y-m-d',strtotime(date($this->input->post('hasta'))));
        if($this->input->post('status')){
            $lista = $this->esp->getEgresados_($this->input->post('periodo'));
            $ls = [];
            if(!is_numeric($lista))
                foreach ($lista as $key => $value) {
                    if($value->lleva_cursos_actividades){
                        if($value->cursos_forzado == 1 || $value->actividades_forzado == 1 || $value->practicas_forzado == 1){
                            array_push($ls, $value);
                        }else{
                            if($value->estado_egreso == 0){
                                array_push($ls, $value);
                            }
                        }
                    }
                    else{
                        if($value->cursos_forzado == 1 || $value->practicas_forzado == 1){
                            array_push($ls, $value);
                        }else{
                            if($value->estado_egreso == 0){
                                array_push($ls, $value);
                            }
                        }
                    }
                }
            $lista = $ls;
        }
        else{
            $lista = $this->esp->getEgresados($desde,$hasta);
        }
        if(!is_numeric($lista)){
            $inform = ['lista'=>$lista,'desde'=>$desde,'hasta'=>$hasta];
            $this->session->set_userdata('lista_egresados_consulta',$inform);
            echo json_encode(['status'=>200,'data'=>$lista,'message'=>'Consulta satisfactoria']);
        }
        else
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron registros']);
    }

    public function listaIngreso(){
        if(!$this->session->userdata('lista_egresados_consulta'))
            header('Location: '.base_url());
        $lista = $this->session->userdata('lista_egresados_consulta');
        $this->load->library('constancias',$this->parameters);
        $this->constancias->imprimir_lista_egresados($lista);
    }

    public function consultaPreparaCambio(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $registro_alumno = $this->esp->getEgresado($this->input->post('id_alumno_especialidad'));
        if(!is_numeric($registro_alumno))
            echo json_encode(['status'=>200,'data'=>$registro_alumno,'message'=>'Consulta satisfactoria']);
        else
            echo json_encode(['status'=>202,'data'=>[],'message'=>'No se encontraron registros']);
    }

    public function actualizarAlumnoEspecialidad(){
        if(!$this->input->post())
            header('Location: '.base_url());
        $data = [];
        if(is_numeric($this->input->post('forzar_cursos')))
            $data['cursos_forzado'] = $this->input->post('forzar_cursos');
        if(is_numeric($this->input->post('actividades')))
            $data['actividades_forzado'] = $this->input->post('actividades');
        if(is_numeric($this->input->post('practicas')))
            $data['practicas_forzado'] = $this->input->post('practicas');
        $estado_egreso = 0;
        if($this->input->post('forzar_cursos') || $this->input->post('actividades') || $this->input->post('practicas')){

            if($this->input->post('forzar_cursos') && $this->input->post('actividades') && $this->input->post('practicas')){

                if($this->input->post('forzar_cursos') == 1 && $this->input->post('actividades') == 1 && $this->input->post('practicas') == 1){
                    $estado_egreso = 1;
                }
            }
            else{
                if(($this->input->post('forzar_cursos') && $this->input->post('actividades')) || ($this->input->post('forzar_cursos') && $this->input->post('practicas')) || ($this->input->post('actividades') && $this->input->post('practicas'))){
                    if($this->input->post('forzar_cursos') == 1 && $this->input->post('actividades') == 1 && is_null($this->input->post('practicas')))
                        $estado_egreso = 1;
                    if($this->input->post('forzar_cursos') == 1 && $this->input->post('practicas') == 1 && is_null($this->input->post('actividades')))
                        $estado_egreso = 1;
                    if($this->input->post('actividades') == 1 && $this->input->post('practicas') == 1 && is_null($this->input->post('forzar_cursos')))
                        $estado_egreso = 1;
                }
                else{
                    if($this->input->post('forzar_cursos') == 1 && is_null($this->input->post('actividades')) && is_null($this->input->post('practicas')) )
                        $estado_egreso = 1;
                    if($this->input->post('actividades') == 1 && is_null($this->input->post('forzar_cursos')) && is_null($this->input->post('practicas')))
                        $estado_egreso = 1;
                    if($this->input->post('practicas') == 1 && is_null($this->input->post('actividades')) && is_null($this->input->post('forzar_cursos')))
                        $estado_egreso = 1;
                }
            }

        }

        $data['estado_egreso'] = $estado_egreso;
        if($estado_egreso)
            $data['fch_egreso'] = date('Y-m-d');
        $alumno = $this->esp->getAlumnoEspecialidadForId($this->input->post('id_alumno_especialidad'));
        if($estado_egreso && $estado_egreso != $alumno->estado_egreso){
            $alu = $this->alumno->getAlumnoEgreso(['codigo'=>$alumno->cod_alumno,'egresado'=>1]);
            if(is_numeric($alu)){
                $this->alumno->updateAlumno($alumno->cod_alumno,['egresado'=>$estado_egreso,'fch_egreso'=>date('Y-m-d')]);
            }
        }
        if(!$estado_egreso && $estado_egreso != $alumno->estado_egreso){
            $alu = $this->alumno->getAlumnoEgreso(['codigo'=>$alumno->cod_alumno,'egresado'=>1]);
            if(is_numeric($alu)){
                $this->alumno->updateAlumno($alumno->cod_alumno,['egresado'=>$estado_egreso,'fch_egreso'=>date('2000-01-01')]);
            }
        }
        $this->alumno->updateAlumnoEspecialidad($this->input->post('id_alumno_especialidad'),$data);
        echo json_encode(['status'=>200,'data'=>[],'message'=>'Actualización satisfactoria']);
    }

    public function procesar(){
    	$data = [
            'parameters'        => $this->parameters,
            'usuario'           => $this->session->userdata('usuario'),
            'module'            => 'Egresados',
            'content'           => $this->load->view('egresados/procesar',[
                'parameters'        => $this->parameters,
                'usuario'           => $this->session->userdata('usuario')
                ],true)
        ];
        $this->load->view('body',$data);
    }

    public function procesar_egresados(){
        if(!$this->input->post())
            header('Location: '.base_url());
        set_time_limit(10000);
        $alumnos = $this->esp->getAlumnosEspecialidadEgreso();
        $alumnos_egresados_anterior = $this->esp->getAlumnosEspecialidadWhere(['estado_egreso'=>1]);
        $alumnos_egresados = 0;
        foreach ($alumnos as $al => $alumno) {
            $egresado_final = 0;
            if($alumno->cursos || $alumno->cursos_forzado) {
                if($alumno->practicas || $alumno->practicas_forzado){
                    if($alumno->lleva_cursos_actividades){
                        if($alumno->actividades || $alumno->actividades_forzado){
                            $egresado_final = 1;
                        }
                    }
                    else{
                        $egresado_final = 1;
                    }
                }
            }
            if($egresado_final)
                $alumnos_egresados += 1;
            if($egresado_final && $egresado_final != $alumno->estado_egreso){
                $this->esp->updateAlumnoEspecialidadCurso($alumno->id,['estado_egreso'=>$egresado_final,'fch_egreso'=>date('Y-m-d')]);
                $alu = $this->alumno->getAlumnoEgreso(['codigo'=>$alumno->cod_alumno,'egresado'=>1]);
                if(is_numeric($alu)){
                    $this->alumno->updateAlumno($alumno->cod_alumno,['egresado'=>$egresado_final,'fch_egreso'=>date('Y-m-d')]);
                }
                //$alumnos_egresados += 1;
            }
            if(!$egresado_final && $egresado_final != $alumno->estado_egreso){
                $this->esp->updateAlumnoEspecialidadCurso($alumno->id,['estado_egreso'=>$egresado_final,'fch_egreso'=>date('2000-01-01')]);
                $alu = $this->alumno->getAlumnoEgreso(['codigo'=>$alumno->cod_alumno,'egresado'=>1]);
                if(is_numeric($alu)){
                    $this->alumno->updateAlumno($alumno->cod_alumno,['egresado'=>$egresado_final,'fch_egreso'=>date('2000-01-01')]);
                }
            }
        }
        echo json_encode(['status'=>200,'data'=>['alumnos_nuevos'=>$alumnos_egresados - $alumnos_egresados_anterior],'message'=>'Actualización satisfactoriamente']);
    }

    public function procesar_practicas(){
        if(!$this->input->post())
            header('Location: '.base_url());
        set_time_limit(10000);
        $alumnos = $this->esp->getAlumnosEspecialidadPracticas();
        $cant_alumnos_cursos_culminado_anterior = $this->esp->cantAlumnosPracticasCulminadas()->cantidad;
        $cant_alumnos_practicas_culminados = 0;
        //echo '<pre>';
        foreach ($alumnos as $al => $alumno) {
            $informacion = $this->alumno->getAlumnoInf($alumno->cod_alumno,$alumno->id);
            $especialidad_periodo = $this->esp->getEspecialidadesPeriodoForId($informacion->id_especialidad_periodo);
            //cantidad de practicas que deberia haber llevado
            $cantidad_practicas = $this->esp->getPracticasMalla($informacion->id_especialidad_periodo);
            $estado = 0;
            foreach ($cantidad_practicas as $key => $value) {
                $consulta_practicas = $this->practica->getPracticasAlumnoLlevadas($alumno->cod_alumno,$value->id_modulo);
                if(!is_numeric($consulta_practicas)){
                    $estado = 1;
                }else{
                    $estado = 0;
                    break;
                }
                # code...
            }
            if($estado)
                $cant_alumnos_practicas_culminados += 1;
            $this->esp->updateAlumnoEspecialidadCurso($alumno->id,['practicas'=>$estado]);
            //print_r($cantidad_practicas);
        }
        echo json_encode(['status'=>200,'data'=>['alumnos_nuevos'=>$cant_alumnos_practicas_culminados-$cant_alumnos_cursos_culminado_anterior],'message'=>'Proceso culminado satisfactoriamente']);
        //echo '</pre>';
        exit();
    }

    public function procesar_cursos_regulares(){
        if(!$this->input->post())
            header('Location: '.base_url());
        set_time_limit(10000);
        $alumnos = $this->esp->getAlumnosEspecialidadCursos();
        $cant_alumnos_cursos_culminado_anterior = $this->esp->cantAlumnosCursosCulminados()->cantidad;
        $cant_alumnos_cursos_culminados = 0;
        foreach ($alumnos as $al => $alumno){
            $informacion = $this->alumno->getAlumnoInf($alumno->cod_alumno,$alumno->id);
            $especialidad_periodo = $this->esp->getEspecialidadesPeriodoForId($informacion->id_especialidad_periodo);
            $malla = $this->esp->getMallaPeriodo($informacion->id_especialidad_periodo);
            $ml = [];
            if(!is_numeric($malla))
            foreach ($malla as $key => $value) {
                if($value->modular == 1)
                    $modular = 1;
                if(isset($ml[$value->id_ciclo])){
                    $m = $ml[$value->id_ciclo];
                }else{
                    $m = [];
                }
                array_push($m, $value);
                $ml[$value->id_ciclo] = $m;
            }
            //$band = 0;
            foreach ($ml as $key => $value) {
                //if(!$band) {
                $alumno_matriculas = $this->alumno->getAlumnoMatriculaCertificado($alumno->cod_alumno,$key,$especialidad_periodo->id_especialidad,$especialidad_periodo->id_periodo);
                $id_alumno_matricula = 0;
                //recorriendo alumnos_matricula
                if(!is_numeric($alumno_matriculas))
                    foreach ($alumno_matriculas as $als => $alms) {
                        $band = false;
                        //recorriendo cursos
                        foreach ($value as $c => $cr){
                            $consulta = $this->alumno->getCursosBusquedaCertificado($alms->id,$cr->id_curso);
                            if(!is_numeric($consulta))
                                $band = true;
                            else
                                $band = false;
                            if(!$band)
                                break;
                        }
                        if($band){
                            $id_alumno_matricula = $alms->id;
                        }
                    }
                    $alumno_matricula = $this->alumno->getCursosMatriculaAlumnoCertificado_($id_alumno_matricula);
                    if(!is_numeric($alumno_matricula)){
                        foreach ($alumno_matricula as $k => $v){
                            $band = 0; 
                            if($index = $this->buscarCurso($v->id_curso,$value) != -1) {
                                //$band = 0;
                                if($v->estado == 1){
                                    $band = 1;
                                    continue;
                                }
                                if($v->estado == 0){
                                    $nota_no_regular = $this->evaluaciones->getAlumnoNotaNoRegularCertificado($v->id_curso,$alumno->cod_alumno,1);
                                    if(!is_numeric($nota_no_regular)) {
                                        if($nota_no_regular->estado == 1){
                                            $band = 1;
                                        }
                                    }
                                }
                            }
                        }
                    }else{
                        $band=0;
                        foreach ($value as $ky => $val) {
                            $nota_no_regular_aux = $this->evaluaciones->getNotaNoRegularForAlumnoAuxCertificado($alumno->cod_alumno,$val->id_curso,$especialidad_periodo->id_especialidad,$key);
                            if(!is_numeric($nota_no_regular_aux)){
                                if($nota_no_regular_aux->estado == 1)
                                    $band = 1;
                            }
                        }
                    }
                    if(!$band){
                        break;
                    }

                //}
            }
            $this->esp->updateAlumnoEspecialidadCurso($alumno->id,['cursos'=>$band]);
            if($band)
                $cant_alumnos_cursos_culminados += 1;
            /*echo '<pre>';
            print_r($ml);
            echo '</pre>';
            exit();*/
        }
        echo json_encode(['status'=>200,'data'=>['alumnos_nuevos'=>$cant_alumnos_cursos_culminados - $cant_alumnos_cursos_culminado_anterior],'message'=>'Proceso culminado satisfactoriamente']);
    }

    public function procesar_cursos_actividades(){
        if(!$this->input->post())
            header('Location: '.base_url());
        set_time_limit(10000);
        $alumnos = $this->esp->getAlumnosEspecialidadActividades();
        $cant_alumnos_cursos_culminado_anterior = $this->esp->cantAlumnosCursosCulminadosActividades()->cantidad;
        $cant_alumnos_cursos_culminados = 0;
        foreach ($alumnos as $al => $alumno) {
            $informacion = $this->alumno->getAlumnoInf($alumno->cod_alumno,$alumno->id);
            $especialidad_periodo = $this->esp->getEspecialidadesPeriodoForId($informacion->id_especialidad_periodo);
            $malla = [];
            $malla = $this->esp->getMallaPeriodoActividades($informacion->id_especialidad_periodo);
            $ml = [];
            if(!is_numeric($malla))
            foreach ($malla as $key => $value) {
                if($value->modular == 1)
                    $modular = 1;
                if(isset($ml[$value->id_ciclo])){
                    $m = $ml[$value->id_ciclo];
                }else{
                    $m = [];
                }
                array_push($m, $value);
                $ml[$value->id_ciclo] = $m;
            }
            if(count($ml) != 0){
                foreach ($ml as $key => $value) {
                    //if(!$band) {
                    $alumno_matriculas = $this->alumno->getAlumnoMatriculaCertificado($alumno->cod_alumno,$key,$especialidad_periodo->id_especialidad,$especialidad_periodo->id_periodo);
                    $id_alumno_matricula = 0;
                    //recorriendo alumnos_matricula
                    if(!is_numeric($alumno_matriculas))
                        foreach ($alumno_matriculas as $als => $alms) {
                            $band = false;
                            //recorriendo cursos
                            foreach ($value as $c => $cr){
                                $consulta = $this->alumno->getCursosBusquedaCertificado($alms->id,$cr->id_curso);
                                if(!is_numeric($consulta))
                                    $band = true;
                                else
                                    $band = false;
                                if(!$band)
                                    break;
                            }
                            if($band){
                                $id_alumno_matricula = $alms->id;
                            }
                        }
                        $alumno_matricula = $this->alumno->getCursosMatriculaAlumnoCertificado_Actividades($id_alumno_matricula);
                        if(!is_numeric($alumno_matricula)){
                            foreach ($alumno_matricula as $k => $v){
                                $band = 0;
                                if($index = $this->buscarCurso($v->id_curso,$value) != -1) {
                                    //$band = 0;
                                    if($v->estado == 1){
                                        $band = 1;
                                        continue;
                                    }
                                    if($v->estado == 0){
                                        $nota_no_regular = $this->evaluaciones->getAlumnoNotaNoRegularCertificado($v->id_curso,$alumno->cod_alumno,1);
                                        if(!is_numeric($nota_no_regular)) {
                                            if($nota_no_regular->estado == 1){
                                                $band = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }else{
                            $band=0;
                            foreach ($value as $ky => $val) {
                                $nota_no_regular_aux = $this->evaluaciones->getNotaNoRegularForAlumnoAuxCertificado($alumno->cod_alumno,$val->id_curso,$especialidad_periodo->id_especialidad,$key);
                                if(!is_numeric($nota_no_regular_aux)){
                                    if($nota_no_regular_aux->estado == 1)
                                        $band = 1;
                                }
                            }
                        }
                        if(!$band){
                            break;
                        }

                    //}
                }
            }else
                $band = 0;
            $this->esp->updateAlumnoEspecialidadCurso($alumno->id,['actividades'=>$band]);
            if($band)
                $cant_alumnos_cursos_culminados += 1;
            /*echo '<pre>';
            print_r($ml);
            echo '</pre>';
            exit();*/
        }
        echo json_encode(['status'=>200,'data'=>['alumnos_nuevos'=>$cant_alumnos_cursos_culminados - $cant_alumnos_cursos_culminado_anterior],'message'=>'Proceso culminado satisfactoriamente']);
    }

    public function buscarCurso($id_curso,$cursos){
        foreach ($cursos as $key => $value) {
            if($value->id_curso == $id_curso)
                return $key;
        }
        return -1;
    }

}