<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Auth_model extends CI_Model
{

	public function get_user_by_username($username)
	{
		// **KUNCI PERBAIKAN UTAMA**
		// Menambahkan u.employee_id ke dalam SELECT statement.
		// Ieu akar masalah kunaon employee_id janten 0.
		// **PERBAIKAN TUNTAS**: Menambahkan u.profile_picture_url ke dalam SELECT
		$this->db->select('u.user_id, u.employee_id, u.username, u.password_hash, u.full_name, u.role_id, u.profile_picture_url, r.role_name');
		$this->db->from('m_users u');
		$this->db->join('m_roles r', 'u.role_id = r.role_id', 'left');
		$this->db->where('u.username', $username);
		$this->db->where('u.is_active', 1);
		return $this->db->get()->row();
	}

	public function get_user_permissions($role_id)
	{
		$this->db->select('permission_key');
		$this->db->from('m_role_permissions');
		$this->db->where('role_id', $role_id);
		$query = $this->db->get();
		return array_column($query->result_array(), 'permission_key');
	}
}
