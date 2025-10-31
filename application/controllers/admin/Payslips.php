<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Payslips extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('UPLOAD_PAYSLIPS');
		$this->load->model('Payslip_model', 'payslip_model');
		$this->load->model('Admin_Employee_model', 'admin_employee');
		$this->load->library('upload');
	}

	public function index()
	{
		if ($this->input->post()) {
			$config['upload_path']   = './uploads/payslips/';
			$config['allowed_types'] = 'pdf';
			$config['max_size']      = 1024; // 1MB
			$config['encrypt_name']  = TRUE;
			if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

			$this->upload->initialize($config);

			if ($this->upload->do_upload('payslip_file')) {
				$file_data = $this->upload->data();
				$db_data = [
					'employee_id' => $this->input->post('employee_id'),
					'period_month' => $this->input->post('period_month'),
					'period_year' => $this->input->post('period_year'),
					'file_name' => $file_data['orig_name'],
					'file_path' => 'uploads/payslips/' . $file_data['file_name'],
					'uploaded_by' => $this->user_data['user_id']
				];
				$this->payslip_model->insert_payslip($db_data);
				$this->session->set_flashdata('success', 'Slip gaji berhasil diunggah.');
			} else {
				$this->session->set_flashdata('error', $this->upload->display_errors());
			}
			redirect('admin/payslips');
		}

		$data['payslips'] = $this->payslip_model->get_all_payslips();
		$data['employees'] = $this->admin_employee->get_all_employees_for_dropdown();
		$this->render_page('admin/payslips/index_view', $data, 'Kelola Slip Gaji');
	}

	public function delete($id)
	{
		$this->payslip_model->delete_payslip($id);
		$this->session->set_flashdata('success', 'Slip gaji berhasil dihapus.');
		redirect('admin/payslips');
	}
}
