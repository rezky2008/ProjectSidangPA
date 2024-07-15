<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ruangan_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Ruangan_model');
    }

    public function get_ruangan_all(){
        $response['data_ruangan'] = $this->Ruangan_model->get_all();
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_ruangan_by_id($id){
        $response['data_ruangan'] = $this->Ruangan_model->get_by_id($id);
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function add_ruangan(){
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id_ruangan'])) {
            $response = ['message' => 'failed', 'error' => 'id_ruangan is required'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        $id_ruangan = $data['id_ruangan'];

        $insert_data = array(
            'id_ruangan' => $id_ruangan,
            // Add other necessary fields here
        );
        
        $inserted = $this->Ruangan_model->insert($insert_data);

        if ($inserted) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_ruangan(){
        $data = json_decode(file_get_contents('php://input'), true);

        $id_ruangan = $data['id_ruangan'];
        $jadwal = $data['jadwal'];

        $update_data = array(
            'id_ruangan' => $id_ruangan,
            'jadwal' => $jadwal,
        );

        $updated = $this->Ruangan_model->update($id_ruangan, $update_data); // Updated to use $id_ruangan

        if ($updated) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_ruangan($id){
        $deleted = $this->Ruangan_model->delete($id);

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