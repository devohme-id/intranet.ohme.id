<?php if ($this->session->flashdata('success')): ?>
	<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
		<span class="font-medium">Sukses!</span> <?php echo $this->session->flashdata('success'); ?>
	</div>
<?php endif; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
	<div class="flex justify-between items-center mb-4">
		<h2 class="text-xl font-semibold">Daftar Berita</h2>
		<a href="<?php echo site_url('admin/news/add'); ?>" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tambah Berita</a>
	</div>
	<table class="w-full text-sm text-left text-gray-500">
		<thead class="text-xs text-gray-700 uppercase bg-gray-50">
			<tr>
				<th class="px-6 py-3">Judul</th>
				<th class="px-6 py-3">Status</th>
				<th class="px-6 py-3">Tgl Publikasi</th>
				<th class="px-6 py-3 text-right">Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($articles as $article): ?>
				<tr class="bg-white border-b">
					<td class="px-6 py-4 font-medium text-gray-900"><?php echo html_escape($article['title']); ?></td>
					<td class="px-6 py-4">
						<span class="px-2 py-1 font-semibold leading-tight rounded-full <?php echo $article['status'] == 'published' ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'; ?>">
							<?php echo ucfirst($article['status']); ?>
						</span>
					</td>
					<td class="px-6 py-4"><?php echo $article['published_at'] ? date('d M Y', strtotime($article['published_at'])) : '-'; ?></td>
					<td class="px-6 py-4 text-right">
						<a href="<?php echo site_url('admin/news/edit/' . $article['article_id']); ?>" class="font-medium text-blue-600 hover:underline mr-3">Edit</a>
						<a href="<?php echo site_url('admin/news/delete/' . $article['article_id']); ?>" onclick="return confirm('Anda yakin ingin menghapus berita ini?')" class="font-medium text-red-600 hover:underline">Hapus</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
