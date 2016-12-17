<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{		
		$num = 8;
/*
		0110
		0100
		0110
*/
		//$num1 = $num && 4;
		$num2 = $num | 4;
		//var_dump($num1);
		var_dump($num2);exit;
		if ($i == 1 && $a == 2) {
		}
		//$this->load->view('memo', $data);
	}
}
