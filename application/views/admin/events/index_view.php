<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
		<span class="font-medium">Sukses!</span> <?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<div class="flex justify-between items-center mb-4">
		<h2 class="text-xl font-semibold">Daftar Kegiatan</h2>
		<a href="<?php echo site_url('admin/events/add'); ?>" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tambah Kegiatan</a>
	</div>
	<table class="w-full text-sm text-left text-gray-500">
		<thead class="text-xs text-gray-700 uppercase bg-gray-50">
			<tr>
				<th class="px-6 py-3">Nama Kegiatan</th>
				<th class="px-6 py-3">Waktu Mulai</th>
				<th class="px-6 py-3">Lokasi</th>
				<th class="px-6 py-3 text-right">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($events as $event): ?>
				<tr class="bg-white border-b">
					<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($event['title']); ?></td>
					<td class="px-6 py-4"><?php echo date('d M Y, H:i', strtotime($event['start_datetime'])); ?></td>
					<td class="px-6 py-4"><?php echo html_escape($event['location']); ?></td>
					<td class="px-6 py-4 text-right">
						<a href="<?php echo site_url('admin/events/edit/' . $event['event_id']); ?>" class="font-medium text-blue-600 hover:underline mr-3">Edit</a>
						<a href="<?php echo site_url('admin/events/delete/' . $event['event_id']); ?>" onclick="return confirm('Anda yakin ingin menghapus kegiatan ini?')" class="font-medium text-red-600 hover:underline">Hapus</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
