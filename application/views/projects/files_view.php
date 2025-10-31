<div class="grid lg:grid-cols-3 gap-8">
	<!-- Form Upload File -->
	<div class="lg:col-span-1">
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-6">Unggah File Proyek</h2>
			<form action="<?php echo site_url('projects/files/' . $project->project_id); ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Pilih File</label>
					<input type="file" name="project_file" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer" required>
					<p class="mt-1 text-xs text-gray-500">Maks 10MB.</p>
				</div>
				<button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Unggah</button>
			</form>
		</div>
	</div>
	<!-- Daftar File -->
	<div class="lg:col-span-2">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-4">Daftar File Proyek</h2>
			<ul class="divide-y divide-gray-200">
				<?php foreach ($files as $file): ?>
					<li class="py-3 flex items-center justify-between">
						<div>
							<p class="font-medium text-gray-800"><?php echo html_escape($file['file_name']); ?></p>
							<p class="text-xs text-gray-500">Diunggah oleh <?php echo html_escape($file['uploader_name']); ?> pada <?php echo date('d M Y', strtotime($file['uploaded_at'])); ?></p>
						</div>
						<a href="<?php echo site_url('projects/download_file/' . $file['file_id']); ?>" class="text-blue-600 hover:text-blue-800"><i data-feather="download" class="w-5 h-5"></i></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
