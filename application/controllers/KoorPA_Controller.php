<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KoorPA_Controller extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('KoorPA_model');
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

	public function add_user(){
		if ($this->session->userdata('logged_in')) {
			$data['status'] = $this->session->userdata('status');
			$data['username'] = $this->session->userdata('username');
			$this->load->view('header', $data);
			$this->load->view('add_user', $data);
			$this->load->view('footer');
		} else {
			redirect('/login');
		}
	}

	public function adding_user(){
		$username = $this->input->post('username');
        $password = $this->input->post('password');
		$status = 'member';

		$data = array(
            'username' => $username,
            'password' => $password,
			'status' => $status
        );

		$inserted = $this->User_model->insert($data);

        if ($inserted) {
            // Redirect to success page or show success message
            redirect('Utama/index');
        } else {
            // Redirect to error page or show error message
            redirect('error_page');
        }
	}

	public function edit_user($id){
		$data['user'] = $this->User_model->get_by_id($id);
		$data['status'] = $this->session->userdata('status');
		$data['username'] = $this->session->userdata('username');
		$this->load->view('header', $data);
		$this->load->view('edit_user', $data);
		$this->load->view('footer');
	}

	public function editing_user(){
		$id = $this->input->post('id');
		$username = $this->input->post('username');
        $password = $this->input->post('password');
		$status = $this->input->post('status');

		$data = array(
			'id' => $id,
            'username' => $username,
            'password' => $password,
			'status' => $status
        );

		$updated = $this->User_model->update($id, $data);

        if ($updated) {
            // Redirect to success page or show success message
            redirect('/');
        } else {
            // Redirect to error page or show error message
            redirect('error_page');
        }
	}

	public function delete_user($id){
		$deleted = $this->User_model->delete($id);

		if ($deleted) {
            // Redirect to success page or show success message
            redirect('/');
        } else {
            // Redirect to error page or show error message
            redirect('error_page');
        }
	}

	
}
