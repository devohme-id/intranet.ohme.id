<?php
defined('BASEPATH') || exit('No direct script access allowed');

class App_launcher_model extends CI_Model
{

	public function get_apps_grouped()
	{
		$this->db->from('portal_apps');
		$this->db->where('is_active', 1);
		$this->db->order_by('category', 'ASC');
		$this->db->order_by('app_name', 'ASC');
		$apps = $this->db->get()->result_array();

		$grouped = [
			'Desktop' => [],
			'Web' => [],
			'Mobile' => []
		];

		foreach ($apps as $app) {
			$grouped[$app['category']][] = $app;
		}
		return $grouped;
	}

	public function get_all_for_admin()
	{
		return $this->db->order_by('app_name', 'ASC')->get('portal_apps')->result_array();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where('portal_apps', ['app_id' => $id])->row();
	}

	public function insert($data)
	{
		return $this->db->insert('portal_apps', $data);
	}

	public function update($id, $data)
	{
		$this->db->where('app_id', $id);
		return $this->db->update('portal_apps', $data);
	}

	public function delete($id)
	{
		return $this->db->delete('portal_apps', ['app_id' => $id]);
	}
}
