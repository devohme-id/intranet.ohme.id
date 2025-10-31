<?php
defined('BASEPATH') || exit('No direct script access allowed');

class News_model extends CI_Model
{

	// == PUBLIC FACING (UPDATED) ==

	/**
	 * Mengambil daftar artikel berita dengan paginasi dan pencarian.
	 * @param int $limit Jumlah data per halaman.
	 * @param int $offset Posisi awal data.
	 * @param string|null $search Kata kunci pencarian.
	 * @return array
	 */
	public function get_paginated_published_articles($limit, $offset, $search = null)
	{
		$this->db->from('portal_news_articles');
		$this->db->where('status', 'published');

		if ($search) {
			$this->db->group_start();
			$this->db->like('title', $search);
			$this->db->or_like('content', $search);
			$this->db->group_end();
		}

		$this->db->order_by('published_at', 'DESC');
		$this->db->limit($limit, $offset);
		return $this->db->get()->result_array();
	}

	/**
	 * Menghitung total artikel yang dipublikasi untuk paginasi.
	 * @param string|null $search Kata kunci pencarian.
	 * @return int
	 */
	public function count_published_articles($search = null)
	{
		$this->db->from('portal_news_articles');
		$this->db->where('status', 'published');

		if ($search) {
			$this->db->group_start();
			$this->db->like('title', $search);
			$this->db->or_like('content', $search);
			$this->db->group_end();
		}

		return $this->db->count_all_results();
	}

	public function get_article_by_slug($slug)
	{
		$this->db->select('pa.*, u.full_name as author_name');
		$this->db->from('portal_news_articles pa');
		$this->db->join('m_users u', 'pa.author_id = u.user_id', 'left');
		$this->db->where('pa.slug', $slug);
		$this->db->where('pa.status', 'published');
		return $this->db->get()->row();
	}

	// == ADMIN CRUD (No changes needed here) ==
	public function get_articles($search = null)
	{
		$this->db->from('portal_news_articles');
		if ($search) {
			$this->db->like('title', $search);
		}
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_article_by_id($id)
	{
		return $this->db->get_where('portal_news_articles', ['article_id' => $id])->row();
	}

	public function insert_article($data)
	{
		$this->db->insert('portal_news_articles', $data);
		return $this->db->insert_id();
	}

	public function update_article($id, $data)
	{
		$this->db->where('article_id', $id);
		return $this->db->update('portal_news_articles', $data);
	}

	public function delete_article($id)
	{
		return $this->db->delete('portal_news_articles', ['article_id' => $id]);
	}
}
