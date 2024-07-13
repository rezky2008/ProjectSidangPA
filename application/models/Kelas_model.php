<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Function to get all records
    public function get_all()
    {
        $query = $this->db->get('kelas');
        return $query->result();
    }

    // Function to get a single record by id
    public function get_by_id($id)
    {
        $query = $this->db->get_where('kelas', array('id_kelas' => $id));
        return $query->row();
    }

    // Function to insert a new record
    public function insert($data)
    {
        return $this->db->insert('kelas', $data);
    }

    // Function to update a record
    public function update($id, $data)
    {
        $this->db->where('id_kelas', $id);
        return $this->db->update('kelas', $data);
    }

    // Function to delete a record
    public function delete($id)
    {
        $this->db->where('id_kelas', $id);
        return $this->db->delete('kelas');
    }

    // Additional custom functions can be added here
}