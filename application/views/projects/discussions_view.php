<!-- Header Proyek -->
<div class="bg-white p-6 rounded-lg shadow-md mb-8">
	<h1 class="text-3xl font-bold text-gray-800"><?php echo html_escape($project->project_name); ?></h1>
	<p class="mt-2 text-gray-600"><?php echo html_escape($project->description); ?></p>
</div>

<!-- Navigasi Tab yang Diperbaiki -->
<?php $this->load->view('projects/partials/tabs_nav', ['project' => $project, 'active_tab' => $active_tab]); ?>

<!-- Konten Halaman Diskusi -->
<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-8">
	<!-- Form Buat Topik -->
	<div class="lg:col-span-1">
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-6">Mulai Diskusi Baru</h2>
			<form action="<?php echo site_url('projects/discussions/' . $project->project_id); ?>" method="post">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Judul Diskusi</label>
					<input type="text" name="title" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
				</div>
				<div class="mb-6">
					<label class="block mb-2 text-sm font-medium">Pesan Pembuka</label>
					<textarea name="content" rows="5" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required></textarea>
				</div>
				<button type="submit" name="create_discussion" value="1" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Mulai Diskusi</button>
			</form>
		</div>
	</div>
	<!-- Daftar Topik Diskusi -->
	<div class="lg:col-span-2">
		<div class="bg-white rounded-lg shadow-md">
			<div class="p-6 border-b">
				<h2 class="text-xl font-semibold text-gray-800">Daftar Diskusi Proyek</h2>
			</div>
			<div class="divide-y divide-gray-200">
				<?php if (empty($discussions)): ?>
					<p class="p-6 text-center text-gray-500">Belum ada diskusi di proyek ini.</p>
				<?php else: ?>
					<?php foreach ($discussions as $disc): ?>
						<div class="p-6 hover:bg-gray-50">
							<div class="flex items-center justify-between">
								<div>
									<a href="<?php echo site_url('projects/discussion_thread/' . $disc['discussion_id']); ?>" class="text-lg font-semibold text-gray-900 hover:text-blue-600"><?php echo html_escape($disc['title']); ?></a>
									<p class="text-sm text-gray-500">
										oleh <?php echo html_escape($disc['author_name']); ?> &bull; <?php echo date('d M Y', strtotime($disc['created_at'])); ?>
									</p>
								</div>
								<div class="text-center">
									<p class="text-lg font-bold text-gray-800"><?php echo $disc['reply_count']; ?></p>
									<p class="text-xs text-gray-500">Balasan</p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
