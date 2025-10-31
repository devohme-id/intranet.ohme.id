        </div> <!-- Penutup .container dari sidebar.php -->
        </main> <!-- Penutup <main> dari sidebar.php -->

        <!-- Footer Utama -->
        <!-- (optional: tambahkan footer responsif di sini jika perlu) -->

        </div> <!-- Penutup .flex.flex-col dari sidebar.php -->
        </div> <!-- Penutup .flex.h-screen dari header.php -->

        <script>
        	feather.replace();
        	document.addEventListener('DOMContentLoaded', function() {

        		// --- Fungsionalitas Sidebar Dropdown ---
        		const menuToggleLinks = document.querySelectorAll('[data-menu-toggle]');
        		menuToggleLinks.forEach(link => {
        			link.addEventListener('click', function(e) {
        				e.preventDefault();
        				const submenu = this.nextElementSibling;
        				const icon = this.querySelector('i[data-feather="chevron-down"]');
        				if (submenu && submenu.tagName === 'UL') {
        					submenu.classList.toggle('hidden');
        					if (icon) {
        						icon.classList.toggle('rotate-180');
        					}
        				}
        			});
        		});

        		// --- Fungsionalitas Sidebar Mobile ---
        		const mobileMenuButton = document.getElementById('mobile-menu-button');
        		const sidebar = document.getElementById('sidebar');
        		const backdrop = document.getElementById('sidebar-backdrop');

        		function toggleSidebar() {
        			const isOpen = !sidebar.classList.contains('-translate-x-full');
        			sidebar.classList.toggle('-translate-x-full', isOpen);
        			backdrop.classList.toggle('hidden', isOpen);
        		}
        		if (mobileMenuButton && sidebar && backdrop) {
        			mobileMenuButton.addEventListener('click', toggleSidebar);
        			backdrop.addEventListener('click', toggleSidebar);
        		}

        		// --- Fungsionalitas Dropdown Profil ---
        		const profileMenuButton = document.getElementById('profile-menu-button');
        		const profileMenu = document.getElementById('profile-menu');
        		if (profileMenuButton && profileMenu) {
        			profileMenuButton.addEventListener('click', function(e) {
        				e.stopPropagation();
        				profileMenu.classList.toggle('hidden');
        			});
        			window.addEventListener('click', function(e) {
        				if (!profileMenuButton.contains(e.target) && !profileMenu.contains(e.target)) {
        					profileMenu.classList.add('hidden');
        				}
        			});
        		}

        		// --- Fungsionalitas Sidebar Desktop (Show/Hide & Hover Expand) ---
        		const desktopToggleButton = document.getElementById('desktop-sidebar-toggle');
        		const mainLayout = document.querySelector('body > .flex, body > .flex-col');

        		// Saat halaman dimuat, periksa state dari localStorage
        		if (localStorage.getItem('oneportal-sidebar-collapsed') === 'true') {
        			if (mainLayout) mainLayout.classList.add('sidebar-collapsed');
        		}

        		// Event listener untuk tombol toggle
        		if (desktopToggleButton && mainLayout) {
        			desktopToggleButton.addEventListener('click', function() {
        				mainLayout.classList.toggle('sidebar-collapsed');
        				localStorage.setItem('oneportal-sidebar-collapsed', mainLayout.classList.contains('sidebar-collapsed'));
        			});
        		}

        		// Event listeners untuk hover expand kondisional
        		if (sidebar && mainLayout) {
        			sidebar.addEventListener('mouseenter', function() {
        				if (mainLayout.classList.contains('sidebar-collapsed')) {
        					mainLayout.classList.add('sidebar-hover-expand');
        				}
        			});
        			sidebar.addEventListener('mouseleave', function() {
        				if (mainLayout.classList.contains('sidebar-collapsed')) {
        					mainLayout.classList.remove('sidebar-hover-expand');
        				}
        			});
        		}
        	});
        </script>
        </body>

        </html>
