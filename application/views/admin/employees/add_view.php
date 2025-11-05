<?php if (validation_errors()): ?>
	<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
		<span class="font-medium">Terjadi kesalahan!</span>
		<?php echo validation_errors('<div>', '</div>'); ?>
	</div>
<?php endif; ?>

<div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
	<form action="<?php echo site_url('admin/employees/add'); ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="grid md:grid-cols-2 gap-6">
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
				<input type="text" name="full_name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo set_value('full_name'); ?>" required>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">NIK <span class="text-red-500">*</span></label>
				<input type="text" name="nik" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo set_value('nik'); ?>" required>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">No Id Card</label>
				<input type="text" name="id_card_no" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo set_value('id_card_no'); ?>">
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Fingerprint</label>
				<input type="text" name="fingerprint_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo set_value('fingerprint_id'); ?>">
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Tanggal Lahir</label>
				<input type="date" name="birth_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo set_value('birth_date'); ?>">
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Join Date</label>
				<input type="date" name="join_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo set_value('join_date'); ?>">
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Jabatan</label>
				<select name="position" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
					<option value="">-- pilih position --</option>
					<?php foreach ($positions as $position): ?>
						<option value="<?php echo $position['position']; ?>" <?php echo set_select('position', $position['position']); ?>>
							<?php echo html_escape($position['position']); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Departemen</label>
				<select name="department" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
					<option value="">-- pilih departemen --</option>
					<?php foreach ($departments as $department): ?>
						<option value="<?php echo $department['department']; ?>" <?php echo set_select('department', $department['department']); ?>>
							<?php echo html_escape($department['department']); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="mb-6">
			<label class="block mb-2 text-sm font-medium">Grup</label>
			<select name="group_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
				<option value="">-- pilih grup --</option>
				<?php foreach ($groups as $group): ?>
					<option value="<?php echo $group['group_id']; ?>" <?php echo set_select('group_id', $group['group_id']); ?>>
						<?php echo html_escape($group['group_name']); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-6">
			<label class="block mb-2 text-sm font-medium">Atasan Langsung (Manager)</label>
			<select name="manager_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
				<option value="">-- Tidak Ada Atasan --</option>
				<?php foreach ($managers as $manager): ?>
					<option value="<?php echo $manager['employee_id']; ?>" <?php echo set_select('manager_id', $manager['employee_id']); ?>>
						<?php echo html_escape($manager['full_name']) . ' (' . html_escape($manager['nik']) . ')'; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-6">
			<label class="flex items-center">
				<input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300" checked>
				<span class="ml-2 text-sm font-medium text-gray-900">Karyawan Aktif</span>
			</label>
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Karyawan</button>
			<a href="<?php echo site_url('admin/employees'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>