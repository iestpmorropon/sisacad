<?php
class Practica extends CI_Model {

	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function newPractica($datos = array()){
        if(count($datos) == 0)
            return 0;
        $this->db->insert('tpracticas_preprof',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function deletePractica($id){
        $this->db->where('id',$id)->delete('tpracticas_preprof');
    }

    /*public function updatePractica($datos = array(),$id){
        $this->db->where('id',$id)->update('tpracticas_preprof',$datos);
    }*/

    public function getNota($data = array()){
        $q = $this->db->select()
                    //->where('tp.id != 1')
                    ->where('tpp.id_modulo',$data['id_modulo'])
                    ->where('tpp.id_especialidad',$data['id_especialidad'])
                    ->where('tpp.cod_alumno',$data['cod_alumno'])
                    ->get('tpracticas_preprof tpp');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getNota_($data){
        $q = $this->db->select()
                    //->where('tp.id != 1')
                    ->where($data)
                    ->get('tpracticas_preprof');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getAllPracticas(){
        $q = $this->db->select()
                    //->where('tp.id != 1')
                    //->where($data)
                    ->get('tpracticas_preprof');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function updatePractica($data,$where){
        $this->db->where($where)->update('tpracticas_preprof',$data);
    }

    public function getPracticasAlumnoData($where){
        $this->db->select('tpp.*, tm.nombre as modulo, tpp.fecha_acta, CONCAT(tp.nombre, " ",tp.apell_pat, " ", tp.apell_mat) as nombres, te.nombre as especialidad, tt.nombre as turno, tp.dni, tm.orden');
        $this->db->join('tmodulo tm','tm.id = tpp.id_modulo');
        $this->db->join('talumno ta','ta.codigo = tpp.cod_alumno');
        $this->db->join('tusuario tu','tu.id = ta.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->join('tespecialidad te','te.id = tpp.id_especialidad');
        $this->db->join('tturno tt','tt.id = tpp.id_turno');
        //$this->db->where('')
        if(isset($where['id_especialidad']))
            $this->db->where('tpp.id_especialidad',$where['id_especialidad']);
        if(isset($where['id_turno']))
            $this->db->where('tpp.id_turno',$where['id_turno']);
        if(isset($where['fechaStart']))
            $this->db->where("tpp.fecha_acta BETWEEN '".$where['fechaStart']."' AND '".$where['fechaEnd']."'");
        if(isset($where['cod_alumno']))
            $this->db->where("tpp.cod_alumno = '".$where['cod_alumno']."'");
        $res = $this->db->get('tpracticas_preprof tpp')->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getPracticasAlumnoLlevadas($cod_alumno,$id_modulo){
        $this->db->select('tpp.*');
        $this->db->from('tpracticas_preprof tpp');
        $this->db->where('tpp.cod_alumno',$cod_alumno);
        $this->db->where('tpp.id_modulo',$id_modulo);
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
}