<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Payslip extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('Payslip_model', 'payslip_model');
	}

	public function index()
	{
		$employee_id = $this->user_data['employee_id'] ?? null;
		if (empty($employee_id)) {
			$this->session->set_flashdata('error', 'Akun Anda tidak terhubung dengan data karyawan.');
			redirect('dashboard');
		}

		$data['payslips'] = $this->payslip_model->get_my_payslips($employee_id);
		$this->render_page('services/payslip/index_view', $data, 'Slip Gaji Saya');
	}

	public function download($payslip_id)
	{
		$employee_id = $this->user_data['employee_id'];
		$this->load->helper('download');

		// Verifikasi kepemilikan sebelum mengunduh
		$payslip = $this->payslip_model->get_payslip_for_download($payslip_id, $employee_id);

		if ($payslip && file_exists($payslip->file_path)) {
			$file_content = file_get_contents($payslip->file_path);
			// Nama file saat diunduh: SlipGaji-Bulan-Tahun-Nama.pdf
			$download_name = 'SlipGaji-' . $payslip->period_month . '-' . $payslip->period_year . '-' . url_title($this->user_data['full_name'], 'underscore', TRUE) . '.pdf';
			force_download($download_name, $file_content);
		} else {
			show_error('File tidak ditemukan atau Anda tidak memiliki hak akses.', 404, 'Akses Ditolak');
		}
	}
}
