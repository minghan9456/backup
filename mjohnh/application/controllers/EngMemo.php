<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'models/inc.php';

class EngMemo extends CI_Controller {

	public function index() {
		$view_data = array();
		$view_data['THISHOST'] = THISHOST;
	
		$member = $this->input->get('member');
		if (isset($member) && $member) {
			MemoModel::linkMemoDB();
			$view_data['daily_word'] = MemoModel::randomGetWord();

			$this->load->view('memo', $view_data);
		}
		else {
			$this->load->view('login', $view_data);
		}
	}

	public function login() {
		$rtn = array('status' => false);
		$fbid = $this->input->post('fbid');
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$_now = date("Y-m-d h:i:s");

		MemberModel::linkMemoDB();
		$status = MemberModel::login($fbid, $name, $email, $_now);
		if ($status) {
			$rtn['status'] = true;
		}

		echo json_encode($rtn);

	}

	public function newWord() {
		$view_data = array();
		$view_data['THISHOST'] = THISHOST;
		$view_data['insert_rtn'] = "";
		$input_eng_word = ($_eng = $this->input->post('input_eng_word')) ? $_eng : "";
		$input_chi_means = ($_chi = $this->input->post('input_chi_means')) ? $_chi : "";

		if ($input_eng_word && $input_chi_means) {
			$_now = date("Y-m-d h:i:s");
			$input_chi_means = explode(",", $input_chi_means);

			MemoModel::linkMemoDB();
			$ret = MemoModel::insert($input_eng_word, $input_chi_means, $_now);

			if ($ret) {
				if ($ret == DBERR_DUPLICATE) {
					$view_data['insert_rtn'] = "Duplicate error.";
				}
				else {
					$view_data['insert_rtn'] = "Unknown error.";
				}
			}
			else {
				$view_data['insert_rtn'] = "Insert success.";
			}
		}
		$view_data['daily_word'] = MemoModel::randomGetWord();
		$this->load->view('memo', $view_data);
	}
}
