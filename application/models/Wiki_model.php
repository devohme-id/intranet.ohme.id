<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Wiki_model extends CI_Model
{

	// == Kategori ==
	public function get_all_categories()
	{
		$this->db->select('c.*, (SELECT COUNT(a.article_id) FROM portal_wiki_articles a WHERE a.category_id = c.category_id) as article_count');
		$this->db->from('portal_wiki_categories c');
		$this->db->order_by('c.category_name', 'ASC');
		return $this->db->get()->result_array();
	}

	public function get_category_by_id($id)
	{
		return $this->db->get_where('portal_wiki_categories', ['category_id' => $id])->row();
	}

	public function insert_category($data)
	{
		return $this->db->insert('portal_wiki_categories', $data);
	}

	public function update_category($id, $data)
	{
		$this->db->where('category_id', $id);
		return $this->db->update('portal_wiki_categories', $data);
	}

	public function is_category_in_use($id)
	{
		$count = $this->db->where('category_id', $id)->count_all_results('portal_wiki_articles');
		return $count > 0;
	}

	public function delete_category($id)
	{
		if ($this->is_category_in_use($id)) {
			return false;
		}
		return $this->db->delete('portal_wiki_categories', ['category_id' => $id]);
	}

	// == Artikel ==
	/**
	 * FUNGSI DIPERBARUI: Menambahkan parameter limit dan offset untuk paginasi.
	 */
	public function get_articles_with_category($limit, $offset, $search = null)
	{
		$this->db->select('a.*, c.category_name, u.full_name as last_author');
		$this->db->from('portal_wiki_articles a');
		$this->db->join('portal_wiki_categories c', 'a.category_id = c.category_id');
		$this->db->join('m_users u', 'a.last_updated_by = u.user_id', 'left');

		if ($search) {
			$this->db->group_start();
			$this->db->like('a.title', $search);
			$this->db->or_like('c.category_name', $search);
			$this->db->group_end();
		}

		$this->db->order_by('a.last_updated_at', 'DESC');
		$this->db->limit($limit, $offset);
		return $this->db->get()->result_array();
	}

	/**
	 * FUNGSI BARU: Menghitung total artikel untuk paginasi.
	 */
	public function count_articles($search = null)
	{
		$this->db->from('portal_wiki_articles a');
		$this->db->join('portal_wiki_categories c', 'a.category_id = c.category_id');

		if ($search) {
			$this->db->group_start();
			$this->db->like('a.title', $search);
			$this->db->or_like('c.category_name', $search);
			$this->db->group_end();
		}

		return $this->db->count_all_results();
	}

	public function get_article_by_id($id)
	{
		$this->db->select('a.*, r.content');
		$this->db->from('portal_wiki_articles a');
		$this->db->join('portal_wiki_revisions r', 'a.current_revision_id = r.revision_id', 'left');
		$this->db->where('a.article_id', $id);
		return $this->db->get()->row();
	}

	public function insert_article($data)
	{
		$this->db->insert('portal_wiki_articles', $data);
		return $this->db->insert_id();
	}

	public function update_article($id, $data)
	{
		$this->db->where('article_id', $id);
		return $this->db->update('portal_wiki_articles', $data);
	}

	// == Revisi ==
	public function insert_revision($data)
	{
		$this->db->insert('portal_wiki_revisions', $data);
		return $this->db->insert_id();
	}

	public function get_article_revisions($article_id)
	{
		$this->db->select('r.revision_id, r.edit_summary, r.edited_at, u.full_name as editor_name');
		$this->db->from('portal_wiki_revisions r');
		$this->db->join('m_users u', 'r.edited_by = u.user_id', 'left');
		$this->db->where('r.article_id', $article_id);
		$this->db->order_by('r.edited_at', 'DESC');
		return $this->db->get()->result_array();
	}
}
