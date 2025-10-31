<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - ONE-Portal</title>
	<!-- TailwindCSS -->
	<!-- <script src="https://cdn.tailwindcss.com"></script> -->
	<link rel="stylesheet" href="<?= base_url('assets/css/output.css') ?>">

	<!-- Feather Icons -->
	<script src="https://unpkg.com/feather-icons"></script>
	<!-- Custom Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<style>
		/* Load font dari folder assets/fonts */
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
			/* font-size: 0.875rem; */
		}

		/* Style untuk background image di sisi kanan */
		.bg-login-image {
			background-image: url('https://lh3.googleusercontent.com/p/AF1QipM5UcMtxCPm0birQ8hAYvhnGpGtLVvEjs5gIQZS=s680-w680-h510-rw');
			background-size: cover;
			background-position: center;
		}
	</style>
</head>

<body class="bg-gray-100">

	<div class="min-h-screen flex">
		<!-- Kolom Kiri - Panel Form Login -->
		<div class="w-full md:w-1/2 lg:w-1/3 bg-white flex items-center justify-center p-8 md:p-12">
			<div class="w-full max-w-md">
				<!-- Logo dan Judul -->
				<div class="text-center">
					<div class="flex items-center justify-center mb-4">
						<img src="https://placehold.co/32x32/db2777/ffffff?text=OP" class="h-8 mr-3" alt="Portal Logo" />
						<span class="text-2xl font-bold text-gray-800">ONE-Portal</span>
					</div>
					<p class="mb-8 text-gray-500">Silakan masuk untuk melanjutkan ke dasbor Anda.</p>
				</div>

				<!-- Pesan Error -->
				<?php if ($this->session->flashdata('error')): ?>
					<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
						<?php echo $this->session->flashdata('error'); ?>
					</div>
				<?php endif; ?>

				<!-- Form Login -->
				<form class="space-y-6" action="<?php echo site_url('auth/login'); ?>" method="post">
					<!-- CSRF Token -->
					<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

					<div>
						<label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
						<input type="text" name="username" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan NIK/Username" required>
					</div>

					<div>
						<label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
						<input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••" required>
					</div>

					<div>
						<button type="submit" class="w-full bg-blue-600 text-white font-bold p-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-slate-300 transition-all duration-300">
							Sign In
						</button>
					</div>
				</form>

				<div class="text-center mt-12 text-sm text-gray-400">
					<!-- <p>Copyright &copy; <?php echo date('Y'); ?> Nama Perusahaan Anda. All Rights Reserved.</p> -->
					<p>Dibuat oleh <strong>Roby Kornela</strong> - Pod Engineering Team</span> </br>PT. OHM Electronics Indonesia © <?= date('Y'); ?></p>
				</div>
			</div>
		</div>

		<!-- Kolom Kanan - Gambar Latar -->
		<div class="hidden md:block md:w-1/2 lg:w-2/3 bg-login-image">
			<!-- Div ini hanya berfungsi sebagai container untuk gambar latar -->
			<!-- Widget Status Server -->
			<div class="absolute bottom-4 right-4 w-64 bg-slate-800 bg-opacity-70 backdrop-blur-sm text-white p-4 rounded-lg shadow-lg border border-slate-700">
				<h3 class="font-semibold text-sm border-b border-slate-600 pb-2 mb-3 flex items-center">
					<i data-feather="globe" class="w-4 h-4 mr-2"></i>
					Status Jaringan
				</h3>
				<ul id="server-status-list" class="space-y-2 text-xs">
					<!-- JavaScript akan mengisi daftar ini -->
				</ul>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			feather.replace();

			const statusList = document.getElementById('server-status-list');
			if (statusList) {
				function checkServerStatus() {
					fetch('<?php echo site_url("api/serverstatus/check"); ?>')
						.then(response => {
							if (!response.ok) {
								throw new Error('Network response was not ok');
							}
							return response.json();
						})
						.then(data => {
							statusList.innerHTML = ''; // Hapus status sebelumnya
							data.forEach(server => {
								const li = document.createElement('li');
								li.className = 'flex items-center justify-between';

								const isOnline = server.status === 'online';
								const statusColor = isOnline ? 'green' : 'red';
								const statusText = isOnline ? 'Online' : 'Offline';

								li.innerHTML = `
                                    <span>${server.name}</span>
                                    <div class="flex items-center">
                                        <span class="mr-2 font-medium text-${statusColor}-400">${statusText}</span>
                                        <span class="w-3 h-3 bg-${statusColor}-500 rounded-full"></span>
                                    </div>
                                `;
								statusList.appendChild(li);
							});
						})
						.catch(error => {
							statusList.innerHTML = '<li class="text-xs text-yellow-400">Gagal memuat status server.</li>';
							console.error('Error checking server status:', error);
						});
				}

				// Tampilkan status "memeriksa" saat pertama kali dimuat
				statusList.innerHTML = '<li class="text-xs text-slate-400 animate-pulse">Memeriksa koneksi...</li>';

				// Lakukan pengecekan pertama, lalu ulangi setiap 15 detik
				checkServerStatus();
				setInterval(checkServerStatus, 15000);
			}
		});
	</script>

</body>

</html>
