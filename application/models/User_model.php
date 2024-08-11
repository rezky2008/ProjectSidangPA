<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Function to get all records
    public function get_all()
    {
        $query = $this->db->get('user');
        return $query->result();
    }

    // Function to get a single record by username
    public function get_by_id($id)
    {
        $query = $this->db->get_where('user', array('id_user' => $id));
        return $query->row();
    }

    public function get_by_email($email)
    {
        $query = $this->db->get_where('user', array('email' => $email));
        return $query->row();
    }

    // Function to insert a new record
    public function insert($data)
    {
        return $this->db->insert('user', $data);
    }

    // Function to update a record
    public function update($id, $data)
    {
        $this->db->where('id_user', $id);
        return $this->db->update('user', $data);
    }

    // Function to delete a record
    public function delete($id)
    {
        $this->db->where('id_user', $id);
        return $this->db->delete('user');
    }

    // Additional custom functions can be added here
}