<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Application\Database\DatabaseConnectionProvider;
use JetBrains\PhpStorm\NoReturn;

final class IpInfoController
{
	public function __construct(
		private readonly DatabaseConnectionProvider $connectionProvider
	) {}
	
	#[NoReturn] public function __invoke(?string $ip): void
	{
		if ($ip === null) {
			http_response_code(400);
			echo 'IP address is missing.';
			exit;
		}
		
		if (str_contains($ip, ':')) {
			$query = 'SELECT ip_locations.*, ip6_blocks.latitude, ip6_blocks.longitude, ip6_blocks.is_anonymous_proxy FROM ip6_blocks JOIN ip_locations ON ip6_blocks.geoname_id = ip_locations.geoname_id WHERE INET6_ATON(:ip) BETWEEN ip6_blocks.ip_from AND ip6_blocks.ip_to LIMIT 1';
		} else {
			$query = 'SELECT ip_locations.*, ip4_blocks.latitude, ip4_blocks.longitude, ip4_blocks.is_anonymous_proxy FROM ip4_blocks JOIN ip_locations ON ip4_blocks.geoname_id = ip_locations.geoname_id WHERE INET_ATON(:ip) BETWEEN ip4_blocks.ip_from AND ip4_blocks.ip_to LIMIT 1';
		}

		$statement = $this->connectionProvider->provide()->prepare($query);
		$statement->execute(['ip' => $ip]);
		$data = $statement->fetch();
		if ($data !== false) {
			header('Content-Type: application/json; charset=utf-8');
			
			echo json_encode([
				'continent_code' => $data['continent_code'],
				'continent_name' => $data['continent_name'],
				'country_iso_code' => $data['country_iso_code'] === '' ? null : $data['country_iso_code'],
				'country_name' => $data['country_name'] === '' ? null : $data['country_name'],
				'city_name' => $data['city_name'] === '' ? null : $data['city_name'],
				'subdivision_1_iso_code' => $data['subdivision_1_iso_code'] === '' ? null : $data['subdivision_1_iso_code'],
				'subdivision_1_name' => $data['subdivision_1_name'] === '' ? null : $data['subdivision_1_name'],
				'subdivision_2_iso_code' => $data['subdivision_2_iso_code'] === '' ? null : $data['subdivision_2_iso_code'],
				'subdivision_2_name' => $data['subdivision_2_name'] === '' ? null : $data['subdivision_2_name'],
				'metro_code' => $data['metro_code'] === 0 ? null : $data['metro_code'],
				'time_zone' => $data['time_zone'],
				'latitude' => $data['latitude'],
				'longitude' => $data['longitude'],
				'is_anonymous_proxy' => (bool) $data['is_anonymous_proxy'],
			]);
			
		} else {
			http_response_code(404);
		}
		
		exit;
	}
}
