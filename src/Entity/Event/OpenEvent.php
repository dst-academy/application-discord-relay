<?php

namespace App\Entity\Event;

use App\Entity\EventAbstract;

class OpenEvent extends EventAbstract {
    public const TRANSFORMABLES = ['item' => ['name', 'description']];
    private $item;

    public function getItem(): string
    {
        return $this->item;
    }

    public function setItem(string $item): void
    {
        $this->item = $item;
    }

    public function getData(): array
    {
        return parent::getData() + [
            'item' => $this->getItem(),
        ];
    }
}
