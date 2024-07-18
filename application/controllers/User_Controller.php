<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Controller extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

	public function login(){
        $data = json_decode(file_get_contents('php://input'), true);

		$username = $data['username'];
        $password = $data['password'];

		$user['user'] = $this->KoorPA_model->get_by_username($username);

		if ($password === $user['user']->password){
			$this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('username', $user['user']->username);

            $response['message'] = 'success'
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
		} else{
            $response['message'] = 'failed'
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		
        $response['message'] = 'success'
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
	}

	public function get_user_by_email(){
		$data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email'])) {
            $response = ['message' => 'failed', 'error' => 'email is required'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

		$email = $data['email'];

        $response['data_user'] = $this->User_model->get_by_email($email);
        $response['message'] = 'success';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

	
}
