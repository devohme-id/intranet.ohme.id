<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
		<span class="font-medium">Sukses!</span> <?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<!-- Header Halaman dan Tombol Aksi -->
	<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
		<div>
			<h2 class="text-xl font-semibold">Kelola Artikel Wiki</h2>
			<p class="text-sm text-gray-500">Buat, edit, dan kelola semua artikel di Pusat Pengetahuan.</p>
		</div>
		<div class="flex items-center gap-2">
			<form action="<?php echo site_url('admin/wiki'); ?>" method="get" class="flex-grow">
				<div class="relative">
					<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
						<i data-feather="search" class="w-5 h-5 text-gray-400"></i>
					</div>
					<input type="search" name="search" class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari judul atau kategori..." value="<?php echo html_escape($search_term); ?>">
				</div>
			</form>
			<a href="<?php echo site_url('admin/wiki/add'); ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
				<i data-feather="plus" class="w-4 h-4 mr-2"></i>
				<span>Artikel Baru</span>
			</a>
		</div>
	</div>

	<!-- Tabel Daftar Artikel -->
	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-4 py-3 text-center w-16">No.</th>
					<th class="px-6 py-3">Judul Artikel</th>
					<th class="px-6 py-3">Kategori</th>
					<th class="px-6 py-3">Update Terakhir</th>
					<th class="px-6 py-3 text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($articles)): ?>
					<tr>
						<td colspan="5" class="px-6 py-4 text-center text-gray-500">
							Tidak ada artikel yang ditemukan.
						</td>
					</tr>
				<?php else: ?>
					<?php $i = $start_no + 1; ?>
					<?php foreach ($articles as $article): ?>
						<tr class="bg-white border-b hover:bg-gray-50">
							<td class="px-4 py-4 text-center text-gray-500 font-medium"><?php echo $i++; ?></td>
							<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($article['title']); ?></td>
							<td class="px-6 py-4"><?php echo html_escape($article['category_name']); ?></td>
							<td class="px-6 py-4"><?php echo date('d M Y, H:i', strtotime($article['last_updated_at'])); ?> oleh <?php echo html_escape($article['last_author']); ?></td>
							<td class="px-6 py-4 text-right whitespace-nowrap">
								<a href="<?php echo site_url('admin/wiki/edit/' . $article['article_id']); ?>" class="font-medium text-blue-600 hover:underline">Edit</a>
								<!-- Tombol Hapus bisa ditambahkan di sini -->
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<!-- Paginasi -->
	<div class="mt-6 flex justify-center">
		<?php echo $pagination; ?>
	</div>
</div>
