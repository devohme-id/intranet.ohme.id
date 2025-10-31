<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Wiki_categories extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_WIKI_CATEGORIES');
		$this->load->model('Wiki_model', 'wiki_model');
	}

	public function index()
	{
		$data['categories'] = $this->wiki_model->get_all_categories();
		$this->render_page('admin/wiki_categories/index_view', $data, 'Kelola Kategori Wiki');
	}

	public function add()
	{
		if ($this->input->post()) {
			$data = [
				'category_name' => $this->input->post('category_name'),
				'description' => $this->input->post('description'),
				'is_active' => $this->input->post('is_active') ?? 0
			];
			$this->wiki_model->insert_category($data);
			$this->session->set_flashdata('success', 'Kategori baru berhasil ditambahkan.');
			redirect('admin/wiki_categories');
		}
		$this->render_page('admin/wiki_categories/form_view', [], 'Tambah Kategori Baru');
	}

	public function edit($id)
	{
		$category = $this->wiki_model->get_category_by_id($id);
		if (!$category) {
            show_404();
        }

		if ($this->input->post()) {
			$data = [
				'category_name' => $this->input->post('category_name'),
				'description' => $this->input->post('description'),
				'is_active' => $this->input->post('is_active') ?? 0
			];
			$this->wiki_model->update_category($id, $data);
			$this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
			redirect('admin/wiki_categories');
		}
		$data['category'] = $category;
		$this->render_page('admin/wiki_categories/form_view', $data, 'Edit Kategori');
	}

	public function delete($id)
	{
		if ($this->wiki_model->delete_category($id)) {
			$this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error', 'Gagal menghapus. Kategori ini masih digunakan oleh beberapa artikel.');
		}
		redirect('admin/wiki_categories');
	}
}
