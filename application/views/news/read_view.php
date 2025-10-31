<div class="max-w-4xl mx-auto">
	<div class="bg-white rounded-lg shadow-md overflow-hidden">
		<img class="w-full h-80 object-cover" src="<?php echo $article->cover_image_url ? html_escape($article->cover_image_url) : 'https://placehold.co/800x400/e2e8f0/4a5568?text=Berita'; ?>" alt="Cover Berita">
		<div class="p-8">
			<div class="mb-4 text-sm text-gray-500">
				<span>Dipublikasikan pada <?php echo date('d F Y', strtotime($article->published_at)); ?></span>
				<span class="mx-2">&bull;</span>
				<span>Oleh: <?php echo html_escape($article->full_name ?? 'Administrator'); ?></span>
			</div>
			<h1 class="text-4xl font-bold text-gray-800 mb-6"><?php echo html_escape($article->title); ?></h1>

			<div class="prose max-w-none">
				<?php echo $article->content; ?>
			</div>

			<div class="mt-8 border-t pt-4">
				<a href="<?php echo site_url('news'); ?>" class="text-blue-600 hover:underline">&larr; Kembali ke Daftar Berita</a>
			</div>
		</div>
	</div>
</div>

<style>
	/* Styling untuk konten yang diambil dari database */
	.prose h2 {
		font-size: 1.5rem;
		font-weight: 600;
		margin-top: 1.5em;
		margin-bottom: 0.5em;
	}

	.prose p,
	.prose li {
		line-height: 1.75;
		font-size: 1.125rem;
	}

	.prose img {
		max-width: 100%;
		height: auto;
		border-radius: 0.5rem;
		margin-top: 1em;
	}
</style>
