<?php
defined('BASEPATH') || exit('No direct script access allowed');

class EmployeeDirectory extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('Employee_model');
		$this->load->library('pagination');
	}

	public function index()
	{
		$search_term = $this->input->get('search', TRUE);

		// ====================================================================
		// KONFIGURASI PAGINATION YANG DIPERBAIKI
		// ====================================================================
		$config['base_url'] = site_url('EmployeeDirectory/index');
		$config['total_rows'] = $this->employee_model->count_employees($search_term);
		$config['per_page'] = 12;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['num_links'] = 5; // Jumlah link angka yang ditampilkan

		// Styling Paginasi dengan TailwindCSS
		$config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="inline-flex -space-x-px text-sm">';
		$config['full_tag_close'] = '</ul></nav>';

		// First Link
		$config['first_link'] = 'Awal';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		// Last Link
		$config['last_link'] = 'Akhir';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		// Previous Link
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		// Next Link
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		// Current Page
		$config['cur_tag_open'] = '<li><a href="#" class="z-10 py-2 px-3 leading-tight text-white bg-blue-600 border border-blue-600 hover:bg-blue-700 hover:text-white">';
		$config['cur_tag_close'] = '</a></li>';

		// Numbered Page
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		// Default attributes untuk semua link
		$config['attributes'] = [
			'class' => 'py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'
		];
		
		$this->pagination->initialize($config);

		$page = ($this->uri->segment(3)) ? (int)$this->uri->segment(3) : 1;
		$offset = ($page > 1) ? ($page - 1) * $config['per_page'] : 0;

		$data['employees'] = $this->employee_model->get_employees($config['per_page'], $offset, $search_term);
		$data['pagination'] = $this->pagination->create_links();
		$data['search_term'] = $search_term;

		$this->render_page('directory/index_view', $data, 'Direktori Karyawan');
	}
}
