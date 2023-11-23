<?php

declare(strict_types=1);

namespace App\MaxMind;

final class MaxMindCredentials
{
	public function __construct(
		public readonly int $accountId,
		public readonly string $licenseKey,
	) {}
}
