<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Forum extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('Forum_model', 'forum_model');
	}

	public function index()
	{
		$data['categories'] = $this->forum_model->get_all_categories();
		foreach ($data['categories'] as &$category) {
			$category['threads'] = $this->forum_model->get_threads_by_category($category['category_id']);
		}
		$this->render_page('forum/index_view', $data, 'Forum Diskusi');
	}

	public function thread($thread_id)
	{
		$thread = $this->forum_model->get_thread_by_id($thread_id);
		if (!$thread) {
            show_404();
        }

		if ($this->input->post('content')) {
			$this->forum_model->create_post([
				'thread_id' => $thread_id,
				'user_id' => $this->user_data['user_id'],
				'content' => $this->input->post('content')
			]);
			$this->session->set_flashdata('success', 'Balasan Anda berhasil dikirim.');
			redirect('forum/thread/' . $thread_id);
		}

		$data['thread'] = $thread;
		$data['posts'] = $this->forum_model->get_posts_by_thread($thread_id);
		$this->render_page('forum/thread_view', $data, $thread->title);
	}

	public function create($category_id)
	{
		if ($this->input->post()) {
			$thread_id = $this->forum_model->create_thread([
				'category_id' => $category_id,
				'user_id' => $this->user_data['user_id'],
				'title' => $this->input->post('title'),
				'content' => $this->input->post('content')
			]);
			$this->session->set_flashdata('success', 'Topik baru berhasil dibuat.');
			redirect('forum/thread/' . $thread_id);
		}
		$this->render_page('forum/create_thread_view', ['category_id' => $category_id], 'Buat Topik Baru');
	}
}
