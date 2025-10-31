<?php
$is_edit = isset($app);
$form_action = $is_edit ? site_url('admin/apps/edit/' . $app->app_id) : site_url('admin/apps/add');
?>
<div class="bg-white p-8 rounded-lg shadow-md max-w-3xl mx-auto">
	<form action="<?php echo $form_action; ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="grid md:grid-cols-2 gap-6">
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Nama Aplikasi</label>
				<input type="text" name="app_name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($app->app_name) : ''; ?>" required>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Kategori</label>
				<select name="category" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
					<option value="Desktop" <?php if ($is_edit && $app->category == 'Desktop') {
    echo 'selected';
} ?>>Desktop</option>
					<option value="Web" <?php if ($is_edit && $app->category == 'Web') {
    echo 'selected';
} ?>>Web</option>
					<option value="Mobile" <?php if ($is_edit && $app->category == 'Mobile') {
    echo 'selected';
} ?>>Mobile</option>
				</select>
			</div>
		</div>

		<div class="mb-4">
			<label class="block mb-2 text-sm font-medium">Deskripsi</label>
			<textarea name="description" rows="3" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"><?php echo $is_edit ? html_escape($app->description) : ''; ?></textarea>
		</div>

		<div class="mb-4">
			<label class="block mb-2 text-sm font-medium">URL / Path</label>
			<input type="text" name="url" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($app->url) : ''; ?>">
		</div>

		<div class="grid md:grid-cols-3 gap-6">
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Versi</label>
				<input type="text" name="version" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($app->version) : ''; ?>">
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Status</label>
				<!-- **PERUBAHAN: Mengganti input teks menjadi dropdown** -->
				<select name="status" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
					<option value="Stabil" <?php if ($is_edit && $app->status == 'Stabil') {
    echo 'selected';
} ?>>Stabil</option>
					<option value="Dalam Pengembangan" <?php if ($is_edit && $app->status == 'Dalam Pengembangan') {
    echo 'selected';
} ?>>Dalam Pengembangan</option>
					<option value="Beta" <?php if ($is_edit && $app->status == 'Beta') {
    echo 'selected';
} ?>>Beta</option>
					<option value="Dihentikan" <?php if ($is_edit && $app->status == 'Dihentikan') {
    echo 'selected';
} ?>>Dihentikan</option>
				</select>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Pengembang</label>
				<input type="text" name="developer" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($app->developer) : ''; ?>">
			</div>
		</div>

		<div class="mb-4">
			<label class="block mb-2 text-sm font-medium">Info Implementasi</label>
			<textarea name="implementation_info" rows="3" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"><?php echo $is_edit ? html_escape($app->implementation_info) : ''; ?></textarea>
		</div>

		<div class="mb-6">
			<label class="block mb-2 text-sm font-medium">Ikon (Nama dari Feather Icons)</label>
			<input type="text" name="icon" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($app->icon) : 'box'; ?>">
		</div>

		<div class="mb-6">
			<label class="flex items-center"><input type="checkbox" name="is_active" value="1" class="w-4 h-4" <?php echo ($is_edit && $app->is_active) || !$is_edit ? 'checked' : ''; ?>><span class="ml-2 text-sm">Aktifkan</span></label>
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
			<a href="<?php echo site_url('admin/apps'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>
