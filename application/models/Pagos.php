<?php
class Pagos extends CI_Model {

	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

//    public function getCursosMalla($id_especialidad_periodo,$id_ciclo){
//        $q = $this->db->select('tmp.id as id_malla_periodo, tc.id as id_curso, tcl.id as id_ciclo, tcl.nombre as nombre_ciclo, tc.nombre as curso, tc.codigo as codigo_curso, tp.nombre as promocion')
//                    ->join('tmalla tm','tm.id = tmp.id_malla')
//                    ->join('tcurso tc','tc.id = tm.id_curso')
//                    ->join('tciclo tcl','tcl.id = tmp.id_ciclo')
//                    ->join('tespecialidad_periodo tep','tep.id = tmp.id_especialidad_periodo')
//                    ->join('tperiodo tp','tp.id = tep.id_periodo')
//                    ->where('tmp.estado',1)
//                    ->where('tmp.id_especialidad_periodo',$id_especialidad_periodo)
//                    ->where('tmp.id_ciclo',$id_ciclo)
//                    ->get('tmalla_periodo tmp');
//        $res = $q->result();
//        if($res)
//            return $res;
//        else
//            return 0;
//    }
    
    public function getPagos($where){
        $q = $this->db->select('tp.*, tt.nombre as concepto, ta.codigo, CONCAT(tpr.nombre," ",tpr.apell_pat," ",tpr.apell_mat) as alumno, tp.monto, tp.fch_pago')
                    ->join('ttupa tt','tt.id = tp.id_tupa')
                    //->join('talumno_matricula tam','tam.id = tp.id_alumno_matricula')
                    ->join('talumno ta','ta.codigo = tp.cod_alumno')
                    ->join('tusuario tu','tu.id = ta.id_usuario')
                    ->join('tpersona tpr','tpr.id = tu.id_persona')
                    //->order_by('tt.id_categoria_tupa')
                    ->where($where)
                    ->get('tpagos tp');
        $res = $q->result();
        if($res)
            return $res;
        else 
            return 0;
    }
    
    public function getAllTupa(){
        $q = $this->db->select('tt.id, tt.nombre as concepto, tct.nombre as categoria, tt.costo')
                    ->join('tcategoria_tupa tct','tct.id = tt.id_categoria_tupa')
                    ->order_by('tt.id_categoria_tupa')
                    ->get('ttupa tt');
        $res = $q->result();
        if($res)
            return $res;
        else 
            return 0;
    }
    
    public function getTupa(){
        $q = $this->db->select('')
                    //->join('')
                    ->order_by('tt.id_categoria_tupa')
                    ->get('ttupa tt');
        $res = $q->result();
        if($res)
            return $res;
        else 
            return 0;
    }
    
    public function getCategoriasTupa(){
        $q = $this->db->select('')
                    //->join('')
                    //->order_by('tt.id_categoria_tupa')
                    ->get('tcategoria_tupa tt');
        $res = $q->result();
        if($res)
            return $res;
        else 
            return 0;
    }
    
    public function updateConcepto($data,$id){
        $this->db->where('id',$id)->update('ttupa',$data);
    }
    
    public function newConcepto($data){
        $this->db->insert('ttupa',$data);
        $id = $this->db->insert_id();
        return $id;
    }
    
    public function newPago($data){
        $this->db->insert('tpagos',$data);
        $id = $this->db->insert_id();
        return $id;
    }
    
    public function newSolicitudPago($data){
        $this->db->insert('tpagos_solicitudes',$data);
        $id = $this->db->insert_id();
        return $id;
    }
    
    public function getPagoForId($id){
        $q = $this->db->select()
                    //->join('')
                    //->order_by('tt.id_categoria_tupa')
                    ->where('id',$id)
                    ->get('ttupa tt');
        $res = $q->result();
        if($res)
            return $res[0];
        else 
            return 0;
    }
    
    public function getPago($id){
        $q = $this->db->select()
                    //->join('')
                    //->order_by('tt.id_categoria_tupa')
                    ->where('id',$id)
                    ->get('tpagos tt');
        $res = $q->result();
        if($res)
            return $res[0];
        else 
            return 0;
    }
    
    public function getPagosForAlumnoConcepto($cod_alumno,$concepto = -1){
        $this->db->select('tam.cod_alumno, tp.nombre as periodo, tc.nombre as ciclo, tt.nombre as turno, ttp.nombre as concepto, tg.monto as pago, tg.fch_pago as fecha');
        $this->db->from('talumno_matricula tam');
        //$this->db->join('tespecialidad te','te.id = tam.id_especialidad');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->join('tciclo tc','tc.id = tam.id_ciclo');
        $this->db->join('tturno tt','tt.id = tam.id_turno');
        $this->db->join('tpagos tg','tg.id_alumno_matricula = tam.id');
        $this->db->join('ttupa ttp','ttp.id = tg.id_tupa');
        if($concepto != -1)
            $this->db->where('tg.id_tupa',$concepto);
        $this->db->where('tam.cod_alumno',$cod_alumno);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getPagosForAlumnoConcepto_($cod_alumno,$concepto = -1){
        $this->db->select('tam.cod_alumno, tp.nombre as periodo, tc.nombre as ciclo, tt.nombre as turno, ttp.nombre as concepto, tg.monto as pago, tg.fch_pago as fecha');
        $this->db->from('talumno_matricula tam');
        //$this->db->join('tespecialidad te','te.id = tam.id_especialidad');
        $this->db->join('tperiodo tp','tp.id = tam.id_periodo');
        $this->db->join('tciclo tc','tc.id = tam.id_ciclo');
        $this->db->join('tturno tt','tt.id = tam.id_turno');
        $this->db->join('tpagos tg','tg.id_alumno_matricula = tam.id');
        $this->db->join('ttupa ttp','ttp.id = tg.id_tupa');
        if($concepto != -1)
            $this->db->where('tg.id_tupa',$concepto);
        $this->db->where('tam.cod_alumno',$cod_alumno);
        //$this->db->get();
        $res = $this->db->get()->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
    public function getSolicitudesPago($cod_alumno){
        $q = $this->db->select('tps.*, tp.monto, tt.nombre as concepto')
                    //->where('tnca.id_alumno_matricula',$id_alumno_matricula)
                    ->join('tpagos tp','tp.id = tps.id_pago')
                    ->join('ttupa tt','tt.id = tp.id_tupa')
                    ->where('tps.cod_alumno',$cod_alumno)
                    ->order_by('tps.id','desc')
                    ->get('tpagos_solicitudes tps');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }
    
}