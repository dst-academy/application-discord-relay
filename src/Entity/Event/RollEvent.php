<?php

namespace App\Entity\Event;

use App\Entity\EventAbstract;

class RollEvent extends EventAbstract {
    private $score;
    private $minimum;
    private $maximum;

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function getMinimum(): int
    {
        return $this->minimum;
    }

    public function setMinimum(int $minimum): void
    {
        $this->minimum = $minimum;
    }

    public function getMaximum(): int
    {
        return $this->maximum;
    }

    public function setMaximum(int $maximum): void
    {
        $this->maximum = $maximum;
    }

    public function getData(): array
    {
        return parent::getData() + [
            'score' => $this->getScore(),
            'minimum' => $this->getMinimum(),
            'maximum' => $this->getMaximum(),
        ];
    }
}
