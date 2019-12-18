<?php
class Periodo extends CI_Model {

	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function getCursosMalla($id_especialidad_periodo,$id_ciclo = 1,$id_turno){
        $q = $this->db->select('tce.id_modulo, tmp.id as id_malla_periodo, tc.id as id_curso, tcl.id as id_ciclo, tcl.nombre as nombre_ciclo, tc.nombre as curso, tc.codigo as codigo_curso, tc.creditos, tp.nombre as promocion, tm.id_turno, tep.id_especialidad')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('tcurso tc','tc.id = tm.id_curso')
                    ->join('tciclo tcl','tcl.id = tmp.id_ciclo')
                    ->join('tespecialidad_periodo tep','tep.id = tmp.id_especialidad_periodo')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->join('tcurso_especialidad tce','tc.id = tce.id_curso and tce.id_especialidad = tep.id_especialidad')
                    ->where('tmp.estado',1)
                    ->where('tm.id_turno',$id_turno)
                    ->where('tmp.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tmp.id_ciclo',$id_ciclo)
                    ->get('tmalla_periodo tmp');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosMallaAnteriores($id_ciclo,$id_especialidad_periodo){
        $q = $this->db->select('tcl.id as id_ciclo, tcl.nombre as nombre_ciclo, tc.nombre as curso, tc.codigo as codigo_curso, tp.nombre as promocion')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('tcurso tc','tc.id = tm.id_curso')
                    ->join('tciclo tcl','tcl.id = tmp.id_ciclo')
                    ->join('tespecialidad_periodo tep','tep.id = tmp.id_especialidad_periodo')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->where('tmp.estado',1)
                    //->where('tm.id_especialidad',$id_especialidad)
                    ->where('tmp.id_ciclo',$id_ciclo)
                    ->where('tmp.id_especialidad_periodo',$id_especialidad_periodo)
                    ->get('tmalla_periodo tmp');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getMallaCursoEspecialidad($id_curso,$id_especialidad,$id_especialidad_periodo){
        $q = $this->db->select()
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->where('tmp.estado',1)
                    ->where('tm.id_curso',$id_curso)
                    ->where('tm.id_especialidad',$id_especialidad)
                    ->where('tmp.id_especialidad_periodo',$id_especialidad_periodo)
                    ->get('tmalla_periodo tmp');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function updatePeriodo($where,$data){
        $this->db->where($where)->update('tperiodo',$data);
    }

    public function getPeriodoAnterior($fecha){
        $q = $this->db->select()
                    ->where("fch_inicio < '".$fecha."'")
                    ->order_by('fch_inicio','desc')
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getPeriodosNuevos($fecha){
        $q = $this->db->select()
                    //->where("fch_inicio >= '".$fecha."'")
                    ->where_in("estado",array(0,1))
                    ->order_by('fch_inicio','asc')
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getPeriodoFechaPromocional($fecha){
        $q = $this->db->select()
                    ->like("nombre",$fecha,'both')
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getPeriodoActual($fecha){
        $q = $this->db->select()
                    ->where("fch_inicio <= '".$fecha."'")
                    ->where("fch_fin >= '".$fecha."'")
                    ->order_by('fch_inicio','desc')
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getPeriodoActivo(){
        $q = $this->db->select()
                    ->where("estado",1)
                    //->order_by('fch_inicio','desc')
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }



    public function getPeriodosAnteriores($id_periodo_activo,$id_especialidad,$id_turno,$id_ciclo){
        $q = $this->db->select('tp.id, tp.nombre, tep.id_especialidad')
                    ->join('tespecialidad_periodo tep','tep.id_periodo = tp.id')
                    ->join('tmalla_periodo tmp','tmp.id_especialidad_periodo = tep.id')
                    ->join('tmalla tm','tmp.id_malla = tm.id')
                    ->join('tseccion_curso tsc','tsc.id_malla_periodo = tmp.id')
                    ->where('tep.id_especialidad',$id_especialidad)
                    ->where('tep.id_turno',$id_turno)
                    ->where('tmp.id_ciclo',$id_ciclo)
                    ->where('tsc.estado <',2)
                    ->where('tep.estado',1)
                    ->group_by('tep.id_periodo')
                    ->order_by('tp.id','desc')
                    ->get('tperiodo tp');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getMalla($id_curso,$id_especialidad){
        $q = $this->db->select()
                    ->where("id_especialidad",$id_especialidad)
                    ->where("id_curso",$id_curso)
                    //->order_by('fch_inicio','desc')
                    ->get('tmalla');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getEspecialidadPeriodo($id_especialidad_periodo,$id_turno = 1){
        $q = $this->db->select('tep.id, te.nombre as especialidad, tp.nombre as periodo')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->where("tep.id",$id_especialidad_periodo)
                    ->where("tep.id_turno",$id_turno)
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getEspecialidadPeriodo_($id_especialidad_periodo){
        $q = $this->db->select('tep.id, te.nombre as especialidad, tp.nombre as periodo')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->where("tep.id",$id_especialidad_periodo)
                    //->where("tep.id_especialidad",$id_especialidad)
//                    ->where("id_curso",$id_curso)
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getEspecialidadesForPeriodo($id_periodo,$id_turno = 0){
        $this->db->select('tam.*, te.nombre as especialidad');
        $this->db->join('tespecialidad te','te.id = tam.id_especialidad');
        //$this->db->join('talumno_matricula tam','tam.id_especialidad = tep.id_especialidad and tam.id_turno = tep.id_turno and tam.id_periodo = tep.id_periodo');
                    //->join('tperiodo tp','tp.id = tep.id_periodo')
        $this->db->where("tam.id_periodo",$id_periodo);
        if($id_turno != 0)
            $this->db->where("tam.id_turno",$id_turno);
        $this->db->group_by('tam.id_especialidad');
        $this->db->order_by('te.nombre','asc');
        $res = $this->db->get('talumno_matricula tam')->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getTurnosForEspecialidadPeriodo($id_periodo,$id_especialidad){
        $this->db->select('tam.*, te.nombre as especialidad');
        $this->db->join('tespecialidad te','te.id = tam.id_especialidad');
        //$this->db->join('talumno_matricula tam','tam.id_especialidad = tep.id_especialidad and tam.id_turno = tep.id_turno and tam.id_periodo = tep.id_periodo');
                    //->join('tperiodo tp','tp.id = tep.id_periodo')
        $this->db->where("tam.id_periodo",$id_periodo);
        $this->db->where("tam.id_especialidad",$id_especialidad);
        if($id_turno != 0)
            $this->db->where("tam.id_turno",$id_turno);
        $this->db->group_by('tam.id_turno');
        //$this->db->order_by('te.nombre','asc');
        $res = $this->db->get('talumno_matricula tam')->result();
        if($res)
            return $res;
        else
            return 0;
        /*$q = $this->db->select('tep.*')
                    //->join('tespecialidad te','te.id = tep.id_especialidad')
                    //->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->where("tep.id_periodo",$id_periodo)
                    ->where("tep.id_especialidad",$id_especialidad)
//                    ->where("id_curso",$id_curso)
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;*/
    }

    public function getCiclosForEspecialidadPeriodoTurno($id_periodo,$id_especialidad,$id_turno){
        $q = $this->db->select('tam.*')
                    //->join('tespecialidad te','te.id = tep.id_especialidad')
                    //->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->where("tam.id_periodo",$id_periodo)
                    ->where("tam.id_especialidad",$id_especialidad)
                    ->where("tam.id_turno",$id_turno)
                    ->group_by("tam.id_ciclo")
//                    ->where("id_curso",$id_curso)
                    ->get('talumno_matricula tam');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getMallaPeriodoForId($id){
        $q = $this->db->select()
                    //->where("id_especialidad",$id_especialidad)
                    ->where("id",$id)
                    //->order_by('fch_inicio','desc')
                    ->get('tmalla_periodo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getCicloForMalla($id_malla){
        $q = $this->db->select('tmp.*, tcl.nombre as ciclo')
                    ->join('tciclo tcl','tcl.id = tmp.id_ciclo')
                    ->where("tmp.id",$id_malla)
                    //->order_by('fch_inicio','desc')
                    ->get('tmalla_periodo tmp');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getPeriodoForId($id){
        $q = $this->db->select()
                    ->where("id",$id)
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getGrupoAnyWhere($where){
        $q = $this->db->select()
                    ->where($where)
                    ->get('tgrupo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getCursosModulo($where){
        $q = $this->db->select()
                    ->where($where)
                    ->get('tcurso_especialidad');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getCursosNoEncontrados($cod_alumno){
        $q = $this->db->query("select tm.id_curso, tc.codigo, tc.nombre as curso, tc.creditos, tm.id_ciclo from tmalla_periodo tmp INNER JOIN tmalla tm on tm.id = tmp.id_malla INNER JOIN tespecialidad_periodo tep on tep.id = tmp.id_especialidad_periodo INNER JOIN talumno_especialidad tae on tae.id_especialidad_periodo = tmp.id_especialidad_periodo INNER JOIN tcurso tc on tc.id = tm.id_curso WHERE tae.cod_alumno = '".$cod_alumno."' and tae.activo = 1 and not EXISTS ( select tamc.id_curso from talumno_matricula_curso tamc INNER JOIN talumno_matricula tam on tam.id = tamc.id_alumno_matricula INNER JOIN talumno_especialidad tae on tam.cod_alumno = tae.cod_alumno WHERE tae.activo = 1 and tam.cod_alumno = '".$cod_alumno."' and tm.id_curso = tamc.id_curso)");
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getAlumnosMatriculaCierre($id_periodo){
        $q = $this->db->select()
                    ->where('id_periodo',$id_periodo)
                    ->get('talumno_matricula');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosAlumnoMatriculaCierre($id_alumno_matricula){
        $q = $this->db->select('tam.*, ttec.tipo_eval, ttec.eval_minima, tamc.valor_nota')
                    ->join('talumno_matricula_curso tamc','tamc.id_alumno_matricula = tam.id')
                    ->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tamc.id_curso')
                    ->where('tam.id',$id_alumno_matricula)
                    ->get('talumno_matricula tam');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getEspecialdiadPer($id_periodo, $id_especialidad,$id_ciclo,$id_turno){
        $q = $this->db->select()
                    ->where('id_periodo_actual',$id_periodo)
                    ->where('id_especialidad',$id_especialidad)
                    ->where('id_ciclo_actual',$id_ciclo)
                    ->where('id_turno',$id_turno)
                    ->get('tespecialidad_periodo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

}