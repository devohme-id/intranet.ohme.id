<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Controller API untuk memeriksa status konektivitas server.
 * Controller ini tidak mewarisi MY_Controller karena harus dapat diakses secara publik
 * dari halaman login tanpa memerlukan sesi login.
 */
class ServerStatus extends CI_Controller
{

	/**
	 * Memeriksa konektivitas ke daftar server yang telah ditentukan.
	 * Menggunakan fsockopen untuk memeriksa port tertentu dengan timeout singkat.
	 * Mengembalikan hasil dalam format JSON.
	 */
	public function check()
	{
		// Daftar server yang akan diperiksa
		// Format: 'Nama Tampilan' => ['host' => 'ip_address', 'port' => port_number]
		$servers = [
			'Database'   => ['host' => '10.217.4.115', 'port' => 3306],
			'Web Server' => ['host' => '10.217.4.115', 'port' => 8090],
			'Internet'   => ['host' => '1.1.1.1', 'port' => 53], // Cloudflare DNS (port 53 untuk DNS)
			'GMES'       => ['host' => '10.217.4.65', 'port' => 80], // Asumsi port 80 (HTTP)
			'Router'     => ['host' => '192.168.12.1', 'port' => 8089], // Asumsi port 80 (Web Interface)
			'CCTV 1'     => ['host' => '192.168.12.200', 'port' => 8081],
			'CCTV 2'     => ['host' => '192.168.12.201', 'port' => 8082]
		];

		$results = [];
		$timeout = 300; // Timeout koneksi dalam detik (untuk respons cepat)

		foreach ($servers as $name => $server) {
			// Menggunakan @ untuk menekan warning jika koneksi gagal (misal, host tidak ditemukan)
			$connection = @fsockopen($server['host'], $server['port'], $errno, $errstr, $timeout);

			if (is_resource($connection)) {
				// Jika koneksi berhasil, status 'online'
				$results[] = ['name' => $name, 'status' => 'online'];
				fclose($connection);
			} else {
				// Jika koneksi gagal, status 'offline'
				$results[] = ['name' => $name, 'status' => 'offline'];
			}
		}

		// Mengatur header output sebagai JSON dan mengirimkan hasilnya
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($results));
	}
}
