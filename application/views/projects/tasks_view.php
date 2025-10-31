<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
	<!-- Kolom To Do -->
	<div class="bg-gray-100 rounded-lg p-4">
		<h3 class="font-bold text-lg mb-4">To Do</h3>
		<div class="space-y-3">
			<?php foreach ($tasks as $task): if ($task['status'] == 'todo'): ?>
					<div class="bg-white p-3 rounded-md shadow-sm">
						<p><?php echo html_escape($task['task_title']); ?></p>
						<p class="text-xs text-gray-500 mt-2">Ditugaskan kepada: <?php echo html_escape($task['assignee_name'] ?? 'Belum ada'); ?></p>
					</div>
			<?php endif;
			endforeach; ?>
		</div>
	</div>
	<!-- Kolom In Progress -->
	<div class="bg-gray-100 rounded-lg p-4">
		<h3 class="font-bold text-lg mb-4">In Progress</h3>
		<div class="space-y-3">
			<?php foreach ($tasks as $task): if ($task['status'] == 'in_progress'): ?>
					<div class="bg-white p-3 rounded-md shadow-sm">
						<p><?php echo html_escape($task['task_title']); ?></p>
						<p class="text-xs text-gray-500 mt-2">Ditugaskan kepada: <?php echo html_escape($task['assignee_name'] ?? 'Belum ada'); ?></p>
					</div>
			<?php endif;
			endforeach; ?>
		</div>
	</div>
	<!-- Kolom Done -->
	<div class="bg-gray-100 rounded-lg p-4">
		<h3 class="font-bold text-lg mb-4">Done</h3>
		<div class="space-y-3">
			<?php foreach ($tasks as $task): if ($task['status'] == 'done'): ?>
					<div class="bg-white p-3 rounded-md shadow-sm opacity-70">
						<p class="line-through"><?php echo html_escape($task['task_title']); ?></p>
						<p class="text-xs text-gray-500 mt-2">Ditugaskan kepada: <?php echo html_escape($task['assignee_name'] ?? 'Belum ada'); ?></p>
					</div>
			<?php endif;
			endforeach; ?>
		</div>
	</div>
</div>
<!-- Form Tambah Tugas (Contoh sederhana) -->
<div class="mt-8 bg-white p-6 rounded-lg shadow-md">
	<h3 class="text-lg font-semibold mb-4">Tambah Tugas Baru</h3>
	<form action="<?php echo site_url('projects/tasks/' . $project->project_id); ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
		<div class="grid md:grid-cols-3 gap-4">
			<input type="text" name="task_title" placeholder="Nama Tugas" class="md:col-span-2 bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
			<select name="assignee_user_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
				<option value="">-- Tugaskan kepada --</option>
				<?php foreach ($members as $member): ?>
					<option value="<?php echo $member['user_id']; ?>"><?php echo html_escape($member['full_name']); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<button type="submit" name="create_task" value="1" class="mt-4 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Tambah Tugas</button>
	</form>
</div>
