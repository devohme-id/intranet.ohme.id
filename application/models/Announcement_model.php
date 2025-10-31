<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Announcement_model extends CI_Model
{

	public function get_all($search = null)
	{
		if ($search) {
			$this->db->like('title', $search);
		}
		return $this->db->order_by('created_at', 'DESC')->get('portal_announcements')->result_array();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where('portal_announcements', ['announcement_id' => $id])->row();
	}

	public function insert($data)
	{
		return $this->db->insert('portal_announcements', $data);
	}

	public function update($id, $data)
	{
		$this->db->where('announcement_id', $id);
		return $this->db->update('portal_announcements', $data);
	}

	public function delete($id)
	{
		return $this->db->delete('portal_announcements', ['announcement_id' => $id]);
	}
}
