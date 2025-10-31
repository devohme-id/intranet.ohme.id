<!-- Form Pencarian -->
<div class="mb-8">
	<form action="<?php echo site_url('news'); ?>" method="get">
		<label for="search-news" class="sr-only">Cari Berita</label>
		<div class="relative">
			<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
				<i data-feather="search" class="w-5 h-5 text-gray-400"></i>
			</div>
			<input type="search" id="search-news" name="search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari berdasarkan judul atau isi berita..." value="<?php echo html_escape($search_term); ?>">
			<button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Cari</button>
		</div>
	</form>
</div>

<!-- Informasi Hasil Pencarian -->
<?php if ($search_term): ?>
	<div class="mb-6 p-4 text-sm text-blue-700 bg-blue-100 rounded-lg" role="alert">
		Menampilkan <strong><?php echo $total_rows; ?></strong> hasil untuk pencarian: <span class="font-semibold">"<?php echo html_escape($search_term); ?>"</span>. <a href="<?php echo site_url('news'); ?>" class="font-bold underline">Lihat semua berita</a>.
	</div>
<?php endif; ?>

<!-- Grid Berita -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
	<?php if (!empty($articles)): ?>
		<?php foreach ($articles as $article): ?>
			<div class="bg-white rounded-lg shadow-md overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 flex flex-col">
				<a href="<?php echo site_url('news/read/' . $article['slug']); ?>">
					<img class="w-full h-48 object-cover" src="<?php echo $article['cover_image_url'] ? html_escape($article['cover_image_url']) : 'https://placehold.co/600x400/e2e8f0/4a5568?text=Berita'; ?>" alt="Cover Berita">
				</a>
				<div class="p-6 flex flex-col flex-grow">
					<p class="text-sm text-gray-500 mb-2"><?php echo date('d F Y', strtotime($article['published_at'])); ?></p>
					<h3 class="text-lg font-semibold text-gray-800 mb-2 flex-grow">
						<a href="<?php echo site_url('news/read/' . $article['slug']); ?>" class="hover:text-blue-600">
							<?php echo html_escape($article['title']); ?>
						</a>
					</h3>
					<p class="text-gray-600 text-sm line-clamp-3">
						<?php echo strip_tags($article['content']); ?>
					</p>
					<a href="<?php echo site_url('news/read/' . $article['slug']); ?>" class="inline-block mt-4 text-blue-600 font-semibold hover:underline">
						Baca Selengkapnya &rarr;
					</a>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<!-- Pesan Tidak Ditemukan -->
		<div class="col-span-full text-center py-16">
			<i data-feather="alert-circle" class="w-16 h-16 mx-auto text-gray-400"></i>
			<h3 class="mt-4 text-lg font-semibold text-gray-700">Tidak Ada Hasil</h3>
			<p class="mt-1 text-gray-500">
				<?php if ($search_term): ?>
					Kami tidak dapat menemukan berita dengan kata kunci "<?php echo html_escape($search_term); ?>".
				<?php else: ?>
					Saat ini belum ada berita yang dipublikasikan.
				<?php endif; ?>
			</p>
		</div>
	<?php endif; ?>
</div>

<!-- Paginasi -->
<div class="flex justify-center mt-8">
	<?php echo $pagination; ?>
</div>

<style>
	.line-clamp-3 {
		display: -webkit-box;
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}
</style>
