<div class="space-y-12">
	<?php foreach ($apps_grouped as $category => $apps): ?>
		<?php if (!empty($apps)): ?>
			<div>
				<h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?php echo $category; ?></h2>
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
					<?php foreach ($apps as $app): ?>
						<div class="flex flex-col bg-white p-6 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
							<div class="flex items-start">
								<div class="flex-shrink-0 bg-blue-100 rounded-lg p-3 mr-4">
									<i data-feather="<?php echo empty($app['icon']) ? 'box' : $app['icon']; ?>" class="w-6 h-6 text-blue-600"></i>
								</div>
								<div class="flex-grow">
									<h3 class="text-lg font-semibold text-gray-900"><?php echo html_escape($app['app_name']); ?></h3>
									<p class="text-sm text-gray-500 mt-1 line-clamp-2"><?php echo html_escape($app['description']); ?></p>
								</div>
							</div>
							<div class="mt-4 pt-4 border-t border-gray-200 text-xs text-gray-500 space-y-1 flex-grow">
								<p><strong>Versi:</strong> <?php echo html_escape($app['version']); ?></p>
								<p><strong>Status:</strong> <?php echo html_escape($app['status']); ?></p>
								<p><strong>Pengembang:</strong> <?php echo html_escape($app['developer']); ?></p>
								<!-- **PERUBAHAN: Menampilkan Info Implementasi** -->
								<?php if (!empty($app['implementation_info'])): ?>
									<p class="mt-2"><strong>Info Implementasi:</strong><br><?php echo nl2br(html_escape($app['implementation_info'])); ?></p>
								<?php endif; ?>
							</div>
							<div class="mt-4 text-right">
								<a href="<?php echo empty($app['url']) ? '#' : prep_url($app['url']); ?>" target="_blank" class="text-sm font-semibold text-blue-600 hover:underline">
									Buka Aplikasi &rarr;
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
