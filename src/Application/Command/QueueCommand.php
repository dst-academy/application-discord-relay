<?php namespace Application\Command;

use Application\Entity\Event\EventInterface;
use Application\Entity\Message\MessageInterface;
use Application\Event\Factory\EventFactory;
use Application\Message\FormatterInterface;
use Application\Message\TransmitterInterface;
use InvalidArgumentException;
use Predis\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException as ConsoleInvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException as DependencyInjectionInvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Wrep\Daemonizable\Command\EndlessContainerAwareCommand;

/**
 * Class QueueCommand
 * @package Application\Command
 */
class QueueCommand extends EndlessContainerAwareCommand {

	/**
	 * @var array
	 */
	protected $hooks;

	/**
	 * @var array
	 */
	protected $keys;

	/**
	 * @var Client
	 */
	protected $queue;

	/**
	 * @var FormatterInterface
	 */
	protected $formatter;

	/**
	 * @var TransmitterInterface
	 */
	protected $transmitter;

	/**
	 * @var EventFactory
	 */
	protected $factory;

	/**
	 * @const int
	 */
	public const TIMEOUT = 0;

	/**
	 * @throws ConsoleInvalidArgumentException
	 * @throws InvalidArgumentException
	 */
	protected function configure(): void
	{
		$this
			->setName('app:queue')
			->setDescription('Reads the log-queue and dispatches requests to API web-hooks.')
			->setTimeout(0);
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @throws ServiceCircularReferenceException
	 * @throws ServiceNotFoundException
	 * @throws DependencyInjectionInvalidArgumentException
	 */
	protected function initialize(InputInterface $input, OutputInterface $output): void
	{
		# Get configuration.
		$this->hooks = $this->getContainer()->getParameter('app.discord_hooks');
		$this->keys = \array_keys($this->hooks);

		# Get services.
		$this->factory = $this->getContainer()->get('app.event.factory');
		$this->queue = $this->getContainer()->get('snc_redis.default');
		$this->formatter = $this->getContainer()->get('app.message.formatter');
		$this->transmitter = $this->getContainer()->get('app.message.transmitter');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {

		# Get next queue item.
		if([$key, $data] = $this->pop($this->keys)) {

			# Convert the queue data to an event.
			$event = $this->convert($data);

			# Format the event to a message.
			$message = $this->format($event);

			# Transmit message.
			if(!$this->transmit($key, $message)) {

				# Add message back to the queue.
				$this->push($key, $data);
			}
		}
	}

	/**
	 * @param array $keys
	 * @return array
	 */
	protected function pop(array $keys): array
	{
		return $this->queue->blpop($keys, self::TIMEOUT);
	}

	/**
	 * @param string $key
	 * @param array $data
	 * @return int
	 */
	protected function push(string $key, array $data): int
	{
		return $this->queue->lpush($key, $data);
	}

	/**
	 * @param string $data
	 * @return EventInterface
	 */
	protected function convert(string $data): EventInterface
	{
		# Decode JSON string.
		$data = \json_decode($data);

		# Instantiate event entity.
		return $this->factory->createFromLogLine($data->message);
	}

	/**
	 * @param EventInterface $event
	 * @return MessageInterface
	 */
	protected function format(EventInterface $event): MessageInterface
	{
		return $this->formatter->format($event);
	}

	/**
	 * @param string $key
	 * @param MessageInterface $message
	 * @return ResponseInterface
	 */
	protected function transmit(string $key, MessageInterface $message): ResponseInterface
	{
		# Get hook configuration.
		$hook = $this->hooks[$key];

		# Define endpoint.
		$endpoint = \implode('/', [
			$hook['id'],
			$hook['token'],
		]);

		# Transmit message to Discord.
		return $this->transmitter->transmit($message, $endpoint);
	}
}
