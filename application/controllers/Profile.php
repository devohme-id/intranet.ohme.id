<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Profile extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		// Cek login sudah dilakukan di MY_Controller
		$this->load->model('User_model');
		$this->load->library('form_validation');
		$this->load->library('upload');
	}

	public function index()
	{
		// **PERBAIKAN**: Menggunakan user_id dari properti yang sudah disiapkan MY_Controller
		$user_id = $this->user_data['user_id'];

		if ($this->input->method() === 'post') {
			$this->process_password_update($user_id);
		} else {
			$this->display_profile_page($user_id);
		}
	}

	private function display_profile_page($user_id)
	{
		$data['user'] = (object) $this->user_data;

		if (!empty($data['user']->employee_id)) {
			$this->load->model('Admin_Employee_model');
			$data['employee'] = $this->Admin_Employee_model->get_employee_by_id($data['user']->employee_id);
		} else {
			$data['employee'] = null;
		}

		$this->render_page('profile/index_view', $data, 'Profil Saya');
	}

	private function process_password_update($user_id)
	{
		$this->form_validation->set_rules('current_password', 'Kata Sandi Saat Ini', 'required');
		$this->form_validation->set_rules('new_password', 'Kata Sandi Baru', 'required|min_length[6]');
		$this->form_validation->set_rules('confirm_password', 'Konfirmasi Kata Sandi', 'required|matches[new_password]');

		if ($this->form_validation->run() === FALSE) {
			$this->display_profile_page($user_id);
			return;
		}

		$user_from_db = $this->User_model->get_user_by_id($user_id);

		if (!$user_from_db) {
			$this->session->set_flashdata('error', 'Gagal memuat data pengguna untuk verifikasi.');
			redirect('profile');
			return;
		}

		if (password_verify($this->input->post('current_password'), $user_from_db->password_hash)) {
			$new_password_hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT);
			$this->User_model->update_user($user_id, ['password_hash' => $new_password_hash]);
			$this->session->set_flashdata('success', 'Kata sandi Anda berhasil diperbarui.');
		} else {
			$this->session->set_flashdata('error', 'Kata sandi saat ini yang Anda masukkan salah.');
		}

		redirect('profile');
	}

	public function upload_photo()
	{
		// **PERBAIKAN UTAMA**: Mengambil user_id dari properti $this->user_data yang andal
		$user_id = $this->user_data['user_id'];

		$user_data_from_db = $this->User_model->get_user_by_id($user_id);

		if (!$user_data_from_db) {
			$this->session->set_flashdata('error', 'Gagal memuat data pengguna untuk pembaruan.');
			redirect('profile');
			return;
		}

		$upload_path = './uploads/profile_pictures/';
		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0777, TRUE);
		}

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size'] = 2048;
		$config['encrypt_name'] = TRUE;

		$this->upload->initialize($config);

		if (!$this->upload->do_upload('profile_picture')) {
			$this->session->set_flashdata('error', $this->upload->display_errors('', ''));
		} else {
			$upload_data = $this->upload->data();
			$file_path = 'uploads/profile_pictures/' . $upload_data['file_name'];

			if ($this->User_model->update_user($user_id, ['profile_picture_url' => $file_path]) > 0) {
				$old_photo = $user_data_from_db->profile_picture_url;
				if (!empty($old_photo) && file_exists('./' . $old_photo)) {
					unlink('./' . $old_photo);
				}

				$user_info = $this->session->userdata('user_info');
				$user_info['profile_picture_url'] = $file_path;
				$this->session->set_userdata('user_info', $user_info);

				$this->session->set_flashdata('success', 'Foto profil berhasil diperbarui.');
			} else {
				if (file_exists($upload_path . $upload_data['file_name'])) {
					unlink($upload_path . $upload_data['file_name']);
				}
				$this->session->set_flashdata('error', 'Berhasil mengunggah file, namun gagal menyimpan ke database.');
			}
		}
		redirect('profile');
	}
}
