<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('security');
	}

	public function login()
	{
		if ($this->session->userdata('is_logged_in')) {
			redirect('dashboard');
		}

		if ($this->input->post()) {
			$username = $this->input->post('username', TRUE);
			$password = $this->input->post('password', TRUE);

			$user = $this->auth_model->get_user_by_username($username);

			if ($user && password_verify($password, $user->password_hash)) {

				$permissions = $this->auth_model->get_user_permissions($user->role_id);

				// **KUNCI PERBAIKAN**
				// Menambahkan 'employee_id' ke dalam data sesi
				$session_data = [
					'is_logged_in' => TRUE,
					'user_data'    => [
						'user_id'     => $user->user_id,
						'employee_id' => $user->employee_id, // Ditambahkan
						'username'    => $user->username,
						'full_name'   => $user->full_name,
						'role_id'     => $user->role_id,
						'role_name'   => $user->role_name,
						'profile_picture_url' => $user->profile_picture_url // Ini yang paling penting
					],
					'permissions'  => $permissions
				];

				$this->session->set_userdata($session_data);

				// Redirect ke URL yang dituju sebelumnya jika ada
				if ($this->session->has_userdata('redirect_url')) {
					$redirect_url = $this->session->userdata('redirect_url');
					$this->session->unset_userdata('redirect_url');
					redirect($redirect_url);
				} else {
					redirect('dashboard');
				}
			} else {
				$this->session->set_flashdata('error', 'Username atau Password salah!');
				redirect('auth/login');
			}
		}

		$data['csrf'] = [
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		];
		$this->load->view('auth/login_view', $data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('auth/login');
	}
}
