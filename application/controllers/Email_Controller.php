<?php
class Email_Controller extends CI_Controller {

    public function send_mail() {
        $this->load->library('email');

        // No need to set configuration here since it's already in config/email.php

        $this->email->from('wedri20ti@mahasiswa.pcr.ac.id', 'Penjadwalan PA');
        $this->email->to('lunarknight20@gmail.com');
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');

        if ($this->email->send()) {
            echo 'Email sent.';
        } else {
            show_error($this->email->print_debugger());
        }
    }
}
?>