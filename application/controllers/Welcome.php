<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('welcome_model');
	}


	public function index()
	{
		$data['content'] = $this->welcome_model->getContent('');
		if ($this->input->post('find')) {
			$data['content'] = $this->welcome_model->getContent($this->input->post('stock'));
			$this->load->view('template', $data);
		} elseif ($this->input->post('downloadStock')) {
			$this->welcome_model->downloadStock1($this->input->post('stock'));
			echo "Done";
		} else {
			$this->load->view('template', $data);
		}
	}
}
