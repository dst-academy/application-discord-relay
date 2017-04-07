<?php

namespace Application\Message;

use Application\Entity\Event\EventInterface;
use Application\Entity\Message\MessageInterface;

/**
 * Interface FormatterInterface
 * @package Application\Message
 */
interface FormatterInterface
{
	/**
	 * @param EventInterface $event
	 * @return MessageInterface
	 */
	public function format(EventInterface $event): MessageInterface;
}
