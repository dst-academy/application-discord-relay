<?php

namespace App\Entity\Event;

use App\Entity\EventAbstract;

class ResurrectEvent extends EventAbstract {
    private $inflictor;

    public function getInflictor(): string
    {
        return $this->inflictor;
    }

    public function setInflictor(string $inflictor): void
    {
        $this->inflictor = $inflictor;
    }

    public function getData(): array
    {
        return parent::getData() + [
            'inflictor' => $this->getInflictor(),
        ];
    }
}
