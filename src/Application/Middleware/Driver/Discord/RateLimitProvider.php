<?php

namespace Application\Middleware\Driver\Discord;

use Concat\Http\Middleware\RateLimitProvider as RateLimitProviderBase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RateLimitProvider
 * @package Application\Message\Middleware
 */
class RateLimitProvider implements RateLimitProviderBase
{
	/**
	 * @var int
	 */
	static protected $lastRequestTime = 0;

	/**
	 * @var int
	 */
	static protected $requestAllowance = 0;

	/**
	 * @return int
	 */
	public function getLastRequestTime(): int
	{
		return self::$lastRequestTime;
	}

	/**
	 *
	 */
	public function setLastRequestTime(): void
	{
		self::$lastRequestTime = \time();
	}

	/**
	 * @param RequestInterface $request
	 * @return int
	 */
	public function getRequestTime(RequestInterface $request): int
	{
		return \time();
	}

	/**
	 * @param RequestInterface $request
	 * @return int
	 */
	public function getRequestAllowance(RequestInterface $request): int
	{
		return self::$requestAllowance;
	}

	/**
	 * @param ResponseInterface $response
	 */
	public function setRequestAllowance(ResponseInterface $response): void
	{
		$requests = (int) ($response->getHeader('x-ratelimit-remaining')[0] ?? 0);
		$expiration = $response->getHeader('x-ratelimit-reset')[0] ?? 0;
		$timespan = $expiration - \time();

		self::$requestAllowance = $requests === 0 ? 0 : $timespan / $requests;
	}
}
