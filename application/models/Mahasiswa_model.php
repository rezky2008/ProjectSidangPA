<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Function to get all records
    public function get_all()
    {
        $query = $this->db->get('mahasiswa');
        return $query->result();
    }

    // Function to get a single record by id
    public function get_by_nim($NIM)
    {
        $query = $this->db->get_where('mahasiswa', array('NIM' => $NIM));
        return $query->row();
    }

    // Function to insert a new record
    public function insert($data)
    {
        return $this->db->insert('mahasiswa', $data);
    }

    // Function to update a record
    public function update($NIM, $data)
    {
        $this->db->where('NIM', $NIM);
        return $this->db->update('mahasiswa', $data);
    }

    // Function to delete a record
    public function delete($NIM)
    {
        $this->db->where('NIM', $NIM);
        return $this->db->delete('mahasiswa');
    }

    // Additional custom functions can be added here
}