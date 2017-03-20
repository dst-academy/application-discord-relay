<?php namespace Application\Command;

use Predis\Client;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
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
	protected $buffer;

	/**
	 * @var
	 */
	protected $queue;

	/**
	 * @var \GuzzleHttp\Client
	 */
	protected $api;

	/**
	 * @var array
	 */
	public static $emoji = [
		'Join Announcement' => 'white_check_mark',
		'Leave Announcement' => 'negative_squared_cross_mark',
		'Roll Announcement' => 'game_die',
		'Skin Announcement' => 'kimono',
		'Resurrect Announcement' => 'heartpulse',
		'Death Announcement' => 'skull',
	];

	/**
	 * @var array
	 */
	public static $color = [
		'Join Announcement' => 1752220, # aqua
		'Leave Announcement' => 15158332, #red
		'Roll Announcement' => 10181046, # purple
		'Skin Announcement' => 15844367, # gold
		'Resurrect Announcement' => 3066993, # green
		'Death Announcement' => 10181046, # dark red
	];

	/**
	 *
	 */
	const BUFFER_TIMEOUT = 1;

	/**
	 *
	 */
	protected function configure() {
		$this
			->setName('app:queue')
			->setDescription('Reads the DST log-buffer and dispatches requests to Discord webhooks.')
			->setTimeout(0);
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function initialize(InputInterface $input, OutputInterface $output) {

		# Get configuration.
		$this->hooks = $this->getContainer()->getParameter('app.discord_hooks');
		$this->keys = array_keys($this->hooks);

		# Get services.
		$this->buffer = $this->queue = $this->getContainer()->get('snc_redis.default');
		$this->api = $this->getContainer()->get('guzzle.client.discord');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {

		# Get next buffer entry.
		if($entry = $this->fetch()) {

			# Get message.
			[$key, $data] = $entry;

			# Process buffer entry.
			$message = $this->process($data);

			# Transmit message to Discord.
			$this->transmit($key, $message);
		}
	}

	/**
	 * @return string|null
	 */
	private function fetch() {
		return $this->buffer->blpop($this->keys, self::BUFFER_TIMEOUT);
	}

	/**
	 * @param string $data
	 * @return array
	 */
	private function process(string $data): array
	{
		# Convert data string to generic array.
		$data = $this->convert($data);

		# Create API-friendly request object.
		$data = $this->format($data);

		# Return message.
		return $data;
	}

	/**
	 * @param string $data
	 * @return array
	 */
	private function convert(string $data): array
	{
		# Decode JSON string.
		$data = json_decode($data, true);

		# Format message.
		# https://regex101.com/r/Mrz4TO/4
		preg_match('/^\[(\d{2}\:\d{2}\:\d{2})\]:[ ](?:\[(.+)\])(?:[ ]\((.+)\))?(?:[ ](.+):)?[ ](.*)$/x', $data['message'], $matches);

		# Create generic log array.
		$message = [
			'time' => $matches[1],
			'event' => $matches[2],
			'identifier' => $matches[3],
			'username' => $matches[4],
			'message' => $matches[5],
		];

		# Return the generic message array.
		return $message;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	private function format(array $data): array
	{
		# Pre-set message array.
		$message = [
			'username' => 'Server',
			'embeds' => [],
		];

		# Format message according to the event type.
		if (strpos($data['event'], 'Announcement') !== false) {
			$message['embeds'][] = [
				'color' => $this->color($data['event']),
				'title' => $data['event'] . ' ' . $this->emoji($data['event']),
				'description' => $data['message'],
			];
		} else {
			$message['username'] = $data['username'];
			$message['content'] = $data['message'];
		}

		# Return an API-friendly message array.
		return $message;
	}

	/**
	 * @param string $event
	 * @return string
	 */
	private function emoji(string $event): string
	{
		return array_key_exists($event, self::$emoji) ? ':' . self::$emoji[$event] . ':' : '';
	}

	/**
	 * @param string $event
	 * @return string
	 */
	private function color(string $event): string
	{
		return array_key_exists($event, self::$color) ? self::$color[$event] : 0;
	}

	/**
	 * @param string $key
	 * @param array $message
	 * @return void
	 */
	private function transmit(string $key, array $message) {

		# Get web-hook configuration.
		$hook = $this->hooks[$key];

		# Build web-hook endpoint path.
		$path = $hook['id'] . '/' . $hook['token'];

		# Send request to the Discord API.
		$this->api->post($path, [
			'json' => $message,
		]);
	}
}
