<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-8">
	<!-- Kolom Detil Pengajuan -->
	<div class="lg:col-span-2 space-y-8">
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h3 class="text-xl font-semibold mb-6">Detail Pengajuan</h3>
			<dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
				<div class="sm:col-span-1">
					<dt class="text-sm font-medium text-gray-500">Nama Karyawan</dt>
					<dd class="mt-1 text-sm text-gray-900 font-semibold"><?php echo html_escape($request->employee_name); ?></dd>
				</div>
				<div class="sm:col-span-1">
					<dt class="text-sm font-medium text-gray-500">NIK</dt>
					<dd class="mt-1 text-sm text-gray-900"><?php echo html_escape($request->nik); ?></dd>
				</div>
				<div class="sm:col-span-1">
					<dt class="text-sm font-medium text-gray-500">Jenis Cuti</dt>
					<dd class="mt-1 text-sm text-gray-900"><?php echo html_escape($request->type_name); ?></dd>
				</div>
				<div class="sm:col-span-1">
					<dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
					<dd class="mt-1 text-sm text-gray-900"><?php echo date('d F Y', strtotime($request->created_at)); ?></dd>
				</div>
				<div class="sm:col-span-1">
					<dt class="text-sm font-medium text-gray-500">Periode Cuti</dt>
					<dd class="mt-1 text-sm text-gray-900"><?php echo date('d M Y', strtotime($request->start_date)) . ' s/d ' . date('d M Y', strtotime($request->end_date)); ?></dd>
				</div>
				<div class="sm:col-span-1">
					<dt class="text-sm font-medium text-gray-500">Total Hari</dt>
					<dd class="mt-1 text-sm text-gray-900"><?php echo $request->total_days; ?> hari</dd>
				</div>
				<div class="sm:col-span-2">
					<dt class="text-sm font-medium text-gray-500">Alasan</dt>
					<dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap"><?php echo html_escape($request->reason); ?></dd>
				</div>
			</dl>
		</div>
		<!-- Riwayat Persetujuan -->
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h3 class="text-xl font-semibold mb-4">Riwayat Persetujuan</h3>
			<ol class="relative border-l border-gray-200">
				<?php if (empty($approval_history)): ?>
					<li class="ml-4">
						<p class="text-sm text-gray-500">Belum ada riwayat persetujuan.</p>
					</li>
				<?php else: ?>
					<?php foreach ($approval_history as $history): ?>
						<li class="mb-6 ml-4">
							<div class="absolute w-3 h-3 <?php echo $history['status'] == 'approved' ? 'bg-green-500' : 'bg-red-500'; ?> rounded-full mt-1.5 -left-1.5 border border-white"></div>
							<time class="mb-1 text-sm font-normal leading-none text-gray-400"><?php echo date('d F Y, H:i', strtotime($history['created_at'])); ?></time>
							<h3 class="text-lg font-semibold text-gray-900"><?php echo ucfirst($history['status']); ?> by <?php echo html_escape($history['approver_name']); ?></h3>
							<p class="text-base font-normal text-gray-500">Level: <?php echo ucfirst($history['approval_level']); ?></p>
							<?php if (!empty($history['remarks'])): ?>
								<p class="mt-2 text-sm italic text-gray-600">"<?php echo html_escape($history['remarks']); ?>"</p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ol>
		</div>
	</div>
	<!-- Kolom Aksi -->
	<div class="lg:col-span-1">
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h3 class="text-xl font-semibold mb-6">Aksi Persetujuan</h3>
			<form action="<?php echo site_url('admin/leaves/view/' . $request->request_id); ?>" method="post">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<input type="hidden" name="update_status" value="1" />
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Ubah Status Ke</label>
					<select name="status" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
						<option value="submitted" <?php if ($request->status == 'submitted') {
    echo 'selected';
} ?>>Submitted</option>
						<option value="approved_manager" <?php if ($request->status == 'approved_manager') {
    echo 'selected';
} ?>>Approved by Manager</option>
						<option value="approved_hr" <?php if ($request->status == 'approved_hr') {
    echo 'selected';
} ?>>Approved by HR</option>
						<option value="rejected" <?php if ($request->status == 'rejected') {
    echo 'selected';
} ?>>Rejected</option>
						<option value="completed" <?php if ($request->status == 'completed') {
    echo 'selected';
} ?>>Completed</option>
					</select>
				</div>
				<div class="mb-6">
					<label class="block mb-2 text-sm font-medium">Catatan (Opsional)</label>
					<textarea name="remarks" rows="3" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"></textarea>
				</div>
				<button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Update Status</button>
			</form>
		</div>
	</div>
</div>
