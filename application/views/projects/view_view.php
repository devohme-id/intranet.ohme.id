<!-- Header Proyek -->
<div class="bg-white p-6 rounded-lg shadow-md mb-8">
	<h1 class="text-3xl font-bold text-gray-800"><?php echo html_escape($project->project_name); ?></h1>
	<p class="mt-2 text-gray-600"><?php echo html_escape($project->description); ?></p>
	<div class="mt-4 text-sm text-gray-500">
		<span>Pemilik: <strong><?php echo html_escape($project->owner_name); ?></strong></span>
		<span class="mx-2">&bull;</span>
		<span>Status: <strong class="text-green-600"><?php echo ucfirst($project->status); ?></strong></span>
	</div>
</div>

<!-- Navigasi Tab yang Diperbaiki -->
<?php $this->load->view('projects/partials/tabs_nav', ['project' => $project, 'active_tab' => $active_tab]); ?>

<!-- Konten Tab -->
<div class="grid lg:grid-cols-3 gap-8">
	<div class="lg:col-span-2">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h3 class="text-lg font-semibold mb-4">Aktivitas Proyek</h3>
			<p class="text-gray-500">Fitur aktivitas proyek akan tersedia di sini.</p>
		</div>
	</div>
	<div class="lg:col-span-1">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<div class="flex justify-between items-center mb-4">
				<h3 class="text-lg font-semibold">Anggota Tim</h3>
				<a href="<?php echo site_url('projects/members/' . $project->project_id); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Kelola</a>
			</div>
			<ul class="space-y-3">
				<?php foreach ($members as $member): ?>
					<li class="flex items-center">
						<img class="w-10 h-10 rounded-full mr-3 object-cover" src="https://placehold.co/40x40/818cf8/ffffff?text=<?php echo substr($member['full_name'], 0, 1); ?>" alt="avatar">
						<div>
							<p class="font-medium text-gray-800"><?php echo html_escape($member['full_name']); ?></p>
							<p class="text-xs text-gray-500"><?php echo html_escape($member['position'] ?? 'N/A'); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
