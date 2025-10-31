<div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
	<h2 class="text-xl font-semibold mb-4">Riwayat Slip Gaji Anda</h2>
	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-6 py-3">Periode</th>
					<th class="px-6 py-3">Tanggal Unggah</th>
					<th class="px-6 py-3 text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($payslips)): ?>
					<tr>
						<td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada slip gaji yang tersedia untuk Anda.</td>
					</tr>
				<?php else: ?>
					<?php
					$months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
					?>
					<?php foreach ($payslips as $slip): ?>
						<tr class="bg-white border-b hover:bg-gray-50">
							<td class="px-6 py-4 font-medium text-gray-900">
								<?php echo $months[$slip['period_month']] . ' ' . $slip['period_year']; ?>
							</td>
							<td class="px-6 py-4">
								<?php echo date('d F Y, H:i', strtotime($slip['uploaded_at'])); ?>
							</td>
							<td class="px-6 py-4 text-right">
								<a href="<?php echo site_url('services/payslip/download/' . $slip['payslip_id']); ?>" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">
									<i data-feather="download" class="w-4 h-4 mr-2"></i>
									Unduh
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
