<?php
class Alumno extends CI_Model {

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }
    
    public function getCantidadAlumnos($id_periodo){
        $q = $this->db->select()
                //->join('trol tr','tr.id = tpr.id_rol')
                ->where('estado_semestre',1)
                ->where('id_periodo',$id_periodo)
                ->get('talumno_matricula');
        return $q->num_rows();
//        $res = $q->result();
//        if($res)
//            return $res;
//        else
//            return 0;
    }

    public function updateAlumno($codigo, $data){
        $this->db->where('codigo',$codigo)->update('talumno',$data);
    }

    public function getAlumnoWhere($where){
        $q = $this->db->select()
                //->join('trol tr','tr.id = tpr.id_rol')
                ->where($where)
                ->get('talumno');
        return $q->num_rows();
        /*$res = $q->result();
        if($res)
            return $res;
        else
            return 0;*/
    }

    public function getAlumnoForWhere($where){
        $q = $this->db->select()
                //->join('trol tr','tr.id = tpr.id_rol')
                ->where($where)
                ->get('talumno');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getAlumnoEgreso($where){
        $q = $this->db->select()
                //->join('trol tr','tr.id = tpr.id_rol')
                ->where($where)
                ->get('talumno');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getCachimbos(){
        $q = $this->db->select()
                //->join('trol tr','tr.id = tpr.id_rol')
                ->where_in('id_ciclo',[0,1])
                ->where('estado',1)
                ->get('talumno_especialidad');
        return $q->num_rows();
    }

    /*public function getAlumnoEspecialidad($where){
        //talumno_especialidad
        $q = $this->db->select()
                //->join('trol tr','tr.id = tpr.id_rol')
                ->where($where)
                ->get('talumno_especialidad');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }*/

    public function getMatriculaExistente($where){
        $q = $this->db->select()
                //->join('trol tr','tr.id = tpr.id_rol')
                ->where($where)
                ->get('talumno_matricula');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function updateAlumnoMatricula($where,$data){
        $this->db->where($where)->update('talumno_matricula',$data);
    }

    public function updateAlumnoEspecialidad($id, $data){
        $this->db->where('id',$id)->update('talumno_especialidad',$data);
    }

    public function updateAlumnoEspecialidad_($where, $data){
        $this->db->where($where)->update('talumno_especialidad',$data);
    }

    public function updateAlumnoEspecialidadEsp($where, $data){
        $this->db->query('UPDATE talumno_especialidad SET id_ciclo = id_ciclo_anterior, id_periodo_actual = id_periodo_anterior WHERE id_ciclo = '.$where['id_ciclo']." and cod_alumno = '".$where['cod_alumno']."' and id_periodo_actual = ".$where['id_periodo_actual'].";");
    }

    public function updateAlumnoEspecialidadEsp2($id){
        $this->db->query('UPDATE talumno_especialidad SET id_ciclo_anterior = id_ciclo, id_periodo_anterior = id_periodo_actual WHERE id = '.$id.';');
    }

    public function getTipoAlumno(){
            $q = $this->db->select()
                //->join('trol tr','tr.id = tpr.id_rol')
                ->where('estado',1)
                ->get('ttipoalumno');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getGenero(){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    //->where('estado',1)
                    ->get('tgenero');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getEstadoCivil(){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    //->where('estado',1)
                    ->get('testado_civil');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function newAlumno($datos = array()){
        $q = $this->db->query("CALL proc_nuevo_alumno(".$datos['id_especialidad'].",".$datos['id_periodo'].",".$datos['id_persona'].",'".$datos['fch_ingreso']."','".$datos['turno']."','".$datos['pass']."',".$datos['alumnno_tipo'].",'".$datos['dni']."');");
        $res = $q->result();
        mysqli_next_result( $this->db->conn_id );
        //$this->db->free_result();
        /*$q->next_result();*/
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function registerUsuario($datos = array()){
        if(count($datos) == 0)
            return 0;
        $this->db->insert('tusuario',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function updateUsuario($data,$where){
        $this->db->where($where)->update('tusuario',$data);
    }

    public function newEspecialidadAlumno($datos = array()){
        $this->db->insert('talumno_especialidad',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function getAlumnoForCodigo($codigo){
        $this->db->select('tu.id as id_usuario, tp.id as id_persona, ta.codigo, tp.nombre, tp.apell_pat, tp.apell_mat, tp.dni, tp.telefono, tp.celular_1, tp.celular_2, tp.direccion, tp.email, tp.fch_nac, tp.id_genero, tp.id_estado_civil');
        $this->db->from('talumno ta');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->where('ta.codigo',$codigo);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getAlumnosFiltro($where){
        $this->db->select('ta.codigo, tp.nombre, tp.apell_pat, tp.apell_mat, te.nombre as especialidad');
        $this->db->from('talumno ta');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->join('talumno_especialidad tae','ta.codigo = tae.cod_alumno');
        $this->db->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo');
        $this->db->join('tespecialidad te','te.id = tep.id_especialidad');
        $this->db->where('tep.id_especialidad',$where['id_especialidad']);
        $this->db->like('tp.nombre',$where['nombre'],'both');
        $this->db->like('tp.apell_pat',$where['apell_pat'],'both');
        $this->db->like('tp.apell_mat',$where['apell_mat'],'both');
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getAlumnosCodigo($codigo){
        $this->db->select('ta.codigo, tp.nombre, tp.apell_pat, tp.apell_mat, tp.dni');
        $this->db->from('talumno ta');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->like('ta.codigo',$codigo,'both');
        //$this->db->or_like('')
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getAlumnosForDni($data){
        $this->db->select("tu.id as id_usuario, tp.id as id_persona, ta.codigo, tp.nombre, tp.apell_pat, tp.apell_mat, tp.dni");
        $this->db->from('talumno ta');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->like('tp.dni',$data,'both');
         $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getAlumnosNombreorApellidos($data){
        $q = $this->db->query("select tp.id as id_persona, ta.id_usuario, ta.codigo, tp.nombre, tp.apell_pat, tp.apell_mat, tp.dni from talumno ta inner join tusuario tu on tu.id = ta.id_usuario inner join (select id, apell_pat, apell_mat, dni, nombre from tpersona where CONCAT(apell_pat,' ',apell_mat,' ',nombre) like '%".$data."%') tp on tp.id = tu.id_persona "); 
        /*$this->db->select("CONCAT(tp.apell_pat,' ',tp.apell_mat,' ',tp.nombre) as nombres, tp.id, tp.dni");
        $this->db->from('tpersona tp');
        //$this->db->like('',$data);
        $anidado = $this->db->get_compiled_select();
        $this->db->select("ta.codigo, tp.nombre, tp.apell_pat, tp.apell_mat, tp.dni");
        $this->db->from('talumno ta');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join($anidado.' tp','tp.id = tu.id_persona');
        $this->db->like('tp.nombres',$data,'both');*/
        //$this->db->or_like("apellidos",$data,'both');
        //$this->db->or_like('tp.apell_mat',$data,'both');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getDataAlumno($where){
        $this->db->select('tp.*, tu.*, tap.nombre as tipo_alumno, tp.id as id_usuario');
        $this->db->from('talumno ta');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->join('talumnotipo tap','tap.id = ta.id_alumno_tipo');
        $this->db->where('ta.codigo',$where['codigo']);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getEspecialidadPeriodoForAlumno($codigo){
        $this->db->select('te.nombre as especialidad, tp.nombre, tc.id as id_ciclo, tc.nombre as ciclo, tt.nombre as turno, tep.id as id_especialidad_periodo, tae.expediente_ingreso, tae.id_turno');
        $this->db->from('talumno_especialidad tae');
        $this->db->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo');
        $this->db->join('tespecialidad te','te.id = tep.id_especialidad');
        $this->db->join('tperiodo tp','tp.id = tep.id_periodo');
        $this->db->join('tciclo tc','tc.id = tae.id_ciclo');
        $this->db->join('tturno tt','tt.id = tae.id_turno');
        $this->db->where('tae.cod_alumno',$codigo);
        $this->db->where('tae.activo',1);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getEspecialidadPeriodoForId($id){
        $this->db->select('tam.cod_alumno, te.nombre as especialidad, tp.nombre as periodo, tc.nombre as ciclo, tt.nombre as turno');
        $this->db->from('talumno_matricula tam');
        //$this->db->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo');
        $this->db->join('tespecialidad te','te.id = tam.id_especialidad');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->join('tciclo tc','tc.id = tam.id_ciclo');
        $this->db->join('tturno tt','tt.id = tam.id_turno');
        $this->db->where('tam.id',$id);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getAlumnosEspecialidadPeriodo($id_especialidad,$id_periodo){
        $this->db->select('ta.codigo, tpr.nombre, tpr.apell_pat, tpr.apell_mat, tm.id_grupo, tg.nombre as grupo, tm.estado_semestre');
        $this->db->from('talumno_matricula tm');
        $this->db->join('talumno ta','ta.codigo = tm.cod_alumno');
        $this->db->join('tespecialidad te','te.id = tm.id_especialidad');
        $this->db->join('tperiodo tp','tp.id = tm.id_periodo');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tpr','tpr.id = tu.id_persona');
        $this->db->join('tgrupo tg','tg.id = tm.id_grupo');
        $this->db->where('ta.estado',1);
        //$this->db->where('tm.estado_semestre',1);
        $this->db->where('tm.id_especialidad',$id_especialidad);
        $this->db->where('tm.id_periodo',$id_periodo);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getEspecialidadAlumno($codigo_alumno){
        $this->db->select('tae.id_especialidad_periodo, tae.id_ciclo,  te.id as id_especialidad, te.nombre as especialidad, tt.id as id_turno, tt.nombre as turno, tae.estado, tae.id_periodo_actual, tp.nombre as periodo');
        $this->db->from('talumno_especialidad tae');
        $this->db->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo');
        $this->db->join('tespecialidad te','te.id = tep.id_especialidad');
        $this->db->join('tperiodo tp','tp.id = tae.id_periodo_actual');
        //$this->db->join('tgrupo tg','tg.id = tae.id_grupo');
        $this->db->join('tturno tt','tt.id = tep.id_turno');
        //$this->db->where('tae.estado',1);
        //$this->db->where('tm.estado_semestre',1);
        $this->db->where('tae.cod_alumno',$codigo_alumno);
        //$this->db->where('tm.id_periodo',$id_periodo);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getMatriculaAlumnoAprobada($codigo_alumno,$id_especialidad){
        $this->db->select('tam.*, te.nombre as especialidad');
        $this->db->from('talumno_matricula tam');
        $this->db->join('tespecialidad te','te.id = tam.id_especialidad');
        $this->db->where('tam.estado_semestre',1);
        $this->db->where('tam.cod_alumno',$codigo_alumno);
        $this->db->where('tam.id_especialidad',$id_especialidad);
        $this->db->order_by('tam.id','desc');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getMatriculaAlumno($codigo_alumno){
        $this->db->select('tm.*, te.nombre as especialidad, tp.nombre as periodo, tm.estado_semestre');
        $this->db->from('talumno_matricula tm');
        $this->db->join('tespecialidad te','te.id = tm.id_especialidad');
        $this->db->join('tperiodo tp','tp.id = tm.id_periodo');
        //$this->db->where('ta.estado',1);
        //$this->db->where('tm.estado_semestre',1);
        $this->db->where('tm.cod_alumno',$codigo_alumno);
        //$this->db->where('tm.id_periodo',$id_periodo);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getMatriculaAlumnoActivo($codigo_alumno){
        $this->db->select('tm.*, te.nombre as especialidad, tp.nombre as periodo, tm.estado_semestre, tt.nombre as turno');
        $this->db->from('talumno_matricula tm');
        $this->db->join('tespecialidad te','te.id = tm.id_especialidad');
        $this->db->join('tperiodo tp','tp.id = tm.id_periodo');
        $this->db->join('talumno_especialidad tae','tae.cod_alumno = tm.cod_alumno');
        $this->db->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo');
        $this->db->join('tturno tt','tt.id = tm.id_turno');
        $this->db->where('tae.activo',1);
        $this->db->where('tm.id_periodo >= tep.id_periodo');
        //$this->db->where('tm.estado_semestre',1);
        $this->db->where('tm.cod_alumno',$codigo_alumno);
        //$this->db->where('tm.id_periodo',$id_periodo);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function deleteTnotaCapacidadCursoAlumno($where){
        $this->db->where($where)->delete('tnota_capacidad_curso_alumno');
    }

    public function deleteTnotaCursoAlumno($where){
        $this->db->where($where)->delete('tnota_curso_alumno');
    }

    public function deleteTalumnoMatriculaCurso($where){
        $this->db->where($where)->delete('talumno_matricula_curso');
    }

    public function deleteTalumnoMatricula($where){
        $this->db->where($where)->delete('talumno_matricula');
    }

    public function getSeccionCursoForIdMallaPeriodo($id_malla_periodo, $id_turno){
        $this->db->select('');
        $this->db->from('tseccion_curso tsc');
        $this->db->where('tsc.id_malla_periodo',$id_malla_periodo);
        $this->db->where('tsc.id_turno',$id_turno);
        $this->db->where('tsc.id_curso',$id_curso);
        $res = $this->db->get();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getSeccion($id_ciclo,$id_especialidad_periodo,$id_periodo,$id_turno,$id_curso){
        $this->db->select('tsc.*, tmp.id as id_malla_periodo');
        $this->db->from('tseccion_curso tsc');
        $this->db->join('tmalla_periodo tmp','tmp.id = tsc.id_malla_periodo');
        $this->db->where('tmp.id_ciclo',$id_ciclo);
        $this->db->where('tmp.id_especialidad_periodo',$id_especialidad_periodo);
        $this->db->where('tsc.id_periodo',$id_periodo);
        $this->db->where('tsc.id_curso',$id_curso);
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getGrupo($id_especialidad_periodo,$id_turno){
        $this->db->select('');
        $this->db->from('tgrupo');
        $this->db->where('id_especialidad_periodo',$id_especialidad_periodo);
        $this->db->where('id_turno',$id_turno);
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function newMatricula($data){
        $this->db->insert('talumno_matricula',$data);
        $id = $this->db->insert_id();
        return $id;
    }
    
    public function updateCicloInEspecialidad($codigo_alumno,$id_ciclo){
        $this->db->where(['cod_alumno'=>$codigo_alumno,'estado'=>1])->update('talumno_especialidad',['id_ciclo'=>$id_ciclo]);
    }

    public function newMatriculaCurso($data){
        $this->db->insert('talumno_matricula_curso',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function updateAlumnoMatriculaCurso($where,$data){
        $this->db->where($where)->update('talumno_matricula_curso',$data);
    }

    public function newGrupo($datos){
        $q = $this->db->query("CALL proc_nuevo_grupo(".$datos['id_especialidad'].",".$datos['id_periodo'].",".$datos['id_turno'].",".$datos['id_especialidad_periodo'].");");
        $res = $q->result();
        mysqli_next_result( $this->db->conn_id );
        //$this->db->free_result();
        /*$q->next_result();*/
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getAlumnoEspecialidad($cod_alumno){
        $q = $this->db->select('tae.*, te.nombre as especialidad, tp.nombre as periodo, tcl.nombre as ciclo, tt.nombre as turno')
                    ->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->join('tciclo tcl','tcl.id = tae.id_ciclo')
                    ->join('tturno tt','tt.id = tae.id_turno')
                    //->where('tam.estado',1)
                    ->where('tae.cod_alumno',$cod_alumno)
                    ->get('talumno_especialidad tae');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    //Querys para ver el historial de los alumnos
    public function getMatriculasHistorial($cod_alumno,$id_especialidad,$id_periodo){
        $q = $this->db->select('tam.*, tp.nombre as periodo, tcl.nombre as ciclo')
                    ->join('tperiodo tp','tp.id = tam.id_periodo')
                    ->join('tciclo tcl','tcl.id = tam.id_ciclo')
                    //->where('tam.estado',1)
                    ->where('tam.cod_alumno',$cod_alumno)
                    ->where('tam.id_especialidad',$id_especialidad)
                    ->where('tam.id_periodo >= '.$id_periodo)
                    ->get('talumno_matricula tam');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosNoRegulares($id_alumno_matricula){
        $q = $this->db->select('tnnr.*, tc.nombre as curso, tc.codigo, tc.creditos, ttnnr.nombre as tipo_nota, tmd.nombre as modulo')
                    ->join('talumno_matricula_curso tamc','tamc.id = tnnr.id_alumno_matricula_curso')
                    ->join('tcurso tc','tc.id = tamc.id_curso')
                    ->join('ttipo_nota_no_regular ttnnr','ttnnr.id = tnnr.id_tipo_nota_no_regular')
                    ->join('tmodulo tmd','tmd.id = tamc.id_modulo')
                    ->where('tamc.id_alumno_matricula',$id_alumno_matricula)
                    ->get('tnota_no_regular tnnr');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getNotasCursos($id_alumno_matricula){
        $q = $this->db->select('tamc.*, tc.nombre as curso, tc.codigo, tc.creditos, tm.orden, tamc.id_modulo, tmd.nombre as modulo, ttec.eval_minima')
                    ->join('tmodulo tmd','tmd.id = tamc.id_modulo')
                    //->join('talu tnca','tnca.id_alumno_matricula = tamc.id_alumno_matricula')
                    ->join('tcurso tc','tc.id = tamc.id_curso')
                    ->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tamc.id_curso')
                    //->where('tam.estado',1)
                    ->where('tamc.id_alumno_matricula',$id_alumno_matricula)
                    //->group_by('tnca.id_curso')
                    ->order_by('tm.orden')
                    //->where('tnca.id_curso',$id_curso)
                    ->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosAuxiliares($cod_alumno){
        $q = $this->db->select('tnnra.*, tc.nombre as curso, tc.creditos, tcl.nombre as ciclo, tc.codigo, ttnnr.nombre as tipo_nota, tp.nombre as periodo')
                    ->join('tcurso tc','tc.id = tnnra.id_curso')
                    ->join('tperiodo tp','tp.id = tnnra.id_periodo')
                    ->join('tciclo tcl','tcl.id = tnnra.id_ciclo')
                    ->join('ttipo_nota_no_regular ttnnr','ttnnr.id = tnnra.id_tipo_nota_no_regular')
                    ->where('tnnra.cod_alumno',$cod_alumno)
                    ->get('tnota_no_regular_aux tnnra');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosNota($id_alumno_matricula){
        $q = $this->db->select('tnca.*, tc.nombre as curso, tc.codigo, tc.creditos, tm.orden, tamc.id_modulo')
                    //->join('tmodulo tmd','tmd.id = tamc.id_modulo')
                    ->join('tnota_curso_alumno tnca','tnca.id_alumno_matricula = tamc.id_alumno_matricula')
                    ->join('tcurso tc','tc.id = tnca.id_curso')
                    ->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    //->where('tam.estado',1)
                    ->where('tnca.id_alumno_matricula',$id_alumno_matricula)
                    ->group_by('tnca.id_curso')
                    ->order_by('tm.orden')
                    //->where('tnca.id_curso',$id_curso)
                    ->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
        /*$q = $this->db->select('tnca.*, tc.nombre as curso, tc.codigo')
                    ->join('tcurso tc','tc.id = tnca.id_curso')
                    //->where('tam.estado',1)
                    ->where('tnca.id_alumno_matricula',$id_alumno_matricula)
                    ->where('tnca.id_curso',$id_curso)
                    ->get('tnota_curso_alumno tnca');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;*/
    }

    public function getAlumnosMatriculaCurso($where){
        $this->db->select('tamc.*, tam.id_ciclo, tam.id_turno, tam.estado_semestre, tp.nombre, tp.apell_pat, tp.apell_mat, tp.dni, tpr.nombre as periodo, ta.codigo, tc.nombre as ciclo, tcr.nombre as curso, tt.nombre as turno, tes.nombre as especialidad, tgr.codigo as codigo_genero, tp.fch_nac');
        $this->db->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula');
        $this->db->join('tcurso tcr','tcr.id = tamc.id_curso');
        //nuevos join
        $this->db->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo');
        $this->db->join('tmalla tm','tm.id = tmp.id_malla');
        ///
        $this->db->join('talumno ta','ta.codigo = tam.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->join('tperiodo tpr','tpr.id = tam.id_periodo');
        $this->db->join('tciclo tc','tc.id = tam.id_ciclo');
        $this->db->join('tturno tt','tt.id = tam.id_turno');
        $this->db->join('tespecialidad tes','tes.id = tam.id_especialidad');
        $this->db->join('tgenero tgr','tgr.id = tp.id_genero');
        $this->db->group_by('tam.cod_alumno');
        $this->db->where('tam.id_especialidad',$where['id_especialidad']);
        if($where['id_periodo'])
            $this->db->where('tam.id_periodo',$where['id_periodo']);
        if($where['id_turno'])
            $this->db->where('tam.id_turno',$where['id_turno']);
        if($where['id_ciclo'])
            $this->db->where('tam.id_ciclo',$where['id_ciclo']);
        $this->db->order_by('tp.apell_pat, tp.apell_mat, tp.nombre, tm.orden');
                    //->get(' tamc');
        $res = $this->db->get('talumno_matricula_curso tamc')->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getAlumnoMatricula(){
        $q = $this->db->select()
                    //->join('tperiodo tp','tp.id = tam.id_periodo')
                    //->where('tam.estado',1)
                    ->where('tam.cod_alumno',$cod_alumno)
                    ->where('tam.id_periodo <= '.$id_periodo)
                    ->get('talumno_matricula tam');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function actualizaEstadoSemestre($datos){
        $q = $this->db->query("CALL proc_revisa_estado_semestre(".$datos['id_nota'].");");
        $res = $q->result();
        mysqli_next_result( $this->db->conn_id );
//        $q->next_result(); 
//        $q->free_result(); 
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getAlumnosCurso($id_malla_periodo,$id_curso){
        $q = $this->db->select('tp.nombre, tp.apell_pat, tp.apell_mat, tp.dni, tnca.valor_nota, tam.id as id_alumno_matricula')
                    ->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('talumno ta','ta.codigo = tam.cod_alumno')
                    ->join('tusuario tu','tu.id = ta.id_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->join('tnota_curso_alumno tnca','tnca.id_alumno_matricula = tamc.id_alumno_matricula and tnca.id_curso = tamc.id_curso and tnca.cod_alumno = tam.cod_alumno')
                    //->where('tam.estado',1)
                    ->where('tnca.id_recuperacion',0)
                    ->where('tamc.id_curso',$id_curso)
                    ->where('tamc.id_malla_periodo',$id_malla_periodo)
                    ->order_by('tp.apell_pat, tp.apell_mat, tp.nombre')
                    ->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getGrupoForEespecialidad($id_especialidad){
        $q = $this->db->select('tg. id, tg.nombre as grupo')
                    ->join('tespecialidad_periodo tep','tep.id = tg.id_especialidad_periodo')
                    ->where('tep.id_especialidad',$id_especialidad)
                    ->where('tep.estado',1)
                    ->get('tgrupo tg');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getJustGrupo($id){
        $q = $this->db->select('tg. id, tg.nombre as grupo, tg.id_especialidad_periodo')
                    ->where('tg.id',$id)
                    ->get('tgrupo tg');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getJustGrupoInformacion($id){
        $q = $this->db->select('tg. id, tg.nombre as grupo, tg.id_especialidad_periodo, tt.nombre as turno')
                    ->join('tturno tt','tt.id = tg.id_turno')
                    ->where('tg.id',$id)
                    ->get('tgrupo tg');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getGrupoAlumnos($id_grupo){
        $q = $this->db->select('ta.codigo, tp.nombre, tp.apell_pat, tp.apell_mat, tp.dni, tc.nombre as ciclo, tper.nombre as periodo, tam.id_periodo, tam.id as id_alumno_matricula')
                    ->join('talumno ta','ta.codigo = tae.cod_alumno')
                    ->join('tusuario tu','tu.id = ta.id_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->join('tciclo tc','tc.id = tae.id_ciclo')
                    ->join('talumno_matricula tam','tae.id_grupo = tam.id_grupo and tae.cod_alumno = tam.cod_alumno')
                    ->join('tperiodo tper','tper.id = tam.id_periodo')
                    //->where('tep.id_especialidad',$id_especialidad)
                    ->where('tae.id_grupo',$id_grupo)
                    ->get('talumno_especialidad tae');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getNotasCursoGrupo($cod_alumno,$id_alumno_matricula,$id_curso){
        $q = $this->db->select('tnca.valor_nota')
                    ->where('tnca.cod_alumno',$cod_alumno)
                    ->where('tnca.id_alumno_matricula',$id_alumno_matricula)
                    ->where('tnca.id_curso',$id_curso)
                    ->get('tnota_curso_alumno tnca');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getNotaCursoAlumno($id){
        $q = $this->db->select('tnca.*, ttec.tipo_eval, ttec.eval_minima')
                    ->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tnca.id_curso')
                    ->where('tnca.id',$id)
                    ->get('tnota_curso_alumno tnca');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getNotasCursoAlumnoMatricula($id_alumno_matricula,$id_curso,$id_ciclo){
        $q = $this->db->select('tamc.valor_nota, tm.orden, ttec.tipo_eval, ttec.eval_minima')
                    ->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tamc.id_curso')
                    ->where('tamc.id_ciclo',$id_ciclo)
                    ->where('tamc.id_alumno_matricula',$id_alumno_matricula)
                    ->where('tamc.id_curso',$id_curso)
                    ->order_by('tm.orden')
                    //->where('tamc.id_malla_periodo',$id_malla_periodo)
                    ->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getCursosMatriculaAlumno($id_periodo,$id_ciclo,$id_especialidad,$id_turno){
        $q = $this->db->select('tamc.*,tm.orden,tc.nombre as curso, tc.creditos')
                    ->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('tcurso tc','tc.id = tamc.id_curso')
                    ->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->where('tam.id_periodo',$id_periodo)
                    ->where('tam.id_ciclo',$id_ciclo)
                    ->where('tam.id_especialidad',$id_especialidad)
                    ->where('tam.id_turno',$id_turno)
                    ->group_by('tamc.id_curso')
                    ->order_by('tm.orden','asc')
                    //->where('tam.id_malla_periodo',$id_malla_periodo)
                    ->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosMatriculaAlumno_($cod_alumno,$id_periodo,$id_ciclo,$id_especialidad,$id_turno,$asc = 1){
        $this->db->select('tamc.*,tm.orden, tamc.id as id_alumno_matricula_curso,tc.nombre as curso, tc.creditos, tmd.tipo, tmd.nombre as modulo, tmd.id_padre, tmd.orden as orden_modulo, tp.clases_fin as fecha')
                    ->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('tcurso tc','tc.id = tamc.id_curso')
                    ->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('tmodulo tmd','tmd.id = tamc.id_modulo');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->where('tam.id_periodo',$id_periodo);
        $this->db->where('tam.id_ciclo',$id_ciclo);
        $this->db->where('tam.id_especialidad',$id_especialidad);
        if($asc)
            $this->db->where('tam.id_turno',$id_turno);
        $this->db->where('tam.cod_alumno',$cod_alumno);
        $this->db->group_by('tamc.id_curso');
        $this->db->order_by('tam.id','desc');
        $this->db->order_by('tm.orden','asc');
                    //->where('tam.id_malla_periodo',$id_malla_periodo)
        $q = $this->db->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosMatriculaAlumnoCertificado($cod_alumno,$id_ciclo,$id_especialidad,$id_turno,$asc = 1){
        $this->db->select('tamc.*,tm.orden, tamc.id as id_alumno_matricula_curso,tc.nombre as curso, tc.creditos, tmd.tipo, tmd.nombre as modulo, tmd.id_padre, tmd.orden as orden_modulo, tp.clases_fin as fecha, tp.nombre as periodo')
                    ->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('tcurso tc','tc.id = tamc.id_curso')
                    ->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('tmodulo tmd','tmd.id = tamc.id_modulo');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->join('tcurso_especialidad tce','tce.id_curso = tamc.id_curso');
        $this->db->where('tam.id_ciclo',$id_ciclo);
        $this->db->where('tam.id_especialidad',$id_especialidad);
        if($asc)
            $this->db->where('tam.id_turno',$id_turno);
        $this->db->where('tam.cod_alumno',$cod_alumno);
        $this->db->where('tce.id_tipo_curso != 4');
        $this->db->group_by('tamc.id_curso');
        $this->db->order_by('tam.id','desc');
        $this->db->order_by('tm.orden','asc');
                    //->where('tam.id_malla_periodo',$id_malla_periodo)
        $q = $this->db->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getAlumnoMatriculaCertificado($cod_alumno,$id_ciclo,$id_especialidad,$id_periodo){
        $this->db->select();
        $this->db->where('tam.id_ciclo',$id_ciclo);
        $this->db->where('tam.id_especialidad',$id_especialidad);
        $this->db->where('tam.cod_alumno',$cod_alumno);
        $this->db->where('tam.id_periodo >= '.$id_periodo);
        $this->db->order_by('tam.id','asc');
        $q = $this->db->get('talumno_matricula tam');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosBusquedaCertificado($id_alumno_matricula,$id_curso){
        $this->db->select();
        $this->db->where('tamc.id_alumno_matricula',$id_alumno_matricula);
        $this->db->where('tamc.id_curso',$id_curso);
        /*$this->db->where('tamc.cod_alumno',$cod_alumno);
        $this->db->where('tamc.id_periodo >= '.$id_periodo);
        $this->db->order_by('tamc.id','asc');*/
        $q = $this->db->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosMatriculaAlumnoCertificado_($id_alumno_matricula){
        $this->db->select('tamc.*,tm.orden, tamc.id as id_alumno_matricula_curso,tc.nombre as curso, tc.creditos, tmd.tipo, tmd.nombre as modulo, tmd.orden as orden_modulo, tp.clases_fin as fecha, tp.nombre as periodo')
                    ->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('tcurso tc','tc.id = tamc.id_curso')
                    ->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('tmodulo tmd','tmd.id = tamc.id_modulo');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->join('tcurso_especialidad tce','tce.id_curso = tamc.id_curso');
        /*$this->db->where('tam.id_ciclo',$id_ciclo);
        $this->db->where('tam.id_especialidad',$id_especialidad);
        $this->db->where('tam.cod_alumno',$cod_alumno);*/
        $this->db->where('tam.id',$id_alumno_matricula);
        $this->db->where('tce.id_tipo_curso != 4');
        //$this->db->group_by('tamc.id_curso');
        $this->db->order_by('tam.id','desc');
        $this->db->order_by('tm.orden','asc');
                    //->where('tam.id_malla_periodo',$id_malla_periodo)
        $q = $this->db->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosMatriculaAlumnoCertificado_Actividades($id_alumno_matricula){
        $this->db->select('tamc.*,tm.orden, tamc.id as id_alumno_matricula_curso,tc.nombre as curso, tc.creditos, tmd.tipo, tmd.nombre as modulo, tmd.orden as orden_modulo, tp.clases_fin as fecha, tp.nombre as periodo')
                    ->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('tcurso tc','tc.id = tamc.id_curso')
                    ->join('tmalla_periodo tmp','tmp.id = tamc.id_malla_periodo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('tmodulo tmd','tmd.id = tamc.id_modulo');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->join('tcurso_especialidad tce','tce.id_curso = tamc.id_curso');
        /*$this->db->where('tam.id_ciclo',$id_ciclo);
        $this->db->where('tam.id_especialidad',$id_especialidad);
        $this->db->where('tam.cod_alumno',$cod_alumno);*/
        $this->db->where('tam.id',$id_alumno_matricula);
        $this->db->where('tce.id_tipo_curso',4);
        //$this->db->group_by('tamc.id_curso');
        $this->db->order_by('tam.id','desc');
        $this->db->order_by('tm.orden','asc');
                    //->where('tam.id_malla_periodo',$id_malla_periodo)
        $q = $this->db->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getUltimaMatricula($cod_alumno){
        $q = $this->db->select()
                    //->where('tnca.id_alumno_matricula',$id_alumno_matricula)
                    ->where('tam.cod_alumno',$cod_alumno)
                    ->order_by('tam.id','desc')
                    ->get('talumno_matricula tam');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getAlumnoInf_($cod_alumno){
        $q = $this->db->select('ta.*, tae.id as id_alumno_especialidad, tep.id as id_especialidad_periodo, tat.nombre as tipo_alumno, tae.estado as estado_especialidad, tc.nombre as ciclo, te.nombre as especialidad, te.id as id_especialidad, te.codigo as codigo_especialidad, tt.nombre as turno')
                    ->join('talumnotipo tat','ta.id_alumno_tipo = tat.id')
                    ->join('talumno_especialidad tae','ta.codigo = tae.cod_alumno')
                    ->join('tturno tt','tt.id = tae.id_turno')
                    ->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    //->join('tgrupo tg','tg.id = tae.id_grupo')
                    ->join('tciclo tc','tc.id = tae.id_ciclo')
                    //->where('tnca.id_alumno_matricula',$id_alumno_matricula)
                    ->where('ta.codigo',$cod_alumno)
                    ->where('tae.activo',1)
                    //->where('tae.activo',1)
//                    ->order_by('tam.id','desc')
                    ->get('talumno ta');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getAlumnoInf($cod_alumno,$id_alumno_especialidad){
        $q = $this->db->select('ta.*, tae.id as id_alumno_especialidad, tep.id as id_especialidad_periodo, tat.nombre as tipo_alumno, tae.estado as estado_especialidad, tc.nombre as ciclo, te.nombre as especialidad, te.id as id_especialidad, te.codigo as codigo_especialidad, tt.nombre as turno')
                    ->join('talumnotipo tat','ta.id_alumno_tipo = tat.id')
                    ->join('talumno_especialidad tae','ta.codigo = tae.cod_alumno')
                    ->join('tturno tt','tt.id = tae.id_turno')
                    ->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    //->join('tgrupo tg','tg.id = tae.id_grupo')
                    ->join('tciclo tc','tc.id = tae.id_ciclo')
                    //->where('tnca.id_alumno_matricula',$id_alumno_matricula)
                    ->where('ta.codigo',$cod_alumno)
                    ->where('tae.activo',1)
                    //->where('tae.activo',1)
                    ->where('tae.id',$id_alumno_especialidad)
//                    ->order_by('tam.id','desc')
                    ->get('talumno ta');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getAlumnoHisto($cod_alumno){
        $q = $this->db->select('tae.id as id_alumno_especialidad, tpr.nombre, tpr.apell_pat, tpr.apell_mat, tae.cod_alumno, te.nombre as especialidad, tp.nombre as periodo, tt.nombre as turno, tae.cursos, tae.practicas, tae.estado_egreso')
                    //->join('talumnotipo tat','ta.id_alumno_tipo = tat.id')
                    ->join('talumno ta','ta.codigo = tae.cod_alumno')
                    ->join('tusuario tu','tu.id = ta.id_usuario')
                    ->join('tpersona tpr','tpr.id = tu.id_persona')
                    ->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->join('tturno tt','tt.id = tae.id_turno')
                    //->where('tnca.id_alumno_matricula',$id_alumno_matricula)
                    ->where('ta.codigo',$cod_alumno)
                    //->where('tae.activo',1)
//                    ->order_by('tam.id','desc')
                    ->get('talumno_especialidad tae');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getAlumnoMatricula_($id){
        $this->db->select();
        $this->db->where('id',$id);
        //$this->db->;
        $res = $this->db->get('talumno_matricula')->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getPeriodosCursosNoEncontrados($id_curso){
        $this->db->select('tam.id_periodo, tp.nombre');
        $this->db->join('talumno_matricula tam','tamc.id_alumno_matricula = tam.id');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->where('tamc.id_curso',$id_curso);
        $this->db->group_by('tam.id_periodo');
        $res = $this->db->get('talumno_matricula_curso tamc')->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getTurnosPeriodosCursosNoEncontrados($id_curso,$id_periodo){
        $this->db->select('tam.id_turno, tt.nombre');
        $this->db->join('talumno_matricula tam','tamc.id_alumno_matricula = tam.id');
        $this->db->join('tturno tt','tt.id = tam.id_turno');
        $this->db->where('tamc.id_curso',$id_curso);
        $this->db->where('tam.id_periodo',$id_periodo);
        $this->db->group_by('tam.id_turno');
        $res = $this->db->get('talumno_matricula_curso tamc')->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getTipoIngreso(){
        $this->db->select();
        //$this->
        $res = $this->db->get('ttipo_ingreso')->result();
        if($res)
            return $res;
        else
            return 0;
    }

}