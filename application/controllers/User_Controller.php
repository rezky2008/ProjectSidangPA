<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Controller extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('email');
    }

	public function login(){
        $data = json_decode(file_get_contents('php://input'), true);

		$username = $data['username'];
        $password = $data['password'];

		$user['user'] = $this->KoorPA_model->get_by_username($username);

		if ($password === $user['user']->password){
			$this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('username', $user['user']->username);

            $response['message'] = 'success';

            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));

		} else{
            $response['message'] = 'failed';
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		
        $response['message'] = 'success';
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
	}

	public function get_user_all(){
        $response['data_user'] = $this->User_model->get_all();
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

	public function get_user_by_id($id){
		$data = json_decode(file_get_contents('php://input'), true);

        $response['data_user'] = $this->User_model->get_by_id($id);
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

	public function add_user(){
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email'])) {
            $response = ['message' => 'failed', 'error' => 'email is required'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];
        $nama = $data['nama'];
        $kelas = $data['kelas'];

        $insert_data = array(
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'nama' => $nama,
            'kelas' => $kelas,
            // Add other necessary fields here
        );
        
        $inserted = $this->User_model->insert($insert_data);

        if ($inserted) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_user(){
        $data = json_decode(file_get_contents('php://input'), true);

        $id_user = $data['id_user'];
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];
        $nama = $data['nama'];
        $kelas = $data['kelas'];

        $update_data = array(
            'id_user' => $id_user,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'nama' => $nama,
            'kelas' => $kelas,
        );

        $updated = $this->User_model->update($id_user, $update_data); // Updated to use $email

        if ($updated) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_user($id){
		$deleted = $this->User_model->delete($id);

        if ($deleted) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'failed';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function lupa_password(){
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'];

        $user = $this->User_model->get_by_email($email);
        $nama = json_decode($user->nama, true);
        $password = json_decode($user->password, true);

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
        $this->email->to($email); // Specify multiple recipients here
        $this->email->subject("[no-reply] Lupa Password | $nama");
    
        $message = "
        <p>Berikut merupakan password anda:</p>
        <p>$password</p>
        ";
    
        $this->email->message($message);
    
        if ($this->email->send()) {
            echo 'Email sent.';
        } else {
            show_error($this->email->print_debugger());
        }
    }
	
}
