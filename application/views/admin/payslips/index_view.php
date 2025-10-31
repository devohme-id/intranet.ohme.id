<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
<?php elseif ($this->session->flashdata('error')): ?>
	<div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-8">
	<!-- Form Upload -->
	<div class="lg:col-span-1">
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-6">Unggah Slip Gaji</h2>
			<form action="<?php echo site_url('admin/payslips'); ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Pilih Karyawan</label>
					<select name="employee_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
						<option value="">-- Pilih Karyawan --</option>
						<?php foreach ($employees as $emp): ?>
							<option value="<?php echo $emp['employee_id']; ?>"><?php echo html_escape($emp['full_name']) . ' (' . $emp['nik'] . ')'; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="grid grid-cols-2 gap-4 mb-4">
					<div>
						<label class="block mb-2 text-sm font-medium">Bulan</label>
						<select name="period_month" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
							<?php for ($m = 1; $m <= 12; ++$m): ?>
								<option value="<?php echo $m; ?>" <?php if ($m == date('m')) {
    echo 'selected';
} ?>><?php echo date('F', mktime(0, 0, 0, $m, 1)); ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div>
						<label class="block mb-2 text-sm font-medium">Tahun</label>
						<input type="number" name="period_year" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo date('Y'); ?>" required>
					</div>
				</div>
				<div class="mb-6">
					<label class="block mb-2 text-sm font-medium">Pilih File PDF</label>
					<input type="file" name="payslip_file" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer" required>
					<p class="mt-1 text-xs text-gray-500">Tipe file: PDF. Maks 1MB.</p>
				</div>
				<button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Unggah</button>
			</form>
		</div>
	</div>

	<!-- Daftar Slip Gaji Terunggah -->
	<div class="lg:col-span-2">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-4">Riwayat Unggah Slip Gaji</h2>
			<div class="overflow-x-auto">
				<table class="w-full text-sm text-left text-gray-500">
					<thead class="text-xs text-gray-700 uppercase bg-gray-50">
						<tr>
							<th class="px-6 py-3">Karyawan</th>
							<th class="px-6 py-3">Periode</th>
							<th class="px-6 py-3">Tgl Unggah</th>
							<th class="px-6 py-3 text-right">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($payslips as $slip): ?>
							<tr class="bg-white border-b">
								<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($slip['full_name']); ?></td>
								<td class="px-6 py-4"><?php echo date('F', mktime(0, 0, 0, $slip['period_month'], 1)) . ' ' . $slip['period_year']; ?></td>
								<td class="px-6 py-4"><?php echo date('d M Y', strtotime($slip['uploaded_at'])); ?></td>
								<td class="px-6 py-4 text-right">
									<a href="<?php echo site_url('admin/payslips/delete/' . $slip['payslip_id']); ?>" onclick="return confirm('Anda yakin ingin menghapus slip gaji ini?')" class="font-medium text-red-600 hover:underline">Hapus</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
