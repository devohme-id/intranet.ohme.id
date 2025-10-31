<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Leave extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('LeaveRequest_model', 'leave_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$employee_id = $this->session->userdata('employee_id');

		if ($this->input->post()) {
			$this->submit_request($employee_id);
		}

		$data['leave_balance'] = $this->leave_model->get_annual_leave_balance($employee_id);
		$data['leave_history'] = $this->leave_model->get_leave_requests_by_employee($employee_id);
		$data['leave_types'] = $this->leave_model->get_active_leave_types();

		$this->render_page('services/leave/index_view', $data, 'Pengajuan Cuti');
	}

	private function submit_request($employee_id)
	{
		$this->form_validation->set_rules('leave_type_id', 'Jenis Cuti', 'required');
		$this->form_validation->set_rules('start_date', 'Tanggal Mulai', 'required');
		$this->form_validation->set_rules('end_date', 'Tanggal Selesai', 'required');
		$this->form_validation->set_rules('reason', 'Alasan', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('services/leave');
		}

		$start_date = new DateTime($this->input->post('start_date'));
		$end_date = new DateTime($this->input->post('end_date'));
		$total_days = $end_date->diff($start_date)->days + 1;

		// Validasi kuota
		$balance = $this->leave_model->get_annual_leave_balance($employee_id);
		if ($total_days > $balance->balance) {
			$this->session->set_flashdata('error', 'Sisa kuota cuti Anda tidak mencukupi.');
			redirect('services/leave');
		}

		$data = [
			'employee_id' => $employee_id,
			'leave_type_id' => $this->input->post('leave_type_id'),
			'start_date' => $this->input->post('start_date'),
			'end_date' => $this->input->post('end_date'),
			'total_days' => $total_days,
			'reason' => $this->input->post('reason'),
			'status' => 'submitted' // Status awal
		];

		$this->db->insert('portal_leave_requests', $data);
		$this->session->set_flashdata('success', 'Pengajuan cuti Anda telah berhasil dikirim.');
		redirect('services/leave');
	}
}
