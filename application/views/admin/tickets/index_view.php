<div class="bg-white p-6 rounded-lg shadow-md">
	<table class="w-full text-sm text-left text-gray-500">
		<thead class="text-xs text-gray-700 uppercase bg-gray-50">
			<tr>
				<th class="px-6 py-3">Kode</th>
				<th class="px-6 py-3">Subjek</th>
				<th class="px-6 py-3">Pelapor</th>
				<th class="px-6 py-3">Kategori</th>
				<th class="px-6 py-3">Update Terakhir</th>
				<th class="px-6 py-3">Status</th>
				<th class="px-6 py-3 text-right">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($tickets as $ticket): ?>
				<tr class="bg-white border-b">
					<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($ticket['ticket_code']); ?></td>
					<td class="px-6 py-4"><?php echo html_escape($ticket['subject']); ?></td>
					<td class="px-6 py-4"><?php echo html_escape($ticket['user_name']); ?></td>
					<td class="px-6 py-4"><?php echo html_escape($ticket['category_name']); ?></td>
					<td class="px-6 py-4"><?php echo date('d M Y, H:i', strtotime($ticket['updated_at'])); ?></td>
					<td class="px-6 py-4"><?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?></td>
					<td class="px-6 py-4 text-right">
						<a href="<?php echo site_url('admin/tickets/view/' . $ticket['ticket_id']); ?>" class="font-medium text-blue-600 hover:underline">Lihat & Balas</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
