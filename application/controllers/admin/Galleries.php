<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Galleries extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_GALLERIES');
		$this->load->model('gallery_model');
		$this->load->library('upload');
	}

	public function index()
	{
		$data['galleries'] = $this->gallery_model->get_galleries();
		$this->render_page('admin/galleries/index_view', $data, 'Kelola Galeri');
	}

	public function add()
	{
		if ($this->input->post()) {
			$data = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'event_date' => $this->input->post('event_date'),
				'created_by' => $this->user_data['user_id']
			];
			$gallery_id = $this->gallery_model->insert_gallery($data);
			$this->session->set_flashdata('success', 'Album baru berhasil dibuat. Silakan unggah foto.');
			redirect('admin/galleries/manage/' . $gallery_id);
		}
		$this->render_page('admin/galleries/add_view', [], 'Buat Album Baru');
	}

	public function manage($id)
	{
		if ($this->input->post()) { // Update metadata album
			$data = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'event_date' => $this->input->post('event_date'),
			];
			$this->gallery_model->update_gallery($id, $data);
			$this->session->set_flashdata('success', 'Detail album berhasil diperbarui.');
			redirect('admin/galleries/manage/' . $id);
		}

		$data['gallery'] = $this->gallery_model->get_gallery_with_photos($id);
		if (empty($data['gallery'])) {
            show_404();
        }

		$this->render_page('admin/galleries/manage_view', $data, 'Kelola Album: ' . $data['gallery']['title']);
	}

	public function upload_photos($gallery_id)
	{
		$config['upload_path']   = './uploads/galleries/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size']      = 2048; // 2MB
		$config['encrypt_name']  = TRUE;
		if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

		$this->upload->initialize($config);

		$count = count($_FILES['photos']['name']);
		$upload_data = [];

		for ($i = 0; $i < $count; $i++) {
			$_FILES['photo']['name']     = $_FILES['photos']['name'][$i];
			$_FILES['photo']['type']     = $_FILES['photos']['type'][$i];
			$_FILES['photo']['tmp_name'] = $_FILES['photos']['tmp_name'][$i];
			$_FILES['photo']['error']    = $_FILES['photos']['error'][$i];
			$_FILES['photo']['size']     = $_FILES['photos']['size'][$i];

			if ($this->upload->do_upload('photo')) {
				$file_data = $this->upload->data();
				$upload_data[] = [
					'gallery_id' => $gallery_id,
					'file_path'  => 'uploads/galleries/' . $file_data['file_name'],
				];
			}
		}

		if ($upload_data !== []) {
			$this->gallery_model->insert_photos($upload_data);
			$this->session->set_flashdata('success', count($upload_data) . ' foto berhasil diunggah.');
		} else {
			$this->session->set_flashdata('error', 'Gagal mengunggah foto. Pastikan format dan ukuran file sesuai.');
		}
		redirect('admin/galleries/manage/' . $gallery_id);
	}

	public function delete_photo($gallery_id, $photo_id)
	{
		$this->gallery_model->delete_photo($photo_id);
		$this->session->set_flashdata('success', 'Foto berhasil dihapus.');
		redirect('admin/galleries/manage/' . $gallery_id);
	}

	public function delete($id)
	{
		$this->gallery_model->delete_gallery_and_photos($id);
		$this->session->set_flashdata('success', 'Album dan semua fotonya berhasil dihapus.');
		redirect('admin/galleries');
	}
}
