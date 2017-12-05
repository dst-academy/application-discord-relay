<?php

namespace Application\Message;

use Application\Entity\Event\EventInterface;
use Application\Entity\Message\Message;
use Application\Entity\Message\MessageInterface;
use Symfony\Component\Translation\Exception\InvalidArgumentException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Formatter
 * @package Application\Message
 */
abstract class FormatterAbstract implements FormatterInterface
{
	public const TRANSLATION_DOMAIN_SKINS = 'skins';

	/**
	 * @var TranslatorInterface
	 */
	protected $translator;

	/**
	 * Formatter constructor.
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * @return string
	 */
	abstract protected static function getTranslationDomainForMessages(): string;

	/**
	 * @param EventInterface $event
	 * @throws InvalidArgumentException
	 * @return MessageInterface
	 */
	public function format(EventInterface $event): MessageInterface
	{
		# Get parameters.
		$parameters = $event->toArray();

		# Substitute parameters.
		$parameters = $this->substituteParameters($parameters, $event->getSubstitutables());

		# Prepare parameters.
		$parameters = $this->prepareParameters($parameters);

		# Format event.
		$content = $this->translator->trans(\get_class($event), $parameters, static::getTranslationDomainForMessages());

		# Create and return message.
		return new Message($event->getUsername(), $content);
	}

	/**
	 * @param array $parameters
	 * @param array $keys
	 * @throws InvalidArgumentException
	 * @return array
	 */
	protected function substituteParameters(array $parameters, array $keys): array
	{
		# Substitute parameters.
		foreach($keys as $key) {
			$parameters[$key] = $this->translator->trans($parameters[$key].'.'.$key, [], static::TRANSLATION_DOMAIN_SKINS);
		}

		return $parameters;
	}

	/**
	 * @param array $parameters
	 * @return array
	 */
	protected function prepareParameters(array $parameters): array
	{
		# Build localization parameters.
		return \array_combine(
			\array_map(function($key) {
				return '%' . $key . '%';
			}, \array_keys($parameters)),
			$parameters
		);
	}
}
