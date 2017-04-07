<?php

namespace Application\Entity;

/**
 * Class EntityAbstract
 * @package Application\Entity
 */
abstract class EntityAbstract
{
	/**
	 * @var array
	 */
	protected $properties = [];

	/**
	 * @param string $name
	 * @param $value
	 */
	protected function setProperty(string $name, $value): void
	{
		$this->properties[$name] = $value;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	protected function getProperty(string $name)
	{
		return $this->properties[$name] ?? null;
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return $this->properties;
	}
}
