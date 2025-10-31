<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Anda bisa langsung menggunakan $this->load karena sudah diwariskan.
		$this->load->model('dashboard_model');
	}

	public function index()
	{
		// Mengambil semua data yang diperlukan untuk dashboard
		$data['announcements'] = $this->dashboard_model->get_active_announcements();
		$data['events'] = $this->dashboard_model->get_upcoming_events();
		$data['birthdays'] = $this->dashboard_model->get_birthdays_this_month();
		$data['new_employees'] = $this->dashboard_model->get_new_employees();
		$data['latest_gallery'] = $this->dashboard_model->get_latest_gallery();
		$data['active_projects'] = $this->dashboard_model->get_my_active_projects($this->user_data['user_id']);
		$data['network_status_widget'] = $this->load->view('widgets/network_status_widget', NULL, TRUE);

		// Render halaman menggunakan method dari MY_Controller
		$this->render_page('dashboard_view', $data, 'Dashboard');
	}
}
