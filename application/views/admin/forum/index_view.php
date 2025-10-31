<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
		<span class="font-medium">Sukses!</span> <?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<div class="flex justify-between items-center mb-4">
		<h2 class="text-xl font-semibold">Daftar Kategori Forum</h2>
		<a href="<?php echo site_url('admin/forum/add'); ?>" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tambah Kategori</a>
	</div>
	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-6 py-3">Nama Kategori</th>
					<th class="px-6 py-3">Deskripsi</th>
					<th class="px-6 py-3">Jumlah Topik</th>
					<th class="px-6 py-3">Status</th>
					<th class="px-6 py-3 text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($categories as $cat): ?>
					<tr class="bg-white border-b">
						<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($cat['category_name']); ?></td>
						<td class="px-6 py-4"><?php echo html_escape($cat['description']); ?></td>
						<td class="px-6 py-4"><?php echo $cat['thread_count']; ?></td>
						<td class="px-6 py-4">
							<span class="px-2 py-1 font-semibold leading-tight rounded-full <?php echo $cat['is_active'] ? 'text-green-700 bg-green-100' : 'text-gray-700 bg-gray-100'; ?>">
								<?php echo $cat['is_active'] ? 'Aktif' : 'Non-Aktif'; ?>
							</span>
						</td>
						<td class="px-6 py-4 text-right">
							<a href="<?php echo site_url('admin/forum/edit/' . $cat['category_id']); ?>" class="font-medium text-blue-600 hover:underline mr-3">Edit</a>
							<a href="<?php echo site_url('admin/forum/delete/' . $cat['category_id']); ?>" onclick="return confirm('Anda yakin ingin menghapus kategori ini?')" class="font-medium text-red-600 hover:underline">Hapus</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
