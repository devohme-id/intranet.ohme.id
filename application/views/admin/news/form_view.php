<?php
$is_edit = isset($article);
$form_action = $is_edit ? site_url('admin/news/edit/' . $article->article_id) : site_url('admin/news/add');
?>
<!-- Load TinyMCE SelfHosted -->
<script src="<?= base_url('assets/tinymce/tinymce.min.js'); ?>"></script>
<script>
	tinymce.init({
		selector: 'textarea#content-editor',
		height: 400,
		license_key: 'gpl', // ✅ tambahkan ini
		plugins: 'lists link image table code help wordcount',
		toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
	});
</script>

<div class="bg-white p-8 rounded-lg shadow-md">
	<form action="<?php echo $form_action; ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="mb-4">
			<label for="title" class="block mb-2 text-sm font-medium text-gray-900">Judul Berita</label>
			<input type="text" name="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($article->title) : ''; ?>" required>
		</div>

		<div class="mb-4">
			<label for="content-editor" class="block mb-2 text-sm font-medium text-gray-900">Konten Berita</label>
			<textarea id="content-editor" name="content" rows="15"><?php echo $is_edit ? html_escape($article->content) : ''; ?></textarea>
		</div>

		<div class="mb-6">
			<label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
			<select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
				<option value="draft" <?php echo ($is_edit && $article->status == 'draft') ? 'selected' : ''; ?>>Draft</option>
				<option value="published" <?php echo ($is_edit && $article->status == 'published') ? 'selected' : ''; ?>>Published</option>
			</select>
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
			<a href="<?php echo site_url('admin/news'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>
