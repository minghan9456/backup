<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yuji extends CI_Controller {
	public function index()
	{
		print_r('yuji');exit;
		$this->load->view('welcome_message');
	}
}
