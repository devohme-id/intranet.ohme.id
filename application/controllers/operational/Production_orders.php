<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Production_orders extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('PRODUCTION_OVERVIEW_VIEW');
		$this->load->model('Production_order_model', 'po_model');
		$this->load->library('pagination');
	}

	/**
	 * FUNGSI DIPERBARUI: Menambahkan logika paginasi pada halaman utama.
	 */
	public function index()
	{
		$selected_line = $this->input->get('line', TRUE) ?? 'all';
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		$per_page = 10; // Jumlah item per halaman
		$offset = ($page > 1) ? ($page - 1) * $per_page : 0;

		// Konfigurasi Paginasi
		$config['base_url'] = site_url('operational/production_orders');
		$config['total_rows'] = $this->po_model->count_wip_data($selected_line);
		$config['per_page'] = $per_page;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';

		// Styling Paginasi (konsisten dengan halaman detail)
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
		$config['cur_tag_open'] = '<li><a href="#" class="z-10 py-2 px-3 leading-tight text-white bg-pink-600 border border-pink-600 hover:bg-pink-700 hover:text-white">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = ['class' => 'py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'];

		$this->pagination->initialize($config);

		// Menyiapkan data untuk view
		$data['production_lines'] = $this->po_model->get_active_lines();
		$data['wip_data'] = $this->po_model->get_wip_data($selected_line, $per_page, $offset);
		$data['selected_line'] = $selected_line;
		$data['pagination'] = $this->pagination->create_links();
		$data['start_no'] = $offset;

		$this->render_page('operational/production_order_view', $data, 'Pesanan Produksi');
	}

	public function view_serials($prod_order_no = '')
	{
		$wo_number_decoded = urldecode($prod_order_no);
		$wo = $this->po_model->get_wo_by_number($wo_number_decoded);
		if (!$wo) {
			show_404();
		}

		$search = $this->input->get('search', TRUE);
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		$per_page = 15;
		$offset = ($page > 1) ? ($page - 1) * $per_page : 0;

		// Konfigurasi Paginasi
		$config['base_url'] = site_url('operational/production_orders/view_serials/' . $prod_order_no);
		$config['total_rows'] = $this->po_model->count_unique_serials_by_wo($wo->prod_header_id, $search);
		$config['per_page'] = $per_page;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		// Styling Paginasi
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

		$total_completed_units = $this->po_model->count_completed_units_by_wo($wo->prod_header_id);
		$total_planned_qty = (int)$wo->quantity_to_produce;

		$overall_progress = 0;
		if ($total_planned_qty > 0) {
			$overall_progress = round(($total_completed_units / $total_planned_qty) * 100, 2);
		}

		$data['total_completed_units'] = $total_completed_units;
		$data['total_planned_qty'] = $total_planned_qty;
		$data['overall_progress'] = $overall_progress;
		$data['serials'] = $this->po_model->get_pivoted_serials_by_wo($wo->prod_header_id, $per_page, $offset, $search);
		$data['wo'] = $wo;
		$data['pagination'] = $this->pagination->create_links();
		$data['start_no'] = $offset;
		$data['search_term'] = $search;

		$this->render_page('operational/serial_number_view', $data, 'Detail Serial Number: ' . $wo->prod_order_no);
	}
}
