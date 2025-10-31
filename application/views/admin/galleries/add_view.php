<div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
	<h2 class="text-2xl font-semibold mb-6">Buat Album Galeri Baru</h2>
	<form action="<?php echo site_url('admin/galleries/add'); ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
		<div class="mb-4">
			<label class="block mb-2 text-sm font-medium">Judul Album</label>
			<input type="text" name="title" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
		</div>
		<div class="mb-4">
			<label class="block mb-2 text-sm font-medium">Tanggal Kegiatan</label>
			<input type="date" name="event_date" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
		</div>
		<div class="mb-6">
			<label class="block mb-2 text-sm font-medium">Deskripsi</label>
			<textarea name="description" rows="4" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"></textarea>
		</div>
		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Buat Album & Lanjut Upload</button>
			<a href="<?php echo site_url('admin/galleries'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>
