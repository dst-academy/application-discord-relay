<?php

namespace App\Entity;

abstract class EventAbstract implements EventInterface {
    public const SUBSTITUTABLES = [];
    public const TRANSFORMABLES = [];

    private $type;
    private $time;
    private $user;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): void
    {
        $this->time = $time;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    public function getSubstitutables(): array
    {
        return static::SUBSTITUTABLES;
    }

    public function getTransformables(): array
    {
        return static::TRANSFORMABLES;
    }

    public function getData(): array
    {
        return [
            'type' => $this->getType(),
            'time' => $this->getTime(),
            'user' => $this->getUser(),
        ];
    }
}
