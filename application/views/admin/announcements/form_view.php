<?php
$is_edit = isset($announcement);
$form_action = $is_edit ? site_url('admin/announcements/edit/' . $announcement->announcement_id) : site_url('admin/announcements/add');
?>
<div class="bg-white p-8 rounded-lg shadow-md">
	<form action="<?php echo $form_action; ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="mb-4">
			<label for="title" class="block mb-2 text-sm font-medium">Judul Pengumuman</label>
			<input type="text" name="title" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($announcement->title) : ''; ?>" required>
		</div>

		<div class="mb-4">
			<label for="content" class="block mb-2 text-sm font-medium">Isi Pengumuman</label>
			<textarea name="content" rows="5" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required><?php echo $is_edit ? html_escape($announcement->content) : ''; ?></textarea>
		</div>

		<div class="grid md:grid-cols-2 gap-6">
			<div class="mb-4">
				<label for="start_date" class="block mb-2 text-sm font-medium">Tanggal Mulai Tampil</label>
				<input type="date" name="start_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? $announcement->start_date : ''; ?>" required>
			</div>
			<div class="mb-4">
				<label for="end_date" class="block mb-2 text-sm font-medium">Tanggal Selesai Tampil</label>
				<input type="date" name="end_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? $announcement->end_date : ''; ?>" required>
			</div>
		</div>

		<div class="mb-6">
			<label class="flex items-center">
				<input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300" <?php echo ($is_edit && $announcement->is_active) || !$is_edit ? 'checked' : ''; ?>>
				<span class="ml-2 text-sm font-medium text-gray-900">Aktifkan Pengumuman</span>
			</label>
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
			<a href="<?php echo site_url('admin/announcements'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>
