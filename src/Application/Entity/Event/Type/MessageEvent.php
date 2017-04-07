<?php

namespace Application\Entity\Event\Type;

use Application\Entity\Event\EventAbstract;

/**
 * Class MessageEvent
 * @package Application\Entity
 */
abstract class MessageEvent extends EventAbstract
{
	/**
	 * MessageEvent constructor.
	 * @param string $time
	 * @param string $username
	 * @param string $message
	 */
	public function __construct(string $time, string $username, string $message)
	{
		$this->setProperty('time', $time);
		$this->setProperty('username', $username);
		$this->setProperty('message', $message);
	}

	/**
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->getProperty('message');
	}
}
