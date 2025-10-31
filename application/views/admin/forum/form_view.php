<?php
$is_edit = isset($category);
$form_action = $is_edit ? site_url('admin/forum/edit/' . $category->category_id) : site_url('admin/forum/add');
?>
<div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
	<form action="<?php echo $form_action; ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="mb-4">
			<label for="category_name" class="block mb-2 text-sm font-medium">Nama Kategori</label>
			<input type="text" name="category_name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($category->category_name) : ''; ?>" required>
		</div>

		<div class="mb-4">
			<label for="description" class="block mb-2 text-sm font-medium">Deskripsi</label>
			<textarea name="description" rows="3" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"><?php echo $is_edit ? html_escape($category->description) : ''; ?></textarea>
		</div>

		<div class="mb-6">
			<label class="flex items-center">
				<input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300" <?php echo ($is_edit && $category->is_active) || !$is_edit ? 'checked' : ''; ?>>
				<span class="ml-2 text-sm font-medium text-gray-900">Aktifkan Kategori</span>
			</label>
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
			<a href="<?php echo site_url('admin/forum'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>
