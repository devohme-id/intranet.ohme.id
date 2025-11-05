<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Employees extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_EMPLOYEES');
		$this->load->model('Admin_Employee_model', 'admin_employee');
		$this->load->library('pagination');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$search = $this->input->get('search', TRUE);
		$config['base_url'] = site_url('admin/employees/index');
		$config['total_rows'] = $this->admin_employee->count_employees($search);
		$config['per_page'] = 15;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="inline-flex -space-x-px text-sm">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['first_link'] = 'Awal';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Akhir';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" class="z-10 py-2 px-3 leading-tight text-white bg-blue-600 border border-blue-600 hover:bg-blue-700 hover:text-white">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = ['class' => 'py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'];

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? (int)$this->uri->segment(4) : 1;
		$offset = ($page > 1) ? ($page - 1) * $config['per_page'] : 0;

		$data['employees'] = $this->admin_employee->get_employees($config['per_page'], $offset, $search);
		$data['pagination'] = $this->pagination->create_links();
		$data['search_term'] = $search;

		// **PERBAIKAN**: Memeriksa hak akses di controller dan mengirim hasilnya ke view
		$data['can_add'] = $this->has_permission('ADD_EMPLOYEES');
		$data['can_edit'] = $this->has_permission('EDIT_EMPLOYEES');
		$data['can_delete'] = $this->has_permission('DELETE_EMPLOYEES');

		$this->render_page('admin/employees/index_view', $data, 'Kelola Karyawan');
	}

	public function add()
	{
		$this->check_permission('ADD_EMPLOYEES');

		$this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim');
		$this->form_validation->set_rules('nik', 'NIK', 'required|trim|is_unique[m_employees.nik]');
		$this->form_validation->set_rules('id_card_no', 'No Id Card', 'required|trim|is_unique[m_employees.id_card_no]');
		$this->form_validation->set_rules('fingerprint_id', 'Fingerprint', 'required|trim|is_unique[m_employees.fingerprint_id]');
		$this->form_validation->set_rules('birth_date', 'Tanggal Lahir', 'required|trim');
		$this->form_validation->set_rules('join_date', 'Join Date', 'required|trim');
		$this->form_validation->set_rules('position', 'Jabatan', 'required|trim');
		$this->form_validation->set_rules('department', 'Departemen', 'required|trim');
		$this->form_validation->set_rules('group_id', 'Group', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$data['managers'] = $this->admin_employee->get_all_employees_for_dropdown();
			$data['groups'] = $this->admin_employee->get_all_groups_for_dropdown();
			$data['departments'] = $this->admin_employee->get_all_departments_for_dropdown();
			$data['positions'] = $this->admin_employee->get_all_positions_for_dropdown();
			$this->render_page('admin/employees/add_view', $data, 'Tambah Karyawan Baru');
		} else {
			$manager_id = $this->input->post('manager_id');
			$data = [
				'full_name' => $this->input->post('full_name'),
				'nik' => $this->input->post('nik'),
				'position' => $this->input->post('position'),
				'department' => $this->input->post('department'),
				'manager_id' => empty($manager_id) ? NULL : $manager_id,
				'is_active' => $this->input->post('is_active') ?? 1,
				'id_card_no' => $this->input->post('id_card_no'),
				'fingerprint_id' => $this->input->post('fingerprint_id'),
				'birth_date' => $this->input->post('birth_date'),
				'join_date' => $this->input->post('join_date'),
				'group_id' => $this->input->post('group_id')
			];

			if ($this->admin_employee->add_employee($data)) {
				$this->session->set_flashdata('success', 'Karyawan baru berhasil ditambahkan.');
			} else {
				$this->session->set_flashdata('error', 'Gagal menambahkan karyawan baru.');
			}
			redirect('admin/employees');
		}
	}

	public function edit($id)
	{
		$this->check_permission('EDIT_EMPLOYEES');
		$employee = $this->admin_employee->get_employee_by_id($id);
		if (!$employee) {
			show_404();
		}

		$original_nik = $employee->nik;
		$original_card = $employee->id_card_no;
		$original_finger = $employee->fingerprint_id;
		$nik_unique_rule = ($this->input->post('nik') != $original_nik) ? '|is_unique[m_employees.nik]' : '';
		$card_unique_rule = ($this->input->post('id_card_no') != $original_card) ? '|is_unique[m_employees.id_card_no]' : '';
		$fingerprint_unique_rule = ($this->input->post('fingerprint_id') != $original_finger) ? '|is_unique[m_employees.fingerprint_id]' : '';

		$this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim');
		$this->form_validation->set_rules('nik', 'NIK', 'required|trim' . $nik_unique_rule);
		$this->form_validation->set_rules('id_card_no', 'No Id Card', 'required|trim'.$card_unique_rule);
		$this->form_validation->set_rules('fingerprint_id', 'Fingerprint', 'required|trim'.$fingerprint_unique_rule);
		$this->form_validation->set_rules('birth_date', 'Tanggal Lahir', 'required|trim');
		$this->form_validation->set_rules('join_date', 'Join Date', 'required|trim');
		$this->form_validation->set_rules('position', 'Jabatan', 'required|trim');
		$this->form_validation->set_rules('department', 'Departemen', 'required|trim');
		$this->form_validation->set_rules('group_id', 'Group', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$data['employee'] = $employee;
			$data['managers'] = $this->admin_employee->get_all_employees_for_dropdown($id);
			$data['groups'] = $this->admin_employee->get_all_groups_for_dropdown($id);
			$data['positions'] = $this->admin_employee->get_all_positions_for_dropdown($id);
			$data['departments'] = $this->admin_employee->get_all_departments_for_dropdown($id);
			$this->render_page('admin/employees/edit_view', $data, 'Edit Karyawan: ' . $employee->full_name);
		} else {
			$manager_id = $this->input->post('manager_id');
			$data = [
				'full_name' => $this->input->post('full_name'),
				'nik' => $this->input->post('nik'),
				'position' => $this->input->post('position'),
				'department' => $this->input->post('department'),
				'manager_id' => empty($manager_id) ? NULL : $manager_id,
				'is_active' => $this->input->post('is_active') ?? 0,
				'id_card_no' => $this->input->post('id_card_no'),
				'fingerprint_id' => $this->input->post('fingerprint_id'),
				'birth_date' => $this->input->post('birth_date'),
				'join_date' => $this->input->post('join_date'),
				'group_id' => $this->input->post('group_id')
			];

			if ($this->admin_employee->update_employee($id, $data) > 0) {
				$this->session->set_flashdata('success', 'Data karyawan berhasil diperbarui.');
			} else {
				$this->session->set_flashdata('info', 'Tidak ada perubahan data yang disimpan.');
			}
			redirect('admin/employees');
		}
	}

	public function delete($id)
	{
		$this->check_permission('DELETE_EMPLOYEES');

		if ($this->input->method() !== 'post') {
			show_error('Metode tidak diizinkan.', 405);
		}

		if ($this->admin_employee->delete_employee($id)) {
			$this->session->set_flashdata('success', 'Data karyawan berhasil dihapus (dinonaktifkan).');
		} else {
			$this->session->set_flashdata('error', 'Gagal menghapus data karyawan.');
		}
		redirect('admin/employees');
	}
}
