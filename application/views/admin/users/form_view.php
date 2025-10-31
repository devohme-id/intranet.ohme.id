<?php
$is_edit = isset($user);
$form_action = $is_edit ? site_url('admin/users/edit/' . $user->user_id) : site_url('admin/users/add');
?>
<?php if ($this->session->flashdata('error')): ?>
	<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
	<form action="<?php echo $form_action; ?>" method="post" id="user-form">
		<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />

		<?php if (!$is_edit): ?>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Pilih Karyawan</label>
				<select name="employee_id" id="employee-select" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
					<option value="">-- Pilih Karyawan (yang belum punya akun) --</option>
					<?php foreach ($employees as $emp): ?>
						<option value="<?php echo $emp['employee_id']; ?>" data-nik="<?php echo html_escape($emp['nik']); ?>">
							<?php echo html_escape($emp['full_name']) . ' (' . html_escape($emp['nik']) . ')'; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php else: ?>
			<div class="mb-4">
				<label class="block mb-2 text-sm font-medium">Nama Lengkap</label>
				<input type="text" name="full_name" class="bg-gray-200 border border-gray-300 text-sm rounded-lg w-full p-2.5" value="<?php echo html_escape($user->full_name); ?>" readonly>
			</div>
		<?php endif; ?>

		<div class="mb-4">
			<label for="username" class="block mb-2 text-sm font-medium">Username</label>
			<div class="relative">
				<input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 pr-10" value="<?php echo $is_edit ? html_escape($user->username) : ''; ?>" required>
				<div id="username-status" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"></div>
			</div>
			<p id="username-message" class="mt-1 text-xs"></p>
		</div>

		<div class="grid md:grid-cols-2 gap-6 mb-4">
			<!-- **KUNCI PERBAIKAN TUNTAS** -->
			<div>
				<label class="block mb-2 text-sm font-medium">Kata Sandi</label>
				<!-- Container 'relative' sekarang hanya membungkus input dan tombol -->
				<div class="relative">
					<input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 pr-10" <?php echo $is_edit ? '' : 'required'; ?>>
					<button type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500" onclick="togglePassword('password')">
						<i data-feather="eye" class="w-5 h-5"></i>
					</button>
				</div>
			</div>
			<div>
				<label class="block mb-2 text-sm font-medium">Konfirmasi Kata Sandi</label>
				<!-- Container 'relative' sekarang hanya membungkus input dan tombol -->
				<div class="relative">
					<input type="password" name="confirm_password" id="confirm_password" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 pr-10" <?php echo $is_edit ? '' : 'required'; ?>>
					<button type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500" onclick="togglePassword('confirm_password')">
						<i data-feather="eye" class="w-5 h-5"></i>
					</button>
				</div>
			</div>
		</div>
		<?php if ($is_edit): ?><p class="text-xs text-gray-500 -mt-2 mb-4">Kosongkan kata sandi jika tidak ingin mengubah.</p><?php endif; ?>

		<div class="mb-4">
			<label class="block mb-2 text-sm font-medium">Peran (Role)</label>
			<select name="role_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required>
				<?php foreach ($roles as $role): ?>
					<option value="<?php echo $role['role_id']; ?>" <?php echo ($is_edit && $user->role_id == $role['role_id']) ? 'selected' : ''; ?>><?php echo html_escape($role['role_name']); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-6">
			<label class="flex items-center">
				<input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300" <?php echo ($is_edit && $user->is_active) || !$is_edit ? 'checked' : ''; ?>>
				<span class="ml-2 text-sm font-medium text-gray-900">Akun Aktif</span>
			</label>
		</div>

		<div class="flex items-center space-x-4">
			<button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
			<a href="<?php echo site_url('admin/users'); ?>" class="text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg">Batal</a>
		</div>
	</form>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Fitur: Inisiasi Username Otomatis
		const employeeSelect = document.getElementById('employee-select');
		const usernameInput = document.getElementById('username');
		if (employeeSelect) {
			employeeSelect.addEventListener('change', function() {
				const selectedOption = this.options[this.selectedIndex];
				const nik = selectedOption.getAttribute('data-nik');
				if (nik) {
					usernameInput.value = 'OHM' + nik;
					// Memicu event 'input' untuk validasi
					usernameInput.dispatchEvent(new Event('input'));
				} else {
					usernameInput.value = '';
				}
			});
		}

		// Fitur: Validasi Username Duplikat via AJAX
		const usernameStatus = document.getElementById('username-status');
		const usernameMessage = document.getElementById('username-message');
		const userForm = document.getElementById('user-form');
		let usernameTimer;
		let isUsernameValid = true;

		usernameInput.addEventListener('input', function() {
			clearTimeout(usernameTimer);
			const username = this.value;
			usernameStatus.innerHTML = '<i data-feather="loader" class="w-5 h-5 text-gray-400 animate-spin"></i>';
			feather.replace();

			if (username.length < 3) {
				usernameStatus.innerHTML = '';
				usernameMessage.textContent = '';
				return;
			}

			usernameTimer = setTimeout(() => {
				const formData = new FormData();
				formData.append('username', username);
				formData.append('<?php echo $csrf['name']; ?>', '<?php echo $csrf['hash']; ?>');
				<?php if ($is_edit): ?>
					formData.append('exclude_id', '<?php echo $user->user_id; ?>');
				<?php endif; ?>

				fetch('<?php echo site_url("admin/users/check_username_ajax"); ?>', {
						method: 'POST',
						body: formData
					})
					.then(response => response.json())
					.then(data => {
						if (data.exists) {
							usernameStatus.innerHTML = '<i data-feather="x-circle" class="w-5 h-5 text-red-500"></i>';
							usernameMessage.textContent = 'Username ini sudah digunakan.';
							usernameMessage.className = 'mt-1 text-xs text-red-600';
							isUsernameValid = false;
						} else {
							usernameStatus.innerHTML = '<i data-feather="check-circle" class="w-5 h-5 text-green-500"></i>';
							usernameMessage.textContent = 'Username tersedia.';
							usernameMessage.className = 'mt-1 text-xs text-green-600';
							isUsernameValid = true;
						}
						feather.replace();
					});
			}, 500); // Delay 500ms setelah user berhenti mengetik
		});

		userForm.addEventListener('submit', function(e) {
			if (!isUsernameValid) {
				e.preventDefault();
				alert('Username yang Anda masukkan sudah digunakan. Harap ganti dengan yang lain.');
			}
		});
	});

	// Fitur: Hide/Show Password
	function togglePassword(id) {
		const input = document.getElementById(id);
		// Tombol sekarang adalah sibling dari input, bukan nextElementSibling
		const button = input.nextElementSibling;
		const icon = button.querySelector('i');
		if (input.type === 'password') {
			input.type = 'text';
			icon.setAttribute('data-feather', 'eye-off');
		} else {
			input.type = 'password';
			icon.setAttribute('data-feather', 'eye');
		}
		feather.replace();
	}
</script>
