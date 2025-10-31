<!-- Load fslightbox.js untuk fitur lightbox -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/index.min.js" xintegrity="sha512-jrAlY+Sz+pGHq22/t8o4dGr8S/J5/ULhBllEU5KdSMvyDLte5TnQll13i0+K5D9oNolJSGaDkS//3iPq9+fVLA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="bg-white rounded-lg shadow-md p-8 mb-8">
	<p class="text-sm text-gray-500 mb-1">Album Kegiatan: <?php echo date('d F Y', strtotime($gallery['event_date'])); ?></p>
	<p class="text-gray-600"><?php echo html_escape($gallery['description']); ?></p>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
	<?php if (!empty($gallery['photos'])): ?>
		<?php foreach ($gallery['photos'] as $photo): ?>
			<a data-fslightbox="gallery" href="<?php echo base_url($photo['file_path']); ?>">
				<img class="w-full h-48 object-cover rounded-lg shadow-sm transform hover:scale-105 transition-transform duration-300" src="<?php echo base_url($photo['file_path']); ?>" alt="Foto galeri">
			</a>
		<?php endforeach; ?>
	<?php else: ?>
		<p class="col-span-full text-center text-gray-500 py-16">Album ini belum memiliki foto.</p>
	<?php endif; ?>
</div>

<div class="mt-8">
	<a href="<?php echo site_url('galleries'); ?>" class="text-blue-600 hover:underline">&larr; Kembali ke Daftar Album</a>
</div>
