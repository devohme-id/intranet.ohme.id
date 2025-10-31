<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Employee_model extends CI_Model
{

	/**
	 * Mengambil daftar karyawan dengan paginasi dan pencarian.
	 * @param int $limit Jumlah data per halaman.
	 * @param int $offset Posisi awal data.
	 * @param string|null $search_term Kata kunci pencarian.
	 * @return array Daftar karyawan.
	 */
	/**
	 * FUNGSI DIPERBARUI: Menambahkan JOIN ke tabel m_users untuk mengambil URL foto profil.
	 */
	public function get_employees($limit, $offset, $search_term = null)
	{
		// SELECT e.* akan mengambil semua kolom dari m_employees
		// SELECT u.profile_picture_url akan mengambil kolom foto dari m_users
		$this->db->select('e.*, u.profile_picture_url');
		$this->db->from('m_employees e');
		// Menggunakan LEFT JOIN untuk memastikan semua karyawan tetap tampil
		// meskipun mereka belum memiliki akun pengguna.
		$this->db->join('m_users u', 'e.employee_id = u.employee_id', 'left');
		$this->db->where('e.is_active', 1);

		if ($search_term) {
			$this->db->group_start();
			$this->db->like('e.full_name', $search_term);
			$this->db->or_like('e.nik', $search_term);
			$this->db->or_like('e.department', $search_term);
			$this->db->or_like('e.position', $search_term);
			$this->db->group_end();
		}

		$this->db->order_by('e.full_name', 'ASC');
		$this->db->limit($limit, $offset);
		return $this->db->get()->result_array();
	}

	/**
	 * Menghitung total jumlah karyawan untuk paginasi.
	 * @param string|null $search_term Kata kunci pencarian.
	 * @return int Jumlah total karyawan.
	 */
	public function count_employees($search_term = null)
	{
		$this->db->from('m_employees');
		$this->db->where('is_active', 1);

		if ($search_term) {
			$this->db->group_start();
			$this->db->like('full_name', $search_term);
			$this->db->or_like('nik', $search_term);
			$this->db->or_like('department', $search_term);
			$this->db->or_like('position', $search_term);
			$this->db->group_end();
		}

		return $this->db->count_all_results();
	}
}
