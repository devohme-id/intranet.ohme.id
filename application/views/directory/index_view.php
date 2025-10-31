<!-- Form Pencarian -->
<div class="mb-6">
	<form action="<?php echo site_url('EmployeeDirectory'); ?>" method="get">
		<div class="relative">
			<input type="search" name="search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari berdasarkan Nama, NIK, Departemen, atau Jabatan..." value="<?php echo html_escape($this->input->get('search')); ?>">
			<button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Cari</button>
		</div>
	</form>
</div>

<!-- Grid Kartu Karyawan -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
	<?php if (!empty($employees)): ?>
		<?php foreach ($employees as $employee): ?>
			<div class="text-center bg-white rounded-lg shadow-md p-6 transform hover:-translate-y-1 transition-transform duration-300">
				<?php
				// **PERUBAHAN**: Logika untuk menampilkan foto profil atau placeholder
				$photo_path = FCPATH . $employee['profile_picture_url'];
				if (!empty($employee['profile_picture_url']) && file_exists($photo_path)) {
					$photo_url = base_url($employee['profile_picture_url']) . '?t=' . filemtime($photo_path);
				} else {
					$photo_url = 'https://placehold.co/96x96/7c3aed/ffffff?text=' . substr($employee['full_name'], 0, 1);
				}
				?>
				<img class="w-24 h-24 mx-auto rounded-full object-cover mb-4" src="<?php echo $photo_url; ?>" alt="Foto profil <?php echo html_escape($employee['full_name']); ?>">
				<h3 class="text-lg font-semibold text-gray-800"><?php echo html_escape($employee['full_name']); ?></h3>
				<p class="text-sm text-gray-500"><?php echo html_escape($employee['nik']); ?></p>
				<div class="mt-2 text-sm text-blue-600 font-medium"><?php echo html_escape($employee['position']); ?></div>
				<p class="text-xs text-gray-500"><?php echo html_escape($employee['department']); ?></p>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="col-span-full text-center py-12">
			<i data-feather="alert-circle" class="w-16 h-16 mx-auto text-gray-400"></i>
			<p class="mt-4 text-gray-500">
				<?php if ($this->input->get('search')): ?>
					Karyawan dengan kata kunci "<?php echo html_escape($this->input->get('search')); ?>" tidak ditemukan.
				<?php else: ?>
					Tidak ada data karyawan yang bisa ditampilkan.
				<?php endif; ?>
			</p>
		</div>
	<?php endif; ?>
</div>

<!-- Paginasi -->
<div class="mt-8 flex justify-center">
	<?php echo $pagination; ?>
</div>
