<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('VIEW_USER_MANAGEMENT');
		$this->load->model('User_model', 'user_model');
		$this->load->model('Admin_Employee_model', 'employee_model');
		$this->load->library('pagination');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$search = $this->input->get('search', TRUE);
		$config['base_url'] = site_url('admin/users/index');
		$config['total_rows'] = $this->user_model->count_users($search);
		$config['per_page'] = 15;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		// ... (konfigurasi styling paginasi) ...
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? (int)$this->uri->segment(4) : 1;
		$offset = ($page > 1) ? ($page - 1) * $config['per_page'] : 0;

		$data['users'] = $this->user_model->get_users($config['per_page'], $offset, $search);
		$data['pagination'] = $this->pagination->create_links();
		$data['search_term'] = $search;

		// Kirim data hak akses ke view
		$data['can_add'] = $this->has_permission('ADD_USERS');
		$data['can_edit'] = $this->has_permission('EDIT_USERS');
		$data['can_delete'] = $this->has_permission('DELETE_USERS');

		$this->render_page('admin/users/index_view', $data, 'Kelola Pengguna');
	}

	public function add()
	{
		$this->check_permission('ADD_USERS');

		$this->form_validation->set_rules('employee_id', 'Karyawan', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]|is_unique[m_users.username]');
		$this->form_validation->set_rules('password', 'Kata Sandi', 'required|min_length[6]');
		$this->form_validation->set_rules('confirm_password', 'Konfirmasi Kata Sandi', 'required|matches[password]');
		$this->form_validation->set_rules('role_id', 'Peran', 'required|is_natural_no_zero');

		if ($this->form_validation->run() == FALSE) {
			$data['employees'] = $this->user_model->get_employees_without_user_account();
			$data['roles'] = $this->user_model->get_all_roles();
			$this->render_page('admin/users/form_view', $data, 'Tambah Pengguna Baru');
		} else {
			$employee_id = $this->input->post('employee_id');
			$employee = $this->employee_model->get_employee_by_id($employee_id);

			$data = [
				'employee_id' => $employee_id,
				'username' => $this->input->post('username'),
				'password_hash' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
				'full_name' => $employee->full_name,
				'role_id' => $this->input->post('role_id'),
				'is_active' => $this->input->post('is_active') ?? 0
			];
			$this->user_model->insert_user($data);
			$this->session->set_flashdata('success', 'Pengguna baru berhasil dibuat.');
			redirect('admin/users');
		}
	}

	public function edit($id)
	{
		$this->check_permission('EDIT_USERS');
		$user = $this->user_model->get_user_by_id($id);
		if (!$user) {
            show_404();
        }

		$original_username = $user->username;
		$is_unique_rule = ($this->input->post('username') != $original_username) ? '|is_unique[m_users.username]' : '';

		$this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]' . $is_unique_rule);
		$this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim');
		$this->form_validation->set_rules('role_id', 'Peran', 'required|is_natural_no_zero');

		if (!empty($this->input->post('password'))) {
			$this->form_validation->set_rules('password', 'Kata Sandi', 'min_length[6]');
			$this->form_validation->set_rules('confirm_password', 'Konfirmasi Kata Sandi', 'matches[password]');
		}

		if ($this->form_validation->run() == FALSE) {
			$data['user'] = $user;
			$data['roles'] = $this->user_model->get_all_roles();
			$this->render_page('admin/users/form_view', $data, 'Edit Pengguna: ' . $user->full_name);
		} else {
			$data = [
				'username' => $this->input->post('username'),
				'full_name' => $this->input->post('full_name'),
				'role_id' => $this->input->post('role_id'),
				'is_active' => $this->input->post('is_active') ?? 0
			];
			// Hanya update password jika diisi
			if (!empty($this->input->post('password'))) {
				$data['password_hash'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
			}

			if ($this->user_model->update_user($id, $data) > 0) {
				$this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');
			} else {
				$this->session->set_flashdata('info', 'Tidak ada perubahan data yang disimpan.');
			}
			redirect('admin/users');
		}
	}

	public function delete($id)
	{
		$this->check_permission('DELETE_USERS');

		if ($this->input->method() !== 'post') {
			show_error('Metode tidak diizinkan.', 405);
		}

		// Mencegah user menghapus akunnya sendiri
		if ($id == $this->session->userdata('user_id')) {
			$this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
			redirect('admin/users');
		}

		if ($this->user_model->delete_user($id)) {
			$this->session->set_flashdata('success', 'Pengguna berhasil dihapus (dinonaktifkan).');
		} else {
			$this->session->set_flashdata('error', 'Gagal menghapus pengguna.');
		}
		redirect('admin/users');
	}

	public function check_username_ajax()
	{
		$username = $this->input->post('username');
		$exclude_id = $this->input->post('exclude_id') ?? 0;

		$is_exists = $this->user_model->is_username_exists($username, $exclude_id);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(['exists' => $is_exists]));
	}
}
