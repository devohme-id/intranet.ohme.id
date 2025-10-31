<div class="p-6 bg-white rounded-lg shadow-md">
	<h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-4"><?php echo html_escape($profile->title); ?></h2>

	<div class="prose max-w-none">
		<?php echo $profile->content; // Konten dari database (HTML) 
		?>
	</div>
</div>

<style>
	/* Styling untuk konten yang diambil dari database */
	.prose h2 {
		font-size: 1.5rem;
		font-weight: 600;
		margin-top: 1.5em;
		margin-bottom: 0.5em;
	}

	.prose ul {
		list-style-type: disc;
		padding-left: 1.5em;
	}

	.prose p,
	.prose li {
		line-height: 1.75;
	}

	.prose img {
		max-width: 100%;
		height: auto;
		border-radius: 0.5rem;
		margin-top: 1em;
	}
</style>
