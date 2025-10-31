<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Struktur Hak Akses Profesional (Permissions Structure)
|--------------------------------------------------------------------------
|
| Dikelompokkan berdasarkan menu utama di sidebar untuk konsistensi
| dan kemudahan manajemen. Memisahkan antara hak akses untuk 'melihat' (VIEW)
| dan hak akses untuk 'mengelola' (MANAGE/CRUD).
|
*/

$config['permissions'] = [
	'Dasar' => [
		'DASHBOARD_VIEW' => 'Melihat Dashboard Utama',
	],
	'Komunikasi Inti' => [
		'COMPANY_PROFILE_VIEW' => 'Melihat Visi Misi & Struktur Organisasi',
		'EMPLOYEE_DIRECTORY_VIEW' => 'Melihat Direktori Karyawan',
		'DOCUMENTS_VIEW' => 'Melihat Pusat Dokumen (SOP)',
		'NEWS_VIEW' => 'Melihat Berita Perusahaan',
		'GALLERIES_VIEW' => 'Melihat Galeri Foto',
	],
	'Layanan Digital' => [
		'LEAVE_REQUEST_CREATE' => 'Mengajukan Cuti',
		'PAYSLIP_VIEW_OWN' => 'Melihat Slip Gaji Sendiri',
		'HELPDESK_TICKET_CREATE' => 'Membuat Tiket Helpdesk',
	],
	'Operasional' => [
		'PRODUCTION_ORDERS_VIEW' => 'Melihat Pesanan Produksi',
	],
	'Kolaborasi' => [
		'FORUM_VIEW' => 'Melihat & Berpartisipasi di Forum',
		'PROJECT_VIEW' => 'Melihat Ruang Kerja Proyek',
		'WIKI_VIEW' => 'Melihat Pusat Pengetahuan',
	],
	'Aplikasi & Layanan' => [
		'APP_LAUNCHER_VIEW' => 'Melihat Gerbang Aplikasi',
	],
	'Administrasi' => [
		// Sub-grup untuk kejelasan
		'USERS_VIEW'   => '(Admin) Melihat Daftar Pengguna',
		'USERS_CREATE' => '(Admin) Menambah Pengguna Baru',
		'USERS_UPDATE' => '(Admin) Mengedit Data Pengguna',
		'USERS_DELETE' => '(Admin) Menghapus Pengguna',

		'ROLES_VIEW'   => '(Admin) Melihat Daftar Peran',
		'ROLES_CREATE' => '(Admin) Membuat Peran Baru',
		'ROLES_UPDATE' => '(Admin) Mengedit Peran & Hak Akses',
		'ROLES_DELETE' => '(Admin) Menghapus Peran',

		'EMPLOYEES_VIEW'   => '(Admin) Melihat Daftar Karyawan',
		'EMPLOYEES_CREATE' => '(Admin) Menambah Karyawan Baru',
		'EMPLOYEES_UPDATE' => '(Admin) Mengedit Data Karyawan',
		'EMPLOYEES_DELETE' => '(Admin) Menghapus Karyawan',

		'COMPANY_PROFILE_MANAGE' => '(Admin) Mengelola Profil Perusahaan',
		'DOCUMENT_MANAGE' => '(Admin) Mengelola Dokumen (CRUD)',
		'NEWS_MANAGE'    => '(Admin) Mengelola Berita (CRUD)',
		'GALLERY_MANAGE' => '(Admin) Mengelola Galeri (CRUD)',
		'ANNOUNCEMENT_MANAGE' => '(Admin) Mengelola Pengumuman (CRUD)',
		'EVENT_MANAGE' => '(Admin) Mengelola Kalender (CRUD)',
		'LEAVE_MANAGE_ALL' => '(Admin) Mengelola Semua Pengajuan Cuti',
		'PAYSLIP_MANAGE'   => '(Admin) Mengelola Slip Gaji',
		'HELPDESK_MANAGE_ALL' => '(Admin) Mengelola Semua Tiket Helpdesk',
		'FORUM_CATEGORY_MANAGE' => '(Admin) Mengelola Kategori Forum',
		'WIKI_ARTICLE_MANAGE' => '(Admin) Mengelola Artikel Wiki',
		'WIKI_CATEGORY_MANAGE' => '(Admin) Mengelola Kategori Wiki',
		'APP_LAUNCHER_MANAGE' => '(Admin) Mengelola Gerbang Aplikasi',
	]
];
