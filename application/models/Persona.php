<?php
class Persona extends CI_Model {

	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function newPersona($datos = array()){
        if(count($datos) == 0)
            return 0;
        $this->db->insert('tpersona',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function getCantidadPersonas(){
        $q = $this->db->select()
                //->join('trol tr','tr.id = tpr.id_rol')
                //->where($where)
                ->get('tpersona');
        return $q->num_rows();
    }

    public function getMaximoPersona(){
        $q = $this->db->select('max(id) as ultimo')
                ->get('tpersona');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function updatePersona($data,$where){
        $this->db->where($where)->update('tpersona',$data);
    }

    public function getUsuarioRol($id_usuario,$id_rol){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('id_usuario',$id_usuario)
                    ->where('id_rol',$id_rol)
                    ->get('tpersona_rol');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function newUsuarioRol($rol){
        $this->db->insert('tpersona_rol',$rol);
    }

    public function getProfesorForUsuario($id_usuario){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('id_usuario',$id_usuario)
                    //->where('id_rol',$id_rol)
                    ->get('tprofesor');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getProfesor($id){
        $this->db->select('tpf.id as id_profesor, tpf.codigo as codigo_profesor, tp.nombre, tp.apell_pat, tp.apell_mat');
        $this->db->join('tusuario tu','tu.id = tpf.id_usuario');
        $this->db->join('tpersona tp','tp.id = tu.id_persona');
        $this->db->where('tpf.id',$id);
        $this->db->from('tprofesor tpf');
        $res = $this->db->get()->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

}