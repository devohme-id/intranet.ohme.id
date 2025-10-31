<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Events extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_EVENTS');
		$this->load->model('event_model');
	}

	public function index()
	{
		$search = $this->input->get('search');
		$data['events'] = $this->event_model->get_all($search);
		$this->render_page('admin/events/index_view', $data, 'Kelola Kalender Kegiatan');
	}

	public function add()
	{
		if ($this->input->post()) {
			$db_data = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'start_datetime' => $this->input->post('start_datetime'),
				'end_datetime' => $this->input->post('end_datetime'),
				'location' => $this->input->post('location'),
				'created_by' => $this->user_data['user_id']
			];
			$this->event_model->insert($db_data);
			$this->session->set_flashdata('success', 'Kegiatan berhasil ditambahkan.');
			redirect('admin/events');
		}
		$this->render_page('admin/events/form_view', [], 'Tambah Kegiatan Baru');
	}

	public function edit($id)
	{
		$event = $this->event_model->get_by_id($id);
		if (!$event) {
            show_404();
        }

		if ($this->input->post()) {
			$db_data = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'start_datetime' => $this->input->post('start_datetime'),
				'end_datetime' => $this->input->post('end_datetime'),
				'location' => $this->input->post('location'),
			];
			$this->event_model->update($id, $db_data);
			$this->session->set_flashdata('success', 'Kegiatan berhasil diperbarui.');
			redirect('admin/events');
		}
		$data['event'] = $event;
		$this->render_page('admin/events/form_view', $data, 'Edit Kegiatan');
	}

	public function delete($id)
	{
		$this->event_model->delete($id);
		$this->session->set_flashdata('success', 'Kegiatan berhasil dihapus.');
		redirect('admin/events');
	}
}
