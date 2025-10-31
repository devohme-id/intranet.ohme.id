<?php
defined('BASEPATH') || exit('No direct script access allowed');

class LeaveRequest_model extends CI_Model
{

	// == Karyawan ==
	public function get_leave_types()
	{
		return $this->db->get_where('portal_leave_types', ['is_active' => 1])->result_array();
	}

	public function insert_request($data)
	{
		$this->db->insert('portal_leave_requests', $data);
		return $this->db->insert_id();
	}

	public function get_my_requests($employee_id)
	{
		$this->db->select('lr.*, lt.type_name');
		$this->db->from('portal_leave_requests lr');
		$this->db->join('portal_leave_types lt', 'lr.leave_type_id = lt.leave_type_id');
		$this->db->where('lr.employee_id', $employee_id);
		$this->db->order_by('lr.created_at', 'DESC');
		return $this->db->get()->result_array();
	}

	// == Admin & Manager (DIROMBAK TOTAL) ==

	/**
	 * **FUNGSI BARU**
	 * Nyandak pengajuan anu statusna 'submitted' ti bawahan langsung hiji manager.
	 */
	public function get_requests_for_manager_approval($manager_employee_id)
	{
		$this->db->select('lr.*, lt.type_name, e.full_name as employee_name');
		$this->db->from('portal_leave_requests lr');
		$this->db->join('portal_leave_types lt', 'lr.leave_type_id = lt.leave_type_id');
		$this->db->join('m_employees e', 'lr.employee_id = e.employee_id');
		$this->db->where('e.manager_id', $manager_employee_id);
		$this->db->where('lr.status', 'submitted'); // Ngan anu peryogi persetujuan manager
		$this->db->order_by('lr.created_at', 'ASC');
		return $this->db->get()->result_array();
	}

	/**
	 * **FUNGSI BARU**
	 * Nyandak pengajuan anu statusna 'approved_manager' pikeun persetujuan HR.
	 */
	public function get_requests_for_hr_approval()
	{
		$this->db->select('lr.*, lt.type_name, e.full_name as employee_name');
		$this->db->from('portal_leave_requests lr');
		$this->db->join('portal_leave_types lt', 'lr.leave_type_id = lt.leave_type_id');
		$this->db->join('m_employees e', 'lr.employee_id = e.employee_id');
		$this->db->where('lr.status', 'approved_manager'); // Ngan anu peryogi persetujuan HR
		$this->db->order_by('lr.created_at', 'ASC');
		return $this->db->get()->result_array();
	}

	public function get_request_by_id($id)
	{
		$this->db->select('lr.*, lt.type_name, e.full_name as employee_name, e.nik');
		$this->db->from('portal_leave_requests lr');
		$this->db->join('portal_leave_types lt', 'lr.leave_type_id = lt.leave_type_id');
		$this->db->join('m_employees e', 'lr.employee_id = e.employee_id');
		$this->db->where('lr.request_id', $id);
		return $this->db->get()->row();
	}

	public function update_status($id, $status)
	{
		$this->db->where('request_id', $id);
		return $this->db->update('portal_leave_requests', ['status' => $status]);
	}

	public function insert_approval($data)
	{
		return $this->db->insert('portal_leave_approvals', $data);
	}

	public function get_approval_history($request_id)
	{
		$this->db->select('la.*, u.full_name as approver_name');
		$this->db->from('portal_leave_approvals la');
		$this->db->join('m_users u', 'la.approver_id = u.user_id');
		$this->db->where('la.request_id', $request_id);
		$this->db->order_by('la.created_at', 'ASC');
		return $this->db->get()->result_array();
	}

	/**
	 * Mengambil informasi kuota cuti tahunan seorang karyawan untuk tahun berjalan.
	 * Jika kuota belum ada, akan memicu perhitungan.
	 */
	public function get_annual_leave_balance($employee_id)
	{
		$current_year = date('Y');
		$annual_leave_type = $this->db->get_where('portal_leave_types', ['type_name' => 'Cuti Tahunan'])->row();

		if (!$annual_leave_type) {
			return (object)['balance' => 0, 'leave_type_id' => 0];
		}

		$this->check_and_generate_annual_quota($employee_id, $annual_leave_type, $current_year);

		$this->db->select('balance, leave_type_id');
		$this->db->from('portal_employee_leave_balances');
		$this->db->where('employee_id', $employee_id);
		$this->db->where('leave_type_id', $annual_leave_type->leave_type_id);
		$this->db->where('period_year', $current_year);
		$result = $this->db->get()->row();

		return $result ?: (object)['balance' => 0, 'leave_type_id' => $annual_leave_type->leave_type_id];
	}

	/**
	 * Memeriksa dan membuat kuota cuti tahunan jika diperlukan.
	 */
	public function check_and_generate_annual_quota($employee_id, $annual_leave_type, $year)
	{
		$employee = $this->db->get_where('m_employees', ['employee_id' => $employee_id])->row();
		if (!$employee || !$employee->join_date) {
			return;
		}

		$balance_exists = $this->db->get_where('portal_employee_leave_balances', [
			'employee_id' => $employee_id,
			'leave_type_id' => $annual_leave_type->leave_type_id,
			'period_year' => $year
		])->num_rows() > 0;

		if ($balance_exists) {
			return; // Kuota sudah ada
		}

		// Logika pemberian kuota
		$join_date = new DateTime($employee->join_date);
		$current_date = new DateTime();
		$interval = $join_date->diff($current_date);
		$months_worked = ($interval->y * 12) + $interval->m;

		if ($months_worked >= 12) {
			$data = [
				'employee_id' => $employee_id,
				'leave_type_id' => $annual_leave_type->leave_type_id,
				'balance' => $annual_leave_type->default_quota,
				'period_year' => $year
			];
			$this->db->insert('portal_employee_leave_balances', $data);
			$balance_id = $this->db->insert_id();

			// Catat di log
			$this->log_balance_change($balance_id, $annual_leave_type->default_quota, 'accrual', null, 'Initial quota for ' . $year);
		}
	}

	/**
	 * Mengurangi kuota cuti dan mencatatnya di log.
	 */
	public function deduct_leave_balance($request_id)
	{
		$request = $this->db->get_where('portal_leave_requests', ['request_id' => $request_id])->row();
		$leave_type = $this->db->get_where('portal_leave_types', ['leave_type_id' => $request->leave_type_id])->row();

		$annual_leave_type_id = $this->get_annual_leave_balance($request->employee_id)->leave_type_id;
		$deduct_from_type_id = $leave_type->is_deductible ? $annual_leave_type_id : $request->leave_type_id;

		if ($leave_type->has_quota || $leave_type->is_deductible) {
			$balance = $this->db->get_where('portal_employee_leave_balances', [
				'employee_id' => $request->employee_id,
				'leave_type_id' => $deduct_from_type_id,
				'period_year' => date('Y', strtotime($request->start_date))
			])->row();

			if ($balance) {
				$new_balance = $balance->balance - $request->total_days;
				$this->db->where('balance_id', $balance->balance_id)->update('portal_employee_leave_balances', ['balance' => $new_balance]);
				$this->log_balance_change($balance->balance_id, -$request->total_days, 'request', $request_id);
			}
		}
	}

	/**
	 * Mencatat setiap perubahan pada kuota cuti.
	 */
	private function log_balance_change($balance_id, $amount, $reason, $request_id = null, $notes = '')
	{
		$log_data = [
			'balance_id' => $balance_id,
			'change_amount' => $amount,
			'reason' => $reason,
			'related_request_id' => $request_id,
			'notes' => $notes,
			'created_by' => $this->session->userdata('user_id') ?? null
		];
		$this->db->insert('portal_leave_balance_logs', $log_data);
	}

	public function get_leave_requests_by_employee($employee_id)
	{
		$this->db->select('lr.*, lt.type_name');
		$this->db->from('portal_leave_requests lr');
		$this->db->join('portal_leave_types lt', 'lr.leave_type_id = lt.leave_type_id');
		$this->db->where('lr.employee_id', $employee_id);
		$this->db->order_by('lr.created_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_active_leave_types()
	{
		return $this->db->get_where('portal_leave_types', ['is_active' => 1])->result_array();
	}
}
