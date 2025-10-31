<!-- Widget Status Jaringan -->
<div class="p-6 bg-white rounded-lg shadow-md">
	<div class="flex items-center justify-between mb-4">
		<h3 class="text-lg font-semibold text-gray-800">Status Jaringan</h3>
		<i data-feather="globe" class="text-gray-500"></i>
	</div>
	<div id="status-container" class="space-y-3">
		<!-- Status items will be injected here by JavaScript -->
	</div>
	<p class="text-xs text-gray-400 mt-4 text-right">Status diperbarui otomatis setiap 5 menit</p>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Pastikan Feather Icons di-replace jika belum
		if (typeof feather !== 'undefined') {
			feather.replace();
		}

		const servers = [{
				name: 'Database',
				host: '10.217.4.115',
				port: 3306,
				type: 'tcp'
			},
			{
				name: 'Web Server',
				host: '10.217.4.115',
				port: 8090,
				type: 'http'
			},
			{
				name: 'Internet',
				host: '1.1.1.1',
				port: 80,
				type: 'http'
			},
			{
				name: 'GMES',
				host: '10.217.4.65',
				port: 80,
				type: 'http'
			},
			{
				name: 'Router',
				host: '192.168.12.1',
				port: 8089,
				type: 'http'
			},
			{
				name: 'CCTV 1',
				host: '192.168.12.200',
				port: 8081,
				type: 'http'
			},
			{
				name: 'CCTV 2',
				host: '192.168.12.201',
				port: 8082,
				type: 'http'
			}
		];

		const container = document.getElementById('status-container');

		function renderInitialState() {
			if (!container) return;
			container.innerHTML = ''; // Clear previous state
			servers.forEach(server => {
				const serverId = `status-${server.name.replace(/\s+/g, '-')}`;
				const element = `
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <span id="${serverId}-dot" class="w-3 h-3 rounded-full mr-3 status-pending"></span>
                        <span class="text-gray-700">${server.name}</span>
                    </div>
                    <span id="${serverId}-status" class="font-semibold text-gray-400">Checking...</span>
                </div>
            `;
				container.innerHTML += element;
			});
		}

		async function checkServerStatus(server) {
			const serverId = `status-${server.name.replace(/\s+/g, '-')}`;
			const dotElement = document.getElementById(`${serverId}-dot`);
			const statusElement = document.getElementById(`${serverId}-status`);

			if (!dotElement || !statusElement) return;

			// Simulasi untuk port non-HTTP
			if (server.type === 'tcp') {
				updateStatusUI(dotElement, statusElement, 'unknown', 'Monitored');
				return;
			}

			const controller = new AbortController();
			const timeoutId = setTimeout(() => controller.abort(), 300000);

			try {
				await fetch(`http://${server.host}:${server.port}`, {
					mode: 'no-cors',
					signal: controller.signal
				});
				updateStatusUI(dotElement, statusElement, 'online', 'Online');
			} catch (error) {
				updateStatusUI(dotElement, statusElement, 'offline', 'Offline');
			} finally {
				clearTimeout(timeoutId);
			}
		}

		function updateStatusUI(dot, status, state, text) {
			dot.classList.remove('status-pending', 'bg-green-500', 'bg-red-500', 'bg-yellow-500', 'bg-gray-400');
			status.classList.remove('text-gray-400', 'text-green-600', 'text-red-600', 'text-yellow-600');

			switch (state) {
				case 'online':
					dot.classList.add('bg-green-500');
					status.classList.add('text-green-600');
					break;
				case 'offline':
					dot.classList.add('bg-red-500');
					status.classList.add('text-red-600');
					break;
				case 'unknown':
					dot.classList.add('bg-yellow-500');
					status.classList.add('text-yellow-600');
					break;
			}
			status.textContent = text;
		}

		function runAllChecks() {
			servers.forEach(server => checkServerStatus(server));
		}

		// Initial run
		renderInitialState();
		runAllChecks();

		// Re-check every 5 menit dalam milidetik
		setInterval(runAllChecks, 300000);
	});
</script>
