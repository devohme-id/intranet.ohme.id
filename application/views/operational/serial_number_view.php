<div class="bg-white p-6 rounded-lg shadow-md">
	<!-- Header Halaman -->
	<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
		<div>
			<a href="<?php echo site_url('operational/production_orders'); ?>" class="inline-flex items-center text-sm text-pink-600 hover:text-pink-800 font-semibold mb-2 transition-colors">
				<i data-feather="arrow-left" class="w-4 h-4 mr-1"></i>
				Kembali ke Pesanan Produksi
			</a>
			<h2 class="text-2xl font-bold text-gray-800">Detail Serial Number</h2>
			<p class="text-sm text-gray-500">Work Order: <?php echo html_escape($wo->prod_order_no); ?></p>
		</div>
		<!-- Form Pencarian -->
		<div class="w-full md:w-1/3">
			<form action="<?php echo site_url('operational/production_orders/view_serials/' . urlencode($wo->prod_order_no)); ?>" method="get">
				<div class="relative">
					<input type="search" name="search" class="block w-full p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-pink-300 focus:border-pink-500" placeholder="Cari serial number..." value="<?php echo html_escape($search_term); ?>">
					<button type="submit" class="text-white absolute right-1.5 top-1/2 -translate-y-1/2 bg-pink-600 hover:bg-pink-700 font-medium rounded-md text-sm px-4 py-1.5">Cari</button>
				</div>
			</form>
		</div>
	</div>

	<!-- **BLOK DIPERBARUI**: Overall Progress Work Order dengan Logika Akurat -->
	<div class="bg-white p-6 rounded-lg shadow-md mb-6 border border-gray-200">
		<h3 class="text-lg font-semibold text-gray-800">Overall Work Order Progress</h3>
		<p class="text-sm text-gray-500 mb-3">
			Progress penyelesaian berdasarkan jumlah unit yang telah lulus semua proses (SMT, BPR, &amp; DMS).
		</p>

		<div class="flex justify-between items-center mb-1">
			<span class="text-base font-medium text-pink-700">
				Unit Selesai: <?php echo number_format($total_completed_units); ?> / <?php echo number_format($total_planned_qty); ?> Unit
			</span>
			<span class="text-lg font-semibold text-pink-700">
				<?php echo $overall_progress; ?>%
			</span>
		</div>

		<!-- Progress Bar Container -->
		<div class="w-full bg-gray-200 rounded-full h-4">
			<!-- Progress Bar Filler -->
			<div class="bg-pink-600 h-4 rounded-full transition-all duration-500" style="width: <?php echo $overall_progress; ?>%"></div>
		</div>
	</div>

	<!-- Tabel Data Serial Number -->
	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-4 py-3 w-16 text-center">No.</th>
					<th class="px-6 py-3">Serial Number</th>
					<th class="px-6 py-3">SMT</th>
					<th class="px-6 py-3">BPR</th>
					<th class="px-6 py-3">DMS</th>
					<th class="px-6 py-3 text-center">Status Akhir</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($serials)): ?>
					<tr>
						<td colspan="6" class="px-6 py-10 text-center text-gray-500">
							Tidak ada data serial number yang ditemukan.
						</td>
					</tr>
				<?php else: ?>
					<?php $i = $start_no + 1; ?>
					<?php foreach ($serials as $item): ?>
						<?php
						$is_ok = !empty($item['smt_time']) && !empty($item['bpr_time']) && !empty($item['dms_time']);
						?>
						<tr class="bg-white border-b hover:bg-gray-50">
							<td class="px-4 py-2 text-center text-gray-500"><?php echo $i++; ?></td>
							<td class="px-6 py-2 font-mono font-medium text-gray-800"><?php echo html_escape($item['serial_number']); ?></td>
							<td class="px-6 py-2 text-gray-600"><?php echo $item['smt_time'] ? date('d M Y, H:i:s', strtotime($item['smt_time'])) : '-'; ?></td>
							<td class="px-6 py-2 text-gray-600"><?php echo $item['bpr_time'] ? date('d M Y, H:i:s', strtotime($item['bpr_time'])) : '-'; ?></td>
							<td class="px-6 py-2 text-gray-600"><?php echo $item['dms_time'] ? date('d M Y, H:i:s', strtotime($item['dms_time'])) : '-'; ?></td>
							<td class="px-6 py-2 text-center">
								<span class="px-2 py-0.5 text-xs font-semibold rounded-full <?php echo $is_ok ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
									<?php echo $is_ok ? 'OK' : 'In Progress'; ?>
								</span>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<!-- Paginasi -->
	<div class="mt-6 flex justify-center">
		<?php echo $pagination; ?>
	</div>
</div>
