<?php
defined('BASEPATH') || exit('No direct script access allowed');

class CompanyProfile extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_COMPANY_PROFILE');
		$this->load->model('companyprofile_model');
	}

	public function index()
	{
		$data['profiles'] = $this->companyprofile_model->get_all_profiles();
		$this->render_page('admin/company_profile/index_view', $data, 'Kelola Profil Perusahaan');
	}

	public function edit($key = '')
	{
		$profile = $this->companyprofile_model->get_profile($key);
		if (!$profile) {
			show_404();
		}

		if ($this->input->post()) {
			$update_data = [
				'title' => $this->input->post('title'),
				'content' => $this->input->post('content'), // Hati-hati dengan XSS, gunakan library pembersih jika perlu
				'last_updated_by' => $this->user_data['user_id']
			];

			if ($this->companyprofile_model->update_profile($key, $update_data)) {
				$this->session->set_flashdata('success', 'Profil perusahaan berhasil diperbarui.');
				redirect('admin/companyprofile');
			} else {
				$this->session->set_flashdata('error', 'Gagal memperbarui profil perusahaan.');
			}
		}

		$data['profile'] = $profile;
		$this->render_page('admin/company_profile/edit_view', $data, 'Edit ' . $profile->title);
	}
}
