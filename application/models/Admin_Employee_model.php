<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Admin_Employee_model extends CI_Model
{

	public function get_employees($limit, $offset, $search = null)
	{
		$this->db->select('e.*, m.full_name as manager_name');
		$this->db->from('m_employees e');
		$this->db->join('m_employees m', 'e.manager_id = m.employee_id', 'left');

		if ($search) {
			$this->db->group_start();
			$this->db->like('e.full_name', $search);
			$this->db->or_like('e.nik', $search);
			$this->db->or_like('e.position', $search);
			$this->db->or_like('e.department', $search);
			$this->db->group_end();
		}

		$this->db->order_by('e.full_name', 'ASC');
		$this->db->limit($limit, $offset);
		return $this->db->get()->result_array();
	}

	public function count_employees($search = null)
	{
		if ($search) {
			$this->db->group_start();
			$this->db->like('full_name', $search);
			$this->db->or_like('nik', $search);
			$this->db->or_like('position', $search);
			$this->db->or_like('department', $search);
			$this->db->group_end();
		}
		return $this->db->count_all_results('m_employees');
	}

	public function get_employee_by_id($id)
	{
		return $this->db->get_where('m_employees', ['employee_id' => $id])->row();
	}

	public function get_all_employees_for_dropdown($exclude_id = null)
	{
		if ($exclude_id) {
			$this->db->where('employee_id !=', $exclude_id);
		}
		$this->db->order_by('full_name', 'ASC');
		return $this->db->get('m_employees')->result_array();
	}

	public function add_employee($data)
	{
		$this->db->insert('m_employees', $data);
		return $this->db->insert_id();
	}

	public function update_employee($id, $data)
	{
		$this->db->where('employee_id', $id);
		$this->db->update('m_employees', $data);
		return $this->db->affected_rows();
	}

	public function delete_employee($id)
	{
		// Menggunakan soft delete untuk menjaga integritas data
		$this->db->where('employee_id', $id);
		$this->db->update('m_employees', ['is_active' => 0]);
		return $this->db->affected_rows();
	}

	public function is_nik_exists($nik, $exclude_id = null)
	{
		$this->db->where('nik', $nik);
		if ($exclude_id) {
			$this->db->where('employee_id !=', $exclude_id);
		}
		return $this->db->count_all_results('m_employees') > 0;
	}
}
