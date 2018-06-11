<?php

namespace App\Entity;

interface EventInterface {
    public function getType(): string;
    public function getUser(): string;
    public function getTime(): string;
    public function getSubstitutables(): array;
    public function getTransformables(): array;
    public function getData(): array;
}
