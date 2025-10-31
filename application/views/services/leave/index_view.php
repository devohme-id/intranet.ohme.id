<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
	<!-- Kolom Kiri: Form Pengajuan & Info Kuota -->
	<div class="lg:col-span-2">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-4 border-b pb-3">Formulir Pengajuan Cuti</h2>

			<?php if ($this->session->flashdata('success')): ?>
				<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
			<?php elseif ($this->session->flashdata('error')): ?>
				<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('error'); ?></div>
			<?php endif; ?>

			<form action="<?php echo site_url('services/leave'); ?>" method="post">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Jenis Cuti</label>
					<select name="leave_type_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
						<option value="">-- Pilih Jenis Cuti --</option>
						<?php foreach ($leave_types as $type): ?>
							<option value="<?php echo $type['leave_type_id']; ?>"><?php echo $type['type_name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="grid md:grid-cols-2 gap-4 mb-4">
					<div>
						<label class="block mb-2 text-sm font-medium">Tanggal Mulai</label>
						<input type="date" name="start_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
					</div>
					<div>
						<label class="block mb-2 text-sm font-medium">Tanggal Selesai</label>
						<input type="date" name="end_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
					</div>
				</div>

				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Alasan Cuti</label>
					<textarea name="reason" rows="4" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required></textarea>
				</div>

				<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Ajukan Cuti</button>
			</form>
		</div>
	</div>

	<!-- Kolom Kanan: Sisa Kuota -->
	<div>
		<div class="bg-white p-6 rounded-lg shadow-md text-center">
			<h3 class="text-gray-500 font-medium mb-2">Sisa Cuti Tahunan <?php echo date('Y'); ?></h3>
			<p class="text-5xl font-bold text-blue-600"><?php echo number_format($leave_balance->balance, 0); ?></p>
			<p class="text-gray-500 font-medium mt-1">Hari</p>
		</div>
	</div>
</div>

<!-- Tabel Riwayat Pengajuan -->
<div class="bg-white p-6 rounded-lg shadow-md mt-6">
	<h2 class="text-xl font-semibold mb-4 border-b pb-3">Riwayat Pengajuan Cuti</h2>
	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-6 py-3">Jenis Cuti</th>
					<th class="px-6 py-3">Tanggal</th>
					<th class="px-6 py-3 text-center">Durasi</th>
					<th class="px-6 py-3">Status</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($leave_history as $history): ?>
					<tr class="bg-white border-b hover:bg-gray-50">
						<td class="px-6 py-4 font-medium"><?php echo $history['type_name']; ?></td>
						<td class="px-6 py-4"><?php echo date('d M Y', strtotime($history['start_date'])); ?> - <?php echo date('d M Y', strtotime($history['end_date'])); ?></td>
						<td class="px-6 py-4 text-center"><?php echo $history['total_days']; ?> hari</td>
						<td class="px-6 py-4">
							<span class="px-2 py-1 text-xs font-medium rounded-full 
                            <?php
							switch ($history['status']) {
								case 'approved_hr':
									echo 'bg-green-100 text-green-800';
									break;
								case 'rejected':
									echo 'bg-red-100 text-red-800';
									break;
								default:
									echo 'bg-yellow-100 text-yellow-800';
							}
							?>">
								<?php echo ucfirst(str_replace('_', ' ', $history['status'])); ?>
							</span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
