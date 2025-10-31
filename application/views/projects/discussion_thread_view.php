<div class="mb-6">
	<a href="<?php echo site_url('projects/discussions/' . $project->project_id); ?>" class="text-blue-600 hover:underline">&larr; Kembali ke Daftar Diskusi</a>
</div>

<div class="bg-white rounded-lg shadow-md">
	<!-- Topik Utama -->
	<div class="p-6 border-b">
		<h1 class="text-3xl font-bold text-gray-800"><?php echo html_escape($discussion->title); ?></h1>
	</div>
	<div class="p-6 flex">
		<div class="flex-shrink-0 mr-4 text-center">
			<div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600"><?php echo substr($discussion->author_name, 0, 1); ?></div>
			<p class="mt-2 text-sm font-semibold"><?php echo html_escape($discussion->author_name); ?></p>
		</div>
		<div class="flex-grow">
			<p class="text-xs text-gray-500 mb-2">Diposting pada <?php echo date('d F Y, H:i', strtotime($discussion->created_at)); ?></p>
			<div class="prose max-w-none"><?php echo nl2br(html_escape($discussion->content)); ?></div>
		</div>
	</div>

	<!-- Balasan -->
	<div class="bg-gray-50 p-6 space-y-6">
		<?php foreach ($replies as $reply): ?>
			<div class="flex">
				<div class="flex-shrink-0 mr-4 text-center">
					<div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600"><?php echo substr($reply['author_name'], 0, 1); ?></div>
					<p class="mt-2 text-sm font-semibold"><?php echo html_escape($reply['author_name']); ?></p>
				</div>
				<div class="flex-grow bg-white rounded-lg p-4 border">
					<p class="text-xs text-gray-500 mb-2">Membalas pada <?php echo date('d F Y, H:i', strtotime($reply['created_at'])); ?></p>
					<div class="prose max-w-none text-sm"><?php echo nl2br(html_escape($reply['content'])); ?></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<!-- Form Balasan -->
	<div class="p-6 border-t">
		<h3 class="text-lg font-semibold mb-4">Tulis Balasan Anda</h3>
		<form action="<?php echo site_url('projects/discussion_thread/' . $discussion->discussion_id); ?>" method="post">
			<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
			<textarea name="content" rows="5" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 mb-4" placeholder="Tulis balasan Anda di sini..." required></textarea>
			<button type="submit" name="create_reply" value="1" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Kirim Balasan</button>
		</form>
	</div>
</div>
