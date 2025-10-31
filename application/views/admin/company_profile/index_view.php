<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
		<span class="font-medium">Sukses!</span> <?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<table class="w-full text-sm text-left text-gray-500">
		<thead class="text-xs text-gray-700 uppercase bg-gray-50">
			<tr>
				<th scope="col" class="px-6 py-3">Judul Halaman</th>
				<th scope="col" class="px-6 py-3">Terakhir Diperbarui</th>
				<th scope="col" class="px-6 py-3 text-right">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($profiles as $profile): ?>
				<tr class="bg-white border-b">
					<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
						<?php echo html_escape($profile['title']); ?>
					</th>
					<td class="px-6 py-4">
						<?php echo date('d M Y, H:i', strtotime($profile['last_updated_at'])); ?>
					</td>
					<td class="px-6 py-4 text-right">
						<a href="<?php echo site_url('admin/companyprofile/edit/' . $profile['profile_key']); ?>" class="font-medium text-blue-600 hover:underline">Edit</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
