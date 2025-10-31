<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Role_model extends CI_Model
{

	public function get_roles()
	{
		$this->db->select('r.*, (SELECT COUNT(u.user_id) FROM m_users u WHERE u.role_id = r.role_id) as user_count');
		$this->db->from('m_roles r');
		$this->db->order_by('r.role_name', 'ASC');
		return $this->db->get()->result_array();
	}

	public function get_role_by_id($id)
	{
		return $this->db->get_where('m_roles', ['role_id' => $id])->row();
	}

	public function insert_role($data)
	{
		$this->db->insert('m_roles', $data);
		return $this->db->insert_id();
	}

	public function update_role($id, $data)
	{
		$this->db->where('role_id', $id);
		return $this->db->update('m_roles', $data);
	}

	public function delete_role($id)
	{
		// Pastikan tidak ada user yang menggunakan role ini sebelum dihapus
		$user_count = $this->db->where('role_id', $id)->count_all_results('m_users');
		if ($user_count > 0) {
			return false; // Gagal karena role masih digunakan
		}
		$this->db->delete('m_role_permissions', ['role_id' => $id]);
		$this->db->delete('m_roles', ['role_id' => $id]);
		return true;
	}

	public function get_role_permissions($role_id)
	{
		$permissions = $this->db->select('permission_key')->get_where('m_role_permissions', ['role_id' => $role_id])->result_array();
		return array_column($permissions, 'permission_key');
	}

	public function update_role_permissions($role_id, $permissions)
	{
		$this->db->trans_start();
		// 1. Hapus semua permission lama untuk role ini
		$this->db->delete('m_role_permissions', ['role_id' => $role_id]);
		// 2. Sisipkan permission baru jika ada
		if (!empty($permissions)) {
			$batch_data = [];
			foreach ($permissions as $key) {
				$batch_data[] = [
					'role_id' => $role_id,
					'permission_key' => $key
				];
			}
			$this->db->insert_batch('m_role_permissions', $batch_data);
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}
