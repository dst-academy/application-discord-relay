<?php

namespace App\Utility;

use App\Entity\EventInterface;

class Message
{
    private $event;
    private $content;

    public function __construct(EventInterface $event, string $content)
    {
        $this->event = $event;
        $this->content = $content;
    }

    public function getEvent(): EventInterface
    {
        return $this->event;
    }

    public function setEvent(EventInterface $event): void
    {
        $this->event = $event;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }
}
