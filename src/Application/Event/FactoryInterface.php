<?php

namespace Application\Event;

use Application\Entity\Event\EventInterface;

/**
 * Interface FactoryInterface
 * @package Application\Event
 */
interface FactoryInterface
{
	/**
	 * @param string $message
	 * @return EventInterface
	 */
	public function createFromLogLine(string $message): EventInterface;
}
