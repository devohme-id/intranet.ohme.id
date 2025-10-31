<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Wiki extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_WIKI_ARTICLES');
		$this->load->model('Wiki_model', 'wiki_model');
		$this->load->library('pagination'); // Memuat library paginasi
	}

	public function index()
	{
		$search = $this->input->get('search', TRUE);

		// Konfigurasi Paginasi
		$config['base_url'] = site_url('admin/wiki/index');
		$config['total_rows'] = $this->wiki_model->count_articles($search);
		$config['per_page'] = 15;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;

		// Styling Paginasi dengan TailwindCSS
		$config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="inline-flex -space-x-px text-sm">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['first_link'] = 'Awal';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Akhir';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" aria-current="page" class="z-10 py-2 px-3 leading-tight text-white bg-blue-600 border border-blue-600 hover:bg-blue-700 hover:text-white">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = ['class' => 'py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'];

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(4)) ? (int)$this->uri->segment(4) : 1;
		$offset = ($page > 1) ? ($page - 1) * $config['per_page'] : 0;

		$data['articles'] = $this->wiki_model->get_articles_with_category($config['per_page'], $offset, $search);
		$data['pagination'] = $this->pagination->create_links();
		$data['search_term'] = $search;
		$data['start_no'] = $offset;

		$this->render_page('admin/wiki/index_view', $data, 'Kelola Pusat Pengetahuan');
	}

	public function add()
	{
		if ($this->input->post()) {
			$this->db->trans_start();
			$article_id = $this->wiki_model->insert_article([
				'category_id' => $this->input->post('category_id'),
				'title' => $this->input->post('title'),
				'slug' => url_title($this->input->post('title'), 'dash', TRUE),
				'created_by' => $this->user_data['user_id'],
				'last_updated_by' => $this->user_data['user_id']
			]);
			$revision_id = $this->wiki_model->insert_revision([
				'article_id' => $article_id,
				'content' => $this->input->post('content'),
				'edit_summary' => 'Versi awal dibuat',
				'edited_by' => $this->user_data['user_id']
			]);
			$this->wiki_model->update_article($article_id, ['current_revision_id' => $revision_id]);
			$this->db->trans_complete();

			$this->session->set_flashdata('success', 'Artikel baru berhasil dibuat.');
			redirect('admin/wiki');
		}
		$data['categories'] = $this->wiki_model->get_all_categories();
		$this->render_page('admin/wiki/form_view', $data, 'Tambah Artikel Baru');
	}

	public function edit($id)
	{
		$article = $this->wiki_model->get_article_by_id($id);
		if (!$article) {
            show_404();
        }

		if ($this->input->post()) {
			$this->db->trans_start();
			$this->wiki_model->update_article($id, [
				'category_id' => $this->input->post('category_id'),
				'title' => $this->input->post('title'),
				'slug' => url_title($this->input->post('title'), 'dash', TRUE),
				'last_updated_by' => $this->user_data['user_id']
			]);
			$revision_id = $this->wiki_model->insert_revision([
				'article_id' => $id,
				'content' => $this->input->post('content'),
				'edit_summary' => $this->input->post('edit_summary'),
				'edited_by' => $this->user_data['user_id']
			]);
			$this->wiki_model->update_article($id, ['current_revision_id' => $revision_id]);
			$this->db->trans_complete();

			$this->session->set_flashdata('success', 'Artikel berhasil diperbarui.');
			redirect('admin/wiki');
		}
		$data['article'] = $article;
		$data['categories'] = $this->wiki_model->get_all_categories();
		$this->render_page('admin/wiki/form_view', $data, 'Edit Artikel');
	}
}
