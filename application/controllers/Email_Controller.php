<?php
class Email_Controller extends CI_Controller {

    public function send_email_with_plesk() {
        $this->load->library('email');

        // Email configuration
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'penjadwalanpa.pocari.id', // Typically, mail.yourdomain.com
            'smtp_port' => 465, // Standard port for TLS
            'smtp_user' => 'no-reply@penjadwalanpa.pocari.id', // Your Plesk email address
            'smtp_pass' => 'penjadwalanpaemail', // Your Plesk email password
            'mailtype'  => 'html', 
            'charset'   => 'utf-8', // or 'iso-8859-1'
            'wordwrap'  => TRUE,
            'smtp_crypto' => 'tls' // Use 'ssl' if using port 465
        );

        $this->email->initialize($config);

        $this->email->from('no-reply@penjadwalanpa.pocari.id', 'Politeknik Caltex Riau');
        $this->email->to('lunarknight20@gmail.com'); 
        $this->email->subject('Sidang Akhir Mahasiswa [Rezky Kurniawan]');

        // Compose the email body with HTML
        $message = '
            <html>
            <head>
                <title>Sidang Akhir Mahasiswa</title>
            </head>
            <body>
                <h1 style="color: #3498db;">Hello, User!</h1>
                <p>This is a sample email with <strong>HTML formatting</strong>.</p>
                <p>You can use various HTML tags to style your email content:</p>
                <ul>
                    <li>Paragraphs</li>
                    <li><strong>Bold Text</strong></li>
                    <li><em>Italic Text</em></li>
                    <li><a href="https://www.example.com">Links</a></li>
                    <li>And more...</li>
                </ul>
                <p>Best regards,</p>
                <p><em>Your Company</em></p>
            </body>
            </html>
        ';

        $this->email->message($message);

        if ($this->email->send()) {
            echo 'Email sent successfully.';
        } else {
            echo $this->email->print_debugger();
        }
    }
}


?>