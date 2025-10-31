<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
		<span class="font-medium">Sukses!</span> <?php echo $this->session->flashdata('success'); ?>
	</div>
<?php elseif ($this->session->flashdata('error')): ?>
	<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
		<span class="font-medium">Gagal!</span> <?php echo $this->session->flashdata('error'); ?>
	</div>
<?php elseif ($this->session->flashdata('info')): ?>
	<div class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg" role="alert">
		<?php echo $this->session->flashdata('info'); ?>
	</div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
		<div>
			<h2 class="text-xl font-semibold">Daftar Pengguna</h2>
			<p class="text-sm text-gray-500">Kelola akun dan hak akses pengguna portal.</p>
		</div>
		<div class="flex items-center gap-2">
			<form action="<?php echo site_url('admin/users'); ?>" method="get" class="flex-grow">
				<div class="relative">
					<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
						<i data-feather="search" class="w-5 h-5 text-gray-400"></i>
					</div>
					<input type="search" name="search" class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari Nama, Username, NIK..." value="<?php echo html_escape($search_term); ?>">
				</div>
			</form>
			<?php if ($can_add): ?>
				<a href="<?php echo site_url('admin/users/add'); ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
					<i data-feather="plus" class="w-4 h-4 mr-2"></i>
					<span>Tambah Pengguna</span>
				</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-6 py-3">Nama Lengkap</th>
					<th class="px-6 py-3">Username</th>
					<th class="px-6 py-3">NIK</th>
					<th class="px-6 py-3">Peran</th>
					<th class="px-6 py-3 text-center">Status</th>
					<th class="px-6 py-3 text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($users)): ?>
					<tr>
						<td colspan="6" class="px-6 py-4 text-center text-gray-500">
							Tidak ada data pengguna yang tersedia.
						</td>
					</tr>
				<?php else: ?>
					<?php foreach ($users as $user): ?>
						<tr class="bg-white border-b hover:bg-gray-50">
							<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($user['full_name']); ?></td>
							<td class="px-6 py-4"><?php echo html_escape($user['username']); ?></td>
							<td class="px-6 py-4"><?php echo html_escape($user['nik'] ?? '-'); ?></td>
							<td class="px-6 py-4"><?php echo html_escape($user['role_name']); ?></td>
							<td class="px-6 py-4 text-center">
								<span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $user['is_active'] ? 'text-green-800 bg-green-100' : 'text-red-800 bg-red-100'; ?>">
									<?php echo $user['is_active'] ? 'Aktif' : 'Non-Aktif'; ?>
								</span>
							</td>
							<td class="px-6 py-4 text-right whitespace-nowrap">
								<?php if ($can_edit): ?>
									<a href="<?php echo site_url('admin/users/edit/' . $user['user_id']); ?>" class="font-medium text-blue-600 hover:underline">Edit</a>
								<?php endif; ?>
								<?php if ($can_delete && $user['user_id'] != $this->session->userdata('user_id')): ?>
									<button onclick="openDeleteModal('<?php echo site_url('admin/users/delete/' . $user['user_id']); ?>', '<?php echo html_escape($user['full_name']); ?>')" class="font-medium text-red-600 hover:underline ml-3">Hapus</button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<div class="mt-6 flex justify-center"><?php echo $pagination; ?></div>
</div>
