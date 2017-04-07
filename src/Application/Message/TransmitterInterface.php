<?php

namespace Application\Message;

use Application\Entity\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface TransmitterInterface
 * @package Application\Message
 */
interface TransmitterInterface
{
	/**
	 * @param MessageInterface $message
	 * @param string $endpoint
	 * @return ResponseInterface
	 */
	public function transmit(MessageInterface $message, string $endpoint): ResponseInterface;
}
