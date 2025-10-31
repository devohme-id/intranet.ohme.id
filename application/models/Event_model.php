<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Event_model extends CI_Model
{

	public function get_all($search = null)
	{
		if ($search) {
			$this->db->like('title', $search);
			$this->db->or_like('location', $search);
		}
		return $this->db->order_by('start_datetime', 'DESC')->get('portal_events')->result_array();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where('portal_events', ['event_id' => $id])->row();
	}

	public function insert($data)
	{
		return $this->db->insert('portal_events', $data);
	}

	public function update($id, $data)
	{
		$this->db->where('event_id', $id);
		return $this->db->update('portal_events', $data);
	}

	public function delete($id)
	{
		return $this->db->delete('portal_events', ['event_id' => $id]);
	}
}
