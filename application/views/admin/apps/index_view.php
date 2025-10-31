<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<div class="flex justify-between items-center mb-4">
		<h2 class="text-xl font-semibold">Daftar Aplikasi</h2>
		<a href="<?php echo site_url('admin/apps/add'); ?>" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tambah Aplikasi</a>
	</div>
	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-6 py-3">Nama Aplikasi</th>
					<th class="px-6 py-3">Kategori</th>
					<th class="px-6 py-3">Versi</th>
					<th class="px-6 py-3">Status</th>
					<th class="px-6 py-3 text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($apps as $app): ?>
					<tr class="bg-white border-b">
						<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($app['app_name']); ?></td>
						<td class="px-6 py-4"><?php echo $app['category']; ?></td>
						<td class="px-6 py-4"><?php echo $app['version']; ?></td>
						<td class="px-6 py-4"><?php echo $app['status']; ?></td>
						<td class="px-6 py-4 text-right">
							<a href="<?php echo site_url('admin/apps/edit/' . $app['app_id']); ?>" class="font-medium text-blue-600 hover:underline mr-3">Edit</a>
							<a href="<?php echo site_url('admin/apps/delete/' . $app['app_id']); ?>" onclick="return confirm('Anda yakin?')" class="font-medium text-red-600 hover:underline">Hapus</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
