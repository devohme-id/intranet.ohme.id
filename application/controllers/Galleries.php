<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Galleries extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('gallery_model');
	}

	public function index()
	{
		$data['galleries'] = $this->gallery_model->get_galleries();
		$this->render_page('galleries/index_view', $data, 'Galeri Foto');
	}

	public function view($id)
	{
		$data['gallery'] = $this->gallery_model->get_gallery_with_photos($id);
		if (empty($data['gallery'])) {
			show_404();
		}
		$this->render_page('galleries/detail_view', $data, $data['gallery']['title']);
	}
}
