<?php

namespace Application\Entity\Event\Type;

use Application\Entity\Event\EventAbstract;

/**
 * Class LifeEvent
 * @package Application\Entity
 */
abstract class LifeEvent extends EventAbstract
{
	/**
	 * LifeEvent constructor.
	 * @param string $time
	 * @param string $username
	 * @param string $trigger
	 */
	public function __construct(string $time, string $username, string $trigger)
	{
		$this->setProperty('time', $time);
		$this->setProperty('username', $username);
		$this->setProperty('trigger', $trigger);
	}

	/**
	 * @return string
	 */
	public function getTrigger(): string
	{
		return $this->getProperty('trigger');
	}
}
