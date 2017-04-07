<?php

namespace Application\Entity\Message;

/**
 * Interface MessageInterface
 * @package Application\Entity
 */
interface MessageInterface
{
	/**
	 * @return string
	 */
	public function getUsername(): string;

	/**
	 * @return string
	 */
	public function getContent(): string;
}
