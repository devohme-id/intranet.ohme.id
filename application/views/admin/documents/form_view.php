<?php
$is_edit = isset($document);
$form_action = $is_edit ? site_url('admin/documents/edit/' . $document->document_id) : site_url('admin/documents/add');
?>
<div class="bg-white p-8 rounded-lg shadow-md">
	<form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="grid md:grid-cols-2 gap-6">
			<div class="mb-4">
				<label for="title" class="block mb-2 text-sm font-medium text-gray-900">Judul Dokumen</label>
				<input type="text" name="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($document->title) : ''; ?>" required>
			</div>
			<div class="mb-4">
				<label for="document_code" class="block mb-2 text-sm font-medium text-gray-900">Kode Dokumen</label>
				<input type="text" name="document_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($document->document_code) : ''; ?>">
			</div>
		</div>

		<div class="mb-4">
			<label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
			<select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5" required>
				<?php foreach ($categories as $cat): ?>
					<option value="<?php echo $cat['category_id']; ?>" <?php echo ($is_edit && $document->category_id == $cat['category_id']) ? 'selected' : ''; ?>><?php echo html_escape($cat['category_name']); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-4">
			<label for="description" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
			<textarea name="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5"><?php echo $is_edit ? html_escape($document->description) : ''; ?></textarea>
		</div>

		<div class="mb-6">
			<label for="document_file" class="block mb-2 text-sm font-medium text-gray-900">Upload File</label>
			<input type="file" name="document_file" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer" <?php echo $is_edit ? '' : 'required'; ?>>
			<p class="mt-1 text-sm text-gray-500">Tipe file: PDF, DOC, DOCX, XLS, XLSX. Maks 5MB.</p>
			<?php if ($is_edit): ?>
				<p class="mt-1 text-sm text-blue-500">File saat ini: <?php echo $document->file_name; ?>. Kosongkan jika tidak ingin mengubah file.</p>
			<?php endif; ?>
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
			<a href="<?php echo site_url('admin/documents'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>
