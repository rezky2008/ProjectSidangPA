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


    public function find_recom() {
        $data = json_decode(file_get_contents('php://input'), true);
        $tipe_sidang = $data['tipe_sidang']; // Get the type of schedule
    
        $data_mhs = $this->Mahasiswa_model->get_by_nim($data['mahasiswa']);
        $dosen_pbb = $this->Dosen_model->get_by_name($data['dosen_pbb']);
        $dosen_pnj1 = $this->Dosen_model->get_by_name($data['dosen_pnj1']);
        $dosen_pnj2 = $this->Dosen_model->get_by_name($data['dosen_pnj2']);
        $ruangan = $this->Ruangan_model->get_all();
    
        $kelas_mhs = $this->Kelas_model->get_by_id($data_mhs->id_kelas);
        $jadwal_kelas = json_decode($kelas_mhs->jadwal, true);
    
        $jadwal_pbb = json_decode($dosen_pbb->jadwal, true);
        $jadwal_pnj1 = json_decode($dosen_pnj1->jadwal, true);
        $jadwal_pnj2 = json_decode($dosen_pnj2->jadwal, true);
    
        $result = array();
    
        // Check if arrays are properly initialized and have the expected dimensions
        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 7; $j++) {
                $kelas_val = $jadwal_kelas[$i][$j];
                $pbb_val = $jadwal_pbb[$i][$j];
                $pnj1_val = $jadwal_pnj1[$i][$j];
                $pnj2_val = $jadwal_pnj2[$i][$j];
    
                // Cast the result of the AND operation to an integer
                $result[$i][$j] = (int)($kelas_val && $pbb_val && $pnj1_val && $pnj2_val);
            }
        }
    
        if ($tipe_sidang == "akhir") {
            for ($i = 0; $i < 5; $i++) {
                for ($j = 0; $j < 7; $j++) {
                    if ($result[$i][$j] == 1) {
                        // If the element is "1", set it to "1,1" (represented by 1 for both slots)
                        $result[$i][$j] = 1;
                    } else {
                        // If the element is "0", set it to "0,0" (represented by 0 for both slots)
                        $result[$i][$j] = 0;
                    }
    
                    // Special condition for first element in each array
                    if ($j == 0) {
                        if ($result[$i][$j] == 1) {
                            $result[$i][$j] = [1, 1];
                        } else {
                            $result[$i][$j] = [0, 0];
                        }
                    }
                }
            }
        }
    
        $ruangan_with_counts = array();
    
        foreach ($ruangan as $jadwal_ruangan) {
            $ruangan_val = json_decode($jadwal_ruangan->jadwal, true);
            $temp_result = array();
            $count_ones = 0;
    
            for ($i = 0; $i < 5; $i++) {
                for ($j = 0; $j < 7; $j++) {
                    if (is_array($result[$i][$j])) {
                        $temp_result[$i][$j] = array_map(function($val) use ($ruangan_val, $i, $j) {
                            return (int)($val && $ruangan_val[$i][$j]);
                        }, $result[$i][$j]);
                        $count_ones += array_sum($temp_result[$i][$j]);
                    } else {
                        $temp_result[$i][$j] = (int)($result[$i][$j] && $ruangan_val[$i][$j]);
                        if ($temp_result[$i][$j] == 1) {
                            $count_ones++;
                        }
                    }
                }
            }
    
            $jadwal_ruangan->jadwal = json_encode($temp_result);
            $ruangan_with_counts[] = array('ruangan' => $jadwal_ruangan, 'count' => $count_ones);
        }
    
        // Sort the rooms based on the count of ones in descending order
        usort($ruangan_with_counts, function($a, $b) {
            return $b['count'] - $a['count'];
        });
    
        // Get the top 10 rooms
        $top_10_ruangan = array_slice($ruangan_with_counts, 0, 10);
        $top_10_ruangan = array_map(function($item) {
            return $item['ruangan'];
        }, $top_10_ruangan);
    
        // Convert the result array to a JSON string
        $response['jadwal_without_ruangan'] = json_encode($result);
        $response['data_jadwal_ruangan'] = $top_10_ruangan;
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }    
}