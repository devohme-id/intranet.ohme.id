<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Tickets extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->check_permission('MANAGE_HELPDESK_TICKETS');
		$this->load->model('Ticket_model', 'ticket_model');
	}

	public function index()
	{
		$data['tickets'] = $this->ticket_model->get_all_tickets_for_admin();
		$this->render_page('admin/tickets/index_view', $data, 'Kelola Helpdesk');
	}

	public function view($ticket_id)
	{
		$ticket = $this->ticket_model->get_ticket_by_id($ticket_id);
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
			redirect('admin/tickets/view/' . $ticket_id);
		}

		if ($this->input->post('update_status')) {
			$new_status = $this->input->post('status');
			$this->ticket_model->update_ticket_status($ticket_id, $new_status);
			$this->session->set_flashdata('success', 'Status tiket berhasil diperbarui.');
			redirect('admin/tickets/view/' . $ticket_id);
		}

		$data['ticket'] = $ticket;
		$data['replies'] = $this->ticket_model->get_ticket_replies($ticket_id);
		$this->render_page('admin/tickets/view_view', $data, 'Detail Tiket: ' . $ticket->ticket_code);
	}
}
