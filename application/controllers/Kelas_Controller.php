<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kelas_model');
    }

    public function get_kelas_all(){
        $response['data_kelas'] = $this->Kelas_model->get_all();
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_kelas_by_id($id){
        $id = urldecode($id);
        $response['data_kelas'] = $this->Kelas_model->get_by_id($id);
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function add_kelas(){
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id_kelas'])) {
            $response = ['message' => 'failed', 'error' => 'id_kelas is required'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        $id_kelas = $data['id_kelas'];

        $insert_data = array(
            'id_kelas' => $id_kelas,
            // Add other necessary fields here
        );
        
        $inserted = $this->Kelas_model->insert($insert_data);

        if ($inserted) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_kelas(){
        $data = json_decode(file_get_contents('php://input'), true);

        $id_kelas = $data['id_kelas'];
        $jadwal = $data['jadwal'];

        $update_data = array(
            'id_kelas' => $id_kelas,
            'jadwal' => $jadwal,
        );

        $updated = $this->Kelas_model->update($id_kelas, $update_data); // Updated to use $id_kelas

        if ($updated) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_kelas($id){
        $deleted = $this->Kelas_model->delete($id);

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