<?php

namespace Application\Message\Driver\Discord;

use Application\Entity\Message\MessageInterface;
use Application\Message\TransmitterAbstract;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Transmitter
 * @package Application\Message\Driver\Discord
 */
class Transmitter extends TransmitterAbstract
{
	/**
	 * @param MessageInterface $message
	 * @param string $endpoint
	 * @return ResponseInterface
	 * @throws GuzzleException
	 */
	public function transmit(MessageInterface $message, string $endpoint): ResponseInterface
	{
		# Build request.
		$request = $this->buildRequest($message, $endpoint);

		# Send request to the Discord API.
		return $this->client->send($request);
	}

	/**
	 * @param MessageInterface $message
	 * @param string $endpoint
	 * @return RequestInterface
	 */
	protected function buildRequest(MessageInterface $message, string $endpoint): RequestInterface
	{
		# Define payload.
		$payload = [
			'username' => $message->getUsername(),
			'content' => $message->getContent(),
		];

		# Create and return request.
		return new Request('POST', $endpoint, [
			'content-type' => 'application/json',
		], \json_encode($payload));
	}
}
