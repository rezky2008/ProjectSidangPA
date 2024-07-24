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


    public function find_recom(){
        $data = json_decode(file_get_contents('php://input'), true);

        $data_mhs = $this->Mahasiswa_model->get_by_nim($data['mahasiswa']);
        $dosen_pbb = $this->Dosen_model->get_by_name($data['dosen_pbb']);
        $dosen_pnj1 = $this->Dosen_model->get_by_name($data['dosen_pnj1']);
        $dosen_pnj2 = $this->Dosen_model->get_by_name($data['dosen_pnj2']);
        $ruangan = $this->Ruangan_model->get_all();

        $kelas_mhs = $this->Kelas_model->get_by_id($data_mhs['id_kelas']);
        $jadwal_kelas = $kelas_mhs['jadwal'];

        $jadwal_pbb = $dosen_pbb['jadwal'];

        $jadwal_pnj1 = $dosen_pnj1['jadwal'];

        $jadwal_pnj2 = $dosen_pnj2['jadwal'];

        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 7; $j++) {
                $result[$i][$j] = $jadwal_kelas[$i][$j] && $jadwal_pbb[$i][$j] && $jadwal_pnj1[$i][$j] && $jadwal_pnj2[$i][$j];
            }
        }

        $response['test_hasil'] = $result;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}