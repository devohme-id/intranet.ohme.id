<?php
defined('BASEPATH') || exit('No direct script access allowed');

class CompanyProfile_model extends CI_Model
{

	public function get_profile($key)
	{
		return $this->db->get_where('portal_company_profiles', ['profile_key' => $key])->row();
	}

	public function get_all_profiles()
	{
		return $this->db->get('portal_company_profiles')->result_array();
	}

	public function update_profile($key, $data)
	{
		$this->db->where('profile_key', $key);
		return $this->db->update('portal_company_profiles', $data);
	}
}
