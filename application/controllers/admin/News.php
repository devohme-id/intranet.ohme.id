<?php
defined('BASEPATH') || exit('No direct script access allowed');

class News extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_NEWS');
		$this->load->model('news_model');
		$this->load->helper('text');
	}

	public function index()
	{
		$search = $this->input->get('search');
		$data['articles'] = $this->news_model->get_articles($search);
		$this->render_page('admin/news/index_view', $data, 'Kelola Berita');
	}

	public function add()
	{
		if ($this->input->post()) {
			$title = $this->input->post('title');
			$db_data = [
				'title' => $title,
				'slug' => url_title(strtolower($title)),
				'content' => $this->input->post('content'),
				'status' => $this->input->post('status'),
				'published_at' => ($this->input->post('status') == 'published') ? date('Y-m-d H:i:s') : null,
				'author_id' => $this->user_data['user_id']
			];
			$this->news_model->insert_article($db_data);
			$this->session->set_flashdata('success', 'Berita berhasil ditambahkan.');
			redirect('admin/news');
		}

		// **KUNCI PERBAIKAN**
		// Mengubah argumen kedua dari 'null' menjadi array kosong '[]'
		// untuk mencegah error "array_merge()".
		$this->render_page('admin/news/form_view', [], 'Tambah Berita Baru');
	}

	public function edit($id)
	{
		$article = $this->news_model->get_article_by_id($id);
		if (!$article) {
            show_404();
        }

		if ($this->input->post()) {
			$title = $this->input->post('title');
			$db_data = [
				'title' => $title,
				'slug' => url_title(strtolower($title)),
				'content' => $this->input->post('content'),
				'status' => $this->input->post('status'),
			];
			if ($this->input->post('status') == 'published' && $article->status != 'published') {
				$db_data['published_at'] = date('Y-m-d H:i:s');
			}
			$this->news_model->update_article($id, $db_data);
			$this->session->set_flashdata('success', 'Berita berhasil diperbarui.');
			redirect('admin/news');
		}
		$data['article'] = $article;
		$this->render_page('admin/news/form_view', $data, 'Edit Berita');
	}

	public function delete($id)
	{
		$this->news_model->delete_article($id);
		$this->session->set_flashdata('success', 'Berita berhasil dihapus.');
		redirect('admin/news');
	}
}
