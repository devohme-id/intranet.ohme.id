<div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
	<form action="<?php echo site_url('admin/employees/edit/' . $employee->employee_id); ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="grid md:grid-cols-2 gap-6">
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Nama Lengkap</label>
				<input type="text" name="full_name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->full_name); ?>" required>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">NIK</label>
				<input type="text" name="nik" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->nik); ?>" required>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Jabatan</label>
				<input type="text" name="position" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->position); ?>">
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Departemen</label>
				<input type="text" name="department" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->department); ?>">
			</div>
		</div>

		<div class="mb-6">
			<label class="block mb-2 text-sm font-medium">Atasan Langsung (Manager)</label>
			<select name="manager_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
				<option value="">-- Tidak Ada Atasan --</option>
				<?php foreach ($managers as $manager): ?>
					<option value="<?php echo $manager['employee_id']; ?>" <?php echo ($employee->manager_id == $manager['employee_id']) ? 'selected' : ''; ?>>
						<?php echo html_escape($manager['full_name']) . ' (' . html_escape($manager['nik']) . ')'; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-6">
			<label class="flex items-center">
				<input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300" <?php echo ($employee->is_active == 1) ? 'checked' : ''; ?>>
				<span class="ml-2 text-sm font-medium text-gray-900">Karyawan Aktif</span>
			</label>
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Perubahan</button>
			<a href="<?php echo site_url('admin/employees'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>
