<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cursos extends CI_Controller {

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

	public function getCursosForPeriodo(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$cursos = $this->periodo->getCursosMalla($this->input->post('id_especialidad_periodo'),1,$this->input->post('turno'));
                /*$grupo = $this->periodo->getGrupoAnyWhere([
                    'id_especialidad_periodo'               => $this->input->post('id_especialidad_periodo'),
                    'id_ciclo'                              => 1
                ]);*/
		if(is_numeric($cursos))
			echo json_encode(['status'=>202,'data'=>[],'message'=>'No hay registro de cursos para este periodo']);
		else
			echo json_encode(['status'=>200,'data'=>['cursos'=>$cursos,'grupo'=>[]],'message'=>'Consulta satisfactoria']);
	}

	public function gestioncursos(){
		if(!$this->buscarPermiso(3))
			header('Location: '.base_url());
		var_dump($this->session->userdata('usuario'));
		exit(); 
	}

	public function gestionseccion($id_periodo = 0){
		if(!$this->buscarPermiso(3))
			header('Location: '.base_url());
		$periodos = $this->esp->getPeriodos();
		$u = $this->session->userdata('usuario');
		$profesor = $this->persona->getProfesorForUsuario($u['id_usuario']);
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Gestion',
			'content'			=> $this->load->view('gestion/profesores/seccion',[
				'parameters'		=> $this->parameters,
				'periodos'			=> $periodos,
				'profesor'			=> $profesor,
                                'id_periodo'                    => $id_periodo,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}
        
        public function getItemCapacidad(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $item = $this->seccion->getItemCapacidadConsult($this->input->post('id_item'));
            if(is_numeric($item))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'Sin resultados']);
            else
                echo json_encode (['status'=>200,'data'=>$item,'message'=>'Resultados']);
        }
        
        public function editarItemCapacidad(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $this->seccion->updateItemCapacidad(['contenido'=> $this->input->post('contenido')],$this->input->post('id_item'));
            echo json_encode (['status'=>200,'data'=>[],'message'=>'Resultados']);
        }
        
        public function getCapacidad(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $capacidad = $this->seccion->getCapacidadConsult($this->input->post('id_capacidad'));
            if(is_numeric($capacidad))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'Sin resultados']);
            else
                echo json_encode (['status'=>200,'data'=>$capacidad,'message'=>'Resultados']);
        }
        
        public function editarCapacidad(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $this->seccion->updateCapacidadConsult(['nombre'=> $this->input->post('name')],$this->input->post('id_capacidad'));
            echo json_encode (['status'=>200,'data'=>[],'message'=>'Resultados']);
        }
        
        public function getCapacidadesAndItems(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $capacidades = $this->seccion->getCapacidadesSeccionCurso($this->input->post('id_seccion_curso'));
            $res = [];
            if(!is_numeric($capacidades))
                foreach ($capacidades as $key => $value) {
                    $items = $this->seccion->getItemsCapacidadesSeccionCurso($value->id);
                    $a['capacidad'] = $value;
                    $a['items'] = $items;
                    array_push($res, $a);
                }
            if(is_numeric($capacidades))
                echo json_encode (['status'=>202,'data'=>[],'message'=>'Sin resultados']);
            else
                echo json_encode (['status'=>200,'data'=>$res,'message'=>'Resultados']);
        }
        
        public function importarCapacidades(){
            if(!$this->input->post())
                header('Location: '.base_url ());
            $capacidades = $this->seccion->getCapacidadesSeccionCurso($this->input->post('id_seccion_curso'));
            $res = [];
            if(!is_numeric($capacidades)){
                foreach ($capacidades as $key => $value) {
                    $data = [
                        'nombre'                => $value->nombre,
                        'id_seccion_curso'      => $this->input->post('id_seccion_nuevo'),
                        'estado'                => 1
                    ];
                    $id_new = $this->seccion->newCapacidadSeccion($data);
                    $items = $this->seccion->getItemsCapacidadesSeccionCurso($value->id);
                    if(!is_numeric($items)){
                        foreach($items as $k => $v){
                            $data_item = [
                                'contenido'         => $v->contenido,
                                'id_capacidad'      => $id_new,
                                'estado'            => 1
                            ];
                            $this->seccion->newItemCapacidadSeccion($data_item);
                        }
                    }
                }
            }
            echo json_encode(['status'=>200,'data'=>$this->input->post(null,true),'message'=>'Resultado encontrado']);
        }
        
        public function newCapacidad(){
            if(!$this->input->post())
                header('Location: '.base_url());
            if($this->input->post('capacidad') == ''){
                echo json_encode(['status'=>202,'data'=>[],'message'=>'Ingrese un dato valido']);
                exit();
            }
            $data = [
                'nombre'            => $this->input->post('capacidad'),
                'id_seccion_curso'  => $this->input->post('id_seccion_curso'),
                'estado'            => 1
            ];
            $id = $this->seccion->newCapacidadSeccion($data);
            //$id = 1;
            echo json_encode(['status'=>200,'data'=>['id'=>$id,'nombre'=>$this->input->post('capacidad')],'message'=>'Registro Satisfactorio']);
        }
        
        public function newItemCapacidad(){
            if(!$this->input->post())
                header('Location: '.base_url());
            if($this->input->post('contenido') == ''){
                echo json_encode(['status'=>202,'data'=>[],'message'=>'Ingrese un dato valido']);
                exit();
            }
            $data = [
                'contenido'         => $this->input->post('contenido'),
                'id_capacidad'      => $this->input->post('id_capacidad'),
                'estado'            => 1
            ];
            $id = $this->seccion->newItemCapacidadSeccion($data);
            echo json_encode(['status'=>200,'data'=>['id'=>$id,'nombre'=>$this->input->post('contenido')],'message'=>'Registro Satisfactorio']);
        }
        
        public function updateSeccion(){
            if(!$this->input->post())
                header('Location: '.base_url());
            $this->seccion->updateTSeccionCurso(['id'=>$this->input->post('id_seccion_curso')],['estado'=>$this->input->post('estado')]);
            echo json_encode(['status'=>200,'data'=>[],'message'=>'Registro Satisfactorio']);
            exit();
            var_dump($this->input->post(null,true));
            exit();
        }
        
        public function seccionnotas($codigo_seccion,$id_seccion_curso,$id_curso){
            if(!$this->buscarPermiso(3))
                    header('Location: '.base_url());
            if($codigo_seccion == '')
                    header('Location: '.base_url());
            if($id_seccion_curso == '')
                    header('Location: '.base_url());
            if($id_curso == '')
                    header('Location: '.base_url());
            $seccion_curso = $this->seccion->getSeccion($id_seccion_curso);
            /*echo '<pre>';
            print_r($seccion_curso);
            echo '</pre>';
            exit();*/
            $seccion  = $this->seccion->getSeccionForMalla($seccion_curso->id_malla_periodo);
            $alumnos = $this->seccion->getAlumnosForSeccion($seccion_curso->id_malla_periodo);
            $capacidades = $this->seccion->getCapacidadesSeccionCurso($id_seccion_curso);
//            $periodo = $this->esp->getPeriodo($seccion_curso->id_periodo);
            $res = [];
            if(!is_numeric($capacidades))
                foreach ($capacidades as $key => $value) {
                    $items = $this->seccion->getItemsCapacidadesSeccionCurso($value->id);
                    $a['capacidad'] = $value;
                    $a['items'] = $items;
                    array_push($res, $a);
                }
            if(!is_numeric($alumnos))
                foreach ($alumnos as $key => $value) {
                    $notas = $this->seccion->getNotaPractica($value->id_alumno_matricula,$seccion_curso->id_curso);
                    $nts = [];
                    if(!is_numeric($notas))
                        foreach ($notas as $n){
                            $nts[$n->id_item] = $n->valor_nota;
                        }
                    $alumnos[$key]->notas = $nts;
                }
            $data = [
                    'parameters'		=> $this->parameters,
                    'usuario'			=> $this->session->userdata('usuario'),
                    'module'			=> 'Gestion',
                    'content'			=> $this->load->view('gestion/profesores/alumnos_notas',[
                            'parameters'		=> $this->parameters,
                            'seccion_curso'                 => $seccion_curso,
                            'seccion'			=> $seccion,
                            'alumnos'			=> $alumnos,
                            'id_curso'			=> $id_curso,
                            'capacidades'               => $res,
//                            'periodo'                   => $periodo,
                            'usuario'			=> $this->session->userdata('usuario')
                            ],true)
            ];
            $this->load->view('body',$data);
        }
        
        public function impresionseccionnotas($id_seccion_curso){
            if($id_seccion_curso == '')
                header('Location: '.base_url ());
            $this->load->library('impresion',$this->parameters);
            $seccion_curso = $this->seccion->getSeccion($id_seccion_curso);
            $seccion = $this->seccion->getJustSeccion($seccion_curso->id_seccion);
            $capacidades = $this->seccion->getCapacidadesSeccionCurso($id_seccion_curso);
            $alumnos = $this->alumno->getAlumnosCurso($seccion_curso->id_malla_periodo,$seccion_curso->id_curso);
            $res = [];
            if(!is_numeric($capacidades))
                foreach ($capacidades as $key => $value) {
                    $items = $this->seccion->getItemsCapacidadesSeccionCurso($value->id);
                    $a['capacidad'] = $value;
                    $a['items'] = $items;
                    array_push($res, $a);
                }
            foreach ($alumnos as $key => $value) {
                $notas = $this->seccion->getNotaPractica($value->id_alumno_matricula,$seccion_curso->id_curso);
                $resultado = $this->seccion->getNota($value->id_alumno_matricula,$seccion_curso->id_curso);
                $alumnos[$key]->valor_nota = is_numeric($resultado) ? '-' : $resultado->valor_nota;
                $nts = [];
                foreach ($notas as $n){
                    $nts[$n->id_item] = $n->valor_nota;
                }
                $alumnos[$key]->notas = $nts;
            }
            $data = [
                'id_seccion_curso'		=> $id_seccion_curso,
                'seccion'                       => $seccion,
                'capacidades'                   => $res,
                'alumnos'                       => $alumnos
            ];
            $this->impresion->imprimir_borrador($data,$this->parameters);
        }
        
        public function ingresaNotaPractica(){
            if(!$this->input->post())
		header('Location: '.base_url());
            $data = [
                'id_tipo_nota'                  => 1,
                'id_tipo_evaluacion'            => 1,
                'id_alumno_matricula'           => $this->input->post('id_alumno_matricula'),
                'id_curso'                      => $this->input->post('id_curso'),
                'cod_alumno'                    => $this->input->post('cod_alumno'),
                'id_capacidad'                  => $this->input->post('id_capacidad'),
                'id_item'                       => $this->input->post('id_item'),
                'valor_nota'                    => $this->input->post('valor_nota')
            ];
            $id = $this->seccion->newNotaPractica($data);
            echo json_encode(['status'=>200,'data'=>['id'=>$id],'message'=>'Registro Satisfactorio']);
        }

    public function imprimirlista($id_seccion_curso = 0,$id_curso = 0){
        if($id_seccion_curso == 0)
            header('Location: '.base_url());
        if($id_curso == 0)
            header('Location: '.base_url());
        $seccion_curso = $this->seccion->getSeccion($id_seccion_curso);
        $alumnos = $this->seccion->getAlumnosForSeccion($seccion_curso->id_malla_periodo);
        $ciclo = $this->periodo->getCicloForMalla($seccion_curso->id_malla_periodo);
        $profesor = $this->esp->getProfesor($seccion_curso->id_profesor);
        $curso = $this->esp->getCursoForId($seccion_curso->id_curso);
        $periodo = $this->periodo->getPeriodoForId($seccion_curso->id_periodo);
        $turno = $this->esp->getTurno($seccion_curso->id_turno);
        /*echo '<pre>';
        print_r($alumnos);
        echo '</pre>';
        exit();*/
        $this->load->library('impresion',$this->parameters);
        $data = [
            'seccion_curso'                 => $seccion_curso,
            'alumnos'                       => $alumnos,
            'profesor'                      => $profesor,
            'curso'                         => $curso,
            'periodo'                       => $periodo,
            'turno'                         => $turno,
            'ciclo'                         => $ciclo
        ];
        $this->impresion->lista_alumnos($data);
    }
        
	public function seccion($codigo_seccion,$id_seccion_curso,$id_curso){
		if(!$this->buscarPermiso(3))
			header('Location: '.base_url());
		if($codigo_seccion == '')
			header('Location: '.base_url());
		if($id_seccion_curso == '')
			header('Location: '.base_url());
		if($id_curso == '')
			header('Location: '.base_url());
		$seccion_curso = $this->seccion->getSeccion($id_seccion_curso);
		$alumnos = $this->seccion->getAlumnosForSeccion($seccion_curso->id_malla_periodo);
        //echo '<pre>';
		foreach ($alumnos as $key => $value) {
			$resultado = $this->seccion->getNota($value->id_alumno_matricula,$seccion_curso->id_curso);
            //print_r($resultado);
            /*echo '<pre>';
            echo '<br>';
            print_r($value->id_alumno_matricula);
            echo '<br>';
            print_r($seccion_curso->id_curso);
            echo '<br>';
            print_r($seccion_curso);*/
			$alumnos[$key]->valor_nota = is_numeric($resultado) ? '-' : $resultado->valor_nota;
            $alumnos[$key]->id_nota = is_numeric($resultado) ? 0 : $resultado->id;
                        if(!is_numeric($resultado)){
                            $recuperacion = $this->seccion->getNotaRecuperacion($value->id_alumno_matricula,$seccion_curso->id_curso,$resultado->id);
                            $alumnos[$key]->valor_nota_recuperacion = is_numeric($recuperacion) ? '-' : $recuperacion->valor_nota;
                            $alumnos[$key]->id_nota_recuperacion = is_numeric($recuperacion) ? 0 : $recuperacion->id;
                        }
		}
		$seccion  = $this->seccion->getSeccionForMalla($seccion_curso->id_malla_periodo);
                $eval = $this->seccion->getTipoEvaluacion($seccion->id_curso);
                $tipo_eval = $this->seccion->getTipoEvaluacion($seccion->id_curso);
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Gestion',
			'content'			=> $this->load->view('gestion/profesores/alumnos',[
				'parameters'		=> $this->parameters,
                                'seccion_curso'                 => $seccion_curso,
				'seccion'			=> $seccion,
                                'tipo_eval'                     => $tipo_eval,
				'alumnos'			=> $alumnos,
				'id_curso'			=> $id_curso,
                                'eval'                          => $eval,
                    'id_seccion_curso'      => $id_seccion_curso,
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}
        
        public function imprimiractacurso($id_seccion_curso){
            if($id_seccion_curso == '')
                header('Location: '.base_url ());
            $this->load->library('pdf',$this->parameters);
            $seccion_curso = $this->seccion->getSeccion($id_seccion_curso);
            $seccion = $this->seccion->getJustSeccion($seccion_curso->id_seccion);
            $curso = $this->esp->getCursoForId($seccion_curso->id_curso);
            $periodo = $this->periodo->getPeriodoForId($seccion_curso->id_periodo);
            $especialidad = $this->esp->getEspecialidad($seccion->id_especialidad);
            $malla = $this->periodo->getMalla($curso->id,$especialidad->id);
            $turno = $this->esp->getTurno($seccion_curso->id_turno);
            $profesor = $this->esp->getProfesor($seccion_curso->id_profesor);
            $alumnos = $this->alumno->getAlumnosCurso($seccion_curso->id_malla_periodo,$seccion_curso->id_curso);
            $capacidades = $this->seccion->getCapacidadesSeccionCurso($id_seccion_curso);
            $res = [];
            if(!is_numeric($capacidades))
                foreach ($capacidades as $key => $value) {
                    $items = $this->seccion->getItemsCapacidadesSeccionCurso($value->id);
                    $a['capacidad'] = $value;
                    $a['items'] = $items;
                    array_push($res, $a);
                }
//            echo '<pre>';
//            print_r($periodo);
//            echo '</pre>';
//            exit();
            foreach ($alumnos as $key => $value) {
                $notas = $this->seccion->getNotaPractica($value->id_alumno_matricula,$seccion_curso->id_curso);
                $resultado = $this->seccion->getNota($value->id_alumno_matricula,$seccion_curso->id_curso);
                $alumnos[$key]->valor_nota = is_numeric($resultado) ? '-' : $resultado->valor_nota;
                $nota_min = $this->seccion->getNotaMinima($seccion_curso->id_curso);
                $alumnos[$key]->eval_minima = $nota_min->eval_minima;
                $nts = [];
                if(!is_numeric($notas))
                    foreach ($notas as $n){
                        $nts[$n->id_item] = $n->valor_nota;
                    }
                else
                    $nts = 0;
                $alumnos[$key]->notas = $nts;
                $alumnos[$key]->id_recuperacion = 0;
                if(!is_numeric($resultado)){
                    $recu = $this->seccion->getRecuperacion($resultado->id);
                    $alumnos[$key]->id_recuperacion = is_numeric($recu) ? 0 : $recu->id;
                    $alumnos[$key]->valor_nota_recuperacion = is_numeric($recu) ? '-' : $recu->valor_nota;
                }
            }
            $data = [
                'id_seccion_curso'		=> $id_seccion_curso,
                'seccion'                   => $seccion,
                'curso'                     => $curso,
                'especialidad'              => $especialidad,
                'malla'                     => $malla,
                'turno'                     => $turno,
                'profesor'                  => $profesor,
                'alumnos'                   => $alumnos,
                'periodo'                   => $periodo,
                'capacidades'               => $res
            ];
            $this->pdf->imprimir_acta_notas_profesor($data,$this->parameters);
        }

	public function getNotasAlumnoCurso(){
		echo json_encode(['status'=>200,'data'=>[],'message'=>'']);
	}
        
        public function registarNotaRecuperacion(){
            if(!$this->input->post())
                header('Location: '.base_url());
                $data_nota = [
                    'id_tipo_nota'              => $this->input->post('tipo_nota'),
                    'id_tipo_evaluacion'        => $this->input->post('tipo_eval'),
                    'id_alumno_matricula'       => $this->input->post('id_alumno_matricula'),
                    'valor_nota'                => $this->input->post('valor_nota'),
                    'id_curso'                  => $this->input->post('id_curso'),
                    'cod_alumno'                => $this->input->post('codigo_alumno'),
                    'id_recuperacion'           => $this->input->post('id_nota')
                ];
            if($this->input->post('id_nota_recuperacion') == 0){
                $id = $this->seccion->newNota($data_nota);
            }else{
                $this->seccion->updateNota(['id'=> $this->input->post('id_nota_recuperacion')],$data_nota);
                $id = $this->input->post('id_nota_recuperacion');
            }
            echo json_encode(['status'=>200,'data'=>['id'=>$id],'message'=>'Registro satisfactorio']);
        }

	public function registraNotaAlumno(){
		if(!$this->input->post())
			header('Location: '.base_url());
		$data_nota = [
			'id_tipo_nota'				=> $this->input->post('tipo_nota'),
			'id_tipo_evaluacion'		=> $this->input->post('tipo_eval'),
			'id_alumno_matricula'		=> $this->input->post('id_alumno_matricula'),
			'valor_nota'				=> $this->input->post('nota'),
			'id_curso'					=> $this->input->post('id_curso'),
			'cod_alumno'				=> $this->input->post('codigo_alumno')
		];
                $verificacion = $this->seccion->getNotaVerificacion([
                    'id_alumno_matricula'               => $this->input->post('id_alumno_matricula'),
                    'id_curso'                          => $this->input->post('id_curso'),
                    'cod_alumno'                        => $this->input->post('codigo_alumno')
                ]);
                if(is_numeric($verificacion)){
                    $id = $this->seccion->newNota($data_nota);
                }else{
                    $this->seccion->updateNota([
                        'id'        => $verificacion->id
                    ],[
                        'valor_nota'                        => $this->input->post('nota')
                        ]);
                    $id = $verificacion->id;
                }
//                    $resp = $this->alumno->actualizaEstadoSemestre(['id_nota'=>$id]);
		echo json_encode(['status'=>200,'data'=>['id'=>$id],'message'=>'Registro satisfactorio']);
	}

    public function evaluaciones(){
        if(!$this->buscarPermiso(2))
            header('Location: '.base_url());
        $u = $this->session->userdata('usuario');
        $al = $this->alumno->getAlumnoForWhere(['id_usuario'=>$u['id_usuario']]);
        if(!is_numeric($al))
            $alumno_especialidad = $this->alumno->getAlumnoEspecialidad($al->codigo);
        else
            $alumno_especialidad = 0;

        $data = [
            'parameters'        => $this->parameters,
            'usuario'           => $this->session->userdata('usuario'),
            'module'            => 'Consultar',
            'content'           => $this->load->view('alumnos/especialidades',[
                'parameters'        => $this->parameters,
                'especialidades'    => $alumno_especialidad,
                ///'especialidades' => $especialidades,
                //'tipoalumno'      => 'regular',
                'usuario'           => $this->session->userdata('usuario')
                ],true)
        ];
        $this->load->view('body',$data);
    }

	public function historial($id){
		if(!$this->buscarPermiso(2))
			header('Location: '.base_url());
		$u = $this->session->userdata('usuario');
        $especialidad_periodo = $this->esp->searchEspecialidadPeriodo(['id'=>$id]);
        $al = $this->alumno->getAlumnoForWhere(['id_usuario'=>$u['id_usuario']]);
		$matriculas = $this->alumno->getMatriculasHistorial($al->codigo,$especialidad_periodo->id_especialidad,$especialidad_periodo->id_periodo);
        if(is_numeric($matriculas))
            $matriculas = [];
		foreach ($matriculas as $key => $value) {
			$cursos = $this->alumno->getNotasCursos($value->id);
			$matriculas[$key]->cursos = $cursos;
            $no_regulares = $this->alumno->getCursosNoRegulares($value->id);
            $matriculas[$key]->no_regulares = $no_regulares;
		}
        $auxiliares = $this->alumno->getCursosAuxiliares($u['usuario']);
		$data = [
			'parameters'		=> $this->parameters,
			'usuario'			=> $this->session->userdata('usuario'),
			'module'			=> 'Consultar',
			'content'			=> $this->load->view('alumnos/historial',[
				'parameters'		=> $this->parameters,
				'periodos'			=> $matriculas,
                'auxiliares'        => $auxiliares,
				///'especialidades'	=> $especialidades,
				//'tipoalumno'		=> 'regular',
				'usuario'			=> $this->session->userdata('usuario')
				],true)
		];
		$this->load->view('body',$data);
	}

	public function buscarPermiso($id_rol){
		$usuario = $this->session->userdata('usuario');
		$permiso = false;
		foreach ($usuario['roles'] as $key => $value) 
			if($id_rol == $value->id_rol)
				$permiso = true;
		return $permiso;
	}

}