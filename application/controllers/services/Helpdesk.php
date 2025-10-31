<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Helpdesk extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('Ticket_model', 'ticket_model');
	}

	public function index()
	{
		if ($this->input->post()) {
			$data = [
				'ticket_code' => $this->ticket_model->generate_ticket_code(),
				'user_id' => $this->user_data['user_id'],
				'category_id' => $this->input->post('category_id'),
				'subject' => $this->input->post('subject'),
				'description' => $this->input->post('description'),
				'priority' => $this->input->post('priority'),
				'status' => 'open'
			];
			$this->ticket_model->create_ticket($data);
			$this->session->set_flashdata('success', 'Tiket Anda telah berhasil dibuat dengan kode ' . $data['ticket_code']);
			redirect('services/helpdesk');
		}

		$data['categories'] = $this->ticket_model->get_ticket_categories();
		$data['my_tickets'] = $this->ticket_model->get_my_tickets($this->user_data['user_id']);
		$this->render_page('services/helpdesk/index_view', $data, 'Helpdesk');
	}

	public function view($ticket_id)
	{
		$ticket = $this->ticket_model->get_ticket_by_id($ticket_id, $this->user_data['user_id']);
		if (!$ticket) {
            show_404();
        }

		if ($this->input->post('reply_message')) {
			$reply_data = [
				'ticket_id' => $ticket_id,
				'user_id' => $this->user_data['user_id'],
				'message' => $this->input->post('reply_message')
			];
			$this->ticket_model->add_reply($reply_data);
			$this->session->set_flashdata('success', 'Balasan Anda telah terkirim.');
			redirect('services/helpdesk/view/' . $ticket_id);
		}

		if ($this->input->post('close_ticket')) {
			$this->ticket_model->update_ticket_status($ticket_id, 'closed');
			$this->session->set_flashdata('success', 'Tiket telah ditutup.');
			redirect('services/helpdesk/view/' . $ticket_id);
		}

		$data['ticket'] = $ticket;
		$data['replies'] = $this->ticket_model->get_ticket_replies($ticket_id);
		$this->render_page('services/helpdesk/view_view', $data, 'Detail Tiket: ' . $ticket->ticket_code);
	}
}
