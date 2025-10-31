<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * MY_Controller.php
 *
 * Base controller untuk aplikasi ini.
 * Diperbarui untuk kompatibilitas dengan PHP 8.2 dengan mendeklarasikan
 * semua properti yang dimuat secara dinamis oleh CodeIgniter.
 */
class MY_Controller extends CI_Controller
{

	// --- Deklarasi Properti untuk Kompatibilitas PHP 8.2 ---

	// Properti inti yang dimuat oleh CI_Controller
	public $benchmark;
	public $hooks;
	public $config;
	public $log;
	public $utf8;
	public $uri;
	public $router;
	public $exceptions;
	public $output;
	public $security;
	public $input;
	public $lang;
	public $load;

	// Properti yang umum dimuat oleh CI_Loader
	public $db;
	public $session;
	public $form_validation;
	public $user_agent;
	// Tambahkan library lain yang Anda autoload di sini jika ada

	// Properti untuk semua model dalam aplikasi
	public $admin_employee_model;
	public $announcement_model;
	public $app_launcher_model;
	public $auth_model;
	public $companyprofile_model;
	public $dashboard_model;
	public $document_model;
	public $employee_model;
	public $event_model;
	public $forum_model;
	public $gallery_model;
	public $leaverequest_model;
	public $menu_model;
	public $news_model;
	public $payslip_model;
	public $production_order_model;
	public $project_model;
	public $role_model;
	public $ticket_model;
	public $user_model;
	public $wiki_model;

	// Properti kustom aplikasi Anda (dipertahankan dari file asli)
	protected $user_data;
	protected $permissions;

	// --- Akhir Deklarasi Properti ---

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('menu_model');

		$exceptions = ['auth'];
		if (!in_array(strtolower($this->router->fetch_class()), $exceptions)) {
			$this->verify_login();
		}
	}

	private function verify_login()
	{
		if (!$this->session->userdata('is_logged_in')) {
			$this->session->set_userdata('redirect_url', current_url());
			redirect('auth/login');
		}
		$this->user_data = $this->session->userdata('user_data');
		$this->permissions = $this->session->userdata('permissions');
	}

	protected function has_permission($key)
	{
		if (isset($this->user_data['role_id']) && $this->user_data['role_id'] == 1) {
			return true;
		}
		return !empty($this->permissions) && in_array($key, $this->permissions);
	}

	protected function check_permission($key)
	{
		if (!$this->has_permission($key)) {
			show_error('Anda tidak memiliki hak akses untuk melihat halaman ini.', 403, 'Akses Ditolak');
		}
	}

	protected function render_page($view, $data = [], $page_title = 'Dashboard')
	{
		// Logika render_page Anda dipertahankan
		$current_uri = $this->uri->uri_string();
		if (empty($current_uri)) {
			$current_uri = 'dashboard';
		}

		$view_data = [
			'menus' => $this->menu_model->get_accessible_menus($this->permissions, $this->user_data['role_id']),
			'user_info' => $this->user_data,
			'page_title' => $page_title,
			'current_uri' => $current_uri,
			'csrf' => [
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			]
		];

		$data = array_merge($data, $view_data);

		$this->load->view('template/header', $data);
		$this->load->view('template/sidebar', $data);
		$this->load->view($view, $data);
		$this->load->view('template/footer', $data);
	}
}
