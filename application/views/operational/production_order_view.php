<div class="bg-white p-6 rounded-lg shadow-md">
	<!-- Header dan Filter -->
	<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
		<div>
			<h2 class="text-xl font-semibold text-gray-800">Pesanan Produksi (Work Order)</h2>
			<p class="text-sm text-gray-500">Monitor progress Work In Progress (WIP) di setiap lini produksi.</p>
		</div>
		<!-- Filter Line Produksi -->
		<div class="flex items-center gap-2 flex-wrap">
			<a href="<?php echo site_url('operational/production_orders?line=all'); ?>"
				class="px-4 py-2 text-sm font-medium rounded-md transition-colors
                      <?php echo ($selected_line == 'all') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
				Semua Line
			</a>
			<?php foreach ($production_lines as $line): ?>
				<a href="<?php echo site_url('operational/production_orders?line=' . urlencode($line)); ?>"
					class="px-4 py-2 text-sm font-medium rounded-md transition-colors
                          <?php echo ($selected_line == $line) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
					<?php echo html_escape($line); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>

	<!-- Tabel Data WIP -->
	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-4 py-3 text-center">No.</th>
					<th class="px-6 py-3">Work Order</th>
					<th class="px-6 py-3">Nama Produk</th>
					<th class="px-6 py-3 text-center">Target Qty</th>
					<th class="px-6 py-3 w-1/4 text-center">Progress SMT</th>
					<th class="px-6 py-3 w-1/4 text-center">Progress BPR</th>
					<th class="px-6 py-3 w-1/4 text-center">Progress DMS</th>
					<th class="px-6 py-3 w-1/4 text-center">Overall Progress</th>
					<th class="px-6 py-3 text-center">Status</th>
					<th class="px-6 py-3 text-center">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($wip_data)) : ?>
					<tr>
						<td colspan="10" class="px-6 py-10 text-center text-gray-500">
							Tidak ada data Pesanan Produksi yang aktif untuk line yang dipilih.
						</td>
					</tr>
				<?php else : ?>
					<!-- PERUBAHAN: Nomor urut dimulai dari $start_no -->
					<?php $i = $start_no + 1; ?>
					<?php foreach ($wip_data as $wip) : ?>
						<tr class="bg-white border-b hover:bg-gray-50">
							<td class="px-4 py-2 text-center text-gray-500"><?php echo $i++; ?></td>
							<td class="px-6 py-2 font-medium text-gray-900">
								<?php echo html_escape($wip['prod_order_no']); ?>
								<p class="text-xs text-gray-500"><?php echo html_escape($wip['production_line']); ?></p>
							</td>
							<td class="px-6 py-2"><?php echo html_escape($wip['item_name']); ?></td>
							<td class="px-6 py-2 text-center font-semibold"><?php echo number_format($wip['quantity_to_produce'], 0); ?></td>

							<?php
							$target = $wip['quantity_to_produce'];
							$progress_smt = ($target > 0) ? ($wip['qty_smt_ok'] / $target) * 100 : 0;
							$progress_bpr = ($target > 0) ? ($wip['qty_bpr_ok'] / $target) * 100 : 0;
							$progress_dms = ($target > 0) ? ($wip['qty_dms_ok'] / $target) * 100 : 0;
							$overall_progress = ($target > 0) ? ($wip['completed_units'] / $target) * 100 : 0;
							$progress_color = $overall_progress >= 100 ? 'bg-green-500' : 'bg-blue-500';
							?>

							<td class="px-6 py-2 text-center">
								<div class="flex items-center justify-center">
									<div class="w-20 bg-gray-200 rounded-full h-2.5 mr-2">
										<div class="bg-yellow-400 h-2.5 rounded-full" style="width: <?php echo $progress_smt; ?>%"></div>
									</div><span class="text-xs font-medium w-8"><?php echo round($progress_smt); ?>%</span>
								</div>
							</td>
							<td class="px-6 py-2 text-center">
								<div class="flex items-center justify-center">
									<div class="w-20 bg-gray-200 rounded-full h-2.5 mr-2">
										<div class="bg-indigo-400 h-2.5 rounded-full" style="width: <?php echo $progress_bpr; ?>%"></div>
									</div><span class="text-xs font-medium w-8"><?php echo round($progress_bpr); ?>%</span>
								</div>
							</td>
							<td class="px-6 py-2 text-center">
								<div class="flex items-center justify-center">
									<div class="w-20 bg-gray-200 rounded-full h-2.5 mr-2">
										<div class="bg-purple-500 h-2.5 rounded-full" style="width: <?php echo $progress_dms; ?>%"></div>
									</div><span class="text-xs font-medium w-8"><?php echo round($progress_dms); ?>%</span>
								</div>
							</td>
							<td class="px-6 py-2 text-center">
								<div class="flex items-center justify-center">
									<div class="w-20 bg-gray-200 rounded-full h-2.5 mr-2">
										<div class="<?php echo $progress_color; ?> h-2.5 rounded-full" style="width: <?php echo $overall_progress; ?>%"></div>
									</div><span class="text-xs font-bold w-8 <?php echo $overall_progress >= 100 ? 'text-green-600' : 'text-blue-600'; ?>"><?php echo round($overall_progress); ?>%</span>
								</div>
							</td>

							<td class="px-6 py-2 text-center">
								<?php
								$status_class = '';
								switch ($wip['status']) {
									case 'Open':
										$status_class = 'bg-gray-100 text-gray-800';
										break;
									case 'In Progress':
									case 'Materials Issued':
									case 'Setup Validated':
										$status_class = 'bg-yellow-100 text-yellow-800';
										break;
									case 'BPR Validated':
									case 'DMS Validated':
										$status_class = 'bg-indigo-100 text-indigo-800';
										break;
									case 'Ready for Production':
										$status_class = 'bg-blue-100 text-blue-800';
										break;
									case 'Awaiting FQA':
										$status_class = 'bg-purple-100 text-purple-800';
										break;
									case 'Completed':
										$status_class = 'bg-green-100 text-green-800';
										break;
									case 'Cancelled':
										$status_class = 'bg-red-100 text-red-800';
										break;
									default:
										$status_class = 'bg-gray-100 text-gray-800';
								}
								?><span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $status_class; ?>"><?php echo html_escape($wip['status']); ?></span>
							</td>
							<td class="px-6 py-2 text-center">
								<a href="<?php echo site_url('operational/production_orders/view_serials/' . urlencode($wip['prod_order_no'])); ?>" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-pink-50 hover:border-pink-300 hover:text-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors" title="Lihat Detail Serial Number">
									<i data-feather="eye" class="w-4 h-4 mr-2"></i>
									Detail
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<!-- BLOK BARU: Paginasi -->
	<div class="mt-6 flex justify-center">
		<?php echo $pagination; ?>
	</div>
</div>
