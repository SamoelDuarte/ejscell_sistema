<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Device_model extends CI_Model {

    protected $table_name = 'devices';

    public function __construct() {
        parent::__construct();
    }

    public function save($data) {
        
        $this->db->insert($this->table_name, $data);

        return $this->db->insert_id();
    }

    public function deleteRowsWithStatusNotOne()
    {
        // Delete all rows where status is not 1
        $this->db->where('status !=', 1);
        $this->db->or_where('status', NULL);
        $this->db->delete('devices');
    
        // Check if any rows were affected
        $affected_rows = $this->db->affected_rows();
    
        // Get the last executed query for debugging
        $last_query = $this->db->last_query();
    
        return array(
            'affected_rows' => $affected_rows,
            'last_query' => $last_query
        );
    }

    public function update($data,$id){
         

        // Atualiza os dados do dispositivo no banco de dados
        $this->db->where('id', $id);
        $this->db->update('devices', $data);
        
        // Retorna verdadeiro se a atualização for bem-sucedida
        return $this->db->affected_rows() > 0;
    }
}