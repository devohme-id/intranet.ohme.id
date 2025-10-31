<!-- Load TinyMCE SelfHosted -->
<script src="<?= base_url('assets/tinymce/tinymce.min.js'); ?>"></script>
<script>
	tinymce.init({
		selector: 'textarea#content-editor',
		height: 500,
		license_key: 'gpl', // ✅ tambahkan ini
		plugins: 'lists link image table code help wordcount autoresize',
		toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | table | code',
		// Memungkinkan upload gambar langsung dari editor
		image_title: true,
		automatic_uploads: true,
		file_picker_types: 'image',
		file_picker_callback: function(cb, value, meta) {
			var input = document.createElement('input');
			input.setAttribute('type', 'file');
			input.setAttribute('accept', 'image/*');
			input.onchange = function() {
				var file = this.files[0];
				var reader = new FileReader();
				reader.onload = function() {
					var id = 'blobid' + (new Date()).getTime();
					var blobCache = tinymce.activeEditor.editorUpload.blobCache;
					var base64 = reader.result.split(',')[1];
					var blobInfo = blobCache.create(id, file, base64);
					blobCache.add(blobInfo);
					cb(blobInfo.blobUri(), {
						title: file.name
					});
				};
				reader.readAsDataURL(file);
			};
			input.click();
		}
	});
</script>

<div class="bg-white p-8 rounded-lg shadow-md">
	<form action="<?php echo site_url('admin/companyprofile/edit/' . $profile->profile_key); ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="mb-6">
			<label for="title" class="block mb-2 text-sm font-medium text-gray-900">Judul Halaman</label>
			<input type="text" id="title" name="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo html_escape($profile->title); ?>" required>
		</div>

		<div class="mb-6">
			<label for="content-editor" class="block mb-2 text-sm font-medium text-gray-900">Konten Halaman</label>
			<p class="text-xs text-gray-500 mb-2">Untuk menyisipkan gambar bagan organisasi, gunakan tombol 'Insert/edit image' <i data-feather="image" class="inline-block w-4 h-4"></i> pada toolbar di bawah.</p>
			<textarea id="content-editor" name="content" rows="15"><?php echo $profile->content; ?></textarea>
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Simpan Perubahan</button>
			<a href="<?php echo site_url('admin/companyprofile'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Batal</a>
		</div>
	</form>
</div>
