<!-- Header Proyek -->
<div class="bg-white p-6 rounded-lg shadow-md mb-8">
	<h1 class="text-3xl font-bold text-gray-800"><?php echo html_escape($project->project_name); ?></h1>
	<p class="mt-2 text-gray-600"><?php echo html_escape($project->description); ?></p>
</div>

<!-- Navigasi Tab yang Diperbaiki -->
<?php $this->load->view('projects/partials/tabs_nav', ['project' => $project, 'active_tab' => $active_tab]); ?>

<!-- Konten Halaman Kelola Anggota -->
<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-8">
	<!-- Form Tambah Anggota -->
	<div class="lg:col-span-1">
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-6">Tambah Anggota Tim</h2>
			<form action="<?php echo site_url('projects/members/' . $project->project_id); ?>" method="post">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<div class="mb-4">
					<label class="block mb-2 text-sm font-medium">Pilih Karyawan</label>
					<select name="user_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
						<option value="">-- Pilih Karyawan --</option>
						<?php foreach ($non_members as $user): ?>
							<option value="<?php echo $user['user_id']; ?>"><?php echo html_escape($user['full_name']) . ' (' . $user['nik'] . ')'; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<button type="submit" name="add_member" value="1" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Tambahkan ke Proyek</button>
			</form>
		</div>
	</div>
	<!-- Daftar Anggota Saat Ini -->
	<div class="lg:col-span-2">
		<div class="bg-white p-6 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold mb-4">Daftar Anggota Tim</h2>
			<ul class="divide-y divide-gray-200">
				<?php foreach ($members as $member): ?>
					<li class="py-3 flex items-center justify-between">
						<div class="flex items-center">
							<img class="w-10 h-10 rounded-full mr-3" src="https://placehold.co/40x40/818cf8/ffffff?text=<?php echo substr($member['full_name'], 0, 1); ?>" alt="avatar">
							<div>
								<p class="font-medium text-gray-800"><?php echo html_escape($member['full_name']); ?></p>
								<p class="text-xs text-gray-500"><?php echo html_escape($member['position'] ?? 'N/A'); ?></p>
							</div>
						</div>
						<?php if ($project->owner_user_id == $user_info['user_id'] && $member['user_id'] != $project->owner_user_id): ?>
							<a href="<?php echo site_url('projects/remove_member/' . $project->project_id . '/' . $member['user_id']); ?>" onclick="return confirm('Anda yakin ingin menghapus anggota ini?')" class="text-red-500 hover:text-red-700">
								<i data-feather="trash-2" class="w-5 h-5"></i>
							</a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
