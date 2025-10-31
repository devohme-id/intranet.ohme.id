<div class="space-y-8">
	<?php if (!empty($categories)): ?>
		<?php foreach ($categories as $category): ?>
			<?php if (!empty($category['documents'])): ?>
				<div class="p-6 bg-white rounded-lg shadow-md">
					<h3 class="text-xl font-semibold text-gray-700 mb-4"><?php echo html_escape($category['category_name']); ?></h3>
					<ul class="space-y-2">
						<?php foreach ($category['documents'] as $doc): ?>
							<li>
								<!-- **KUNCI PERBAIKAN DOWNLOAD** -->
								<!-- Tautan mengarah ke controller download dengan ID dokumen -->
								<a href="<?php echo site_url('documents/download/' . $doc['document_id']); ?>" class="flex items-center p-3 text-gray-600 rounded-md hover:bg-gray-100 transition-colors">
									<i data-feather="file-text" class="w-5 h-5 mr-3 text-gray-500"></i>
									<span class="flex-1"><?php echo html_escape($doc['title']); ?></span>
									<span class="text-sm text-gray-400 mr-4"><?php echo html_escape($doc['document_code']); ?></span>
									<i data-feather="download" class="w-5 h-5 text-blue-500"></i>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php else: ?>
		<p class="text-center text-gray-500">Belum ada kategori dokumen yang dibuat.</p>
	<?php endif; ?>
</div>
