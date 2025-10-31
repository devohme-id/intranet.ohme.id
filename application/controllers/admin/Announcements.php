<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Announcements extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_ANNOUNCEMENTS');
		$this->load->model('announcement_model');
	}

	public function index()
	{
		$search = $this->input->get('search');
		$data['announcements'] = $this->announcement_model->get_all($search);
		$this->render_page('admin/announcements/index_view', $data, 'Kelola Pengumuman');
	}

	public function add()
	{
		if ($this->input->post()) {
			$db_data = [
				'title' => $this->input->post('title'),
				'content' => $this->input->post('content'),
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date'),
				'is_active' => $this->input->post('is_active') ?? 0,
				'created_by' => $this->user_data['user_id']
			];
			$this->announcement_model->insert($db_data);
			$this->session->set_flashdata('success', 'Pengumuman berhasil ditambahkan.');
			redirect('admin/announcements');
		}
		$this->render_page('admin/announcements/form_view', [], 'Tambah Pengumuman Baru');
	}

	public function edit($id)
	{
		$announcement = $this->announcement_model->get_by_id($id);
		if (!$announcement) {
            show_404();
        }

		if ($this->input->post()) {
			$db_data = [
				'title' => $this->input->post('title'),
				'content' => $this->input->post('content'),
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date'),
				'is_active' => $this->input->post('is_active') ?? 0,
			];
			$this->announcement_model->update($id, $db_data);
			$this->session->set_flashdata('success', 'Pengumuman berhasil diperbarui.');
			redirect('admin/announcements');
		}
		$data['announcement'] = $announcement;
		$this->render_page('admin/announcements/form_view', $data, 'Edit Pengumuman');
	}

	public function delete($id)
	{
		$this->announcement_model->delete($id);
		$this->session->set_flashdata('success', 'Pengumuman berhasil dihapus.');
		redirect('admin/announcements');
	}
}
