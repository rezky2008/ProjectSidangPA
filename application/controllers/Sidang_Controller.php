<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sidang_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dosen_model');
        $this->load->model('Kelas_model');
        $this->load->model('Mahasiswa_model');
        $this->load->model('Ruangan_model');
    }


    public function cari_jadwal(){
        $data = json_decode(file_get_contents('php://input'), true);

        $mhs = $data['mahasiswa'];
        $dosen_pbb = $data['dosen_pbb'];
        $dosen_pnj1 = $data['dosen_pnj1'];
        $dosen_pnj2 = $data['dosen_pnj2'];

        $data_mhs = $this->Mahasiswa_model->get_by_nim($mhs);
        $kelas_mhs = $this->Kelas_model->get_kelas_by_id($data_mhs['']);

        $insert_data = array(
            'id_dosen' => $id_dosen,
            'nama' => $nama,
            'inisial' => $inisial,
            'kbk' => $kbk,
            'email' => $email,
            // Add other necessary fields here
        );
        

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

        $id_dosen = $data['id_dosen'];
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

        $updated = $this->Dosen_model->update($id_dosen, $update_data); // Updated to use $id_dosen

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