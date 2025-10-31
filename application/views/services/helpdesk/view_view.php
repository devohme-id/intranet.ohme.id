<div class="bg-white rounded-lg shadow-md">
	<!-- Header Tiket -->
	<div class="p-6 border-b">
		<h2 class="text-2xl font-semibold text-gray-800"><?php echo html_escape($ticket->subject); ?></h2>
		<p class="text-sm text-gray-500">Tiket #<?php echo $ticket->ticket_code; ?> &bull; Dibuat pada <?php echo date('d F Y, H:i', strtotime($ticket->created_at)); ?></p>
	</div>
	<!-- Konten Percakapan -->
	<div class="p-6 space-y-6">
		<!-- Pesan Asli -->
		<div class="flex">
			<div class="flex-shrink-0 mr-4">
				<div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600"><?php echo substr($ticket->user_name, 0, 1); ?></div>
			</div>
			<div class="flex-grow bg-gray-100 rounded-lg p-4">
				<p class="font-semibold text-gray-800"><?php echo html_escape($ticket->user_name); ?></p>
				<p class="text-sm text-gray-600 whitespace-pre-wrap"><?php echo html_escape($ticket->description); ?></p>
			</div>
		</div>
		<!-- Balasan -->
		<?php foreach ($replies as $reply): ?>
			<?php $is_admin = ($reply['role_id'] == 1); ?>
			<div class="flex <?php echo $is_admin ? 'justify-end' : ''; ?>">
				<?php if (!$is_admin): ?>
					<div class="flex-shrink-0 mr-4">
						<div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600"><?php echo substr($reply['replier_name'], 0, 1); ?></div>
					</div>
				<?php endif; ?>
				<div class="flex-grow <?php echo $is_admin ? 'bg-blue-100 text-right' : 'bg-gray-100'; ?> rounded-lg p-4 max-w-xl">
					<p class="font-semibold <?php echo $is_admin ? 'text-blue-800' : 'text-gray-800'; ?>"><?php echo html_escape($reply['replier_name']); ?></p>
					<p class="text-sm text-gray-600 whitespace-pre-wrap"><?php echo html_escape($reply['message']); ?></p>
				</div>
				<?php if ($is_admin): ?>
					<div class="flex-shrink-0 ml-4">
						<div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center font-bold text-blue-600">A</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<!-- Form Balasan -->
	<div class="p-6 border-t">
		<?php if ($ticket->status != 'closed'): ?>
			<form action="<?php echo site_url('services/helpdesk/view/' . $ticket->ticket_id); ?>" method="post">
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
				<textarea name="reply_message" rows="4" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 mb-4" placeholder="Tulis balasan Anda..." required></textarea>
				<div class="flex justify-between items-center">
					<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Kirim Balasan</button>
					<?php if ($ticket->status == 'resolved'): ?>
						<button type="submit" name="close_ticket" value="1" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5">Tutup Tiket</button>
					<?php endif; ?>
				</div>
			</form>
		<?php else: ?>
			<p class="text-center text-gray-500 font-medium">Tiket ini telah ditutup.</p>
		<?php endif; ?>
	</div>
</div>
