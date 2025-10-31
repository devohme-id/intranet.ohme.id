<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Document_model extends CI_Model
{

	// == PUBLIC FACING ==
	public function get_categories_with_documents()
	{
		$this->db->select('d.document_id, d.title, d.document_code, d.category_id, v.file_path');
		$this->db->from('portal_documents d');
		$this->db->join('portal_document_versions v', 'd.current_version_id = v.version_id', 'left');
		$documents = $this->db->order_by('d.title', 'ASC')->get()->result_array();

		$categories = $this->db->order_by('category_name', 'ASC')->get('portal_document_categories')->result_array();

		foreach ($categories as &$category) {
			$category['documents'] = array_filter($documents, function ($doc) use ($category) {
				return $doc['category_id'] == $category['category_id'];
			});
		}
		return $categories;
	}

	// == ADMIN CRUD ==

	/**
	 * **FUNGSI YANG DIPERBAIKI**
	 * Mengambil daftar dokumen beserta informasi versi aktifnya untuk halaman admin.
	 * @param string|null $search Kata kunci pencarian.
	 * @return array
	 */
	public function get_documents_with_version($search = null)
	{
		$this->db->select('d.*, c.category_name, v.version_number, v.file_name, v.uploaded_at as version_uploaded_at');
		$this->db->from('portal_documents d');
		$this->db->join('portal_document_categories c', 'd.category_id = c.category_id', 'left');
		$this->db->join('portal_document_versions v', 'd.current_version_id = v.version_id', 'left');
		if ($search) {
			$this->db->group_start();
			$this->db->like('d.title', $search);
			$this->db->or_like('d.document_code', $search);
			$this->db->or_like('c.category_name', $search);
			$this->db->group_end();
		}
		$this->db->order_by('d.last_updated_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_document_by_id($id)
	{
		return $this->db->get_where('portal_documents', ['document_id' => $id])->row();
	}

	public function get_document_versions($document_id)
	{
		$this->db->select('v.*, u.full_name as uploader_name');
		$this->db->from('portal_document_versions v');
		$this->db->join('m_users u', 'v.uploaded_by = u.user_id', 'left');
		$this->db->where('v.document_id', $document_id);
		$this->db->order_by('v.uploaded_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_version_by_id($version_id)
	{
		return $this->db->get_where('portal_document_versions', ['version_id' => $version_id])->row();
	}

	public function get_all_categories()
	{
		return $this->db->get('portal_document_categories')->result_array();
	}

	public function insert_document($data)
	{
		$this->db->insert('portal_documents', $data);
		return $this->db->insert_id();
	}

	public function insert_version($data)
	{
		$this->db->insert('portal_document_versions', $data);
		return $this->db->insert_id();
	}

	public function update_document($id, $data)
	{
		$this->db->where('document_id', $id);
		$this->db->update('portal_documents', $data);
		return $this->db->affected_rows();
	}

	public function delete_document_and_versions($id)
	{
		$versions = $this->get_document_versions($id);
		foreach ($versions as $version) {
			if (!empty($version['file_path']) && file_exists($version['file_path'])) {
				unlink($version['file_path']);
			}
		}
		$this->db->delete('portal_document_versions', ['document_id' => $id]);
		$this->db->delete('portal_documents', ['document_id' => $id]);
		return $this->db->affected_rows();
	}
}
