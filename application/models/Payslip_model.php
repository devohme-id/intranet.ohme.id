<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Payslip_model extends CI_Model
{

	// == Karyawan ==
	public function get_my_payslips($employee_id)
	{
		$this->db->from('portal_payslips');
		$this->db->where('employee_id', $employee_id);
		$this->db->order_by('period_year', 'DESC');
		$this->db->order_by('period_month', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_payslip_for_download($payslip_id, $employee_id)
	{
		return $this->db->get_where('portal_payslips', [
			'payslip_id' => $payslip_id,
			'employee_id' => $employee_id
		])->row();
	}

	// == Admin ==
	public function get_all_payslips()
	{
		$this->db->select('p.*, e.full_name, e.nik');
		$this->db->from('portal_payslips p');
		$this->db->join('m_employees e', 'p.employee_id = e.employee_id');
		$this->db->order_by('p.uploaded_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function insert_payslip($data)
	{
		return $this->db->insert('portal_payslips', $data);
	}

	public function delete_payslip($id)
	{
		$payslip = $this->db->get_where('portal_payslips', ['payslip_id' => $id])->row();
		if ($payslip) {
			if (file_exists($payslip->file_path)) {
				unlink($payslip->file_path);
			}
			return $this->db->delete('portal_payslips', ['payslip_id' => $id]);
		}
		return false;
	}
}
