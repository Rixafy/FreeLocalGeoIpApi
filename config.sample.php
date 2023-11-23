<?php

/**
 * Rename this file to config.php and fill in your credentials.
 */

declare(strict_types=1);

return [
	'connection' => [
		'host' => $_ENV['DB_HOST'] ?? 'localhost',
		'port' => $_ENV['DB_PORT'] ?? 3306,
		'database' => $_ENV['DB_DATABASE'] ?? 'test',
		'user' => $_ENV['DB_USER'] ?? 'root',
		'password' => $_ENV['DB_PASSWORD'] ?? '',
	],
	'maxmind' => [
		'accountId' => $_ENV['MAXMIND_ACCOUNT_ID'] ?? 123,
		'licenseKey' => $_ENV['MAXMIND_LICENSE_KEY'] ?? 'abc',
	],
];
