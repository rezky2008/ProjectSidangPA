<?php
class Email_Controller extends CI_Controller {

        public function send_mail() {
            $this->load->library('email');
    
            $config = array(
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://smtp.gmail.com',
                'smtp_port' => 465,
                'smtp_user' => 'lunarknight20@gmail.com',
                'smtp_pass' => 'xjoufcoibxcbxttg', // Your app password without spaces
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE,
                'newline' => "\r\n" // Note: must use double quotes for newline
            );
    
            $this->email->initialize($config);
    
            $this->email->from('lunarknight20@gmail.com', 'Rezky Kurniawan');
            $this->email->to('lunarknight000@gmail.com');
            $this->email->subject('Email Test');
            $this->email->message('Testing the email class.');
    
            if ($this->email->send()) {
                echo 'Email sent.';
            } else {
                show_error($this->email->print_debugger());
            }
        }
    }
    
}
?>