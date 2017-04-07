<?php

namespace Application\Message\Driver\Discord;

use Application\Message\FormatterAbstract;

/**
 * Class Formatter
 * @package Application\Message\Driver\Discord
 */
class Formatter extends FormatterAbstract
{
	protected const MESSAGE_DOMAIN = 'messages-discord';

	/**
	 * @return string
	 */
	protected static function getTranslationDomainForMessages(): string
	{
		return self::MESSAGE_DOMAIN;
	}
}
