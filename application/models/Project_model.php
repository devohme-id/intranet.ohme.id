<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Project_model extends CI_Model
{

	// == Proyek ==
	public function create_project($data)
	{
		$this->db->insert('portal_projects', $data);
		return $this->db->insert_id();
	}

	public function get_my_projects($user_id)
	{
		$this->db->select('p.*, u.full_name as owner_name');
		$this->db->from('portal_projects p');
		$this->db->join('portal_project_members pm', 'p.project_id = pm.project_id');
		$this->db->join('m_users u', 'p.owner_user_id = u.user_id');
		$this->db->where('pm.user_id', $user_id);
		$this->db->order_by('p.created_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_project_details($project_id)
	{
		$this->db->select('p.*, u.full_name as owner_name');
		$this->db->from('portal_projects p');
		$this->db->join('m_users u', 'p.owner_user_id = u.user_id');
		$this->db->where('p.project_id', $project_id);
		return $this->db->get()->row();
	}

	// == Anggota (Members) ==
	public function add_member($data)
	{
		$sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $this->db->insert_string('portal_project_members', $data));
		return $this->db->query($sql);
	}

	public function remove_member($project_id, $user_id)
	{
		return $this->db->delete('portal_project_members', ['project_id' => $project_id, 'user_id' => $user_id]);
	}

	public function get_project_members($project_id)
	{
		$this->db->select('u.user_id, e.full_name, e.position'); // Mengambil e.full_name
		$this->db->from('portal_project_members pm');
		$this->db->join('m_users u', 'pm.user_id = u.user_id');
		$this->db->join('m_employees e', 'u.employee_id = e.employee_id', 'left');
		$this->db->where('pm.project_id', $project_id);
		return $this->db->get()->result_array();
	}

	/**
	 * **FUNGSI YANG DIPERBAIKI**
	 * Mengambil daftar pengguna yang belum menjadi anggota proyek.
	 */
	public function get_non_members($project_id)
	{
		$sub_query = $this->db->select('user_id')->from('portal_project_members')->where('project_id', $project_id)->get_compiled_select();

		$this->db->select('u.user_id, e.full_name, e.nik'); // Mengambil e.full_name dan e.nik
		$this->db->from('m_users u');
		// Melakukan JOIN ke tabel m_employees untuk mendapatkan data yang benar
		$this->db->join('m_employees e', 'u.employee_id = e.employee_id', 'inner'); // INNER JOIN untuk memastikan hanya user yang juga karyawan
		$this->db->where("u.user_id NOT IN ($sub_query)", NULL, FALSE);
		$this->db->where('u.is_active', 1);
		$this->db->order_by('e.full_name', 'ASC');
		return $this->db->get()->result_array();
	}

	public function is_member($project_id, $user_id)
	{
		$count = $this->db->get_where('portal_project_members', ['project_id' => $project_id, 'user_id' => $user_id])->num_rows();
		return $count > 0;
	}

	// == Tugas (Tasks) ==
	public function get_tasks_by_project($project_id)
	{
		$this->db->select('t.*, u.full_name as assignee_name');
		$this->db->from('portal_project_tasks t');
		$this->db->join('m_users u', 't.assignee_user_id = u.user_id', 'left');
		$this->db->where('t.project_id', $project_id);
		return $this->db->get()->result_array();
	}

	public function create_task($data)
	{
		return $this->db->insert('portal_project_tasks', $data);
	}

	public function update_task_status($task_id, $status)
	{
		$this->db->where('task_id', $task_id);
		return $this->db->update('portal_project_tasks', ['status' => $status]);
	}

	// == File ==
	public function get_files_by_project($project_id)
	{
		$this->db->select('f.*, u.full_name as uploader_name');
		$this->db->from('portal_project_files f');
		$this->db->join('m_users u', 'f.uploader_user_id = u.user_id');
		$this->db->where('f.project_id', $project_id);
		$this->db->order_by('f.uploaded_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function add_file($data)
	{
		return $this->db->insert('portal_project_files', $data);
	}

	public function get_file_by_id($file_id)
	{
		return $this->db->get_where('portal_project_files', ['file_id' => $file_id])->row();
	}

	public function delete_file($file_id)
	{
		$file = $this->get_file_by_id($file_id);
		if ($file) {
			if (file_exists($file->file_path)) {
				unlink($file->file_path);
			}
			return $this->db->delete('portal_project_files', ['file_id' => $file_id]);
		}
		return false;
	}

	// == Diskusi Proyek ==
	public function create_discussion($data)
	{
		$this->db->insert('portal_project_discussions', $data);
		return $this->db->insert_id();
	}

	public function get_discussions_by_project($project_id)
	{
		$this->db->select('d.*, u.full_name as author_name, (SELECT COUNT(r.reply_id) FROM portal_project_discussion_replies r WHERE r.discussion_id = d.discussion_id) as reply_count');
		$this->db->from('portal_project_discussions d');
		$this->db->join('m_users u', 'd.user_id = u.user_id');
		$this->db->where('d.project_id', $project_id);
		$this->db->order_by('d.created_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_discussion_by_id($discussion_id)
	{
		$this->db->select('d.*, u.full_name as author_name');
		$this->db->from('portal_project_discussions d');
		$this->db->join('m_users u', 'd.user_id = u.user_id');
		$this->db->where('d.discussion_id', $discussion_id);
		return $this->db->get()->row();
	}

	public function create_discussion_reply($data)
	{
		return $this->db->insert('portal_project_discussion_replies', $data);
	}

	public function get_discussion_replies($discussion_id)
	{
		$this->db->select('r.*, u.full_name as author_name, e.position');
		$this->db->from('portal_project_discussion_replies r');
		$this->db->join('m_users u', 'r.user_id = u.user_id');
		$this->db->join('m_employees e', 'u.employee_id = e.employee_id', 'left');
		$this->db->where('r.discussion_id', $discussion_id);
		$this->db->order_by('r.created_at', 'ASC');
		return $this->db->get()->result_array();
	}
}
