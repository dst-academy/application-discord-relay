<?php

namespace Application\Message;
use GuzzleHttp\ClientInterface;

/**
 * Class Transmitter
 * @package Application\Message
 */
abstract class TransmitterAbstract implements TransmitterInterface
{
	/**
	 * @var ClientInterface
	 */
	protected $client;

	/**
	 * Transmitter constructor.
	 * @param ClientInterface $client
	 */
	public function __construct(ClientInterface $client)
	{
		$this->client = $client;
	}
}
