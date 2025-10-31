<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Apps extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('App_launcher_model', 'app_model');
	}

	public function index()
	{
		$data['apps_grouped'] = $this->app_model->get_apps_grouped();
		$this->render_page('apps/index_view', $data, 'Aplikasi & Layanan');
	}
}
