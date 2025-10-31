<!-- Grid Utama -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

	<!-- Kolom Konten Utama (2/3) -->
	<div class="lg:col-span-2 space-y-8">
		<!-- Kartu Selamat Datang -->
		<div class="p-6 bg-white rounded-lg shadow-md">
			<h2 class="text-2xl font-semibold text-gray-800">Selamat Datang, <?php echo html_escape($user_info['full_name']); ?>!</h2>
			<p class="mt-2 text-gray-600">Ini adalah pusat informasi Anda. Semua yang Anda butuhkan ada di sini.</p>
		</div>

		<!-- Pengumuman Penting -->
		<div class="p-6 bg-white rounded-lg shadow-md">
			<h3 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
				<i data-feather="bell" class="w-6 h-6 mr-3 text-blue-500"></i>
				Pengumuman Penting
			</h3>
			<div class="space-y-4">
				<?php if (!empty($announcements)): ?>
					<?php foreach ($announcements as $ann): ?>
						<div class="p-4 border-l-4 border-blue-500 bg-blue-50 rounded-r-lg">
							<h4 class="font-bold text-blue-800"><?php echo html_escape($ann['title']); ?></h4>
							<p class="mt-1 text-gray-600"><?php echo nl2br(html_escape($ann['content'])); ?></p>
							<p class="mt-2 text-xs text-gray-500">Berlaku hingga: <?php echo date('d M Y', strtotime($ann['end_date'])); ?></p>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="text-center py-4 text-gray-500"><i data-feather="info" class="w-8 h-8 mx-auto mb-2"></i>
						<p>Saat ini tidak ada pengumuman.</p>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Galeri Terbaru -->
		<div class="p-6 bg-white rounded-lg shadow-md">
			<h3 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
				<i data-feather="image" class="w-6 h-6 mr-3 text-purple-500"></i>
				Galeri Terbaru
			</h3>
			<?php if (!empty($latest_gallery)): ?>
				<a href="<?php echo site_url('galleries/view/' . $latest_gallery['gallery_id']); ?>" class="block group">
					<div class="relative overflow-hidden rounded-lg">
						<img class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-300" src="<?php echo $latest_gallery['cover_image'] ? base_url($latest_gallery['cover_image']) : 'https://placehold.co/800x400/e2e8f0/4a5568?text=Galeri+Terbaru'; ?>" alt="Cover Galeri <?php echo html_escape($latest_gallery['title']); ?>">
						<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
						<div class="absolute bottom-0 left-0 p-4">
							<h4 class="text-lg font-bold text-white"><?php echo html_escape($latest_gallery['title']); ?></h4><span class="text-sm text-gray-200 group-hover:underline">Lihat Album &rarr;</span>
						</div>
					</div>
				</a>
			<?php else: ?>
				<div class="text-center py-4 text-gray-500"><i data-feather="camera-off" class="w-8 h-8 mx-auto mb-2"></i>
					<p>Belum ada galeri yang dipublikasikan.</p>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- Kolom Widget Samping (1/3) -->
	<div class="space-y-8">
		<!-- **PERUBAHAN**: Menampilkan widget status jaringan -->
		<?php echo $network_status_widget; ?>
		<!-- **WIDGET BARU: PROYEK AKTIF** -->
		<div class="p-6 bg-white rounded-lg shadow-md">
			<h3 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
				<i data-feather="briefcase" class="w-6 h-6 mr-3 text-cyan-500"></i>
				Proyek/Task Aktif Saya
			</h3>
			<ul class="space-y-2">
				<?php if (!empty($active_projects)): ?>
					<?php foreach ($active_projects as $project): ?>
						<li>
							<a href="<?php echo site_url('projects/view/' . $project['project_id']); ?>" class="group flex items-center p-2 rounded-md hover:bg-gray-100 transition-colors">
								<div class="flex-shrink-0 bg-cyan-100 rounded-md p-2 mr-3">
									<i data-feather="folder" class="w-5 h-5 text-cyan-700"></i>
								</div>
								<p class="font-semibold text-gray-800 group-hover:text-blue-600"><?php echo html_escape($project['project_name']); ?></p>
							</a>
						</li>
					<?php endforeach; ?>
				<?php else: ?>
					<li class="text-center py-2 text-gray-500 text-sm">Anda tidak memiliki proyek aktif.</li>
				<?php endif; ?>
			</ul>
		</div>

		<!-- Kalender Kegiatan -->
		<div class="p-6 bg-white rounded-lg shadow-md">
			<h3 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
				<i data-feather="calendar" class="w-6 h-6 mr-3 text-green-500"></i>
				Kalender Kegiatan
			</h3>
			<ul class="space-y-3">
				<?php if (!empty($events)): ?>
					<?php foreach ($events as $event): ?>
						<li class="flex items-start">
							<div class="flex-shrink-0 text-center bg-green-100 rounded-md p-2 mr-4">
								<p class="text-sm font-bold text-green-800"><?php echo date('d', strtotime($event['start_datetime'])); ?></p>
								<p class="text-xs text-green-600"><?php echo date('M', strtotime($event['start_datetime'])); ?></p>
							</div>
							<div>
								<p class="font-semibold text-gray-800"><?php echo html_escape($event['title']); ?></p>
								<p class="text-sm text-gray-500"><?php echo date('H:i', strtotime($event['start_datetime'])); ?> di <?php echo html_escape($event['location']); ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				<?php else: ?>
					<li class="text-center py-2 text-gray-500 text-sm">Tidak ada kegiatan mendatang.</li>
				<?php endif; ?>
			</ul>
		</div>

		<!-- Ulang Tahun & Karyawan Baru -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-8">
			<div class="p-6 bg-white rounded-lg shadow-md">
				<h3 class="text-xl font-semibold text-gray-700 mb-4 flex items-center"><i data-feather="gift" class="w-6 h-6 mr-3 text-pink-500"></i>Ulang Tahun</h3>
				<ul class="space-y-4 text-sm">
					<?php if (!empty($birthdays)): ?>
						<?php foreach ($birthdays as $bday): ?>
							<li class="flex items-center">
								<img class="w-10 h-10 rounded-full mr-3 object-cover" src="https://placehold.co/40x40/f472b6/ffffff?text=<?php echo substr($bday['full_name'], 0, 1); ?>" alt="avatar">
								<div>
									<p class="font-semibold text-gray-800"><?php echo html_escape($bday['full_name']); ?></p>
									<p class="text-xs text-gray-500 italic mt-1">"all the wishes of the world, to have the greatest birthday"</p>
									<p class="text-xs text-pink-600 font-semibold mt-1"><?php echo date('d F', strtotime($bday['birth_date'])); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					<?php else: ?>
						<li class="text-center py-2 text-gray-500 text-sm">Tidak ada yang berulang tahun bulan ini.</li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="p-6 bg-white rounded-lg shadow-md">
				<h3 class="text-xl font-semibold text-gray-700 mb-4 flex items-center"><i data-feather="user-plus" class="w-6 h-6 mr-3 text-indigo-500"></i>Karyawan Baru</h3>
				<ul class="space-y-2 text-sm">
					<?php if (!empty($new_employees)): ?>
						<?php foreach ($new_employees as $new): ?>
							<li class="flex items-center">
								<img class="w-8 h-8 rounded-full mr-3 object-cover" src="https://placehold.co/40x40/818cf8/ffffff?text=<?php echo substr($new['full_name'], 0, 1); ?>" alt="avatar">
								<div>
									<p class="font-medium text-gray-800"><?php echo html_escape($new['full_name']); ?></p>
									<p class="text-gray-500">Bergabung <?php echo date('d F Y', strtotime($new['join_date'])); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					<?php else: ?>
						<li class="text-center py-2 text-gray-500 text-sm">Tidak ada karyawan baru baru-baru ini.</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
