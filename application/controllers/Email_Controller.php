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
            $message = "
            <p>Testing the email class.</p>
            <p>This is the second line.</p>
            <p>This is the third line.</p>
            <table style='border-collapse: collapse;'>
                <tr>
                    <td style='padding: 0; margin: 0;'><p>Ruang</p></td>
                    <td style='padding: 0; margin: 0;'><p>:</p></td>
                    <td style='padding: 0; margin: 0;'><p>301</p></td>
                </tr>
                <tr>
                    <td style='padding: 0; margin: 0;'><p>Waktu</p></td>
                    <td style='padding: 0; margin: 0;'><p>:</p></td>
                    <td style='padding: 0; margin: 0;'><p>Senin, 07.00 - 09.00</p></td>
                </tr>
            </table>
            ";
            $this->email->message($message);
    
            if ($this->email->send()) {
                echo 'Email sent.';
            } else {
                show_error($this->email->print_debugger());
            }
        }
        
    }
?>