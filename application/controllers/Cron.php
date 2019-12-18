<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

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
            $this->load->model('Pagos','pagos');
		if(!$this->session->userdata('usuario'))
			header('Location: '.base_url());
	}
        
    public function index(){
        $this->load->library('excel');
        $this->excel->carga(BASEPATH.'../files/paracargarnotas.xlsx');
        $i = 56;
        while($this->excel->leer('B'.$i) != ''){
            $persona = [
                'nombre'            => strtoupper($this->excel->leer('D'.$i)),
                'apell_pat'         => strtoupper($this->excel->leer('B'.$i)),
                'apell_mat'         => strtoupper($this->excel->leer('C'.$i)),
                'dni'               => $this->excel->leer('A'.$i) != '' ? $this->excel->leer('A'.$i) : 12345678+$i,
                'telefono'          => '1234156',
                'celular_1'         => '963852741',
                'celular_2'         => '963852741',
                'direccion'         => 'direccion',
                'email'             => 'email@email.com',
                'fch_nac'           => date('Y-m-d'),
                'fch_registro'      => date('Y-m-d'),
                'fch_salida'        => date('Y-m-d'),
                'id_genero'         => 1,
                'id_estado_civil'   => 1,
                'estado'            => 1
            ];
            echo $persona['nombre'].' -- ';
            $id_persona = $this->persona->newPersona($persona);
            //$id_persona = 1;
            //especialidad alumno
            $especialidad = $this->esp->searchEspecialidad($this->excel->leer('F'.$i));
            if(is_numeric($especialidad))
                $id_especialidad = 1;
            else
                $id_especialidad = $especialidad[0]->id;
            $data = [
                'id_especialidad'       => $id_especialidad,
                'id_periodo'            => 14,
                'id_persona'            => $id_persona,
                'fch_ingreso'           => date('Y-m-d'),
                'turno'                 => $this->excel->leer('I'.$i) == 'VESPERTINO' ? 1 : 2,
                'pass'                  => sha1(md5('123456')),
                'alumnno_tipo'          => 2,
                'dni'                   => $this->excel->leer('A'.$i) != '' ? $this->excel->leer('A'.$i) : 12345678+$i,
            ];
            $al = $this->alumno->newAlumno($data);
            echo 'Cod. alumno: '.$al->codigo_alumno.' -- '.$this->excel->leer('F'.$i);
            $relacio = $this->esp->searchEspecialidadPeriodo([
                'id_especialidad'=>$id_especialidad,
                'id_periodo'=> 14
            ]);
            if(is_numeric($relacio))
                $id_especialidad_periodo = 48;
            else
                $id_especialidad_periodo = $relacio->id;
            switch ($this->excel->leer('J'.$i)) {
                case 'I':
                    $id_ciclo = 1;
                    break;

                case 'II':
                    $id_ciclo = 2;
                    break;

                case 'III':
                    $id_ciclo = 3;
                    break;

                case 'IV':
                    $id_ciclo = 4;
                    break;

                case 'V':
                    $id_ciclo = 5;
                    break;
                
                default:
                    # code...
                    break;
            }
            $dat = [
                'cod_alumno'                    => $al->codigo_alumno,
                'id_especialidad_periodo'       => $id_especialidad_periodo,
                'estado_egreso'                 => 0,
                'estado_titulado'               => 0,
                'fch_ingreso'                   => date('Y-m-d'),
                'fch_egreso'                    => date('Y-m-d'),
                'fch_titulado'                  => date('Y-m-d'),
                'id_periodo_ingreso'            => 14,
                'estado'                        => 1,
                'id_ciclo'                      => $id_ciclo,
                'id_grupo'                      => 1,
                'id_turno'                      => $this->excel->leer('I'.$i) == 'VESPERTINO' ? 1 : 2,
            ];
            $this->alumno->newEspecialidadAlumno($dat);
            $nota_prac = [
                'nombre_practica'               => '',
                'id_modulo'                     => 1,
                'cod_alumno'                    => $al->cod_alumno,
                'id_especialidad'               => $id_especialidad,
                'fecha'                         => date('Y-m-d', strtotime(date($this->excel->leer('Q'.$i)))),
                'id_tipo_evaluacion'            => 1,
                'valor_nota'                    => $value['eval']
            ];
            $this->practica->newPractica($data);
            echo PHP_EOL;
            $i++;
        }
        //echo $this->excel->leer('A2');
    }

    public function cambiarturnoalumno(){
        $practicas = $this->practica->getAllPracticas();
        foreach ($practicas as $key => $value) {
            $mes = rand(4, 8);
            $dia = rand(1, 28);
            echo 'Alumno: '.$value->cod_alumno.' - Nota: '.$value->valor_nota.' -> Fecha Registro: '.date('Y').'-'.$mes.'-'.$dia.PHP_EOL;
            $this->practica->updatePractica(['fecha_reg'=>date('Y').'-'.$mes.'-'.$dia],['id'=>$value->id]);
        }
    }
}