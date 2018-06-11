<?php

namespace App\Utility;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Transmitter
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function transmit(Message $message, string $endpoint): ResponseInterface
    {
        $request = $this->buildRequest($message, $endpoint);
        return $this->client->send($request);
    }

    private function buildRequest(Message $message, string $endpoint): RequestInterface
    {
        $payload = $this->buildPayload($message);

        return new Request('post', $endpoint, [
            'content-type' => 'application/json',
        ], \json_encode($payload));
    }

    private function buildPayload(Message $message): array
    {
        $payload = [
            'username' => $message->getEvent()->getUser(),
            'content' => $message->getContent(),
        ];

        if ($url = getenv('APP_AVATAR')) {
            $payload['avatar_url'] = $url;
        }

        return $payload;
    }
}
