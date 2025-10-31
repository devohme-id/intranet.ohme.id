<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->paths([
		__DIR__ . '/application',
	]);

	// Aturan untuk upgrade dari PHP 7.4 ke 8.2
	$rectorConfig->sets([
		LevelSetList::UP_TO_PHP_82,
		SetList::CODE_QUALITY,
	]);
};
