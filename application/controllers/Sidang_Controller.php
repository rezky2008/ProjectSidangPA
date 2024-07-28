<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sidang_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dosen_model');
        $this->load->model('Kelas_model');
        $this->load->model('Mahasiswa_model');
        $this->load->model('Ruangan_model');
        $this->load->model('Sidang_model');
    }


    public function find_recom() {
        $data = json_decode(file_get_contents('php://input'), true);
    
        $data_mhs = $this->Mahasiswa_model->get_by_nim($data['mahasiswa']);
        $dosen_pbb = $this->Dosen_model->get_by_name($data['dosen_pbb']);
        $dosen_pnj1 = $this->Dosen_model->get_by_name($data['dosen_pnj1']);
        $dosen_pnj2 = $this->Dosen_model->get_by_name($data['dosen_pnj2']);
        $tipe_sidang = $data['tipe_sidang'];
        $ruangan = $this->Ruangan_model->get_all();
        $sidang = $this->Sidang_model->get_all();
    
        $kelas_mhs = $this->Kelas_model->get_by_id($data_mhs->id_kelas);
        $jadwal_kelas = json_decode($kelas_mhs->jadwal, true);
        
        $jadwal_pbb = json_decode($dosen_pbb->jadwal, true);
        $jadwal_pnj1 = json_decode($dosen_pnj1->jadwal, true);
        $jadwal_pnj2 = json_decode($dosen_pnj2->jadwal, true);
    
        $result = array();

        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $kelas_val = $jadwal_kelas[$i][$j];
                $pbb_val = $jadwal_pbb[$i][$j];
                $pnj1_val = $jadwal_pnj1[$i][$j];
                $pnj2_val = $jadwal_pnj2[$i][$j];
    
                // Cast the result of the AND operation to an integer
                $result[$i][$j] = (int)($kelas_val && $pbb_val && $pnj1_val && $pnj2_val);
            }
        }
        

        foreach ($ruangan as $jadwal_ruangan) {
            $ruangan_val = json_decode($jadwal_ruangan->jadwal, true);
            $temp_result = array();
    
            for ($i = 0; $i < 5; $i++) {
                for ($j = 0; $j < 10; $j++) {
                    $temp_result[$i][$j] = (int)($result[$i][$j] && $ruangan_val[$i][$j]);
                }
            }
    
            $jadwal_ruangan->jadwal = json_encode($temp_result);
        }

        if ($tipe_sidang == "akhir"){
            for ($i = 0; $i < 5; $i++) {
                $temp_result = array();
                for ($j = 0; $j < 9; $j++) {
                    $temp_result[$j] = (int)($result[$i][$j] && $result[$i][$j+1]);
                }
                $result[$i] = $temp_result;
            }

            foreach ($ruangan as $jadwal_ruangan) {
                $ruangan_val = json_decode($jadwal_ruangan->jadwal, true);
                $temp_result = array();
        
                for ($i = 0; $i < 5; $i++) {
                    for ($j = 0; $j < 9; $j++) {
                        $temp_result[$i][$j] = (int)($ruangan_val[$i][$j] && $ruangan_val[$i][$j+1]);
                    }
                }
        
                $jadwal_ruangan->jadwal = json_encode($temp_result);
            }
        }


        
    
        // Convert the result array to a JSON string
        $response['jadwal_without_ruangan'] = json_encode($result);
        $response['data_jadwal_ruangan'] = $ruangan;
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_sidang_all(){
        $response['data_sidang'] = $this->Sidang_model->get_all();
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_sidang_by_id($id){
        $id = urldecode($id);
        $response['data_sidang'] = $this->Sidang_model->get_by_id($id);
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function add_sidang(){
        $data = json_decode(file_get_contents('php://input'), true);

        $mahasiswa = $data['mahasiswa'];
        $pembimbing = $data['pembimbing'];
        $penguji1 = $data['penguji1'];
        $penguji2 = $data['penguji2'];
        $tipe_sidang = $data['tipe_sidang'];
        $ruang = $data['ruang'];
        $waktu_display = $data['waktu_display'];
        $waktu_index = $data['waktu_index'];

        $insert_data = array(
            'mahasiswa' => $mahasiswa,
            'pembimbing' => $pembimbing,
            'penguji1' => $penguji1,
            'penguji2' => $penguji2,
            'tipe_sidang' => $tipe_sidang,
            'ruang' => $ruang,
            'waktu_display' => $waktu_display,
            'waktu_index' => $waktu_index,
            // Add other necessary fields here
        );
        
        $inserted = $this->Sidang_model->insert($insert_data);

        if ($inserted) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_sidang(){
        $data = json_decode(file_get_contents('php://input'), true);

        $id_sidang = $data['id_sidang'];
        $mahasiswa = $data['mahasiswa'];
        $pembimbing = $data['pembimbing'];
        $penguji1 = $data['penguji1'];
        $penguji2 = $data['penguji2'];
        $tipe_sidang = $data['tipe_sidang'];
        $ruang = $data['ruang'];
        $waktu_display = $data['waktu_display'];
        $waktu_index = $data['waktu_index'];

        $insert_data = array(
            'id_sidang' => $id_sidang,
            'mahasiswa' => $mahasiswa,
            'pembimbing' => $pembimbing,
            'penguji1' => $penguji1,
            'penguji2' => $penguji2,
            'tipe_sidang' => $tipe_sidang,
            'ruang' => $ruang,
            'waktu_display' => $waktu_display,
            'waktu_index' => $waktu_index,
            // Add other necessary fields here
        );

        $updated = $this->Sidang_model->update($id_sidang, $update_data); // Updated to use $id_sidang

        if ($updated) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_sidang($id){
        $deleted = $this->Sidang_model->delete($id);

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