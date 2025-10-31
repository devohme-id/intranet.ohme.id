<?php
// Definisikan class untuk setiap status tab
$default_class = 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300';
$active_class = 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active';
?>
<div class="mb-6 border-b border-gray-200">
	<ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
		<li class="mr-2">
			<a href="<?php echo site_url('projects/view/' . $project->project_id); ?>"
				class="<?php echo ($active_tab == 'summary') ? $active_class : $default_class; ?>">
				Ringkasan
			</a>
		</li>
		<li class="mr-2">
			<a href="<?php echo site_url('projects/tasks/' . $project->project_id); ?>"
				class="<?php echo ($active_tab == 'tasks') ? $active_class : $default_class; ?>">
				Tugas
			</a>
		</li>
		<li class="mr-2">
			<a href="<?php echo site_url('projects/discussions/' . $project->project_id); ?>"
				class="<?php echo ($active_tab == 'discussions') ? $active_class : $default_class; ?>">
				Diskusi
			</a>
		</li>
		<li class="mr-2">
			<a href="<?php echo site_url('projects/files/' . $project->project_id); ?>"
				class="<?php echo ($active_tab == 'files') ? $active_class : $default_class; ?>">
				File
			</a>
		</li>
		<li class="mr-2">
			<a href="<?php echo site_url('projects/members/' . $project->project_id); ?>"
				class="<?php echo ($active_tab == 'members') ? $active_class : $default_class; ?>">
				Anggota
			</a>
		</li>
	</ul>
</div>
