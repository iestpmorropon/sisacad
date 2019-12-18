<?php
class Usuario extends CI_Model {

	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function newUsuario($datos = array()){
        if(count($datos) == 0)
            return 0;
        $this->db->insert('tusuario',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function getRoles_(){
        $q = $this->db->select()
                    ->get('trol tr');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getPermisosForPerfil($id_perfil){
        $q = $this->db->select('tpr.*, tmds.nombre, tmds.logo, tmds.id_padre, tmds.descripcion')
                    ->join('tmodulos tmds','tmds.id = tpr.id_modulo')
                    ->where('tpr.id_rol',$id_perfil)
                    ->where('tpr.estado',1)
                    ->get('tpermisos_roles tpr');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getUsuarios(){
        $q = $this->db->select('tp.*, tu.*, tu.id as id_usuario, tg.nombre as genero, tec.nombre as estado_civil')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->join('tgenero tg','tg.id = tp.id_genero')
                    ->join('testado_civil tec', 'tec.id = tp.id_estado_civil')
                    ->where('tp.id != 1')
                    ->get('tusuario tu');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getUsuariosForPerfil($id_perfil = []){
        $q = $this->db->select('tp.*, tu.*, tu.id as id_usuario, tg.nombre as genero, tec.nombre as estado_civil, tr.nombre as perfil, tu.estado as estado_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->join('tgenero tg','tg.id = tp.id_genero')
                    ->join('testado_civil tec', 'tec.id = tp.id_estado_civil')
                    ->join('tpersona_rol tpr','tpr.id_usuario = tu.id')
                    ->join('trol tr','tr.id = tpr.id_rol')
                    ->where_in('tpr.id_rol',$id_perfil)
                    //->where('tpr.id_rol != 2')
                    ->where('tp.id != 1')
                    ->get('tusuario tu');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getUsuarioForId($id){
        $q = $this->db->select('tp.*, tu.*, tu.id as id_usuario, tg.nombre as genero, tec.nombre as estado_civil, tr.nombre as perfil, tu.estado as estado_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->join('tgenero tg','tg.id = tp.id_genero')
                    ->join('testado_civil tec', 'tec.id = tp.id_estado_civil')
                    ->join('tpersona_rol tpr','tpr.id_usuario = tu.id')
                    ->join('trol tr','tr.id = tpr.id_rol')
                    ->where('tu.id',$id)
                    //->where('tpr.id_rol != 2')
                    ->where('tp.id != 1')
                    ->get('tusuario tu');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getUsuario($data = array()){
        $q = $this->db->select('tp.*, tu.*, tu.id as id_usuario, tg.nombre as genero, tec.nombre as estado_civil')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->join('tgenero tg','tg.id = tp.id_genero')
                    ->join('testado_civil tec', 'tec.id = tp.id_estado_civil')
                    ->where($data)
                    ->get('tusuario tu');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }
    
    public function getUsuarioPersonalice($data){
        $q = $this->db->select('tp.*, tu.*, tu.id as id_usuario, tpr.id')
                    ->join('tusuario tu','tu.id = tpr.id_usuario')
                    ->join('tpersona tp','tp.id = tu.id_persona')
                    ->where($data)
                    //->where("tu.usuario != 'tesoreria'")
                    ->get('tpersona_rol tpr');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getRoles($id_usuario = 0){
        $q = $this->db->select('tpr.*, tr.nombre as rol')
                    ->join('trol tr','tr.id = tpr.id_rol')
                    ->where('tpr.estado',1)
                    ->where('tpr.id_usuario',$id_usuario)
                    ->get('tpersona_rol tpr');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getModules($id_rol){
        $q = $this->db->select('tm.*, tpr.id_rol')
                    ->join('tpermisos_roles tpr','tpr.id_modulo = tm.id')
                    ->where('tpr.id_rol = '.$id_rol)
                    ->where('tpr.estado',1)
                    ->get('tmodulos tm');
        $res = $q->result_array();
        if($res)
            return $res;
        else
            return 0;
    }

    public function getUsuarioForPersona($id_persona){
        $q = $this->db->select()
                    //->join('trol tr','tr.id = tpr.id_rol')
                    ->where('id_persona',$id_persona)
                    //->where('id_rol',$id_rol)
                    ->get('tusuario');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function getPermisoWhere($where){
        $q = $this->db->select()
                    ->where($where)
                    ->get('tpermisos_roles');
        $res = $q->result();
        if($res)
            return $res[0];
        else
            return 0;
    }

    public function newPermiso($datos = array()){
        $this->db->insert('tpermisos_roles',$datos);
        $id = $this->db->insert_id();
        return $id;
    }

    public function updatePermiso($where, $data){
        $this->db->where($where)->update('tpermisos_roles',$data);
    }

    public function updateUsuario($data, $where){
        $this->db->where($where)->update('tusuario',$data);
    }

    public function newPermisoRol($data){
        $this->db->insert('tpersona_rol',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function deletePermisoRol($data){
        $this->db->where($data)->delete('tpersona_rol');
    }

    public function getPermisoPadre(){
        $q = $this->db->select()
                    ->where('id_padre',0)
                    ->get('tmodulos');
        $res = $q->result();
        if($res)
            return $res;
        else
            return 0;
    }

}