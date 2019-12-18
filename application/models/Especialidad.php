<?php
class Especialidad extends CI_Model {

	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function getProfesorJefe($id_profesor,$id_especialidad){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('id_profesor',$id_profesor)
                    ->where('id_especialidad',$id_especialidad)
                    ->where('estado',1)
                    ->get('tjefe_area');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function updateProfesorJefe($where,$data){
        $this->db->where($where)->update('tjefe_area',$data);
    }

    public function deleteProfesorJefe($id){
        $this->db->where('id',$id)->delete('tjefe_area');
    }

    public function consultProfesorJefe($id_profesor){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('id_profesor',$id_profesor)
                    ->where('estado',1)
                    ->get('tjefe_area');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function setProfesorJefe($data){
        $this->db->insert('tjefe_area',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function getEspecialidades(){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('estado',1)
                    ->get('tespecialidad');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getEspecialidadPorId($id){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('id',$id)
                    ->get('tespecialidad');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getPeriodosEspecialidad($id_especialidad,$count =1, $tipo = 1){
        $q = $this->db->select('tep.*, tp.nombre, tep.id_turno, tt.nombre as turno')
                    ->join('tespecialidad_periodo tep','tep.id_periodo_actual = tp.id')
                    ->join('tturno tt','tt.id = tep.id_turno')
                    ->order_by('tp.fch_inicio','DESC')
                    ->where('tep.id_especialidad',$id_especialidad)
                    //->where('tp.tipo',$tipo)
                    //->where('tp.estado',1)
                    ->get('tperiodo tp');
        $res = $q->result();
        if($res){
            if($count == 1)
                return $res[0];
            else
                return $res;
        }
        else
            return 0;
    }


    public function getTurnosEspecialidadPeriodos($id_especialidad, $id_periodo){
        $q = $this->db->select('tep.*, tt.nombre as turno')
                    ->join('tturno tt','tt.id = tep.id_turno')
                    ->where('tep.id_especialidad',$id_especialidad)
                    ->where('tep.id_periodo_actual',$id_periodo)
                    ->group_by('tt.id')
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res){
            return $res;
        }
        else
            return 0;
    }

    public function getEspecialidadPeriodoForWhere($id_especialidad,$id_periodo,$id_turno){
        $q = $this->db->select()
                    ->where('tep.id_especialidad',$id_especialidad)
                    ->where('tep.id_periodo',$id_periodo)
                    ->where('tep.id_turno',$id_turno)
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res){
            return $res[0];
        }
        else
            return 0;
    }

    public function getEspecialidadesPeriod(){
        $q = $this->db->select('te.id, tp.nombre as periodo, te.nombre as especialidad, tep.id_turno, tt.nombre as turno')
                    ->join('tespecialidad_periodo tep','tep.id_periodo_actual = tp.id')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    ->join('tturno tt','tt.id = tep.id_turno')
                    ->order_by('te.nombre','ASC')
                    ->group_by('te.id')
                    //->where('tp.tipo',$tipo)
                    ->where('tp.estado',1)
                    ->where('tep.estado',1)
                    ->get('tperiodo tp');
        $res = $q->result();
        if($res){
            return $res;
        }
        else
            return 0;
    }

    public function getAlumnoEspecialidad($cod_alumno){
        $q = $this->db->select('')
                    ->where('cod_alumno',$cod_alumno)
                    ->where('activo',1)
                    ->get('talumno_especialidad');
        $res = $q->result();
        if($res){
            return $res[0];
        }
        else
            return 0;
    }

    public function getAlumnoEspecialidad_($cod_alumno){
        $q = $this->db->select('tae.*, tep.id_especialidad')
                    ->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo')
                    ->where('tae.cod_alumno',$cod_alumno)
                    ->where('tae.activo',1)
                    ->get('talumno_especialidad tae');
        $res = $q->result();
        if($res){
            return $res[0];
        }
        else
            return 0;
    }

    public function getAlumnoEspecialidadForId($id){
        $q = $this->db->select('')
                    ->where('id',$id)
                    ->where('activo',1)
                    ->get('talumno_especialidad');
        $res = $q->result();
        if($res){
            return $res[0];
        }
        else
            return 0;
    }

    public function getAlumnosEspecialidad(){
        $q = $this->db->select('')
                    ->where('cursos',0)
                    ->get('talumno_especialidad');
        $res = $q->result();
        if($res){
            return $res;
        }
        else
            return 0;
    }

    public function getAlumnosEspecialidadEgreso(){
        $q = $this->db->select('')
                    ->where('estado_egreso',0)
                    ->get('talumno_especialidad');
        $res = $q->result();
        if($res){
            return $res;
        }
        else
            return 0;
    }

    public function getAlumnosEspecialidadCursos(){
        $q = $this->db->select('')
                    ->where('cursos',0)
                    ->get('talumno_especialidad');
        $res = $q->result();
        if($res){
            return $res;
        }
        else
            return 0;
    }

    public function getAlumnosEspecialidadPracticas(){
        $q = $this->db->select('')
                    ->where('practicas',0)
                    ->get('talumno_especialidad');
        $res = $q->result();
        if($res){
            return $res;
        }
        else
            return 0;
    }

    public function getAlumnosEspecialidadActividades(){
        $q = $this->db->select('')
                    ->where('id_periodo_ingreso <= 23')
                    ->where('actividades',0)
                    ->where('lleva_cursos_actividades',1)
                    ->get('talumno_especialidad');
        $res = $q->result();
        if($res){
            return $res;
        }
        else
            return 0;
    }

    public function getAlumnosEspecialidadWhere($where){
        $q = $this->db->select('')
                    ->where($where)
                    ->get('talumno_especialidad');
        return $q->num_rows();
        $res = $q->result();
        if($res){
            return $res;
        }
        else
            return 0;
    }

    public function cantAlumnosPracticasCulminadas(){
        $q = $this->db->select('count(id) as cantidad')
                    ->where('tae.practicas',1)
                    ->get('talumno_especialidad tae');
        $res = $q->result();
        if($res){
            return $res[0];
        }
        else
            return 0;
    }

    public function cantAlumnosCursosCulminados(){
        $q = $this->db->select('count(id) as cantidad')
                    ->where('tae.cursos',1)
                    ->get('talumno_especialidad tae');
        $res = $q->result();
        if($res){
            return $res[0];
        }
        else
            return 0;
    }

    public function cantAlumnosCursosCulminadosActividades(){
        $q = $this->db->select('count(id) as cantidad')
                    ->where('tae.actividades',1)
                    ->get('talumno_especialidad tae');
        $res = $q->result();
        if($res){
            return $res[0];
        }
        else
            return 0;
    }

    public function updateAlumnoEspecialidadCurso($id,$data){
        $this->db->where('id',$id)->update('talumno_especialidad',$data);
    }

    public function getEspecialidadesPeriodo($id_periodo){
        $q = $this->db->select('tep.*, te.id as id_especialidad, te.nombre, te.codigo, tp.nombre as periodo')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    //->order_by('tp.fch_inicio','DESC')
                    //->where('tep.id_periodo',$id_periodo)
                    ->group_by('tep.id_especialidad')
                    ->where('tep.estado',1)
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res){
                return $res;
        }
        else
            return 0;
    }

    public function getEspecialidadesForPeriodo($id_periodo){
        $q = $this->db->select('tep.*, te.id as id_especialidad, te.nombre, te.codigo, tp.nombre as periodo')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    //->order_by('tp.fch_inicio','DESC')
                    ->where('tep.id_periodo_actual',$id_periodo)
                    ->group_by('tep.id_especialidad')
                    ->where('tep.estado',1)
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res){
                return $res;
        }
        else
            return 0;
    }
    
    public function getEspecialidadesPeriodo_($id_especialidad,$id_especialidad_periodo){
        $q = $this->db->select('tep.*, te.id as id_especialidad, te.nombre, te.codigo, tp.nombre as periodo, tt.nombre as turno')
                    ->join('tespecialidad te','te.id = tep.id_especialidad')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->join('tturno tt','tt.id = tep.id_turno')
                    //->order_by('tp.fch_inicio','DESC')
                    ->where('tep.id_especialidad',$id_especialidad)
                    ->where('tep.id != '.$id_especialidad_periodo)
                    //->where('tep.estado',1)
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res){
                return $res;
        }
        else
            return 0;
    }

    public function getEspecialidadesPeriodoForId($id_especialidad_periodo){
        $q = $this->db->select('tep.*,tp.nombre as periodo')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    //->order_by('tp.fch_inicio','DESC')
                    //->where('tep.id_especialidad',$id_especialidad)
                    ->where('tep.id',$id_especialidad_periodo)
                    //->where('tep.estado',1)
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res){
                return $res[0];
        }
        else
            return 0;
    }
    
    public function getEspecialidadesPeriodoForNombre($nombre){
        $q = $this->db->select('tep.*, te.id as id_especialidad, te.nombre, te.codigo, tp.nombre as periodo, tt.nombre as turno')
                    ->join('tespecialidad te','te.id = tep.id_especialidad','inner')
                    ->join('tperiodo tp','tp.id = tep.id_periodo','inner')
                    ->join('tturno tt','tt.id = tep.id_turno')
                    //->order_by('tp.fch_inicio','DESC')
                    ->like('tp.nombre',$nombre,'after')
                    //->where('tep.estado',1)
                    ->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res){
                return $res;
        }
        else
            return 0;
    }

    public function getCiclos(){
        $q = $this->db->select()
                    //->order_by('fch_inicio','desc')
                    ->get('tciclo');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCiclo($id){
        $q = $this->db->select()
                    //->order_by('fch_inicio','desc')
                    ->where('id',$id)
                    ->get('tciclo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function searchEspecialidadPeriodo($where){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    //->where('estado',1)
                    ->where($where)
                    //->order_by('fch_inicio','desc')
                    ->get('tespecialidad_periodo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function searchGrupoEspecialidad($id_especialidad_periodo){
        $q = $this->db->select()
                    ->where('id_especialidad_periodo',$id_especialidad_periodo)
                    ->get('tgrupo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getPeriodosCerrados(){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('estado',2)
                    ->order_by('fch_inicio','desc')
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getPeriodos(){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    //->where('estado',1)
                    ->order_by('fch_inicio','desc')
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getPeriodos_(){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('tipo',1)
                    ->order_by('fch_inicio','desc')
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getPeriodosLike(){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('estado',1)
                    ->like('nombre','-01','both')
                    ->order_by('fch_inicio','desc')
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getPeriodo($id_periodo){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('id',$id_periodo)
                    //->where('estado',1)
                    ->get('tperiodo');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function searchEspecialidad($nombre = ''){
        $q = $this->db->select('te.*')
                    //->join('tidentificacion ti','ti.id_empresa = tp.id')
                    //->where('tp.id != 1')
                    ->where('te.estado',1)
                    ->where("te.nombre like '%".$nombre."%'")
                    ->get('tespecialidad te');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosAutocomplete($nombre = ''){
        $q = $this->db->select('tc.*')
                    ->where('tc.estado',1)
                    ->where("tc.nombre like '%".$nombre."%'")
                    ->get('tcurso tc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursoModulo($id_curso = 0){
        $q = $this->db->select()
                    ->where('tce.estado',1)
                    ->join('tmodulo tm','tm.id = tce.id_modulo','left')
                    ->where('tce.id_curso',$id_curso)
                    //->where("tc.nombre like '%".$nombre."%'")
                    ->get('tcurso_especialidad tce');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getEspecialidad($id_especialidad){
        $q = $this->db->select('te.*')
                    //->join('tidentificacion ti','ti.id_empresa = tp.id')
                    ->where('te.id',$id_especialidad)
                    ->where('te.estado',1)
                    //->where("te.nombre like '%".$nombre."%'")
                    ->get('tespecialidad te');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getModulos(){
        $q = $this->db->select('tem.*, te.nombre as especialidad, te.codigo, tm.nombre as modulo, tm.cant_cursos')
                    ->join('tmodulo tm','tem.id_modulo = tm.id')
                    ->join('tespecialidad te','tem.id_especialidad = te.id')
                    //->where('tp.id != 1')
                    ->where('tem.estado',1)
                    ->get('tespecialidad_modulo tem');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getModulosOnly($id_especialidad){
        $this->db->select('tem.*, tm.nombre as modulo, te.nombre as especialidad, tm.tipo, tm.orden');
        $this->db->join('tmodulo tm','tm.id = tem.id_modulo');
        $this->db->join('tespecialidad te','te.id = tem.id_especialidad');
        $this->db->where('tem.id_especialidad',$id_especialidad);
        $this->db->where('tm.id > 10');
        $res =  $this->db->get('tespecialidad_modulo tem')->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getModulosForEspecialidadAlumno($id_especialidad, $cod_alumno){
        $this->db->select('tce.id_modulo, tep.id_especialidad, tmd.nombre as modulo, tmd.orden');
        $this->db->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo');
        $this->db->join('tmalla_periodo tmp','tmp.id_especialidad_periodo = tae.id_especialidad_periodo');
        $this->db->join('tmalla tm','tm.id = tmp.id_malla');
        $this->db->join('tcurso_especialidad tce','tce.id_curso = tm.id_curso and tce.id_especialidad = tm.id_especialidad');
        $this->db->join('tmodulo tmd','tmd.id = tce.id_modulo');
        $this->db->where('tae.cod_alumno',$cod_alumno);
        $this->db->where('tep.id_especialidad',$id_especialidad);
        $this->db->where('tae.activo',1);
        $this->db->where('tmd.orden >= 1');
        $this->db->group_by('tmd.orden');
        $this->db->order_by('tmd.orden','asc');
        $res =  $this->db->get('talumno_especialidad tae')->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getModulosForEspecialidad($id_especialidad){
        $q = $this->db->select('tem.*, te.nombre as especialidad, te.codigo, tm.nombre as modulo, tm.cant_cursos, tm.tipo')
                    ->join('tmodulo tm','tem.id_modulo = tm.id')
                    ->join('tespecialidad te','tem.id_especialidad = te.id')
                    //->where('tp.id != 1')
                    ->where('tem.estado',1)
                    ->where('tem.id_especialidad',$id_especialidad)
                    ->get('tespecialidad_modulo tem');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getOrders($id_especialidad){
        $q = $this->db->select('tm.id, tm.orden')
                    ->join('tespecialidad_modulo tem','tem.id_modulo = tm.id')
                    ->where('tem.id_especialidad',$id_especialidad)
                    ->where('tm.orden != 0')
                    ->order_by('tm.orden')
                    ->get('tmodulo tm');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getCantidadOrders($id_especialidad){
        $q = $this->db->select('count(tm.orden) as cantidad')
                    ->join('tespecialidad_modulo tem','tem.id_modulo = tm.id')
                    ->where('tem.id_especialidad',$id_especialidad)
        //            ->where('tm.orden != 0')
                    ->group_by('tm.orden')
                    ->get('tmodulo tm');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getEspecialidadPeriodo($id_periodo,$id_especialidad,$id_turno){
        $q = $this->db->select('tp.*')
                    //->join('tmodulo tm','tem.id_modulo = tm.id')
                    //->where('tp.estado',1)
                    ->where('tp.id_especialidad',$id_especialidad)
                    ->where('tp.id_periodo',$id_periodo)
                    ->where('tp.id_turno',$id_turno)
                    //->where('tem.id_especialidad',$id_especialidad)
                    ->get('tespecialidad_periodo tp');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getEspecialidadPeriodos($id_periodo,$id_especialidad,$id_turno = -1){
        $this->db->select();
        //$this->db->join();
        $this->db->where('tep.id_ciclo_actual != 6');
        $this->db->where('tep.id_periodo_actual != '.$id_periodo);
        $this->db->where('tep.id_especialidad',$id_especialidad);
        if($id_turno != -1)
            $this->db->where('tep.id_turno',$id_turno);
        $q = $this->db->get('tespecialidad_periodo tep');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosEspecialidadPeriodo($id_especialidad,$all = 0){
        $this->db->select('tc.*, tm.nombre as modulo, tm.id as id_modulo, tm.id_padre');
        $this->db->join('tcurso_especialidad tce','tc.id = tce.id_curso');
        $this->db->join('tmodulo tm','tce.id_modulo = tm.id');
        $this->db->where('tc.estado',1);
        $this->db->where('tce.id_especialidad',$id_especialidad);
        /*if(!$all)
            $this->db->where('tce.id_modulo != 0');*/
        $this->db->where('tce.estado',1);
                    //->where('tem.id_especialidad',$id_especialidad)
        $q = $this->db->get('tcurso tc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getCursosEspecialidadTransversal(){
        $q = $this->db->select('tc.*, tm.nombre as modulo, tm.id as id_modulo, tm.id_padre')
                    ->join('tcurso_especialidad tce','tc.id = tce.id_curso')
                    ->join('tmodulo tm','tce.id_modulo = tm.id')
                    ->where('tc.estado',1)
                    //->where('tce.id_especialidad',$id_especialidad)
                    ->where('tce.id_modulo',0)
                    ->where('tce.estado',1)
                    //->where('tem.id_especialidad',$id_especialidad)
                    ->get('tcurso tc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getModulosEspecialidad($id_especialidad){
        $q = $this->db->select('tm.*')
                    ->join('tespecialidad_modulo tem','tem.id_modulo = tm.id')
                    //->where('tem.estado',1)
                    //->where('tem.id_especialidad',$id_especialidad)
                    ->get('tmodulo tm');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getTiposModulos($id_tipo,$id_especialidad){
        $q = $this->db->select('tmd.*')
                    ->join('tespecialidad_modulo tem','tem.id_modulo = tmd.id')
                    ->where('tmd.id_padre',1)
                    ->where('tem.id_especialidad',$id_especialidad)
                    ->order_by('tmd.id','desc')
                    ->get('tmodulo tmd');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getTiposModulos_($id_tipo,$id_especialidad){
        $q = $this->db->select('tmd.*')
                    ->join('tespecialidad_modulo tem','tem.id_modulo = tmd.id')
                    ->where('tmd.id_padre != 1')
                    ->where('tmd.id_padre > 0')
                    ->where('tem.id_especialidad',$id_especialidad)
                    ->order_by('tmd.id','desc')
                    ->get('tmodulo tmd');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getTiposModulos__($id_tipo,$id_especialidad){
        $q = $this->db->select('tmd.*')
                    ->join('tespecialidad_modulo tem','tem.id_modulo = tmd.id')
                    ->where('tmd.id > 1')
                    ->where('tmd.id_padre',0)
                    ->where('tem.id_especialidad',$id_especialidad)
                    ->order_by('tmd.id','desc')
                    ->get('tmodulo tmd');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursoNotaMinima($id_curso){
        $q = $this->db->select('ttec.*')
                    ->where('ttec.id_curso',$id_curso)
                    ->get('ttipo_evaluacion_curso ttec');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function newCursoEspecialidad($data){
        $this->db->insert('tcurso_especialidad',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function getCursoEspecialidad($id_especialidad,$id_curso,$id_modulo){
        $q = $this->db->select('tec.*')
                    ->where('tec.id_especialidad',$id_especialidad)
                    ->where('tec.id_curso',$id_curso)
                    ->where('tec.id_modulo',$id_modulo)
                    ->get('tcurso_especialidad tec');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function updateCursoEspecialidad($where,$data){
        $this->db->where($where)->update('tcurso_especialidad',$data);
    }

    public function getEspecialidadModulo($id){
        $q = $this->db->select('tem.*, tm.nombre as modulo, te.nombre as especialidad, tm.cant_cursos, tm.tipo')
                    ->join('tmodulo tm','tem.id_modulo = tm.id')
                    ->join('tespecialidad te','tem.id_especialidad = te.id')
                    ->where('tem.id',$id)
                    ->get('tespecialidad_modulo tem');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function newCurso($datos){
        $q = $this->db->query("CALL proc_nuevo_curso('".$datos['nombre']."',".$datos['id_especialidad'].",".$datos['creditos'].",".$datos['horas'].",'".$datos['descripcion']."',".$datos['electivo'].",'".$datos['temas']."','".$datos['subcodigo_curso']."');");
        $res = $q->result();
        mysqli_next_result( $this->db->conn_id );
        //$this->db->free_result();
        /*$q->next_result();*/
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function newTipoEvalCursoRegistro($data){
        $this->db->insert('ttipo_evaluacion_curso',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function newModuloRegistro($datos = array()){
        if(count($datos) == 0)
            return 0;
        $this->db->insert('tmodulo',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function updateModulooRegistro($data,$where){
        $this->db->where($where)->update('tmodulo',$data);
    }

    public function newEspecialidadModulo($datos = array()){
        if(count($datos) == 0)
            return 0;
        $this->db->insert('tespecialidad_modulo',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function updateEspecialidadModulo($data,$where){
        $this->db->where($where)->update('tespecialidad_modulo',$data);
    }

    public function newEspecialidad($datos = array()){
        if(count($datos) == 0)
            return 0;
        $this->db->insert('tespecialidad',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function newEspecialidadPeriodo($datos = array()){
        if(count($datos) == 0)
            return 0;
        $this->db->insert('tespecialidad_periodo',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function updateEspecialidadPeriodo($data,$where){
        $this->db->where($where)->update('tespecialidad_periodo',$data);
        return $this->db->affected_rows();
    }

    public function getMallaPeriodoCurso($id_especialidad_periodo,$id_curso){
        $q = $this->db->select('tpc.*')
                    ->join('tmalla tm','tm.id = tpc.id_malla')
                    ->where('tpc.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tm.id_curso',$id_curso)
                    //->where('tem.id_especialidad',$id_especialidad)
                    ->get('tmalla_periodo tpc');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getPeriodoCiclo($id_especialidad_periodo,$id_malla){
        $q = $this->db->select('tpc.*')
                    ->where('tpc.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tpc.id_malla',$id_malla)
                    //->where('tem.id_especialidad',$id_especialidad)
                    ->get('tmalla_periodo tpc');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getCursoCiclo($id_curso,$id_ciclo,$id_especialidad){
        $q = $this->db->select('tm.*')
                    ->where('tm.id_curso',$id_curso)
                    ->where('tm.id_ciclo',$id_ciclo)
                    ->where('tm.id_especialidad',$id_especialidad)
                    ->get('tmalla tm');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function newCursoCiclo($data){
        $this->db->insert('tmalla',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function newMallaPeriodo($data){
        $this->db->insert('tmalla_periodo',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function updateMallaPeriodo($where,$data){
        $this->db->where($where)->update('tmalla_periodo',$data);
    }

    public function quitaCursoMallaPeriodo($where){
        $this->db->where($where)->delete('tmalla_periodo');
    }

    public function quitaCursoMalla($where){
        $this->db->where($where)->delete('tmalla');
    }

    public function newPeriodo($datos = array()){
        if(count($datos) == 0)
            return 0;
        $this->db->insert('tperiodo',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function updatePeriodo($data,$where){
        $this->db->where($where)->update('tperiodo',$data);
    }

    public function getCursosEspecialidadMalla($id_especialidad_periodo,$id_ciclo){
        $q = $this->db->select('tm.*, tmp.id_malla')
                    ->join('tmall tm','tm.id = tmp.id_malla')
                    ///->order_by('tc.id_ciclo')
                    ->where('tmp.id_ciclo',$id_ciclo)
                    ->where('tmp.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tmp.estado',1)
                    ->get('tmalla_periodo tmp');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosEspecialidad($id_especialidad_periodo,$ciclo = 1){
        $q = $this->db->select('tc.*')
                    //->join('tmodulo tm','tem.id_modulo = tm.id')
                    ->where('tc.estado',1)
                    ->where('tc.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tc.id_ciclo',$ciclo)
                    ///->order_by('tc.id_ciclo')
                    //->where('tem.id_especialidad',$id_especialidad)
                    ->get('tcurso tc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCurso($codigo){
        $q = $this->db->select('tc.*')
                    //->join('tmodulo tm','tem.id_modulo = tm.id')
                    ->where('tc.estado',1)
                    //->where('tc.id_especialidad_periodo',$id_especialidad_periodo)
                    ->like('tc.codigo',$codigo)
                    ->get('tcurso tc');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getInfoCurso($id_curso){
        $q = $this->db->select('tce.*, te.nombre as especialidad, tm.nombre as modulo')
                    ->join('tespecialidad te','te.id = tce.id_especialidad')
                    ->join('tmodulo tm','tm.id = tce.id_modulo')
                    ->where('tce.id_curso',$id_curso)
                    //->like('tc.codigo',$codigo)
                    ->get('tcurso_especialidad tce');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getCursoForId($id){
        $q = $this->db->select('tc.*')
                    ->where('tc.estado',1)
                    ->like('tc.id',$id)
                    ->get('tcurso tc');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getSeccion($id_curso){
        $q = $this->db->select('tsc.*, tper.nombre, tper.apell_pat, tper.apell_mat, tt.nombre as turno')
                    //->join('tmodulo tm','tem.id_modulo = tm.id')
                    ->join('tprofesor tp','tp.id = tsc.id_profesor')
                    ->join('tusuario tu','tu.id = tp.id_usuario')
                    ->join('tpersona tper','tper.id = tu.id_persona')
                    ->join('tturno tt','tt.id = tsc.id_turno')
                    //->where('tc.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tsc.estado',1)
                    ->where('tsc.id_curso',$id_curso)
                    ->get('tseccion_curso tsc');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getProfesores(){
        $q = $this->db->select('tp.*, tf.id as id_profesor, tp.id as id_persona, tu.id as id_usuario, tu.usuario, tf.codigo')
                    ->join('tusuario tu','tu.id = tf.id_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    //->where('tf.estado',1)
                    //->where('tc.id_especialidad_periodo',$id_especialidad_periodo)
                    //->where('tc.codigo',$codigo)
                    ->get('tprofesor tf');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getProfesoresAll(){
        $q = $this->db->select('tp.*, tf.id as id_profesor, tp.id as id_persona, tu.id as id_usuario, tu.usuario, tf.codigo')
                    ->join('tusuario tu','tu.id = tf.id_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    //->where('tf.estado',1)
                    //->where('tc.id_especialidad_periodo',$id_especialidad_periodo)
                    //->where('tc.codigo',$codigo)
                    ->get('tprofesor tf');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function newProfesor($datos = array()){
        $q = $this->db->query("CALL proc_nuevo_profesor(".$datos['id_usuario'].",".$datos['fch_ingreso'].",".$datos['estado'].");");
        $res = $q->result();
        mysqli_next_result( $this->db->conn_id );
        //$this->db->free_result();
        /*$q->next_result();*/
        if($res)
            return $res[0];
        else
            return 0;
        /*if(count($datos) == 0)
            return 0;
        $this->db->insert('tprofesor',$datos);
        $id = $this->db->insert_id();
        return $id;*/
    }

    public function updateProfesor($data,$where){
        $this->db->where($where)->update('tprofesor',$data);
    }

    public function getProfesor($id_profesor){
        $q = $this->db->select('tp.*, tf.id as id_profesor, tp.id as id_persona, tu.id as id_usuario, tu.usuario, tf.codigo')
                    ->join('tusuario tu','tu.id = tf.id_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->where('tf.id',$id_profesor)
                    //->where('tc.id_especialidad_periodo',$id_especialidad_periodo)
                    //->where('tc.codigo',$codigo)
                    ->get('tprofesor tf');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getTmallaPeriodo($id){
        $q = $this->db->select('')
                    //->where('tf.id',$id_profesor)
                    //->where('tc.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tmp.id',$id)
                    ->get('tmalla_periodo tmp');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getTurno($id){
        $q = $this->db->select('')
                    ->where('tmp.id',$id)
                    ->get('tturno tmp');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getNotasCursoAlumnoMatricula($cod_alumno){
        $this->db->select('tam.*, tamc.id as id_alumno_matricula_curso, tc.nombre as curso, tamc.valor_nota, tc.codigo as cod_curso, tc.creditos, tp.nombre as periodo, tam.id_periodo, tt.nombre as turno, tcl.nombre as ciclo, ttec.tipo_eval, ttec.eval_minima');
        $this->db->from('talumno_matricula_curso tamc');
        $this->db->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula');
        $this->db->join('tcurso tc','tc.id = tamc.id_curso');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->join('tturno tt','tam.id_turno = tt.id');
        $this->db->join('tciclo tcl','tcl.id = tamc.id_ciclo');
        $this->db->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tamc.id_curso');
        //$this->db->join('tnota_no_regular tnnr','')
        $this->db->where('tam.cod_alumno',$cod_alumno);
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getPracticasMalla($id_especialidad_periodo){
        $q = $this->db->select('tmd.id as id_modulo, tmd.nombre as modulo')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('tcurso tc','tc.id = tm.id_curso')
                    ->join('tcurso_especialidad tce','tce.id_curso = tm.id_curso')
                    ->join('tmodulo tmd','tmd.id = tce.id_modulo')
                    ->where('tmp.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tmd.orden > 0')
                    //->where('tem.id_especialidad',$id_especialidad)
                    ->group_by('tmd.orden')
                    ->get('tmalla_periodo tmp');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getMallaPeriodo($id_especialidad_periodo){
        $q = $this->db->select('tpc.*, tc.nombre as curso, tc.horas, tc.id as id_curso, tc.codigo as codigo_curso, tp.nombre as periodo, tep.modular, tmd.nombre as modulo, tmd.id as id_modulo, tmd.orden, tc.creditos, tm.id as id_malla')
                    ->join('tmalla tm','tm.id = tpc.id_malla')
                    ->join('tcurso tc','tc.id = tm.id_curso')
                    ->join('tespecialidad_periodo tep','tep.id = tpc.id_especialidad_periodo')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->join('tcurso_especialidad tce','tce.id_curso = tm.id_curso')
                    ->join('tmodulo tmd','tmd.id = tce.id_modulo')
                    ->order_by('tpc.id_ciclo','asc')
                    ->order_by('tm.orden','asc')
                    //->where('tep.id_periodo != 7')
                    //->where('tep.id_periodo != 9')
                    ->where('tpc.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tce.id_tipo_curso != 4')
                    ->where('tpc.estado',1)
                    //->where('tem.id_especialidad',$id_especialidad)
                    ->get('tmalla_periodo tpc');
        $res = $q->result();
        /*echo '<pre>';
        print_r($this->db->last_query());
        echo '</pre>';
        exit();*/
        if($res)
            return $res;
        else
            return 0;
    }

    public function getMallaPeriodoActividades($id_especialidad_periodo){
        $q = $this->db->select('tpc.*, tc.nombre as curso, tc.horas, tc.id as id_curso, tc.codigo as codigo_curso, tp.nombre as periodo, tep.modular, tmd.nombre as modulo, tmd.id as id_modulo, tmd.orden, tc.creditos')
                    ->join('tmalla tm','tm.id = tpc.id_malla')
                    ->join('tcurso tc','tc.id = tm.id_curso')
                    ->join('tespecialidad_periodo tep','tep.id = tpc.id_especialidad_periodo')
                    ->join('tperiodo tp','tp.id = tep.id_periodo')
                    ->join('tcurso_especialidad tce','tce.id_curso = tm.id_curso')
                    ->join('tmodulo tmd','tmd.id = tce.id_modulo')
                    ->order_by('tpc.id_ciclo','asc')
                    ->order_by('tm.orden','asc')
                    ->where('tpc.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tce.id_tipo_curso',4)
                    ->where('tpc.estado',1)
                    //->where('tem.id_especialidad',$id_especialidad)
                    ->get('tmalla_periodo tpc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getCursosGrupo($id_periodo,$id_grupo){
        $q = $this->db->select('tc.nombre as curso, tc.id as id_curso, tc.creditos, tamc.id_alumno_matricula, tam.cod_alumno')
                    ->join('talumno_matricula tam','tam.id = tamc.id_alumno_matricula')
                    ->join('tcurso tc','tc.id = tamc.id_curso')
                    ->where('tam.id_periodo',$id_periodo)
                    ->where('tam.id_grupo',$id_grupo)
                    ->group_by('tamc.id_curso')
                    ->get('talumno_matricula_curso tamc');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCursosMallaEspecialidadPeriodo($id_especialidad_periodo,$id_ciclo){
        $q = $this->db->select('tmp.*, tm.id_curso, tm.orden, tc.nombre as curso, tc.creditos')
                    ->join('tmalla tm','tm.id = tmp.id_malla')
                    ->join('tcurso tc','tc.id = tm.id_curso')
                    //->join('ttipo_evaluacion_curso ttec','ttec.id_curso = tc.id')
                    ->where('tmp.id_especialidad_periodo',$id_especialidad_periodo)
                    ->where('tmp.id_ciclo',$id_ciclo)
                    ->group_by('tm.id_curso')
                    ->order_by('tm.orden')
                    ->get('tmalla_periodo tmp');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getModules($where){
        $q = $this->db->select()
                    ->where($where)
                    ->order_by('id_padre')
                    ->get('tmodulos');
        $res = $q->result_array();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getEgresados($desde, $hasta,$status = 1){
        $this->db->select('tae.*, tae.id as id_alumno_especialidad, tae.cod_alumno, tpr.apell_pat, tpr.apell_mat, tpr.nombre, te.nombre as especialidad, tp.nombre as periodo, tt.nombre as turno, tae.fch_egreso');
        $this->db->from('talumno_especialidad tae');
        $this->db->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo');
        $this->db->join('tespecialidad te','te.id = tep.id_especialidad');
        $this->db->join('talumno ta','ta.codigo = tae.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tpr','tpr.id = tu.id_persona');
        $this->db->join('tperiodo tp','tp.id = tep.id_periodo');
        $this->db->join('tturno tt','tt.id = tep.id_turno');
        $this->db->where("tae.fch_egreso BETWEEN '".$desde."' and '".$hasta."'");
            $this->db->where('tae.estado_egreso',1);
        $res = $this->db->get()->result();
        /*var_dump($this->db->last_query());
        exit();*/
        if($res)
            return $res;
        else
            return 0;
    }

    public function getEgresados_($id_periodo){
        $this->db->select('tae.*, tae.id as id_alumno_especialidad, tae.cod_alumno, tpr.apell_pat, tpr.apell_mat, tpr.nombre, te.nombre as especialidad, tp.nombre as periodo, tt.nombre as turno, tae.fch_egreso');
        $this->db->from('talumno_especialidad tae');
        $this->db->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo');
        $this->db->join('tespecialidad te','te.id = tep.id_especialidad');
        $this->db->join('talumno ta','ta.codigo = tae.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tpr','tpr.id = tu.id_persona');
        $this->db->join('tperiodo tp','tp.id = tep.id_periodo');
        $this->db->join('tturno tt','tt.id = tep.id_turno');
        $this->db->where('tep.id_periodo',$id_periodo);
        //$this->db->where("tae.fch_ingreso BETWEEN '".$desde."' and '".$hasta."'");
            //$this->db->where('tae.estado_egreso',0);
        $res = $this->db->get()->result();
        /*var_dump($this->db->last_query());
        exit();*/
        if($res)
            return $res;
        else
            return 0;
    }

    public function getPeriodosEspecialidadPeriodo(){
        $this->db->select('tep.id, tp.nombre as periodo, tp.id as id_periodo');
        $this->db->from('tespecialidad_periodo tep');
        $this->db->join('tperiodo tp','tp.id = tep.id_periodo');
        $this->db->group_by('tep.id_periodo');
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getEgresado($id_alumno_especialidad){
        $this->db->select('tae.*, tae.id as id_alumno_especialidad, tae.cod_alumno, tpr.apell_pat, tpr.apell_mat, tpr.nombre, te.nombre as especialidad, tp.nombre as periodo, tt.nombre as turno, tae.fch_egreso');
        $this->db->from('talumno_especialidad tae');
        $this->db->join('tespecialidad_periodo tep','tep.id = tae.id_especialidad_periodo');
        $this->db->join('tespecialidad te','te.id = tep.id_especialidad');
        $this->db->join('talumno ta','ta.codigo = tae.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tpr','tpr.id = tu.id_persona');
        $this->db->join('tperiodo tp','tp.id = tep.id_periodo');
        $this->db->join('tturno tt','tt.id = tep.id_turno');
        $this->db->where('tae.id',$id_alumno_especialidad);
        $res = $this->db->get()->result();
        /*var_dump($this->db->last_query());
        exit();*/
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getTurnosForEspecialidadSeccion($id_especialidad,$id_periodo){
        $this->db->select('tep.*, tsc.id_turno, tt.nombre as turno, tmp.id_ciclo');
        $this->db->from('tespecialidad_periodo tep');
        $this->db->join('tmalla_periodo tmp','tmp.id_especialidad_periodo = tep.id');
        $this->db->join('tmalla tm','tm.id = tmp.id_malla');
        $this->db->join('tseccion_curso tsc','tsc.id_malla_periodo = tmp.id');
        $this->db->join('tseccion ts','ts.id = tsc.id_seccion');
        $this->db->join('tturno tt','tt.id = tsc.id_turno');
        $this->db->where('tep.estado',1);
        $this->db->where('ts.id_especialidad',$id_especialidad);
        $this->db->where('tsc.id_periodo',$id_periodo);
        $this->db->group_by('tsc.id_turno');
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getCiclosForEspecialidadSeccion($id_especialidad,$id_turno,$id_periodo){
        $this->db->select('tep.*, tmp.id_ciclo, tcl.nombre ciclo');
        $this->db->from('tespecialidad_periodo tep');
        $this->db->join('tmalla_periodo tmp','tmp.id_especialidad_periodo = tep.id');
        $this->db->join('tmalla tm','tm.id = tmp.id_malla');
        $this->db->join('tseccion_curso tsc','tsc.id_malla_periodo = tmp.id');
        $this->db->join('tciclo tcl','tcl.id = tmp.id_ciclo');
        $this->db->where('tep.estado',1);
        $this->db->where('tep.id_especialidad',$id_especialidad);
        $this->db->where('tsc.id_periodo',$id_periodo);
        $this->db->where('tsc.id_turno',$id_turno);
        $this->db->group_by('tmp.id_ciclo');
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getModuloForId($id){
        $this->db->select();
        $this->db->from('tmodulo tmd');
        $this->db->where('tmd.id',$id);
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

}