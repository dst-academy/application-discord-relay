<?php

namespace Application\Entity\Event\Type;

use Application\Entity\Event\EventAbstract;

/**
 * Class SkinEvent
 * @package Application\Entity\Event
 */
class SkinEvent extends EventAbstract
{
	public const SUBSTITUTABLES = ['name', 'description'];

	/**
	 * SkinEvent constructor.
	 * @param string $time
	 * @param string $username
	 * @param string $name
	 * @param string $description
	 */
	public function __construct(string $time, string $username, string $name, string $description)
	{
		$this->setProperty('time', $time);
		$this->setProperty('username', $username);
		$this->setProperty('name', $name);
		$this->setProperty('description', $description);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->getProperty('name');
	}
}
