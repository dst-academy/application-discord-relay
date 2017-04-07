<?php

namespace Application\Entity\Message;

use Application\Entity\EntityAbstract;

/**
 * Class Message
 * @package Application\Entity\Message
 */
class Message extends EntityAbstract implements MessageInterface
{
	/**
	 * Message constructor.
	 * @param string $username
	 * @param string $content
	 */
	public function __construct(string $username, string $content)
	{
		$this->setProperty('username', $username);
		$this->setProperty('content', $content);
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->getProperty('username');
	}

	/**
	 * @return string
	 */
	public function getContent(): string
	{
		return $this->getProperty('content');
	}
}
