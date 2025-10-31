<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Ticket_model extends CI_Model
{

	public function generate_ticket_code()
	{
		$date = date('Ymd');
		$this->db->like('ticket_code', 'TICKET-' . $date, 'after');
		$this->db->select_max('ticket_code');
		$result = $this->db->get('portal_tickets')->row();

		if ($result && $result->ticket_code) {
			$last_num = (int) substr($result->ticket_code, -3);
			$new_num = $last_num + 1;
			return 'TICKET-' . $date . '-' . str_pad($new_num, 3, '0', STR_PAD_LEFT);
		} else {
			return 'TICKET-' . $date . '-001';
		}
	}

	public function get_ticket_categories()
	{
		return $this->db->get_where('portal_ticket_categories', ['is_active' => 1])->result_array();
	}

	public function create_ticket($data)
	{
		return $this->db->insert('portal_tickets', $data);
	}

	public function get_my_tickets($user_id)
	{
		$this->db->select('t.*, c.category_name');
		$this->db->from('portal_tickets t');
		$this->db->join('portal_ticket_categories c', 't.category_id = c.category_id');
		$this->db->where('t.user_id', $user_id);
		$this->db->order_by('t.updated_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_ticket_by_id($ticket_id, $user_id = null)
	{
		$this->db->select('t.*, c.category_name, u.full_name as user_name');
		$this->db->from('portal_tickets t');
		$this->db->join('portal_ticket_categories c', 't.category_id = c.category_id');
		$this->db->join('m_users u', 't.user_id = u.user_id');
		$this->db->where('t.ticket_id', $ticket_id);
		if ($user_id) { // Verifikasi kepemilikan jika user_id diberikan
			$this->db->where('t.user_id', $user_id);
		}
		return $this->db->get()->row();
	}

	public function get_ticket_replies($ticket_id)
	{
		$this->db->select('tr.*, u.full_name as replier_name, u.role_id');
		$this->db->from('portal_ticket_replies tr');
		$this->db->join('m_users u', 'tr.user_id = u.user_id');
		$this->db->where('tr.ticket_id', $ticket_id);
		$this->db->order_by('tr.created_at', 'ASC');
		return $this->db->get()->result_array();
	}

	public function add_reply($data)
	{
		$this->db->trans_start();
		$this->db->insert('portal_ticket_replies', $data);
		// Update timestamp di tiket utama
		$this->db->set('updated_at', 'NOW()', FALSE);
		$this->db->where('ticket_id', $data['ticket_id']);
		$this->db->update('portal_tickets');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function update_ticket_status($ticket_id, $status)
	{
		$this->db->where('ticket_id', $ticket_id);
		return $this->db->update('portal_tickets', ['status' => $status]);
	}

	public function get_all_tickets_for_admin()
	{
		$this->db->select('t.*, c.category_name, u.full_name as user_name');
		$this->db->from('portal_tickets t');
		$this->db->join('portal_ticket_categories c', 't.category_id = c.category_id');
		$this->db->join('m_users u', 't.user_id = u.user_id');
		$this->db->order_by('t.updated_at', 'DESC');
		return $this->db->get()->result_array();
	}
}
