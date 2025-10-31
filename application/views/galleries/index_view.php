<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
	<?php if (!empty($galleries)): ?>
		<?php foreach ($galleries as $gallery): ?>
			<a href="<?php echo site_url('galleries/view/' . $gallery['gallery_id']); ?>" class="group block bg-white rounded-lg shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
				<div class="relative">
					<img class="w-full h-56 object-cover" src="<?php echo $gallery['cover_image'] ? base_url($gallery['cover_image']) : 'https://placehold.co/600x400/e2e8f0/4a5568?text=Galeri'; ?>" alt="Cover Album <?php echo html_escape($gallery['title']); ?>">
					<div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-40 transition-colors duration-300 flex items-center justify-center">
						<div class="text-center text-white p-4">
							<h3 class="text-2xl font-bold"><?php echo html_escape($gallery['title']); ?></h3>
							<p class="text-sm mt-1"><?php echo $gallery['photo_count']; ?> Foto</p>
						</div>
					</div>
				</div>
			</a>
		<?php endforeach; ?>
	<?php else: ?>
		<p class="col-span-full text-center text-gray-500 py-16">Belum ada album galeri yang dipublikasikan.</p>
	<?php endif; ?>
</div>
