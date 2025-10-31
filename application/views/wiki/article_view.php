<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
	<!-- Kolom Konten Utama -->
	<div class="lg:col-span-3">
		<div class="bg-white p-8 rounded-lg shadow-md">
			<h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-6 border-b pb-4"><?php echo html_escape($article->title); ?></h1>
			<div class="prose max-w-none">
				<?php echo $article->content; // Konten dari database (HTML) 
				?>
			</div>
		</div>
	</div>

	<!-- Kolom Samping untuk Histori -->
	<div class="lg:col-span-1">
		<div class="bg-white p-6 rounded-lg shadow-md sticky top-8">
			<h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4 flex items-center">
				<i data-feather="clock" class="w-5 h-5 mr-2 text-gray-500"></i>
				Histori Perubahan
			</h3>
			<div class="space-y-4 max-h-96 overflow-y-auto pr-2">
				<?php if (empty($revisions)): ?>
					<p class="text-sm text-gray-500">Belum ada histori perubahan.</p>
				<?php else: ?>
					<?php foreach ($revisions as $revision): ?>
						<div class="text-sm border-l-2 border-gray-200 pl-3">
							<p class="font-medium text-gray-700"><?php echo html_escape($revision['edit_summary']); ?></p>
							<p class="text-xs text-gray-500 mt-1">
								<?php echo date('d M Y, H:i', strtotime($revision['edited_at'])); ?> oleh
								<span class="font-semibold"><?php echo html_escape($revision['editor_name']); ?></span>
							</p>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<style>
	/* Styling untuk konten artikel yang dihasilkan dari editor WYSIWYG */
	.prose h1,
	.prose h2,
	.prose h3 {
		color: #374151;
		/* gray-700 */
	}

	.prose p,
	.prose li {
		color: #4b5563;
		/* gray-600 */
	}

	.prose a {
		color: #3b82f6;
		/* blue-500 */
	}

	.prose img {
		max-width: 100%;
		border-radius: 0.5rem;
	}
</style>
