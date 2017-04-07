<?php

namespace Application\Event;

/**
 * Interface ParserInterface
 * @package Application\Message
 */
interface ParserInterface
{
	/**
	 * @param string $message
	 * @return array
	 */
	public function parse(string $message): array;
}
