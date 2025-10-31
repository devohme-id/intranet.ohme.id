<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Company extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('CompanyProfile_model', 'company_profile');
	}

	/**
	 * Menampilkan halaman profil perusahaan berdasarkan key.
	 * @param string $key Kunci profil dari URL.
	 */
	public function profile($key = '')
	{
		// $this->check_permission('VIEW_COMPANY_PROFILE'); // Aktifkan jika perlu hak akses

		$profile = $this->company_profile->get_profile($key);

		if (!$profile) {
			show_404();
			return;
		}

		$data['profile'] = $profile;
		$this->render_page('company/profile_view', $data, $profile->title);
	}
}
