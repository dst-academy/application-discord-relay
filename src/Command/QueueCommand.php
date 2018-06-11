<?php

namespace App\Command;

use App\Entity\EventInterface;
use App\Utility\Converter;
use App\Utility\Formatter;
use App\Utility\Message;
use App\Utility\Transmitter;
use Predis\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wrep\Daemonizable\Command\EndlessCommand;

class QueueCommand extends EndlessCommand
{
    public static $defaultName = 'app:queue';
    public static $defaultDescription = 'Read the log-queue and dispatch requests to API web-hooks.';

    private $queue;
    private $converter;
    private $formatter;
    private $transmitter;
    private $mapping;
    private $events;

    public function __construct(
        ClientInterface $queue,
        Converter $converter,
        Formatter $formatter,
        Transmitter $transmitter,
        array $mapping,
        array $events
    ) {
        $this->queue = $queue;
        $this->converter = $converter;
        $this->formatter = $formatter;
        $this->transmitter = $transmitter;
        $this->mapping = $mapping;
        $this->events = \array_filter($events);

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription(self::$defaultDescription)
            ->setTimeout(0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $item = $this->retrieve();
        $event = $this->convert($item['data']);

        if ($this->filter($event)) {
            $message = $this->transform($event);
            $this->transmit($item['key'], $message);
        }
    }

    private function retrieve(): array
    {
        $keys = \array_keys($this->mapping);
        return $this->queue->blpop($keys, 0);
    }

    private function filter(EventInterface $event): bool
    {
        return empty($this->events) || \in_array($event->getType(), $this->events);
    }

    private function convert(string $data): EventInterface
    {
        return $this->converter->convert($data);
    }

    private function transform(EventInterface $event): Message
    {
        $message = $this->formatter->format($event);
        return new Message($event, $message);
    }

    private function transmit(string $key, Message $message): ResponseInterface
    {
        $endpoint = $this->mapping[$key];
        return $this->transmitter->transmit($message, $endpoint);
    }
}
