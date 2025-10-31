<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Gallery_model extends CI_Model
{

	// == PUBLIC ==
	public function get_galleries()
	{
		$this->db->select('g.*, (SELECT COUNT(p.photo_id) FROM portal_gallery_photos p WHERE p.gallery_id = g.gallery_id) as photo_count');
		$this->db->select('(SELECT p.file_path FROM portal_gallery_photos p WHERE p.gallery_id = g.gallery_id ORDER BY p.photo_id ASC LIMIT 1) as cover_image');
		$this->db->from('portal_galleries g');
		$this->db->order_by('g.event_date', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_gallery_with_photos($id)
	{
		$gallery = $this->db->get_where('portal_galleries', ['gallery_id' => $id])->row_array();
		if ($gallery) {
			$gallery['photos'] = $this->db->get_where('portal_gallery_photos', ['gallery_id' => $id])->result_array();
		}
		return $gallery;
	}

	// == ADMIN ==
	public function get_gallery_by_id($id)
	{
		return $this->db->get_where('portal_galleries', ['gallery_id' => $id])->row();
	}

	public function insert_gallery($data)
	{
		$this->db->insert('portal_galleries', $data);
		return $this->db->insert_id();
	}

	public function update_gallery($id, $data)
	{
		$this->db->where('gallery_id', $id);
		return $this->db->update('portal_galleries', $data);
	}

	public function delete_gallery_and_photos($id)
	{
		$gallery = $this->get_gallery_with_photos($id);
		if ($gallery && !empty($gallery['photos'])) {
			foreach ($gallery['photos'] as $photo) {
				if (file_exists($photo['file_path'])) {
					unlink($photo['file_path']);
				}
			}
		}
		$this->db->delete('portal_gallery_photos', ['gallery_id' => $id]);
		return $this->db->delete('portal_galleries', ['gallery_id' => $id]);
	}

	public function insert_photos($data)
	{
		return $this->db->insert_batch('portal_gallery_photos', $data);
	}

	public function delete_photo($photo_id)
	{
		$photo = $this->db->get_where('portal_gallery_photos', ['photo_id' => $photo_id])->row();
		if ($photo) {
			if (file_exists($photo->file_path)) {
				unlink($photo->file_path);
			}
			return $this->db->delete('portal_gallery_photos', ['photo_id' => $photo_id]);
		}
		return false;
	}
}
