<?php
function generate_menu_html($menus, $current_uri, $is_submenu = false, &$parent_active = false)
{
	$html = '<ul class="space-y-1 ' . ($is_submenu ? 'pl-4 mt-1 hidden' : '') . '">';
	foreach ($menus as $menu) {
		$has_children = isset($menu['children']) && !empty($menu['children']);
		$url = $has_children ? '#' : site_url($menu['menu_url']);
		$is_active = (strcasecmp($current_uri, $menu['menu_url']) == 0);
		$has_active_child = false;
		$submenu_html = '';
		if ($has_children) {
			$submenu_html = generate_menu_html($menu['children'], $current_uri, true, $has_active_child);
		}
		$final_is_active = $is_active || $has_active_child;
		if ($final_is_active && !$is_submenu) {
			$submenu_html = str_replace('hidden', '', $submenu_html);
		}
		if ($final_is_active) {
			$parent_active = true;
		}
		$active_class = $final_is_active ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-100';
		$link_class = 'flex items-center p-2 text-base font-normal rounded-lg transition-colors duration-150 ' . $active_class;
		$data_menu_toggle = $has_children ? 'data-menu-toggle' : '';
		$html .= '<li>';
		$html .= '<a href="' . $url . '" class="' . $link_class . '" ' . $data_menu_toggle . '>';
		if (!empty($menu['menu_icon'])) {
			$html .= '<i data-feather="' . html_escape($menu['menu_icon']) . '" class="w-5 h-5"></i>';
		}
		$html .= '<span class="ml-3 flex-1 whitespace-nowrap">' . html_escape($menu['menu_title']) . '</span>';
		if ($has_children) {
			$chevron_rotation = ($final_is_active) ? 'rotate-180' : '';
			$html .= '<i data-feather="chevron-down" class="w-4 h-4 transition-transform duration-200 ' . $chevron_rotation . '"></i>';
		}
		$html .= '</a>';
		$html .= $submenu_html;
		$html .= '</li>';
	}
	$html .= '</ul>';
	return $html;
}
?>

<!-- Style untuk Sidebar diciutkan -->
<style>
	/* Transisi halus */
	#sidebar,
	.main-content-wrapper,
	#sidebar .whitespace-nowrap,
	#desktop-sidebar-toggle i {
		transition: all 0.3s ease-in-out;
	}

	/* State diciutkan untuk sidebar */
	.sidebar-collapsed #sidebar {
		width: 5rem;
		/* 80px */
	}

	/* Sembunyikan teks dan panah dropdown saat diciutkan */
	.sidebar-collapsed #sidebar .whitespace-nowrap,
	.sidebar-collapsed #sidebar [data-feather='chevron-down'] {
		opacity: 0;
		visibility: hidden;
		width: 0;
	}

	/* Pusatkan ikon menu saat diciutkan */
	.sidebar-collapsed #sidebar nav a {
		justify-content: center;
	}

	.sidebar-collapsed #sidebar nav .ml-3 {
		margin-left: 0;
	}

	/* Putar ikon toggle */
	.sidebar-collapsed #desktop-sidebar-toggle i {
		transform: rotate(180deg);
	}

	/* Gaya untuk auto-expand saat hover */
	.sidebar-collapsed.sidebar-hover-expand #sidebar {
		width: 16rem;
		/* 256px, lebar asli */
		box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
	}

	.sidebar-collapsed.sidebar-hover-expand #sidebar .whitespace-nowrap,
	.sidebar-collapsed.sidebar-hover-expand #sidebar [data-feather='chevron-down'] {
		opacity: 1;
		visibility: visible;
		width: auto;
		transition-delay: 0.15s;
	}

	.sidebar-collapsed.sidebar-hover-expand #sidebar nav a {
		justify-content: flex-start;
	}

	.sidebar-collapsed.sidebar-hover-expand #sidebar nav .ml-3 {
		margin-left: 0.75rem;
		/* 12px */
	}

	/* PERBAIKAN: Sidebar dinamis mengikuti tinggi konten */
	.layout-container {
		display: flex;
		min-height: 100vh;
		flex-direction: row;
		width: 100%;
	}

	/* Desktop: Sidebar mengikuti tinggi container */
	@media (min-width: 768px) {
		#sidebar {
			position: sticky;
			top: 0;
			height: 100vh;
			overflow-y: auto;
		}

		/* Jika konten lebih tinggi dari viewport, sidebar akan extend */
		.layout-container {
			align-items: stretch;
			width: 100%;
		}

		/* Pastikan sidebar selalu full height */
		#sidebar {
			min-height: 100vh;
		}

		/* Pastikan main content menggunakan sisa space */
		.main-content-wrapper {
			flex: 1;
			min-width: 0;
			width: 100%;
		}
	}

	/* Mobile: Tetap gunakan fixed positioning */
	@media (max-width: 767px) {
		#sidebar {
			position: fixed;
			height: 100vh;
		}
	}

	/* Alternative: Jika ingin sidebar selalu mengikuti tinggi konten secara dinamis */
	.dynamic-height-layout {
		display: flex;
		min-height: 100vh;
		width: 100%;
	}

	.dynamic-height-layout #sidebar {
		position: relative;
		height: auto;
		min-height: 100vh;
	}

	@media (min-width: 768px) {
		.dynamic-height-layout #sidebar {
			position: relative;
			height: auto;
		}

		.dynamic-height-layout .main-content-wrapper {
			flex: 1;
			width: 100%;
		}
	}
</style>

<!-- Backdrop untuk sidebar mobile -->
<div id="sidebar-backdrop" class="fixed inset-0 z-20 bg-black bg-opacity-50 hidden md:hidden"></div>

<!-- Container Layout yang diperbaiki -->
<div class="layout-container">
	<!-- Sidebar dengan height dinamis -->
	<aside id="sidebar"
		class="
		z-30 flex-shrink-0 w-64
		bg-white shadow-lg transition-transform duration-300 ease-in-out
		transform fixed -translate-x-full
		md:relative md:translate-x-0 md:flex md:flex-col
		"
		aria-label="Sidebar">
		<div class="flex flex-col h-full">
			<!-- Sidebar Header with Toggle Button -->
			<div class="flex items-center justify-between h-[65px] px-4 flex-shrink-0">
				<a href="<?php echo site_url('dashboard'); ?>" class="flex items-center overflow-hidden">
					<img src="https://placehold.co/32x32/db2777/ffffff?text=OP" class="h-8 flex-shrink-0" alt="Portal Logo" />
					<span class="ml-3 text-xl font-semibold whitespace-nowrap">ONE-Portal</span>
				</a>
			</div>
			<nav class="flex-1 px-2 space-y-1 py-4 overflow-y-auto">
				<?php echo generate_menu_html($menus, $current_uri); ?>
			</nav>
			<div class="px-4 py-3 border-t border-gray-200 flex-shrink-0">
				<p class="text-xs text-gray-400 text-center">
					<!-- ONE-Portal v1.0.0 -->
					Dibuat oleh <strong>Roby Kornela</strong>
					<a href="https://wa.me/+6281319019994" target="_blank" class="inline-flex items-center ml-1">
						<img src="https://static.cdnlogo.com/logos/w/29/whatsapp-icon.svg"
							alt="WhatsApp"
							class="w-4 h-4 inline-block">
					</a>
					</br>Poduction Engineering Team</br>
					PT. OHM Electronics Indonesia © <?= date('Y'); ?>
				</p>

			</div>
		</div>
	</aside>

	<!-- Konten Utama -->
	<div class="flex flex-col flex-1 min-w-0 main-content-wrapper w-full">
		<header class="z-10 py-4 bg-white shadow-md flex-shrink-0 w-full">
			<div class="flex items-center justify-between h-full px-4 md:px-6 w-full">
				<div class="flex items-center flex-1 min-w-0">
					<!-- Mobile hamburger -->
					<button id="mobile-menu-button" class="p-1 mr-2 rounded-md md:hidden focus:outline-none focus:ring-2 focus:ring-pink-500" aria-label="Menu">
						<i data-feather="menu" class="w-6 h-6 text-gray-600"></i>
					</button>
					<!-- Tombol Sidebar Desktop -->
					<button id="desktop-sidebar-toggle" class="p-2 mr-2 rounded-full hidden md:block hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-colors" aria-label="Toggle sidebar">
						<i data-feather="menu" class="w-6 h-6 text-gray-600"></i>
					</button>
					<h1 class="text-xl md:text-2xl font-semibold text-gray-700 truncate"><?php echo html_escape($page_title); ?></h1>
				</div>
				<ul class="flex items-center flex-shrink-0 space-x-3 md:space-x-6">
					<!-- Menu Profil dengan Dropdown -->
					<li class="relative">
						<button id="profile-menu-button" class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none" aria-label="Account" aria-haspopup="true">
							<div class="flex items-center">
								<div class="hidden md:block text-right mr-3">
									<p class="font-bold text-gray-800"><?php echo html_escape($user_info['full_name']); ?></p>
									<p class="text-sm text-gray-500"><?php echo html_escape($user_info['role_name']); ?></p>
								</div>
								<?php
								$header_photo_url = 'https://placehold.co/40x40/7c3aed/ffffff?text=' . substr($user_info['full_name'], 0, 1);
								if (!empty($user_info['profile_picture_url'])) {
									$header_photo_path = FCPATH . $user_info['profile_picture_url'];
									if (file_exists($header_photo_path)) {
										$header_photo_url = base_url($user_info['profile_picture_url']) . '?t=' . filemtime($header_photo_path);
									}
								}
								?>
								<img class="object-cover w-10 h-10 rounded-full" src="<?php echo $header_photo_url; ?>" alt="User avatar" aria-hidden="true" />
							</div>
						</button>
						<!-- Dropdown Menu -->
						<div id="profile-menu" class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md hidden" aria-label="submenu">
							<a class="flex items-center w-full px-4 py-2 text-sm font-medium text-left text-gray-700 rounded-md hover:bg-gray-100 hover:text-pink-600" href="<?php echo site_url('profile'); ?>">
								<i data-feather="user" class="w-4 h-4 mr-2"></i>
								<span>Profil Saya</span>
							</a>
							<a class="flex items-center w-full px-4 py-2 text-sm font-medium text-left text-gray-700 rounded-md hover:bg-gray-100 hover:text-pink-600" href="<?php echo site_url('auth/logout'); ?>">
								<i data-feather="log-out" class="w-4 h-4 mr-2"></i>
								<span>Logout</span>
							</a>
						</div>
					</li>
				</ul>
			</div>
		</header>
		<main class="flex-1 w-full">
			<div class="p-4 md:p-6 w-full">
				<!-- Konten halaman akan dimuat di sini -->
