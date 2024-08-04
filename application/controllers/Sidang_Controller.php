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
        $this->load->library('email');
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

        $nama_mahasiswa = $data['nama_mahasiswa'];
        $nim_mahasiswa = $data['nim_mahasiswa'];
        $pembimbing = $data['pembimbing'];
        $penguji1 = $data['penguji1'];
        $penguji2 = $data['penguji2'];
        $tipe_sidang = $data['tipe_sidang'];
        $ruang = $data['ruang'];
        $waktu_display = $data['waktu_display'];
        $waktu_index = $data['waktu_index'];

        $koordinat_waktu = array_map('intval', explode(" ", $waktu_index));
        $hari = $koordinat_waktu[0];
        $jam = $koordinat_waktu[1];

        $ruangan_with_id = $this->Ruangan_model->get_by_id($ruang);
        $ruang_jadwal = json_decode($ruangan_with_id->jadwal, true);
        if ($tipe_sidang == "proposal"){
            $ruang_jadwal[$hari][$jam] = 0;
        }else{
            $ruang_jadwal[$hari][$jam] = 0;
            $ruang_jadwal[$hari][$jam+1] = 0;
        }
        $ruang_jadwal_updated = json_encode($ruang_jadwal);

        $insert_data = array(
            'nama_mahasiswa' => $nama_mahasiswa,
            'nim_mahasiswa' => $nim_mahasiswa,
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

            $update_jadwal = array(
                'id_ruangan' => $ruang,
                'jadwal' => $ruang_jadwal_updated,
            );

            $this->Ruangan_model->update($ruang, $update_jadwal);
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
        $nama_mahasiswa = $data['nama_mahasiswa'];
        $nim_mahasiswa = $data['nim_mahasiswa'];
        $pembimbing = $data['pembimbing'];
        $penguji1 = $data['penguji1'];
        $penguji2 = $data['penguji2'];
        $tipe_sidang = $data['tipe_sidang'];
        $ruang = $data['ruang'];
        $waktu_display = $data['waktu_display'];
        $waktu_index = $data['waktu_index'];

        $insert_data = array(
            'id_sidang' => $id_sidang,
            'nama_mahasiswa' => $nama_mahasiswa,
            'nim_mahasiswa' => $nim_mahasiswa,
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
        $data_sidang = $this->Sidang_model->get_by_id($id);

        $ruang_sidang = $data_sidang->ruang;
        $tipe_sidang = $data_sidang->tipe_sidang;
        $waktu_sidang = $data_sidang->waktu_index;

        $koordinat_waktu = array_map('intval', explode(" ", $waktu_sidang));
        $hari = $koordinat_waktu[0];
        $jam = $koordinat_waktu[1];

        $ruangan_with_id = $this->Ruangan_model->get_by_id($ruang_sidang);
        $ruang_jadwal = json_decode($ruangan_with_id->jadwal, true);
        if ($tipe_sidang == "proposal"){
            $ruang_jadwal[$hari][$jam] = 1;
        }else{
            $ruang_jadwal[$hari][$jam] = 1;
            $ruang_jadwal[$hari][$jam+1] = 1;
        }
        $ruang_jadwal_updated = json_encode($ruang_jadwal);
        $update_jadwal = array(
            'id_ruangan' => $ruang_sidang,
            'jadwal' => $ruang_jadwal_updated,
        );

        $this->Ruangan_model->update($ruang_sidang, $update_jadwal);

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

    public function send_mail($id) {
        $data_sidang = $this->Sidang_model->get_by_id($id);
    
        $ruang_sidang = $data_sidang->ruang;
        $tipe_sidang = ucfirst($data_sidang->tipe_sidang);
        $waktu_sidang = $data_sidang->waktu_display;
        $nama_mahasiswa = $data_sidang->nama_mahasiswa;
    
        $nim_mahasiswa = $data_sidang->nim_mahasiswa;
        $nama_pembimbing = $data_sidang->pembimbing;
        $nama_penguji1 = $data_sidang->penguji1;
        $nama_penguji2 = $data_sidang->penguji2;
    
        $data_mhs = $this->Mahasiswa_model->get_by_nim($nim_mahasiswa);
        $data_pbb = $this->Dosen_model->get_by_name($nama_pembimbing);
        $data_pnj1 = $this->Dosen_model->get_by_name($nama_penguji1);
        $data_pnj2 = $this->Dosen_model->get_by_name($nama_penguji2);
    
        $email_mhs = $data_mhs->email;
        $email_pbb = $data_pbb->email;
        $email_pnj1 = $data_pnj1->email;
        $email_pnj2 = $data_pnj2->email;
    
        $daftar_email = [$email_mhs, $email_pbb, $email_pnj1, $email_pnj2];
        $waktu_displays = explode(", ",$waktu_sidang);
    
        // Extract the day of the week from $waktu_sidang
        preg_match('/^(\w+),/', $waktu_sidang, $matches);
        $hari = $matches[1];
    
        // Create an array to map Indonesian days to English days
        $hari_mapping = [
            'Senin' => 'Monday',
            'Selasa' => 'Tuesday',
            'Rabu' => 'Wednesday',
            'Kamis' => 'Thursday',
            'Jumat' => 'Friday',
            'Sabtu' => 'Saturday',
            'Minggu' => 'Sunday'
        ];
    
        // Get the current date
        $current_date = date('Y-m-d');
    
        // Calculate the date for the next occurrence of the given day
        $next_date = date('Y-m-d', strtotime("next " . $hari_mapping[$hari], strtotime($current_date)));
    
        // If the calculated next date is less than 7 days away, calculate the date for the week after
        if (strtotime($next_date) - strtotime($current_date) < 7 * 86400) {
            $next_date = date('Y-m-d', strtotime("next " . $hari_mapping[$hari], strtotime($next_date)));
        }

        // Set locale to Indonesian for date formatting
        setlocale(LC_TIME, 'id_ID.UTF-8');
        $tanggal_format = strftime('%d %B %Y', strtotime($next_date));

    
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'rezky20ti@mahasiswa.pcr.ac.id',
            'smtp_pass' => 'zpazeabxzhffwsth',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE,
            'newline' => "\r\n"
        );
    
        $this->email->initialize($config);
    
        $this->email->from('rezky20ti@mahasiswa.pcr.ac.id', 'Sistem Penjadwalan PA');
        $this->email->to($daftar_email); // Specify multiple recipients here
        $this->email->subject("[no-reply] Sidang $tipe_sidang | $nama_mahasiswa");
    
        $message = "
        <p>Assalamu'alaikum Wr.Wb</p>
        <p>Dengan email ini kami mengundang hadirin untuk menghadiri Sidang $tipe_sidang mahasiswa $nama_mahasiswa yang akan dilaksanakan pada:</p>
        <p>Tanggal: $waktu_displays[0], $tanggal_format</p>
        <p>Waktu : $waktu_displays[1]</p>
        <p>Tempat : R $ruang_sidang</p>
        <p>Kami harap hadirin dapat menghadiri acara sidang ini</p>
        <br>
        <p>Terima kasih,</p>
        <p>Sistem Penjadwalan PA</p>
        ";
    
        $this->email->message($message);
    
        if ($this->email->send()) {
            echo 'Email sent.';
        } else {
            show_error($this->email->print_debugger());
        }
    }
    
    
}