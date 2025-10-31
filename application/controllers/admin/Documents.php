<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Documents extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_DOCUMENTS');
		$this->load->model('document_model');
		$this->load->library('upload');
	}

	public function index()
	{
		$search = $this->input->get('search');
		$data['documents'] = $this->document_model->get_documents_with_version($search);
		$this->render_page('admin/documents/index_view', $data, 'Kelola Dokumen');
	}

	private function _handle_upload()
	{
		$config['upload_path']   = './uploads/documents/';
		$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|ppt|pptx';
		$config['max_size']      = 5120; // 5MB
		$config['encrypt_name']  = TRUE;

		if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

		$this->upload->initialize($config);

		if ($this->upload->do_upload('document_file')) {
			return $this->upload->data();
		}
		$this->session->set_flashdata('error', $this->upload->display_errors());
		return false;
	}

	public function add()
	{
		if ($this->input->post()) {
			$upload_data = $this->_handle_upload();
			if ($upload_data) {
				$this->db->trans_start();
				// 1. Insert document master
				$doc_id = $this->document_model->insert_document([
					'title' => $this->input->post('title'),
					'document_code' => $this->input->post('document_code'),
					'category_id' => $this->input->post('category_id'),
					'description' => $this->input->post('description'),
					'created_by' => $this->user_data['user_id'],
					'last_updated_by' => $this->user_data['user_id']
				]);
				// 2. Insert first version
				$version_id = $this->document_model->insert_version([
					'document_id' => $doc_id,
					'version_number' => '1.0',
					'remarks' => 'Versi awal',
					'file_name' => $upload_data['orig_name'],
					'file_path' => 'uploads/documents/' . $upload_data['file_name'],
					'file_size' => $upload_data['file_size'],
					'uploaded_by' => $this->user_data['user_id']
				]);
				// 3. Update current_version_id
				$this->document_model->update_document($doc_id, ['current_version_id' => $version_id]);
				$this->db->trans_complete();

				$this->session->set_flashdata('success', 'Dokumen berhasil ditambahkan.');
				redirect('admin/documents');
			}
		}
		$data['categories'] = $this->document_model->get_all_categories();
		$this->render_page('admin/documents/form_view', $data, 'Tambah Dokumen Baru');
	}

	public function edit($id)
	{
		$document = $this->document_model->get_document_by_id($id);
		if (!$document) {
            show_404();
        }

		if ($this->input->post()) {
			// Hanya update metadata dokumen
			$db_data = [
				'title' => $this->input->post('title'),
				'document_code' => $this->input->post('document_code'),
				'category_id' => $this->input->post('category_id'),
				'description' => $this->input->post('description'),
				'last_updated_by' => $this->user_data['user_id']
			];
			$affected_rows = $this->document_model->update_document($id, $db_data);
			if ($affected_rows > 0) {
				$this->session->set_flashdata('success', 'Metadata dokumen berhasil diperbarui.');
			} else {
				$this->session->set_flashdata('info', 'Tidak ada perubahan data.');
			}
			redirect('admin/documents/edit/' . $id);
		}

		$data['document'] = $document;
		$data['versions'] = $this->document_model->get_document_versions($id);
		$data['categories'] = $this->document_model->get_all_categories();
		$this->render_page('admin/documents/edit_view', $data, 'Edit Dokumen & Versi');
	}

	public function add_version($doc_id)
	{
		if ($this->input->post()) {
			$upload_data = $this->_handle_upload();
			if ($upload_data) {
				$this->db->trans_start();
				$version_id = $this->document_model->insert_version([
					'document_id' => $doc_id,
					'version_number' => $this->input->post('version_number'),
					'remarks' => $this->input->post('remarks'),
					'file_name' => $upload_data['orig_name'],
					'file_path' => 'uploads/documents/' . $upload_data['file_name'],
					'file_size' => $upload_data['file_size'],
					'uploaded_by' => $this->user_data['user_id']
				]);
				// Otomatis set versi baru sebagai versi aktif
				$this->document_model->update_document($doc_id, ['current_version_id' => $version_id]);
				$this->db->trans_complete();
				$this->session->set_flashdata('success', 'Versi baru berhasil diunggah.');
			}
		}
		redirect('admin/documents/edit/' . $doc_id);
	}

	public function delete($id)
	{
		$this->document_model->delete_document_and_versions($id);
		$this->session->set_flashdata('success', 'Dokumen dan semua versinya berhasil dihapus.');
		redirect('admin/documents');
	}
}
