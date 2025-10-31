<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Projects extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('Project_model', 'project_model');
		$this->load->library('upload');
	}

	private function _check_member($project_id)
	{
		if (!$this->project_model->is_member($project_id, $this->user_data['user_id'])) {
			show_error('Anda bukan anggota proyek ini.', 403, 'Akses Ditolak');
		}
	}

	public function index()
	{
		$data['projects'] = $this->project_model->get_my_projects($this->user_data['user_id']);
		$this->render_page('projects/index_view', $data, 'Ruang Kerja Proyek');
	}

	public function create()
	{
		if ($this->input->post()) {
			$this->db->trans_start();
			$project_id = $this->project_model->create_project(['project_name' => $this->input->post('project_name'), 'description' => $this->input->post('description'), 'owner_user_id' => $this->user_data['user_id']]);
			$this->project_model->add_member(['project_id' => $project_id, 'user_id' => $this->user_data['user_id']]);
			$this->db->trans_complete();
			$this->session->set_flashdata('success', 'Proyek baru berhasil dibuat.');
			redirect('projects/view/' . $project_id);
		}
		$this->render_page('projects/create_view', [], 'Buat Proyek Baru');
	}

	public function view($project_id)
	{
		$this->_check_member($project_id);
		$data['project'] = $this->project_model->get_project_details($project_id);
		$data['members'] = $this->project_model->get_project_members($project_id);
		if (!$data['project']) {
            show_404();
        }

		$data['active_tab'] = 'summary'; // Menandai tab aktif
		$this->render_page('projects/view_view', $data, $data['project']->project_name);
	}

	// --- Anggota ---
	public function members($project_id)
	{
		$this->_check_member($project_id);
		if ($this->input->post('add_member')) {
			$this->project_model->add_member(['project_id' => $project_id, 'user_id' => $this->input->post('user_id')]);
			$this->session->set_flashdata('success', 'Anggota baru berhasil ditambahkan.');
			redirect('projects/members/' . $project_id);
		}
		$data['project'] = $this->project_model->get_project_details($project_id);
		$data['members'] = $this->project_model->get_project_members($project_id);
		$data['non_members'] = $this->project_model->get_non_members($project_id);

		$data['active_tab'] = 'members'; // Menandai tab aktif
		$this->render_page('projects/members_view', $data, 'Kelola Anggota Proyek');
	}

	public function remove_member($project_id, $user_id)
	{
		$this->_check_member($project_id);
		$project = $this->project_model->get_project_details($project_id);
		if ($project->owner_user_id == $this->user_data['user_id'] && $user_id != $project->owner_user_id) {
			$this->project_model->remove_member($project_id, $user_id);
			$this->session->set_flashdata('success', 'Anggota berhasil dihapus.');
		}
		redirect('projects/members/' . $project_id);
	}

	// --- Tugas ---
	public function tasks($project_id)
	{
		$this->_check_member($project_id);
		if ($this->input->post('create_task')) {
			$this->project_model->create_task(['project_id' => $project_id, 'task_title' => $this->input->post('task_title'), 'assignee_user_id' => $this->input->post('assignee_user_id'), 'due_date' => $this->input->post('due_date'), 'created_by_user_id' => $this->user_data['user_id']]);
			$this->session->set_flashdata('success', 'Tugas baru berhasil dibuat.');
			redirect('projects/tasks/' . $project_id);
		}
		$data['project'] = $this->project_model->get_project_details($project_id);
		$data['tasks'] = $this->project_model->get_tasks_by_project($project_id);
		$data['members'] = $this->project_model->get_project_members($project_id);

		$data['active_tab'] = 'tasks'; // Menandai tab aktif
		$this->render_page('projects/tasks_view', $data, 'Manajemen Tugas');
	}

	public function update_task_status($task_id, $status)
	{
		$this->project_model->update_task_status($task_id, $status);
		echo json_encode(['success' => true]);
	}

	// --- File ---
	public function files($project_id)
	{
		$this->_check_member($project_id);
		if (!empty($_FILES['project_file']['name'])) {
			$config['upload_path']   = './uploads/projects/';
			$config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|xls|xlsx|zip|rar';
			$config['max_size']      = 10240; // 10MB
			$config['encrypt_name']  = TRUE;
			if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
			$this->upload->initialize($config);

			if ($this->upload->do_upload('project_file')) {
				$file = $this->upload->data();
				$this->project_model->add_file(['project_id' => $project_id, 'uploader_user_id' => $this->user_data['user_id'], 'file_name' => $file['orig_name'], 'file_path' => 'uploads/projects/' . $file['file_name']]);
				$this->session->set_flashdata('success', 'File berhasil diunggah.');
			} else {
				$this->session->set_flashdata('error', $this->upload->display_errors());
			}
			redirect('projects/files/' . $project_id);
		}
		$data['project'] = $this->project_model->get_project_details($project_id);
		$data['files'] = $this->project_model->get_files_by_project($project_id);

		$data['active_tab'] = 'files'; // Menandai tab aktif
		$this->render_page('projects/files_view', $data, 'File Proyek');
	}

	public function download_file($file_id)
	{
		$file = $this->project_model->get_file_by_id($file_id);
		if ($file) {
			$this->_check_member($file->project_id);
			$this->load->helper('download');
			force_download($file->file_path, NULL);
		} else {
			show_404();
		}
	}

	// --- Diskusi ---
	public function discussions($project_id)
	{
		$this->_check_member($project_id);
		if ($this->input->post('create_discussion')) {
			$this->project_model->create_discussion(['project_id' => $project_id, 'user_id' => $this->user_data['user_id'], 'title' => $this->input->post('title'), 'content' => $this->input->post('content')]);
			$this->session->set_flashdata('success', 'Topik diskusi baru berhasil dibuat.');
			redirect('projects/discussions/' . $project_id);
		}
		$data['project'] = $this->project_model->get_project_details($project_id);
		$data['discussions'] = $this->project_model->get_discussions_by_project($project_id);

		$data['active_tab'] = 'discussions'; // Menandai tab aktif
		$this->render_page('projects/discussions_view', $data, 'Diskusi Proyek');
	}

	public function discussion_thread($discussion_id)
	{
		$discussion = $this->project_model->get_discussion_by_id($discussion_id);
		if (!$discussion) {
            show_404();
        }
		$this->_check_member($discussion->project_id);

		if ($this->input->post('create_reply')) {
			$this->project_model->create_discussion_reply(['discussion_id' => $discussion_id, 'user_id' => $this->user_data['user_id'], 'content' => $this->input->post('content')]);
			$this->session->set_flashdata('success', 'Balasan Anda berhasil dikirim.');
			redirect('projects/discussion_thread/' . $discussion_id);
		}
		$data['project'] = $this->project_model->get_project_details($discussion->project_id);
		$data['discussion'] = $discussion;
		$data['replies'] = $this->project_model->get_discussion_replies($discussion_id);

		$data['active_tab'] = 'discussions'; // Menandai tab aktif
		$this->render_page('projects/discussion_thread_view', $data, $discussion->title);
	}
}
