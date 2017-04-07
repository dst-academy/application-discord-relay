<?php

namespace Application\Entity\Event;

/**
 * Class EventInterface
 * @package Application\Entity
 */
interface EventInterface
{
	/**
	 * @return string
	 */
	public function getTime(): string;

	/**
	 * @return string
	 */
	public function getUsername(): string;

	/**
	 * @return array
	 */
	public function getSubstitutables(): array;

	/**
	 * @return array
	 */
	public function toArray(): array;
}
