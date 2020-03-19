<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Emails extends ClientsController
{
	function __construct(){
		parent::__construct();
	}

	public function send_email()
	{
		// print_r($_POST); exit;
		$config = Array(
			'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com.',
            'smtp_port' => 465,
            'smtp_user' => 'gwolf.bond97@gmail.com', // change it to yours
            'smtp_pass' => 'bond4575', // change it to yours
            'smtp_crypto' => 'ssl'
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
		);

		$from_email = 'dipay@example.com';
		$to_email = $_POST['email'];

		$this->load->library('email');

		$this->email->from($from_email, 'Dipay');
		$this->email->to($to_email);
		$this->email->subject('email test');
		$this->email->message('Testing the email class');

		if($this->email->send()){
			echo "success";
		} else {
			show_error($this->email->print_debugger());
		}
	}
}