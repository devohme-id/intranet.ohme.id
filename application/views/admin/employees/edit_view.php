<div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
	<form action="<?php echo site_url('admin/employees/edit/' . $employee->employee_id); ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="grid md:grid-cols-2 gap-6">
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
				<input type="text" name="full_name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->full_name); ?>" required>
				<?php echo form_error('full_name','<span class="text-red-500">', '</span>'); ?>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">NIK <span class="text-red-500">*</span></label>
				<input type="text" name="nik" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->nik); ?>" required>
				<?php echo form_error('nik','<span class="text-red-500">', '</span>'); ?>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">No Id Card <span class="text-red-500">*</span></label>
				<input type="text" name="id_card_no" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->id_card_no); ?>">
				<?php echo form_error('id_card_no','<span class="text-red-500">', '</span>'); ?>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Fingerprint <span class="text-red-500">*</span></label>
				<input type="text" name="fingerprint_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->fingerprint_id); ?>">
				<?php echo form_error('fingerprint_id','<span class="text-red-500">', '</span>'); ?>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Tanggal Lahir <span class="text-red-500">*</span></label>
				<input type="date" name="birth_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->birth_date); ?>">
				<?php echo form_error('birth_date','<span class="text-red-500">', '</span>'); ?>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Join Date <span class="text-red-500">*</span></label>
				<input type="date" name="join_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($employee->join_date); ?>">
				<?php echo form_error('join_date','<span class="text-red-500">', '</span>'); ?>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Jabatan <span class="text-red-500">*</span></label>
				<select name="position" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
					<option value="">-- pilih departemen --</option>
					<?php foreach ($positions as $position): ?>
						<option value="<?php echo $position['position']; ?>" <?php echo html_escape($employee->position == $position['position']) ? 'selected' : ''; ?>>
							<?php echo html_escape($position['position']); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Departemen <span class="text-red-500">*</span></label>
				<select name="department" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
					<option value="">-- pilih departemen --</option>
					<?php foreach ($departments as $department): ?>
						<option value="<?php echo $department['department']; ?>" <?php echo html_escape($employee->department == $department['department']) ? 'selected' : ''; ?>>
							<?php echo html_escape($department['department']); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="mb-6">
			<label class="block mb-2 text-sm font-medium">Grup <span class="text-red-500">*</span></label>
			<select name="group_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
				<option value="">-- pilih grup --</option>
				<?php foreach ($groups as $group): ?>
					<option value="<?php echo $group['group_id']; ?>" <?php echo html_escape($employee->group_id == $group['group_id']) ? 'selected' : ''; ?>>
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
					<option value="<?php echo $manager['employee_id']; ?>" <?php echo html_escape($employee->manager_id == $manager['employee_id']) ? 'selected' : ''; ?>>
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