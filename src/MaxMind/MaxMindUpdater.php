<?php

declare(strict_types=1);

namespace App\MaxMind;

use App\Application\Database\DatabaseConnectionProvider;
use IPLib\Factory;
use PDO;
use RuntimeException;
use ZipArchive;

final class MaxMindUpdater
{
	private string $maxmindCityUrl = 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City-CSV&license_key={licenseKey}&suffix=zip';

	public function __construct(
		private readonly DatabaseConnectionProvider $connectionProvider,
		readonly MaxMindCredentials $credentials,
	) {
		$this->maxmindCityUrl = str_replace('{licenseKey}', $credentials->licenseKey, $this->maxmindCityUrl);
	}
	
	private function getMaxMindCacheDir(): string
	{
		return __DIR__ . '/../../tmp/maxmind';
	}

	public function update(callable $output): void
	{
		$maxmindCacheDir = $this->getMaxMindCacheDir();

		$output('<fg=yellow>■</> Downloading files...');
		$this->updateCsvFiles();

		$output('<fg=yellow>■</> Formatting IPV6 addresses...');
		$this->formatIpv6File();

		$output('<fg=yellow>■</> Preparing geoip tables...');
		
		$this->connectionProvider->provide()->query(file_get_contents(__DIR__ . '/../../resources/create_ip_blocks.sql'));
		$this->connectionProvider->provide()->query(file_get_contents(__DIR__ . '/../../resources/create_ip_locations.sql'));
		
		$output('<fg=yellow>■</> Updating geoip database...');

		$this->connectionProvider->getNewConnection()->query(str_replace(
			'{filePath}',
			$maxmindCacheDir . '/GeoLite2-City-Locations-en.csv',
			file_get_contents(__DIR__ . '/../../resources/import_locations.sql')
		));

		$this->connectionProvider->getNewConnection()->query(str_replace(
			'{filePath}', 
			$maxmindCacheDir . '/GeoLite2-City-Blocks-IPv4.csv', 
			file_get_contents(__DIR__ . '/../../resources/import_ipv4.sql')
		));

		$this->connectionProvider->getNewConnection()->query(str_replace(
			'{filePath}',
			$maxmindCacheDir . '/GeoLite2-City-Blocks-IPv6-Ranges.csv',
			file_get_contents(__DIR__ . '/../../resources/import_ipv6.sql')
		));
		
		$output('<fg=green>■</> Database updated!');
		
		$this->cleanCache();
	}

	private function updateCsvFiles(): void
	{
		$maxmindCacheDir = $this->getMaxMindCacheDir();
		
		$this->cleanCache();
		
		if (!file_exists($maxmindCacheDir)) {
			mkdir($maxmindCacheDir, 0777, true);
		}
		
		$zipFile = $maxmindCacheDir . '/maxmind.zip';
		file_put_contents($zipFile, file_get_contents($this->maxmindCityUrl));

		$zip = new ZipArchive;
		if ($zip->open($zipFile) === true) {
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$filename = $zip->getNameIndex($i);
				$fileInfo = pathinfo($filename);
				copy('zip://' . $zipFile . '#' . $filename, $maxmindCacheDir . '/' . $fileInfo['basename']);
			}
			$zip->close();
		} else {
			throw new RuntimeException('Update failed! Cannot access zip archive ' . $zipFile);
		}
	}

	private function formatIpv6File(): void
	{
		$maxmindCacheDir = $this->getMaxMindCacheDir();

		$source = $maxmindCacheDir . '/GeoLite2-City-Blocks-IPv6.csv';
		$destination = $maxmindCacheDir . '/GeoLite2-City-Blocks-IPv6-Ranges.csv';

		@unlink($destination);

		$error = false;
		if ($fpOut = fopen($destination, 'w')) {
			if ($fpIn = fopen($source, 'rb')) {
				while (($line = fgets($fpIn)) !== false) {
					$split = explode(',', $line);
					if ($split[0] === 'network') {
						continue;
					}
					$range = Factory::parseRangeString($split[0]);
					$split[0] = $range->getStartAddress()->toString() . ',' . $range->getEndAddress()->toString();
					fwrite($fpOut, implode(',', $split));
				}
				fclose($fpIn);
			} else {
				$error = true;
			}
			fclose($fpOut);
		} else {
			$error = true;
		}

		if ($error) {
			throw new RuntimeException('Formatting IPv6 file failed!');
		}

		@unlink($source);
	}
	
	private function cleanCache(): void
	{
		@array_map('unlink', glob($this->getMaxMindCacheDir() . '/*.*'));
	}
}
