<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dosen_model');
    }

    public function get_dosen_all(){
        $response['data_dosen'] = $this->Dosen_model->get_all();
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_dosen_by_id($id){
        $response['data_dosen'] = $this->Dosen_model->get_by_id($id);
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function add_dosen(){
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id_dosen'])) {
            $response = ['message' => 'failed', 'error' => 'id_dosen is required'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        $id_dosen = $data['id_dosen'];
        $nama = $data['nama'];
        $inisial = $data['inisial'];
        $kbk = $data['kbk'];
        $email = $data['email'];

        $insert_data = array(
            'id_dosen' => $id_dosen,
            'nama' => $nama,
            'inisial' => $inisial,
            'kbk' => $kbk,
            'email' => $email,
            // Add other necessary fields here
        );
        
        $inserted = $this->Dosen_model->insert($insert_data);

        if ($inserted) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_dosen(){
        $data = json_decode(file_get_contents('php://input'), true);

        $id_lama = $data['id_lama'];
        $id_baru = $data['id_baru'];
        $nama = $data['nama'];
        $inisial = $data['inisial'];
        $kbk = $data['kbk'];
        $email = $data['email'];
        $jadwal = $data['jadwal'];

        $update_data = array(
            'id_dosen' => $id_dosen,
            'nama' => $nama,
            'inisial' => $inisial,
            'kbk' => $kbk,
            'email' => $email,
            'jadwal' => $jadwal,
        );

        $updated = $this->Dosen_model->update($id_lama, $update_data); // Updated to use $id_dosen

        if ($updated) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_dosen($id){
        $deleted = $this->Dosen_model->delete($id);

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