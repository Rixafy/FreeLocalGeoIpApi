<?php

declare(strict_types=1);

namespace App\Application\Database;

use PDO;

final class DatabaseConnectionProvider
{
	private ?PDO $connection = null;
	
	public function __construct(
		private readonly array $config,
	) {}
	
	public function provide(): PDO
	{
		return $this->connection ??= $this->getNewConnection();
	}

	public function getNewConnection(): PDO
	{
		return new PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=UTF8', $this->config['host'], (int) $this->config['port'], $this->config['database']), $this->config['user'], $this->config['password'], [
			PDO::MYSQL_ATTR_LOCAL_INFILE => true,
			PDO::MYSQL_ATTR_LOCAL_INFILE_DIRECTORY => __DIR__ . '/../../../resources',
		]);
	}
}
