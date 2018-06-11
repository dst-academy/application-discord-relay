<?php

namespace App\Utility;

use Concat\Http\Middleware\RateLimitProvider as RateLimitProviderBase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Limiter implements RateLimitProviderBase
{
	static protected $lastRequestTime = 0;
	static protected $requestAllowance = 0;

	public function getLastRequestTime(): int
	{
		return self::$lastRequestTime;
	}

	public function setLastRequestTime(): void
	{
		self::$lastRequestTime = \time();
	}

	public function getRequestTime(RequestInterface $request): int
	{
		return \time();
	}

	public function getRequestAllowance(RequestInterface $request): int
	{
		return self::$requestAllowance;
	}

	public function setRequestAllowance(ResponseInterface $response): void
	{
		$requests = (int) ($response->getHeader('x-ratelimit-remaining')[0] ?? 0);
		$expiration = $response->getHeader('x-ratelimit-reset')[0] ?? 0;
		$timespan = $expiration - \time();

		self::$requestAllowance = $requests === 0 ? 0 : $timespan / $requests;
	}
}
