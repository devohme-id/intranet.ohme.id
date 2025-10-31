<?php
defined('BASEPATH') || exit('No direct script access allowed');

class User_model extends CI_Model
{

	public function get_users($limit, $offset, $search = null)
	{
		$this->db->select('u.*, r.role_name, e.nik');
		$this->db->from('m_users u');
		$this->db->join('m_roles r', 'u.role_id = r.role_id', 'left');
		$this->db->join('m_employees e', 'u.employee_id = e.employee_id', 'left');

		if ($search) {
			$this->db->group_start();
			$this->db->like('u.full_name', $search);
			$this->db->or_like('u.username', $search);
			$this->db->or_like('e.nik', $search);
			$this->db->or_like('r.role_name', $search);
			$this->db->group_end();
		}

		$this->db->order_by('u.full_name', 'ASC');
		$this->db->limit($limit, $offset);
		return $this->db->get()->result_array();
	}

	public function count_users($search = null)
	{
		$this->db->from('m_users u');
		$this->db->join('m_roles r', 'u.role_id = r.role_id', 'left');
		$this->db->join('m_employees e', 'u.employee_id = e.employee_id', 'left');
		if ($search) {
			$this->db->group_start();
			$this->db->like('u.full_name', $search);
			$this->db->or_like('u.username', $search);
			$this->db->or_like('e.nik', $search);
			$this->db->or_like('r.role_name', $search);
			$this->db->group_end();
		}
		return $this->db->count_all_results();
	}

	public function get_user_by_id($id)
	{
		return $this->db->get_where('m_users', ['user_id' => $id])->row();
	}

	public function get_all_roles()
	{
		return $this->db->get_where('m_roles', ['is_active' => 1])->result_array();
	}

	public function get_employees_without_user_account()
	{
		$sub_query = $this->db->select('employee_id')->from('m_users')->where('employee_id IS NOT NULL', NULL, FALSE)->get_compiled_select();
		$this->db->from('m_employees');
		$this->db->where("employee_id NOT IN ($sub_query)", NULL, FALSE);
		$this->db->where('is_active', 1);
		$this->db->order_by('full_name', 'ASC');
		return $this->db->get()->result_array();
	}

	public function insert_user($data)
	{
		return $this->db->insert('m_users', $data);
	}

	public function update_user($id, $data)
	{
		$this->db->where('user_id', $id);
		$this->db->update('m_users', $data);
		return $this->db->affected_rows();
	}

	public function delete_user($id)
	{
		// Menggunakan soft delete (menonaktifkan akun)
		$this->db->where('user_id', $id);
		$this->db->update('m_users', ['is_active' => 0]);
		return $this->db->affected_rows();
	}

	public function is_username_exists($username, $exclude_id = 0)
	{
		$this->db->where('username', $username);
		if ($exclude_id > 0) {
			$this->db->where('user_id !=', $exclude_id);
		}
		return $this->db->count_all_results('m_users') > 0;
	}
}
