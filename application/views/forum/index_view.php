<div class="space-y-8">
	<?php foreach ($categories as $category): ?>
		<div class="bg-white rounded-lg shadow-md">
			<div class="p-6 border-b flex justify-between items-center">
				<div>
					<h2 class="text-xl font-semibold text-gray-800"><?php echo html_escape($category['category_name']); ?></h2>
					<p class="text-sm text-gray-500"><?php echo html_escape($category['description']); ?></p>
				</div>
				<a href="<?php echo site_url('forum/create/' . $category['category_id']); ?>" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Buat Topik Baru</a>
			</div>
			<div class="divide-y divide-gray-200">
				<?php if (empty($category['threads'])): ?>
					<p class="p-6 text-center text-gray-500">Belum ada topik di kategori ini.</p>
				<?php else: ?>
					<?php foreach ($category['threads'] as $thread): ?>
						<div class="p-6 hover:bg-gray-50">
							<div class="flex items-center justify-between">
								<div>
									<a href="<?php echo site_url('forum/thread/' . $thread['thread_id']); ?>" class="text-lg font-semibold text-gray-900 hover:text-blue-600"><?php echo html_escape($thread['title']); ?></a>
									<p class="text-sm text-gray-500">
										oleh <?php echo html_escape($thread['author_name']); ?> &bull; <?php echo date('d M Y', strtotime($thread['created_at'])); ?>
									</p>
								</div>
								<div class="text-center">
									<p class="text-lg font-bold text-gray-800"><?php echo $thread['post_count']; ?></p>
									<p class="text-xs text-gray-500">Balasan</p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
