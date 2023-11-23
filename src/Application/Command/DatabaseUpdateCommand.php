<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\MaxMind\MaxMindUpdater;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:database:update')]
final class DatabaseUpdateCommand extends Command
{
	public function __construct(
		private readonly MaxMindUpdater $updater,
	) {
		parent::__construct();
	}
	
	public function execute(InputInterface $input, OutputInterface $output): int
	{
		$this->updater->update(fn(string $content) => $output->writeln($content));
		
		return Command::SUCCESS;
	}
}
