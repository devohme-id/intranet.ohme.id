<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
		<span class="font-medium">Sukses!</span> <?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<div class="flex justify-between items-center mb-4">
		<h2 class="text-xl font-semibold">Daftar Album Galeri</h2>
		<a href="<?php echo site_url('admin/galleries/add'); ?>" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Buat Album Baru</a>
	</div>
	<table class="w-full text-sm text-left text-gray-500">
		<thead class="text-xs text-gray-700 uppercase bg-gray-50">
			<tr>
				<th class="px-6 py-3">Judul Album</th>
				<th class="px-6 py-3">Tanggal Kegiatan</th>
				<th class="px-6 py-3">Jumlah Foto</th>
				<th class="px-6 py-3 text-right">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($galleries as $gallery): ?>
				<tr class="bg-white border-b">
					<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($gallery['title']); ?></td>
					<td class="px-6 py-4"><?php echo date('d M Y', strtotime($gallery['event_date'])); ?></td>
					<td class="px-6 py-4"><?php echo $gallery['photo_count']; ?></td>
					<td class="px-6 py-4 text-right">
						<a href="<?php echo site_url('admin/galleries/manage/' . $gallery['gallery_id']); ?>" class="font-medium text-blue-600 hover:underline mr-3">Kelola</a>
						<a href="<?php echo site_url('admin/galleries/delete/' . $gallery['gallery_id']); ?>" onclick="return confirm('Anda yakin ingin menghapus album ini beserta semua fotonya?')" class="font-medium text-red-600 hover:underline">Hapus</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
