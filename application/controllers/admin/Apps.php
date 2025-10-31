<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Apps extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_APPS');
		$this->load->model('App_launcher_model', 'app_model');
	}

	public function index()
	{
		$data['apps'] = $this->app_model->get_all_for_admin();
		$this->render_page('admin/apps/index_view', $data, 'Kelola Aplikasi');
	}

	public function add()
	{
		if ($this->input->post()) {
			$data = $this->_get_form_data();
			$this->app_model->insert($data);
			$this->session->set_flashdata('success', 'Aplikasi baru berhasil ditambahkan.');
			redirect('admin/apps');
		}
		$this->render_page('admin/apps/form_view', [], 'Tambah Aplikasi Baru');
	}

	public function edit($id)
	{
		$app = $this->app_model->get_by_id($id);
		if (!$app) {
            show_404();
        }

		if ($this->input->post()) {
			$data = $this->_get_form_data();
			$this->app_model->update($id, $data);
			$this->session->set_flashdata('success', 'Data aplikasi berhasil diperbarui.');
			redirect('admin/apps');
		}
		$data['app'] = $app;
		$this->render_page('admin/apps/form_view', $data, 'Edit Aplikasi');
	}

	public function delete($id)
	{
		$this->app_model->delete($id);
		$this->session->set_flashdata('success', 'Aplikasi berhasil dihapus.');
		redirect('admin/apps');
	}

	private function _get_form_data()
	{
		return [
			'app_name' => $this->input->post('app_name'),
			'description' => $this->input->post('description'),
			'category' => $this->input->post('category'),
			'url' => $this->input->post('url'),
			'version' => $this->input->post('version'),
			'status' => $this->input->post('status'),
			'developer' => $this->input->post('developer'),
			'implementation_info' => $this->input->post('implementation_info'),
			'icon' => $this->input->post('icon'),
			'is_active' => $this->input->post('is_active') ?? 0
		];
	}
}
