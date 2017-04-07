<?php

namespace Application\Event\Factory;

use Application\Entity\Event\Type\DieEvent;
use Application\Entity\Event\Type\JoinEvent;
use Application\Entity\Event\Type\LeaveEvent;
use Application\Entity\Event\Type\ResurrectEvent;
use Application\Entity\Event\Type\RollEvent;
use Application\Entity\Event\Type\SayEvent;
use Application\Entity\Event\Type\SkinEvent;
use Application\Entity\Event\Type\WhisperEvent;
use Application\Entity\Event\EventInterface;
use Application\Event\FactoryInterface;
use Application\Event\ParserInterface;
use ReflectionClass;
use ReflectionParameter;

/**
 * Class EventFactory
 * @package Application\Event\Factory
 */
class EventFactory implements FactoryInterface
{
	/**
	 * @var ParserInterface
	 */
	private $parser;

	/**
	 * @var array
	 */
	private static $types = [
		'Say'                    => SayEvent::class,
		'Whisper'                => WhisperEvent::class,
		'Join Announcement'      => JoinEvent::class,
		'Leave Announcement'     => LeaveEvent::class,
		'Roll Announcement'      => RollEvent::class,
		'Skin Announcement'      => SkinEvent::class,
		'Resurrect Announcement' => ResurrectEvent::class,
		'Death Announcement'     => DieEvent::class,
	];

	/**
	 * EventFactory constructor.
	 * @param ParserInterface $parser
	 */
	public function __construct(ParserInterface $parser)
	{
		$this->parser = $parser;
	}

	/**
	 * @param string $message
	 * @return EventInterface
	 */
	public function createFromLogLine(string $message): EventInterface
	{
		# Parse message.
		$data = $this->parser->parse($message);

		# Get class.
		$class = self::$types[$data['type']];

		# Get parameters.
		$arguments = $this->getArgumentsForClass($class, $data);

		# Instantiate and return the final event.
		return new $class(...$arguments);
	}

	/**
	 * @param string $class
	 * @param array $data
	 * @return array
	 */
	protected function getArgumentsForClass(string $class, array $data): array
	{
		# Instantiate reflection class.
		$reflection = new ReflectionClass($class);

		# Get constructor parameters.
		$parameters = $reflection->getConstructor()->getParameters();

		# Map arguments to an array.
		$arguments = \array_map(function (ReflectionParameter $parameter) use($data) {
			return $data[$parameter->getName()] ?? null;
		}, $parameters);

		# Return final arguments.
		return $arguments;
	}
}
