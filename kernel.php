<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Application\Command\DatabaseUpdateCommand;
use App\Application\Controller\IpInfoController;
use App\Application\Database\DatabaseConnectionProvider;
use App\MaxMind\MaxMindCredentials;
use App\MaxMind\MaxMindUpdater;
use Symfony\Component\Console\Application;

$config = include __DIR__ . '/config.php';

$connectionProvider = new DatabaseConnectionProvider($config['connection']);

if (php_sapi_name() === 'cli') {
	$application = new Application();
	
	$application->add(new DatabaseUpdateCommand(new MaxMindUpdater($connectionProvider, new MaxMindCredentials(
		(int) $config['maxmind']['accountId'],
		$config['maxmind']['licenseKey'],
	))));
	
	$application->setDefaultCommand(DatabaseUpdateCommand::getDefaultName());
	
	/** @noinspection PhpUnhandledExceptionInspection */
	exit($application->run());
	
} else {
	(new IpInfoController($connectionProvider))($_GET['ip'] ?? null);
}
