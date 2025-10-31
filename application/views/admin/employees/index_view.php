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
			<h2 class="text-xl font-semibold">Daftar Karyawan</h2>
			<p class="text-sm text-gray-500">Kelola data karyawan dan struktur atasan.</p>
		</div>
		<div class="flex items-center gap-2">
			<form action="<?php echo site_url('admin/employees'); ?>" method="get" class="flex-grow">
				<div class="relative">
					<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
						<i data-feather="search" class="w-5 h-5 text-gray-400"></i>
					</div>
					<input type="search" name="search" class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari Nama, NIK..." value="<?php echo html_escape($search_term); ?>">
				</div>
			</form>
			<?php if ($can_add): // **PERBAIKAN** 
			?>
				<a href="<?php echo site_url('admin/employees/add'); ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
					<i data-feather="plus" class="w-4 h-4 mr-2"></i>
					<span>Tambah Karyawan</span>
				</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-6 py-3">Nama Lengkap</th>
					<th class="px-6 py-3">NIK</th>
					<th class="px-6 py-3">Jabatan</th>
					<th class="px-6 py-3">Departemen</th>
					<th class="px-6 py-3">Atasan Langsung</th>
					<th class="px-6 py-3 text-center">Status</th>
					<th class="px-6 py-3 text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($employees)): ?>
					<tr>
						<td colspan="7" class="px-6 py-4 text-center text-gray-500">
							<?php if (!empty($search_term)): ?>
								Tidak ada karyawan yang cocok dengan pencarian "<?php echo html_escape($search_term); ?>".
							<?php else: ?>
								Tidak ada data karyawan yang tersedia.
							<?php endif; ?>
						</td>
					</tr>
				<?php else: ?>
					<?php foreach ($employees as $emp): ?>
						<tr class="bg-white border-b hover:bg-gray-50">
							<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($emp['full_name']); ?></td>
							<td class="px-6 py-4"><?php echo html_escape($emp['nik']); ?></td>
							<td class="px-6 py-4"><?php echo html_escape($emp['position']); ?></td>
							<td class="px-6 py-4"><?php echo html_escape($emp['department']); ?></td>
							<td class="px-6 py-4"><?php echo html_escape($emp['manager_name'] ?? '-'); ?></td>
							<td class="px-6 py-4 text-center">
								<?php if ($emp['is_active']): ?>
									<span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Aktif</span>
								<?php else: ?>
									<span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Non-Aktif</span>
								<?php endif; ?>
							</td>
							<td class="px-6 py-4 text-right whitespace-nowrap">
								<?php if ($can_edit): // **PERBAIKAN** 
								?>
									<a href="<?php echo site_url('admin/employees/edit/' . $emp['employee_id']); ?>" class="font-medium text-blue-600 hover:underline">Edit</a>
								<?php endif; ?>
								<?php if ($can_delete): // **PERBAIKAN** 
								?>
									<button onclick="openDeleteModal('<?php echo site_url('admin/employees/delete/' . $emp['employee_id']); ?>', '<?php echo html_escape($emp['full_name']); ?>')" class="font-medium text-red-600 hover:underline ml-3">Hapus</button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<div class="mt-6 flex justify-center">
		<?php echo $pagination; ?>
	</div>
</div>
