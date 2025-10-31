<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Forum_model extends CI_Model
{

	// == Kategori (Fungsi Publik & Admin) ==
	public function get_all_categories()
	{
		$this->db->select('c.*, (SELECT COUNT(t.thread_id) FROM portal_forum_threads t WHERE t.category_id = c.category_id) as thread_count');
		$this->db->from('portal_forum_categories c');
		$this->db->order_by('c.category_name', 'ASC');
		return $this->db->get()->result_array();
	}

	/**
	 * **FUNGSI YANG DITAMBAHKAN**
	 * Mengambil satu data kategori berdasarkan ID-nya.
	 * @param int $id ID Kategori
	 * @return object|null
	 */
	public function get_category_by_id($id)
	{
		return $this->db->get_where('portal_forum_categories', ['category_id' => $id])->row();
	}

	public function insert_category($data)
	{
		return $this->db->insert('portal_forum_categories', $data);
	}

	public function update_category($id, $data)
	{
		$this->db->where('category_id', $id);
		return $this->db->update('portal_forum_categories', $data);
	}

	public function delete_category($id)
	{
		// Peringatan: Menghapus kategori bisa menyebabkan topik di dalamnya menjadi "yatim".
		// Implementasi profesional seharusnya menonaktifkan kategori atau memindahkan topiknya.
		return $this->db->delete('portal_forum_categories', ['category_id' => $id]);
	}

	// == Topik (Threads) ==
	public function get_threads_by_category($category_id)
	{
		$this->db->select('t.*, u.full_name as author_name, (SELECT COUNT(p.post_id) FROM portal_forum_posts p WHERE p.thread_id = t.thread_id) as post_count');
		$this->db->from('portal_forum_threads t');
		$this->db->join('m_users u', 't.user_id = u.user_id');
		$this->db->where('t.category_id', $category_id);
		$this->db->order_by('t.updated_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_thread_by_id($thread_id)
	{
		$this->db->select('t.*, u.full_name as author_name, c.category_name');
		$this->db->from('portal_forum_threads t');
		$this->db->join('m_users u', 't.user_id = u.user_id');
		$this->db->join('portal_forum_categories c', 't.category_id = c.category_id');
		$this->db->where('t.thread_id', $thread_id);
		return $this->db->get()->row();
	}

	public function create_thread($data)
	{
		$this->db->insert('portal_forum_threads', $data);
		return $this->db->insert_id();
	}

	// == Balasan (Posts) ==
	public function get_posts_by_thread($thread_id)
	{
		$this->db->select('p.*, u.full_name as author_name, e.position');
		$this->db->from('portal_forum_posts p');
		$this->db->join('m_users u', 'p.user_id = u.user_id');
		$this->db->join('m_employees e', 'u.employee_id = e.employee_id', 'left');
		$this->db->where('p.thread_id', $thread_id);
		$this->db->order_by('p.created_at', 'ASC');
		return $this->db->get()->result_array();
	}

	public function create_post($data)
	{
		$this->db->trans_start();
		$this->db->insert('portal_forum_posts', $data);
		// Update timestamp di thread utama
		$this->db->set('updated_at', 'NOW()', FALSE);
		$this->db->where('thread_id', $data['thread_id']);
		$this->db->update('portal_forum_threads');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}
