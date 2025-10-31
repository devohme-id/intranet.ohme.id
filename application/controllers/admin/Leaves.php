<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Leaves extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_LEAVE_REQUESTS');
		$this->load->model('LeaveRequest_model', 'leave_model');
	}

	public function index()
	{
		$employee_id = $this->user_data['employee_id'] ?? null;

		// Logika pikeun nampilkeun daptar anu relevan
		$data['requests_for_manager'] = [];
		if ($employee_id) {
			$data['requests_for_manager'] = $this->leave_model->get_requests_for_manager_approval($employee_id);
		}

		// Asumsi Admin/HR tiasa ningali pengajuan anu peryogi persetujuan HR
		$data['requests_for_hr'] = $this->leave_model->get_requests_for_hr_approval();

		$this->render_page('admin/leaves/index_view', $data, 'Kelola Pengajuan Cuti');
	}

	public function view($id)
	{
		$request = $this->leave_model->get_request_by_id($id);
		if (!$request) {
            show_404();
        }

		if ($this->input->post('update_status')) {
			$new_status = $this->input->post('status');
			$remarks = $this->input->post('remarks');
			$approval_level = '';
			$is_rejected = ($new_status == 'rejected');

			// Nangtukeun level persetujuan dumasar status ayeuna
			if ($request->status == 'submitted') {
				$approval_level = 'manager';
			} elseif ($request->status == 'approved_manager') {
				$approval_level = 'hr';
			}

			if ($approval_level !== '' && $approval_level !== '0') {
				$this->db->trans_start();
				// 1. Catet dina tabel approval
				$this->leave_model->insert_approval([
					'request_id' => $id,
					'approver_id' => $this->user_data['user_id'],
					'approval_level' => $approval_level,
					'status' => $is_rejected ? 'rejected' : 'approved',
					'remarks' => $remarks
				]);
				// 2. Ropéa status utama
				$this->leave_model->update_status($id, $new_status);
				$this->db->trans_complete();

				$this->session->set_flashdata('success', 'Status pengajuan berhasil diperbarui.');
			} else {
				$this->session->set_flashdata('info', 'Tidak ada tindakan persetujuan yang diambil pada tahap ini.');
			}
			redirect('admin/leaves/view/' . $id);
		}

		$data['request'] = $request;
		$data['approval_history'] = $this->leave_model->get_approval_history($id);
		$this->render_page('admin/leaves/view_view', $data, 'Detail Pengajuan Cuti');
	}
}
