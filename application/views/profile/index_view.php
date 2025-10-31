<?php if (validation_errors()): ?>
	<div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
		<span class="font-medium">Terjadi kesalahan validasi:</span>
		<?php echo validation_errors('<div class="mt-1">', '</div>'); ?>
	</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
		<span class="font-medium">Sukses!</span> <?php echo $this->session->flashdata('success'); ?>
	</div>
<?php elseif ($this->session->flashdata('error')): ?>
	<div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
		<span class="font-medium">Gagal!</span> <?php echo $this->session->flashdata('error'); ?>
	</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
	<!-- Kolom Kiri: Informasi Profil & Ganti Foto -->
	<div class="md:col-span-1">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<div class="flex flex-col items-center">
				<?php
				// **PERBAIKAN TUNTAS**: Memeriksa keberadaan properti SEBELUM digunakan
				$photo_url = 'https://placehold.co/96x96/7c3aed/ffffff?text=' . substr($user->full_name, 0, 1);
				if (!empty($user->profile_picture_url)) {
					$profile_photo_path = FCPATH . $user->profile_picture_url;
					if (file_exists($profile_photo_path)) {
						$photo_url = base_url($user->profile_picture_url) . '?t=' . filemtime($profile_photo_path);
					}
				}
				?>
				<img class="object-cover w-24 h-24 rounded-full mb-4" src="<?php echo $photo_url; ?>" alt="User avatar">
				<h2 class="text-xl font-bold text-gray-800"><?php echo html_escape($user->full_name); ?></h2>
				<p class="text-sm text-gray-500"><?php echo html_escape($employee->position ?? 'N/A'); ?></p>
			</div>

			<!-- Form Ganti Foto -->
			<div class="mt-6 border-t border-gray-200 pt-4">
				<form action="<?php echo site_url('profile/upload_photo'); ?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
					<label class="block mb-2 text-sm font-medium text-gray-700">Ganti Foto Profil</label>
					<input type="file" name="profile_picture" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
					<p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF (Maks. 2MB)</p>
					<button type="submit" class="mt-4 w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Unggah Foto</button>
				</form>
			</div>
		</div>
	</div>

	<!-- Kolom Kanan: Detail & Ubah Kata Sandi -->
	<div class="md:col-span-2 space-y-6">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Detail Informasi</h3>
			<dl>
				<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
					<dt class="text-sm font-medium text-gray-500">NIK</dt>
					<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo html_escape($employee->nik ?? 'N/A'); ?></dd>
				</div>
				<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
					<dt class="text-sm font-medium text-gray-500">Departemen</dt>
					<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo html_escape($employee->department ?? 'N/A'); ?></dd>
				</div>
				<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
					<dt class="text-sm font-medium text-gray-500">Username</dt>
					<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo html_escape($user->username); ?></dd>
				</div>
			</dl>
		</div>
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Ubah Kata Sandi</h3>
			<form action="<?php echo site_url('profile'); ?>" method="post">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<div class="mb-4">
					<label for="current_password" class="block mb-2 text-sm font-medium">Kata Sandi Saat Ini</label>
					<input type="password" name="current_password" id="current_password" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
				</div>
				<div class="mb-4">
					<label for="new_password" class="block mb-2 text-sm font-medium">Kata Sandi Baru</label>
					<input type="password" name="new_password" id="new_password" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
				</div>
				<div class="mb-6">
					<label for="confirm_password" class="block mb-2 text-sm font-medium">Konfirmasi Kata Sandi Baru</label>
					<input type="password" name="confirm_password" id="confirm_password" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
				</div>
				<button type="submit" name="submit_password" value="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Perbarui Kata Sandi</button>
			</form>
		</div>
	</div>
</div>
