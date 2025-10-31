<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Roles extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('VIEW_ROLE_MANAGEMENT');
		$this->load->model('Role_model', 'role_model');
		$this->config->load('permissions'); // Memuat file konfigurasi permissions
	}

	public function index()
	{
		$data['roles'] = $this->role_model->get_roles();
		$this->render_page('admin/roles/index_view', $data, 'Kelola Peran');
	}

	public function add()
	{
		if ($this->input->post()) {
			$data = [
				'role_name' => $this->input->post('role_name'),
				'description' => $this->input->post('description'),
				'is_active' => $this->input->post('is_active') ?? 0
			];
			$role_id = $this->role_model->insert_role($data);
			$this->session->set_flashdata('success', 'Peran baru berhasil dibuat.');
			redirect('admin/roles/edit/' . $role_id);
		}
		$this->render_page('admin/roles/form_view', [], 'Tambah Peran Baru');
	}

	public function edit($id)
	{
		$role = $this->role_model->get_role_by_id($id);
		if (!$role) {
            show_404();
        }

		if ($this->input->post('update_role')) {
			$data = [
				'role_name' => $this->input->post('role_name'),
				'description' => $this->input->post('description'),
				'is_active' => $this->input->post('is_active') ?? 0
			];
			$this->role_model->update_role($id, $data);

			$permissions = $this->input->post('permissions') ?? [];
			$this->role_model->update_role_permissions($id, $permissions);

			$this->session->set_flashdata('success', 'Peran dan hak akses berhasil diperbarui.');
			redirect('admin/roles/edit/' . $id);
		}

		$data['role'] = $role;
		$data['all_permissions'] = $this->config->item('permissions');
		$data['role_permissions'] = $this->role_model->get_role_permissions($id);
		$this->render_page('admin/roles/form_view', $data, 'Edit Peran: ' . $role->role_name);
	}

	public function delete($id)
	{
		if ($this->role_model->delete_role($id)) {
			$this->session->set_flashdata('success', 'Peran berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error', 'Gagal menghapus peran. Pastikan tidak ada pengguna yang masih menggunakan peran ini.');
		}
		redirect('admin/roles');
	}
}
