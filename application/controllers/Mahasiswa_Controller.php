<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Mahasiswa_model');
    }

    public function get_mahasiswa_all(){
        $response['data_mahasiswa'] = $this->Mahasiswa_model->get_all();
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_mahasiswa_by_id($id){
        $response['data_mahasiswa'] = $this->Mahasiswa_model->get_by_id($id);
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function add_mahasiswa(){
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['NIM'])) {
            $response = ['message' => 'failed', 'error' => 'NIM is required'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        $NIM = $data['NIM'];
        $nama = $data['nama'];
        $id_kelas = $data['id_kelas'];

        $insert_data = array(
            'NIM' => $NIM,
            'nama' => $nama,
            'id_kelas' => $id_kelas
            // Add other necessary fields here
        );
        
        $inserted = $this->Mahasiswa_model->insert($insert_data);

        if ($inserted) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_mahasiswa(){
        $data = json_decode(file_get_contents('php://input'), true);

        $NIM = $data['NIM'];
        $nama = $data['nama'];
        $id_kelas = $data['id_kelas'];

        $update_data = array(
            'NIM' => $NIM,
            'nama' => $nama,
            'id_kelas' => $id_kelas,
        );

        $updated = $this->Mahasiswa_model->update($NIM, $update_data); // Updated to use $NIM

        if ($updated) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_mahasiswa($id){
        $deleted = $this->Mahasiswa_model->delete($id);

        if ($deleted) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}