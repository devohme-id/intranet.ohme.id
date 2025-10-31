<div class="flex justify-between items-center mb-6">
	<div>
		<h2 class="text-2xl font-bold text-gray-800">Ruang Kerja Proyek</h2>
		<p class="text-gray-500">Berkolaborasi dengan tim Anda di satu tempat.</p>
	</div>
	<a href="<?php echo site_url('projects/create'); ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
		<i data-feather="plus" class="w-4 h-4 mr-2"></i>Buat Proyek Baru
	</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
	<?php if (empty($projects)): ?>
		<p class="col-span-full text-center text-gray-500 py-16">Anda belum menjadi anggota proyek mana pun.</p>
	<?php else: ?>
		<?php foreach ($projects as $project): ?>
			<a href="<?php echo site_url('projects/view/' . $project['project_id']); ?>" class="block bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
				<span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full <?php echo $project['status'] == 'active' ? 'text-green-700 bg-green-100' : 'text-gray-700 bg-gray-100'; ?>">
					<?php echo ucfirst($project['status']); ?>
				</span>
				<h3 class="text-lg font-semibold text-gray-900 mt-2"><?php echo html_escape($project['project_name']); ?></h3>
				<p class="text-sm text-gray-500 mt-1 line-clamp-2"><?php echo html_escape($project['description']); ?></p>
				<div class="border-t mt-4 pt-3 text-xs text-gray-500">
					Pemilik: <?php echo html_escape($project['owner_name']); ?>
				</div>
			</a>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
