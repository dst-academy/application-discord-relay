<?php

namespace Application\Entity\Event\Type;

use Application\Entity\Event\EventAbstract;

/**
 * Class PresenceEvent
 * @package Application\Entity
 */
abstract class PresenceEvent extends EventAbstract {

	/**
	 * MessageEvent constructor.
	 * @param string $time
	 * @param string $username
	 */
	public function __construct(string $time, string $username)
	{
		$this->setProperty('time', $time);
		$this->setProperty('username', $username);
	}
}
