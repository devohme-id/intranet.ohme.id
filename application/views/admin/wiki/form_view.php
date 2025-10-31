<?php
$is_edit = isset($article);
$form_action = $is_edit ? site_url('admin/wiki/edit/' . $article->article_id) : site_url('admin/wiki/add');
?>
<!-- Load TinyMCE SelfHosted -->
<script src="<?= base_url('assets/tinymce/tinymce.min.js'); ?>"></script>
<script>
	tinymce.init({
		selector: 'textarea#content-editor',
		height: 500,
		license_key: 'gpl',
		plugins: 'lists link image table code help wordcount',
		toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | code | table'
	});
</script>

<div class="bg-white p-8 rounded-lg shadow-md">
	<form action="<?php echo $form_action; ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
		<div class="mb-4">
			<label class="block mb-2 text-sm font-medium">Judul Artikel</label>
			<input type="text" name="title" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($article->title) : ''; ?>" required>
		</div>
		<div class="mb-4">
			<label class="block mb-2 text-sm font-medium">Kategori</label>
			<select name="category_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
				<?php foreach ($categories as $cat): ?>
					<option value="<?php echo $cat['category_id']; ?>" <?php echo ($is_edit && $article->category_id == $cat['category_id']) ? 'selected' : ''; ?>><?php echo html_escape($cat['category_name']); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="mb-4">
			<label class="block mb-2 text-sm font-medium">Konten Artikel</label>
			<textarea id="content-editor" name="content"><?php echo $is_edit ? html_escape($article->content) : ''; ?></textarea>
		</div>
		<?php if ($is_edit): ?>
			<div class="mb-6">
				<label class="block mb-2 text-sm font-medium">Ringkasan Perubahan</label>
				<input type="text" name="edit_summary" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Contoh: Menambahkan detail pada bagian X">
			</div>
		<?php endif; ?>
		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Artikel</button>
			<a href="<?php echo site_url('admin/wiki'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>
