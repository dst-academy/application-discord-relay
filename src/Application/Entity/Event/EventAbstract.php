<?php

namespace Application\Entity\Event;

use Application\Entity\EntityAbstract;

/**
 * Class EventAbstract
 * @package Application\Entity
 */
abstract class EventAbstract extends EntityAbstract implements EventInterface
{
	public const SUBSTITUTABLES = [];

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->getProperty('username');
	}

	/**
	 * @return string
	 */
	public function getTime(): string
	{
		return $this->getProperty('time');
	}

	/**
	 * @return array
	 */
	public function getSubstitutables(): array
	{
		return static::SUBSTITUTABLES;
	}
}
