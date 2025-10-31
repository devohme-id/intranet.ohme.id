<?php
$is_edit = isset($event);
$form_action = $is_edit ? site_url('admin/events/edit/' . $event->event_id) : site_url('admin/events/add');
?>
<div class="bg-white p-8 rounded-lg shadow-md">
	<form action="<?php echo $form_action; ?>" method="post">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<div class="mb-4">
			<label for="title" class="block mb-2 text-sm font-medium">Nama Kegiatan</label>
			<input type="text" name="title" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($event->title) : ''; ?>" required>
		</div>

		<div class="mb-4">
			<label for="description" class="block mb-2 text-sm font-medium">Deskripsi</label>
			<textarea name="description" rows="4" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"><?php echo $is_edit ? html_escape($event->description) : ''; ?></textarea>
		</div>

		<div class="grid md:grid-cols-2 gap-6">
			<div class="mb-4">
				<label for="start_datetime" class="block mb-2 text-sm font-medium">Waktu Mulai</label>
				<input type="datetime-local" name="start_datetime" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? date('Y-m-d\TH:i', strtotime($event->start_datetime)) : ''; ?>" required>
			</div>
			<div class="mb-4">
				<label for="end_datetime" class="block mb-2 text-sm font-medium">Waktu Selesai (Opsional)</label>
				<input type="datetime-local" name="end_datetime" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo ($is_edit && $event->end_datetime) ? date('Y-m-d\TH:i', strtotime($event->end_datetime)) : ''; ?>">
			</div>
		</div>

		<div class="mb-6">
			<label for="location" class="block mb-2 text-sm font-medium">Lokasi</label>
			<input type="text" name="location" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo $is_edit ? html_escape($event->location) : ''; ?>">
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
			<a href="<?php echo site_url('admin/events'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>
