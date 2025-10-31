<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Documents extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('document_model');
	}

	public function index()
	{
		$data['categories'] = $this->document_model->get_categories_with_documents();
		$this->render_page('documents/index_view', $data, 'Pusat Dokumen (SOP)');
	}

	public function download($document_id)
	{
		$this->load->helper('download');

		$document = $this->document_model->get_document_by_id($document_id);
		if (!$document || !$document->current_version_id) {
			show_404();
			return;
		}

		$version = $this->document_model->get_version_by_id($document->current_version_id);
		if (!$version || !file_exists($version->file_path)) {
			show_error('File tidak ditemukan di server.', 404, 'File Not Found');
			return;
		}

		// Ambil konten file dan paksa unduh
		$file_content = file_get_contents($version->file_path);
		force_download($version->file_name, $file_content);
	}
}
