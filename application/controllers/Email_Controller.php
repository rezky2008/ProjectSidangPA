<?php
class Email_Controller extends CI_Controller {

        public function send_mail() {
            $this->load->library('email');
    
            $config = array(
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://smtp.gmail.com',
                'smtp_port' => 465,
                'smtp_user' => 'rezky20ti@mahasiswa.pcr.ac.id',
                'smtp_pass' => 'zpazeabxzhffwsth', // Your app password without spaces
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE,
                'newline' => "\r\n" // Note: must use double quotes for newline
            );
    
            $this->email->initialize($config);
    
            $this->email->from('rezky20ti@mahasiswa.pcr.ac.id', 'PenjadwalanPA');
            $this->email->to('lunarknight20@gmail.com');
            $this->email->subject('Email Test');
            $this->email->message(
                'Mencoba baris 1 \r\n
                Mencoba baris 2\r\n
                Mencoba baris 3'
            );
    
            if ($this->email->send()) {
                echo 'Email sent.';
            } else {
                show_error($this->email->print_debugger());
            }
        }
    }
?>