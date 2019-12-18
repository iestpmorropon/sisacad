<?php
class Evaluaciones extends CI_Model {

	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function getTipoNotaNoRegulares(){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    //->where('estado',1)
                    ->get('ttipo_nota_no_regular');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getTipoEvalForCurso($id_curso){
        $q = $this->db->select('ttec.tipo_eval, ttec.eval_minima')
                    //->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tamc.id_curso')
                    ->where('ttec.id_curso',$id_curso)
                    ->get('ttipo_evaluacion_curso ttec');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getCursoAlumno($id){
        $q = $this->db->select('tamc.*, ttec.tipo_eval, ttec.eval_minima')
                    ->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tamc.id_curso')
                    ->where('tamc.id',$id)
                    ->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getActaNoRegular($data){
        $q = $this->db->select()
                    //->join('talumno_matricula_curso tamc','tamc.id_alumno_matricula = tam.id')
                    ->where('tanr.id_especialidad',$data['id_especialidad'])
                    ->where('tanr.id_periodo',$data['id_periodo'])
                    ->where('tanr.id_turno',$data['id_turno'])
                    ->where('tanr.id_ciclo',$data['id_ciclo'])
                    ->where('tanr.fecha',$data['fecha'])
                    ->where('tanr.id_tipo_nota_no_regular',$data['id_tipo_nota_no_regular'])
                    ->get('tacta_no_regular tanr');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getNotaNoRegularForAlumno($id_alumno_matricula_curso){
        $q = $this->db->select()
                    ->where('tnnr.id_alumno_matricula_curso',$id_alumno_matricula_curso)
                    ->get('tnota_no_regular tnnr');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getNotaNoRegularForAlumnoAux($cod_alumno,$id_curso){
        $q = $this->db->select()
                    ->where('tnnra.cod_alumno',$cod_alumno)
                    ->where('tnnra.id_curso',$id_curso)
                    //->where('tnnra.id_periodo',$id_periodo)
                    ->order_by('tnnra.id','desc')
                    ->get('tnota_no_regular_aux tnnra');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getNotaNoRegularForAlumnoAuxCertificado($cod_alumno,$id_curso,$id_especialidad,$id_ciclo){
        $q = $this->db->select('tnnra.*, tp.nombre as periodo, ttnnr.nombre as tipo_no_regular, ttnnr.abrev')
                    ->join('tperiodo tp','tp.id = tnnra.id_periodo')
                    ->join('ttipo_nota_no_regular ttnnr','ttnnr.id = tnnra.id_tipo_nota_no_regular')
                    ->where('tnnra.cod_alumno',$cod_alumno)
                    ->where('tnnra.id_curso',$id_curso)
                    ->where('tnnra.id_especialidad',$id_especialidad)
                    ->where('tnnra.id_ciclo',$id_ciclo)
                    ->order_by('tnnra.id','desc')
                    ->get('tnota_no_regular_aux tnnra');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getActaNoRegularForId($id){
        $q = $this->db->select()
                    //->join('talumno_matricula_curso tamc','tamc.id_alumno_matricula = tam.id')
                    ->where('tanr.id',$id)
                    ->get('tacta_no_regular tanr');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getTipoNotaNoRegular($id){
        $q = $this->db->select()
                    //->join('talumno_matricula_curso tamc','tamc.id_alumno_matricula = tam.id')
                    ->where('ttnnr.id',$id)
                    ->get('ttipo_nota_no_regular ttnnr');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getActaNoRegular_($data){
        $q = $this->db->select()
                    ->join('talumno_matricula_curso tamc','tamc.id_alumno_matricula = tam.id')
                    ->where('tam.id_especialidad',$data['id_especialidad'])
                    ->where('tam.id_periodo',$data['id_periodo'])
                    ->where('tam.id_turno',$data['id_turno'])
                    ->where('tam.id_ciclo',$data['id_ciclo'])
                    //->where('')
                    ->get('talumno_matricula tam');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getActasForFechaAux($data){
        $q = $this->db->select('tnnra.*, ttnr.nombre as tipo_no_regular, tpr.nombre, tpr.apell_pat, tpr.apell_mat, tp.nombre as periodo, tt.nombre as turno, te.nombre as especialidad, tcl.nombre as ciclo, tcr.nombre as curso, tnnra.fecha_acta as fecha')
                    ->join('ttipo_nota_no_regular ttnr','ttnr.id = tnnra.id_tipo_nota_no_regular')
                    ->join('tespecialidad te','te.id = tnnra.id_especialidad')
                    ->join('tperiodo tp','tp.id = tnnra.id_periodo')
                    ->join('tciclo tcl','tcl.id = tnnra.id_ciclo')
                    ->join('tturno tt','tt.id = tnnra.id_turno')
                    ->join('tacta_no_regular tanr','tnnra.id_acta = tanr.id')
                    //->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso')
                    //->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('talumno ta','ta.codigo = tnnra.cod_alumno')
                    ->join('tusuario tu','tu.id = ta.id_usuario')
                    ->join('tpersona tpr','tpr.id = tu.id_persona')
                    ->join('tcurso tcr','tcr.id = tnnra.id_curso')
                    ->where('tnnra.id_acta != 0')
                    ->where("tnnra.fecha_acta BETWEEN '".$data['desde']."' AND '".$data['hasta']."'")
                    //->where('')
                    ->get('tnota_no_regular_aux tnnra');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getActasForFecha($data){
        $q = $this->db->select('tanr.*, ttnr.nombre as tipo_no_regular, tpr.nombre, tpr.apell_pat, tpr.apell_mat, tp.nombre as periodo, tt.nombre as turno, te.nombre as especialidad, tcl.nombre as ciclo, tcr.nombre as curso')
                    ->join('ttipo_nota_no_regular ttnr','ttnr.id = tanr.id_tipo_nota_no_regular')
                    ->join('tespecialidad te','te.id = tanr.id_especialidad')
                    ->join('tperiodo tp','tp.id = tanr.id_periodo')
                    ->join('tciclo tcl','tcl.id = tanr.id_ciclo')
                    ->join('tturno tt','tt.id = tanr.id_turno')
                    ->join('tnota_no_regular tnnr','tnnr.id_acta = tanr.id')
                    ->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso')
                    ->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('talumno ta','ta.codigo = tam.cod_alumno')
                    ->join('tusuario tu','tu.id = ta.id_usuario')
                    ->join('tpersona tpr','tpr.id = tu.id_persona')
                    ->join('tcurso tcr','tcr.id = tamc.id_curso')
                    ->where("tanr.fecha BETWEEN '".$data['desde']."' AND '".$data['hasta']."'")
                    //->where('')
                    ->get('tacta_no_regular tanr');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getActasForFecha_($data){
        $q = $this->db->select('tanr.*')
                    ->where("tanr.fecha BETWEEN '".$data['desde']."' AND '".$data['hasta']."'")
                    //->where('')
                    ->get('tacta_no_regular tanr');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getAlumno($id_alumno_matricula_curso){
        $this->db->select('tamc.id as id_alumno_matricula_curso, tm.cod_alumno, tp.apell_pat, tp.apell_mat, tp.nombre, tp.dni');
        $this->db->join('talumno_matricula tm','tm.id = tamc.id_alumno_matricula');
        $this->db->join('talumno ta','ta.codigo = tm.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->where('tamc.id',$id_alumno_matricula_curso);
        $this->db->from('talumno_matricula_curso tamc');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getEvaluacionesNoRegulares($id){
        $this->db->select(' tam.cod_alumno, tamc.id_curso, tnnr.* , tp.apell_pat, tp.apell_mat, tp.nombre');
        $this->db->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso');
        $this->db->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula');
        $this->db->join('talumno ta','ta.codigo = tam.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        //$this->db->join('')
        $this->db->where('tnnr.id_acta',$id);
        $this->db->order_by('tp.apell_pat, tp.apell_mat, tp.nombre');
        $this->db->from('tnota_no_regular tnnr');
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getEvaluacionesNoRegularesAux($id_acta){
        $this->db->select(' tnnra.cod_alumno, tnnra.id_curso, tnnra.* , tp.apell_pat, tp.apell_mat, tp.nombre');
        //$this->db->join('talumno_matricula_curso tamc','tamc.id = tnnra.id_alumno_matricula_curso');
        //$this->db->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula');
        $this->db->join('talumno ta','ta.codigo = tnnra.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        //$this->db->join('')
        $this->db->where('tnnra.id_acta',$id_acta);
        $this->db->order_by('tp.apell_pat, tp.apell_mat, tp.nombre');
        $this->db->from('tnota_no_regular_aux tnnra');
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosAuxiliares($cod_alumno){
        $this->db->select('tnnra.id_tipo_nota_no_regular, tnnra.valor_nota, tnnra.estado, tnnra.fecha_acta, tnnra.fecha_registro, tnnra.id_profesor, tnnra.id_acta, tnnra.id_curso, ttec.eval_minima, tc.nombre as curso, tc.creditos');
        $this->db->from('tnota_no_regular_aux tnnra');
        $this->db->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tnnra.id_curso');
        $this->db->join('tcurso tc','tc.id = ttec.id_curso');
        $this->db->where('tnnra.cod_alumno',$cod_alumno);
        //$this->db->where
        $this->db->group_by('tnnra.id_curso');
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosAlumnoMatriculaNotaNoRegular($id){
        $this->db->select('tnnr.*, tamc.id_curso, tcr.nombre as curso, tcr.creditos, tm.orden');
        $this->db->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso');
        $this->db->join('tcurso tcr','tcr.id = tamc.id_curso');
        $this->db->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo');
        $this->db->join('tmalla tm','tm.id = tmp.id_malla');
        $this->db->where('tnnr.id_acta',$id);
        $this->db->group_by('tamc.id_curso');
        $this->db->order_by('tm.orden');
        $this->db->from('tnota_no_regular tnnr');
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getAlumnoNotaNoRegular($id_curso,$id_alumno_matricula_curso,$asc = 0){
        $this->db->select('tnnr.*, tamc.id_curso, ttec.eval_minima');
        $this->db->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso');
        $this->db->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tamc.id_curso');
        $this->db->where('tamc.id_curso',$id_curso);
        $this->db->where('tnnr.id_alumno_matricula_curso',$id_alumno_matricula_curso);
        if($asc)
            $this->db->order_by('tnnr.id','desc');
        $this->db->from('tnota_no_regular tnnr');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getAlumnoNotaNoRegularCertificado($id_curso,$cod_alumno){
        $this->db->select('tnnr.*, tamc.id_curso, ttnnr.nombre, ttnnr.abrev, tp.nombre as periodo');
        $this->db->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso');
        //$this->db->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tamc.id_curso');
        $this->db->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->join('ttipo_nota_no_regular ttnnr','ttnnr.id = tnnr.id_tipo_nota_no_regular');
        $this->db->where('tamc.id_curso',$id_curso);
        $this->db->where('tam.cod_alumno',$cod_alumno);
        //$this->db->where('tnnr.id_alumno_matricula_curso',$id_alumno_matricula_curso);
        $this->db->order_by('tnnr.id','desc');
        $this->db->from('tnota_no_regular tnnr');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function newActaNoRegular($data){
        $this->db->insert('tacta_no_regular',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function newNotaNoRegular($data){
        $this->db->insert('tnota_no_regular',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function newNotaNoRegularAux($data){
        $this->db->insert('tnota_no_regular_aux',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function updateNotaNoRegular($data,$id_nota_no_regular){
        $this->db->where('id',$id_nota_no_regular)->update('tnota_no_regular',$data);
    }

    public function updateNotaNoRegularAux($data,$id_nota_no_regular){
        $this->db->where('id',$id_nota_no_regular)->update('tnota_no_regular_aux',$data);
    }

    public function getNotaAlumnoCursoRegular($id_periodo,$id_especialidad,$id_turno,$id_ciclo){
        $this->db->select('tamc.id, tp.apell_pat, tp.apell_mat, tp.nombre, tp.dni, tc.nombre as curso, tc.codigo, tamc.valor_nota, tamc.estado');
        $this->db->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula');
        $this->db->join('talumno ta','ta.codigo = tam.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->join('tcurso tc','tc.id = tamc.id_curso');
        $this->db->where('tam.id_periodo',$id_periodo);
        $this->db->where('tam.id_especialidad',$id_especialidad);
        $this->db->where('tam.id_turno',$id_turno);
        $this->db->where('tam.id_ciclo',$id_ciclo);
        $this->db->from('talumno_matricula_curso tamc');
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getNotaAlumnoCursoRegular_($id_alumno_matricula_curso){
        $this->db->select('tamc.id, tp.apell_pat, tp.apell_mat, tp.nombre, tp.dni, tc.nombre as curso, tc.codigo, tamc.valor_nota, tamc.estado, te.nombre as especialidad, tpd.nombre as periodo, tt.nombre as turno, tam.id_ciclo, ttec.tipo_eval, ttec.eval_minima');
        $this->db->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula');
        $this->db->join('talumno ta','ta.codigo = tam.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->join('tcurso tc','tc.id = tamc.id_curso');
        $this->db->join('tespecialidad te','te.id = tam.id_especialidad');
        $this->db->join('tperiodo tpd','tpd.id = tam.id_periodo');
        $this->db->join('tturno tt','tt.id = tam.id_turno');
        $this->db->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tamc.id_curso');
        $this->db->join('ttipo_evaluacion tte','tte.id = ttec.tipo_eval');
        $this->db->where('tamc.id',$id_alumno_matricula_curso);
        $this->db->from('talumno_matricula_curso tamc');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function updateNotaAlumnoCursoRegular($id, $data){
        $this->db->where('id',$id)->update('talumno_matricula_curso',$data);
    }

    public function getConsultaEvaluacionesNoRegulares($id_alumno_matricula_curso,$aprobatoria = 1, $count = 0){
        $this->db->select('tnnr.id as id_nota_no_regular, tc.codigo, tamc.id_curso, tc.nombre as curso, ttnnr.nombre as tipo_nota, tnnr.valor_nota, tnnr.fecha_acta, tnnr.fecha_registro, ttnnr.id as id_tipo_nota_no_regular');
        $this->db->join('ttipo_nota_no_regular ttnnr','ttnnr.id = tnnr.id_tipo_nota_no_regular');
        $this->db->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso');
        $this->db->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula');
        //$this->db->join('tacta_no_regular tanr')
        $this->db->join('tcurso tc','tc.id = tamc.id_curso');
        $this->db->where('tnnr.id_alumno_matricula_curso',$id_alumno_matricula_curso);
        if($aprobatoria == 2)
            $this->db->order_by('tnnr.id','desc');
        $this->db->from('tnota_no_regular tnnr');
        $res = $this->db->get()->result();
        if($res){
            if($count)
                return $res[0];
            else
                return $res;
        }
        else
            return 0;
    }

    public function getConsultaEvaluacionesNoRegularesAux($cod_alumno,$id_curso,$aprobatoria = 1, $count = 0){
        $this->db->select('tnnra.id as id_nota_no_regular_aux, tc.codigo, tnnra.id_curso, tc.nombre as curso, ttnnr.nombre as tipo_nota, tnnra.valor_nota, tnnra.fecha_acta, tnnra.fecha_registro, ttnnr.id as id_tipo_nota_no_regular, tnnra.id_periodo, tnnra.id_turno');
        $this->db->join('ttipo_nota_no_regular ttnnr','ttnnr.id = tnnra.id_tipo_nota_no_regular');
        //$this->db->join('talumno_matricula_curso tamc','tamc.id = tnnra.id_alumno_matricula_curso');
        //$this->db->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula');
        //$this->db->join('tacta_no_regular tanr')
        $this->db->join('tcurso tc','tc.id = tnnra.id_curso');
        $this->db->where('tnnra.cod_alumno',$cod_alumno);
        $this->db->where('tnnra.id_curso',$id_curso);
        if($aprobatoria == 2)
            $this->db->order_by('tnnra.valor_nota','desc');
        $this->db->from('tnota_no_regular_aux tnnra');
        $res = $this->db->get()->result();
        if($res){
            if($count)
                return $res[0];
            else
                return $res;
        }
        else
            return 0;
    }

    public function getNotaNoRegular($id){
        $this->db->select();
        $this->db->where('tamc.id',$id);
        $this->db->from('tnota_no_regular tamc');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getNotaNoRegularAux($id){
        $this->db->select();
        $this->db->where('tamc.id',$id);
        $this->db->from('tnota_no_regular_aux tamc');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function deleteRegistroNoRegular($id){
        $this->db->where('id',$id)->delete('tnota_no_regular');
    }

    public function deleteRegistroNoRegularAux($id){
        $this->db->where('id',$id)->delete('tnota_no_regular_aux');
    }

    public function getEvalMinimaNoRegular($id_nota_no_regular){
        $this->db->select('tnnr.id as id_nota_no_regular, tamc.id as id_alumno_matricula_curso, tc.id as id_curso, ttec.tipo_eval, ttec.eval_minima');
        $this->db->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso');
        $this->db->join('tcurso tc','tc.id = tamc.id_curso');
        $this->db->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tc.id');
        $this->db->where('tnnr.id',$id_nota_no_regular);
        $this->db->from('tnota_no_regular tnnr');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getEvalMinimaNoRegularAux($id_nota_no_regular_aux){
        $this->db->select('tnnra.id as id_nota_no_regular_aux, tc.id as id_curso, ttec.tipo_eval, ttec.eval_minima');
        //$this->db->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso');
        $this->db->join('tcurso tc','tc.id = tnnra.id_curso');
        $this->db->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tc.id');
        $this->db->where('tnnra.id',$id_nota_no_regular_aux);
        $this->db->from('tnota_no_regular_aux tnnra');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function consultaCantidadRegistrosForActa($id_acta){
        $this->db->select();
        $this->db->where('tamc.id_acta',$id_acta);
        $this->db->from('tnota_no_regular tamc');
        $res = $this->db->get()->num_rows();
        return $res;
    }

    public function consultaCantidadRegistrosForActaAux($id_acta){
        $this->db->select();
        $this->db->where('tamc.id_acta',$id_acta);
        $this->db->from('tnota_no_regular_aux tamc');
        $res = $this->db->get()->num_rows();
        return $res;
    }

    public function deleteActa($id_acta){
        $this->db->where('id',$id_acta)->delete('tacta_no_regular');
    }

}