<div class="bg-white p-6 rounded-lg shadow-md">
	<!-- Header Halaman dan Formulir Pencarian -->
	<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
		<div>
			<h2 class="text-xl font-semibold text-gray-800">Pusat Pengetahuan</h2>
			<p class="text-sm text-gray-500">Temukan informasi, panduan, dan pengetahuan yang Anda butuhkan.</p>
		</div>
		<div class="w-full md:w-1/3">
			<form action="<?php echo site_url('wiki'); ?>" method="get">
				<div class="relative">
					<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
						<i data-feather="search" class="w-5 h-5 text-gray-400"></i>
					</div>
					<input type="search" name="search" class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari judul atau kategori..." value="<?php echo isset($search_term) ? html_escape($search_term) : ''; ?>">
				</div>
			</form>
		</div>
	</div>

	<div class="overflow-x-auto">
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th class="px-4 py-3 text-center w-16">No.</th>
					<th class="px-6 py-3">Judul Artikel</th>
					<th class="px-6 py-3">Kategori</th>
					<th class="px-6 py-3">Update Terakhir</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($articles)): ?>
					<tr>
						<td colspan="4" class="px-6 py-4 text-center text-gray-500">
							<?php if (!empty($search_term)): ?>
								Tidak ada artikel yang cocok dengan pencarian "<?php echo html_escape($search_term); ?>".
							<?php else: ?>
								Belum ada artikel yang dipublikasikan.
							<?php endif; ?>
						</td>
					</tr>
				<?php else: ?>
					<?php // **PERUBAHAN**: Menggunakan nomor awal dari controller 
					?>
					<?php $i = $start_no + 1; ?>
					<?php foreach ($articles as $article): ?>
						<tr class="bg-white border-b hover:bg-gray-50">
							<td class="px-4 py-4 text-center text-gray-500 font-medium"><?php echo $i++; ?></td>
							<td class="px-6 py-4 font-medium text-gray-900">
								<a href="<?php echo site_url('wiki/article/' . $article['article_id']); ?>" class="hover:text-blue-600">
									<?php echo html_escape($article['title']); ?>
								</a>
							</td>
							<td class="px-6 py-4"><?php echo html_escape($article['category_name']); ?></td>
							<td class="px-6 py-4"><?php echo date('d M Y, H:i', strtotime($article['last_updated_at'])); ?> oleh <?php echo html_escape($article['last_author']); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<!-- **PERUBAHAN**: Menampilkan Paginasi -->
	<div class="mt-6 flex justify-center">
		<?php echo $pagination; ?>
	</div>
</div>
