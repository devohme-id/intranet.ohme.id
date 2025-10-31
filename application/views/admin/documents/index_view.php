<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
		<span class="font-medium">Sukses!</span> <?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<div class="flex justify-between items-center mb-4">
		<h2 class="text-xl font-semibold">Daftar Dokumen</h2>
		<a href="<?php echo site_url('admin/documents/add'); ?>" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tambah Dokumen</a>
	</div>
	<table class="w-full text-sm text-left text-gray-500">
		<thead class="text-xs text-gray-700 uppercase bg-gray-50">
			<tr>
				<th class="px-6 py-3">Judul</th>
				<th class="px-6 py-3">Kode</th>
				<th class="px-6 py-3">Kategori</th>
				<th class="px-6 py-3">Versi Aktif</th>
				<th class="px-6 py-3">Tgl Update Terakhir</th>
				<th class="px-6 py-3 text-right">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($documents as $doc): ?>
				<tr class="bg-white border-b">
					<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($doc['title']); ?></td>
					<td class="px-6 py-4"><?php echo html_escape($doc['document_code']); ?></td>
					<td class="px-6 py-4"><?php echo html_escape($doc['category_name']); ?></td>
					<td class="px-6 py-4"><?php echo html_escape($doc['version_number'] ?? 'N/A'); ?></td>
					<td class="px-6 py-4"><?php echo $doc['last_updated_at'] ? date('d M Y', strtotime($doc['last_updated_at'])) : date('d M Y', strtotime($doc['created_at'])); ?></td>
					<td class="px-6 py-4 text-right">
						<a href="<?php echo site_url('admin/documents/edit/' . $doc['document_id']); ?>" class="font-medium text-blue-600 hover:underline mr-3">Kelola</a>
						<a href="<?php echo site_url('admin/documents/delete/' . $doc['document_id']); ?>" onclick="return confirm('Anda yakin ingin menghapus dokumen ini beserta semua versinya?')" class="font-medium text-red-600 hover:underline">Hapus</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
