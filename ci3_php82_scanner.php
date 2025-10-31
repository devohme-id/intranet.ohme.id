<?php

/**
 * CI3 PHP 8.2 Full Compatibility Scanner
 * by Opus Octopus
 * ---------------------------------
 * Scan seluruh file project CI3 untuk fungsi dan sintaks deprecated
 * di PHP 8.2.
 * - Scan: application/, system/, vendor/
 * - Skip: system/ jika versinya sudah terbaru
 */

$rootDir = __DIR__; // root project CI3
$ciSystemDir = $rootDir . '/system';
$skipSystem = false;

// === Cek versi CodeIgniter ===
$versionFile = $ciSystemDir . '/core/CodeIgniter.php';
if (file_exists($versionFile)) {
	$content = file_get_contents($versionFile);
	if (preg_match("/define\('CI_VERSION',\s*'([\d\.]+)'\)/", $content, $m)) {
		$ciVersion = $m[1];
		echo "ℹ️  Detected CodeIgniter version: $ciVersion\n";
		if (version_compare($ciVersion, '3.1.13', '>=')) {
			echo "✅ CI3 sudah versi terbaru, skip scan folder system/ untuk mengurangi false positive.\n";
			$skipSystem = true;
		}
	}
}

$patterns = [
	'create_function\(',
	'\beach\(',
	'\bsplit\(',
	'\bereg(_replace)?\(',
	'\bget_magic_quotes_gpc\(',
	'\bset_magic_quotes_runtime\(',
	'\bmssql_',
	'\bmysql_',
	'\b__autoload\(',
	'\$[a-zA-Z0-9_]+\{[^\}]+\}', // array style lama $var{0}
	'Only variables should be passed by reference',
	'deprecated'
];

$regex = '/' . implode('|', $patterns) . '/i';

function scanDirRecursive($dir, $regex, $skipSystem)
{
	$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
	$issues = [];
	foreach ($rii as $file) {
		if ($file->isDir()) continue;
		$path = $file->getPathname();

		// Skip folder system jika diminta
		if ($skipSystem && strpos($path, DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR) !== false) {
			continue;
		}

		$ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
		if (!in_array($ext, ['php', 'inc'])) continue;

		$lines = file($path);
		foreach ($lines as $num => $line) {
			if (preg_match($regex, $line)) {
				$issues[] = [
					'file' => $path,
					'line' => $num + 1,
					'code' => trim($line)
				];
			}
		}
	}
	return $issues;
}

echo "🔍 Scanning directory: $rootDir\n";
$results = scanDirRecursive($rootDir, $regex, $skipSystem);

if (empty($results)) {
	echo "✅ Tidak ditemukan penggunaan fungsi deprecated yang terdeteksi di PHP 8.2.\n";
} else {
	echo "⚠️ Ditemukan " . count($results) . " potensi masalah:\n\n";
	foreach ($results as $r) {
		echo "- {$r['file']} (Line {$r['line']}): {$r['code']}\n";
	}
}
