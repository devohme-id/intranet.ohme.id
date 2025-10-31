<?php
defined('BASEPATH') || exit('No direct script access allowed');

class News extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('news_model');
		$this->load->library('pagination'); // Memuat library pagination
	}

	public function index()
	{
		$search = $this->input->get('search', TRUE);

		// Konfigurasi Paginasi
		$config['base_url'] = site_url('news/index');
		$config['total_rows'] = $this->news_model->count_published_articles($search);
		$config['per_page'] = 6; // 6 berita per halaman
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE; // Penting untuk menjaga parameter pencarian

		// Styling Paginasi (menggunakan konfigurasi yang sudah solved)
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

		$page = ($this->uri->segment(3)) ? (int)$this->uri->segment(3) : 1;
		$offset = ($page > 1) ? ($page - 1) * $config['per_page'] : 0;

		$data['articles'] = $this->news_model->get_paginated_published_articles($config['per_page'], $offset, $search);
		$data['pagination'] = $this->pagination->create_links();
		$data['search_term'] = $search;
		$data['total_rows'] = $config['total_rows'];

		$this->render_page('news/index_view', $data, 'Berita Perusahaan');
	}

	public function read($slug = '')
	{
		$article = $this->news_model->get_article_by_slug($slug);
		if (!$article) {
			show_404();
			return;
		}
		$data['article'] = $article;
		$this->render_page('news/read_view', $data, $article->title);
	}
}
