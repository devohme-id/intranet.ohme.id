<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo html_escape($page_title); ?> - ONE-Portal | PT. OHM Electronics Indonesia</title>
	<!-- TailwindCSS -->
	<!-- <script src="https://cdn.tailwindcss.com"></script> -->
	<link rel="stylesheet" href="<?= base_url('assets/css/output.css') ?>">

	<!-- Feather Icons -->
	<script src="https://unpkg.com/feather-icons"></script>
	<!-- Custom Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<style>
		@font-face {
			font-family: 'MyFontText';
			src: url('<?= base_url('assets/fonts/LGEITextTTF-Regular.ttf'); ?>') format('truetype');
			font-weight: normal;
			font-style: normal;
		}

		@font-face {
			font-family: 'MyFontHeadline';
			src: url('<?= base_url('assets/fonts/LGEIHeadlineTTF-Regular.ttf'); ?>') format('truetype');
			font-weight: normal;
			font-style: normal;
		}

		body {
			font-family: 'MyFontText', sans-serif;
			font-size: 16px;
		}

		::-webkit-scrollbar {
			width: 8px;
			height: 8px;
		}

		::-webkit-scrollbar-track {
			background: #f1f1f1;
		}

		::-webkit-scrollbar-thumb {
			background: #888;
			border-radius: 4px;
		}

		::-webkit-scrollbar-thumb:hover {
			background: #555;
		}

		img[alt="User avatar"] {
			width: 36px !important;
			height: 36px !important;
			max-width: 40px !important;
			max-height: 40px !important;
			min-width: 32px;
			min-height: 32px;
			border-radius: 9999px;
			object-fit: cover;
		}

		@media (min-width: 640px) {
			img[alt="User avatar"] {
				width: 40px !important;
				height: 40px !important;
			}
		}
	</style>
</head>

<body class="bg-gray-50">
	<div class="flex flex-col min-h-screen bg-gray-50 md:flex-row">
