<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
<?php elseif ($this->session->flashdata('error')): ?>
	<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<div class="flex justify-between items-center mb-4">
		<h2 class="text-xl font-semibold">Daftar Peran Pengguna</h2>
		<a href="<?php echo site_url('admin/roles/add'); ?>" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tambah Peran</a>
	</div>
	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-6 py-3">Nama Peran</th>
					<th class="px-6 py-3">Deskripsi</th>
					<th class="px-6 py-3">Jumlah Pengguna</th>
					<th class="px-6 py-3">Status</th>
					<th class="px-6 py-3 text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($roles as $role): ?>
					<tr class="bg-white border-b">
						<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($role['role_name']); ?></td>
						<td class="px-6 py-4"><?php echo html_escape($role['description']); ?></td>
						<td class="px-6 py-4"><?php echo $role['user_count']; ?></td>
						<td class="px-6 py-4">
							<span class="px-2 py-1 font-semibold leading-tight rounded-full <?php echo $role['is_active'] ? 'text-green-700 bg-green-100' : 'text-gray-700 bg-gray-100'; ?>">
								<?php echo $role['is_active'] ? 'Aktif' : 'Non-Aktif'; ?>
							</span>
						</td>
						<td class="px-6 py-4 text-right">
							<a href="<?php echo site_url('admin/roles/edit/' . $role['role_id']); ?>" class="font-medium text-blue-600 hover:underline mr-3">Edit Hak Akses</a>
							<?php if ($role['role_id'] != 1): // Administrator tidak bisa dihapus 
							?>
								<a href="<?php echo site_url('admin/roles/delete/' . $role['role_id']); ?>" onclick="return confirm('Anda yakin ingin menghapus peran ini?')" class="font-medium text-red-600 hover:underline">Hapus</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
