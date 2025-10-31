<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
<?php elseif ($this->session->flashdata('error')): ?>
	<div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-8">
	<!-- Kolom Edit Metadata Album -->
	<div class="lg:col-span-1">
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h3 class="text-xl font-semibold mb-6">Edit Detail Album</h3>
			<form action="<?php echo site_url('admin/galleries/manage/' . $gallery['gallery_id']); ?>" method="post">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Judul Album</label>
					<input type="text" name="title" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($gallery['title']); ?>" required>
				</div>
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Tanggal Kegiatan</label>
					<input type="date" name="event_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $gallery['event_date']; ?>" required>
				</div>
				<div class="mb-6">
					<label class="block mb-2 text-sm font-medium">Deskripsi</label>
					<textarea name="description" rows="4" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"><?php echo html_escape($gallery['description']); ?></textarea>
				</div>
				<button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Perubahan</button>
			</form>
		</div>
	</div>

	<!-- Kolom Upload & Daftar Foto -->
	<div class="lg:col-span-2">
		<div class="bg-white p-8 rounded-lg shadow-md mb-8">
			<h3 class="text-xl font-semibold mb-6">Unggah Foto Baru</h3>
			<form action="<?php echo site_url('admin/galleries/upload_photos/' . $gallery['gallery_id']); ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Pilih Foto (Bisa lebih dari satu)</label>
					<input type="file" name="photos[]" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer" multiple required>
					<p class="mt-1 text-xs text-gray-500">Tipe file: JPG, JPEG, PNG, GIF. Maks 2MB per file.</p>
				</div>
				<button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5">Unggah Foto</button>
			</form>
		</div>

		<div class="bg-white p-6 rounded-lg shadow-md">
			<h3 class="text-xl font-semibold mb-6">Daftar Foto di Album Ini</h3>
			<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
				<?php if (!empty($gallery['photos'])): ?>
					<?php foreach ($gallery['photos'] as $photo): ?>
						<div class="relative group">
							<img class="w-full h-32 object-cover rounded-lg" src="<?php echo base_url($photo['file_path']); ?>" alt="Foto">
							<div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-colors flex items-center justify-center">
								<a href="<?php echo site_url('admin/galleries/delete_photo/' . $gallery['gallery_id'] . '/' . $photo['photo_id']); ?>" onclick="return confirm('Anda yakin ingin menghapus foto ini?')" class="text-white opacity-0 group-hover:opacity-100 transition-opacity">
									<i data-feather="trash-2" class="w-6 h-6"></i>
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p class="col-span-full text-center text-gray-500 py-8">Belum ada foto di album ini.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
