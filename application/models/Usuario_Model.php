<?php

class Usuario_Model extends CI_Model{

    public function getAllUsers() {
        $query = $this->db->get('usuario');
        return $query->result();
    }

    public function insert($data = array()){
        $this->db->insert('usuario', $data);
        return $this->db->affected_rows();
    }

    public function delete($id){
        $this->db->where('id =', $id);
        $this->db->delete('usuario');
        return $this->db->affected_rows();     
    }

    public function alter($id,$data = array()){
        $this->db->where('id', $id); 
        $this->db->update('usuario', $data);
        return $this->db->affected_rows();
    }
}