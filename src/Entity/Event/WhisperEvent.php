<?php

namespace App\Entity\Event;

use App\Entity\EventAbstract;

class WhisperEvent extends EventAbstract {
    public const SUBSTITUTABLES = ['message'];

    private $message;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getData(): array
    {
        return parent::getData() + [
            'message' => $this->getMessage(),
        ];
    }
}
