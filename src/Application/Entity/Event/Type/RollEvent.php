<?php

namespace Application\Entity\Event\Type;

use Application\Entity\Event\EventAbstract;

/**
 * Class RollEvent
 * @package Application\Entity\Event
 */
class RollEvent extends EventAbstract
{
	/**
	 * MessageEvent constructor.
	 * @param string $time
	 * @param string $username
	 * @param int $score
	 * @param int $minimum
	 * @param int $maximum
	 */
	public function __construct(string $time, string $username, int $score, int $minimum, int $maximum)
	{
		$this->setProperty('time', $time);
		$this->setProperty('username', $username);
		$this->setProperty('score', $score);
		$this->setProperty('minimum', $minimum);
		$this->setProperty('maximum', $maximum);
	}

	/**
	 * @return int
	 */
	public function getScore(): int
	{
		return $this->getProperty('score');
	}

	/**
	 * @return int
	 */
	public function getMinimum(): int
	{
		return $this->getProperty('minimum');
	}

	/**
	 * @return int
	 */
	public function getMaximum(): int
	{
		return $this->getProperty('maximum');
	}
}
