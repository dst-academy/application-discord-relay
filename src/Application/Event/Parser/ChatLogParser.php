<?php

namespace Application\Event\Parser;

use Application\Event\ParserAbstract;

/**
 * Class ChatLogParser
 * @package Application\Event\Parser
 */
class ChatLogParser extends ParserAbstract
{
	public const PATTERN = '/^\[(?<time>\d{2}\:\d{2}\:\d{2})\]:[ ](?:\[(?<type>.+)\])(?:[ ]\((?<identifier>.+)\))??(?:[ ](?<username>.+):)??[ ](?<message>.*)$/U'; # https://regex101.com/r/Mrz4TO/6

	/**
	 * @var array
	 */
	public static $patterns = [
		'Say'                    => '/^(?<message>.+)$/U',
		'Whisper'                => '/^(?<message>.+)$/U',
		'Join Announcement'      => '/^(?<username>.+)$/U', # https://regex101.com/r/wurFQB/1
		'Leave Announcement'     => '/^(?<username>.+)$/U', # https://regex101.com/r/wurFQB/1
		'Roll Announcement'      => '/^(?<username>.+)[ ](?<score>\d+)[ ]\((?<minimum>\d+)-(?<maximum>\d+)\)$/U', # https://regex101.com/r/ZVKsMR/3
		'Skin Announcement'      => '/^(?<username>.+)[ ](?<name>(?<description>[\w]+))$/U', # https://regex101.com/r/prXTLq/3
		'Resurrect Announcement' => '/^(?<username>.+) was resurrected by (?<trigger>.+)\.$/U', # https://regex101.com/r/DkG544/2
		'Death Announcement'     => '/^(?<username>.+) was killed by (?<trigger>.+)\. .+$/U', # https://regex101.com/r/svZNl5/3
	];

	/**
	 * @param string $message
	 * @return array
	 */
	public function parse(string $message): array
	{
		# Match data.
		$data = $this->match($message, self::PATTERN);

		# Match additional data.
		if ($data['message'] && ($pattern = self::$patterns[$data['type']])) {
			$data = \array_merge($data, $this->match($data['message'], $pattern));
		}

		return $data;
	}

	/**
	 * @param string $message
	 * @param string $pattern
	 * @return array
	 */
	protected function match(string $message, string $pattern): array
	{
		\preg_match($pattern, $message, $matches);

		return \array_filter($matches, function ($key) {
			return !\is_numeric($key);
		}, ARRAY_FILTER_USE_KEY);
	}
}
