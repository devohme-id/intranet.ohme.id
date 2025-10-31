<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{

	public function get_active_announcements($limit = 5)
	{
		$today = date('Y-m-d');
		$this->db->from('portal_announcements');
		$this->db->where('is_active', 1);
		$this->db->where('start_date <=', $today);
		$this->db->where('end_date >=', $today);
		$this->db->order_by('start_date', 'DESC');
		$this->db->limit($limit);
		return $this->db->get()->result_array();
	}

	public function get_upcoming_events($limit = 5)
	{
		$now = date('Y-m-d H:i:s');
		$this->db->from('portal_events');
		$this->db->where('start_datetime >=', $now);
		$this->db->order_by('start_datetime', 'ASC');
		$this->db->limit($limit);
		return $this->db->get()->result_array();
	}

	public function get_birthdays_this_month()
	{
		$current_month = date('m');
		$this->db->from('m_employees');
		$this->db->where('is_active', 1);
		$this->db->where("DATE_FORMAT(birth_date, '%m') =", $current_month);
		$this->db->order_by("DATE_FORMAT(birth_date, '%d')");
		return $this->db->get()->result_array();
	}

	public function get_new_employees($days = 30)
	{
		$date_threshold = date('Y-m-d', strtotime("-$days days"));
		$this->db->from('m_employees');
		$this->db->where('is_active', 1);
		$this->db->where('join_date >=', $date_threshold);
		$this->db->order_by('join_date', 'DESC');
		return $this->db->get()->result_array();
	}

	/**
	 * **FUNGSI BARU**
	 * Mengambil satu album galeri terbaru untuk ditampilkan di dashboard.
	 * @return array|null
	 */
	public function get_latest_gallery()
	{
		$this->db->select('g.gallery_id, g.title, (SELECT p.file_path FROM portal_gallery_photos p WHERE p.gallery_id = g.gallery_id ORDER BY p.photo_id ASC LIMIT 1) as cover_image');
		$this->db->from('portal_galleries g');
		$this->db->order_by('g.event_date', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	/**
	 * **FUNGSI BARU**
	 * Mengambil proyek aktif di mana pengguna adalah anggota.
	 * @param int $user_id ID pengguna yang login.
	 * @param int $limit Jumlah proyek yang akan ditampilkan.
	 * @return array
	 */
	public function get_my_active_projects($user_id, $limit = 5)
	{
		$this->db->select('p.project_id, p.project_name');
		$this->db->from('portal_projects p');
		$this->db->join('portal_project_members pm', 'p.project_id = pm.project_id');
		$this->db->where('pm.user_id', $user_id);
		$this->db->where('p.status', 'active');
		$this->db->order_by('p.created_at', 'DESC');
		$this->db->limit($limit);
		return $this->db->get()->result_array();
	}
}
