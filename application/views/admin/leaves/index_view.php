<div class="space-y-8">
	<!-- Bagian Persetujuan Atasan (Manager) -->
	<div class="bg-white p-6 rounded-lg shadow-md">
		<h2 class="text-xl font-semibold mb-4">Menunggu Persetujuan Anda (Sebagai Atasan)</h2>
		<div class="overflow-x-auto">
			<table class="w-full text-sm text-left text-gray-500">
				<thead class="text-xs text-gray-700 uppercase bg-gray-50">
					<tr>
						<th class="px-6 py-3">Nama Karyawan</th>
						<th class="px-6 py-3">Jenis Cuti</th>
						<th class="px-6 py-3">Tanggal</th>
						<th class="px-6 py-3 text-right">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($requests_for_manager)): ?>
						<tr>
							<td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada pengajuan dari bawahan Anda yang memerlukan persetujuan.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($requests_for_manager as $req): ?>
							<tr class="bg-white border-b">
								<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($req['employee_name']); ?></td>
								<td class="px-6 py-4"><?php echo html_escape($req['type_name']); ?></td>
								<td class="px-6 py-4"><?php echo date('d M Y', strtotime($req['start_date'])) . ' - ' . date('d M Y', strtotime($req['end_date'])); ?></td>
								<td class="px-6 py-4 text-right">
									<a href="<?php echo site_url('admin/leaves/view/' . $req['request_id']); ?>" class="font-medium text-blue-600 hover:underline">Proses</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- Bagian Persetujuan HR -->
	<div class="bg-white p-6 rounded-lg shadow-md">
		<h2 class="text-xl font-semibold mb-4">Menunggu Persetujuan HR</h2>
		<div class="overflow-x-auto">
			<table class="w-full text-sm text-left text-gray-500">
				<thead class="text-xs text-gray-700 uppercase bg-gray-50">
					<tr>
						<th class="px-6 py-3">Nama Karyawan</th>
						<th class="px-6 py-3">Jenis Cuti</th>
						<th class="px-6 py-3">Tanggal</th>
						<th class="px-6 py-3 text-right">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($requests_for_hr)): ?>
						<tr>
							<td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada pengajuan yang memerlukan persetujuan HR.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($requests_for_hr as $req): ?>
							<tr class="bg-white border-b">
								<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($req['employee_name']); ?></td>
								<td class="px-6 py-4"><?php echo html_escape($req['type_name']); ?></td>
								<td class="px-6 py-4"><?php echo date('d M Y', strtotime($req['start_date'])) . ' - ' . date('d M Y', strtotime($req['end_date'])); ?></td>
								<td class="px-6 py-4 text-right">
									<a href="<?php echo site_url('admin/leaves/view/' . $req['request_id']); ?>" class="font-medium text-blue-600 hover:underline">Proses</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
