<?php
class Seccion extends CI_Model {

	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }
    
    public function getSeccionesAbiertas(){
        $q = $this->db->select()
                    ->where_in('estado',[0,1])
                    ->get('tseccion_curso');
        return $q->num_rows();
//        $res = $q->result();
//        if($res)
//            return $res;
//        else
//            return 0;
    }

    public function getSecciones($where){
        $q = $this->db->select()
                    ->where($where)
                    ->get('tseccion_curso');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getSeccionesInfo($id_periodo,$id_especialidad){
        $this->db->select('tsc.*, t.nombre as curso, ts.nombre as seccion, tc.nombre as ciclo, tt.nombre as turno');
        $this->db->join('tcurso t','t.id = tsc.id_curso');
        $this->db->join('tseccion ts','ts.id = tsc.id_seccion');
        $this->db->join('tmalla_periodo tmp','tmp.id = tsc.id_malla_periodo');
        $this->db->join('tciclo tc','tc.id = tmp.id_ciclo');
        $this->db->join('tespecialidad_periodo tep','tep.id = tmp.id_especialidad_periodo');
        $this->db->join('tturno tt','tt.id = tsc.id_turno');
        $this->db->where('tep.id_especialidad',$id_especialidad);
        $this->db->where('tsc.id_periodo',$id_periodo);
        $res = $this->db->get('tseccion_curso tsc')->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getSeccion($id){
        $q = $this->db->select()
                    ->where('id',$id)
                    ->get('tseccion_curso');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getJustSeccion($id){
        $q = $this->db->select()
                    ->where('id',$id)
                    ->get('tseccion');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getSeccionesForPeriodo($id_periodo){
        $q = $this->db->select('ts.*, tsc.id as id_seccion_curso, te.nombre as especialidad, tc.nombre as curso, tp.id as id_profesor, CONCAT(tper.nombre," ",tper.apell_pat," ",tper.apell_mat) as profesor, tsc.estado as estado_sec_curso, tm.id_ciclo, tt.nombre as turno')
                    ->join('tseccion ts','ts.id = tsc.id_seccion')
                    ->join('tespecialidad te','te.id = ts.id_especialidad')
                    ->join('tcurso tc','tc.id = tsc.id_curso')
                    ->join('tprofesor tp','tp.id = tsc.id_profesor')
                    ->join('tusuario tu','tu.id = tp.id_usuario')
                    ->join('tpersona tper','tper.id = tu.id_persona')
                    ->join('tmalla_periodo tmp','tmp.id = tsc.id_malla_periodo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('tturno tt','tt.id = tm.id_turno')
                    //->where('tsc.estado',1)
                    ->where('tsc.id_periodo',$id_periodo)
                    ->get('tseccion_curso tsc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function newSeccion($datos = array()){
        $q = $this->db->query("CALL proc_nueva_seccion(".$datos['id_especialidad'].",".$datos['id_curso'].",".$datos['id_profesor'].",".$datos['id_periodo'].",".$datos['id_turno'].",".$datos['id_malla_periodo'].");");
        $res = $q->result();
        mysqli_next_result( $this->db->conn_id );
        //$this->db->free_result();
        /*$q->next_result();*/
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function exitsSeccion($id_especialidad,$id_malla_periodo,$id_curso,$id_turno){
        $q = $this->db->select('ts.nombre as codigo_seccion, ts.id as id_seccion, tsc.id as id_seccion_curso')
                    ->join('tseccion ts','ts.id = tsc.id_seccion')
                    ->where('ts.id_especialidad',$id_especialidad)
                    ->where('tsc.estado',1)
                    ->where('tsc.id_malla_periodo',$id_malla_periodo)
                    ->where('tsc.id_curso',$id_curso)
                    //->where('tsc.id_turno',$id_turno)
                    ->get('tseccion_curso tsc');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function searchProfesor($nombre = ''){
        $q = $this->db->select('tp.*, tf.id as id_profesor, tp.id as id_persona, tu.id as id_usuario, tu.usuario, tf.codigo')
                    ->join('tusuario tu','tu.id = tf.id_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->where("CONCAT(tp.apell_pat,' ',tp.apell_mat,' ',tp.nombre) like '%".$nombre."%'")
                    ->get('tprofesor tf');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getProfesorForId($id){
        $q = $this->db->select('tp.*, tf.id as id_profesor, tp.id as id_persona, tu.id as id_usuario, tu.usuario, tf.codigo')
                    ->join('tusuario tu','tu.id = tf.id_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->where("tf.id",$id)
                    ->get('tprofesor tf');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getSeccionForMalla($id_malla_periodo){
        $q = $this->db->select('ts.nombre as seccion, tc.nombre as curso, tsc.id_curso, tsc.id_seccion, tsc.id as id_seccion_curso, tsc.estado as estado_seccion_curso, ts.estado as estado_seccion')
                    //->join('tusuario tu','tu.id = tf.id_usuario')
                    ->join('tseccion_curso tsc','ts.id = tsc.id_seccion')
                    ->join('tcurso tc','tc.id = tsc.id_curso')
                    ->where("tsc.id_malla_periodo",$id_malla_periodo)
                    ->get('tseccion ts');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function updateTSeccionCurso($where,$data){
        $this->db->where($where)->update('tseccion_curso',$data);
        return $this->db->affected_rows();
    }
    
    public function updateTseccion($where,$data){
        $this->db->where($where)->update('tseccion',$data);
        return $this->db->affected_rows();
    }

    public function getSeccionesForProfesor($id_profesor,$id_periodo){
        $q = $this->db->select('tsc.id as id_seccion_curso, ts.nombre as seccion, te.nombre as especialidad, tc.nombre as curso, tc.id as id_curso, tc.codigo as codigo_curso, tcl.nombre as ciclo, tmp.id as id_malla_periodo, tsc.estado as estado_seccion, tt.nombre as turno')
                    //->join('tusuario tu','tu.id = tf.id_usuario')
                    ->join('tseccion ts','ts.id = tsc.id_seccion')
                    ->join('tespecialidad te','te.id = ts.id_especialidad')
                    ->join('tcurso tc','tc.id = tsc.id_curso')
                    ->join('tmalla_periodo tmp','tmp.id = tsc.id_malla_periodo')
                    ->join('tciclo tcl','tcl.id = tmp.id_ciclo')
                    ->join('tturno tt','tt.id = tsc.id_turno')
                    ->where("tsc.id_profesor",$id_profesor)
                    ->where("tsc.id_periodo",$id_periodo)
                    ->get('tseccion_curso tsc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getCantSeccionesForProfesor($id_profesor,$id_periodo){
        $q = $this->db->select('')
                    ->join('tseccion ts','ts.id = tsc.id_seccion')
                    ->join('tespecialidad te','te.id = ts.id_especialidad')
                    ->join('tcurso tc','tc.id = tsc.id_curso')
                    ->join('tmalla_periodo tmp','tmp.id = tsc.id_malla_periodo')
                    ->join('tciclo tcl','tcl.id = tmp.id_ciclo')
                    ->where("tsc.id_profesor",$id_profesor)
                    ->where("tsc.id_periodo",$id_periodo)
                    ->get('tseccion_curso tsc');
        return $q->num_rows();
    }

    public function getAlumnosForSeccion($id_malla_periodo){
        $q = $this->db->select('tam.id as id_alumno_matricula, ta.codigo as codigo_alumno, tp.nombre, tp.apell_pat, tp.apell_mat, tp.dni, tam.id as id_alumno_matricula')
                    //->join('tusuario tu','tu.id = tf.id_usuario')
                    ->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('talumno ta','ta.codigo = tam.cod_alumno')
                    ->join('tusuario tu','tu.id = ta.id_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    //->join('tnota_curso_alumno tnca','tnca.id_alumno_matricula = tam.id','right')
                    //->where('tnca.id_curso',$id_curso)
                    ->where("tamc.id_malla_periodo",$id_malla_periodo)
                    ->order_by('tp.apell_pat, tp.apell_mat, tp.nombre')
                    ->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getNota($id_alumno_matricula,$id_curso){
        $q = $this->db->select()
                    //->join('tnota_curso_alumno tnca','tnca.id_alumno_matricula = tam.id','right')
                    ->where('tnca.id_curso',$id_curso)
                    ->where('tnca.id_recuperacion',0)
                    ->where("tnca.id_alumno_matricula",$id_alumno_matricula)
                    ->get('tnota_curso_alumno tnca');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getNotaMinima($id_curso){
        $q = $this->db->select()
                    //->join('tnota_curso_alumno tnca','tnca.id_alumno_matricula = tam.id','right')
                    ->where('ttec.id_curso',$id_curso)
                    ->get('ttipo_evaluacion_curso ttec');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getRecuperacion($id_recuperacion){
        $q = $this->db->select()
                    ->where('tnca.id_recuperacion',$id_recuperacion)
                    ->get('tnota_curso_alumno tnca');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getNotaRecuperacion($id_alumno_matricula,$id_curso,$id_nota){
        $q = $this->db->select()
                    ->where('tnca.id_curso',$id_curso)
                    ->where("tnca.id_alumno_matricula",$id_alumno_matricula)
                    ->where('tnca.id_recuperacion',$id_nota)
                    ->get('tnota_curso_alumno tnca');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getNotaPractica($id_alumno_matricula,$id_curso){
        $q = $this->db->select()
                    //->join('tnota_curso_alumno tnca','tnca.id_alumno_matricula = tam.id','right')
                    ->where('tnca.id_curso',$id_curso)
                    ->where("tnca.id_alumno_matricula",$id_alumno_matricula)
                    ->get('tnota_capacidad_curso_alumno tnca');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function newNota($data){
        $this->db->insert('tnota_curso_alumno',$data);
        $id = $this->db->insert_id();
        return $id;
    }
    
    public function newNotaPractica($data){
        $this->db->insert('tnota_capacidad_curso_alumno',$data);
        $id = $this->db->insert_id();
        return $id;
    }
    
    public function updateNota($where,$data){
        $this->db->where($where)->update('tnota_curso_alumno',$data);
    }
    
    public function getNotaVerificacion($where){
        $q = $this->db->select()
                    ->where($where)
                    ->get('tnota_curso_alumno tnca');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getTipoEvaluacion($id_curso){
        $q = $this->db->select()
                    ->where('ttec.id_curso',$id_curso)
                    ->get('ttipo_evaluacion_curso ttec');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getItemCapacidadConsult($id = 0){
        $q = $this->db->select()
                    ->where('tci.id',$id)
                    ->get('tcapacidades_items tci');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function updateItemCapacidad($data,$id){
        $this->db->where('id',$id)->update('tcapacidades_items',$data);
    }
    
    public function getCapacidadConsult($id = 0){
        $q = $this->db->select()
                    ->where('tcc.id',$id)
                    ->get('tcapacidades_curso tcc');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function updateCapacidadConsult($data,$id){
        $this->db->where('id',$id)->update('tcapacidades_curso',$data);
    }
    
    public function getCapacidadesSeccionCurso($id_seccion_curso){
        $q = $this->db->select()
                    ->where('tcc.id_seccion_curso',$id_seccion_curso)
                    ->get('tcapacidades_curso tcc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getItemsCapacidadesSeccionCurso($id_capacidad){
        $q = $this->db->select()
                    ->where('tci.id_capacidad',$id_capacidad)
                    ->get('tcapacidades_items tci');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function newCapacidadSeccion($data){
        $this->db->insert('tcapacidades_curso',$data);
        $id = $this->db->insert_id();
        return $id;
    }
    
    public function newItemCapacidadSeccion($data){
        $this->db->insert('tcapacidades_items',$data);
        $id = $this->db->insert_id();
        return $id;
    }
    
}