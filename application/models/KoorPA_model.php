<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KoorPA_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Function to get all records
    public function get_all()
    {
        $query = $this->db->get('koorpa');
        return $query->result();
    }

    // Function to get a single record by username
    public function get_by_username($username)
    {
        $query = $this->db->get_where('koorpa', array('username' => $username));
        return $query->row();
    }

    // Function to insert a new record
    public function insert($data)
    {
        return $this->db->insert('koorpa', $data);
    }

    // Function to update a record
    public function update($username, $data)
    {
        $this->db->where('username', $username);
        return $this->db->update('koorpa', $data);
    }

    // Function to delete a record
    public function delete($username)
    {
        $this->db->where('username', $username);
        return $this->db->delete('koorpa');
    }

    // Additional custom functions can be added here
}