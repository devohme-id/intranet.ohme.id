<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Forum extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_FORUM_CATEGORIES');
		$this->load->model('Forum_model', 'forum_model');
	}

	public function index()
	{
		$data['categories'] = $this->forum_model->get_all_categories();
		$this->render_page('admin/forum/index_view', $data, 'Kelola Kategori Forum');
	}

	public function add()
	{
		if ($this->input->post()) {
			$data = [
				'category_name' => $this->input->post('category_name'),
				'description' => $this->input->post('description'),
				'is_active' => $this->input->post('is_active') ?? 0
			];
			$this->forum_model->insert_category($data);
			$this->session->set_flashdata('success', 'Kategori baru berhasil ditambahkan.');
			redirect('admin/forum');
		}
		$this->render_page('admin/forum/form_view', [], 'Tambah Kategori Baru');
	}

	public function edit($id)
	{
		$category = $this->forum_model->get_category_by_id($id);
		if (!$category) {
            show_404();
        }

		if ($this->input->post()) {
			$data = [
				'category_name' => $this->input->post('category_name'),
				'description' => $this->input->post('description'),
				'is_active' => $this->input->post('is_active') ?? 0
			];
			$this->forum_model->update_category($id, $data);
			$this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
			redirect('admin/forum');
		}
		$data['category'] = $category;
		$this->render_page('admin/forum/form_view', $data, 'Edit Kategori');
	}

	public function delete($id)
	{
		// Peringatan: Menghapus kategori bisa menyebabkan topik di dalamnya menjadi "yatim".
		// Implementasi profesional seharusnya menonaktifkan kategori atau memindahkan topiknya.
		$this->forum_model->delete_category($id);
		$this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
		redirect('admin/forum');
	}
}
