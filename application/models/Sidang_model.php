<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sidang_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Function to get all records
    public function get_all()
    {
        $query = $this->db->get('sidang');
        return $query->result();
    }

    // Function to get a single record by id
    public function get_by_id($id)
    {
        $query = $this->db->get_where('sidang', array('id_sidang' => $id));
        return $query->row();
    }

    public function get_by_nim_terjadwal($nim)
    {
        $this->db->where('nim_mahasiswa', $nim);
        $this->db->where('status', 'terjadwal');
        $query = $this->db->get('sidang');
        return $query->row();
    }

    public function get_by_nim_akhir_selesai($nim)
    {
        $this->db->where('nim_mahasiswa', $nim);
        $this->db->where('status', 'selesai');
        $this->db->where('tipe_sidang', 'akhir');
        $query = $this->db->get('sidang');
        return $query->row();
    }

    // Function to insert a new record
    public function insert($data)
    {
        return $this->db->insert('sidang', $data);
    }

    // Function to update a record
    public function update($id, $data)
    {
        $this->db->where('id_sidang', $id);
        return $this->db->update('sidang', $data);
    }

    // Function to delete a record
    public function delete($id)
    {
        $this->db->where('id_sidang', $id);
        return $this->db->delete('sidang');
    }

    // Additional custom functions can be added here
}