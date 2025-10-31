<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
<?php elseif ($this->session->flashdata('info')): ?>
	<div class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('info'); ?></div>
<?php elseif ($this->session->flashdata('error')): ?>
	<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-8">
	<!-- Kolom Edit Metadata -->
	<div class="lg:col-span-2 bg-white p-8 rounded-lg shadow-md">
		<h3 class="text-xl font-semibold mb-6">Edit Metadata Dokumen</h3>
		<form action="<?php echo site_url('admin/documents/edit/' . $document->document_id); ?>" method="post">
			<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Judul Dokumen</label>
				<input type="text" name="title" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($document->title); ?>" required>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Kode Dokumen</label>
				<input type="text" name="document_code" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($document->document_code); ?>">
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Kategori</label>
				<select name="category_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
					<?php foreach ($categories as $cat): ?>
						<option value="<?php echo $cat['category_id']; ?>" <?php echo ($document->category_id == $cat['category_id']) ? 'selected' : ''; ?>><?php echo html_escape($cat['category_name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="mb-6">
				<label class="block mb-2 text-sm font-medium">Deskripsi</label>
				<textarea name="description" rows="4" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"><?php echo html_escape($document->description); ?></textarea>
			</div>
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Metadata</button>
		</form>
	</div>

	<!-- Kolom Upload Versi Baru & Daftar Versi -->
	<div class="space-y-8">
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h3 class="text-xl font-semibold mb-6">Unggah Versi Baru</h3>
			<form action="<?php echo site_url('admin/documents/add_version/' . $document->document_id); ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Nomor Versi (e.g., 1.1)</label>
					<input type="text" name="version_number" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
				</div>
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Catatan Revisi</label>
					<textarea name="remarks" rows="3" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"></textarea>
				</div>
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Pilih File</label>
					<input type="file" name="document_file" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer" required>
				</div>
				<button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5">Unggah Versi Baru</button>
			</form>
		</div>

		<div class="bg-white p-6 rounded-lg shadow-md">
			<h3 class="text-xl font-semibold mb-4">Riwayat Versi</h3>
			<ul class="divide-y divide-gray-200">
				<?php foreach ($versions as $v): ?>
					<li class="py-3">
						<p class="font-semibold text-gray-800">Versi <?php echo html_escape($v['version_number']); ?>
							<?php if ($document->current_version_id == $v['version_id']): ?>
								<span class="ml-2 text-xs font-medium text-green-800 bg-green-100 px-2.5 py-0.5 rounded-full">Aktif</span>
							<?php endif; ?>
						</p>
						<p class="text-sm text-gray-600"><?php echo html_escape($v['remarks']); ?></p>
						<p class="text-xs text-gray-500 mt-1">
							Diunggah oleh <?php echo html_escape($v['uploader_name'] ?? 'N/A'); ?> pada <?php echo date('d M Y H:i', strtotime($v['uploaded_at'])); ?>
						</p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
