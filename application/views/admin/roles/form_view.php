<?php
$is_edit = isset($role);
$form_action = $is_edit ? site_url('admin/roles/edit/' . $role->role_id) : site_url('admin/roles/add');
$is_admin_role = $is_edit && ($role->role_name === 'Administrator' || $role->role_id == 1);
?>
<form action="<?php echo $form_action; ?>" method="post">
	<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
	<input type="hidden" name="update_role" value="1" />

	<div class="bg-white p-8 rounded-lg shadow-md mb-8">
		<h2 class="text-2xl font-semibold mb-6"><?php echo $is_edit ? 'Edit Peran' : 'Tambah Peran Baru'; ?></h2>
		<div class="grid md:grid-cols-2 gap-6">
			<div>
				<label class="block mb-2 text-sm font-medium">Nama Peran</label>
				<input type="text" name="role_name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($role->role_name) : ''; ?>" <?php if ($is_admin_role) {
    echo 'readonly';
} ?> required>
				<?php if ($is_admin_role): ?>
					<p class="mt-1 text-xs text-gray-500">Peran Administrator tidak dapat diubah namanya.</p>
				<?php endif; ?>
			</div>
			<div>
				<label class="block mb-2 text-sm font-medium">Deskripsi</label>
				<input type="text" name="description" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($role->description) : ''; ?>">
			</div>
		</div>
		<div class="mt-6">
			<label class="flex items-center">
				<input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300" <?php echo ($is_edit && $role->is_active) || !$is_edit ? 'checked' : ''; ?> <?php if ($is_admin_role) {
    echo 'onclick="return false;"';
} ?>>
				<span class="ml-2 text-sm font-medium text-gray-900">Aktifkan Peran</span>
			</label>
		</div>
	</div>

	<?php if ($is_edit): ?>
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h2 class="text-2xl font-semibold mb-6">Kelola Hak Akses untuk "<?php echo html_escape($role->role_name); ?>"</h2>
			<div class="space-y-8">
				<?php foreach ($all_permissions as $module => $permissions): ?>
					<div class="p-4 border rounded-lg">
						<div class="flex justify-between items-center mb-3 border-b pb-2">
							<h3 class="font-semibold text-lg"><?php echo $module; ?></h3>
							<?php if (!$is_admin_role): ?>
								<label class="flex items-center text-sm">
									<input type="checkbox" class="select-all-checkbox w-4 h-4 text-blue-600 rounded border-gray-300" data-module="<?php echo preg_replace('/[^a-zA-Z0-9]/', '', $module); ?>">
									<span class="ml-2 font-medium">Pilih Semua</span>
								</label>
							<?php endif; ?>
						</div>
						<div class="grid sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-3">
							<?php foreach ($permissions as $key => $label): ?>
								<label class="flex items-center">
									<input type="checkbox" name="permissions[]" value="<?php echo $key; ?>"
										class="permission-checkbox w-4 h-4 text-blue-600 rounded border-gray-300 module-<?php echo preg_replace('/[^a-zA-Z0-9]/', '', $module); ?>"
										<?php echo ($is_admin_role || in_array($key, $role_permissions)) ? 'checked' : ''; ?>
										<?php if ($is_admin_role) {
    echo 'onclick="return false;"';
} ?>>
									<span class="ml-3 text-sm text-gray-700"><?php echo $label; ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ($is_admin_role): ?>
				<p class="mt-4 text-sm text-blue-600 bg-blue-50 p-3 rounded-md">Peran Administrator secara otomatis memiliki semua hak akses.</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="mt-8 flex items-center space-x-4">
		<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
		<a href="<?php echo site_url('admin/roles'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
	</div>
</form>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const selectAllCheckboxes = document.querySelectorAll('.select-all-checkbox');

		selectAllCheckboxes.forEach(function(selectAll) {
			selectAll.addEventListener('change', function() {
				const moduleClass = '.module-' + this.dataset.module;
				const permissionCheckboxes = document.querySelectorAll(moduleClass);
				permissionCheckboxes.forEach(function(checkbox) {
					checkbox.checked = selectAll.checked;
				});
			});
		});
	});
</script>
