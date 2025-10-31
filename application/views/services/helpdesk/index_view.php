<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-8">
	<!-- Form Buat Tiket -->
	<div class="lg:col-span-1">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-6">Buat Tiket Bantuan</h2>
			<form action="<?php echo site_url('services/helpdesk'); ?>" method="post">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Kategori</label>
					<select name="category_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
						<?php foreach ($categories as $cat): ?>
							<option value="<?php echo $cat['category_id']; ?>"><?php echo html_escape($cat['category_name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Prioritas</label>
					<select name="priority" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
						<option value="medium">Medium</option>
						<option value="low">Low</option>
						<option value="high">High</option>
					</select>
				</div>
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Subjek</label>
					<input type="text" name="subject" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
				</div>
				<div class="mb-6">
					<label class="block mb-2 text-sm font-medium">Deskripsi Masalah</label>
					<textarea name="description" rows="5" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required></textarea>
				</div>
				<button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Kirim Tiket</button>
			</form>
		</div>
	</div>

	<!-- Riwayat Tiket -->
	<div class="lg:col-span-2">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-4">Riwayat Tiket Saya</h2>
			<div class="overflow-x-auto">
				<table class="w-full text-sm text-left text-gray-500">
					<thead class="text-xs text-gray-700 uppercase bg-gray-50">
						<tr>
							<th class="px-6 py-3">Kode Tiket</th>
							<th class="px-6 py-3">Subjek</th>
							<th class="px-6 py-3">Update Terakhir</th>
							<th class="px-6 py-3">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($my_tickets as $ticket): ?>
							<tr class="bg-white border-b hover:bg-gray-50">
								<td class="px-6 py-4"><a href="<?php echo site_url('services/helpdesk/view/' . $ticket['ticket_id']); ?>" class="font-medium text-blue-600 hover:underline"><?php echo $ticket['ticket_code']; ?></a></td>
								<td class="px-6 py-4"><?php echo html_escape($ticket['subject']); ?></td>
								<td class="px-6 py-4"><?php echo date('d M Y, H:i', strtotime($ticket['updated_at'])); ?></td>
								<td class="px-6 py-4"><?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
