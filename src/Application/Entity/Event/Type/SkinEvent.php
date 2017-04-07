<?php

namespace Application\Entity\Event\Type;

use Application\Entity\Event\EventAbstract;

/**
 * Class SkinEvent
 * @package Application\Entity\Event
 */
class SkinEvent extends EventAbstract
{
	public const SUBSTITUTABLES = ['name'];

	/**
	 * SkinEvent constructor.
	 * @param string $time
	 * @param string $username
	 * @param string $name
	 */
	public function __construct(string $time, string $username, string $name)
	{
		$this->setProperty('time', $time);
		$this->setProperty('username', $username);
		$this->setProperty('name', $name);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->getProperty('name');
	}
}
